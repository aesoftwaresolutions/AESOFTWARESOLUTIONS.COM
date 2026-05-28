import type { TenantConfig } from "../types";

/**
 * SaaS / B2B Services Preset
 *
 * Suitable for: SaaS companies, B2B services, consulting firms,
 * agencies, professional services, coaching businesses.
 *
 * Pipeline tracks deals from lead to closed/won.
 * Subscriptions enabled with typical SaaS tiers.
 * No PHI compliance.
 */
export const saasPreset: TenantConfig = {
  brand: {
    name: "Velocity",
    tagline: "Sales & Revenue Platform",
    pageTitle: "Velocity — Sales CRM",
    metaDescription: "Velocity — B2B Sales CRM & Pipeline Management",
    logo: {
      paths: [
        { d: "M8 28l8-16 8 10 8-14", stroke: "currentColor", strokeWidth: "2.5", strokeLinecap: "round", strokeLinejoin: "round", fill: "none" },
        { d: "M30 8l4 0 0 4", stroke: "currentColor", strokeWidth: "2", strokeLinecap: "round", strokeLinejoin: "round", fill: "none" },
      ],
      viewBox: "0 0 40 40",
      ariaLabel: "Velocity CRM",
    },
  },

  theme: {
    colors: {
      primary: "#18181B",
      primaryLight: "#27272A",
      accent: "#8B5CF6",
      accentLight: "#A78BFA",
      background: "#FAFAFA",
      sidebar: "#18181B",
      sidebarForeground: "#D4D4D8",
      sidebarBorder: "#27272A",
      sidebarAccent: "#8B5CF6",
      sidebarMuted: "#71717A",
      success: "#16A34A",
      warning: "#D97706",
      error: "#DC2626",
    },
    fonts: {
      display: '"Outfit", system-ui, sans-serif',
      body: '"Outfit", system-ui, sans-serif',
      importUrl: "https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap",
    },
  },

  contacts: {
    entityLabel: "Lead",
    entityLabelPlural: "Leads",
    defaultStatus: "new",
    statuses: [
      { id: "new", label: "New", description: "Unqualified lead", badgeColor: "bg-blue-100 text-blue-700", chartColor: "#3B82F6" },
      { id: "contacted", label: "Contacted", description: "Initial outreach made", badgeColor: "bg-indigo-100 text-indigo-700", chartColor: "#6366F1" },
      { id: "qualified", label: "Qualified", description: "Meets ICP criteria", badgeColor: "bg-purple-100 text-purple-700", chartColor: "#A855F7" },
      { id: "demo_scheduled", label: "Demo Scheduled", description: "Product demo booked", badgeColor: "bg-yellow-100 text-yellow-700", chartColor: "#EAB308" },
      { id: "demo_completed", label: "Demo Completed", description: "Demo delivered", badgeColor: "bg-emerald-100 text-emerald-700", chartColor: "#10B981" },
      { id: "proposal_sent", label: "Proposal Sent", description: "Pricing proposal delivered", badgeColor: "bg-cyan-100 text-cyan-700", chartColor: "#06B6D4" },
      { id: "negotiation", label: "Negotiation", description: "Terms being discussed", badgeColor: "bg-teal-100 text-teal-700", chartColor: "#14B8A6" },
      { id: "customer", label: "Customer", description: "Active paying customer", badgeColor: "bg-green-100 text-green-700", chartColor: "#22C55E" },
      { id: "churned", label: "Churned", description: "Former customer", badgeColor: "bg-red-100 text-red-700", chartColor: "#EF4444" },
      { id: "nurturing", label: "Nurturing", description: "Not ready now, future potential", badgeColor: "bg-amber-100 text-amber-700", chartColor: "#F59E0B" },
      { id: "disqualified", label: "Disqualified", description: "Does not fit ICP", badgeColor: "bg-gray-100 text-gray-600", chartColor: "#6B7280" },
    ],
    sources: [
      { id: "website", label: "Website" },
      { id: "inbound", label: "Inbound" },
      { id: "outbound", label: "Outbound" },
      { id: "referral", label: "Referral" },
      { id: "linkedin", label: "LinkedIn" },
      { id: "google_ads", label: "Google Ads" },
      { id: "content", label: "Content Marketing" },
      { id: "event", label: "Event / Webinar" },
      { id: "partner", label: "Partner" },
      { id: "product_led", label: "Product-Led (Free Trial)" },
      { id: "other", label: "Other" },
    ],
    defaultSource: "inbound",
    temperatures: [
      { id: "hot", label: "Hot", color: "bg-red-500", description: "High intent, ready to buy" },
      { id: "warm", label: "Warm", color: "bg-amber-500", description: "Engaged, evaluating options" },
      { id: "cool", label: "Cool", color: "bg-blue-500", description: "Early stage, researching" },
    ],
    customFields: [
      { key: "company", label: "Company", type: "text" },
      { key: "jobTitle", label: "Job Title", type: "text" },
      { key: "companySize", label: "Company Size", type: "select", options: [
        { value: "1_10", label: "1-10" },
        { value: "11_50", label: "11-50" },
        { value: "51_200", label: "51-200" },
        { value: "201_1000", label: "201-1000" },
        { value: "1000_plus", label: "1000+" },
      ]},
      { key: "annualRevenue", label: "Annual Revenue", type: "text" },
      { key: "useCase", label: "Use Case", type: "text" },
    ],
  },

  pipeline: {
    defaultStage: "lead",
    stages: [
      { id: "lead", label: "Lead", description: "New unqualified lead", borderColor: "border-blue-400" },
      { id: "contacted", label: "Contacted", description: "First touch completed", borderColor: "border-indigo-400" },
      { id: "qualified", label: "Qualified", description: "Budget, authority, need confirmed", borderColor: "border-purple-400" },
      { id: "demo", label: "Demo", description: "Product demo stage", borderColor: "border-yellow-400" },
      { id: "proposal", label: "Proposal", description: "Pricing sent", borderColor: "border-cyan-400" },
      { id: "negotiation", label: "Negotiation", description: "Contract negotiation", borderColor: "border-orange-400" },
      { id: "closed_won", label: "Closed Won", description: "Deal signed", borderColor: "border-green-400" },
      { id: "closed_lost", label: "Closed Lost", description: "Deal lost", borderColor: "border-red-400" },
    ],
  },

  billing: {
    enabled: true,
    currency: "USD",
    currencySymbol: "$",
    tiers: [
      { id: "starter", label: "Starter", fullLabel: "Starter — $49/mo", priceInCents: 4900 },
      { id: "growth", label: "Growth", fullLabel: "Growth — $99/mo", priceInCents: 9900 },
      { id: "pro", label: "Pro", fullLabel: "Pro — $199/mo", priceInCents: 19900 },
      { id: "enterprise", label: "Enterprise", fullLabel: "Enterprise — $499/mo", priceInCents: 49900 },
    ],
  },

  orders: {
    enabled: false,
    types: [],
  },

  compliance: {
    phiEnabled: false,
    ttlHours: 0,
    transferTypes: [],
    partnerLabel: "Partner",
  },

  modules: [
    { id: "dashboard", label: "Dashboard", href: "/dashboard", icon: "LayoutDashboard", description: "Revenue metrics and sales activity", enabled: true },
    { id: "contacts", label: "Leads", href: "/contacts", icon: "Users", section: "Sales", description: "Lead and customer records", enabled: true },
    { id: "pipeline", label: "Deals", href: "/pipeline", icon: "Target", description: "Visual deal pipeline", enabled: true },
    { id: "appointments", label: "Meetings", href: "/appointments", icon: "Calendar", description: "Demo calls and sales meetings", enabled: true },
    { id: "campaigns", label: "Campaigns", href: "/campaigns", icon: "Megaphone", section: "Marketing", description: "Email sequences and ad campaigns", enabled: true },
    { id: "social", label: "Social Media", href: "/social", icon: "Instagram", description: "Social selling and brand posts", enabled: true },
    { id: "orders", label: "Orders", href: "/orders", icon: "ShoppingCart", section: "Operations", description: "Not applicable for SaaS", enabled: false },
    { id: "subscriptions", label: "Subscriptions", href: "/subscriptions", icon: "CreditCard", description: "Customer subscriptions and MRR", enabled: true },
    { id: "tasks", label: "Tasks", href: "/tasks", icon: "CheckSquare", section: "Workflow", description: "Sales tasks and follow-ups", enabled: true },
    { id: "messaging", label: "Messaging", href: "/messaging", icon: "MessageSquare", description: "Prospect and customer messaging", enabled: true },
    { id: "automations", label: "Automations", href: "/automations", icon: "Zap", description: "Automated sales sequences", enabled: true },
    { id: "reports", label: "Reports", href: "/reports", icon: "BarChart3", section: "Insights", description: "Revenue, pipeline, and rep performance", enabled: true },
    { id: "settings", label: "Settings", href: "/settings", icon: "Settings", section: "Admin", description: "Team settings and integrations", enabled: true },
  ],

  appointments: {
    entityLabel: "Meeting",
    types: [
      { id: "discovery_call", label: "Discovery Call" },
      { id: "demo", label: "Product Demo" },
      { id: "technical_review", label: "Technical Review" },
      { id: "contract_review", label: "Contract Review" },
      { id: "onboarding", label: "Onboarding Call" },
      { id: "check_in", label: "QBR / Check-In" },
    ],
    defaultDuration: 45,
    providerLabel: "Sales Rep",
  },

  seed: {
    adminName: "Admin",
    adminEmail: "admin@velocity.com",
    adminPassword: "Admin@123!",
  },

  dashboard: {
    kpis: [
      { id: "totalContacts", label: "Total Leads", description: "All leads and customers", icon: "Users", colorClasses: "text-blue-600 bg-blue-50", dataKey: "totalContacts", format: "number" },
      { id: "newThisMonth", label: "New This Month", description: "New leads this month", icon: "TrendingUp", colorClasses: "text-emerald-600 bg-emerald-50", dataKey: "newContactsThisMonth", format: "number" },
      { id: "activeContacts", label: "Customers", description: "Active paying customers", icon: "Users", colorClasses: "text-green-600 bg-green-50", dataKey: "activePatients", format: "number" },
      { id: "mrr", label: "MRR", description: "Monthly Recurring Revenue", icon: "DollarSign", colorClasses: "text-violet-600 bg-violet-50", dataKey: "mrr", format: "currency" },
      { id: "activeSubs", label: "Active Subs", description: "Paying subscriptions", icon: "DollarSign", colorClasses: "text-purple-600 bg-purple-50", dataKey: "activeSubscriptions", format: "number" },
      { id: "appointments", label: "Meetings", description: "Upcoming meetings", icon: "Calendar", colorClasses: "text-cyan-600 bg-cyan-50", dataKey: "upcomingAppointments", format: "number" },
      { id: "openTasks", label: "Open Tasks", description: "Pending sales tasks", icon: "CheckSquare", colorClasses: "text-orange-600 bg-orange-50", dataKey: "openTasks", format: "number" },
      { id: "urgentTasks", label: "Urgent Tasks", description: "High-priority follow-ups", icon: "AlertTriangle", colorClasses: "text-red-600 bg-red-50", dataKey: "urgentTasks", format: "number" },
    ],
  },
};
