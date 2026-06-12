"use client";

import { useEffect, useState, useCallback } from "react";
import {
  Plus, X, Hash, Image as ImageIcon, Clock,
  Instagram, Twitter, Linkedin, Globe, Edit2, Trash2,
  ThumbsUp, Share2, MessageCircle, Eye, TrendingUp, RefreshCw,
  Send, Link2, Unlink, CheckCircle, AlertCircle, Loader2, ExternalLink,
  ChevronLeft, ChevronRight, BarChart2,
} from "lucide-react";
import {
  BarChart, Bar, XAxis, YAxis, CartesianGrid,
  Tooltip as RechartTooltip, ResponsiveContainer, LineChart, Line, Legend,
} from "recharts";
import { SOCIAL_PLATFORMS, SOCIAL_POST_STATUSES } from "@/lib/constants";

interface SocialAccount {
  _id: string;
  platform: string;
  accountName: string;
  accountHandle?: string;
  profileUrl?: string;
  avatarUrl?: string;
  followerCount?: number;
  isActive: boolean;
  lastSyncedAt?: string;
  connectedBy?: { name: string };
}

interface SocialPost {
  _id: string;
  platform: string;
  status: string;
  content: string;
  mediaUrls: string[];
  hashtags: string[];
  scheduledAt?: string;
  publishedAt?: string;
  externalPostId?: string;
  campaignId?: { _id: string; name: string };
  metrics: {
    likes: number;
    shares: number;
    comments: number;
    reach: number;
    impressions: number;
    clicks: number;
  };
  createdBy?: { name: string };
  createdAt: string;
}

const PLATFORM_ICONS: Record<string, React.ElementType> = {
  instagram: Instagram,
  facebook: Globe,
  twitter: Twitter,
  linkedin: Linkedin,
  tiktok: Globe,
};

const PLATFORM_COLORS: Record<string, string> = {
  instagram: "bg-gradient-to-r from-purple-500 to-pink-500 text-white",
  facebook: "bg-blue-600 text-white",
  twitter: "bg-sky-500 text-white",
  linkedin: "bg-blue-700 text-white",
  tiktok: "bg-slate-800 text-white",
};

const PLATFORM_RING: Record<string, string> = {
  instagram: "ring-pink-400",
  facebook: "ring-blue-500",
  twitter: "ring-sky-400",
  linkedin: "ring-blue-600",
  tiktok: "ring-slate-500",
};

const PLATFORM_SHORT: Record<string, string> = {
  instagram: "Ins",
  facebook: "Fb",
  twitter: "Twt",
  linkedin: "Li",
  tiktok: "Tik",
};

const STATUS_COLORS: Record<string, string> = {
  draft: "bg-gray-100 text-gray-600",
  scheduled: "bg-yellow-100 text-yellow-700",
  published: "bg-green-100 text-green-700",
  failed: "bg-red-100 text-red-600",
};

const CHAR_LIMITS: Record<string, number> = {
  twitter: 280,
  instagram: 2200,
  facebook: 63206,
  linkedin: 3000,
  tiktok: 2200,
};

const CONTENT_TEMPLATES = [
  {
    label: "Missed Call Recovery",
    platform: "linkedin",
    content: "62% of business calls go unanswered. PA small businesses are recovering missed revenue with automated text-back in under 30 seconds. Here's how it works...",
    hashtags: "#SmallBusiness #Pennsylvania #AIAutomation #AESoftware",
  },
  {
    label: "AI Automation Win",
    platform: "facebook",
    content: "A PA home services company reduced no-shows by 28% after deploying automated appointment reminders. The setup took 3 hours. The ROI was immediate...",
    hashtags: "#HomeServices #Automation #Pennsylvania #AESoftware",
  },
  {
    label: "Lead Response Speed",
    platform: "instagram",
    content: "78% of buyers go with the first vendor that responds. If your competitor answers at 10 PM and you don't — you've already lost the lead. Automation fixes this...",
    hashtags: "#LeadGeneration #SmallBusiness #AIAutomation",
  },
  {
    label: "5-Workflow Breakdown",
    platform: "linkedin",
    content: "5 workflows PA small businesses are deploying this month: 1. Lead capture, 2. Missed call text-back, 3. Review requests, 4. Appointment reminders, 5. Monthly reporting...",
    hashtags: "#Automation #Pennsylvania #BusinessGrowth #AESoftware",
  },
  {
    label: "Free Assessment CTA",
    platform: "twitter",
    content: "Running a PA small business? We'll show you where your biggest revenue leak is in 15 minutes — free. No pitch, just data. Link in bio.",
    hashtags: "#Pennsylvania #SmallBusiness #AI",
  },
];

const BEST_TIMES_TO_POST = [
  { platform: "Instagram", days: "Tue, Wed, Fri", times: "9-11am, 2-4pm" },
  { platform: "Facebook", days: "Wed, Thu, Fri", times: "1-4pm, 6-9pm" },
  { platform: "Twitter/X", days: "Mon-Thu", times: "8-10am, 12-1pm" },
  { platform: "LinkedIn", days: "Tue, Wed, Thu", times: "7-9am, 12pm, 5-6pm" },
  { platform: "TikTok", days: "Tue, Thu, Fri", times: "7-9am, 7-9pm" },
];

const EMPTY_FORM = {
  platform: "instagram",
  content: "",
  hashtags: "",
  mediaUrls: "",
  scheduledAt: "",
  status: "draft",
  campaignId: "",
};

function fmt(n: number) {
  if (n >= 1000000) return (n / 1000000).toFixed(1) + "M";
  if (n >= 1000) return (n / 1000).toFixed(1) + "K";
  return n.toString();
}

function fmtFollowers(n?: number) {
  if (!n) return "—";
  return fmt(n);
}

function formatDateTime(d?: string) {
  if (!d) return "";
  return new Date(d).toLocaleString(undefined, {
    month: "short", day: "numeric", hour: "numeric", minute: "2-digit",
  });
}

function getCalendarDays(month: Date): (Date | null)[] {
  const year = month.getFullYear();
  const m = month.getMonth();
  const firstDay = new Date(year, m, 1);
  const lastDay = new Date(year, m + 1, 0);
  const startPad = (firstDay.getDay() + 6) % 7;
  const days: (Date | null)[] = Array(startPad).fill(null);
  for (let d = 1; d <= lastDay.getDate(); d++) {
    days.push(new Date(year, m, d));
  }
  while (days.length % 7 !== 0) {
    days.push(null);
  }
  return days;
}

function getContentTypeTag(hashtags: string[]): string {
  const tagsStr = hashtags.join(" ").toLowerCase();
  if (tagsStr.includes("education")) return "Educational";
  if (tagsStr.includes("testimonial")) return "Testimonial";
  return "General";
}

function getEngagementRate(likes: number, comments: number, shares: number, reach: number): string {
  if (reach === 0) return "0%";
  const rate = ((likes + comments + shares) / reach) * 100;
  return rate.toFixed(1) + "%";
}

export default function SocialPage() {
  const [posts, setPosts] = useState<SocialPost[]>([]);
  const [accounts, setAccounts] = useState<SocialAccount[]>([]);
  const [loading, setLoading] = useState(true);
  const [total, setTotal] = useState(0);
  const [platformFilter, setPlatformFilter] = useState("all");
  const [statusFilter, setStatusFilter] = useState("all");
  const [tab, setTab] = useState<"posts" | "calendar" | "analytics" | "accounts">("posts");
  const [modalOpen, setModalOpen] = useState(false);
  const [editingPost, setEditingPost] = useState<SocialPost | null>(null);
  const [metricsModal, setMetricsModal] = useState<SocialPost | null>(null);
  const [form, setForm] = useState({ ...EMPTY_FORM });
  const [saving, setSaving] = useState(false);
  const [publishing, setPublishing] = useState<string | null>(null);
  const [connecting, setConnecting] = useState<string | null>(null);
  const [syncing, setSyncing] = useState(false);
  const [metricsForm, setMetricsForm] = useState<Partial<SocialPost["metrics"]>>({});
  const [urlParams, setUrlParams] = useState<Record<string, string>>({});
  const [calMonth, setCalMonth] = useState(() => new Date());
  const [showTemplates, setShowTemplates] = useState(false);

  // Parse URL params for OAuth callback messages
  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const connected = params.get("connected");
    const error = params.get("error");
    if (connected || error) {
      setUrlParams({ connected: connected || "", error: error || "" });
      window.history.replaceState({}, "", window.location.pathname);
    }
  }, []);

  const loadPosts = useCallback(() => {
    const params = new URLSearchParams();
    if (platformFilter !== "all") params.set("platform", platformFilter);
    if (statusFilter !== "all") params.set("status", statusFilter);
    setLoading(true);
    fetch(`/api/social-posts?${params}`)
      .then((r) => r.json())
      .then((d) => { setPosts(d.posts || []); setTotal(d.total || 0); })
      .finally(() => setLoading(false));
  }, [platformFilter, statusFilter]);

  const loadAccounts = useCallback(() => {
    fetch("/api/social/accounts")
      .then((r) => r.json())
      .then((d) => setAccounts(d.accounts || []));
  }, []);

  useEffect(() => { loadPosts(); }, [loadPosts]);
  useEffect(() => { loadAccounts(); }, [loadAccounts]);

  function connectedPlatforms() {
    return new Set(accounts.filter((a) => a.isActive).map((a) => a.platform));
  }

  function openCreate(date?: Date) {
    setEditingPost(null);
    const newForm = { ...EMPTY_FORM };
    if (date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      newForm.scheduledAt = `${year}-${month}-${day}T09:00`;
    }
    setForm(newForm);
    setModalOpen(true);
  }

  function openEdit(p: SocialPost) {
    setEditingPost(p);
    setForm({
      platform: p.platform,
      content: p.content,
      hashtags: p.hashtags?.join(" ") || "",
      mediaUrls: p.mediaUrls?.join("\n") || "",
      scheduledAt: p.scheduledAt ? p.scheduledAt.slice(0, 16) : "",
      status: p.status,
      campaignId: (p.campaignId as any)?._id || "",
    });
    setModalOpen(true);
  }

  function openMetrics(p: SocialPost) {
    setMetricsModal(p);
    setMetricsForm({ ...p.metrics });
  }

  function applyTemplate(template: typeof CONTENT_TEMPLATES[0]) {
    setEditingPost(null);
    setForm({
      platform: template.platform,
      content: template.content,
      hashtags: template.hashtags,
      mediaUrls: "",
      scheduledAt: "",
      status: "draft",
      campaignId: "",
    });
    setModalOpen(true);
  }

  async function handleSave(e: React.FormEvent) {
    e.preventDefault();
    setSaving(true);
    const hashtags = form.hashtags
      .split(/[\s,]+/)
      .map((h) => (h.startsWith("#") ? h : `#${h}`))
      .filter(Boolean);
    const mediaUrls = form.mediaUrls.split("\n").map((u) => u.trim()).filter(Boolean);
    const payload = {
      platform: form.platform,
      content: form.content,
      hashtags,
      mediaUrls,
      scheduledAt: form.scheduledAt || undefined,
      status: form.status,
      campaignId: form.campaignId || undefined,
    };
    try {
      if (editingPost) {
        await fetch(`/api/social-posts/${editingPost._id}`, {
          method: "PATCH", headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
      } else {
        await fetch("/api/social-posts", {
          method: "POST", headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload),
        });
      }
      setModalOpen(false);
      loadPosts();
    } finally {
      setSaving(false);
    }
  }

  async function handleDelete(id: string) {
    if (!confirm("Delete this post?")) return;
    await fetch(`/api/social-posts/${id}`, { method: "DELETE" });
    loadPosts();
  }

  async function handleStatusChange(id: string, status: string) {
    await fetch(`/api/social-posts/${id}`, {
      method: "PATCH", headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ status }),
    });
    loadPosts();
  }

  async function handlePublish(postId: string, platform: string) {
    setPublishing(postId);
    try {
      const res = await fetch(`/api/social-posts/${postId}/publish`, {
        method: "POST", headers: { "Content-Type": "application/json" },
        body: JSON.stringify({}),
      });
      const data = await res.json();
      if (!res.ok) {
        alert(`Publish failed: ${data.error}`);
      }
      loadPosts();
    } finally {
      setPublishing(null);
    }
  }

  async function handleConnect(platform: string) {
    setConnecting(platform);
    try {
      const res = await fetch("/api/social/accounts", {
        method: "POST", headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ platform }),
      });
      const data = await res.json();
      if (data.authUrl) window.location.href = data.authUrl;
    } finally {
      setConnecting(null);
    }
  }

  async function handleDisconnect(id: string, name: string) {
    if (!confirm(`Disconnect "${name}"?`)) return;
    await fetch(`/api/social/accounts/${id}`, { method: "DELETE" });
    loadAccounts();
  }

  async function handleSync() {
    setSyncing(true);
    try {
      const res = await fetch("/api/social/sync", { method: "POST" });
      const data = await res.json();
      alert(`Synced ${data.synced} posts${data.failed ? `, ${data.failed} failed` : ""}`);
      loadPosts();
    } finally {
      setSyncing(false);
    }
  }

  async function handleSaveMetrics(e: React.FormEvent) {
    e.preventDefault();
    if (!metricsModal) return;
    await fetch(`/api/social-posts/${metricsModal._id}`, {
      method: "PATCH", headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ metrics: metricsForm }),
    });
    setMetricsModal(null);
    loadPosts();
  }

  function postsForDay(date: Date): SocialPost[] {
    return posts.filter((p) => {
      const d = p.scheduledAt || p.publishedAt;
      if (!d) return false;
      const pd = new Date(d);
      return pd.getFullYear() === date.getFullYear() &&
        pd.getMonth() === date.getMonth() &&
        pd.getDate() === date.getDate();
    });
  }

  function prepareAnalyticsData() {
    const platformStats: Record<string, { platform: string; Likes: number; Shares: number; Comments: number }> = {};
    posts.forEach((p) => {
      if (!platformStats[p.platform]) {
        platformStats[p.platform] = { platform: p.platform.charAt(0).toUpperCase() + p.platform.slice(1), Likes: 0, Shares: 0, Comments: 0 };
      }
      platformStats[p.platform].Likes += p.metrics?.likes || 0;
      platformStats[p.platform].Shares += p.metrics?.shares || 0;
      platformStats[p.platform].Comments += p.metrics?.comments || 0;
    });
    return Object.values(platformStats);
  }

  function topPostsByReach(): Array<SocialPost & { reachDisplay: string }> {
    return posts
      .filter((p) => p.metrics?.reach > 0)
      .sort((a, b) => (b.metrics?.reach || 0) - (a.metrics?.reach || 0))
      .slice(0, 5)
      .map((p) => ({ ...p, reachDisplay: fmt(p.metrics?.reach || 0) }));
  }

  function calculateEngagementStats() {
    const publishedPosts = posts.filter((p) => p.status === "published");
    if (publishedPosts.length === 0) {
      return {
        avgLikes: 0,
        avgReach: 0,
        totalImpressions: 0,
        avgEngagementRate: "0%",
      };
    }
    const totalLikes = publishedPosts.reduce((sum, p) => sum + (p.metrics?.likes || 0), 0);
    const totalReach = publishedPosts.reduce((sum, p) => sum + (p.metrics?.reach || 0), 0);
    const totalImpressions = publishedPosts.reduce((sum, p) => sum + (p.metrics?.impressions || 0), 0);
    const totalEngagement = publishedPosts.reduce(
      (sum, p) => sum + (p.metrics?.likes || 0) + (p.metrics?.comments || 0) + (p.metrics?.shares || 0),
      0
    );
    const avgEngagementRate = totalReach === 0 ? "0%" : ((totalEngagement / totalReach) * 100).toFixed(1) + "%";

    return {
      avgLikes: Math.round(totalLikes / publishedPosts.length),
      avgReach: Math.round(totalReach / publishedPosts.length),
      totalImpressions,
      avgEngagementRate,
    };
  }

  const connected = connectedPlatforms();
  const totals = posts.reduce(
    (acc, p) => ({
      likes: acc.likes + (p.metrics?.likes || 0),
      shares: acc.shares + (p.metrics?.shares || 0),
      comments: acc.comments + (p.metrics?.comments || 0),
      reach: acc.reach + (p.metrics?.reach || 0),
      impressions: acc.impressions + (p.metrics?.impressions || 0),
    }),
    { likes: 0, shares: 0, comments: 0, reach: 0, impressions: 0 }
  );

  const charLimit = CHAR_LIMITS[form.platform] || 2200;
  const charCount = form.content.length;
  const overLimit = charCount > charLimit;

  const calendarDays = getCalendarDays(calMonth);
  const monthName = calMonth.toLocaleString(undefined, { month: "long", year: "numeric" });
  const analyticsData = prepareAnalyticsData();
  const topPosts = topPostsByReach();
  const engagementStats = calculateEngagementStats();

  return (
    <div className="space-y-6">
      {/* OAuth callback notifications */}
      {urlParams.connected && (
        <div className="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-4 py-3 text-sm text-green-700">
          <CheckCircle className="w-4 h-4 shrink-0" />
          <span className="capitalize">{urlParams.connected}</span> account connected successfully!
          <button onClick={() => setUrlParams({})} className="ml-auto text-green-500"><X className="w-4 h-4" /></button>
        </div>
      )}
      {urlParams.error && (
        <div className="flex items-center gap-2 bg-red-50 border border-red-200 rounded-lg px-4 py-3 text-sm text-red-700">
          <AlertCircle className="w-4 h-4 shrink-0" />
          OAuth error: {urlParams.error}
          <button onClick={() => setUrlParams({})} className="ml-auto text-red-500"><X className="w-4 h-4" /></button>
        </div>
      )}

      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-display text-gray-900">Social Media</h1>
          <p className="text-sm text-gray-500 mt-1">{total} posts · {accounts.length} connected account{accounts.length !== 1 ? "s" : ""}</p>
        </div>
        <div className="flex items-center gap-2">
          <button
            onClick={handleSync}
            disabled={syncing}
            className="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition disabled:opacity-50"
          >
            {syncing ? <Loader2 className="w-4 h-4 animate-spin" /> : <RefreshCw className="w-4 h-4" />}
            Sync Metrics
          </button>
          <button
            onClick={() => openCreate()}
            className="inline-flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition"
          >
            <Plus className="w-4 h-4" /> New Post
          </button>
        </div>
      </div>

      {/* Tabs */}
      <div className="flex gap-1 bg-gray-100 rounded-xl p-1 w-fit">
        {["posts", "calendar", "analytics", "accounts"].map((t) => (
          <button
            key={t}
            onClick={() => setTab(t as any)}
            className={`px-4 py-2 rounded-lg text-sm font-medium transition ${
              tab === t ? "bg-white text-gray-900 shadow-sm" : "text-gray-500 hover:text-gray-700"
            }`}
          >
            {t === "posts" && "Posts"}
            {t === "calendar" && "Calendar"}
            {t === "analytics" && "Analytics"}
            {t === "accounts" && (
              <>
                Connected Accounts
                {accounts.length > 0 && (
                  <span className="ml-2 bg-gold text-navy text-xs font-bold px-1.5 py-0.5 rounded-full">{accounts.length}</span>
                )}
              </>
            )}
          </button>
        ))}
      </div>

      {/* ==== CONNECTED ACCOUNTS TAB ==== */}
      {tab === "accounts" && (
        <div className="space-y-4">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {/* Connected accounts */}
            {accounts.map((acct) => {
              const Icon = PLATFORM_ICONS[acct.platform] || Globe;
              return (
                <div key={acct._id} className="bg-white rounded-xl border border-gray-200 p-4">
                  <div className="flex items-start gap-3">
                    <div className={`w-10 h-10 rounded-full flex items-center justify-center shrink-0 ${PLATFORM_COLORS[acct.platform] || "bg-gray-800 text-white"}`}>
                      {acct.avatarUrl ? (
                        <img src={acct.avatarUrl} alt={acct.accountName} className="w-10 h-10 rounded-full object-cover" />
                      ) : (
                        <Icon className="w-5 h-5" />
                      )}
                    </div>
                    <div className="flex-1 min-w-0">
                      <div className="font-semibold text-gray-900 text-sm truncate">{acct.accountName}</div>
                      {acct.accountHandle && (
                        <div className="text-xs text-gray-500">{acct.accountHandle}</div>
                      )}
                      <div className="flex items-center gap-3 mt-1 text-xs text-gray-400">
                        <span className="capitalize">{acct.platform}</span>
                        {acct.followerCount && <span>{fmtFollowers(acct.followerCount)} followers</span>}
                      </div>
                      {acct.lastSyncedAt && (
                        <div className="text-xs text-gray-400 mt-1">
                          Last sync: {formatDateTime(acct.lastSyncedAt)}
                        </div>
                      )}
                    </div>
                    <div className="flex gap-1 shrink-0">
                      {acct.profileUrl && (
                        <a href={acct.profileUrl} target="_blank" rel="noopener noreferrer"
                          className="p-1.5 text-gray-400 hover:text-blue-500 transition">
                          <ExternalLink className="w-3.5 h-3.5" />
                        </a>
                      )}
                      <button
                        onClick={() => handleDisconnect(acct._id, acct.accountName)}
                        className="p-1.5 text-gray-400 hover:text-red-500 transition"
                        title="Disconnect account"
                      >
                        <Unlink className="w-3.5 h-3.5" />
                      </button>
                    </div>
                  </div>
                </div>
              );
            })}

            {/* Connect new account cards */}
            {SOCIAL_PLATFORMS.filter((p) => !connected.has(p)).map((platform) => {
              const Icon = PLATFORM_ICONS[platform] || Globe;
              const isConnecting = connecting === platform;
              return (
                <div key={platform} className="bg-white rounded-xl border border-dashed border-gray-300 p-4 flex flex-col items-center justify-center gap-3 min-h-[120px]">
                  <div className={`w-10 h-10 rounded-full flex items-center justify-center ${PLATFORM_COLORS[platform] || "bg-gray-800 text-white"}`}>
                    <Icon className="w-5 h-5" />
                  </div>
                  <div className="text-center">
                    <p className="text-sm font-medium text-gray-700 capitalize">{platform}</p>
                    <p className="text-xs text-gray-400">Not connected</p>
                  </div>
                  <button
                    onClick={() => handleConnect(platform)}
                    disabled={isConnecting}
                    className="flex items-center gap-1.5 px-3 py-1.5 bg-gold hover:bg-gold-light text-navy text-xs font-semibold rounded-lg transition disabled:opacity-50"
                  >
                    {isConnecting ? <Loader2 className="w-3.5 h-3.5 animate-spin" /> : <Link2 className="w-3.5 h-3.5" />}
                    Connect
                  </button>
                </div>
              );
            })}
          </div>

          {accounts.length === 0 && (
            <div className="text-center py-10 bg-white rounded-xl border border-gray-200">
              <Globe className="w-12 h-12 text-gray-300 mx-auto mb-3" />
              <p className="text-gray-500 font-medium">No social accounts connected</p>
              <p className="text-sm text-gray-400 mt-1">Connect your accounts above to start publishing</p>
            </div>
          )}
        </div>
      )}

      {/* ==== POSTS TAB ==== */}
      {tab === "posts" && (
        <>
          {/* Content Templates Section */}
          <div className="space-y-3">
            <button
              onClick={() => setShowTemplates(!showTemplates)}
              className="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
            >
              {showTemplates ? <ChevronLeft className="w-4 h-4" /> : <ChevronRight className="w-4 h-4" />}
              Content Templates
            </button>
            {showTemplates && (
              <div className="flex gap-3 overflow-x-auto pb-2">
                {CONTENT_TEMPLATES.map((tmpl) => (
                  <button
                    key={tmpl.label}
                    onClick={() => applyTemplate(tmpl)}
                    className="flex-shrink-0 p-3 bg-white rounded-lg border border-gray-200 hover:shadow-md transition max-w-xs"
                  >
                    <div className="text-sm font-medium text-gray-900 text-left">{tmpl.label}</div>
                    <div className="text-xs text-gray-500 mt-1 capitalize">{tmpl.platform}</div>
                    <div className="text-xs text-gray-600 mt-2 line-clamp-2">{tmpl.content}</div>
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Platform Quick Filters */}
          <div className="flex gap-2 flex-wrap">
            {["all", ...SOCIAL_PLATFORMS].map((p) => {
              const Icon = p !== "all" ? (PLATFORM_ICONS[p] || Globe) : Globe;
              const isConn = p !== "all" && connected.has(p);
              return (
                <button
                  key={p}
                  onClick={() => setPlatformFilter(p)}
                  className={`flex items-center gap-2 px-3 py-1.5 rounded-full text-sm font-medium transition ${
                    platformFilter === p ? "bg-navy text-white" : "bg-white border border-gray-200 text-gray-600 hover:border-gray-300"
                  }`}
                >
                  {p !== "all" && <Icon className="w-3.5 h-3.5" />}
                  {p === "all" ? "All Platforms" : p.charAt(0).toUpperCase() + p.slice(1)}
                  {isConn && <span className="w-1.5 h-1.5 rounded-full bg-green-400" title="Connected" />}
                </button>
              );
            })}
          </div>

          {/* Summary Stats */}
          <div className="grid grid-cols-5 gap-4">
            {[
              { label: "Total Likes", value: fmt(totals.likes), icon: ThumbsUp, color: "text-pink-600" },
              { label: "Total Shares", value: fmt(totals.shares), icon: Share2, color: "text-blue-600" },
              { label: "Comments", value: fmt(totals.comments), icon: MessageCircle, color: "text-green-600" },
              { label: "Total Reach", value: fmt(totals.reach), icon: Eye, color: "text-purple-600" },
              { label: "Impressions", value: fmt(totals.impressions), icon: TrendingUp, color: "text-gold" },
            ].map((s) => (
              <div key={s.label} className="bg-white rounded-xl border border-gray-200 p-4">
                <div className="flex items-center justify-between mb-2">
                  <span className="text-xs font-medium text-gray-500 uppercase tracking-wide">{s.label}</span>
                  <s.icon className={`w-4 h-4 ${s.color}`} />
                </div>
                <div className="text-2xl font-bold text-gray-900">{s.value}</div>
              </div>
            ))}
          </div>

          {/* Status filter */}
          <div className="flex gap-3 items-center">
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 bg-white"
            >
              <option value="all">All Statuses</option>
              {SOCIAL_POST_STATUSES.map((s) => (
                <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
              ))}
            </select>
          </div>

          {/* Posts Grid */}
          {loading ? (
            <div className="grid grid-cols-2 gap-4">
              {[...Array(4)].map((_, i) => (
                <div key={i} className="h-40 bg-white rounded-xl border border-gray-200 animate-pulse" />
              ))}
            </div>
          ) : posts.length === 0 ? (
            <div className="text-center py-16 bg-white rounded-xl border border-gray-200">
              <Instagram className="w-12 h-12 text-gray-300 mx-auto mb-4" />
              <p className="text-gray-500 font-medium">No posts found</p>
              <p className="text-sm text-gray-400 mt-1">Create your first social post to get started</p>
            </div>
          ) : (
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
              {posts.map((p) => {
                const Icon = PLATFORM_ICONS[p.platform] || Globe;
                const isPublishing = publishing === p._id;
                const hasAccount = connected.has(p.platform);
                const contentType = getContentTypeTag(p.hashtags || []);
                const engagementRate = getEngagementRate(
                  p.metrics?.likes || 0,
                  p.metrics?.comments || 0,
                  p.metrics?.shares || 0,
                  p.metrics?.reach || 0
                );

                return (
                  <div key={p._id} className="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                    {/* Media Thumbnail */}
                    {p.mediaUrls?.length > 0 && (
                      <img src={p.mediaUrls[0]} alt="Post media" className="w-full h-32 object-cover" />
                    )}

                    <div className={`flex items-center justify-between px-4 py-2.5 ${PLATFORM_COLORS[p.platform] || "bg-gray-800 text-white"}`}>
                      <div className="flex items-center gap-2">
                        <Icon className="w-4 h-4" />
                        <span className="text-sm font-semibold capitalize">{p.platform}</span>
                      </div>
                      <span className={`px-2 py-0.5 rounded-full text-xs font-medium bg-white/20`}>
                        {p.status.charAt(0).toUpperCase() + p.status.slice(1)}
                      </span>
                    </div>

                    <div className="p-4 space-y-3">
                      {/* Content Type and Engagement Rate Badges */}
                      <div className="flex gap-2">
                        <span className="text-xs font-medium px-2 py-1 bg-gray-100 text-gray-700 rounded-full">{contentType}</span>
                        {p.status === "published" && (
                          <span className="text-xs font-medium px-2 py-1 bg-green-100 text-green-700 rounded-full">
                            {engagementRate} engagement
                          </span>
                        )}
                      </div>

                      <p className="text-sm text-gray-800 line-clamp-3 whitespace-pre-line">{p.content}</p>

                      {p.hashtags?.length > 0 && (
                        <div className="flex flex-wrap gap-1">
                          {p.hashtags.slice(0, 5).map((h) => (
                            <span key={h} className="text-xs text-blue-600">{h}</span>
                          ))}
                          {p.hashtags.length > 5 && <span className="text-xs text-gray-400">+{p.hashtags.length - 5}</span>}
                        </div>
                      )}

                      <div className="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                        {p.scheduledAt && p.status === "scheduled" && (
                          <span className="flex items-center gap-1">
                            <Clock className="w-3 h-3" /> Scheduled: {formatDateTime(p.scheduledAt)}
                          </span>
                        )}
                        {p.publishedAt && (
                          <span className="flex items-center gap-1 text-green-600">
                            <CheckCircle className="w-3 h-3" /> {formatDateTime(p.publishedAt)}
                          </span>
                        )}
                        {p.externalPostId && (
                          <span className="text-gray-400 font-mono text-xs">ID: {p.externalPostId.slice(0, 12)}…</span>
                        )}
                      </div>

                      {p.status === "published" && (
                        <div className="grid grid-cols-5 gap-2 bg-gray-50 rounded-lg p-2 text-center">
                          {[
                            { label: "Likes", value: fmt(p.metrics?.likes || 0) },
                            { label: "Shares", value: fmt(p.metrics?.shares || 0) },
                            { label: "Comments", value: fmt(p.metrics?.comments || 0) },
                            { label: "Reach", value: fmt(p.metrics?.reach || 0) },
                            { label: "Impr.", value: fmt(p.metrics?.impressions || 0) },
                          ].map((m) => (
                            <div key={m.label}>
                              <div className="text-xs text-gray-400">{m.label}</div>
                              <div className="text-xs font-semibold text-gray-700">{m.value}</div>
                            </div>
                          ))}
                        </div>
                      )}

                      <div className="flex items-center justify-between pt-1 border-t border-gray-100">
                        <div className="flex items-center gap-2">
                          <select
                            value={p.status}
                            onChange={(e) => handleStatusChange(p._id, e.target.value)}
                            className="px-2 py-1 text-xs border border-gray-200 rounded-lg focus:outline-none"
                          >
                            {SOCIAL_POST_STATUSES.map((s) => (
                              <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
                            ))}
                          </select>

                          {/* Publish button */}
                          {(p.status === "draft" || p.status === "scheduled") && (
                            <button
                              onClick={() => handlePublish(p._id, p.platform)}
                              disabled={isPublishing || !hasAccount}
                              title={!hasAccount ? `Connect a ${p.platform} account first` : "Publish now"}
                              className="flex items-center gap-1 px-2.5 py-1 bg-gold hover:bg-gold-light text-navy text-xs font-semibold rounded-lg transition disabled:opacity-40"
                            >
                              {isPublishing ? <Loader2 className="w-3.5 h-3.5 animate-spin" /> : <Send className="w-3.5 h-3.5" />}
                              Publish
                            </button>
                          )}
                        </div>

                        <div className="flex items-center gap-1.5">
                          {p.status === "published" && (
                            <button onClick={() => openMetrics(p)} className="p-1.5 text-gray-400 hover:text-indigo-600 transition">
                              <TrendingUp className="w-4 h-4" />
                            </button>
                          )}
                          <button onClick={() => openEdit(p)} className="p-1.5 text-gray-400 hover:text-gold transition">
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button onClick={() => handleDelete(p._id)} className="p-1.5 text-gray-400 hover:text-red-500 transition">
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </>
      )}

      {/* ==== CALENDAR TAB ==== */}
      {tab === "calendar" && (
        <div className="bg-white rounded-xl border border-gray-200 p-6">
          {/* Calendar Header */}
          <div className="flex items-center justify-between mb-6">
            <h2 className="text-xl font-semibold text-gray-900">{monthName}</h2>
            <div className="flex gap-2">
              <button
                onClick={() => setCalMonth(new Date(calMonth.getFullYear(), calMonth.getMonth() - 1))}
                className="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition"
              >
                <ChevronLeft className="w-5 h-5" />
              </button>
              <button
                onClick={() => setCalMonth(new Date(calMonth.getFullYear(), calMonth.getMonth() + 1))}
                className="p-2 text-gray-600 hover:bg-gray-100 rounded-lg transition"
              >
                <ChevronRight className="w-5 h-5" />
              </button>
            </div>
          </div>

          {/* Day Headers */}
          <div className="grid grid-cols-7 gap-1 mb-2">
            {["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"].map((day) => (
              <div key={day} className="text-center font-medium text-sm text-gray-600 py-2">
                {day}
              </div>
            ))}
          </div>

          {/* Calendar Grid */}
          <div className="grid grid-cols-7 gap-1">
            {calendarDays.map((date, idx) => {
              const isToday = date && new Date().toDateString() === date.toDateString();
              const dayPosts = date ? postsForDay(date) : [];
              const displayPosts = dayPosts.slice(0, 3);
              const moreCount = dayPosts.length > 3 ? dayPosts.length - 3 : 0;

              return (
                <button
                  key={idx}
                  onClick={() => date && openCreate(date)}
                  className={`min-h-[100px] border rounded-lg p-2 text-left transition cursor-pointer ${
                    isToday
                      ? "bg-gold/10 border-gold"
                      : date
                        ? "border-gray-100 hover:bg-gray-50"
                        : "border-transparent bg-gray-50"
                  }`}
                >
                  {date && (
                    <>
                      <div className={`text-sm font-medium ${isToday ? "text-gold font-bold" : "text-gray-700"}`}>
                        {date.getDate()}
                      </div>
                      <div className="mt-1 space-y-1">
                        {displayPosts.map((post) => (
                          <div
                            key={post._id}
                            className={`text-[10px] font-medium truncate px-1.5 py-0.5 rounded text-white ${
                              PLATFORM_COLORS[post.platform] || "bg-gray-800"
                            }`}
                          >
                            {PLATFORM_SHORT[post.platform] || post.platform.slice(0, 3)}
                          </div>
                        ))}
                        {moreCount > 0 && (
                          <div className="text-[10px] font-medium text-gray-500 px-1.5">
                            +{moreCount} more
                          </div>
                        )}
                      </div>
                    </>
                  )}
                </button>
              );
            })}
          </div>

          <div className="mt-6 p-4 bg-gray-50 rounded-lg text-sm text-gray-600">
            <p>Click on any day to create a new post scheduled for that date.</p>
          </div>
        </div>
      )}

      {/* ==== ANALYTICS TAB ==== */}
      {tab === "analytics" && (
        <div className="space-y-6">
          {/* Engagement Rate Cards */}
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            {[
              { label: "Avg Likes/Post", value: fmt(engagementStats.avgLikes), icon: ThumbsUp, color: "text-pink-600" },
              { label: "Avg Reach/Post", value: fmt(engagementStats.avgReach), icon: Eye, color: "text-purple-600" },
              { label: "Total Impressions", value: fmt(engagementStats.totalImpressions), icon: TrendingUp, color: "text-gold" },
              { label: "Avg Engagement Rate", value: engagementStats.avgEngagementRate, icon: BarChart2, color: "text-blue-600" },
            ].map((stat) => (
              <div key={stat.label} className="bg-white rounded-xl border border-gray-200 p-4">
                <div className="flex items-center justify-between mb-2">
                  <span className="text-xs font-medium text-gray-500 uppercase tracking-wide">{stat.label}</span>
                  <stat.icon className={`w-4 h-4 ${stat.color}`} />
                </div>
                <div className="text-2xl font-bold text-gray-900">{stat.value}</div>
              </div>
            ))}
          </div>

          {/* Engagement by Platform Chart */}
          {analyticsData.length > 0 && (
            <div className="bg-white rounded-xl border border-gray-200 p-6">
              <h3 className="text-lg font-semibold text-gray-900 mb-4">Engagement by Platform</h3>
              <ResponsiveContainer width="100%" height={300}>
                <BarChart data={analyticsData}>
                  <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
                  <XAxis dataKey="platform" stroke="#9ca3af" />
                  <YAxis stroke="#9ca3af" />
                  <RechartTooltip />
                  <Legend />
                  <Bar dataKey="Likes" fill="#ec4899" />
                  <Bar dataKey="Shares" fill="#3b82f6" />
                  <Bar dataKey="Comments" fill="#10b981" />
                </BarChart>
              </ResponsiveContainer>
            </div>
          )}

          {/* Top 5 Posts by Reach */}
          {topPosts.length > 0 && (
            <div className="bg-white rounded-xl border border-gray-200 p-6">
              <h3 className="text-lg font-semibold text-gray-900 mb-4">Top 5 Posts by Reach</h3>
              <div className="space-y-3">
                {topPosts.map((post, idx) => (
                  <div key={post._id} className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div className="flex-1 min-w-0">
                      <div className="text-sm font-medium text-gray-900 line-clamp-2">{post.content}</div>
                      <div className="text-xs text-gray-500 mt-1 capitalize">{post.platform}</div>
                    </div>
                    <div className="flex-shrink-0 ml-4 text-right">
                      <div className="text-lg font-bold text-gray-900">{post.reachDisplay}</div>
                      <div className="text-xs text-gray-500">Reach</div>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          )}

          {/* Best Times to Post */}
          <div className="bg-white rounded-xl border border-gray-200 p-6">
            <h3 className="text-lg font-semibold text-gray-900 mb-4">Best Times to Post (EST)</h3>
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead>
                  <tr className="border-b border-gray-200">
                    <th className="px-4 py-3 text-left font-semibold text-gray-700">Platform</th>
                    <th className="px-4 py-3 text-left font-semibold text-gray-700">Best Days</th>
                    <th className="px-4 py-3 text-left font-semibold text-gray-700">Best Times</th>
                  </tr>
                </thead>
                <tbody>
                  {BEST_TIMES_TO_POST.map((row) => (
                    <tr key={row.platform} className="border-b border-gray-100 hover:bg-gray-50">
                      <td className="px-4 py-3 text-gray-900 font-medium">{row.platform}</td>
                      <td className="px-4 py-3 text-gray-600">{row.days}</td>
                      <td className="px-4 py-3 text-gray-600">{row.times}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      )}

      {/* Create/Edit Modal */}
      {modalOpen && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setModalOpen(false)}>
          <div className="bg-white rounded-xl w-full max-w-xl p-6 max-h-[90vh] overflow-y-auto" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-5">
              <h2 className="text-lg font-semibold text-gray-900">
                {editingPost ? "Edit Post" : "New Social Post"}
              </h2>
              <button onClick={() => setModalOpen(false)} className="text-gray-400 hover:text-gray-600"><X className="w-5 h-5" /></button>
            </div>

            <form onSubmit={handleSave} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-900 mb-1">Platform *</label>
                  <select
                    value={form.platform}
                    onChange={(e) => setForm({ ...form, platform: e.target.value })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  >
                    {SOCIAL_PLATFORMS.map((p) => (
                      <option key={p} value={p}>
                        {p.charAt(0).toUpperCase() + p.slice(1)}
                        {connected.has(p) ? " ✓" : ""}
                      </option>
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
                    {SOCIAL_POST_STATUSES.map((s) => (
                      <option key={s} value={s}>{s.charAt(0).toUpperCase() + s.slice(1)}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div>
                <div className="flex items-center justify-between mb-1">
                  <label className="block text-sm font-medium text-gray-900">Content *</label>
                  <span className={`text-xs ${overLimit ? "text-red-500 font-semibold" : "text-gray-400"}`}>
                    {charCount}/{charLimit}
                  </span>
                </div>
                <textarea
                  required
                  value={form.content}
                  onChange={(e) => setForm({ ...form, content: e.target.value })}
                  rows={5}
                  className={`w-full px-3 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 resize-none ${overLimit ? "border-red-400" : "border-gray-200"}`}
                  placeholder="Write your post content..."
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-900 mb-1">
                  <span className="flex items-center gap-1.5"><Hash className="w-4 h-4" /> Hashtags</span>
                </label>
                <input
                  type="text"
                  value={form.hashtags}
                  onChange={(e) => setForm({ ...form, hashtags: e.target.value })}
                  className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  placeholder="service health wellness"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-900 mb-1">
                  <span className="flex items-center gap-1.5"><ImageIcon className="w-4 h-4" /> Media URLs (one per line)</span>
                </label>
                <textarea
                  value={form.mediaUrls}
                  onChange={(e) => setForm({ ...form, mediaUrls: e.target.value })}
                  rows={2}
                  className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  placeholder="https://cdn.example.com/image.jpg"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-900 mb-1">
                  <span className="flex items-center gap-1.5"><Clock className="w-4 h-4" /> Schedule Date & Time</span>
                </label>
                <input
                  type="datetime-local"
                  value={form.scheduledAt}
                  onChange={(e) => setForm({ ...form, scheduledAt: e.target.value })}
                  className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                />
              </div>

              <div className="flex justify-end gap-3 pt-3">
                <button type="button" onClick={() => setModalOpen(false)} className="px-4 py-2 border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition">
                  Cancel
                </button>
                <button type="submit" disabled={saving || overLimit} className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition disabled:opacity-50">
                  {saving ? "Saving..." : editingPost ? "Update Post" : "Create Post"}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Metrics Modal */}
      {metricsModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setMetricsModal(null)}>
          <div className="bg-white rounded-xl w-full max-w-sm p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-5">
              <h2 className="text-lg font-semibold text-gray-900">Update Metrics</h2>
              <button onClick={() => setMetricsModal(null)} className="text-gray-400 hover:text-gray-600"><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={handleSaveMetrics} className="space-y-3">
              {([
                ["likes", "Likes"],
                ["shares", "Shares"],
                ["comments", "Comments"],
                ["reach", "Reach"],
                ["impressions", "Impressions"],
                ["clicks", "Link Clicks"],
              ] as [keyof SocialPost["metrics"], string][]).map(([key, label]) => (
                <div key={key}>
                  <label className="block text-sm font-medium text-gray-900 mb-1">{label}</label>
                  <input
                    type="number"
                    min="0"
                    value={(metricsForm as any)[key] ?? 0}
                    onChange={(e) => setMetricsForm({ ...metricsForm, [key]: parseInt(e.target.value) || 0 })}
                    className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  />
                </div>
              ))}
              <div className="flex justify-end gap-3 pt-3">
                <button type="button" onClick={() => setMetricsModal(null)} className="px-4 py-2 border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm">Save Metrics</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
