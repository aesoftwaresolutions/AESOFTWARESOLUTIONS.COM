import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Campaign, AuditLog } from "@/lib/db/models";

export async function GET(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const url = new URL(req.url);
  const page = parseInt(url.searchParams.get("page") || "1");
  const limit = parseInt(url.searchParams.get("limit") || "20");
  const status = url.searchParams.get("status");
  const type = url.searchParams.get("type");
  const search = url.searchParams.get("search");

  const filter: Record<string, unknown> = {};
  if (status && status !== "all") filter.status = status;
  if (type && type !== "all") filter.type = type;
  if (search) {
    filter.$or = [
      { name: { $regex: search, $options: "i" } },
      { description: { $regex: search, $options: "i" } },
      { utmCampaign: { $regex: search, $options: "i" } },
    ];
  }

  const [campaigns, total] = await Promise.all([
    Campaign.find(filter)
      .sort({ createdAt: -1 })
      .skip((page - 1) * limit)
      .limit(limit)
      .populate("createdBy", "name")
      .lean(),
    Campaign.countDocuments(filter),
  ]);

  return NextResponse.json({ campaigns, total, page, limit, pages: Math.ceil(total / limit) });
}

export async function POST(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const body = await req.json();
  const { name, type, status, description, budget, startDate, endDate, utmSource, utmMedium, utmCampaign, tags, targetAudience } = body;

  if (!name || !type) {
    return NextResponse.json({ error: "name and type are required" }, { status: 400 });
  }

  const campaign = await Campaign.create({
    name,
    type,
    status: status || "draft",
    description,
    budget,
    startDate,
    endDate,
    utmSource,
    utmMedium,
    utmCampaign,
    tags: tags || [],
    targetAudience,
    createdBy: session.user.id,
  });

  AuditLog.create({
    action: "campaign.created",
    resource: "campaign",
    resourceId: campaign._id.toString(),
    userId: session.user.id,
    userName: session.user?.name,
    details: { name, type },
  }).catch(console.error);

  return NextResponse.json(campaign, { status: 201 });
}
