"use client";

import { useEffect, useState } from "react";
import { Plus, X, Package, Truck, ExternalLink } from "lucide-react";
import { formatDate } from "@/lib/utils";
import { ORDER_STATUS } from "@/lib/constants";
import { Tooltip } from "@/components/ui/tooltip";

interface OrderItem {
  _id: string;
  type: string;
  status: string;
  trackingNumber?: string;
  carrier?: string;
  contactId?: { firstName: string; lastName: string; email: string };
  createdAt: string;
}

const STATUS_COLORS: Record<string, string> = {
  pending: "bg-gray-100 text-gray-600",
  ordered: "bg-blue-100 text-blue-700",
  shipped: "bg-cyan-100 text-cyan-700",
  delivered: "bg-green-100 text-green-700",
  completed: "bg-emerald-100 text-emerald-700",
};

const STATUS_DESCRIPTIONS: Record<string, string> = {
  pending: "Order submitted, awaiting processing by fulfillment",
  ordered: "Order placed with supplier or pharmacy",
  shipped: "Package is in transit to the patient",
  delivered: "Package arrived at the patient's address",
  completed: "Patient confirmed receipt and order is closed",
};

const ORDER_TYPE_DESCRIPTIONS: Record<string, string> = {
  lab_kit: "At-home lab collection kit sent to patient for bloodwork",
  medication: "Prescribed medication shipment (e.g., testosterone, peptides)",
  refill: "Recurring refill of an existing prescription",
};

const CARRIER_URLS: Record<string, string> = {
  UPS: "https://www.ups.com/track?tracknum=",
  USPS: "https://tools.usps.com/go/TrackConfirmAction?tLabels=",
  FedEx: "https://www.fedex.com/fedextrack/?trknbr=",
};

export default function OrdersPage() {
  const [orders, setOrders] = useState<OrderItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [showCreate, setShowCreate] = useState(false);
  const [form, setForm] = useState({
    contactId: "",
    type: "lab_kit",
    carrier: "UPS",
    trackingNumber: "",
  });

  function load() {
    fetch("/api/orders")
      .then((r) => r.json())
      .then((d) => setOrders(d.orders || []))
      .finally(() => setLoading(false));
  }
  useEffect(load, []);

  async function handleCreate(e: React.FormEvent) {
    e.preventDefault();
    await fetch("/api/orders", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(form),
    });
    setShowCreate(false);
    load();
  }

  async function updateStatus(id: string, status: string) {
    await fetch("/api/orders", {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id, status }),
    });
    setOrders((prev) => prev.map((o) => (o._id === id ? { ...o, status } : o)));
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-display text-gray-900">Orders</h1>
        <Tooltip content="Create a new lab kit, medication, or refill order for a patient" side="left">
          <button
            onClick={() => setShowCreate(true)}
            className="inline-flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm"
          >
            <Plus className="w-4 h-4" /> New Order
          </button>
        </Tooltip>
      </div>

      <div className="bg-white rounded-xl border overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50 border-b">
            <tr>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Patient</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Type</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600">Status</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600 hidden md:table-cell">Tracking</th>
              <th className="text-left px-4 py-3 font-medium text-gray-600 hidden md:table-cell">Created</th>
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
              : orders.length === 0
              ? (
                <tr>
                  <td colSpan={6} className="px-4 py-12 text-center text-gray-400">
                    No orders
                  </td>
                </tr>
              )
              : orders.map((o) => (
                <tr key={o._id} className="hover:bg-gray-50">
                  <td className="px-4 py-3 font-medium">
                    {o.contactId?.firstName} {o.contactId?.lastName}
                  </td>
                  <td className="px-4 py-3 capitalize">
                    <Tooltip content={ORDER_TYPE_DESCRIPTIONS[o.type] || o.type.replace(/_/g, " ")} side="top">
                      <span className="flex items-center gap-2 cursor-default">
                        {o.type === "lab_kit" ? (
                          <Package className="w-4 h-4 text-gray-400" />
                        ) : (
                          <Truck className="w-4 h-4 text-gray-400" />
                        )}
                        {o.type.replace(/_/g, " ")}
                      </span>
                    </Tooltip>
                  </td>
                  <td className="px-4 py-3">
                    <Tooltip content={STATUS_DESCRIPTIONS[o.status] || o.status} side="top">
                      <span className={`text-xs px-2.5 py-1 rounded-full font-medium cursor-default ${STATUS_COLORS[o.status]}`}>
                        {o.status}
                      </span>
                    </Tooltip>
                  </td>
                  <td className="px-4 py-3 hidden md:table-cell">
                    {o.trackingNumber ? (
                      <Tooltip
                        content={`Track with ${o.carrier || "carrier"} — opens in new tab`}
                        side="top"
                      >
                        <a
                          href={`${CARRIER_URLS[o.carrier || "UPS"] || "#"}${o.trackingNumber}`}
                          target="_blank"
                          className="text-blue-600 hover:underline text-xs inline-flex items-center gap-1"
                        >
                          {o.trackingNumber.substring(0, 16)}...{" "}
                          <ExternalLink className="w-3 h-3" />
                        </a>
                      </Tooltip>
                    ) : (
                      "—"
                    )}
                  </td>
                  <td className="px-4 py-3 text-gray-500 hidden md:table-cell">
                    {formatDate(o.createdAt)}
                  </td>
                  <td className="px-4 py-3 text-right">
                    <Tooltip content="Update the fulfillment status of this order" side="left">
                      <select
                        value={o.status}
                        onChange={(e) => updateStatus(o._id, e.target.value)}
                        className="text-xs border rounded-lg px-2 py-1"
                      >
                        {ORDER_STATUS.map((s) => (
                          <option key={s} value={s}>
                            {s}
                          </option>
                        ))}
                      </select>
                    </Tooltip>
                  </td>
                </tr>
              ))}
          </tbody>
        </table>
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
              <h2 className="text-lg font-semibold">New Order</h2>
              <button onClick={() => setShowCreate(false)}>
                <X className="w-5 h-5" />
              </button>
            </div>
            <form onSubmit={handleCreate} className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-1">Contact ID *</label>
                <input
                  value={form.contactId}
                  onChange={(e) => setForm({ ...form, contactId: e.target.value })}
                  required
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
                    <option value="lab_kit">Lab Kit</option>
                    <option value="medication">Medication</option>
                    <option value="refill">Refill</option>
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Carrier</label>
                  <select
                    value={form.carrier}
                    onChange={(e) => setForm({ ...form, carrier: e.target.value })}
                    className="w-full px-3 py-2 border rounded-lg text-sm"
                  >
                    <option value="UPS">UPS</option>
                    <option value="USPS">USPS</option>
                    <option value="FedEx">FedEx</option>
                  </select>
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium mb-1">Tracking #</label>
                <input
                  value={form.trackingNumber}
                  onChange={(e) => setForm({ ...form, trackingNumber: e.target.value })}
                  className="w-full px-3 py-2 border rounded-lg text-sm"
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
                  Create
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
