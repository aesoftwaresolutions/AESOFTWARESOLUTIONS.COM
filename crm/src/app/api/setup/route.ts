import { NextResponse } from "next/server";
import bcrypt from "bcryptjs";
import { connectDB } from "@/lib/db/connection";
import { User } from "@/lib/db/models";

/**
 * POST /api/setup
 *
 * Creates the initial admin account. Only works if no users exist yet.
 * This replaces the CLI `npm run seed` command with a browser-based setup flow.
 */
export async function POST(req: Request) {
  try {
    await connectDB();

    // Safety: only allow setup if no users exist
    const userCount = await User.countDocuments();
    if (userCount > 0) {
      return NextResponse.json(
        { error: "Setup already completed. Users already exist in the database." },
        { status: 403 }
      );
    }

    const body = await req.json();
    const { name, email, password } = body;

    if (!name || !email || !password) {
      return NextResponse.json(
        { error: "Name, email, and password are required." },
        { status: 400 }
      );
    }

    if (password.length < 8) {
      return NextResponse.json(
        { error: "Password must be at least 8 characters." },
        { status: 400 }
      );
    }

    const hashedPassword = await bcrypt.hash(password, 12);

    const user = await User.create({
      name,
      email: email.toLowerCase().trim(),
      password: hashedPassword,
      role: "superadmin",
      isActive: true,
    });

    return NextResponse.json({
      success: true,
      message: `Admin account created for ${user.email}`,
    });
  } catch (error: unknown) {
    console.error("Setup error:", error);
    return NextResponse.json(
      { error: "Setup failed. Check server logs." },
      { status: 500 }
    );
  }
}

/**
 * GET /api/setup
 *
 * Check if setup is needed (no users exist).
 */
export async function GET() {
  try {
    await connectDB();
    const userCount = await User.countDocuments();
    return NextResponse.json({
      setupRequired: userCount === 0,
      userCount,
    });
  } catch {
    return NextResponse.json({ setupRequired: true, userCount: 0 });
  }
}
