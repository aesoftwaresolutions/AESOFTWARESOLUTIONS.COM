import { cn } from "@/lib/utils";
import { ArrowUpRight, ArrowDownRight, Minus } from "lucide-react";

interface StatCardProps {
  label: string;
  value: string;
  change?: number;
  changeLabel?: string;
  icon?: React.ReactNode;
}

export function StatCard({ label, value, change, changeLabel, icon }: StatCardProps) {
  const trend = change === undefined ? "neutral" : change > 0 ? "up" : change < 0 ? "down" : "neutral";

  return (
    <div
      className="rounded-lg border bg-card p-5 shadow-sm"
      data-testid={`stat-${label.toLowerCase().replace(/\s+/g, "-")}`}
    >
      <div className="flex items-center justify-between">
        <span className="text-xs font-medium uppercase tracking-wider text-muted-foreground">
          {label}
        </span>
        {icon && <div className="text-muted-foreground/50">{icon}</div>}
      </div>
      <div className="mt-2 text-2xl font-semibold tracking-tight text-foreground tabular-nums">
        {value}
      </div>
      {change !== undefined && (
        <div className="mt-1.5 flex items-center gap-1 text-xs">
          <span
            className={cn(
              "flex items-center gap-0.5 font-medium",
              trend === "up" && "text-emerald-600",
              trend === "down" && "text-red-500",
              trend === "neutral" && "text-muted-foreground"
            )}
          >
            {trend === "up" && <ArrowUpRight className="h-3 w-3" />}
            {trend === "down" && <ArrowDownRight className="h-3 w-3" />}
            {trend === "neutral" && <Minus className="h-3 w-3" />}
            {Math.abs(change).toFixed(1)}%
          </span>
          {changeLabel && (
            <span className="text-muted-foreground">{changeLabel}</span>
          )}
        </div>
      )}
    </div>
  );
}
