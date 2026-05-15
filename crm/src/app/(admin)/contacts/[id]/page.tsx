"use client";

import { useEffect, useState, useCallback } from "react";
import { useParams, useRouter } from "next/navigation";
import Link from "next/link";
import { formatDistanceToNow } from "date-fns";
import { formatDate, formatCurrency } from "@/lib/utils";
import {
  STATES,
  TASK_PRIORITY,
} from "@/lib/constants";
import {
  tenantConfig,
  getStatusColorMap,
  getTierLabelMap,
  formatTenantCurrency,
} from "@/lib/tenant";
import {
  ArrowLeft, Edit, Trash2, Phone, Mail, MapPin, Tag, User, Star,
  Clock, CheckSquare, Calendar, MessageSquare, TrendingUp, CreditCard,
  Plus, X, Check, ChevronDown, Video, Activity, FileText,
  Pencil, AlertCircle, ThermometerSun,
} from "lucide-react";

// ── Types ──────────────────────────────────────────────────────────────────

interface Note {
  _id: string;
  body: string;
  authorName: string;
  createdAt: string;
}

interface ContactData {
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
  notes?: string;
  notesList?: Note[];
  leadScore?: number;
  leadTemperature?: string;
  primaryPathway?: string;
  interests?: Record<string, boolean>;
  customData?: Record<string, unknown>;
  readiness?: string;
  timeline?: string;
  assessmentId?: string;
  assignedTo?: { _id: string; name: string; email: string };
  createdAt: string;
  updatedAt: string;
}

interface Opp {
  _id: string;
  stage: string;
  tier?: string;
  value?: number;
  notes?: string;
  createdAt: string;
}

interface Appt {
  _id: string;
  type: string;
  status: string;
  scheduledAt: string;
  duration: number;
  provider?: string;
  videoLink?: string;
}

interface TaskItem {
  _id: string;
  title: string;
  description?: string;
  status: string;
  priority: string;
  dueDate?: string;
  createdAt: string;
}

interface Order {
  _id: string;
  type: string;
  status: string;
  trackingNumber?: string;
  createdAt: string;
}

interface Sub {
  _id: string;
  tier: string;
  status: string;
  priceInCents: number;
  currentPeriodStart?: string;
  currentPeriodEnd?: string;
  createdAt: string;
}

// ── Constants (from tenant config) ─────────────────────────────────────────

const STATUS_COLORS = getStatusColorMap();
const TIER_LABELS = getTierLabelMap();

const PRIORITY_COLORS: Record<string, string> = {
  urgent: "bg-red-100 text-red-700",
  high: "bg-orange-100 text-orange-700",
  medium: "bg-yellow-100 text-yellow-700",
  low: "bg-blue-100 text-blue-700",
};

const TABS = [
  { id: "activity", label: "Activity", icon: Activity },
  { id: "notes", label: "Notes", icon: FileText },
  { id: "tasks", label: "Tasks", icon: CheckSquare },
  { id: "appointments", label: "Appointments", icon: Calendar },
  { id: "opportunities", label: "Pipeline", icon: TrendingUp },
  { id: "payments", label: "Payments", icon: CreditCard },
];

// ── Helpers ────────────────────────────────────────────────────────────────

function relativeTime(date: string) {
  try {
    return formatDistanceToNow(new Date(date), { addSuffix: true });
  } catch {
    return formatDate(date);
  }
}

function initials(first: string, last: string) {
  return `${first[0] || ""}${last[0] || ""}`.toUpperCase();
}

// ── Main Component ─────────────────────────────────────────────────────────

export default function ContactDetailPage() {
  const { id } = useParams<{ id: string }>();
  const router = useRouter();

  const [contact, setContact] = useState<ContactData | null>(null);
  const [opportunities, setOpportunities] = useState<Opp[]>([]);
  const [appointments, setAppointments] = useState<Appt[]>([]);
  const [tasks, setTasks] = useState<TaskItem[]>([]);
  const [orders, setOrders] = useState<Order[]>([]);
  const [subscriptions, setSubscriptions] = useState<Sub[]>([]);
  const [notesList, setNotesList] = useState<Note[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const [activeTab, setActiveTab] = useState("activity");
  const [noteInput, setNoteInput] = useState("");
  const [savingNote, setSavingNote] = useState(false);

  // Inline field editing
  const [editingField, setEditingField] = useState<string | null>(null);
  const [editValue, setEditValue] = useState<string>("");

  // Edit contact modal
  const [showEditModal, setShowEditModal] = useState(false);
  const [editForm, setEditForm] = useState({
    firstName: "", lastName: "", email: "", phone: "",
    state: "", status: "lead", source: "direct", tier: "", notes: "",
  });

  // Task modal
  const [showTaskModal, setShowTaskModal] = useState(false);
  const [taskForm, setTaskForm] = useState({ title: "", description: "", priority: "medium", dueDate: "" });
  const [editingTask, setEditingTask] = useState<TaskItem | null>(null);

  // Appointment modal
  const [showApptModal, setShowApptModal] = useState(false);
  const [apptForm, setApptForm] = useState({ type: "initial_consult", scheduledAt: "", duration: 30, provider: "", videoLink: "" });

  // Opportunity modal
  const [showOppModal, setShowOppModal] = useState(false);
  const [oppForm, setOppForm] = useState({ stage: "new_lead", tier: "", value: "" });

  // ── Fetch ────────────────────────────────────────────────────────────────

  const fetchData = useCallback(async () => {
    try {
      const res = await fetch(`/api/contacts/${id}`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const data = await res.json();
      setContact(data.contact);
      setOpportunities(data.opportunities || []);
      setAppointments(data.appointments || []);
      setTasks(data.tasks || []);
      setOrders(data.orders || []);
      setSubscriptions(data.subscriptions || []);
      setNotesList((data.contact.notesList || []).slice().reverse());
    } catch (e: unknown) {
      setError(e instanceof Error ? e.message : "Failed to load");
    } finally {
      setLoading(false);
    }
  }, [id]);

  useEffect(() => { fetchData(); }, [fetchData]);

  // ── Inline field save ────────────────────────────────────────────────────

  async function saveField(field: string, value: string) {
    setEditingField(null);
    if (!contact) return;
    const prev = (contact as unknown as Record<string, unknown>)[field];
    setContact({ ...contact, [field]: value } as ContactData);
    try {
      await fetch(`/api/contacts/${id}`, {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ [field]: value }),
      });
    } catch {
      setContact({ ...contact, [field]: prev } as ContactData);
    }
  }

  function startEdit(field: string, current: string) {
    setEditingField(field);
    setEditValue(current);
  }

  // ── Notes ────────────────────────────────────────────────────────────────

  async function saveNote() {
    if (!noteInput.trim()) return;
    setSavingNote(true);
    try {
      const res = await fetch(`/api/contacts/${id}/notes`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ body: noteInput.trim() }),
      });
      const data = await res.json();
      if (res.ok) {
        setNotesList([data.note, ...notesList]);
        setNoteInput("");
      }
    } finally {
      setSavingNote(false);
    }
  }

  // ── Task actions ─────────────────────────────────────────────────────────

  async function toggleTask(task: TaskItem) {
    const newStatus = task.status === "done" ? "todo" : "done";
    setTasks(tasks.map((t) => t._id === task._id ? { ...t, status: newStatus } : t));
    await fetch("/api/tasks", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: task._id, status: newStatus, completedAt: newStatus === "done" ? new Date().toISOString() : null }),
    });
  }

  async function createTask(e: React.FormEvent) {
    e.preventDefault();
    const res = await fetch("/api/tasks", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ...taskForm, contactId: id, dueDate: taskForm.dueDate || undefined }),
    });
    if (res.ok) {
      const data = await res.json();
      setTasks([data.task || { ...taskForm, _id: Date.now().toString(), contactId: id, status: "todo", createdAt: new Date().toISOString() }, ...tasks]);
      setShowTaskModal(false);
      setTaskForm({ title: "", description: "", priority: "medium", dueDate: "" });
      fetchData();
    }
  }

  // ── Appointment actions ──────────────────────────────────────────────────

  async function createAppt(e: React.FormEvent) {
    e.preventDefault();
    const res = await fetch("/api/appointments", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ ...apptForm, contactId: id }),
    });
    if (res.ok) {
      setShowApptModal(false);
      setApptForm({ type: "initial_consult", scheduledAt: "", duration: 30, provider: "", videoLink: "" });
      fetchData();
    }
  }

  // ── Opportunity actions ──────────────────────────────────────────────────

  async function createOpp(e: React.FormEvent) {
    e.preventDefault();
    const res = await fetch("/api/pipeline", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        contactId: id,
        stage: oppForm.stage,
        tier: oppForm.tier || undefined,
        value: oppForm.value ? Math.round(parseFloat(oppForm.value) * 100) : 0,
      }),
    });
    if (res.ok) {
      setShowOppModal(false);
      setOppForm({ stage: "new_lead", tier: "", value: "" });
      fetchData();
    }
  }

  async function moveOppStage(oppId: string, stage: string) {
    setOpportunities(opportunities.map((o) => o._id === oppId ? { ...o, stage } : o));
    await fetch("/api/pipeline", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id: oppId, stage }),
    });
  }

  // ── Edit contact ─────────────────────────────────────────────────────────

  function openEditModal() {
    if (!contact) return;
    setEditForm({
      firstName: contact.firstName,
      lastName: contact.lastName,
      email: contact.email,
      phone: contact.phone || "",
      state: contact.state || "",
      status: contact.status,
      source: contact.source || "direct",
      tier: contact.tier || "",
      notes: contact.notes || "",
    });
    setShowEditModal(true);
  }

  async function handleEditSubmit(e: React.FormEvent) {
    e.preventDefault();
    const res = await fetch(`/api/contacts/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(editForm),
    });
    if (res.ok) {
      setShowEditModal(false);
      fetchData();
    }
  }

  async function handleDelete() {
    if (!confirm(`Delete ${contact?.firstName} ${contact?.lastName}? This will mark them as cancelled.`)) return;
    await fetch(`/api/contacts/${id}`, { method: "DELETE" });
    router.push("/contacts");
  }

  // ── Tag management ────────────────────────────────────────────────────────

  async function removeTag(tag: string) {
    if (!contact) return;
    const newTags = contact.tags.filter((t) => t !== tag);
    setContact({ ...contact, tags: newTags });
    await fetch(`/api/contacts/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ tags: newTags }),
    });
  }

  const [newTag, setNewTag] = useState("");
  async function addTag(e: React.KeyboardEvent) {
    if (e.key !== "Enter" || !newTag.trim() || !contact) return;
    const trimmed = newTag.trim().toLowerCase();
    if (contact.tags.includes(trimmed)) { setNewTag(""); return; }
    const newTags = [...contact.tags, trimmed];
    setContact({ ...contact, tags: newTags });
    setNewTag("");
    await fetch(`/api/contacts/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ tags: newTags }),
    });
  }

  // ── Activity synthesis ────────────────────────────────────────────────────

  function buildActivityFeed() {
    const events: { date: string; type: string; label: string; sub?: string }[] = [];
    if (contact) {
      events.push({ date: contact.createdAt, type: "created", label: "Lead created", sub: contact.source ? `Source: ${contact.source.replace(/_/g, " ")}` : undefined });
    }
    appointments.forEach((a) => {
      events.push({ date: a.scheduledAt, type: "appointment", label: `Appointment: ${a.type.replace(/_/g, " ")}`, sub: `${a.status} · ${a.duration}min${a.provider ? ` with ${a.provider}` : ""}` });
    });
    orders.forEach((o) => {
      events.push({ date: o.createdAt, type: "order", label: `Order: ${o.type.replace(/_/g, " ")}`, sub: `Status: ${o.status}` });
    });
    subscriptions.forEach((s) => {
      events.push({ date: s.createdAt, type: "subscription", label: `Subscription: ${TIER_LABELS[s.tier] || s.tier}`, sub: `${s.status} · ${formatTenantCurrency(s.priceInCents)}/mo` });
    });
    opportunities.forEach((o) => {
      events.push({ date: o.createdAt, type: "opportunity", label: `Pipeline: ${o.stage.replace(/_/g, " ")}`, sub: o.value ? formatTenantCurrency(o.value) + "/mo" : undefined });
    });
    return events.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime());
  }

  const ACTIVITY_DOT: Record<string, string> = {
    created: "bg-blue-500",
    appointment: "bg-purple-500",
    order: "bg-cyan-500",
    subscription: "bg-green-500",
    opportunity: "bg-gold",
  };

  // ── Loading / Error ────────────────────────────────────────────────────────

  if (loading) {
    return (
      <div className="space-y-4">
        <div className="h-8 w-48 bg-gray-200 rounded animate-pulse" />
        <div className="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6">
          <div className="h-[600px] bg-gray-100 rounded-xl animate-pulse" />
          <div className="h-[600px] bg-gray-100 rounded-xl animate-pulse" />
        </div>
      </div>
    );
  }

  if (error || !contact) {
    return (
      <div className="space-y-4">
        <Link href="/contacts" className="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900">
          <ArrowLeft className="w-4 h-4" /> Contacts
        </Link>
        <div className="bg-red-50 border border-red-200 rounded-xl p-6 text-red-700 flex items-center gap-3">
          <AlertCircle className="w-5 h-5" />
          {error || "Contact not found"}
        </div>
      </div>
    );
  }

  const activityFeed = buildActivityFeed();
  const openTasks = tasks.filter((t) => t.status !== "done" && t.status !== "cancelled");
  const doneTasks = tasks.filter((t) => t.status === "done");

  // ── Render ─────────────────────────────────────────────────────────────────

  return (
    <div className="space-y-4 pb-8">
      {/* Page header */}
      <div className="flex items-center justify-between">
        <Link href="/contacts" className="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-900 transition-colors">
          <ArrowLeft className="w-4 h-4" />
          Contacts
        </Link>
        <div className="flex items-center gap-2">
          <button
            onClick={openEditModal}
            className="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50 transition-colors"
          >
            <Edit className="w-4 h-4" /> Edit
          </button>
          <button
            onClick={handleDelete}
            className="inline-flex items-center gap-2 px-3 py-2 border border-red-200 text-red-600 rounded-lg text-sm hover:bg-red-50 transition-colors"
          >
            <Trash2 className="w-4 h-4" /> Delete
          </button>
        </div>
      </div>

      {/* Two-column body */}
      <div className="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-6 items-start">

        {/* ── LEFT SIDEBAR ───────────────────────────────────────────────── */}
        <div className="bg-white rounded-xl border border-gray-200 p-5 space-y-5 sticky top-4">

          {/* Avatar + name */}
          <div className="text-center">
            <div className="w-16 h-16 rounded-full bg-gold flex items-center justify-center mx-auto mb-3">
              <span className="text-xl font-bold text-navy">{initials(contact.firstName, contact.lastName)}</span>
            </div>
            <h1 className="text-lg font-semibold text-gray-900">{contact.firstName} {contact.lastName}</h1>
            <p className="text-sm text-gray-500">{contact.email}</p>
            {contact.phone && <p className="text-sm text-gray-500">{contact.phone}</p>}
          </div>

          {/* Status + tier badges */}
          <div className="flex flex-wrap gap-2 justify-center">
            <select
              value={contact.status}
              onChange={(e) => saveField("status", e.target.value)}
              className={`text-xs px-2.5 py-1 rounded-full font-medium border-0 cursor-pointer ${STATUS_COLORS[contact.status] || "bg-gray-100 text-gray-600"}`}
            >
              {tenantConfig.contacts.statuses.map((s) => (
                <option key={s.id} value={s.id}>{s.label}</option>
              ))}
            </select>
            {contact.tier && (
              <span className="text-xs px-2.5 py-1 rounded-full bg-gold/10 text-gold font-medium">
                {TIER_LABELS[contact.tier] || contact.tier}
              </span>
            )}
          </div>

          {/* Lead score */}
          {contact.leadScore !== undefined && contact.leadScore !== null && (
            <div>
              <div className="flex items-center justify-between mb-1.5">
                <span className="text-xs font-medium text-gray-600">Lead Score</span>
                <div className="flex items-center gap-1.5">
                  {contact.leadTemperature && (
                    <span className={`w-2 h-2 rounded-full ${contact.leadTemperature === "hot" ? "bg-red-500" : contact.leadTemperature === "warm" ? "bg-amber-500" : "bg-blue-400"}`} />
                  )}
                  <span className={`text-xs font-semibold ${contact.leadScore >= 70 ? "text-green-600" : contact.leadScore >= 40 ? "text-amber-600" : "text-red-600"}`}>
                    {contact.leadScore}/100 {contact.leadTemperature && `· ${contact.leadTemperature}`}
                  </span>
                </div>
              </div>
              <div className="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div
                  className={`h-full rounded-full transition-all ${contact.leadScore >= 70 ? "bg-green-500" : contact.leadScore >= 40 ? "bg-amber-500" : "bg-red-500"}`}
                  style={{ width: `${contact.leadScore}%` }}
                />
              </div>
            </div>
          )}

          <div className="border-t border-gray-100" />

          {/* Editable fields */}
          <div className="space-y-3">
            {/* State */}
            <div className="flex items-center justify-between gap-2">
              <div className="flex items-center gap-2 text-gray-500 min-w-0">
                <MapPin className="w-3.5 h-3.5 shrink-0" />
                {editingField === "state" ? (
                  <select
                    autoFocus
                    value={editValue}
                    onChange={(e) => setEditValue(e.target.value)}
                    onBlur={() => saveField("state", editValue)}
                    className="text-sm border border-gray-200 rounded px-2 py-0.5 w-full"
                  >
                    <option value="">Select state</option>
                    {STATES.map((s) => <option key={s} value={s}>{s}</option>)}
                  </select>
                ) : (
                  <span className="text-sm text-gray-700 truncate">{contact.state || "—"}</span>
                )}
              </div>
              {editingField !== "state" && (
                <button onClick={() => startEdit("state", contact.state || "")} className="p-1 hover:bg-gray-100 rounded shrink-0">
                  <Pencil className="w-3 h-3 text-gray-400" />
                </button>
              )}
            </div>

            {/* Source */}
            <div className="flex items-center justify-between gap-2">
              <div className="flex items-center gap-2 text-gray-500 min-w-0">
                <TrendingUp className="w-3.5 h-3.5 shrink-0" />
                {editingField === "source" ? (
                  <input
                    autoFocus
                    value={editValue}
                    onChange={(e) => setEditValue(e.target.value)}
                    onBlur={() => saveField("source", editValue)}
                    onKeyDown={(e) => e.key === "Enter" && saveField("source", editValue)}
                    className="text-sm border border-gray-200 rounded px-2 py-0.5 w-full"
                  />
                ) : (
                  <span className="text-sm text-gray-700 truncate capitalize">{contact.source?.replace(/_/g, " ") || "—"}</span>
                )}
              </div>
              {editingField !== "source" && (
                <button onClick={() => startEdit("source", contact.source || "")} className="p-1 hover:bg-gray-100 rounded shrink-0">
                  <Pencil className="w-3 h-3 text-gray-400" />
                </button>
              )}
            </div>

            {/* Tier */}
            <div className="flex items-center justify-between gap-2">
              <div className="flex items-center gap-2 text-gray-500 min-w-0">
                <Star className="w-3.5 h-3.5 shrink-0" />
                {editingField === "tier" ? (
                  <select
                    autoFocus
                    value={editValue}
                    onChange={(e) => setEditValue(e.target.value)}
                    onBlur={() => saveField("tier", editValue)}
                    className="text-sm border border-gray-200 rounded px-2 py-0.5 w-full"
                  >
                    <option value="">None</option>
                    {tenantConfig.billing.tiers.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                  </select>
                ) : (
                  <span className="text-sm text-gray-700 truncate">{contact.tier ? TIER_LABELS[contact.tier] : "—"}</span>
                )}
              </div>
              {editingField !== "tier" && (
                <button onClick={() => startEdit("tier", contact.tier || "")} className="p-1 hover:bg-gray-100 rounded shrink-0">
                  <Pencil className="w-3 h-3 text-gray-400" />
                </button>
              )}
            </div>

            {/* Assigned To */}
            <div className="flex items-center gap-2 text-gray-500">
              <User className="w-3.5 h-3.5 shrink-0" />
              <span className="text-sm text-gray-700 truncate">{contact.assignedTo?.name || "Unassigned"}</span>
            </div>
          </div>

          {/* Tags */}
          <div>
            <div className="flex items-center gap-2 mb-2">
              <Tag className="w-3.5 h-3.5 text-gray-400" />
              <span className="text-xs font-medium text-gray-500 uppercase tracking-wide">Tags</span>
            </div>
            <div className="flex flex-wrap gap-1.5 mb-2">
              {contact.tags.map((tag) => (
                <span key={tag} className="inline-flex items-center gap-1 text-xs px-2 py-0.5 bg-gray-100 rounded-full text-gray-600">
                  {tag}
                  <button onClick={() => removeTag(tag)} className="hover:text-red-500 transition-colors">
                    <X className="w-2.5 h-2.5" />
                  </button>
                </span>
              ))}
            </div>
            <input
              value={newTag}
              onChange={(e) => setNewTag(e.target.value)}
              onKeyDown={addTag}
              placeholder="Add tag, press Enter"
              className="w-full text-xs px-2.5 py-1.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-gold/30"
            />
          </div>

          {/* Custom fields from tenant config */}
          {tenantConfig.contacts.customFields.length > 0 && (contact.customData || contact.primaryPathway || contact.readiness) && (
            <>
              <div className="border-t border-gray-100" />
              <div className="space-y-2">
                <p className="text-xs font-medium text-gray-500 uppercase tracking-wide">Details</p>
                {contact.primaryPathway && (
                  <div className="flex justify-between text-sm">
                    <span className="text-gray-500">Pathway</span>
                    <span className="font-medium capitalize">{contact.primaryPathway.replace(/_/g, " ")}</span>
                  </div>
                )}
                {contact.interests && (
                  <div className="flex gap-2 flex-wrap">
                    {Object.entries(contact.interests).filter(([, v]) => v).map(([key]) => (
                      <span key={key} className="text-xs px-2 py-0.5 bg-blue-50 text-blue-700 rounded-full uppercase">{key}</span>
                    ))}
                  </div>
                )}
                {contact.readiness && (
                  <div className="flex justify-between text-sm">
                    <span className="text-gray-500">Readiness</span>
                    <span className="font-medium capitalize">{contact.readiness.replace(/_/g, " ")}</span>
                  </div>
                )}
                {contact.timeline && (
                  <div className="flex justify-between text-sm">
                    <span className="text-gray-500">Timeline</span>
                    <span className="font-medium capitalize">{contact.timeline.replace(/_/g, " ")}</span>
                  </div>
                )}
                {contact.customData && Object.entries(contact.customData).map(([key, val]) => {
                  if (!val) return null;
                  const fieldConfig = tenantConfig.contacts.customFields.find((f) => f.key === key);
                  return (
                    <div key={key} className="flex justify-between text-sm">
                      <span className="text-gray-500">{fieldConfig?.label || key}</span>
                      <span className="font-medium capitalize">{String(val).replace(/_/g, " ")}</span>
                    </div>
                  );
                })}
              </div>
            </>
          )}

          <div className="border-t border-gray-100" />
          <p className="text-xs text-gray-400 text-center">Added {formatDate(contact.createdAt)}</p>
        </div>

        {/* ── RIGHT: TABS ────────────────────────────────────────────────── */}
        <div className="bg-white rounded-xl border border-gray-200 overflow-hidden">
          {/* Tab nav */}
          <div className="border-b border-gray-200 overflow-x-auto">
            <div className="flex min-w-max">
              {TABS.map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`flex items-center gap-2 px-5 py-3.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap ${
                    activeTab === tab.id
                      ? "border-gold text-gold"
                      : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                  }`}
                >
                  <tab.icon className="w-4 h-4" />
                  {tab.label}
                  {tab.id === "tasks" && openTasks.length > 0 && (
                    <span className="ml-1 bg-orange-100 text-orange-700 text-xs px-1.5 py-0.5 rounded-full font-medium">
                      {openTasks.length}
                    </span>
                  )}
                </button>
              ))}
            </div>
          </div>

          {/* Tab content */}
          <div className="p-6">

            {/* ── ACTIVITY ─────────────────────────────────────────────── */}
            {activeTab === "activity" && (
              <div className="space-y-1">
                <h3 className="text-sm font-medium text-gray-500 mb-4">Timeline</h3>
                {activityFeed.length === 0 ? (
                  <div className="text-center py-10 text-gray-400">
                    <Activity className="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p className="text-sm">No activity yet</p>
                  </div>
                ) : (
                  <div className="relative">
                    <div className="absolute left-[7px] top-2 bottom-2 w-px bg-gray-200" />
                    <div className="space-y-5">
                      {activityFeed.map((event, i) => (
                        <div key={i} className="flex gap-4 relative">
                          <div className={`w-3.5 h-3.5 rounded-full shrink-0 mt-0.5 border-2 border-white shadow-sm ${ACTIVITY_DOT[event.type] || "bg-gray-400"}`} />
                          <div className="min-w-0">
                            <p className="text-sm font-medium text-gray-900 capitalize">{event.label}</p>
                            {event.sub && <p className="text-xs text-gray-500 mt-0.5 capitalize">{event.sub}</p>}
                            <p className="text-xs text-gray-400 mt-0.5">{relativeTime(event.date)}</p>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>
            )}

            {/* ── NOTES ────────────────────────────────────────────────── */}
            {activeTab === "notes" && (
              <div className="space-y-5">
                <div>
                  <textarea
                    value={noteInput}
                    onChange={(e) => setNoteInput(e.target.value)}
                    placeholder="Add a note about this contact..."
                    rows={3}
                    className="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30 resize-none"
                  />
                  <div className="flex justify-end mt-2">
                    <button
                      onClick={saveNote}
                      disabled={savingNote || !noteInput.trim()}
                      className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm disabled:opacity-50 transition-colors"
                    >
                      {savingNote ? "Saving…" : "Save Note"}
                    </button>
                  </div>
                </div>

                {notesList.length === 0 ? (
                  <div className="text-center py-10 text-gray-400">
                    <FileText className="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p className="text-sm">No notes yet</p>
                  </div>
                ) : (
                  <div className="space-y-3">
                    {notesList.map((note) => (
                      <div key={note._id} className="bg-gray-50 rounded-lg p-4">
                        <div className="flex items-center justify-between mb-2">
                          <span className="text-sm font-medium text-gray-900">{note.authorName}</span>
                          <span className="text-xs text-gray-400">{relativeTime(note.createdAt)}</span>
                        </div>
                        <p className="text-sm text-gray-700 whitespace-pre-wrap">{note.body}</p>
                      </div>
                    ))}
                  </div>
                )}
              </div>
            )}

            {/* ── TASKS ────────────────────────────────────────────────── */}
            {activeTab === "tasks" && (
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <h3 className="text-sm font-medium text-gray-500">
                    {openTasks.length} open · {doneTasks.length} done
                  </h3>
                  <button
                    onClick={() => setShowTaskModal(true)}
                    className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition-colors"
                  >
                    <Plus className="w-3.5 h-3.5" /> New Task
                  </button>
                </div>

                {tasks.length === 0 ? (
                  <div className="text-center py-10 text-gray-400">
                    <CheckSquare className="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p className="text-sm">No tasks</p>
                  </div>
                ) : (
                  <div className="space-y-2">
                    {[...openTasks, ...doneTasks].map((task) => {
                      const overdue = task.dueDate && new Date(task.dueDate) < new Date() && task.status !== "done";
                      return (
                        <div
                          key={task._id}
                          className={`flex items-start gap-3 p-3 rounded-lg border transition-colors ${task.status === "done" ? "bg-gray-50 border-gray-100 opacity-60" : "bg-white border-gray-200"}`}
                        >
                          <button
                            onClick={() => toggleTask(task)}
                            className={`mt-0.5 w-4 h-4 rounded shrink-0 border-2 flex items-center justify-center transition-colors ${task.status === "done" ? "bg-green-500 border-green-500" : "border-gray-300 hover:border-green-400"}`}
                          >
                            {task.status === "done" && <Check className="w-2.5 h-2.5 text-white" />}
                          </button>
                          <div className="flex-1 min-w-0">
                            <p className={`text-sm font-medium ${task.status === "done" ? "line-through text-gray-400" : "text-gray-900"}`}>{task.title}</p>
                            {task.description && <p className="text-xs text-gray-500 mt-0.5 truncate">{task.description}</p>}
                          </div>
                          <div className="flex items-center gap-2 shrink-0">
                            <span className={`text-[10px] px-2 py-0.5 rounded-full font-medium ${PRIORITY_COLORS[task.priority] || "bg-gray-100 text-gray-600"}`}>
                              {task.priority}
                            </span>
                            {task.dueDate && (
                              <span className={`text-[10px] font-medium ${overdue ? "text-red-500" : "text-gray-400"}`}>
                                {overdue && <AlertCircle className="w-3 h-3 inline mr-0.5" />}
                                {formatDate(task.dueDate)}
                              </span>
                            )}
                          </div>
                        </div>
                      );
                    })}
                  </div>
                )}
              </div>
            )}

            {/* ── APPOINTMENTS ─────────────────────────────────────────── */}
            {activeTab === "appointments" && (
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <h3 className="text-sm font-medium text-gray-500">{appointments.length} appointment{appointments.length !== 1 ? "s" : ""}</h3>
                  <button
                    onClick={() => setShowApptModal(true)}
                    className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition-colors"
                  >
                    <Plus className="w-3.5 h-3.5" /> Schedule
                  </button>
                </div>

                {appointments.length === 0 ? (
                  <div className="text-center py-10 text-gray-400">
                    <Calendar className="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p className="text-sm">No appointments</p>
                  </div>
                ) : (
                  <div className="space-y-3">
                    {[...appointments].sort((a, b) => new Date(b.scheduledAt).getTime() - new Date(a.scheduledAt).getTime()).map((appt) => (
                      <div key={appt._id} className="bg-gray-50 rounded-lg p-4 flex items-start justify-between gap-4">
                        <div className="space-y-1">
                          <div className="flex items-center gap-2">
                            <span className="text-sm font-medium text-gray-900 capitalize">{appt.type.replace(/_/g, " ")}</span>
                            <span className={`text-xs px-2 py-0.5 rounded-full font-medium ${
                              appt.status === "confirmed" ? "bg-green-100 text-green-700" :
                              appt.status === "completed" ? "bg-gray-100 text-gray-600" :
                              appt.status === "cancelled" ? "bg-red-100 text-red-600" :
                              "bg-blue-100 text-blue-700"
                            }`}>{appt.status}</span>
                          </div>
                          <p className="text-sm text-gray-700 font-medium">
                            {new Date(appt.scheduledAt).toLocaleDateString("en-US", { weekday: "short", month: "short", day: "numeric" })} at{" "}
                            {new Date(appt.scheduledAt).toLocaleTimeString("en-US", { hour: "numeric", minute: "2-digit" })}
                          </p>
                          <p className="text-xs text-gray-500">
                            <Clock className="w-3 h-3 inline mr-1" />{appt.duration}min
                            {appt.provider && ` · with ${appt.provider}`}
                          </p>
                        </div>
                        {appt.videoLink && (
                          <a
                            href={appt.videoLink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs hover:bg-blue-100 transition-colors shrink-0"
                          >
                            <Video className="w-3.5 h-3.5" /> Join
                          </a>
                        )}
                      </div>
                    ))}
                  </div>
                )}
              </div>
            )}

            {/* ── OPPORTUNITIES ─────────────────────────────────────────── */}
            {activeTab === "opportunities" && (
              <div className="space-y-4">
                <div className="flex items-center justify-between">
                  <h3 className="text-sm font-medium text-gray-500">{opportunities.length} opportunit{opportunities.length !== 1 ? "ies" : "y"}</h3>
                  <button
                    onClick={() => setShowOppModal(true)}
                    className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition-colors"
                  >
                    <Plus className="w-3.5 h-3.5" /> New
                  </button>
                </div>

                {opportunities.length === 0 ? (
                  <div className="text-center py-10 text-gray-400">
                    <TrendingUp className="w-8 h-8 mx-auto mb-2 opacity-50" />
                    <p className="text-sm">No pipeline opportunities</p>
                  </div>
                ) : (
                  <div className="space-y-3">
                    {opportunities.map((opp) => (
                      <div key={opp._id} className="bg-gray-50 rounded-lg p-4">
                        <div className="flex items-start justify-between gap-3">
                          <div className="space-y-1.5">
                            <span className="text-xs px-2.5 py-1 rounded-full bg-blue-100 text-blue-700 font-medium capitalize">
                              {opp.stage.replace(/_/g, " ")}
                            </span>
                            {opp.tier && (
                              <span className="ml-2 text-xs px-2.5 py-1 rounded-full bg-gold/10 text-gold font-medium">
                                {TIER_LABELS[opp.tier] || opp.tier}
                              </span>
                            )}
                            {opp.value !== undefined && opp.value > 0 && (
                              <p className="text-sm font-semibold text-gray-900">{formatTenantCurrency(opp.value)}/mo</p>
                            )}
                            <p className="text-xs text-gray-400">{relativeTime(opp.createdAt)}</p>
                          </div>
                          <div>
                            <select
                              value={opp.stage}
                              onChange={(e) => moveOppStage(opp._id, e.target.value)}
                              className="text-xs px-2 py-1 border border-gray-200 rounded-lg bg-white"
                            >
                              {tenantConfig.pipeline.stages.map((s) => (
                                <option key={s.id} value={s.id}>{s.label}</option>
                              ))}
                            </select>
                          </div>
                        </div>
                        {opp.notes && <p className="text-sm text-gray-600 mt-2 pt-2 border-t border-gray-200">{opp.notes}</p>}
                      </div>
                    ))}
                  </div>
                )}
              </div>
            )}

            {/* ── PAYMENTS ─────────────────────────────────────────────── */}
            {activeTab === "payments" && (
              <div className="space-y-6">
                {/* Subscriptions */}
                <div>
                  <h3 className="text-sm font-semibold text-gray-900 mb-3">Subscriptions</h3>
                  {subscriptions.length === 0 ? (
                    <p className="text-sm text-gray-400 py-4 text-center border border-dashed border-gray-200 rounded-lg">No subscriptions</p>
                  ) : (
                    <div className="space-y-2">
                      {subscriptions.map((sub) => (
                        <div key={sub._id} className="bg-gray-50 rounded-lg p-4 flex items-center justify-between">
                          <div>
                            <p className="text-sm font-medium text-gray-900">{TIER_LABELS[sub.tier] || sub.tier}</p>
                            <p className="text-xs text-gray-500 mt-0.5">
                              {sub.status}
                              {sub.currentPeriodEnd && ` · renews ${formatDate(sub.currentPeriodEnd)}`}
                            </p>
                          </div>
                          <div className="text-right">
                            <p className="text-sm font-semibold text-gray-900">{formatTenantCurrency(sub.priceInCents)}/mo</p>
                            <span className={`text-xs px-2 py-0.5 rounded-full font-medium ${
                              sub.status === "active" ? "bg-green-100 text-green-700" :
                              sub.status === "cancelled" ? "bg-red-100 text-red-600" :
                              "bg-gray-100 text-gray-600"
                            }`}>{sub.status}</span>
                          </div>
                        </div>
                      ))}
                    </div>
                  )}
                </div>

                {/* Orders */}
                <div>
                  <h3 className="text-sm font-semibold text-gray-900 mb-3">Orders</h3>
                  {orders.length === 0 ? (
                    <p className="text-sm text-gray-400 py-4 text-center border border-dashed border-gray-200 rounded-lg">No orders</p>
                  ) : (
                    <div className="space-y-2">
                      {orders.map((order) => (
                        <div key={order._id} className="bg-gray-50 rounded-lg p-4 flex items-center justify-between">
                          <div>
                            <p className="text-sm font-medium text-gray-900 capitalize">{order.type.replace(/_/g, " ")}</p>
                            {order.trackingNumber && (
                              <p className="text-xs text-gray-500 mt-0.5">Tracking: {order.trackingNumber}</p>
                            )}
                            <p className="text-xs text-gray-400">{relativeTime(order.createdAt)}</p>
                          </div>
                          <span className={`text-xs px-2.5 py-1 rounded-full font-medium ${
                            order.status === "delivered" || order.status === "completed" ? "bg-green-100 text-green-700" :
                            order.status === "shipped" ? "bg-blue-100 text-blue-700" :
                            "bg-gray-100 text-gray-600"
                          }`}>{order.status}</span>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
              </div>
            )}

          </div>
        </div>
      </div>

      {/* ── MODALS ──────────────────────────────────────────────────────────── */}

      {/* Edit Contact Modal */}
      {showEditModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setShowEditModal(false)}>
          <div className="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-5">
              <h2 className="text-lg font-semibold">Edit Contact</h2>
              <button onClick={() => setShowEditModal(false)} className="p-1 hover:bg-gray-100 rounded-md"><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={handleEditSubmit} className="space-y-4">
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                  <input value={editForm.firstName} onChange={(e) => setEditForm({ ...editForm, firstName: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                  <input value={editForm.lastName} onChange={(e) => setEditForm({ ...editForm, lastName: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" value={editForm.email} onChange={(e) => setEditForm({ ...editForm, email: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                  <input value={editForm.phone} onChange={(e) => setEditForm({ ...editForm, phone: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">State</label>
                  <select value={editForm.state} onChange={(e) => setEditForm({ ...editForm, state: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">Select</option>
                    {STATES.map((s) => <option key={s} value={s}>{s}</option>)}
                  </select>
                </div>
              </div>
              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                  <select value={editForm.status} onChange={(e) => setEditForm({ ...editForm, status: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    {tenantConfig.contacts.statuses.map((s) => <option key={s.id} value={s.id}>{s.label}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1">Tier</label>
                  <select value={editForm.tier} onChange={(e) => setEditForm({ ...editForm, tier: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value="">None</option>
                    {tenantConfig.billing.tiers.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                  </select>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">Internal Notes</label>
                <textarea value={editForm.notes} onChange={(e) => setEditForm({ ...editForm, notes: e.target.value })} rows={3} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
              </div>
              <div className="flex justify-end gap-3 pt-2">
                <button type="button" onClick={() => setShowEditModal(false)} className="px-4 py-2 border border-gray-200 rounded-lg text-sm hover:bg-gray-50">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm">Save Changes</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* New Task Modal */}
      {showTaskModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setShowTaskModal(false)}>
          <div className="bg-white rounded-xl w-full max-w-md p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-semibold">New Task</h2>
              <button onClick={() => setShowTaskModal(false)}><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={createTask} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">Title *</label>
                <input value={taskForm.title} onChange={(e) => setTaskForm({ ...taskForm, title: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Description</label>
                <textarea value={taskForm.description} onChange={(e) => setTaskForm({ ...taskForm, description: e.target.value })} rows={2} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/30" />
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-sm font-medium mb-1">Priority</label>
                  <select value={taskForm.priority} onChange={(e) => setTaskForm({ ...taskForm, priority: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    {TASK_PRIORITY.map((p) => <option key={p} value={p}>{p}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Due Date</label>
                  <input type="date" value={taskForm.dueDate} onChange={(e) => setTaskForm({ ...taskForm, dueDate: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
                </div>
              </div>
              <div className="flex justify-end gap-3">
                <button type="button" onClick={() => setShowTaskModal(false)} className="px-4 py-2 border rounded-lg text-sm">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold text-navy font-semibold rounded-lg text-sm">Create Task</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Schedule Appointment Modal */}
      {showApptModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setShowApptModal(false)}>
          <div className="bg-white rounded-xl w-full max-w-md p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-semibold">Schedule Appointment</h2>
              <button onClick={() => setShowApptModal(false)}><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={createAppt} className="space-y-4">
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-sm font-medium mb-1">Type</label>
                  <select value={apptForm.type} onChange={(e) => setApptForm({ ...apptForm, type: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    {tenantConfig.appointments.types.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Duration</label>
                  <select value={apptForm.duration} onChange={(e) => setApptForm({ ...apptForm, duration: Number(e.target.value) })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                    <option value={15}>15 min</option>
                    <option value={30}>30 min</option>
                    <option value={45}>45 min</option>
                    <option value={60}>60 min</option>
                  </select>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Date & Time *</label>
                <input type="datetime-local" value={apptForm.scheduledAt} onChange={(e) => setApptForm({ ...apptForm, scheduledAt: e.target.value })} required className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">{tenantConfig.appointments.providerLabel}</label>
                <input value={apptForm.provider} onChange={(e) => setApptForm({ ...apptForm, provider: e.target.value })} placeholder={`Enter ${tenantConfig.appointments.providerLabel.toLowerCase()} name`} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Video Link</label>
                <input value={apptForm.videoLink} onChange={(e) => setApptForm({ ...apptForm, videoLink: e.target.value })} placeholder="https://doxy.me/..." className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm" />
              </div>
              <div className="flex justify-end gap-3">
                <button type="button" onClick={() => setShowApptModal(false)} className="px-4 py-2 border rounded-lg text-sm">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold text-navy font-semibold rounded-lg text-sm">Schedule</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* New Opportunity Modal */}
      {showOppModal && (
        <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" onClick={() => setShowOppModal(false)}>
          <div className="bg-white rounded-xl w-full max-w-md p-6" onClick={(e) => e.stopPropagation()}>
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-semibold">New Opportunity</h2>
              <button onClick={() => setShowOppModal(false)}><X className="w-5 h-5" /></button>
            </div>
            <form onSubmit={createOpp} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">Stage</label>
                <select value={oppForm.stage} onChange={(e) => setOppForm({ ...oppForm, stage: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                  {tenantConfig.pipeline.stages.map((s) => <option key={s.id} value={s.id}>{s.label}</option>)}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Tier</label>
                <select value={oppForm.tier} onChange={(e) => setOppForm({ ...oppForm, tier: e.target.value })} className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                  <option value="">None</option>
                  {tenantConfig.billing.tiers.map((t) => <option key={t.id} value={t.id}>{t.label}</option>)}
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Value ({tenantConfig.billing.currencySymbol}/mo)</label>
                <input
                  type="number"
                  step="0.01"
                  min="0"
                  value={oppForm.value}
                  onChange={(e) => setOppForm({ ...oppForm, value: e.target.value })}
                  placeholder="229.00"
                  className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                />
              </div>
              <div className="flex justify-end gap-3">
                <button type="button" onClick={() => setShowOppModal(false)} className="px-4 py-2 border rounded-lg text-sm">Cancel</button>
                <button type="submit" className="px-4 py-2 bg-gold text-navy font-semibold rounded-lg text-sm">Create</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
