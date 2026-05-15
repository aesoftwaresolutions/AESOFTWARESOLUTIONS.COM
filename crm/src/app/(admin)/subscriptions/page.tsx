"use client";

import { useEffect, useState } from "react";
import { DollarSign } from "lucide-react";
import { formatDate } from "@/lib/utils";
import { SUBSCRIPTION_TIERS, TIER_LABELS } from "@/lib/constants";
import { Tooltip } from "@/components/ui/tooltip";

interface Sub {
  _id: string;
  tier: string;
  status: string;
  priceInCents: number;
  currentPeriodEnd?: string;
  pausedUntil?: string;
  contactId?: { firstName: string; lastName: string; email: string };
  createdAt: string;
}

const STATUS_COLORS: Record<string, string> = {
  active: "bg-green-100 text-green-700",
  paused: "bg-yellow-100 text-yellow-700",
  cancelled: "bg-red-100 text-red-700",
  past_due: "bg-orange-100 text-orange-700",
};

const STATUS_DESCRIPTIONS: Record<string, string> = {
  active: "Subscription is current and billing normally",
  paused: "Subscription temporarily paused — not billing, not cancelled",
  cancelled: "Subscription ended — patient is no longer active",
  past_due: "Last payment failed — follow up required",
};


const FILTER_DESCRIPTIONS: Record<string, string> = {
  all: "Show all subscriptions regardless of status",
  active: "Show only active, billing subscriptions",
  paused: "Show subscriptions that are temporarily paused",
  cancelled: "Show subscriptions that have been cancelled",
  past_due: "Show subscriptions with failed payments requiring follow-up",
};

export default function SubscriptionsPage() {
  const [subs, setSubs] = useState<Sub[]>([]);
  const [mrr, setMrr] = useState(0);
  const [loading, setLoading] = useState(true);
  const [filter, setFilter] = useState("all");

  function load() {
    const params = filter !== "all" ? `?status=${filter}` : "";
    fetch(`/api/subscriptions${params}`)
      .then((r) => r.json())
      .then((d) => {
        setSubs(d.subscriptions || []);
        setMrr(d.mrr || 0);
      })
      .finally(() => setLoading(false));
  }
  useEffect(load, [filter]);

  async function updateStatus(id: string, status: string) {
    await fetch("/api/subscriptions", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status }),
    });
    load();
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-display text-gray-900">Subscriptions</h1>
      </div>

      {/* MRR Card */}
      <div className="bg-white rounded-xl border p-5 flex items-center gap-4">
        <div className="w-12 h-12 rounded-xl bg-gold/10 flex items-center justify-center">
          <DollarSign className="w-6 h-6 text-gold" />
        </div>
        <div>
          <Tooltip content="Total monthly recurring revenue from all active subscriptions" side="top">
            <div className="text-sm text-gray-500 cursor-default">Monthly Recurring Revenue</div>
          </Tooltip>
          <div className="text-3xl font-semibold text-gray-900">${(mrr / 100).toLocaleString()}</div>
        </div>
        <div className="ml-auto text-right">
          <Tooltip content="Annualized recurring revenue — MRR × 12" side="top">
            <div className="text-sm text-gray-500 cursor-default">ARR</div>
          </Tooltip>
          <div className="text-lg font-semibold text-gray-700">${((mrr * 12) / 100).toLocaleString()}</div>
        </div>
      </div>

      <div className="flex gap-2 flex-wrap">
        {["all", "active", "paused", "cancelled", "past_due"].map((s) => (
          <Tooltip key={s} content={FILTER_DESCRIPTIONS[s] || s} side="bottom">
            <button
              onClick={() => setFilter(s)}
              className={`px-3 py-1.5 text-xs rounded-full border transition ${
                filter === s
                  ? "bg-navy text-white border-navy"
                  : "bg-white border-gray-200 hover:bg-gray-50"
              }`}
            >
              {s === "all" ? "All" : s.replace(/_/g, " ")}
            </button>
          </Tooltip>
        ))}
      </div>

      <div className="bg-white rounded-xl border overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Patient</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Tier</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Price</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Status</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600 hidden md:table-cell">Next Billing</th>
              <th className="text-right px-4 py-3 font-medium text-gray-600">Actions</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-100">
            {loading
              ? [...Array(3)].map((_, i) => (
                  <tr key={i}>
                    <td colSpan={6} className="px-4 py-4">
                      <div className="h-4 bg-gray-100 rounded animate-pulse" />
                    </td>
                  </tr>
                ))
              : subs.length === 0
              ? (
                <tr>
                  <td colSpan={6} className="px-4 py-12 text-center text-gray-400">
                    No subscriptions
                  </td>
                </tr>
              )
              : subs.map((s) => (
                <tr key={s._id} className="hover:bg-gray-50">
                  <td className="px-4 py-3 font-medium">
                    {s.contactId?.firstName} {s.contactId?.lastName}
                  </td>
                  <td className="px-4 py-3 capitalize">
                    <Tooltip content={TIER_LABELS[s.tier] || s.tier} side="top">
                      <span className="text-xs bg-gold/10 text-gold px-2 py-0.5 rounded-full font-medium cursor-default">
                        {s.tier.replace(/_/g, " ")}
                      </span>
                    </Tooltip>
                  </td>
                  <td className="px-4 py-3 font-medium">
                    ${(s.priceInCents / 100).toLocaleString()}/mo
                  </td>
                  <td className="px-4 py-3">
                    <Tooltip content={STATUS_DESCRIPTIONS[s.status] || s.status} side="top">
                      <span className={`text-xs px-2.5 py-1 rounded-full font-medium cursor-default ${STATUS_COLORS[s.status]}`}>
                        {s.status.replace(/_/g, " ")}
                      </span>
                    </Tooltip>
                  </td>
                  <td className="px-4 py-3 text-gray-500 hidden md:table-cell">
                    {s.currentPeriodEnd ? formatDate(s.currentPeriodEnd) : "—"}
                  </td>
                  <td className="px-4 py-3 text-right">
                    <Tooltip content="Change the billing status for this subscription" side="left">
                      <select
                        value={s.status}
                        onChange={(e) => updateStatus(s._id, e.target.value)}
                        className="text-xs border rounded-lg px-2 py-1"
                      >
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="past_due">Past Due</option>
                      </select>
                    </Tooltip>
                  </td>
                </tr>
              ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
