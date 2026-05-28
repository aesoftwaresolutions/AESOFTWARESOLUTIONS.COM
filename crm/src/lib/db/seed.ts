import { setServers } from "dns";
import mongoose from "mongoose";
import bcrypt from "bcryptjs";
import { tenantConfig } from "../tenant/tenant.config";

setServers(["8.8.8.8", "1.1.1.1"]);

const MONGODB_URI = process.env.MONGODB_URI || "";

async function seed() {
  await mongoose.connect(MONGODB_URI);
  console.log("Connected to MongoDB");

  const { User } = await import("./models/user.model");
  const { Contact } = await import("./models/contact.model");
  const { Opportunity } = await import("./models/opportunity.model");
  const { Task } = await import("./models/task.model");
  const { Appointment } = await import("./models/appointment.model");
  const { Subscription } = await import("./models/subscription.model");
  const { Order } = await import("./models/order.model");

  const { adminName, adminEmail, adminPassword } = tenantConfig.seed;

  // Check if admin already exists
  const existing = await User.findOne({ email: adminEmail });
  if (existing) {
    console.log(`Admin (${adminEmail}) already exists`);
    await mongoose.disconnect();
    return;
  }

  const hashedPassword = await bcrypt.hash(adminPassword, 12);

  await User.create({
    name: adminName,
    email: adminEmail,
    password: hashedPassword,
    role: "superadmin",
    isActive: true,
  });

  console.log(`Admin created: ${adminEmail} (${adminName})`);
  console.log(`Tenant: ${tenantConfig.brand.name} — ${tenantConfig.brand.tagline}`);
  await mongoose.disconnect();
}

seed().catch((err) => {
  console.error("Seed error:", err);
  process.exit(1);
});
