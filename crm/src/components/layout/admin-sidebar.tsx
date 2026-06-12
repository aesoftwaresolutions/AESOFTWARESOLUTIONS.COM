"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import {
  LayoutDashboard, Users, Target, Calendar, Megaphone, ShoppingCart,
  CreditCard, CheckSquare, MessageSquare, Zap, BarChart3, Settings,
  ChevronLeft, Instagram, Globe,
} from "lucide-react";
import { cn } from "@/lib/utils";
import { Tooltip } from "@/components/ui/tooltip";
import { BrandLogo } from "./ae-logo";
import { tenantConfig, getEnabledModules } from "@/lib/tenant";

// Map icon string names from config to actual Lucide components
const ICON_MAP: Record<string, React.ElementType> = {
  LayoutDashboard,
  Users,
  Target,
  Calendar,
  Megaphone,
  ShoppingCart,
  CreditCard,
  CheckSquare,
  MessageSquare,
  Zap,
  BarChart3,
  Settings,
  Instagram,
  Globe,
};

interface AdminSidebarProps {
  collapsed: boolean;
  onToggle: () => void;
}

export function AdminSidebar({ collapsed, onToggle }: AdminSidebarProps) {
  const pathname = usePathname();
  const enabledModules = getEnabledModules();

  return (
    <aside
      className={cn(
        "fixed inset-y-0 left-0 z-30 flex flex-col border-r transition-all duration-300",
        "bg-sidebar text-sidebar-foreground border-sidebar-border",
        collapsed ? "w-16" : "w-60"
      )}
      data-testid="admin-sidebar"
    >
      {/* Logo */}
      <div className="flex h-14 items-center gap-3 border-b border-sidebar-border px-4">
        <BrandLogo className="h-7 w-7 shrink-0 text-sidebar-primary" />
        {!collapsed && (
          <span className="text-sm font-semibold tracking-wide text-sidebar-foreground">
            {tenantConfig.brand.name.toUpperCase()}
          </span>
        )}
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto py-3 px-2" role="navigation">
        {enabledModules.map((item, idx) => {
          const isActive = pathname === item.href || pathname.startsWith(item.href + "/");
          const IconComponent = ICON_MAP[item.icon] || LayoutDashboard;
          const showSection =
            item.section &&
            !collapsed &&
            (idx === 0 || enabledModules[idx - 1]?.section !== item.section);

          return (
            <div key={item.href}>
              {showSection && (
                <div className="mt-4 mb-1 border-t border-sidebar-border/30 pt-3 px-3 text-[11px] font-medium uppercase tracking-widest text-sidebar-foreground/50">
                  {item.section}
                </div>
              )}
              <Tooltip content={item.description || item.label} side="right">
                <div className={cn("relative", collapsed && "justify-center")}>
                  <Link
                    href={item.href}
                    className={cn(
                      "flex items-center gap-3 rounded-md px-3 py-2 text-sm transition-colors group",
                      isActive
                        ? "bg-sidebar-accent/10 text-sidebar-accent font-semibold"
                        : "text-sidebar-foreground/70 hover:bg-sidebar-accent hover:text-sidebar-foreground",
                      collapsed && "justify-center px-0"
                    )}
                    data-testid={`nav-${item.label.toLowerCase().replace(/\s+/g, "-")}`}
                  >
                    <IconComponent className={cn("h-4 w-4 shrink-0", isActive && "group-hover:translate-x-0.5 transition-transform")} />
                    {!collapsed && <span>{item.label}</span>}
                  </Link>
                  {isActive && (
                    <div className="absolute left-0 inset-y-0 w-0.5 bg-gold rounded-r" />
                  )}
                </div>
              </Tooltip>
            </div>
          );
        })}
      </nav>

      {/* Footer - Collapse button moved to bottom */}
      <div className="border-t border-sidebar-border">
        <Tooltip content={collapsed ? "Expand sidebar" : "Collapse sidebar"} side="right">
          <button
            onClick={onToggle}
            className="flex w-full items-center justify-center h-12 border-sidebar-border text-sidebar-foreground/50 hover:text-sidebar-foreground hover:bg-white/5 transition"
            data-testid="btn-collapse-sidebar"
          >
            <ChevronLeft
              className={cn("h-4 w-4 shrink-0 transition-transform", collapsed && "rotate-180")}
            />
          </button>
        </Tooltip>
      </div>
    </aside>
  );
}
