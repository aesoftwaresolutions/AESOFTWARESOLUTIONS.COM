import type { Metadata } from "next";
import { Providers } from "@/components/providers";
import { tenantConfig } from "@/lib/tenant";
import "./globals.css";

export const metadata: Metadata = {
  title: tenantConfig.brand.pageTitle,
  description: tenantConfig.brand.metaDescription,
};

export default function RootLayout({ children }: { children: React.ReactNode }) {
  return (
    <html lang="en">
      <head>
        {tenantConfig.theme.fonts.importUrl && (
          <link
            href={tenantConfig.theme.fonts.importUrl}
            rel="stylesheet"
          />
        )}
      </head>
      <body className="font-body antialiased"><Providers>{children}</Providers></body>
    </html>
  );
}
