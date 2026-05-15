import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { PricingTier } from "@/lib/db/models";

export async function GET() {
  await connectDB();

  const tiers = await PricingTier.find()
    .sort({ sortOrder: 1, createdAt: 1 })
    .lean();

  return NextResponse.json(tiers);
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();
  const body = await req.json();

  const tier = await PricingTier.create(body);

  return NextResponse.json(tier, { status: 201 });
}
