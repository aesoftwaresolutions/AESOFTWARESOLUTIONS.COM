"use client";

import { useEffect, useState, useCallback } from "react";
import Link from "next/link";
import { Plus, Search, ChevronLeft, ChevronRight, Trash2, Edit, Eye, X } from "lucide-react";
import { formatDate } from "@/lib/utils";
import { Tooltip } from "@/components/ui/tooltip";
import { STATES } from "@/lib/constants";
import {
  tenantConfig,
  getStatusColorMap,
  getStatusDescriptionMap,
  getTierLabelMap,
} from "@/lib/tenant";

interface Contact {
  _id: string;
  firstName: string;
  lastName: string;
  email: string;
  phone?: string;
  state?: string;
  status: string;
  source?: string;
  tier?: string;
  tags: string[];
  createdAt: string;
  assignedTo?: { name: string };
}

const STATUS_COLORS = getStatusColorMap();
const STATUS_DESCRIPTIONS = getStatusDescriptionMap();
const TIER_LABELS = getTierLabelMap();

export default function ContactsPage() {
  const [contacts, setContacts] = useState<Contact[]>([]);
  const [total, setTotal] = useState(0);
  const [page, setPage] = useState(1);
  const [pages, setPages] = useState(1);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("all");
  const [stateFilter, setStateFilter] = useState("all");
  const [loading, setLoading] = useState(true);
  const [showCreate, setShowCreate] = useState(false);
  const [showEdit, setShowEdit] = useState<Contact | null>(null);

  const defaultSource = tenantConfig.contacts.defaultSource;
  const defaultStatus = tenantConfig.contacts.defaultStatus;

  // Form state
  const [form, setForm] = useState({
    firstName: "", lastName: "", email: "", phone: "", state: "",
    status: defaultStatus, source: defaultSource, tier: "", notes: "",
  });

  const fetchContacts = useCallback(async () => {
    setLoading(true);
    const params = new URLSearchParams({ page: String(page), limit: "15" });
    if (search) params.set("search", search);
    if (statusFilter !== "all") params.set("status", statusFilter);
    if (stateFilter !== "all") params.set("state", stateFilter);

    const res = await fetch(`/api/contacts?${params}`);
    const data = await res.json();
    setContacts(data.contacts || []);
    setTotal(data.total || 0);
    setPages(data.pages || 1);
    setLoading(false);
  }, [page, search, statusFilter, stateFilter]);

  useEffect(() => { fetchContacts(); }, [fetchContacts]);

  // Debounced search
  const [searchInput, setSearchInput] = useState("");
  useEffect(() => {
    const t = setTimeout(() => { setSearch(searchInput); setPage(1); }, 400);
    return () => clearTimeout(t);
  }, [searchInput]);

  async function handleCreate(e: React.FormEvent) {
    e.preventDefault();
    const res = await fetch("/api/contacts", { method: "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify(form) });
    if (res.ok) {
      setShowCreate(false);
      setForm({ firstName: "", lastName: "", email: "", phone: "", state: "", status: defaultStatus, source: defaultSource, tier: "", notes: "" });
      fetchContacts();
    } else {
      const err = await res.json();
      alert(err.error || "Error creating contact");
    }
  }

  async function handleUpdate(e: React.FormEvent) {
    e.preventDefault();
    if (!showEdit) return;
    const res = await fetch(`/api/contacts/${showEdit._id}`, { method: "PATCH", headers: { "Content-Type": "application/json" }, body: JSON.stringify(form) });
    if (res.ok) {
      setShowEdit(null);
      fetchContacts();
    }
  }

  async function handleDelete(id: string) {
    if (!confirm("Are you sure? This will cancel this contact.")) return;
    await fetch(`/api/contacts/${id}`, { method: "DELETE" });
    fetchContacts();
  }

  function openEdit(c: Contact) {
    setForm({ firstName: c.firstName, lastName: c.lastName, email: c.email, phone: c.phone || "", state: c.state || "", status: c.status, source: c.source || defaultSource, tier: c.tier || "", notes: "" });
    setShowEdit(c);
  }

  const entityPlural = tenantConfig.contacts.entityLabelPlural;

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
          <h1 className="text-2xl font-display text-gray-900">{entityPlural}</h1>
          <p className="text-sm text-gray-500">{total} total {entityPlural.toLowerCase()}</p>
        </div>
        <button onClick={() => { setForm({ firstName: "", lastName: "", email: "", phone: "", state: "", status: defaultStatus, source: defaultSource, tier: "", notes: "" }); setShowCreate(true); }} className="inline-flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition">
          <Plus className="w-4 h-4" /> Add {tenantConfig.contacts.entityLabel}
        </button>
      </div>

      {/* Filters */}
      <div className="bg-white rounded-xl border border-gray-200 p-4 flex flex-col sm:flex-row gap-3">
        <div className="relative flex-1">
          <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
          <input value={searchInput} onChange={(e) => setSearchInput(e.target.value)} placeholder="Search name, email, phone..." className="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold/30" />
        </div>
        <select value={statusFilter} onChange={(e) => { setStatusFilter(e.target.value); setPage(1); }} className="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50">
          <option value="all">All Statuses</option>
          {tenantConfig.contacts.statuses.map((s) => <option key={s.id} value={s.id}>{s.label}</option>)}
        </select>
        <select value={stateFilter} onChange={(e) => { setStateFilter(e.target.value); setPage(1); }} className="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50">
          <option value="all">All States</option>
          {STATES.map((s) => <option key={s} value={s}>{s}</option>)}
        </select>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-sm">
            <thead className="bg-gray-50 border-b border-gray-200">
              <tr>
                <th className="text-left px-4 py-3 font-medium text-gray-600">Name</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600 hidden sm:table-cell">Email</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600 hidden md:table-cell">State</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600">Status</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600 hidden lg:table-cell">Source</th>
                <th className="text-left px-4 py-3 font-medium text-gray-600 hidden lg:table-cell">Created</th>
                <th className="text-right px-4 py-3 font-medium text-gray-600">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-gray-100">
              {loading ? (
                [...Array(5)].map((_, i) => (
                  <tr key={i}><td colSpan={7} className="px-4 py-4"><div className="h-4 bg-gray-100 rounded animate-pulse" /></td></tr>
                ))
              ) : contacts.length === 0 ? (
                <tr><td colSpan={7} className="px-4 py-12 text-center text-gray-400">No {entityPlural.toLowerCase()} found</td></tr>
              ) : contacts.map((c) => (
                <tr key={c._id} className="hover:bg-gray-50 transition">
                  <td className="px-4 py-3">
                    <Link href={`/contacts/${c._id}`} className="font-medium text-gray-900 hover:text-gold transition">{c.firstName} {c.lastName}</Link>
                    {c.tags?.includes("vip") && <span className="ml-2 text-[10px] bg-gold/20 text-gold px-1.5 py-0.5 rounded-full font-semibold">VIP</span>}
                  </td>
                  <td className="px-4 py-3 text-gray-600 hidden sm:table-cell">{c.email}</td>
                  <td className="px-4 py-3 text-gray-600 hidden md:table-cell">{c.state || "—"}</td>
                  <td className="px-4 py-3">
                    <Tooltip content={STATUS_DESCRIPTIONS[c.status] || c.status} side="top">
                      <span className={`text-xs px-2.5 py-1 rounded-full font-medium cursor-default ${STATUS_COLORS[c.status] || "bg-gray-100"}`}>{c.status.replace(/_/g, " ")}</span>
                    </Tooltip>
                  </td>
                  <td className="px-4 py-3 text-gray-500 hidden lg:table-cell capitalize">{c.source?.replace(/_/g, " ") || "—"}</td>
                  <td className="px-4 py-3 text-gray-500 hidden lg:table-cell">{formatDate(c.createdAt)}</td>
                  <td className="px-4 py-3 text-right">
                    <div className="flex items-center justify-end gap-1">
                      <Tooltip content={`View full ${tenantConfig.contacts.entityLabel.toLowerCase()} profile`} side="top">
                        <Link href={`/contacts/${c._id}`} className="p-1.5 hover:bg-gray-100 rounded-md flex"><Eye className="w-4 h-4 text-gray-400" /></Link>
                      </Tooltip>
                      <Tooltip content={`Edit ${tenantConfig.contacts.entityLabel.toLowerCase()} information`} side="top">
                        <button onClick={() => openEdit(c)} className="p-1.5 hover:bg-gray-100 rounded-md"><Edit className="w-4 h-4 text-gray-400" /></button>
                      </Tooltip>
                      <Tooltip content={`Cancel this ${tenantConfig.contacts.entityLabel.toLowerCase()}`} side="top">
                        <button onClick={() => handleDelete(c._id)} className="p-1.5 hover:bg-red-50 rounded-md"><Trash2 className="w-4 h-4 text-gray-400 hover:text-red-500" /></button>
                      </Tooltip>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Pagination */}
        {pages > 1 && (
          <div className="flex items-center justify-between px-4 py-3 border-t border-gray-200">
            <span className="text-sm text-gray-500">Page {page} of {pages}</span>
            <div className="flex gap-2">
              <button onClick={() => setPage(Math.max(1, page - 1))} disabled={page <= 1} className="p-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50"><ChevronLeft className="w-4 h-4" /></button>
              <button onClick={() => setPage(Math.min(pages, page + 1))} disabled={page >= pages} className="p-2 border rounded-lg hover:bg-gray-50 disabled:opacity-50"><ChevronRight className="w-4 h-4" /></button>
            </div>
          </div>
        )}
      </div>

      {/* Create/Edit Modal */}
      {(showCreate || showEdit) && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => { setShowCreate(false); setShowEdit(null); }}>
          <div className="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-5">
              <h2 className="text-lg font-semibold">{showEdit ? `Edit ${tenantConfig.contacts.entityLabel}` : `New ${tenantConfig.contacts.entityLabel}`}</h2>
              <button onClick={() => { setShowCreate(false); setShowEdit(null); }} className="p-1 hover:bg-gray-100 rounded-md"><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={showEdit ? handleUpdate : handleCreate} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                  <input value={form.firstName} onChange={(e) => setForm({ ...form, firstName: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                  <input value={form.lastName} onChange={(e) => setForm({ ...form, lastName: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" value={form.email} onChange={(e) => setForm({ ...form, email: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                  <input value={form.phone} onChange={(e) => setForm({ ...form, phone: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">State</label>
                  <select value={form.state} onChange={(e) => setForm({ ...form, state: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">Select</option>
                    {STATES.map((s) => <option key={s} value={s}>{s}</option>)}
                  </select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                  <select value={form.status} onChange={(e) => setForm({ ...form, status: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    {tenantConfig.contacts.statuses.map((s) => <option key={s.id} value={s.id}>{s.label}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Tier</label>
                  <select value={form.tier} onChange={(e) => setForm({ ...form, tier: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">None</option>
                    {tenantConfig.billing.tiers.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                  </select>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Source</label>
                <select value={form.source} onChange={(e) => setForm({ ...form, source: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                  {tenantConfig.contacts.sources.map((s) => <option key={s.id} value={s.id}>{s.label}</option>)}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea value={form.notes} onChange={(e) => setForm({ ...form, notes: e.target.value })} rows={3} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button type="button" onClick={() => { setShowCreate(false); setShowEdit(null); }} className="px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm">{showEdit ? "Save Changes" : `Create ${tenantConfig.contacts.entityLabel}`}</button>
              </div>
            </form>
          </div>
        </div>
      )}

    </div>
  );
}
