"use client";

import { useEffect, useState } from "react";
import { Save, Globe, RefreshCw, Plus, Trash2, CheckCircle } from "lucide-react";

interface PricingTier {
  _id: string;
  name: string;
  description: string;
  priceMonthly: number;
  features: string[];
  published: boolean;
  sortOrder: number;
}

type TierFormData = Omit<PricingTier, "_id">;

const EMPTY_TIER: TierFormData = {
  name: "",
  description: "",
  priceMonthly: 0,
  features: [""],
  published: false,
  sortOrder: 0,
};

function Field({
  label,
  value,
  onChange,
  placeholder = "",
  hint = "",
}: {
  label: string;
  value: string;
  onChange: (v: string) => void;
  placeholder?: string;
  hint?: string;
}) {
  return (
    <div>
      <label className="block text-sm font-medium text-gray-700 mb-1">{label}</label>
      {hint && <p className="text-xs text-gray-400 mb-1.5">{hint}</p>}
      <input
        type="text"
        value={value}
        onChange={(e) => onChange(e.target.value)}
        placeholder={placeholder}
        className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
      />
    </div>
  );
}

export default function PricingTiersPage() {
  const [tiers, setTiers] = useState<PricingTier[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [showForm, setShowForm] = useState(false);
  const [editingId, setEditingId] = useState<string | null>(null);
  const [form, setForm] = useState<TierFormData>(EMPTY_TIER);
  const [saving, setSaving] = useState(false);

  const load = async () => {
    setLoading(true);
    setError(null);
    try {
      const res = await fetch("/api/pricing-tiers");
      const data = await res.json();
      setTiers(Array.isArray(data) ? data : []);
    } catch {
      setError("Failed to load tiers");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    load();
  }, []);

  const openCreate = () => {
    setEditingId(null);
    setForm(EMPTY_TIER);
    setShowForm(true);
  };

  const openEdit = (t: PricingTier) => {
    setEditingId(t._id);
    setForm({
      name: t.name,
      description: t.description,
      priceMonthly: t.priceMonthly,
      features: [...t.features],
      published: t.published,
      sortOrder: t.sortOrder,
    });
    setShowForm(true);
  };

  const closeForm = () => {
    setShowForm(false);
    setEditingId(null);
    setForm(EMPTY_TIER);
  };

  const handleSaveTier = async () => {
    if (!form.name.trim()) return;
    setSaving(true);
    setError(null);
    try {
      const body = { ...form, features: form.features.filter((f) => f.trim()) };
      const res = editingId
        ? await fetch(`/api/pricing-tiers/${editingId}`, {
            method: "PATCH",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(body),
          })
        : await fetch("/api/pricing-tiers", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(body),
          });
      if (!res.ok) throw new Error("Save failed");
      await load();
      closeForm();
    } catch {
      setError("Failed to save tier");
    } finally {
      setSaving(false);
    }
  };

  const handleTogglePublish = async (t: PricingTier) => {
    setError(null);
    try {
      await fetch(`/api/pricing-tiers/${t._id}`, {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ published: !t.published }),
      });
      await load();
    } catch {
      setError("Failed to update tier");
    }
  };

  const handleDelete = async (id: string) => {
    if (!confirm("Delete this pricing tier?")) return;
    setError(null);
    try {
      await fetch(`/api/pricing-tiers/${id}`, { method: "DELETE" });
      await load();
    } catch {
      setError("Failed to delete tier");
    }
  };

  const setFeature = (i: number, v: string) =>
    setForm((f) => ({
      ...f,
      features: f.features.map((x, j) => (j === i ? v : x)),
    }));
  const addFeature = () =>
    setForm((f) => ({ ...f, features: [...f.features, ""] }));
  const removeFeature = (i: number) =>
    setForm((f) => ({ ...f, features: f.features.filter((_, j) => j !== i) }));

  return (
    <div className="space-y-4">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-display text-gray-900">Pricing Tiers</h1>
          <p className="text-sm text-gray-500 mt-0.5">
            Manage subscription tiers shown on aesoftwaresolutions.com. Published tiers go live immediately.
          </p>
        </div>
        <div className="flex items-center gap-3">
          <button
            onClick={load}
            className="p-2 border border-gray-200 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition"
          >
            <RefreshCw className="w-4 h-4" />
          </button>
          <button
            onClick={openCreate}
            className="inline-flex items-center gap-2 px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition"
          >
            <Plus className="w-4 h-4" /> Add Pricing Tier
          </button>
        </div>
      </div>

      {error && (
        <div className="bg-red-50 border border-red-200 rounded-xl p-3 text-red-700 text-sm">
          {error}
        </div>
      )}

      {loading ? (
        <div className="animate-pulse h-48 bg-white rounded-xl border" />
      ) : tiers.length === 0 ? (
        <div className="text-center py-16 bg-white rounded-xl border border-dashed border-gray-200 text-gray-400 text-sm">
          No pricing tiers yet. Click <strong>Add Pricing Tier</strong> to create one.
        </div>
      ) : (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          {tiers.map((tier) => (
            <div
              key={tier._id}
              className="bg-white rounded-xl border border-gray-200 p-5 space-y-3"
            >
              <div className="flex items-start justify-between gap-3">
                <div>
                  <div className="flex items-center gap-2">
                    <h3 className="font-semibold text-gray-900">{tier.name}</h3>
                    <span
                      className={`text-xs px-2 py-0.5 rounded-full font-medium ${
                        tier.published
                          ? "bg-emerald-100 text-emerald-700"
                          : "bg-gray-100 text-gray-500"
                      }`}
                    >
                      {tier.published ? "Published" : "Draft"}
                    </span>
                  </div>
                  <p className="text-sm text-gray-500 mt-0.5">{tier.description}</p>
                </div>
                <div className="text-right shrink-0">
                  <span className="text-2xl font-bold text-gray-900">
                    ${tier.priceMonthly}
                  </span>
                  <span className="text-gray-400 text-sm"> / monthly</span>
                </div>
              </div>
              <ul className="space-y-1">
                {tier.features.map((f, i) => (
                  <li key={i} className="flex items-center gap-2 text-sm text-gray-600">
                    <CheckCircle className="w-3.5 h-3.5 text-emerald-500 shrink-0" />
                    {f}
                  </li>
                ))}
              </ul>
              <div className="flex items-center gap-2 pt-1">
                <button
                  onClick={() => openEdit(tier)}
                  className="flex items-center gap-1.5 px-3 py-1.5 border border-gray-200 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-50 transition"
                >
                  <Save className="w-3 h-3" /> Edit
                </button>
                <button
                  onClick={() => handleTogglePublish(tier)}
                  className={`flex items-center gap-1.5 px-3 py-1.5 border rounded-lg text-xs font-medium transition ${
                    tier.published
                      ? "border-amber-300 text-amber-700 hover:bg-amber-50"
                      : "border-emerald-300 text-emerald-700 hover:bg-emerald-50"
                  }`}
                >
                  <Globe className="w-3 h-3" />
                  {tier.published ? "Unpublish" : "Publish"}
                </button>
                <button
                  onClick={() => handleDelete(tier._id)}
                  className="ml-auto p-1.5 text-gray-400 hover:text-red-500 transition"
                >
                  <Trash2 className="w-4 h-4" />
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      {/* Create / Edit form */}
      {showForm && (
        <div className="bg-white rounded-xl border-2 border-gold/40 p-6 space-y-4 shadow-sm">
          <div className="flex items-center justify-between">
            <h3 className="font-semibold text-gray-900">
              {editingId ? "Edit Pricing Tier" : "New Pricing Tier"}
            </h3>
            <button
              onClick={closeForm}
              className="text-gray-400 hover:text-gray-700 transition text-xl leading-none"
            >
              &times;
            </button>
          </div>
          <div className="grid grid-cols-2 gap-4">
            <Field
              label="Tier Name"
              value={form.name}
              onChange={(v) => setForm((f) => ({ ...f, name: v }))}
              placeholder='e.g. "TRT Standard"'
            />
            <Field
              label="Monthly Price ($)"
              value={String(form.priceMonthly)}
              onChange={(v) =>
                setForm((f) => ({ ...f, priceMonthly: parseFloat(v) || 0 }))
              }
              placeholder="199"
            />
          </div>
          <Field
            label="Description"
            value={form.description}
            onChange={(v) => setForm((f) => ({ ...f, description: v }))}
            placeholder='e.g. "Testosterone replacement therapy with monthly check-ins"'
          />
          <div className="grid grid-cols-2 gap-4">
            <Field
              label="Sort Order"
              value={String(form.sortOrder)}
              onChange={(v) => setForm((f) => ({ ...f, sortOrder: parseInt(v) || 0 }))}
              hint="Lower = first"
            />
            <div className="flex items-center gap-3 pt-6">
              <input
                type="checkbox"
                id="publish-now"
                checked={form.published}
                onChange={(e) =>
                  setForm((f) => ({ ...f, published: e.target.checked }))
                }
                className="w-4 h-4 accent-gold"
              />
              <label htmlFor="publish-now" className="text-sm text-gray-700">
                Publish immediately
              </label>
            </div>
          </div>
          <div>
            <div className="flex items-center justify-between mb-2">
              <label className="text-sm font-medium text-gray-700">Features</label>
              <button
                onClick={addFeature}
                className="text-xs text-gold hover:text-gold-light flex items-center gap-1"
              >
                <Plus className="w-3 h-3" /> Add Feature
              </button>
            </div>
            <div className="space-y-2">
              {form.features.map((f, i) => (
                <div key={i} className="flex gap-2">
                  <input
                    type="text"
                    value={f}
                    onChange={(e) => setFeature(i, e.target.value)}
                    placeholder={`Feature ${i + 1}`}
                    className="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                  />
                  <button
                    onClick={() => removeFeature(i)}
                    className="p-1.5 text-gray-400 hover:text-red-500"
                  >
                    <Trash2 className="w-3.5 h-3.5" />
                  </button>
                </div>
              ))}
            </div>
          </div>
          <div className="flex gap-3 pt-2">
            <button
              onClick={handleSaveTier}
              disabled={saving || !form.name.trim()}
              className="inline-flex items-center gap-2 px-5 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition disabled:opacity-50"
            >
              {saving ? (
                <>
                  <RefreshCw className="w-4 h-4 animate-spin" /> Saving...
                </>
              ) : (
                <>
                  <Save className="w-4 h-4" /> {editingId ? "Save Changes" : "Create Tier"}
                </>
              )}
            </button>
            <button
              onClick={closeForm}
              className="px-4 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition"
            >
              Cancel
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
