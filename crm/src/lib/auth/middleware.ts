import { type NextRequest, NextResponse } from "next/server";
import { auth } from "@/lib/auth/config";
import { hasPermission, type Resource, type Operation } from "@/lib/auth/permissions";
import type { Role } from "@/lib/constants";

/**
 * Get the authenticated session from an API route.
 * Returns null if not authenticated.
 */
export async function getServerSession() {
  return auth();
}

/**
 * Require authentication in an API route.
 * Returns the session or a 401 response.
 */
export async function requireAuth() {
  const session = await auth();
  if (!session?.user) {
    return {
      session: null,
      error: NextResponse.json({ error: "Unauthorized" }, { status: 401 }),
    };
  }
  return { session, error: null };
}

/**
 * Require a specific permission in an API route.
 * Returns the session or an appropriate error response.
 */
export async function requirePermission(
  resource: Resource,
  operation: Operation
) {
  const { session, error } = await requireAuth();
  if (error) return { session: null, error };

  const role = session!.user.role as Role;
  if (!hasPermission(role, resource, operation)) {
    return {
      session: null,
      error: NextResponse.json(
        { error: "Forbidden: insufficient permissions" },
        { status: 403 }
      ),
    };
  }
  return { session: session!, error: null };
}

/**
 * Extract client IP from request headers (Vercel-aware).
 */
export function getClientIP(request: NextRequest): string {
  return (
    request.headers.get("x-forwarded-for")?.split(",")[0]?.trim() ??
    request.headers.get("x-real-ip") ??
    "unknown"
  );
}

/**
 * Verify a shared secret for webhook authentication.
 */
export function verifyWebhookSecret(
  request: NextRequest,
  secret: string
): boolean {
  const authHeader = request.headers.get("authorization");
  if (!authHeader) return false;
  const [scheme, token] = authHeader.split(" ");
  return scheme === "Bearer" && token === secret;
}

/**
 * Verify Vercel Cron secret.
 */
export function verifyCronSecret(request: NextRequest): boolean {
  const authHeader = request.headers.get("authorization");
  return authHeader === `Bearer ${process.env.CRON_SECRET}`;
}
