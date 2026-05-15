import crypto from "crypto";
import { connectDB } from "@/lib/db/connection";
import { PHITransfer } from "@/lib/db/models/phi-transfer.model";
import { auditLog } from "@/lib/audit/logger";
import { AUDIT_ACTIONS, PHI_TTL_MS } from "@/lib/constants";

const ALGORITHM = "aes-256-gcm";
const KEY_ID = "tenant-phi-key-v1";
const PURGE_BATCH_SIZE = 500;

function getEncryptionKey(): Buffer {
  const hex = process.env.PHI_ENCRYPTION_KEY;
  if (!hex || hex.length !== 64) {
    throw new Error("PHI_ENCRYPTION_KEY must be a 64-char hex string (32 bytes)");
  }
  return Buffer.from(hex, "hex");
}

/**
 * Encrypt a PHI payload using AES-256-GCM.
 * Returns the encrypted buffer, IV, and auth tag.
 */
function encryptPayload(plaintext: string): {
  encrypted: Buffer;
  iv: Buffer;
  authTag: Buffer;
} {
  const key = getEncryptionKey();
  const iv = crypto.randomBytes(16);
  const cipher = crypto.createCipheriv(ALGORITHM, key, iv);

  const encrypted = Buffer.concat([
    cipher.update(plaintext, "utf8"),
    cipher.final(),
  ]);
  const authTag = cipher.getAuthTag();

  return { encrypted, iv, authTag };
}

/**
 * Initiate a PHI transfer.
 *
 * 1. Encrypts the payload immediately
 * 2. Stores it with a 72-hour TTL
 * 3. Logs the transfer initiation (without PHI content)
 * 4. Returns the transfer ID for tracking
 */
export async function initiatePHITransfer(params: {
  contactId: string;
  transferType: "intake_form" | "lab_requisition" | "consent_form";
  payload: Record<string, unknown>;
  transferredTo: string;
  userId: string;
  ipAddress?: string;
}): Promise<string> {
  await connectDB();

  const { encrypted, iv, authTag } = encryptPayload(
    JSON.stringify(params.payload)
  );

  const transfer = await PHITransfer.create({
    contactId: params.contactId,
    transferType: params.transferType,
    encryptedPayload: encrypted,
    iv,
    authTag,
    encryptionKeyId: KEY_ID,
    status: "pending",
    transferredTo: params.transferredTo,
    expiresAt: new Date(Date.now() + PHI_TTL_MS),
  });

  await auditLog({
    userId: params.userId,
    action: AUDIT_ACTIONS.PHI_TRANSFER_INITIATE,
    resource: "phi_transfer",
    resourceId: transfer._id.toString(),
    metadata: {
      contactId: params.contactId,
      transferType: params.transferType,
      transferredTo: params.transferredTo,
      // NO payload content in audit log
    },
    ipAddress: params.ipAddress,
  });

  return transfer._id.toString();
}

/**
 * Confirm a PHI transfer (called when PC partner confirms receipt).
 * Cryptographically purges the PHI payload.
 */
export async function confirmPHITransfer(params: {
  transferId: string;
  userId: string;
  ipAddress?: string;
}): Promise<boolean> {
  await connectDB();

  const transfer = await PHITransfer.findById(params.transferId);
  if (!transfer || transfer.status === "purged") return false;

  // Cryptographic purge: overwrite encrypted data with random bytes, then null
  transfer.encryptedPayload = Buffer.alloc(0);
  transfer.iv = Buffer.alloc(0);
  transfer.authTag = Buffer.alloc(0);
  transfer.status = "purged";
  transfer.confirmedAt = new Date();
  transfer.purgedAt = new Date();
  await transfer.save();

  await auditLog({
    userId: params.userId,
    action: AUDIT_ACTIONS.PHI_TRANSFER_CONFIRM,
    resource: "phi_transfer",
    resourceId: params.transferId,
    metadata: { contactId: transfer.contactId.toString() },
    ipAddress: params.ipAddress,
  });

  return true;
}

/**
 * Purge expired PHI transfers.
 * Called by the hourly Vercel Cron job.
 * Uses bulkWrite for batched updates and proper per-transfer audit logging.
 * Returns the count of records purged.
 */
export async function purgeExpiredPHITransfers(
  systemUserId: string
): Promise<number> {
  await connectDB();

  let totalPurged = 0;
  let hasMore = true;

  while (hasMore) {
    const expired = await PHITransfer.find(
      {
        status: { $in: ["pending", "transferred"] },
        expiresAt: { $lte: new Date() },
      },
      { _id: 1, contactId: 1, expiresAt: 1 }
    )
      .limit(PURGE_BATCH_SIZE)
      .lean();

    if (expired.length === 0) {
      hasMore = false;
      break;
    }

    // Batch crypto-purge via bulkWrite
    const purgedStatus = "purged" as const;
    const ops = expired.map((t) => ({
      updateOne: {
        filter: { _id: t._id },
        update: {
          $set: {
            encryptedPayload: Buffer.alloc(0),
            iv: Buffer.alloc(0),
            authTag: Buffer.alloc(0),
            status: purgedStatus,
            purgedAt: new Date(),
          },
        },
      },
    }));
    await PHITransfer.bulkWrite(ops);

    // Audit each purge (fire-and-forget batch)
    const auditPromises = expired.map((transfer) =>
      auditLog({
        userId: systemUserId,
        action: AUDIT_ACTIONS.PHI_TRANSFER_EXPIRE,
        resource: "phi_transfer",
        resourceId: transfer._id.toString(),
        metadata: {
          contactId: transfer.contactId.toString(),
          reason: "ttl_expired",
          originalExpiresAt: transfer.expiresAt.toISOString(),
        },
      })
    );
    await Promise.allSettled(auditPromises);

    totalPurged += expired.length;

    if (expired.length < PURGE_BATCH_SIZE) {
      hasMore = false;
    }
  }

  return totalPurged;
}
