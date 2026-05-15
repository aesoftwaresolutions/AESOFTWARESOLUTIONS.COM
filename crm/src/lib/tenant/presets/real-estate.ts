import type { TenantConfig } from "../types";

/**
 * Real Estate Preset
 *
 * Suitable for: real estate agencies, property management firms,
 * mortgage brokers, commercial RE firms.
 *
 * Pipeline tracks deals from initial inquiry through closing.
 * No PHI compliance. Order types are inspections, appraisals, etc.
 */
export const realEstatePreset: TenantConfig = {
  brand: {
    name: "DealFlow",
    tagline: "Real Estate CRM",
    pageTitle: "DealFlow — Agent Portal",
    metaDescription: "DealFlow — Real Estate CRM & Deal Management Platform",
    logo: {
      paths: [
        { d: "M20 5L6 15h6v12h4V27h4v-12h6L26 5z", stroke: "currentColor", strokeWidth: "1.5", fill: "none" },
        { d: "M14 27v6h12v-6", stroke: "currentColor", strokeWidth: "1.5", strokeLinecap: "round", fill: "none" },
      ],
      viewBox: "0 0 40 40",
      ariaLabel: "DealFlow",
    },
  },

  theme: {
    colors: {
      primary: "#1E293B",
      primaryLight: "#334155",
      accent: "#F97316",
      accentLight: "#FB923C",
      background: "#FFF7ED",
      sidebar: "#1E293B",
      sidebarForeground: "#CBD5E1",
      sidebarBorder: "#334155",
      sidebarAccent: "#F97316",
      sidebarMuted: "#94A3B8",
      success: "#16A34A",
      warning: "#D97706",
      error: "#DC2626",
    },
    fonts: {
      display: '"Plus Jakarta Sans", system-ui, sans-serif',
      body: '"Plus Jakarta Sans", system-ui, sans-serif',
      importUrl: "https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap",
    },
  },

  contacts: {
    entityLabel: "Client",
    entityLabelPlural: "Clients",
    defaultStatus: "inquiry",
    statuses: [
      { id: "inquiry", label: "Inquiry", description: "Initial property inquiry", badgeColor: "bg-blue-100 text-blue-700", chartColor: "#3B82F6" },
      { id: "qualified", label: "Qualified", description: "Pre-qualified buyer/seller", badgeColor: "bg-indigo-100 text-indigo-700", chartColor: "#6366F1" },
      { id: "showing_scheduled", label: "Showing Scheduled", description: "Property showing booked", badgeColor: "bg-purple-100 text-purple-700", chartColor: "#A855F7" },
      { id: "offer_pending", label: "Offer Pending", description: "Offer submitted, awaiting response", badgeColor: "bg-yellow-100 text-yellow-700", chartColor: "#EAB308" },
      { id: "under_contract", label: "Under Contract", description: "Contract signed, in escrow", badgeColor: "bg-emerald-100 text-emerald-700", chartColor: "#10B981" },
      { id: "inspection", label: "Inspection", description: "Property inspection phase", badgeColor: "bg-cyan-100 text-cyan-700", chartColor: "#06B6D4" },
      { id: "closing", label: "Closing", description: "Final closing process", badgeColor: "bg-teal-100 text-teal-700", chartColor: "#14B8A6" },
      { id: "closed_won", label: "Closed Won", description: "Deal completed successfully", badgeColor: "bg-green-100 text-green-700", chartColor: "#22C55E" },
      { id: "closed_lost", label: "Closed Lost", description: "Deal fell through", badgeColor: "bg-red-100 text-red-700", chartColor: "#EF4444" },
      { id: "nurturing", label: "Nurturing", description: "Long-term follow-up", badgeColor: "bg-amber-100 text-amber-700", chartColor: "#F59E0B" },
      { id: "inactive", label: "Inactive", description: "No longer active", badgeColor: "bg-gray-100 text-gray-600", chartColor: "#6B7280" },
    ],
    sources: [
      { id: "website", label: "Website" },
      { id: "referral", label: "Referral" },
      { id: "zillow", label: "Zillow" },
      { id: "realtor_com", label: "Realtor.com" },
      { id: "open_house", label: "Open House" },
      { id: "google_ads", label: "Google Ads" },
      { id: "social_media", label: "Social Media" },
      { id: "cold_call", label: "Cold Call" },
      { id: "sign_call", label: "Sign Call" },
      { id: "direct", label: "Direct" },
      { id: "other", label: "Other" },
    ],
    defaultSource: "website",
    temperatures: [
      { id: "hot", label: "Hot", color: "bg-red-500", description: "Ready to buy/sell now" },
      { id: "warm", label: "Warm", color: "bg-amber-500", description: "Active in 1-3 months" },
      { id: "cool", label: "Cool", color: "bg-blue-500", description: "6+ months out" },
    ],
    customFields: [
      { key: "propertyType", label: "Property Type", type: "select", options: [
        { value: "single_family", label: "Single Family" },
        { value: "condo", label: "Condo" },
        { value: "townhouse", label: "Townhouse" },
        { value: "multi_family", label: "Multi-Family" },
        { value: "commercial", label: "Commercial" },
        { value: "land", label: "Land" },
      ]},
      { key: "budgetRange", label: "Budget Range", type: "text" },
      { key: "preferredArea", label: "Preferred Area", type: "text" },
      { key: "preApproved", label: "Pre-Approved", type: "boolean", defaultValue: false },
    ],
  },

  pipeline: {
    defaultStage: "new_inquiry",
    stages: [
      { id: "new_inquiry", label: "New Inquiry", description: "Initial contact received", borderColor: "border-blue-400" },
      { id: "qualification", label: "Qualification", description: "Assessing needs and budget", borderColor: "border-indigo-400" },
      { id: "property_search", label: "Property Search", description: "Identifying matching properties", borderColor: "border-purple-400" },
      { id: "showing", label: "Showing", description: "Touring properties", borderColor: "border-yellow-400" },
      { id: "offer", label: "Offer", description: "Offer submitted", borderColor: "border-cyan-400" },
      { id: "negotiation", label: "Negotiation", description: "Counter-offers in progress", borderColor: "border-orange-400" },
      { id: "under_contract", label: "Under Contract", description: "Contract signed", borderColor: "border-teal-400" },
      { id: "closing", label: "Closing", description: "Final paperwork and closing", borderColor: "border-green-400" },
      { id: "closed_won", label: "Closed Won", description: "Transaction completed", borderColor: "border-emerald-400" },
      { id: "closed_lost", label: "Closed Lost", description: "Deal fell through", borderColor: "border-red-400" },
    ],
  },

  billing: {
    enabled: false,
    currency: "USD",
    currencySymbol: "$",
    tiers: [],
  },

  orders: {
    enabled: true,
    types: [
      { id: "inspection", label: "Home Inspection" },
      { id: "appraisal", label: "Appraisal" },
      { id: "title_search", label: "Title Search" },
      { id: "survey", label: "Property Survey" },
    ],
  },

  compliance: {
    phiEnabled: false,
    ttlHours: 0,
    transferTypes: [],
    partnerLabel: "Partner",
  },

  modules: [
    { id: "dashboard", label: "Dashboard", href: "/dashboard", icon: "LayoutDashboard", description: "Deal metrics and activity overview", enabled: true },
    { id: "contacts", label: "Clients", href: "/contacts", icon: "Users", section: "CRM", description: "Buyer and seller client records", enabled: true },
    { id: "pipeline", label: "Deals", href: "/pipeline", icon: "Target", description: "Track deals from inquiry to closing", enabled: true },
    { id: "appointments", label: "Showings", href: "/appointments", icon: "Calendar", description: "Schedule property showings and meetings", enabled: true },
    { id: "campaigns", label: "Campaigns", href: "/campaigns", icon: "Megaphone", section: "Marketing", description: "Email drip campaigns and mailers", enabled: true },
    { id: "social", label: "Social Media", href: "/social", icon: "Instagram", description: "Listing promotions on social", enabled: true },
    { id: "orders", label: "Services", href: "/orders", icon: "ShoppingCart", section: "Operations", description: "Inspections, appraisals, and surveys", enabled: true },
    { id: "subscriptions", label: "Subscriptions", href: "/subscriptions", icon: "CreditCard", description: "Not applicable", enabled: false },
    { id: "tasks", label: "Tasks", href: "/tasks", icon: "CheckSquare", section: "Workflow", description: "Agent tasks and follow-ups", enabled: true },
    { id: "messaging", label: "Messaging", href: "/messaging", icon: "MessageSquare", description: "Client communication", enabled: true },
    { id: "automations", label: "Automations", href: "/automations", icon: "Zap", description: "Automated follow-up sequences", enabled: true },
    { id: "reports", label: "Reports", href: "/reports", icon: "BarChart3", section: "Insights", description: "Deal analytics and agent performance", enabled: true },
    { id: "settings", label: "Settings", href: "/settings", icon: "Settings", section: "Admin", description: "Brokerage settings and agent management", enabled: true },
  ],

  appointments: {
    entityLabel: "Showing",
    types: [
      { id: "showing", label: "Property Showing" },
      { id: "open_house", label: "Open House" },
      { id: "listing_appointment", label: "Listing Appointment" },
      { id: "closing_meeting", label: "Closing Meeting" },
      { id: "consultation", label: "Buyer Consultation" },
    ],
    defaultDuration: 60,
    providerLabel: "Agent",
  },

  seed: {
    adminName: "Admin",
    adminEmail: "admin@dealflow.com",
    adminPassword: "Admin@123!",
  },

  dashboard: {
    kpis: [
      { id: "totalContacts", label: "Total Clients", description: "All clients in the system", icon: "Users", colorClasses: "text-blue-600 bg-blue-50", dataKey: "totalContacts", format: "number" },
      { id: "newThisMonth", label: "New This Month", description: "New clients this month", icon: "TrendingUp", colorClasses: "text-emerald-600 bg-emerald-50", dataKey: "newContactsThisMonth", format: "number" },
      { id: "activeContacts", label: "Active Deals", description: "Deals in progress", icon: "Users", colorClasses: "text-green-600 bg-green-50", dataKey: "activePatients", format: "number" },
      { id: "mrr", label: "Pipeline Value", description: "Total value of active deals", icon: "DollarSign", colorClasses: "text-amber-600 bg-amber-50", dataKey: "mrr", format: "currency" },
      { id: "appointments", label: "Showings", description: "Upcoming showings", icon: "Calendar", colorClasses: "text-cyan-600 bg-cyan-50", dataKey: "upcomingAppointments", format: "number" },
      { id: "openTasks", label: "Open Tasks", description: "Pending tasks", icon: "CheckSquare", colorClasses: "text-orange-600 bg-orange-50", dataKey: "openTasks", format: "number" },
      { id: "urgentTasks", label: "Urgent Tasks", description: "High-priority items", icon: "AlertTriangle", colorClasses: "text-red-600 bg-red-50", dataKey: "urgentTasks", format: "number" },
    ],
  },
};
