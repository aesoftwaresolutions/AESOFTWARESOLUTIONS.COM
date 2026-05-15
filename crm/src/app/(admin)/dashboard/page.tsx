"use client";
import { useEffect, useState } from "react";
import { Users, DollarSign, Calendar, CheckSquare, TrendingUp, AlertTriangle } from "lucide-react";
import {
  AreaChart, Area, PieChart, Pie, BarChart, Bar, Cell,
  XAxis, YAxis, CartesianGrid, Tooltip, Legend, ResponsiveContainer,
} from "recharts";
import { formatDate, cn } from "@/lib/utils";
import { Tooltip as UITooltip } from "@/components/ui/tooltip";
import {
  tenantConfig,
  getStatusColorMap,
  getStatusChartColorMap,
  formatTenantCurrency,
} from "@/lib/tenant";

// Map icon string names to components
const ICON_MAP: Record<string, React.ElementType> = {
  Users, DollarSign, Calendar, CheckSquare, TrendingUp, AlertTriangle,
};

interface KPIData {
  [key: string]: unknown;
  totalContacts: number;
  newContactsThisMonth: number;
  activePatients: number;
  upcomingAppointments: number;
  openTasks: number;
  urgentTasks: number;
  mrr: number;
  arr: number;
  activeSubscriptions: number;
  totalOrders: number;
  contactsByStatus: Record<string, number>;
  pipeline: Record<string, { count: number; value: number }>;
  recentActivity: { firstName: string; lastName: string; status: string; createdAt: string }[];
  contactsTrend: { date: string; count: number }[];
}

export default function DashboardPage() {
  const [data, setData] = useState<KPIData | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const STATUS_COLORS = getStatusColorMap();
  const STATUS_CHART_COLORS = getStatusChartColorMap();

  useEffect(() => {
    fetch("/api/reports/overview", { credentials: "include" })
      .then((r) => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then(setData)
      .catch((err) => setError(err.message))
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <div className="space-y-6">
        <h1 className="text-2xl font-display text-gray-900">Dashboard</h1>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
          {[...Array(8)].map((_, i) => (
            <div key={i} className="bg-white rounded-xl border border-gray-200 p-5 animate-pulse">
              <div className="h-4 w-20 bg-gray-200 rounded mb-3" />
              <div className="h-8 w-24 bg-gray-200 rounded" />
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (error || !data) {
    return (
      <div className="space-y-6">
        <h1 className="text-2xl font-display text-gray-900">Dashboard</h1>
        <div className="bg-red-50 border border-red-200 rounded-xl p-5 text-red-700">
          Failed to load dashboard data{error ? `: ${error}` : ""}
        </div>
      </div>
    );
  }

  // Map color classes to border colors
  const colorToBorderMap: Record<string, string> = {
    "text-blue-600": "border-l-blue-500",
    "text-emerald-600": "border-l-emerald-500",
    "text-green-600": "border-l-green-500",
    "text-amber-600": "border-l-amber-500",
    "text-purple-600": "border-l-purple-500",
    "text-cyan-600": "border-l-cyan-500",
    "text-orange-600": "border-l-orange-500",
    "text-red-600": "border-l-red-500",
  };

  // Build KPI cards from tenant config
  const kpis = tenantConfig.dashboard.kpis.map((kpi) => {
    const rawValue = (data as Record<string, unknown>)[kpi.dataKey] ?? 0;
    const value = kpi.format === "currency"
      ? formatTenantCurrency(rawValue as number)
      : rawValue;
    return {
      label: kpi.label,
      description: kpi.description,
      value,
      icon: ICON_MAP[kpi.icon] || Users,
      color: kpi.colorClasses,
      borderColor: colorToBorderMap[kpi.colorClasses] || "border-l-gray-300",
    };
  });

  // Prepare chart data
  const statusChartData = Object.entries(data.contactsByStatus ?? {}).map(([status, count]) => ({
    name: status.replace(/_/g, " "),
    value: count,
  }));

  const pipelineChartData = Object.entries(data.pipeline ?? {}).map(([stage, info]) => ({
    stage: stage.replace(/_/g, " "),
    value: (info.value ?? 0) / 100,
  }));

  const accentColor = tenantConfig.theme.colors.accent;

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-display text-gray-900">Dashboard</h1>
        <span className="text-sm text-gray-500">{formatDate(new Date())}</span>
      </div>

      {/* KPI Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        {kpis.map((kpi) => (
          <UITooltip key={kpi.label} content={kpi.description} side="top">
            <div className={cn("bg-white rounded-xl border border-l-4 border-gray-200 p-5 hover:shadow-md transition-shadow duration-200 cursor-default", kpi.borderColor)}>
              <div className="flex items-center gap-3 mb-3">
                <div className={`w-9 h-9 rounded-lg flex items-center justify-center ${kpi.color}`}>
                  <kpi.icon className="w-5 h-5" />
                </div>
                <span className="text-sm text-gray-500">{kpi.label}</span>
              </div>
              <div className="text-3xl font-bold text-gray-900 tracking-tight">{kpi.value as React.ReactNode}</div>
            </div>
          </UITooltip>
        ))}
      </div>

      {/* Charts Section */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Area Chart - 7-Day Trend */}
        <div className="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">
            New {tenantConfig.contacts.entityLabelPlural} (7 Days)
          </h2>
          <ResponsiveContainer width="100%" height={300}>
            <AreaChart data={data.contactsTrend ?? []}>
              <CartesianGrid strokeDasharray="3 3" />
              <XAxis dataKey="date" />
              <YAxis />
              <Tooltip />
              <Legend />
              <Area
                type="monotone"
                dataKey="count"
                stroke={accentColor}
                fill={accentColor}
                name={`New ${tenantConfig.contacts.entityLabelPlural}`}
              />
            </AreaChart>
          </ResponsiveContainer>
        </div>

        {/* Pie Chart - Status Distribution */}
        <div className="bg-white rounded-xl border border-gray-200 p-5">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">
            {tenantConfig.contacts.entityLabel} Status
          </h2>
          <ResponsiveContainer width="100%" height={300}>
            <PieChart>
              <Pie
                data={statusChartData}
                cx="50%"
                cy="50%"
                labelLine={false}
                label={(entry) => entry.name}
                outerRadius={80}
                fill="#8884d8"
                dataKey="value"
              >
                {statusChartData.map((entry) => (
                  <Cell
                    key={`cell-${entry.name}`}
                    fill={STATUS_CHART_COLORS[entry.name.replace(/ /g, "_")] || "#6B7280"}
                  />
                ))}
              </Pie>
              <Tooltip />
              <Legend />
            </PieChart>
          </ResponsiveContainer>
        </div>
      </div>

      {/* Bar Chart - Pipeline Value */}
      <div className="bg-white rounded-xl border border-gray-200 p-5">
        <h2 className="text-lg font-semibold text-gray-900 mb-4">Pipeline Value by Stage</h2>
        <ResponsiveContainer width="100%" height={300}>
          <BarChart data={pipelineChartData}>
            <CartesianGrid strokeDasharray="3 3" />
            <XAxis dataKey="stage" />
            <YAxis />
            <Tooltip formatter={(value) => `${tenantConfig.billing.currencySymbol}${Number(value).toLocaleString()}`} />
            <Legend />
            <Bar dataKey="value" fill={accentColor} name="Pipeline Value" />
          </BarChart>
        </ResponsiveContainer>
      </div>

      {/* Pipeline Overview & Recent Activity */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Pipeline Overview */}
        <div className="bg-white rounded-xl border border-gray-200 p-5">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">Pipeline Overview</h2>
          <div className="space-y-3">
            {Object.entries(data.pipeline ?? {}).map(([stage, info]) => (
              <div key={stage} className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <div className="w-2 h-2 rounded-full bg-gold" />
                  <span className="text-sm text-gray-700 capitalize">{stage.replace(/_/g, " ")}</span>
                </div>
                <div className="flex items-center gap-4">
                  <span className="text-sm font-medium">{info.count}</span>
                  <span className="text-xs text-gray-500">{formatTenantCurrency(info.value ?? 0)}</span>
                </div>
              </div>
            ))}
            {Object.keys(data.pipeline ?? {}).length === 0 && (
              <p className="text-sm text-gray-400">No pipeline data yet</p>
            )}
          </div>
        </div>

        {/* Recent Activity */}
        <div className="bg-white rounded-xl border border-gray-200 p-5">
          <h2 className="text-lg font-semibold text-gray-900 mb-4">
            Recent {tenantConfig.contacts.entityLabelPlural}
          </h2>
          <div className="space-y-3">
            {(data.recentActivity ?? []).map((c, i) => (
              <div key={i} className="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div>
                  <span className="text-sm font-medium text-gray-900">{c.firstName} {c.lastName}</span>
                  <div className="text-xs text-gray-500">{formatDate(c.createdAt)}</div>
                </div>
                <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${STATUS_COLORS[c.status] || "bg-gray-100 text-gray-600"}`}>
                  {c.status.replace(/_/g, " ")}
                </span>
              </div>
            ))}
            {(data.recentActivity ?? []).length === 0 && (
              <p className="text-sm text-gray-400">No recent activity</p>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
