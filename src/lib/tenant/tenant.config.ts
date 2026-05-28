import type { TenantPreset } from "./types";
import { tenantPresets } from "./presets";

export const DEFAULT_TENANT_PRESET_ID = "general-business";

export function getTenantPreset(presetId?: string): TenantPreset {
  if (presetId) {
    const match = tenantPresets.find((p) => p.id === presetId);
    if (match) return match;
  }
  const fromEnv = process.env.NEXT_PUBLIC_TENANT_PRESET;
  if (fromEnv) {
    const match = tenantPresets.find((p) => p.id === fromEnv);
    if (match) return match;
  }
  return (
    tenantPresets.find((p) => p.id === DEFAULT_TENANT_PRESET_ID) ??
    tenantPresets[0]
  );
}

export const defaultTenantPreset = getTenantPreset(DEFAULT_TENANT_PRESET_ID);
