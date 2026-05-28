import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SocialAccount } from "@/lib/db/models";
import { getAdapter, decryptToken } from "@/lib/social";

export async function GET(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const account = await SocialAccount.findById(params.id)
    .select("-accessToken -refreshToken")
    .lean();
  if (!account) return NextResponse.json({ error: "Not found" }, { status: 404 });

  return NextResponse.json(account);
}

// Refresh the access token for an account
export async function POST(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const account = await SocialAccount.findById(params.id);
  if (!account) return NextResponse.json({ error: "Not found" }, { status: 404 });

  if (!account.refreshToken) {
    return NextResponse.json({ error: "No refresh token available — reconnect the account" }, { status: 400 });
  }

  try {
    const adapter = getAdapter(account.platform);
    const plainRefresh = decryptToken(account.refreshToken);
    const { accessToken, refreshToken, expiresIn } = await adapter.refreshAccessToken(plainRefresh);

    const { encryptToken } = await import("@/lib/social");
    const update: Record<string, unknown> = { accessToken: encryptToken(accessToken) };
    if (refreshToken) update.refreshToken = encryptToken(refreshToken);
    if (expiresIn) update.tokenExpiry = new Date(Date.now() + expiresIn * 1000);

    await SocialAccount.findByIdAndUpdate(params.id, update);
    return NextResponse.json({ success: true, message: "Token refreshed successfully" });
  } catch (err: any) {
    return NextResponse.json({ error: err.message }, { status: 500 });
  }
}

export async function DELETE(req: NextRequest, { params }: { params: { id: string } }) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  await SocialAccount.findByIdAndUpdate(params.id, { isActive: false });
  return NextResponse.json({ success: true });
}
