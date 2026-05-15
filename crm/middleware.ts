import { withAuth } from "next-auth/middleware";

export default withAuth({
  pages: { signIn: "/login" },
});

export const config = {
  matcher: [
    "/",
    "/dashboard/:path*",
    "/contacts/:path*",
    "/pipeline/:path*",
    "/appointments/:path*",
    "/tasks/:path*",
    "/orders/:path*",
    "/subscriptions/:path*",
    "/messaging/:path*",
    "/campaigns/:path*",
    "/automations/:path*",
    "/reports/:path*",
    "/settings/:path*",
    "/social/:path*",
    "/website/:path*",
  ],
};
