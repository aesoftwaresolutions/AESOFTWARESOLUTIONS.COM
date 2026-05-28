"use client";

import { useEffect, useState } from "react";
import { Plus, X, Video, Clock } from "lucide-react";
import { formatDate } from "@/lib/utils";
import { APPOINTMENT_STATUS } from "@/lib/constants";
import { Tooltip } from "@/components/ui/tooltip";
import { tenantConfig } from "@/lib/tenant";

interface Appointment {
  _id: string;
  type: string;
  status: string;
  scheduledAt: string;
  duration: number;
  provider?: string;
  videoLink?: string;
  contactId?: { firstName: string; lastName: string; email: string; phone?: string };
}

const STATUS_COLORS: Record<string, string> = {
  scheduled: "bg-blue-100 text-blue-700",
  confirmed: "bg-green-100 text-green-700",
  completed: "bg-gray-100 text-gray-600",
  cancelled: "bg-red-100 text-red-700",
  no_show: "bg-yellow-100 text-yellow-700",
};

const STATUS_DESCRIPTIONS: Record<string, string> = {
  scheduled: "Appointment booked — awaiting confirmation from patient",
  confirmed: "Patient confirmed — appointment is locked in",
  completed: "Appointment concluded successfully",
  cancelled: "Appointment was cancelled by patient or staff",
  no_show: "Patient did not attend the scheduled appointment",
};

const appointmentTypeMap = Object.fromEntries(
  tenantConfig.appointments.types.map((t) => [t.id, t.label])
);

export default function AppointmentsPage() {
  const [appts, setAppts] = useState<Appointment[]>([]);
  const [loading, setLoading] = useState(true);
  const [showCreate, setShowCreate] = useState(false);
  const [form, setForm] = useState({
    contactId: "",
    type: tenantConfig.appointments.types[0]?.id || "initial_consult",
    scheduledAt: "",
    duration: 30,
    provider: "",
    videoLink: "",
  });

  function load() {
    fetch("/api/appointments")
      .then((r) => r.json())
      .then((d) => setAppts(d.appointments || []))
      .finally(() => setLoading(false));
  }
  useEffect(load, []);

  async function handleCreate(e: React.FormEvent) {
    e.preventDefault();
    const res = await fetch("/api/appointments", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(form),
    });
    if (res.ok) {
      setShowCreate(false);
      load();
    }
  }

  async function updateStatus(id: string, status: string) {
    await fetch("/api/appointments", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status }),
    });
    setAppts((prev) => prev.map((a) => (a._id === id ? { ...a, status } : a)));
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-display text-gray-900">Appointments</h1>
        <Tooltip content="Schedule a new appointment for a patient" side="left">
          <button
            onClick={() => setShowCreate(true)}
            className="inline-flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition"
          >
            <Plus className="w-4 h-4" /> Schedule
          </button>
        </Tooltip>
      </div>

      <div className="grid gap-3">
        {loading
          ? [...Array(3)].map((_, i) => (
              <div key={i} className="h-20 bg-white rounded-xl border animate-pulse" />
            ))
          : appts.length === 0
          ? (
            <div className="text-center py-12 text-gray-400 bg-white rounded-xl border">
              No appointments found
            </div>
          )
          : appts.map((a) => (
            <div
              key={a._id}
              className="bg-white rounded-xl border border-gray-200 p-4 flex flex-col sm:flex-row sm:items-center gap-3"
            >
              <div className="flex-1">
                <div className="flex items-center gap-2">
                  <span className="font-medium text-gray-900">
                    {a.contactId?.firstName} {a.contactId?.lastName}
                  </span>
                  <Tooltip content={STATUS_DESCRIPTIONS[a.status] || a.status} side="top">
                    <span className={`text-xs px-2 py-0.5 rounded-full cursor-default ${STATUS_COLORS[a.status]}`}>
                      {a.status.replace(/_/g, " ")}
                    </span>
                  </Tooltip>
                </div>
                <div className="text-sm text-gray-500 mt-1 flex items-center gap-3">
                  <Tooltip
                    content={appointmentTypeMap[a.type] || a.type.replace(/_/g, " ")}
                    side="bottom"
                  >
                    <span className="capitalize cursor-default">
                      {appointmentTypeMap[a.type] || a.type.replace(/_/g, " ")}
                    </span>
                  </Tooltip>
                  <Tooltip content="Appointment duration in minutes" side="bottom">
                    <span className="flex items-center gap-1 cursor-default">
                      <Clock className="w-3 h-3" /> {a.duration}min
                    </span>
                  </Tooltip>
                  {a.provider && <span>with {a.provider}</span>}
                </div>
                <div className="text-sm text-gray-700 mt-1 font-medium">
                  {new Date(a.scheduledAt).toLocaleDateString("en-US", {
                    weekday: "short",
                    month: "short",
                    day: "numeric",
                  })}{" "}
                  at{" "}
                  {new Date(a.scheduledAt).toLocaleTimeString("en-US", {
                    hour: "numeric",
                    minute: "2-digit",
                  })}
                </div>
              </div>
              <div className="flex items-center gap-2">
                {a.videoLink && (
                  <Tooltip content="Join the video call for this appointment" side="left">
                    <a
                      href={a.videoLink}
                      target="_blank"
                      rel="noopener"
                      className="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm hover:bg-blue-100 transition"
                    >
                      <Video className="w-4 h-4" /> Join
                    </a>
                  </Tooltip>
                )}
                <Tooltip content="Update the status of this appointment" side="left">
                  <select
                    value={a.status}
                    onChange={(e) => updateStatus(a._id, e.target.value)}
                    className="px-2 py-1.5 text-xs border rounded-lg"
                  >
                    {APPOINTMENT_STATUS.map((s) => (
                      <option key={s} value={s}>
                        {s.replace(/_/g, " ")}
                      </option>
                    ))}
                  </select>
                </Tooltip>
              </div>
            </div>
          ))}
      </div>

      {showCreate && (
        <div
          className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
          onClick={() => setShowCreate(false)}
        >
          <div
            className="bg-white rounded-xl w-full max-w-md p-6"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-semibold">Schedule Appointment</h2>
              <button onClick={() => setShowCreate(false)}>
                <X className="w-5 h-5" />
              </button>
            </div>
            <form onSubmit={handleCreate} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">Contact ID</label>
                <input
                  value={form.contactId}
                  onChange={(e) => setForm({ ...form, contactId: e.target.value })}
                  required
                  placeholder="Paste contact ID"
                  className="w-full px-3 py-2 border rounded-lg text-sm"
                />
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-sm font-medium mb-1">Type</label>
                  <select
                    value={form.type}
                    onChange={(e) => setForm({ ...form, type: e.target.value })}
                    className="w-full px-3 py-2 border rounded-lg text-sm"
                  >
                    {tenantConfig.appointments.types.map((t) => (
                      <option key={t.id} value={t.id}>
                        {t.label}
                      </option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Duration</label>
                  <select
                    value={form.duration}
                    onChange={(e) => setForm({ ...form, duration: Number(e.target.value) })}
                    className="w-full px-3 py-2 border rounded-lg text-sm"
                  >
                    <option value={15}>15 min</option>
                    <option value={30}>30 min</option>
                    <option value={45}>45 min</option>
                    <option value={60}>60 min</option>
                  </select>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Date & Time</label>
                <input
                  type="datetime-local"
                  value={form.scheduledAt}
                  onChange={(e) => setForm({ ...form, scheduledAt: e.target.value })}
                  required
                  className="w-full px-3 py-2 border rounded-lg text-sm"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">{tenantConfig.appointments.providerLabel}</label>
                <input
                  value={form.provider}
                  onChange={(e) => setForm({ ...form, provider: e.target.value })}
                  className="w-full px-3 py-2 border rounded-lg text-sm"
                  placeholder="e.g. Dr. Williams"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Video Link</label>
                <input
                  value={form.videoLink}
                  onChange={(e) => setForm({ ...form, videoLink: e.target.value })}
                  className="w-full px-3 py-2 border rounded-lg text-sm"
                  placeholder="https://doxy.me/..."
                />
              </div>
              <div className="flex justify-end gap-3">
                <button
                  type="button"
                  onClick={() => setShowCreate(false)}
                  className="px-4 py-2 border rounded-lg text-sm"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-gold text-navy font-semibold rounded-lg text-sm"
                >
                  Schedule
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
