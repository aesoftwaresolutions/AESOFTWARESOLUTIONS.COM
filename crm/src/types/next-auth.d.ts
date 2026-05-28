import "next-auth";
import type { UserRole } from "@/lib/constants";

declare module "next-auth" {
  interface Session {
    user: {
      id: string;
      name: string;
      email: string;
      role: UserRole;
    };
  }
  interface User {
    role: UserRole;
  }
}

declare module "next-auth/jwt" {
  interface JWT {
    role: UserRole;
    userId: string; // set by auth.ts jwt callback: token.userId = user.id
  }
}
