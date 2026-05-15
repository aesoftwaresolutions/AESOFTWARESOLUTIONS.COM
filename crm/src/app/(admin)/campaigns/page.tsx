"use client";

import { useEffect, useState, useCallback } from "react";
import {
  Plus, X, Megaphone, TrendingUp, MousePointerClick,
  DollarSign, Users, BarChart3, Edit2, Trash2, Link as LinkIcon,
  Eye, RefreshCw,
} from "lucide-react";
import { CAMPAIGN_TYPES, CAMPAIGN_STATUSES } from "@/lib/constants";
import { Tooltip } from "@/components/ui/tooltip";

const SUMMARY_STAT_DESCRIPTIONS: Record<string, string> = {
  Impressions: "Total number of times campaign ads or emails were shown to an audience",
  Clicks: "Total clicks on campaign links, ads, or CTAs across all campaigns",
  Conversions: "Total leads or patients acquired across all campaigns",
  "Total Spend": "Combined ad spend and campaign costs across all campaigns",
  Revenue: "Attributed revenue from patients acquired through campaigns",
};

const TYPE_DESCRIPTIONS: Record<string, string> = {
  email: "Email campaign — delivered via SendGrid",
  sms: "SMS campaign — delivered via Twilio",
  google_ads: "Google Ads paid search campaign",
  facebook_ads: "Facebook paid advertising campaign",
  instagram: "Instagram paid advertising campaign",
  facebook_organic: "Organic Facebook posts and content",
  twitter: "Twitter / X organic or paid campaign",
  linkedin: "LinkedIn organic or paid campaign",
  tiktok: "TikTok organic or paid campaign",
  referral: "Patient or partner referral program",
  organic: "Organic search, SEO, or unpaid traffic",
};

const STATUS_CAMPAIGN_DESCRIPTIONS: Record<string, string> = {
  draft: "Not yet launched — still being configured",
  active: "Campaign is live and currently running",
  paused: "Temporarily paused — spend and sends halted",
  completed: "Campaign has ended; results are final",
};

const METRIC_DESCRIPTIONS: Record<string, string> = {
  Impressions: "How many times the campaign creative was displayed",
  Clicks: "Number of clicks on campaign links or ads",
  CTR: "Click-Through Rate = Clicks ÷ Impressions × 100",
  Conversions: "Number of leads or sign-ups generated",
  CVR: "Conversion Rate = Conversions ÷ Clicks × 100",
  "Spend / CPL": "Total spend and Cost Per Lead = Spend ÷ Conversions",
};

interface CampaignMetrics {
  impressions: number;
  clicks: number;
  conversions: number;
  spend: number;
  costPerLead: number;
  revenue: number;
}

interface Campaign {
  _id: string;
  name: string;
  type: string;
  status: string;
  description?: string;
  budget?: number;
  startDate?: string;
  endDate?: string;
  utmSource?: string;
  utmMedium?: string;
  utmCampaign?: string;
  tags: string[];
  targetAudience?: string;
  metrics: CampaignMetrics;
  createdBy?: { name: string };
  createdAt: string;
}

const TYPE_COLORS: Record<string, string> = {
  email: "bg-blue-100 text-blue-700",
  sms: "bg-green-100 text-green-700",
  google_ads: "bg-red-100 text-red-700",
  facebook_ads: "bg-indigo-100 text-indigo-700",
  instagram: "bg-pink-100 text-pink-700",
  facebook_organic: "bg-indigo-100 text-indigo-600",
  twitter: "bg-sky-100 text-sky-700",
  linkedin: "bg-blue-100 text-blue-800",
  tiktok: "bg-slate-100 text-slate-700",
  referral: "bg-purple-100 text-purple-700",
  organic: "bg-emerald-100 text-emerald-700",
};

const STATUS_COLORS: Record<string, string> = {
  draft: "bg-gray-100 text-gray-600",
  active: "bg-green-100 text-green-700",
  paused: "bg-yellow-100 text-yellow-700",
  completed: "bg-blue-100 text-blue-700",
};

const TYPE_LABELS: Record<string, string> = {
  email: "Email",
  sms: "SMS",
  google_ads: "Google Ads",
  facebook_ads: "Facebook Ads",
  instagram: "Instagram",
  facebook_organic: "Facebook Organic",
  twitter: "Twitter / X",
  linkedin: "LinkedIn",
  tiktok: "TikTok",
  referral: "Referral",
  organic: "Organic",
};

const EMPTY_FORM = {
  name: "",
  type: "email",
  status: "draft",
  description: "",
  budget: "",
  startDate: "",
  endDate: "",
  utmSource: "",
  utmMedium: "",
  utmCampaign: "",
  targetAudience: "",
  tags: "",
};

function fmt(n: number) {
  return n.toLocaleString();
}

function fmtMoney(n: number) {
  return n === 0 ? "$0" : `$${n.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function ctr(clicks: number, impressions: number) {
  if (!impressions) return "0%";
  return ((clicks / impressions) * 100).toFixed(2) + "%";
}

function cvr(conversions: number, clicks: number) {
  if (!clicks) return "0%";
  return ((conversions / clicks) * 100).toFixed(2) + "%";
}

function roi(revenue: number, spend: number) {
  if (!spend) return "—";
  const r = ((revenue - spend) / spend) * 100;
  return (r >= 0 ? "+" : "") + r.toFixed(1) + "%";
}

function buildUtmLink(c: Campaign) {
  const base = process.env.NEXT_PUBLIC_FRONTEND_URL || "https://example.com";
  const params = new URLSearchParams();
  if (c.utmSource) params.set("utm_source", c.utmSource);
  if (c.utmMedium) params.set("utm_medium", c.utmMedium);
  if (c.utmCampaign) params.set("utm_campaign", c.utmCampaign);
  const qs = params.toString();
  return qs ? `${base}?${qs}` : "";
}

export default function CampaignsPage() {
  const [campaigns, setCampaigns] = useState<Campaign[]>([]);
  const [loading, setLoading] = useState(true);
  const [total, setTotal] = useState(0);
  const [typeFilter, setTypeFilter] = useState("all");
  const [statusFilter, setStatusFilter] = useState("all");
  const [search, setSearch] = useState("");
  const [modalOpen, setModalOpen] = useState(false);
  const [editingCampaign, setEditingCampaign] = useState<Campaign | null>(null);
  const [metricsModal, setMetricsModal] = useState<Campaign | null>(null);
  const [utmCopied, setUtmCopied] = useState<string | null>(null);
  const [form, setForm] = useState({ ...EMPTY_FORM });
  const [saving, setSaving] = useState(false);
  const [metricsForm, setMetricsForm] = useState<Partial<CampaignMetrics>>({});

  const loadCampaigns = useCallback(() => {
    const params = new URLSearchParams();
    if (typeFilter !== "all") params.set("type", typeFilter);
    if (statusFilter !== "all") params.set("status", statusFilter);
    if (search) params.set("search", search);

    setLoading(true);
    fetch(`/api/campaigns?${params}`)
      .then((r) => r.json())
      .then((d) => {
        setCampaigns(d.campaigns || []);
        setTotal(d.total || 0);
      })
      .finally(() => setLoading(false));
  }, [typeFilter, statusFilter, search]);

  useEffect(() => {
    loadCampaigns();
  }, [loadCampaigns]);

  function openCreate() {
    setEditingCampaign(null);
    setForm({ ...EMPTY_FORM });
    setModalOpen(true);
  }

  function openEdit(c: Campaign) {
    setEditingCampaign(c);
    setForm({
      name: c.name,
      type: c.type,
      status: c.status,
      description: c.description || "",
      budget: c.budget?.toString() || "",
      startDate: c.startDate ? c.startDate.split("T")[0] : "",
      endDate: c.endDate ? c.endDate.split("T")[0] : "",
      utmSource: c.utmSource || "",
      utmMedium: c.utmMedium || "",
      utmCampaign: c.utmCampaign || "",
      targetAudience: c.targetAudience || "",
      tags: c.tags?.join(", ") || "",
    });
    setModalOpen(true);
  }

  function openMetrics(c: Campaign) {
    setMetricsModal(c);
    setMetricsForm({ ...c.metrics });
  }

  async function handleSave(e: React.FormEvent) {
    e.preventDefault();
    setSaving(true);
    const payload = {
      name: form.name,
      type: form.type,
      status: form.status,
      description: form.description || undefined,
      budget: form.budget ? parseFloat(form.budget) : undefined,
      startDate: form.startDate || undefined,
      endDate: form.endDate || undefined,
      utmSource: form.utmSource || undefined,
      utmMedium: form.utmMedium || undefined,
      utmCampaign: form.utmCampaign || undefined,
      targetAudience: form.targetAudience || undefined,
      tags: form.tags ? form.tags.split(",").map((t) => t.trim()).filter(Boolean) : [],
    };

    try {
      if (editingCampaign) {
        const res = await fetch(`/api/campaigns/${editingCampaign._id}`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
        if (res.ok) { setModalOpen(false); loadCampaigns(); }
      } else {
        const res = await fetch("/api/campaigns", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
        if (res.ok) { setModalOpen(false); loadCampaigns(); }
      }
    } finally {
      setSaving(false);
    }
  }

  async function handleDelete(id: string, name: string) {
    if (!confirm(`Delete campaign "${name}"? This cannot be undone.`)) return;
    await fetch(`/api/campaigns/${id}`, { method: "DELETE" });
    loadCampaigns();
  }

  async function handleSaveMetrics(e: React.FormEvent) {
    e.preventDefault();
    if (!metricsModal) return;
    await fetch(`/api/campaigns/${metricsModal._id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ metrics: metricsForm }),
    });
    setMetricsModal(null);
    loadCampaigns();
  }

  async function handleStatusChange(id: string, status: string) {
    await fetch(`/api/campaigns/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ status }),
    });
    loadCampaigns();
  }

  function copyUtm(c: Campaign) {
    const link = buildUtmLink(c);
    if (!link) return;
    navigator.clipboard.writeText(link).then(() => {
      setUtmCopied(c._id);
      setTimeout(() => setUtmCopied(null), 1500);
    });
  }

  // Aggregate summary stats
  const totals = campaigns.reduce(
    (acc, c) => ({
      impressions: acc.impressions + (c.metrics?.impressions || 0),
      clicks: acc.clicks + (c.metrics?.clicks || 0),
      conversions: acc.conversions + (c.metrics?.conversions || 0),
      spend: acc.spend + (c.metrics?.spend || 0),
      revenue: acc.revenue + (c.metrics?.revenue || 0),
    }),
    { impressions: 0, clicks: 0, conversions: 0, spend: 0, revenue: 0 }
  );

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-display text-gray-900">Campaigns</h1>
          <p className="text-sm text-gray-500 mt-1">{total} total campaigns</p>
        </div>
        <button
          onClick={openCreate}
          className="inline-flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition"
        >
          <Plus className="w-4 h-4" /> New Campaign
        </button>
      </div>

      {/* Summary Stats */}
      <div className="grid grid-cols-5 gap-4">
        {[
          { label: "Impressions", value: fmt(totals.impressions), icon: Eye, color: "text-indigo-600" },
          { label: "Clicks", value: fmt(totals.clicks), icon: MousePointerClick, color: "text-blue-600" },
          { label: "Conversions", value: fmt(totals.conversions), icon: Users, color: "text-green-600" },
          { label: "Total Spend", value: fmtMoney(totals.spend), icon: DollarSign, color: "text-red-600" },
          { label: "Revenue", value: fmtMoney(totals.revenue), icon: TrendingUp, color: "text-gold" },
        ].map((s) => (
          <Tooltip key={s.label} content={SUMMARY_STAT_DESCRIPTIONS[s.label] || s.label} side="top">
            <div className="bg-white rounded-xl border border-gray-200 p-4 cursor-default">
              <div className="flex items-center justify-between mb-2">
                <span className="text-xs font-medium text-gray-500 uppercase tracking-wide">{s.label}</span>
                <s.icon className={`w-4 h-4 ${s.color}`} />
              </div>
              <div className="text-2xl font-bold text-gray-900">{s.value}</div>
            </div>
          </Tooltip>
        ))}
      </div>

      {/* Filters */}
      <div className="bg-white rounded-xl border border-gray-200 p-4 flex flex-wrap gap-4 items-end">
        <div className="flex-1 min-w-[200px]">
          <label className="block text-xs font-medium text-gray-600 mb-1">Search</label>
          <input
            type="text"
            placeholder="Search campaigns..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-gray-600 mb-1">Type</label>
          <select
            value={typeFilter}
            onChange={(e) => setTypeFilter(e.target.value)}
            className="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
          >
            <option value="all">All Types</option>
            {CAMPAIGN_TYPES.map((t) => (
              <option key={t} value={t}>{TYPE_LABELS[t] || t}</option>
            ))}
          </select>
        </div>
        <div>
          <label className="block text-xs font-medium text-gray-600 mb-1">Status</label>
          <select
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value)}
            className="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
          >
            <option value="all">All Statuses</option>
            {CAMPAIGN_STATUSES.map((s) => (
              <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
            ))}
          </select>
        </div>
        <button
          onClick={loadCampaigns}
          className="flex items-center gap-1.5 px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition"
        >
          <RefreshCw className="w-4 h-4" /> Refresh
        </button>
      </div>

      {/* Campaign List */}
      {loading ? (
        <div className="space-y-3">
          {[...Array(4)].map((_, i) => (
            <div key={i} className="h-28 bg-white rounded-xl border border-gray-200 animate-pulse" />
          ))}
        </div>
      ) : campaigns.length === 0 ? (
        <div className="text-center py-16 bg-white rounded-xl border border-gray-200">
          <Megaphone className="w-12 h-12 text-gray-300 mx-auto mb-4" />
          <p className="text-gray-500 font-medium">No campaigns found</p>
          <p className="text-sm text-gray-400 mt-1">Create your first campaign to get started</p>
        </div>
      ) : (
        <div className="space-y-3">
          {campaigns.map((c) => {
            const utmLink = buildUtmLink(c);
            return (
              <div key={c._id} className="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition">
                <div className="flex items-start gap-4">
                  <div className="flex-1 min-w-0">
                    {/* Name + Badges */}
                    <div className="flex items-center gap-3 flex-wrap mb-2">
                      <h3 className="font-semibold text-gray-900 text-base">{c.name}</h3>
                      <Tooltip content={TYPE_DESCRIPTIONS[c.type] || c.type} side="top">
                        <span className={`px-2 py-0.5 rounded-full text-xs font-medium cursor-default ${TYPE_COLORS[c.type] || "bg-gray-100 text-gray-600"}`}>
                          {TYPE_LABELS[c.type] || c.type}
                        </span>
                      </Tooltip>
                      <Tooltip content={STATUS_CAMPAIGN_DESCRIPTIONS[c.status] || c.status} side="top">
                        <span className={`px-2 py-0.5 rounded-full text-xs font-medium cursor-default ${STATUS_COLORS[c.status] || "bg-gray-100"}`}>
                          {c.status.charAt(0).toUpperCase() + c.status.slice(1)}
                        </span>
                      </Tooltip>
                    </div>

                    {c.description && (
                      <p className="text-sm text-gray-500 mb-3 line-clamp-1">{c.description}</p>
                    )}

                    {/* Metrics Row */}
                    <div className="grid grid-cols-6 gap-4 text-center bg-gray-50 rounded-lg p-3">
                      {[
                        { label: "Impressions", value: fmt(c.metrics?.impressions || 0) },
                        { label: "Clicks", value: fmt(c.metrics?.clicks || 0) },
                        { label: "CTR", value: ctr(c.metrics?.clicks || 0, c.metrics?.impressions || 0) },
                        { label: "Conversions", value: fmt(c.metrics?.conversions || 0) },
                        { label: "CVR", value: cvr(c.metrics?.conversions || 0, c.metrics?.clicks || 0) },
                        { label: "Spend / CPL", value: `${fmtMoney(c.metrics?.spend || 0)} / ${fmtMoney(c.metrics?.costPerLead || 0)}` },
                      ].map((m) => (
                        <Tooltip key={m.label} content={METRIC_DESCRIPTIONS[m.label] || m.label} side="top">
                          <div className="cursor-default">
                            <div className="text-xs text-gray-500 mb-0.5">{m.label}</div>
                            <div className="text-sm font-semibold text-gray-800">{m.value}</div>
                          </div>
                        </Tooltip>
                      ))}
                    </div>

                    {/* UTM + budget row */}
                    <div className="flex items-center gap-4 mt-3 text-xs text-gray-400 flex-wrap">
                      {c.budget && <span>Budget: {fmtMoney(c.budget)}</span>}
                      {c.startDate && <span>Start: {new Date(c.startDate).toLocaleDateString()}</span>}
                      {c.endDate && <span>End: {new Date(c.endDate).toLocaleDateString()}</span>}
                      {utmLink && (
                        <button
                          onClick={() => copyUtm(c)}
                          className="flex items-center gap-1 text-gold hover:text-gold-light transition"
                        >
                          <LinkIcon className="w-3 h-3" />
                          {utmCopied === c._id ? "Copied!" : "Copy UTM Link"}
                        </button>
                      )}
                      {c.metrics?.revenue > 0 && (
                        <span className={`font-medium ${c.metrics.revenue >= (c.metrics?.spend || 0) ? "text-green-600" : "text-red-500"}`}>
                          ROI: {roi(c.metrics.revenue, c.metrics?.spend || 0)}
                        </span>
                      )}
                    </div>
                  </div>

                  {/* Actions */}
                  <div className="flex items-center gap-2 shrink-0">
                    <select
                      value={c.status}
                      onChange={(e) => handleStatusChange(c._id, e.target.value)}
                      className="px-2 py-1 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold/50"
                    >
                      {CAMPAIGN_STATUSES.map((s) => (
                        <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
                      ))}
                    </select>
                    <Tooltip content="Update impressions, clicks, conversions, and spend metrics" side="left">
                      <button
                        onClick={() => openMetrics(c)}
                        className="p-2 text-gray-400 hover:text-indigo-600 transition"
                      >
                        <BarChart3 className="w-4 h-4" />
                      </button>
                    </Tooltip>
                    <Tooltip content="Edit campaign details, dates, and UTM parameters" side="left">
                      <button
                        onClick={() => openEdit(c)}
                        className="p-2 text-gray-400 hover:text-gold transition"
                      >
                        <Edit2 className="w-4 h-4" />
                      </button>
                    </Tooltip>
                    <Tooltip content="Delete this campaign permanently" side="left">
                      <button
                        onClick={() => handleDelete(c._id, c.name)}
                        className="p-2 text-gray-400 hover:text-red-500 transition"
                      >
                        <Trash2 className="w-4 h-4" />
                      </button>
                    </Tooltip>
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}

      {/* Create/Edit Modal */}
      {modalOpen && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setModalOpen(false)}>
          <div className="bg-white rounded-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-5">
              <h2 className="text-lg font-semibold text-gray-900">
                {editingCampaign ? "Edit Campaign" : "New Campaign"}
              </h2>
              <button onClick={() => setModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSave} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-900 mb-1">Campaign Name *</label>
                  <input
                    type="text"
                    required
                    value={form.name}
                    onChange={(e) => setForm({ ...form, name: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="e.g. Spring Service Promo"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">Type *</label>
                  <select
                    value={form.type}
                    onChange={(e) => setForm({ ...form, type: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  >
                    {CAMPAIGN_TYPES.map((t) => (
                      <option key={t} value={t}>{TYPE_LABELS[t] || t}</option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">Status</label>
                  <select
                    value={form.status}
                    onChange={(e) => setForm({ ...form, status: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  >
                    {CAMPAIGN_STATUSES.map((s) => (
                      <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
                    ))}
                  </select>
                </div>

                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-900 mb-1">Description</label>
                  <textarea
                    value={form.description}
                    onChange={(e) => setForm({ ...form, description: e.target.value })}
                    rows={2}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="Campaign description or goal"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">Budget ($)</label>
                  <input
                    type="number"
                    min="0"
                    step="0.01"
                    value={form.budget}
                    onChange={(e) => setForm({ ...form, budget: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="0.00"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">Target Audience</label>
                  <input
                    type="text"
                    value={form.targetAudience}
                    onChange={(e) => setForm({ ...form, targetAudience: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="e.g. Target demographic, interested in our services"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">Start Date</label>
                  <input
                    type="date"
                    value={form.startDate}
                    onChange={(e) => setForm({ ...form, startDate: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">End Date</label>
                  <input
                    type="date"
                    value={form.endDate}
                    onChange={(e) => setForm({ ...form, endDate: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  />
                </div>

                <div className="col-span-2">
                  <label className="block text-sm font-medium text-gray-900 mb-1">Tags (comma separated)</label>
                  <input
                    type="text"
                    value={form.tags}
                    onChange={(e) => setForm({ ...form, tags: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    placeholder="service_lead, health, q1-2026"
                  />
                </div>

                {/* UTM Section */}
                <div className="col-span-2">
                  <div className="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3 pt-1 border-t border-gray-100">UTM Parameters</div>
                  <div className="grid grid-cols-3 gap-3">
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">utm_source</label>
                      <input
                        type="text"
                        value={form.utmSource}
                        onChange={(e) => setForm({ ...form, utmSource: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                        placeholder="facebook"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">utm_medium</label>
                      <input
                        type="text"
                        value={form.utmMedium}
                        onChange={(e) => setForm({ ...form, utmMedium: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                        placeholder="paid_social"
                      />
                    </div>
                    <div>
                      <label className="block text-xs font-medium text-gray-700 mb-1">utm_campaign</label>
                      <input
                        type="text"
                        value={form.utmCampaign}
                        onChange={(e) => setForm({ ...form, utmCampaign: e.target.value })}
                        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                        placeholder="spring_promo_2026"
                      />
                    </div>
                  </div>
                  {(form.utmSource || form.utmMedium || form.utmCampaign) && (
                    <div className="mt-2 p-2 bg-gray-50 rounded-lg text-xs text-gray-500 font-mono break-all">
                      {process.env.NEXT_PUBLIC_FRONTEND_URL || "https://example.com"}?
                      {[
                        form.utmSource && `utm_source=${form.utmSource}`,
                        form.utmMedium && `utm_medium=${form.utmMedium}`,
                        form.utmCampaign && `utm_campaign=${form.utmCampaign}`,
                      ].filter(Boolean).join("&")}
                    </div>
                  )}
                </div>
              </div>

              <div className="flex justify-end gap-3 pt-4">
                <button type="button" onClick={() => setModalOpen(false)} className="px-4 py-2 border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition">
                  Cancel
                </button>
                <button type="submit" disabled={saving} className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition disabled:opacity-50">
                  {saving ? "Saving..." : editingCampaign ? "Update Campaign" : "Create Campaign"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Metrics Update Modal */}
      {metricsModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setMetricsModal(null)}>
          <div className="bg-white rounded-xl w-full max-w-md p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-5">
              <h2 className="text-lg font-semibold text-gray-900">Update Metrics — {metricsModal.name}</h2>
              <button onClick={() => setMetricsModal(null)} className="text-gray-400 hover:text-gray-600"><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={handleSaveMetrics} className="space-y-3">
              {([
                ["impressions", "Impressions"],
                ["clicks", "Clicks"],
                ["conversions", "Conversions"],
                ["spend", "Spend ($)"],
                ["costPerLead", "Cost Per Lead ($)"],
                ["revenue", "Revenue ($)"],
              ] as [keyof CampaignMetrics, string][]).map(([key, label]) => (
                <div key={key}>
                  <label className="block text-sm font-medium text-gray-900 mb-1">{label}</label>
                  <input
                    type="number"
                    min="0"
                    step={key === "impressions" || key === "clicks" || key === "conversions" ? "1" : "0.01"}
                    value={(metricsForm as any)[key] ?? 0}
                    onChange={(e) => setMetricsForm({ ...metricsForm, [key]: parseFloat(e.target.value) || 0 })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  />
                </div>
              ))}
              <div className="flex justify-end gap-3 pt-3">
                <button type="button" onClick={() => setMetricsModal(null)} className="px-4 py-2 border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition">Save Metrics</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
