import type { TenantConfig } from "../types";

/**
 * Healthcare / Clinical Services Preset
 *
 * Suitable for: telehealth, clinics, wellness centers, dental practices,
 * veterinary clinics, mental health practices, med spas.
 *
 * Includes PHI compliance, lab/medication order types, and clinical pipeline stages.
 */
export const healthcarePreset: TenantConfig = {
  brand: {
    name: "HealthCRM",
    tagline: "Clinical Management Platform",
    pageTitle: "HealthCRM — Admin Portal",
    metaDescription: "Healthcare CRM — Clinical Practice Management Portal",
    logo: {
      paths: [
        { d: "M20 3L5 10v12c0 8.5 6.4 16.4 15 18 8.6-1.6 15-9.5 15-18V10L20 3z", stroke: "currentColor", strokeWidth: "1.5", fill: "none" },
        { d: "M14 18l3 3 7-7", stroke: "currentColor", strokeWidth: "2", strokeLinecap: "round", strokeLinejoin: "round", fill: "none" },
      ],
      viewBox: "0 0 40 40",
      ariaLabel: "HealthCRM",
    },
  },

  theme: {
    colors: {
      primary: "#0C4A6E",
      primaryLight: "#0369A1",
      accent: "#0EA5E9",
      accentLight: "#38BDF8",
      background: "#F0F9FF",
      sidebar: "#0C4A6E",
      sidebarForeground: "#BAE6FD",
      sidebarBorder: "#075985",
      sidebarAccent: "#0EA5E9",
      sidebarMuted: "#7DD3FC",
      success: "#16A34A",
      warning: "#D97706",
      error: "#DC2626",
    },
    fonts: {
      display: '"Inter", system-ui, sans-serif',
      body: '"Inter", system-ui, sans-serif',
      importUrl: "https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap",
    },
  },

  contacts: {
    entityLabel: "Patient",
    entityLabelPlural: "Patients",
    defaultStatus: "lead",
    statuses: [
      { id: "lead", label: "Lead", description: "New inquiry — intake not started", badgeColor: "bg-blue-100 text-blue-700", chartColor: "#3B82F6" },
      { id: "intake_started", label: "Intake Started", description: "Patient began intake paperwork", badgeColor: "bg-indigo-100 text-indigo-700", chartColor: "#6366F1" },
      { id: "intake_completed", label: "Intake Completed", description: "Intake complete — pending clinical review", badgeColor: "bg-purple-100 text-purple-700", chartColor: "#A855F7" },
      { id: "under_review", label: "Under Review", description: "Clinical review in progress", badgeColor: "bg-yellow-100 text-yellow-700", chartColor: "#EAB308" },
      { id: "approved", label: "Approved", description: "Approved for treatment", badgeColor: "bg-emerald-100 text-emerald-700", chartColor: "#10B981" },
      { id: "denied", label: "Denied", description: "Not eligible for treatment", badgeColor: "bg-red-100 text-red-700", chartColor: "#EF4444" },
      { id: "labs_ordered", label: "Labs Ordered", description: "Lab work ordered", badgeColor: "bg-cyan-100 text-cyan-700", chartColor: "#06B6D4" },
      { id: "labs_received", label: "Labs Received", description: "Lab results received", badgeColor: "bg-teal-100 text-teal-700", chartColor: "#14B8A6" },
      { id: "treatment_active", label: "Active Patient", description: "Active treatment protocol", badgeColor: "bg-green-100 text-green-700", chartColor: "#22C55E" },
      { id: "paused", label: "Paused", description: "Treatment paused", badgeColor: "bg-amber-100 text-amber-700", chartColor: "#F59E0B" },
      { id: "cancelled", label: "Cancelled", description: "No longer active", badgeColor: "bg-gray-100 text-gray-600", chartColor: "#6B7280" },
    ],
    sources: [
      { id: "website", label: "Website" },
      { id: "referral", label: "Referral" },
      { id: "social_media", label: "Social Media" },
      { id: "google_ads", label: "Google Ads" },
      { id: "direct", label: "Walk-In / Direct" },
      { id: "insurance", label: "Insurance Network" },
      { id: "other", label: "Other" },
    ],
    defaultSource: "website",
    temperatures: [
      { id: "hot", label: "Hot", color: "bg-red-500", description: "Ready to schedule" },
      { id: "warm", label: "Warm", color: "bg-amber-500", description: "Interested, needs nurturing" },
      { id: "cool", label: "Cool", color: "bg-blue-500", description: "Early inquiry" },
    ],
    customFields: [
      { key: "primaryCondition", label: "Primary Condition", type: "text" },
      { key: "insuranceProvider", label: "Insurance Provider", type: "text" },
      { key: "referringPhysician", label: "Referring Physician", type: "text" },
    ],
  },

  pipeline: {
    defaultStage: "new_lead",
    stages: [
      { id: "new_lead", label: "New Lead", description: "Initial inquiry received", borderColor: "border-blue-400" },
      { id: "intake_sent", label: "Intake Sent", description: "Paperwork sent to patient", borderColor: "border-indigo-400" },
      { id: "intake_complete", label: "Intake Complete", description: "All forms submitted", borderColor: "border-purple-400" },
      { id: "clinical_review", label: "Clinical Review", description: "Provider reviewing case", borderColor: "border-yellow-400" },
      { id: "labs_pending", label: "Labs Pending", description: "Awaiting lab results", borderColor: "border-cyan-400" },
      { id: "labs_complete", label: "Labs Complete", description: "Results received", borderColor: "border-teal-400" },
      { id: "treatment_active", label: "Active Treatment", description: "Patient on protocol", borderColor: "border-green-400" },
      { id: "retained", label: "Retained", description: "Ongoing patient", borderColor: "border-emerald-400" },
      { id: "churned", label: "Churned", description: "Patient left", borderColor: "border-red-400" },
    ],
  },

  billing: {
    enabled: true,
    currency: "USD",
    currencySymbol: "$",
    tiers: [
      { id: "basic", label: "Basic", fullLabel: "Basic — $99/mo", priceInCents: 9900 },
      { id: "standard", label: "Standard", fullLabel: "Standard — $199/mo", priceInCents: 19900 },
      { id: "premium", label: "Premium", fullLabel: "Premium — $399/mo", priceInCents: 39900 },
      { id: "vip", label: "VIP", fullLabel: "VIP — $599/mo", priceInCents: 59900 },
    ],
  },

  orders: {
    enabled: true,
    types: [
      { id: "lab_kit", label: "Lab Kit" },
      { id: "medication", label: "Medication" },
      { id: "supply", label: "Medical Supply" },
      { id: "refill", label: "Prescription Refill" },
    ],
  },

  compliance: {
    phiEnabled: true,
    ttlHours: 72,
    transferTypes: [
      { id: "intake_form", label: "Intake Form" },
      { id: "lab_requisition", label: "Lab Requisition" },
      { id: "consent_form", label: "Consent Form" },
      { id: "referral", label: "Referral Document" },
    ],
    partnerLabel: "Clinical Partner",
  },

  modules: [
    { id: "dashboard", label: "Dashboard", href: "/dashboard", icon: "LayoutDashboard", description: "KPIs and practice overview", enabled: true },
    { id: "contacts", label: "Patients", href: "/contacts", icon: "Users", section: "CRM", description: "Patient records and intake tracking", enabled: true },
    { id: "pipeline", label: "Pipeline", href: "/pipeline", icon: "Target", description: "Patient journey through clinical stages", enabled: true },
    { id: "appointments", label: "Appointments", href: "/appointments", icon: "Calendar", description: "Schedule consultations and follow-ups", enabled: true },
    { id: "campaigns", label: "Campaigns", href: "/campaigns", icon: "Megaphone", section: "Marketing", description: "Patient outreach campaigns", enabled: true },
    { id: "social", label: "Social Media", href: "/social", icon: "Instagram", description: "Social media management", enabled: false },
    { id: "orders", label: "Orders", href: "/orders", icon: "ShoppingCart", section: "Operations", description: "Lab kits and medication orders", enabled: true },
    { id: "subscriptions", label: "Subscriptions", href: "/subscriptions", icon: "CreditCard", description: "Patient billing and subscriptions", enabled: true },
    { id: "tasks", label: "Tasks", href: "/tasks", icon: "CheckSquare", section: "Workflow", description: "Staff tasks and reminders", enabled: true },
    { id: "messaging", label: "Messaging", href: "/messaging", icon: "MessageSquare", description: "Patient communication", enabled: true },
    { id: "automations", label: "Automations", href: "/automations", icon: "Zap", description: "Automated follow-up workflows", enabled: true },
    { id: "reports", label: "Reports", href: "/reports", icon: "BarChart3", section: "Insights", description: "Practice analytics and revenue", enabled: true },
    { id: "settings", label: "Settings", href: "/settings", icon: "Settings", section: "Admin", description: "Practice settings and user management", enabled: true },
  ],

  appointments: {
    entityLabel: "Appointment",
    types: [
      { id: "initial_consult", label: "Initial Consultation" },
      { id: "follow_up", label: "Follow-Up Visit" },
      { id: "lab_review", label: "Lab Review" },
      { id: "check_in", label: "Check-In" },
    ],
    defaultDuration: 30,
    providerLabel: "Provider",
  },

  seed: {
    adminName: "Admin",
    adminEmail: "admin@healthcrm.com",
    adminPassword: "Admin@123!",
  },

  dashboard: {
    kpis: [
      { id: "totalContacts", label: "Total Patients", description: "All patients in the system", icon: "Users", colorClasses: "text-blue-600 bg-blue-50", dataKey: "totalContacts", format: "number" },
      { id: "newThisMonth", label: "New This Month", description: "New patients this month", icon: "TrendingUp", colorClasses: "text-emerald-600 bg-emerald-50", dataKey: "newContactsThisMonth", format: "number" },
      { id: "activeContacts", label: "Active Patients", description: "Currently in treatment", icon: "Users", colorClasses: "text-green-600 bg-green-50", dataKey: "activePatients", format: "number" },
      { id: "mrr", label: "MRR", description: "Monthly Recurring Revenue", icon: "DollarSign", colorClasses: "text-amber-600 bg-amber-50", dataKey: "mrr", format: "currency" },
      { id: "activeSubs", label: "Active Subs", description: "Active subscriptions", icon: "DollarSign", colorClasses: "text-purple-600 bg-purple-50", dataKey: "activeSubscriptions", format: "number" },
      { id: "appointments", label: "Appointments", description: "Upcoming in next 30 days", icon: "Calendar", colorClasses: "text-cyan-600 bg-cyan-50", dataKey: "upcomingAppointments", format: "number" },
      { id: "openTasks", label: "Open Tasks", description: "Pending tasks", icon: "CheckSquare", colorClasses: "text-orange-600 bg-orange-50", dataKey: "openTasks", format: "number" },
      { id: "urgentTasks", label: "Urgent Tasks", description: "High-priority tasks", icon: "AlertTriangle", colorClasses: "text-red-600 bg-red-50", dataKey: "urgentTasks", format: "number" },
    ],
  },
};
