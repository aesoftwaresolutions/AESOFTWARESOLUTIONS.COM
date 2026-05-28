"use client";
import { Zap } from "lucide-react";
import { Tooltip } from "@/components/ui/tooltip";

export default function AutomationsPage() {
  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-display text-gray-900">Automations</h1>
      <div className="bg-white rounded-xl border border-gray-200 p-12 text-center">
        <Tooltip
          content="Trigger-based workflows that automatically execute actions when conditions are met — e.g. send an SMS when a contact completes intake"
          side="top"
        >
          <Zap className="w-12 h-12 text-gray-300 mx-auto mb-4 cursor-default" />
        </Tooltip>
        <h2 className="text-lg font-semibold text-gray-700">Workflow Automations</h2>
        <p className="text-sm text-gray-500 mt-2 max-w-md mx-auto">
          Trigger-based automation engine with condition matching and action execution — coming in Phase 3.
        </p>
        <div className="mt-6 grid grid-cols-3 gap-4 max-w-lg mx-auto text-left">
          {[
            {
              title: "Triggers",
              description: "Contact created, status changed, appointment scheduled, tag added, form submitted",
            },
            {
              title: "Conditions",
              description: "Field value matches, tag exists, lead score threshold, source filter",
            },
            {
              title: "Actions",
              description: "Send email or SMS, create task, update field, move pipeline stage, wait step",
            },
          ].map((item) => (
            <Tooltip key={item.title} content={item.description} side="top">
              <div className="bg-gray-50 rounded-lg p-3 border border-gray-100 cursor-default">
                <div className="text-xs font-semibold text-gray-600 mb-1">{item.title}</div>
                <div className="text-xs text-gray-400 leading-snug line-clamp-2">{item.description}</div>
              </div>
            </Tooltip>
          ))}
        </div>
      </div>
    </div>
  );
}
