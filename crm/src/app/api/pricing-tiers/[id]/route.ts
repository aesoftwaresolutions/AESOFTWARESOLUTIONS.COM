import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { PricingTier } from "@/lib/db/models";

export async function PATCH(
  req: NextRequest,
  { params }: { params: { id: string } }
) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();

  const tier = await PricingTier.findByIdAndUpdate(params.id, body, { new: true });

  if (!tier) {
    return NextResponse.json({ error: "Tier not found" }, { status: 404 });
  }

  return NextResponse.json(tier);
}

export async function DELETE(
  req: NextRequest,
  { params }: { params: { id: string } }
) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const tier = await PricingTier.findByIdAndDelete(params.id);

  if (!tier) {
    return NextResponse.json({ error: "Tier not found" }, { status: 404 });
  }

  return NextResponse.json({ success: true });
}
