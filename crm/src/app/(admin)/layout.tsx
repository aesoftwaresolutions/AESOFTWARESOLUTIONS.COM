"use client";

import { useState } from "react";
import { usePathname } from "next/navigation";
import { useSession, signOut } from "next-auth/react";
import { Menu, Bell, Search, LogOut } from "lucide-react";
import { cn } from "@/lib/utils";
import { AdminSidebar } from "@/components/layout/admin-sidebar";
import { tenantConfig } from "@/lib/tenant";

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const [collapsed, setCollapsed] = useState(false);
  const [mobileOpen, setMobileOpen] = useState(false);
  const pathname = usePathname();
  const { data: session } = useSession();

  return (
    <div className="min-h-screen bg-cream flex">
      {/* Mobile overlay */}
      {mobileOpen && (
        <div className="fixed inset-0 bg-black/50 z-40 lg:hidden" onClick={() => setMobileOpen(false)} />
      )}

      {/* Sidebar — desktop always visible, mobile toggled */}
      <div className={cn(
        "fixed inset-y-0 left-0 z-50 transition-transform duration-300 lg:translate-x-0",
        mobileOpen ? "translate-x-0" : "-translate-x-full"
      )}>
        <AdminSidebar
          collapsed={collapsed}
          onToggle={() => setCollapsed(!collapsed)}
        />
      </div>

      {/* Main content */}
      <div className={cn("flex-1 flex flex-col transition-all duration-300", collapsed ? "lg:ml-16" : "lg:ml-60")}>
        {/* Header */}
        <header className="sticky top-0 z-30 bg-[#1c1c1c]/90 backdrop-blur-md border-b border-[rgba(237,232,220,0.08)] h-16 flex items-center px-4 lg:px-6 gap-4">
          <button onClick={() => setMobileOpen(true)} className="lg:hidden p-2 hover:bg-[#272727] rounded-lg">
            <Menu className="w-5 h-5" />
          </button>
          <div className="flex-1 max-w-lg">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
              <input
                type="text"
                placeholder={`Search ${tenantConfig.contacts.entityLabelPlural.toLowerCase()}, orders...`}
                className="w-full pl-10 pr-4 py-2 text-sm bg-[#1f1f1f] border border-[rgba(237,232,220,0.10)] rounded-lg text-[#EDE8DC] placeholder:text-[#5A5550] focus:outline-none focus:ring-2 focus:ring-gold/30 focus:border-gold transition-all duration-200"
              />
            </div>
          </div>
          <button className="p-2 hover:bg-[#272727] rounded-lg relative">
            <Bell className="w-5 h-5 text-gray-500" />
            <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-brand-error rounded-full" />
          </button>
          <div className="relative w-8 h-8 bg-gold/20 text-gold rounded-full flex items-center justify-center text-sm font-semibold font-body">
            {session?.user?.name?.[0] || tenantConfig.brand.name[0]}
            <span className="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-400 border-2 border-white rounded-full" />
          </div>
          <button
            onClick={() => signOut({ callbackUrl: "/login" })}
            title="Sign out"
            className="p-2 hover:bg-[#272727] rounded-lg text-[#5A5550] hover:text-[#9A9590] transition-colors"
          >
            <LogOut className="w-4 h-4" />
          </button>
        </header>

        {/* Page content */}
        <main className="flex-1 p-4 lg:p-6">{children}</main>
      </div>
    </div>
  );
}
