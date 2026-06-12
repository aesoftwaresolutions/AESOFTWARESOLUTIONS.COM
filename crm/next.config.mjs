/** @type {import('next').NextConfig} */
const nextConfig = {
  experimental: {
    serverActions: {
      bodySizeLimit: "2mb",
    },
  },
  // Vercel Cron configuration lives in vercel.json
  async headers() {
    const frontendUrl = process.env.NEXT_PUBLIC_FRONTEND_URL || "https://aesoftwaresolutions.com";
    return [
      {
        // Allow the frontend to call all API routes
        source: "/api/:path*",
        headers: [
          { key: "Access-Control-Allow-Origin", value: frontendUrl },
          { key: "Access-Control-Allow-Methods", value: "GET,POST,PUT,PATCH,DELETE,OPTIONS" },
          { key: "Access-Control-Allow-Headers", value: "Content-Type, Authorization" },
          { key: "Access-Control-Allow-Credentials", value: "true" },
        ],
      },
    ];
  },
};

export default nextConfig;
