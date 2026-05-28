"use client";

import { Bell, Search, User } from "lucide-react";
import { cn } from "@/lib/utils";
import { Tooltip } from "@/components/ui/tooltip";

interface AdminHeaderProps {
  sidebarCollapsed: boolean;
}

export function AdminHeader({ sidebarCollapsed }: AdminHeaderProps) {
  return (
    <header
      className={cn(
        "fixed top-0 right-0 z-20 flex h-14 items-center justify-between border-b bg-background/80 backdrop-blur-sm px-6 transition-all duration-300",
        sidebarCollapsed ? "left-16" : "left-60"
      )}
      data-testid="admin-header"
    >
      {/* Search */}
      <Tooltip content="Search contacts by name, email, or phone (⌘K)" side="bottom" delayDuration={600}>
        <div className="flex items-center gap-2 rounded-md border bg-card px-3 py-1.5 text-sm text-muted-foreground w-80">
          <Search className="h-4 w-4" />
          <input
            type="text"
            placeholder="Search contacts, orders, tasks..."
            className="flex-1 bg-transparent outline-none placeholder:text-muted-foreground/60"
            data-testid="input-global-search"
          />
          <kbd className="hidden sm:inline-block text-[10px] font-mono bg-muted px-1.5 py-0.5 rounded">
            ⌘K
          </kbd>
        </div>
      </Tooltip>

      {/* Right side */}
      <div className="flex items-center gap-3">
        {/* Notifications */}
        <Tooltip content="Notifications and system alerts" side="bottom">
          <button
            className="relative rounded-md p-2 text-muted-foreground hover:bg-accent hover:text-foreground transition-colors"
            data-testid="btn-notifications"
          >
            <Bell className="h-4 w-4" />
            <span className="absolute top-1 right-1 h-2 w-2 rounded-full bg-destructive" />
          </button>
        </Tooltip>

        {/* User avatar */}
        <Tooltip content="Your account — profile and settings" side="bottom">
          <button
            className="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm text-muted-foreground hover:bg-accent hover:text-foreground transition-colors"
            data-testid="btn-user-menu"
          >
            <div className="flex h-7 w-7 items-center justify-center rounded-full bg-primary/10 text-primary">
              <User className="h-3.5 w-3.5" />
            </div>
          </button>
        </Tooltip>
      </div>
    </header>
  );
}
