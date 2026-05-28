import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SocialAccount, AuditLog } from "@/lib/db/models";
import { getAdapter, encryptToken, decryptToken, getOAuthRedirectUri } from "@/lib/social";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const accounts = await SocialAccount.find({ isActive: true })
    .select("-accessToken -refreshToken") // never expose tokens
    .populate("connectedBy", "name")
    .sort({ platform: 1 })
    .lean();

  return NextResponse.json({ accounts });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  const body = await req.json();
  const { platform } = body;

  if (!platform) return NextResponse.json({ error: "platform is required" }, { status: 400 });

  const adapter = getAdapter(platform);
  const redirectUri = getOAuthRedirectUri(platform);
  const state = Buffer.from(JSON.stringify({ platform, userId: session.user.id })).toString("base64url");
  const authUrl = adapter.getAuthUrl(redirectUri, state);

  return NextResponse.json({ authUrl });
}

export async function DELETE(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  const { id } = await req.json();
  if (!id) return NextResponse.json({ error: "id required" }, { status: 400 });

  await connectDB();
  await SocialAccount.findByIdAndUpdate(id, { isActive: false });

  AuditLog.create({
    action: "social_account.disconnected",
    resource: "social_account",
    resourceId: id,
    userId: session.user.id,
    userName: session.user.name,
  }).catch(console.error);

  return NextResponse.json({ success: true });
}
