"use client";
import { useEffect, useState } from "react";
import { BarChart3, TrendingUp, Users, DollarSign } from "lucide-react";
import { Tooltip } from "@/components/ui/tooltip";

const KPI_DESCRIPTIONS: Record<string, string> = {
  "Total Contacts": "All contacts ever created — leads, patients, and churned",
  "Active Patients": "Contacts currently on an active treatment subscription",
  "MRR": "Monthly Recurring Revenue from all active subscriptions combined",
  "Active Subs": "Number of subscriptions currently in active billing status",
};

const STATUS_DESCRIPTIONS: Record<string, string> = {
  lead: "New inquiry, not yet qualified",
  prospect: "Qualified lead actively considering treatment",
  intake_pending: "Intake form sent, awaiting completion",
  intake_complete: "Intake finished, pending physician review",
  active_patient: "Currently enrolled in a treatment protocol",
  inactive: "Previously active, no current subscription",
  churned: "Cancelled and not re-engaged",
};

const STAGE_DESCRIPTIONS: Record<string, string> = {
  new_lead: "Fresh inquiry — intake form not yet sent",
  intake_sent: "Intake form delivered, awaiting completion",
  intake_complete: "Intake form filled out, ready for physician review",
  pc_review: "Physician is reviewing the intake and assessment",
  labs_pending: "Lab kit ordered, waiting for specimen results",
  labs_complete: "Lab results received, physician reviewing",
  treatment_active: "Patient is actively on a treatment protocol",
  retained: "Long-term patient, subscription renewing",
  churned: "Patient cancelled or became inactive",
};

export default function ReportsPage() {
  const [data, setData] = useState<any>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetch("/api/reports/overview", { credentials: "include" })
      .then((r) => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then(setData)
      .catch((err) => setError(err.message));
  }, []);

  if (error) {
    return (
      <div className="space-y-4">
        <h1 className="text-2xl font-display text-gray-900">Reports</h1>
        <div className="bg-red-50 border border-red-200 rounded-xl p-5 text-red-700">
          Failed to load report data: {error}
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-display text-gray-900">Reports</h1>
      {!data ? (
        <div className="animate-pulse h-96 bg-white rounded-xl border" />
      ) : (
        <div className="space-y-4">
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {[
              { label: "Total Contacts", value: data.totalContacts ?? 0, icon: Users, color: "text-blue-500" },
              { label: "Active Patients", value: data.activePatients ?? 0, icon: TrendingUp, color: "text-green-500" },
              { label: "MRR", value: `$${((data.mrr ?? 0) / 100).toLocaleString()}`, icon: DollarSign, color: "text-gold" },
              { label: "Active Subs", value: data.activeSubscriptions ?? 0, icon: BarChart3, color: "text-purple-500" },
            ].map((kpi) => (
              <Tooltip key={kpi.label} content={KPI_DESCRIPTIONS[kpi.label] || kpi.label} side="top">
                <div className="bg-white rounded-xl border p-5 cursor-default">
                  <div className="flex items-center gap-2 mb-2">
                    <kpi.icon className={`w-5 h-5 ${kpi.color}`} />
                    <span className="text-sm text-gray-500">{kpi.label}</span>
                  </div>
                  <div className="text-2xl font-semibold">{kpi.value}</div>
                </div>
              </Tooltip>
            ))}
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div className="bg-white rounded-xl border p-5">
              <h3 className="font-semibold mb-3">Contacts by Status</h3>
              <div className="space-y-2">
                {Object.entries(data.contactsByStatus ?? {}).map(([status, count]) => (
                  <Tooltip
                    key={status}
                    content={STATUS_DESCRIPTIONS[status] || status.replace(/_/g, " ")}
                    side="right"
                  >
                    <div className="flex items-center justify-between cursor-default">
                      <span className="text-sm capitalize text-gray-700">
                        {status.replace(/_/g, " ")}
                      </span>
                      <div className="flex items-center gap-2">
                        <div className="w-24 h-2 bg-gray-100 rounded-full overflow-hidden">
                          <div
                            className="h-full bg-gold rounded-full"
                            style={{
                              width: `${
                                data.totalContacts
                                  ? Math.min(100, ((count as number) / data.totalContacts) * 100)
                                  : 0
                              }%`,
                            }}
                          />
                        </div>
                        <span className="text-sm font-medium w-8 text-right">{count as number}</span>
                      </div>
                    </div>
                  </Tooltip>
                ))}
              </div>
            </div>

            <div className="bg-white rounded-xl border p-5">
              <h3 className="font-semibold mb-3">Pipeline by Stage</h3>
              <div className="space-y-2">
                {Object.entries(data.pipeline ?? {}).map(([stage, info]: [string, any]) => (
                  <Tooltip
                    key={stage}
                    content={STAGE_DESCRIPTIONS[stage] || stage.replace(/_/g, " ")}
                    side="left"
                  >
                    <div className="flex items-center justify-between cursor-default">
                      <span className="text-sm capitalize text-gray-700">
                        {stage.replace(/_/g, " ")}
                      </span>
                      <div className="text-right">
                        <span className="text-sm font-medium">{info.count}</span>
                        <span className="text-xs text-gray-500 ml-2">
                          ${((info.value ?? 0) / 100).toLocaleString()}
                        </span>
                      </div>
                    </div>
                  </Tooltip>
                ))}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
