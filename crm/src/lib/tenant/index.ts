import { defaultTenantPreset, getTenantPreset, DEFAULT_TENANT_PRESET_ID } from "./tenant.config";
import { tenantPresets } from "./presets";

export const tenantConfig = defaultTenantPreset;
export { getTenantPreset, tenantPresets, DEFAULT_TENANT_PRESET_ID };

export function getStatusColorMap(): Record<string, string> {
  const palette = [
    "bg-slate-100 text-slate-700 border border-slate-200",
    "bg-blue-100 text-blue-700 border border-blue-200",
    "bg-amber-100 text-amber-700 border border-amber-200",
    "bg-emerald-100 text-emerald-700 border border-emerald-200",
    "bg-rose-100 text-rose-700 border border-rose-200",
    "bg-violet-100 text-violet-700 border border-violet-200",
  ];
  return Object.fromEntries(
    tenantConfig.contacts.statuses.map((s, i) => [s.id, palette[i % palette.length]])
  );
}

export function getStatusDescriptionMap(): Record<string, string> {
  return Object.fromEntries(
    tenantConfig.contacts.statuses.map((s) => [s.id, s.description ?? s.label])
  );
}

export function getTierLabelMap(): Record<string, string> {
  return Object.fromEntries(
    tenantConfig.billing.tiers.map((t) => [t.id, t.label])
  );
}

export function getNavLabel(
  key: keyof NonNullable<typeof tenantConfig.navigationLabels>
): string {
  return (
    tenantConfig.navigationLabels?.[key] ??
    key.charAt(0).toUpperCase() + key.slice(1)
  );
}
