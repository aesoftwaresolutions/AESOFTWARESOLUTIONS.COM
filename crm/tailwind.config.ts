import type { Config } from "tailwindcss";
import { tenantConfig } from "./src/lib/tenant/tenant.config";

const tc = tenantConfig.theme.colors;

const config: Config = {
  darkMode: ["class"],
  content: ["./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        navy: { DEFAULT: tc.primary, light: tc.primaryLight },
        gold: { DEFAULT: tc.accent, light: tc.accentLight },
        cream: tc.background,
        sidebar: {
          DEFAULT: tc.sidebar,
          foreground: tc.sidebarForeground,
          border: tc.sidebarBorder,
          accent: tc.sidebarAccent,
          muted: tc.sidebarMuted,
          primary: tc.accent,
        },
        brand: { success: tc.success, warning: tc.warning, error: tc.error },
      },
      fontFamily: {
        display: tenantConfig.theme.fonts.display.split(",").map((f) => f.trim()),
        body: tenantConfig.theme.fonts.body.split(",").map((f) => f.trim()),
      },
      borderRadius: { lg: "12px", md: "8px", sm: "6px" },
    },
  },
  plugins: [require("tailwindcss-animate")],
};
export default config;
