/**
 * Re-exports the canonical NextAuth v4 authOptions and provides a
 * server-side auth() helper compatible with the v4 getServerSession API.
 */
import { getServerSession } from "next-auth";
export { authOptions } from "@/lib/auth";

export async function auth() {
  const { authOptions } = await import("@/lib/auth");
  return getServerSession(authOptions);
}
