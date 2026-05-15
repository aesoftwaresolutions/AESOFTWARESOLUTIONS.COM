import { NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { Contact, Opportunity, Appointment, Task, Subscription, Order } from "@/lib/db/models";

export async function GET() {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  await connectDB();

  const now = new Date();
  const thirtyDaysAgo = new Date(now.getTime() - 30 * 86400000);

  // Calculate 7-day contact trend
  const contactsTrend = [];
  const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  for (let i = 6; i >= 0; i--) {
    const date = new Date(now.getTime() - i * 86400000);
    const dayStart = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    const dayEnd = new Date(date.getFullYear(), date.getMonth(), date.getDate() + 1);
    const count = await Contact.countDocuments({ createdAt: { $gte: dayStart, $lt: dayEnd } });
    contactsTrend.push({ date: dayNames[date.getDay()], count });
  }

  const [
    totalContacts,
    newContactsThisMonth,
    activePatients,
    contactsByStatus,
    pipelineByStage,
    upcomingAppointments,
    openTasks,
    urgentTasks,
    activeSubs,
    totalOrders,
    recentActivity,
  ] = await Promise.all([
    Contact.countDocuments(),
    Contact.countDocuments({ createdAt: { $gte: thirtyDaysAgo } }),
    Contact.countDocuments({ status: "treatment_active" }),
    Contact.aggregate([{ $group: { _id: "$status", count: { $sum: 1 } } }]),
    Opportunity.aggregate([{ $group: { _id: "$stage", count: { $sum: 1 }, value: { $sum: "$value" } } }]),
    Appointment.countDocuments({ status: { $in: ["scheduled", "confirmed"] }, scheduledAt: { $gte: now } }),
    Task.countDocuments({ status: { $in: ["todo", "in_progress"] } }),
    Task.countDocuments({ status: { $in: ["todo", "in_progress"] }, priority: "urgent" }),
    Subscription.find({ status: "active" }).lean(),
    Order.countDocuments(),
    Contact.find().sort("-createdAt").limit(5).select("firstName lastName status createdAt").lean(),
  ]);

  const mrr = activeSubs.reduce((sum, s) => sum + s.priceInCents, 0);
  const arr = mrr * 12;

  return NextResponse.json({
    totalContacts,
    newContactsThisMonth,
    activePatients,
    contactsByStatus: Object.fromEntries(contactsByStatus.map((s) => [s._id, s.count])),
    pipeline: Object.fromEntries(pipelineByStage.map((s) => [s._id, { count: s.count, value: s.value }])),
    upcomingAppointments,
    openTasks,
    urgentTasks,
    mrr,
    arr,
    activeSubscriptions: activeSubs.length,
    totalOrders,
    recentActivity,
    contactsTrend,
  });
}
