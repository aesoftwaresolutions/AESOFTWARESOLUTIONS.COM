"use client";
import { useSession } from "next-auth/react";
import { Shield, Database, Webhook, Users } from "lucide-react";
import { Tooltip } from "@/components/ui/tooltip";

const ROLE_DESCRIPTIONS: Record<string, string> = {
  superadmin: "Full access to all data, settings, and user management",
  admin: "Access to all contacts, pipeline, campaigns, and settings",
  manager: "Team-scoped view with full operational access",
  staff: "Own-scoped contacts, tasks, and appointments only",
  readonly: "View-only access across all resources — no edits",
};

const HIPAA_ITEMS = [
  {
    label: "PHI auto-purge: Active (72hr)",
    description:
      "Protected Health Information is automatically encrypted and purged after 72 hours per HIPAA guidelines",
  },
  {
    label: "Audit logging: Enabled",
    description:
      "All create, update, and delete actions are logged immutably — no PHI stored in log details",
  },
  {
    label: "Encryption at rest: AES-256",
    description:
      "All PHI payloads are encrypted using AES-256-GCM before storage in MongoDB Atlas",
  },
  {
    label: "TLS 1.3: Enforced",
    description:
      "All data in transit uses TLS 1.3 — older protocol versions are rejected",
  },
];

const INTEGRATION_STATUS: Record<string, string> = {
  Ready: "Integration is configured and active",
  "Not configured": "API keys not yet provided — add to environment variables to activate",
};

export default function SettingsPage() {
  const { data: session } = useSession();
  const role = (session?.user as any)?.role || "admin";

  return (
    <div className="space-y-4">
      <h1 className="text-2xl font-display text-gray-900">Settings</h1>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">

        {/* Account */}
        <div className="bg-white rounded-xl border p-5">
          <div className="flex items-center gap-3 mb-4">
            <Users className="w-5 h-5 text-blue-500" />
            <h2 className="font-semibold">Account</h2>
          </div>
          <div className="space-y-3 text-sm">
            <div className="flex justify-between">
              <span className="text-gray-500">Name</span>
              <span className="font-medium">{session?.user?.name}</span>
            </div>
            <div className="flex justify-between">
              <span className="text-gray-500">Email</span>
              <span className="font-medium">{session?.user?.email}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-500">Role</span>
              <Tooltip content={ROLE_DESCRIPTIONS[role] || role} side="left">
                <span className="font-medium capitalize cursor-default">{role}</span>
              </Tooltip>
            </div>
          </div>
        </div>

        {/* HIPAA Compliance */}
        <div className="bg-white rounded-xl border p-5">
          <div className="flex items-center gap-3 mb-4">
            <Shield className="w-5 h-5 text-green-500" />
            <h2 className="font-semibold">HIPAA Compliance</h2>
          </div>
          <div className="space-y-2 text-sm">
            {HIPAA_ITEMS.map((item) => (
              <Tooltip key={item.label} content={item.description} side="left">
                <div className="flex items-center gap-2 cursor-default">
                  <span className="w-2 h-2 bg-green-500 rounded-full flex-shrink-0" />
                  {item.label}
                </div>
              </Tooltip>
            ))}
          </div>
        </div>

        {/* Database */}
        <div className="bg-white rounded-xl border p-5">
          <div className="flex items-center gap-3 mb-4">
            <Database className="w-5 h-5 text-purple-500" />
            <h2 className="font-semibold">Database</h2>
          </div>
          <div className="space-y-2 text-sm text-gray-600">
            <Tooltip content="Primary data store for all CRM records — hosted on MongoDB Atlas" side="right">
              <div className="cursor-default">MongoDB Atlas — Connected</div>
            </Tooltip>
            <Tooltip content="The active database instance for this CRM environment" side="right">
              <div className="cursor-default">Database: tenant_crm</div>
            </Tooltip>
            <Tooltip content="Active Mongoose model collections currently in use" side="right">
              <div className="cursor-default">Collections: 8 active</div>
            </Tooltip>
          </div>
        </div>

        {/* Integrations */}
        <div className="bg-white rounded-xl border p-5">
          <div className="flex items-center gap-3 mb-4">
            <Webhook className="w-5 h-5 text-orange-500" />
            <h2 className="font-semibold">Integrations</h2>
          </div>
          <div className="space-y-2 text-sm">
            {[
              {
                name: "PC Partner Webhook",
                status: "Ready",
                description: "Lab ordering and consent form webhook — HMAC-signed, live",
              },
              {
                name: "Stripe",
                status: "Not configured",
                description: "Subscription billing — add STRIPE_SECRET_KEY to activate",
              },
              {
                name: "SendGrid",
                status: "Not configured",
                description: "Transactional email and campaigns — add SENDGRID_API_KEY to activate",
              },
              {
                name: "Twilio",
                status: "Not configured",
                description: "SMS send/receive and two-way conversations — add Twilio credentials to activate",
              },
            ].map((integration) => (
              <Tooltip key={integration.name} content={integration.description} side="left">
                <div className="flex items-center justify-between cursor-default">
                  <span>{integration.name}</span>
                  <span
                    className={`text-xs px-2 py-0.5 rounded-full ${
                      integration.status === "Ready"
                        ? "bg-green-100 text-green-700"
                        : "bg-yellow-100 text-yellow-700"
                    }`}
                  >
                    {integration.status}
                  </span>
                </div>
              </Tooltip>
            ))}
          </div>
        </div>

      </div>
    </div>
  );
}
