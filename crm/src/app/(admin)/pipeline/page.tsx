"use client";

import { useEffect, useState, useCallback, useMemo } from "react";
import { Tooltip } from "@/components/ui/tooltip";
import {
  tenantConfig,
  getStageBorderColorMap,
  getStageDescriptionMap,
  getTierLabelMap,
  getTemperatureColorMap,
  getTemperatureDescriptionMap,
  formatTenantCurrency,
} from "@/lib/tenant";

interface Contact {
  _id: string;
  firstName: string;
  lastName: string;
  email: string;
}

interface Opp {
  _id: string;
  stage: string;
  tier?: string;
  value?: number;
  temperature?: string;
  contactId: { firstName: string; lastName: string; email: string };
}

const STAGE_COLORS = getStageBorderColorMap();
const STAGE_DESCRIPTIONS = getStageDescriptionMap();
const TEMP_COLORS = getTemperatureColorMap();
const TEMP_DESCRIPTIONS = getTemperatureDescriptionMap();
const TIER_LABELS = getTierLabelMap();

const stages = tenantConfig.pipeline.stages;
const tiers = tenantConfig.billing.tiers;

function DraggableCard({ opp }: { opp: Opp }) {
  const [isDragActive, setIsDragActive] = useState(false);

  return (
    <div
      draggable
      onDragStart={(e) => {
        e.dataTransfer.setData("oppId", opp._id);
        e.dataTransfer.setData("currentStage", opp.stage);
        setIsDragActive(true);
      }}
      onDragEnd={() => setIsDragActive(false)}
      className={`bg-white rounded-lg border-l-4 ${STAGE_COLORS[opp.stage] || "border-gray-400"} p-3 shadow-sm hover:shadow-md transition cursor-move ${
        isDragActive ? "opacity-50" : ""
      }`}
    >
      <div className="flex items-start justify-between gap-2">
        <div className="flex-1 min-w-0">
          <div className="text-sm font-medium text-gray-900 truncate">
            {opp.contactId?.firstName} {opp.contactId?.lastName}
          </div>
          <div className="text-xs text-gray-500 truncate">{opp.contactId?.email}</div>
        </div>
        {opp.temperature && (
          <Tooltip content={TEMP_DESCRIPTIONS[opp.temperature] || opp.temperature} side="top">
            <div className={`w-2 h-2 rounded-full flex-shrink-0 mt-1 ${TEMP_COLORS[opp.temperature] || "bg-gray-400"}`} />
          </Tooltip>
        )}
      </div>

      <div className="flex gap-2 items-center flex-wrap mt-2">
        {opp.tier && (
          <Tooltip content={TIER_LABELS[opp.tier] || opp.tier} side="bottom">
            <span className="text-[10px] bg-gold/10 text-gold px-2 py-0.5 rounded-full capitalize font-medium cursor-default">
              {opp.tier.replace(/_/g, " ")}
            </span>
          </Tooltip>
        )}
        {opp.value && (
          <Tooltip content="Estimated monthly value of this opportunity" side="bottom">
            <span className="text-xs text-gray-600 font-medium">{formatTenantCurrency(opp.value)}/mo</span>
          </Tooltip>
        )}
      </div>
    </div>
  );
}

function PipelineColumn({ stage, opps, onDragOver, onDrop }: { stage: typeof stages[0]; opps: Opp[]; onDragOver: (e: React.DragEvent) => void; onDrop: (e: React.DragEvent) => void }) {
  const totalValue = opps.reduce((sum, o) => sum + (o.value || 0), 0);
  const [isDragOverColumn, setIsDragOverColumn] = useState(false);

  return (
    <div className="flex-shrink-0 w-[280px]">
      <Tooltip content={stage.description} side="top">
        <div className="bg-gray-100 rounded-t-lg px-3 py-2 flex items-center justify-between cursor-default">
          <span className="text-xs font-semibold text-gray-700">{stage.label}</span>
          <Tooltip content={`${opps.length} opportunit${opps.length === 1 ? "y" : "ies"} in this stage`} side="top">
            <span className="text-[10px] bg-white px-2 py-0.5 rounded-full text-gray-500">{opps.length}</span>
          </Tooltip>
        </div>
      </Tooltip>
      <Tooltip content={`Total monthly value across all ${stage.label} opportunities`} side="top">
        <div className="text-[10px] text-gray-500 px-3 py-1 bg-gray-50 cursor-default">
          {formatTenantCurrency(totalValue)}/mo
        </div>
      </Tooltip>
      <div
        onDragOver={(e) => {
          e.preventDefault();
          e.stopPropagation();
          setIsDragOverColumn(true);
          onDragOver(e);
        }}
        onDragLeave={() => setIsDragOverColumn(false)}
        onDrop={(e) => {
          e.preventDefault();
          e.stopPropagation();
          setIsDragOverColumn(false);
          onDrop(e);
        }}
        className={`space-y-2 p-2 rounded-b-lg min-h-[400px] transition ${
          isDragOverColumn ? "bg-blue-50 border-2 border-blue-300" : "bg-gray-50/50"
        }`}
        data-stage={stage.id}
      >
        {opps.map((opp) => (
          <DraggableCard key={opp._id} opp={opp} />
        ))}
      </div>
    </div>
  );
}

function CreateOpportunityModal({ isOpen, onClose, onSuccess }: { isOpen: boolean; onClose: () => void; onSuccess: () => void }) {
  const [searchTerm, setSearchTerm] = useState("");
  const [searchResults, setSearchResults] = useState<Contact[]>([]);
  const [selectedContact, setSelectedContact] = useState<Contact | null>(null);
  const [stage, setStage] = useState<string>(stages[0]?.id || "");
  const [tier, setTier] = useState<string>(tiers[0]?.id || "");
  const [value, setValue] = useState<string>("");
  const [loading, setLoading] = useState(false);
  const [searchLoading, setSearchLoading] = useState(false);

  // Debounced search
  useEffect(() => {
    if (!searchTerm.trim()) {
      setSearchResults([]);
      return;
    }

    const timer = setTimeout(async () => {
      setSearchLoading(true);
      try {
        const res = await fetch(`/api/contacts?search=${encodeURIComponent(searchTerm)}&limit=10`);
        const data = await res.json();
        setSearchResults(data.contacts || []);
      } catch (err) {
        console.error("Search error:", err);
      } finally {
        setSearchLoading(false);
      }
    }, 300);

    return () => clearTimeout(timer);
  }, [searchTerm]);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    if (!selectedContact) return;

    const valueInCents = Math.round(parseFloat(value || "0") * 100);

    setLoading(true);
    try {
      const res = await fetch("/api/pipeline", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          contactId: selectedContact._id,
          stage,
          tier,
          value: valueInCents,
          notes: "",
        }),
      });

      if (res.ok) {
        onClose();
        onSuccess();
      }
    } catch (err) {
      console.error("Create error:", err);
    } finally {
      setLoading(false);
    }
  }

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg p-6 w-full max-w-md shadow-lg">
        <h2 className="text-lg font-semibold text-gray-900 mb-4">Create Opportunity</h2>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Contact Search */}
          <div>
            <label className="text-sm font-medium text-gray-700 block mb-1">{tenantConfig.contacts.entityLabel}</label>
            {selectedContact ? (
              <div className="flex items-center justify-between bg-blue-50 p-2 rounded border border-blue-200">
                <span className="text-sm text-gray-900">
                  {selectedContact.firstName} {selectedContact.lastName}
                </span>
                <button
                  type="button"
                  onClick={() => {
                    setSelectedContact(null);
                    setSearchTerm("");
                  }}
                  className="text-xs text-blue-600 hover:text-blue-700"
                >
                  Change
                </button>
              </div>
            ) : (
              <div>
                <input
                  type="text"
                  placeholder={`Search ${tenantConfig.contacts.entityLabelPlural.toLowerCase()}...`}
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                {searchLoading && <div className="text-xs text-gray-500 mt-1">Searching...</div>}
                {searchResults.length > 0 && (
                  <ul className="mt-2 border border-gray-200 rounded-lg overflow-hidden">
                    {searchResults.map((contact) => (
                      <li key={contact._id}>
                        <button
                          type="button"
                          onClick={() => setSelectedContact(contact)}
                          className="w-full text-left px-3 py-2 hover:bg-gray-100 text-sm text-gray-900"
                        >
                          {contact.firstName} {contact.lastName} ({contact.email})
                        </button>
                      </li>
                    ))}
                  </ul>
                )}
              </div>
            )}
          </div>

          {/* Stage Selector */}
          <div>
            <Tooltip content="The current stage of this opportunity in the pipeline" side="right">
              <label className="text-sm font-medium text-gray-700 block mb-1 cursor-default">Stage</label>
            </Tooltip>
            <select
              value={stage}
              onChange={(e) => setStage(e.target.value)}
              className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              {stages.map((s) => (
                <option key={s.id} value={s.id} title={s.description}>
                  {s.label}
                </option>
              ))}
            </select>
          </div>

          {/* Tier Selector (only if billing is enabled) */}
          {tenantConfig.billing.enabled && tiers.length > 0 && (
            <div>
              <Tooltip content="The subscription tier this opportunity is expected to join" side="right">
                <label className="text-sm font-medium text-gray-700 block mb-1 cursor-default">Tier</label>
              </Tooltip>
              <select
                value={tier}
                onChange={(e) => setTier(e.target.value)}
                className="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                {tiers.map((t) => (
                  <option key={t.id} value={t.id} title={t.fullLabel}>
                    {t.label}
                  </option>
                ))}
              </select>
            </div>
          )}

          {/* Value Input */}
          <div>
            <Tooltip content="Estimated monthly recurring revenue if this opportunity converts" side="right">
              <label className="text-sm font-medium text-gray-700 block mb-1 cursor-default">Monthly Value</label>
            </Tooltip>
            <div className="flex items-center gap-2">
              <span className="text-gray-600">{tenantConfig.billing.currencySymbol}</span>
              <input
                type="number"
                placeholder="0.00"
                value={value}
                onChange={(e) => setValue(e.target.value)}
                step="0.01"
                min="0"
                className="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <span className="text-gray-600">/mo</span>
            </div>
          </div>

          {/* Actions */}
          <div className="flex gap-2 pt-2">
            <button
              type="button"
              onClick={onClose}
              className="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
              Cancel
            </button>
            <button
              type="submit"
              disabled={!selectedContact || loading}
              className="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 disabled:bg-gray-300 disabled:cursor-not-allowed"
            >
              {loading ? "Creating..." : "Create"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default function PipelinePage() {
  const [opps, setOpps] = useState<Opp[]>([]);
  const [loading, setLoading] = useState(true);
  const [isModalOpen, setIsModalOpen] = useState(false);

  useEffect(() => {
    fetchOpportunities();
  }, []);

  async function fetchOpportunities() {
    setLoading(true);
    try {
      const res = await fetch("/api/pipeline");
      const data = await res.json();
      setOpps(data.opportunities || []);
    } catch (err) {
      console.error("Fetch error:", err);
    } finally {
      setLoading(false);
    }
  }

  async function moveOpportunity(oppId: string, newStage: string) {
    // Optimistic update
    setOpps((prev) => prev.map((o) => (o._id === oppId ? { ...o, stage: newStage } : o)));

    try {
      await fetch("/api/pipeline", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: oppId, stage: newStage }),
      });
    } catch (err) {
      console.error("Update error:", err);
      fetchOpportunities(); // Re-fetch on error
    }
  }

  function handleDragOver(e: React.DragEvent) {
    e.preventDefault();
  }

  function handleDropOnColumn(stageId: string, e: React.DragEvent) {
    const oppId = e.dataTransfer.getData("oppId");
    const currentStage = e.dataTransfer.getData("currentStage");

    if (oppId && currentStage !== stageId) {
      moveOpportunity(oppId, stageId);
    }
  }

  const oppsByStage = useMemo(() => {
    const map: Record<string, Opp[]> = {};
    stages.forEach((stage) => {
      map[stage.id] = opps.filter((o) => o.stage === stage.id);
    });
    return map;
  }, [opps]);

  if (loading) {
    return (
      <div className="space-y-4">
        <h1 className="text-2xl font-display">Pipeline</h1>
        <div className="animate-pulse h-96 bg-gray-100 rounded-xl" />
      </div>
    );
  }

  return (
    <>
      <div className="space-y-4">
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-display text-gray-900">Pipeline</h1>
          <Tooltip content="Add a new opportunity to the pipeline" side="left">
            <button
              onClick={() => setIsModalOpen(true)}
              className="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center gap-2"
            >
              <span>+</span> New Opportunity
            </button>
          </Tooltip>
        </div>

        <div className="flex gap-3 overflow-x-auto pb-4">
          {stages.map((stage) => (
            <PipelineColumn
              key={stage.id}
              stage={stage}
              opps={oppsByStage[stage.id] || []}
              onDragOver={handleDragOver}
              onDrop={(e) => handleDropOnColumn(stage.id, e)}
            />
          ))}
        </div>
      </div>

      <CreateOpportunityModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onSuccess={async () => {
          await fetchOpportunities();
        }}
      />
    </>
  );
}
