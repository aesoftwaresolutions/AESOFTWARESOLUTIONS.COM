"use client";

import { useEffect, useState, useCallback } from "react";
import { Save, RefreshCw, Plus, Trash2, ExternalLink, CheckCircle } from "lucide-react";
import { Tooltip } from "@/components/ui/tooltip";

// ── Types ──────────────────────────────────────────────────────────────────────

interface HeroContent {
  badge: string;
  eyebrow: string;
  headline: string;
  subheadline: string;
  description: string;
  ctaPrimary: string;
  ctaSecondary: string;
}

interface Benefit {
  icon: string;
  title: string;
  desc: string;
}

interface Plan {
  name: string;
  price: string;
  firstMonth: string;
  savings: string;
  popular: boolean;
  features: string[];
}

interface Testimonial {
  name: string;
  age: number;
  protocol: string;
  quote: string;
  rating: number;
  stat: string;
  statLabel: string;
}

interface FAQ {
  q: string;
  a: string;
}

interface SiteContent {
  hero: HeroContent;
  benefits: { heading: string; description: string; benefits: Benefit[] };
  pricing: { heading: string; description: string; plans: Plan[] };
  testimonials: { heading: string; description: string; testimonials: Testimonial[] };
  faq: { heading: string; faqs: FAQ[] };
  cta: { heading: string; description: string; buttonText: string };
  stats: { stats: { icon: string; value: string; label: string }[] };
}

// ── Tab definitions ───────────────────────────────────────────────────────────

const TABS = [
  { id: "hero", label: "Hero" },
  { id: "benefits", label: "Benefits" },
  { id: "pricing", label: "Pricing" },
  { id: "testimonials", label: "Testimonials" },
  { id: "faq", label: "FAQ" },
  { id: "cta", label: "CTA" },
  { id: "stats", label: "Stats" },
] as const;

type TabId = (typeof TABS)[number]["id"];

// ── Reusable field components ─────────────────────────────────────────────────

function Field({
  label,
  value,
  onChange,
  multiline = false,
  placeholder = "",
  hint = "",
}: {
  label: string;
  value: string;
  onChange: (v: string) => void;
  multiline?: boolean;
  placeholder?: string;
  hint?: string;
}) {
  return (
    <div>
      <label className="block text-sm font-medium text-gray-700 mb-1">{label}</label>
      {hint && <p className="text-xs text-gray-400 mb-1.5">{hint}</p>}
      {multiline ? (
        <textarea
          value={value}
          onChange={(e) => onChange(e.target.value)}
          rows={4}
          placeholder={placeholder}
          className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 resize-y"
        />
      ) : (
        <input
          type="text"
          value={value}
          onChange={(e) => onChange(e.target.value)}
          placeholder={placeholder}
          className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
        />
      )}
    </div>
  );
}

function SectionCard({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <div className="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
      <h3 className="text-sm font-semibold text-gray-500 uppercase tracking-wider">{title}</h3>
      {children}
    </div>
  );
}

// ── Tab panels ────────────────────────────────────────────────────────────────

function HeroPanel({ data, onChange }: { data: HeroContent; onChange: (d: HeroContent) => void }) {
  const set = (key: keyof HeroContent) => (v: string) => onChange({ ...data, [key]: v });
  return (
    <div className="space-y-4">
      <SectionCard title="Hero Section">
        <Field label="Badge Text" value={data.badge} onChange={set("badge")} hint="Small banner above the headline" />
        <Field label="Eyebrow" value={data.eyebrow} onChange={set("eyebrow")} hint="All-caps label above the main headline" />
        <Field label="Headline" value={data.headline} onChange={set("headline")} hint="Large primary heading (all caps)" />
        <Field label="Subheadline" value={data.subheadline} onChange={set("subheadline")} />
        <Field label="Description" value={data.description} onChange={set("description")} multiline hint="2-3 sentence value proposition" />
        <div className="grid grid-cols-2 gap-4">
          <Field label="Primary CTA" value={data.ctaPrimary} onChange={set("ctaPrimary")} hint='e.g. "Start Free Assessment →"' />
          <Field label="Secondary CTA" value={data.ctaSecondary} onChange={set("ctaSecondary")} hint='e.g. "How It Works ↓"' />
        </div>
      </SectionCard>
    </div>
  );
}

function BenefitsPanel({
  data,
  onChange,
}: {
  data: SiteContent["benefits"];
  onChange: (d: SiteContent["benefits"]) => void;
}) {
  const setBenefit = (i: number, key: keyof Benefit, v: string) => {
    const benefits = data.benefits.map((b, idx) => (idx === i ? { ...b, [key]: v } : b));
    onChange({ ...data, benefits });
  };
  return (
    <div className="space-y-4">
      <SectionCard title="Section Header">
        <Field label="Heading" value={data.heading} onChange={(v) => onChange({ ...data, heading: v })} />
        <Field label="Description" value={data.description} onChange={(v) => onChange({ ...data, description: v })} multiline />
      </SectionCard>
      {data.benefits.map((b, i) => (
        <SectionCard key={i} title={`Benefit ${i + 1}`}>
          <div className="grid grid-cols-2 gap-4">
            <Field label="Icon" value={b.icon} onChange={(v) => setBenefit(i, "icon", v)} hint="Lucide icon name: Activity, Search, Package" />
            <Field label="Title" value={b.title} onChange={(v) => setBenefit(i, "title", v)} />
          </div>
          <Field label="Description" value={b.desc} onChange={(v) => setBenefit(i, "desc", v)} multiline />
        </SectionCard>
      ))}
    </div>
  );
}

function PricingPanel({
  data,
  onChange,
}: {
  data: SiteContent["pricing"];
  onChange: (d: SiteContent["pricing"]) => void;
}) {
  const setPlan = (i: number, key: keyof Plan, v: any) => {
    const plans = data.plans.map((p, idx) => (idx === i ? { ...p, [key]: v } : p));
    onChange({ ...data, plans });
  };
  const setFeature = (planIdx: number, featIdx: number, v: string) => {
    const plans = data.plans.map((p, i) =>
      i === planIdx
        ? { ...p, features: p.features.map((f, j) => (j === featIdx ? v : f)) }
        : p
    );
    onChange({ ...data, plans });
  };
  const addFeature = (planIdx: number) => {
    const plans = data.plans.map((p, i) =>
      i === planIdx ? { ...p, features: [...p.features, ""] } : p
    );
    onChange({ ...data, plans });
  };
  const removeFeature = (planIdx: number, featIdx: number) => {
    const plans = data.plans.map((p, i) =>
      i === planIdx ? { ...p, features: p.features.filter((_, j) => j !== featIdx) } : p
    );
    onChange({ ...data, plans });
  };

  return (
    <div className="space-y-4">
      <SectionCard title="Section Header">
        <Field label="Heading" value={data.heading} onChange={(v) => onChange({ ...data, heading: v })} />
        <Field label="Description" value={data.description} onChange={(v) => onChange({ ...data, description: v })} multiline />
      </SectionCard>
      {data.plans.map((plan, i) => (
        <SectionCard key={i} title={`Plan ${i + 1}${plan.popular ? " — Popular" : ""}`}>
          <div className="grid grid-cols-2 gap-4">
            <Field label="Plan Name" value={plan.name} onChange={(v) => setPlan(i, "name", v)} />
            <div className="flex items-center gap-3 pt-5">
              <input
                type="checkbox"
                checked={plan.popular}
                onChange={(e) => setPlan(i, "popular", e.target.checked)}
                className="w-4 h-4 accent-gold"
                id={`popular-${i}`}
              />
              <label htmlFor={`popular-${i}`} className="text-sm text-gray-700">Mark as Popular</label>
            </div>
          </div>
          <div className="grid grid-cols-3 gap-4">
            <Field label="Regular Price" value={plan.price} onChange={(v) => setPlan(i, "price", v)} hint='e.g. "$229"' />
            <Field label="First Month Price" value={plan.firstMonth} onChange={(v) => setPlan(i, "firstMonth", v)} hint='e.g. "$129"' />
            <Field label="Savings" value={plan.savings} onChange={(v) => setPlan(i, "savings", v)} hint='e.g. "$100"' />
          </div>
          <div>
            <div className="flex items-center justify-between mb-2">
              <label className="text-sm font-medium text-gray-700">Features</label>
              <button
                onClick={() => addFeature(i)}
                className="text-xs text-gold hover:text-gold-light flex items-center gap-1"
              >
                <Plus className="w-3 h-3" /> Add Feature
              </button>
            </div>
            <div className="space-y-2">
              {plan.features.map((f, j) => (
                <div key={j} className="flex gap-2">
                  <input
                    type="text"
                    value={f}
                    onChange={(e) => setFeature(i, j, e.target.value)}
                    className="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  />
                  <button
                    onClick={() => removeFeature(i, j)}
                    className="p-1.5 text-gray-400 hover:text-red-500"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                  </button>
                </div>
              ))}
            </div>
          </div>
        </SectionCard>
      ))}
    </div>
  );
}

function TestimonialsPanel({
  data,
  onChange,
}: {
  data: SiteContent["testimonials"];
  onChange: (d: SiteContent["testimonials"]) => void;
}) {
  const setT = (i: number, key: keyof Testimonial, v: any) => {
    const testimonials = data.testimonials.map((t, idx) =>
      idx === i ? { ...t, [key]: v } : t
    );
    onChange({ ...data, testimonials });
  };
  return (
    <div className="space-y-4">
      <SectionCard title="Section Header">
        <Field label="Heading" value={data.heading} onChange={(v) => onChange({ ...data, heading: v })} />
        <Field label="Description" value={data.description} onChange={(v) => onChange({ ...data, description: v })} multiline />
      </SectionCard>
      {data.testimonials.map((t, i) => (
        <SectionCard key={i} title={`Testimonial ${i + 1}`}>
          <div className="grid grid-cols-2 gap-4">
            <Field label="Name" value={t.name} onChange={(v) => setT(i, "name", v)} />
            <Field label="Protocol" value={t.protocol} onChange={(v) => setT(i, "protocol", v)} />
          </div>
          <div className="grid grid-cols-3 gap-4">
            <Field label="Age" value={String(t.age)} onChange={(v) => setT(i, "age", parseInt(v) || 0)} />
            <Field label="Stat" value={t.stat} onChange={(v) => setT(i, "stat", v)} hint='e.g. "+180 ng/dL"' />
            <Field label="Stat Label" value={t.statLabel} onChange={(v) => setT(i, "statLabel", v)} hint='e.g. "Total T increase"' />
          </div>
          <Field label="Quote" value={t.quote} onChange={(v) => setT(i, "quote", v)} multiline />
        </SectionCard>
      ))}
    </div>
  );
}

function FAQPanel({
  data,
  onChange,
}: {
  data: SiteContent["faq"];
  onChange: (d: SiteContent["faq"]) => void;
}) {
  const setFAQ = (i: number, key: keyof FAQ, v: string) => {
    const faqs = data.faqs.map((f, idx) => (idx === i ? { ...f, [key]: v } : f));
    onChange({ ...data, faqs });
  };
  const addFAQ = () => onChange({ ...data, faqs: [...data.faqs, { q: "", a: "" }] });
  const removeFAQ = (i: number) =>
    onChange({ ...data, faqs: data.faqs.filter((_, idx) => idx !== i) });

  return (
    <div className="space-y-4">
      <SectionCard title="Section Header">
        <Field label="Heading" value={data.heading} onChange={(v) => onChange({ ...data, heading: v })} />
      </SectionCard>
      {data.faqs.map((faq, i) => (
        <div key={i} className="bg-white rounded-xl border border-gray-200 p-6 space-y-3">
          <div className="flex items-center justify-between">
            <h3 className="text-sm font-semibold text-gray-500 uppercase tracking-wider">
              Question {i + 1}
            </h3>
            <button
              onClick={() => removeFAQ(i)}
              className="text-gray-400 hover:text-red-500 transition"
            >
              <Trash2 className="w-4 h-4" />
            </button>
          </div>
          <Field label="Question" value={faq.q} onChange={(v) => setFAQ(i, "q", v)} />
          <Field label="Answer" value={faq.a} onChange={(v) => setFAQ(i, "a", v)} multiline />
        </div>
      ))}
      <button
        onClick={addFAQ}
        className="w-full py-3 border-2 border-dashed border-gray-200 rounded-xl text-sm text-gray-500 hover:border-gold hover:text-gold transition flex items-center justify-center gap-2"
      >
        <Plus className="w-4 h-4" /> Add FAQ
      </button>
    </div>
  );
}

function CTAPanel({
  data,
  onChange,
}: {
  data: SiteContent["cta"];
  onChange: (d: SiteContent["cta"]) => void;
}) {
  return (
    <SectionCard title="Call to Action">
      <Field label="Heading" value={data.heading} onChange={(v) => onChange({ ...data, heading: v })} />
      <Field label="Description" value={data.description} onChange={(v) => onChange({ ...data, description: v })} multiline />
      <Field label="Button Text" value={data.buttonText} onChange={(v) => onChange({ ...data, buttonText: v })} />
    </SectionCard>
  );
}

function StatsPanel({
  data,
  onChange,
}: {
  data: SiteContent["stats"];
  onChange: (d: SiteContent["stats"]) => void;
}) {
  const setStat = (i: number, key: "icon" | "value" | "label", v: string) => {
    const stats = data.stats.map((s, idx) => (idx === i ? { ...s, [key]: v } : s));
    onChange({ stats });
  };
  return (
    <div className="space-y-4">
      {data.stats.map((s, i) => (
        <SectionCard key={i} title={`Stat ${i + 1}`}>
          <div className="grid grid-cols-3 gap-4">
            <Field label="Icon" value={s.icon} onChange={(v) => setStat(i, "icon", v)} hint="Lucide icon name" />
            <Field label="Value" value={s.value} onChange={(v) => setStat(i, "value", v)} hint='e.g. "1,000+"' />
            <Field label="Label" value={s.label} onChange={(v) => setStat(i, "label", v)} hint='e.g. "Active Patients"' />
          </div>
        </SectionCard>
      ))}
    </div>
  );
}

// ── Main page ─────────────────────────────────────────────────────────────────

export default function WebsitePage() {
  const [activeTab, setActiveTab] = useState<TabId>("hero");
  const [content, setContent] = useState<SiteContent | null>(null);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [saved, setSaved] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const load = useCallback(async () => {
    setLoading(true);
    try {
      const res = await fetch("/api/site-content");
      const data = await res.json();
      setContent(data);
    } catch (err) {
      setError("Failed to load site content");
    } finally {
      setLoading(false);
    }
  }, []);

  useEffect(() => {
    load();
  }, [load]);

  async function handleSave() {
    if (!content) return;
    setSaving(true);
    setError(null);
    try {
      const res = await fetch("/api/site-content", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(content),
      });
      if (!res.ok) throw new Error("Save failed");
      setSaved(true);
      setTimeout(() => setSaved(false), 3000);
    } catch (err) {
      setError("Failed to save changes");
    } finally {
      setSaving(false);
    }
  }

  function update<K extends keyof SiteContent>(section: K, data: SiteContent[K]) {
    setContent((prev) => (prev ? { ...prev, [section]: data } : prev));
    setSaved(false);
  }

  if (loading) {
    return (
      <div className="space-y-4">
        <h1 className="text-2xl font-display text-gray-900">Website Editor</h1>
        <div className="animate-pulse h-96 bg-white rounded-xl border" />
      </div>
    );
  }

  if (!content) {
    return (
      <div className="space-y-4">
        <h1 className="text-2xl font-display text-gray-900">Website Editor</h1>
        <div className="bg-red-50 border border-red-200 rounded-xl p-5 text-red-700">
          {error || "Failed to load content."}
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-display text-gray-900">Website Editor</h1>
          <p className="text-sm text-gray-500 mt-0.5">
            Edit the content on{" "}
            <a
              href="https://aesoftwaresolutions.com"
              target="_blank"
              rel="noopener"
              className="text-gold hover:underline inline-flex items-center gap-1"
            >
              aesoftwaresolutions.com <ExternalLink className="w-3 h-3" />
            </a>
          </p>
        </div>
        <div className="flex items-center gap-3">
          <Tooltip content="Reload content from database" side="bottom">
            <button
              onClick={load}
              className="p-2 border border-gray-200 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition"
            >
              <RefreshCw className="w-4 h-4" />
            </button>
          </Tooltip>
          <Tooltip content="Save all changes — updates go live on aesoftwaresolutions.com within 60 seconds" side="bottom">
            <button
              onClick={handleSave}
              disabled={saving}
              className="inline-flex items-center gap-2 px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition disabled:opacity-50"
            >
              {saved ? (
                <>
                  <CheckCircle className="w-4 h-4" /> Saved
                </>
              ) : saving ? (
                <>
                  <RefreshCw className="w-4 h-4 animate-spin" /> Saving...
                </>
              ) : (
                <>
                  <Save className="w-4 h-4" /> Save & Publish
                </>
              )}
            </button>
          </Tooltip>
        </div>
      </div>

      {error && (
        <div className="bg-red-50 border border-red-200 rounded-xl p-3 text-red-700 text-sm">
          {error}
        </div>
      )}

      {/* Tab bar */}
      <div className="flex gap-1 bg-white border border-gray-200 rounded-xl p-1 overflow-x-auto">
        {TABS.map((tab) => (
          <button
            key={tab.id}
            onClick={() => setActiveTab(tab.id)}
            className={`px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap transition ${
              activeTab === tab.id
                ? "bg-navy text-white"
                : "text-gray-600 hover:text-gray-900 hover:bg-gray-50"
            }`}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {/* Tab content */}
      <div className="pb-8">
        {activeTab === "hero" && (
          <HeroPanel data={content.hero} onChange={(d) => update("hero", d)} />
        )}
        {activeTab === "benefits" && (
          <BenefitsPanel data={content.benefits} onChange={(d) => update("benefits", d)} />
        )}
        {activeTab === "pricing" && (
          <PricingPanel data={content.pricing} onChange={(d) => update("pricing", d)} />
        )}
        {activeTab === "testimonials" && (
          <TestimonialsPanel
            data={content.testimonials}
            onChange={(d) => update("testimonials", d)}
          />
        )}
        {activeTab === "faq" && (
          <FAQPanel data={content.faq} onChange={(d) => update("faq", d)} />
        )}
        {activeTab === "cta" && (
          <CTAPanel data={content.cta} onChange={(d) => update("cta", d)} />
        )}
        {activeTab === "stats" && (
          <StatsPanel data={content.stats} onChange={(d) => update("stats", d)} />
        )}
      </div>

      {/* Save toast */}
      {saved && (
        <div className="fixed bottom-6 right-6 z-50 flex items-center gap-2 bg-gray-900 text-white px-4 py-3 rounded-xl shadow-xl text-sm font-medium animate-in slide-in-from-bottom-2">
          <CheckCircle className="w-4 h-4 text-green-400" />
          Changes saved — aesoftwaresolutions.com will update within 60 seconds
        </div>
      )}
    </div>
  );
}
