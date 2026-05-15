import type { TenantPreset } from "../types";

export const tenantPresets: TenantPreset[] = [
  {
    id: "general-business",
    name: "General Business",
    description: "A flexible default for agencies, consultants, and service businesses across industries.",
    industry: "general",
    contacts: {
      entityLabel: "Contact",
      entityLabelPlural: "Contacts",
      defaultSource: "website",
      defaultStatus: "new",
      statuses: [
        { id: "new",             label: "New",             description: "Recently added, not yet worked." },
        { id: "qualified",       label: "Qualified",       description: "Meets criteria and ready for follow-up." },
        { id: "proposal_sent",   label: "Proposal Sent",   description: "Proposal or quote out for review." },
        { id: "active_customer", label: "Active Customer", description: "Currently using your service." },
        { id: "inactive",        label: "Inactive",        description: "Not engaged, retained in CRM." },
      ],
      sources: [
        { id: "website",  label: "Website" },
        { id: "referral", label: "Referral" },
        { id: "email",    label: "Email" },
        { id: "phone",    label: "Phone" },
        { id: "social",   label: "Social Media" },
        { id: "event",    label: "Event" },
        { id: "paid_ads", label: "Paid Ads" },
      ],
    },
    billing: {
      tiers: [
        { id: "starter",    label: "Starter" },
        { id: "growth",     label: "Growth" },
        { id: "premium",    label: "Premium" },
        { id: "enterprise", label: "Enterprise" },
      ],
    },
    pipelineStages: [
      { id: "new_inquiry", label: "New Inquiry" },
      { id: "discovery",   label: "Discovery" },
      { id: "proposal",    label: "Proposal" },
      { id: "negotiation", label: "Negotiation" },
      { id: "won",         label: "Won" },
      { id: "lost",        label: "Lost" },
    ],
    automationTemplates: [
      { id: "new-lead-follow-up", label: "New Lead Follow-up", description: "Create a task and start outreach after a new inquiry." },
      { id: "proposal-reminder",  label: "Proposal Reminder",  description: "Follow up after a proposal is sent." },
      { id: "re-engagement",      label: "Re-engagement",      description: "Outreach tasks for inactive contacts." },
    ],
    questionnaireSections: [
      {
        id: "business-profile", label: "Business Profile",
        fields: [
          { id: "company_name", label: "Company Name" },
          { id: "website",      label: "Website" },
          { id: "industry",     label: "Industry" },
        ],
      },
      {
        id: "needs", label: "Needs Assessment",
        fields: [
          { id: "primary_goal",  label: "Primary Goal" },
          { id: "current_tools", label: "Current Tools" },
          { id: "timeline",      label: "Timeline" },
        ],
      },
    ],
  },
  {
    id: "mortgage-lending",
    name: "Mortgage Lending",
    description: "For mortgage brokers, lenders, and loan origination teams.",
    industry: "finance",
    contacts: {
      entityLabel: "Borrower",
      entityLabelPlural: "Borrowers",
      defaultSource: "realtor_referral",
      defaultStatus: "new_lead",
      statuses: [
        { id: "new_lead",            label: "New Lead" },
        { id: "application_started", label: "Application Started" },
        { id: "processing",          label: "Processing" },
        { id: "clear_to_close",      label: "Clear to Close" },
        { id: "funded",              label: "Funded" },
      ],
      sources: [
        { id: "realtor_referral", label: "Realtor Referral" },
        { id: "website",          label: "Website" },
        { id: "past_client",      label: "Past Client" },
        { id: "builder",          label: "Builder" },
        { id: "call_center",      label: "Call Center" },
      ],
    },
    billing: {
      tiers: [
        { id: "conventional", label: "Conventional" },
        { id: "fha",          label: "FHA" },
        { id: "va",           label: "VA" },
        { id: "jumbo",        label: "Jumbo" },
      ],
    },
    pipelineStages: [
      { id: "lead",         label: "Lead" },
      { id: "application",  label: "Application" },
      { id: "processing",   label: "Processing" },
      { id: "underwriting", label: "Underwriting" },
      { id: "closing",      label: "Closing" },
      { id: "funded",       label: "Funded" },
    ],
    automationTemplates: [
      { id: "app-follow-up", label: "Application Follow-up", description: "Follow up on incomplete applications." },
      { id: "docs-reminder", label: "Documents Reminder",    description: "Remind borrowers to submit conditions." },
      { id: "closing-prep",  label: "Closing Prep",          description: "Assign internal tasks before closing." },
    ],
    questionnaireSections: [
      {
        id: "borrower-profile", label: "Borrower Profile",
        fields: [
          { id: "credit_range", label: "Credit Range" },
          { id: "loan_purpose", label: "Loan Purpose" },
          { id: "occupancy",    label: "Occupancy" },
        ],
      },
    ],
    navigationLabels: {
      contacts:      "Borrowers",
      pipeline:      "Loan Pipeline",
      questionnaire: "Loan Application",
    },
  },
  {
    id: "real-estate",
    name: "Real Estate",
    description: "For real estate agents, teams, and brokerages.",
    industry: "real-estate",
    contacts: {
      entityLabel: "Client",
      entityLabelPlural: "Clients",
      defaultSource: "referral",
      defaultStatus: "new_inquiry",
      statuses: [
        { id: "new_inquiry",       label: "New Inquiry" },
        { id: "showing_scheduled", label: "Showing Scheduled" },
        { id: "active_search",     label: "Active Search" },
        { id: "under_contract",    label: "Under Contract" },
        { id: "closed",            label: "Closed" },
      ],
      sources: [
        { id: "referral",   label: "Referral" },
        { id: "zillow",     label: "Zillow" },
        { id: "website",    label: "Website" },
        { id: "open_house", label: "Open House" },
        { id: "social",     label: "Social Media" },
      ],
    },
    billing: {
      tiers: [
        { id: "buyer",    label: "Buyer" },
        { id: "seller",   label: "Seller" },
        { id: "investor", label: "Investor" },
      ],
    },
    pipelineStages: [
      { id: "lead",           label: "Lead" },
      { id: "qualified",      label: "Qualified" },
      { id: "touring",        label: "Touring" },
      { id: "offer",          label: "Offer" },
      { id: "under_contract", label: "Under Contract" },
      { id: "closed",         label: "Closed" },
    ],
    automationTemplates: [
      { id: "buyer-follow-up",    label: "Buyer Follow-up",    description: "Kick off outreach after a new buyer inquiry." },
      { id: "showing-reminder",   label: "Showing Reminder",   description: "Send reminders before a showing." },
      { id: "contract-checklist", label: "Contract Checklist", description: "Assign tasks once a deal goes under contract." },
    ],
    questionnaireSections: [
      {
        id: "property-goals", label: "Property Goals",
        fields: [
          { id: "budget",        label: "Budget" },
          { id: "location",      label: "Preferred Location" },
          { id: "property_type", label: "Property Type" },
        ],
      },
    ],
    navigationLabels: {
      contacts:      "Clients",
      pipeline:      "Deal Pipeline",
      questionnaire: "Buyer Intake",
    },
  },
  {
    id: "healthcare",
    name: "Healthcare",
    description: "For clinics, wellness practices, and patient intake workflows.",
    industry: "healthcare",
    contacts: {
      entityLabel: "Patient",
      entityLabelPlural: "Patients",
      defaultSource: "website",
      defaultStatus: "new_patient",
      statuses: [
        { id: "new_patient",   label: "New Patient" },
        { id: "intake_pending",label: "Intake Pending" },
        { id: "scheduled",     label: "Scheduled" },
        { id: "active_care",   label: "Active Care" },
        { id: "follow_up",     label: "Follow-up" },
      ],
      sources: [
        { id: "website",             label: "Website" },
        { id: "provider_referral",   label: "Provider Referral" },
        { id: "insurance_directory", label: "Insurance Directory" },
        { id: "phone",               label: "Phone" },
      ],
    },
    billing: {
      tiers: [
        { id: "self_pay",   label: "Self Pay" },
        { id: "commercial", label: "Commercial Insurance" },
        { id: "medicare",   label: "Medicare" },
        { id: "medicaid",   label: "Medicaid" },
      ],
    },
    pipelineStages: [
      { id: "intake",       label: "Intake" },
      { id: "verification", label: "Verification" },
      { id: "scheduled",    label: "Scheduled" },
      { id: "active_care",  label: "Active Care" },
      { id: "discharged",   label: "Discharged" },
    ],
    automationTemplates: [
      { id: "intake-reminder",      label: "Intake Reminder",      description: "Remind patients to complete intake paperwork." },
      { id: "appointment-reminder", label: "Appointment Reminder", description: "Send reminders before appointments." },
      { id: "care-follow-up",       label: "Care Follow-up",       description: "Trigger follow-up tasks after visits." },
    ],
    questionnaireSections: [
      {
        id: "patient-intake", label: "Patient Intake",
        fields: [
          { id: "reason_for_visit",   label: "Reason for Visit" },
          { id: "insurance",          label: "Insurance" },
          { id: "preferred_provider", label: "Preferred Provider" },
        ],
      },
    ],
    navigationLabels: {
      contacts:      "Patients",
      pipeline:      "Care Pipeline",
      questionnaire: "Patient Intake",
    },
  },
  {
    id: "saas",
    name: "SaaS",
    description: "For software companies managing leads, trials, and subscribers.",
    industry: "software",
    contacts: {
      entityLabel: "Account",
      entityLabelPlural: "Accounts",
      defaultSource: "website_signup",
      defaultStatus: "trial",
      statuses: [
        { id: "trial",               label: "Trial" },
        { id: "qualified",           label: "Qualified" },
        { id: "demo_booked",         label: "Demo Booked" },
        { id: "active_subscription", label: "Active Subscription" },
        { id: "churn_risk",          label: "Churn Risk" },
      ],
      sources: [
        { id: "website_signup", label: "Website Signup" },
        { id: "content",        label: "Content" },
        { id: "partner",        label: "Partner" },
        { id: "outbound",       label: "Outbound" },
        { id: "paid_ads",       label: "Paid Ads" },
      ],
    },
    billing: {
      tiers: [
        { id: "free",       label: "Free" },
        { id: "pro",        label: "Pro" },
        { id: "business",   label: "Business" },
        { id: "enterprise", label: "Enterprise" },
      ],
    },
    pipelineStages: [
      { id: "signup",     label: "Signup" },
      { id: "activated",  label: "Activated" },
      { id: "demo",       label: "Demo" },
      { id: "proposal",   label: "Proposal" },
      { id: "closed_won", label: "Closed Won" },
      { id: "renewal",    label: "Renewal" },
    ],
    automationTemplates: [
      { id: "trial-onboarding", label: "Trial Onboarding", description: "Create onboarding tasks and reminders after signup." },
      { id: "demo-follow-up",   label: "Demo Follow-up",   description: "Prompt reps to follow up after demos." },
      { id: "renewal-save",     label: "Renewal Save",     description: "Create retention tasks ahead of renewals." },
    ],
    questionnaireSections: [
      {
        id: "product-fit", label: "Product Fit",
        fields: [
          { id: "team_size",     label: "Team Size" },
          { id: "use_case",      label: "Use Case" },
          { id: "current_stack", label: "Current Stack" },
        ],
      },
    ],
    navigationLabels: {
      contacts:      "Accounts",
      pipeline:      "Sales Pipeline",
      questionnaire: "Qualification",
    },
  },
  {
    id: "retail-ecommerce",
    name: "Retail & Ecommerce",
    description: "For stores, ecommerce brands, and customer lifecycle marketing.",
    industry: "retail",
    contacts: {
      entityLabel: "Customer",
      entityLabelPlural: "Customers",
      defaultSource: "online_store",
      defaultStatus: "new_customer",
      statuses: [
        { id: "new_customer",   label: "New Customer" },
        { id: "repeat_buyer",   label: "Repeat Buyer" },
        { id: "vip",            label: "VIP" },
        { id: "abandoned_cart", label: "Abandoned Cart" },
        { id: "inactive",       label: "Inactive" },
      ],
      sources: [
        { id: "online_store", label: "Online Store" },
        { id: "marketplace",  label: "Marketplace" },
        { id: "instagram",    label: "Instagram" },
        { id: "pop_up",       label: "Pop-up Event" },
        { id: "email",        label: "Email" },
      ],
    },
    billing: {
      tiers: [
        { id: "standard",  label: "Standard" },
        { id: "vip",       label: "VIP" },
        { id: "wholesale", label: "Wholesale" },
      ],
    },
    pipelineStages: [
      { id: "new_order",       label: "New Order" },
      { id: "fulfillment",     label: "Fulfillment" },
      { id: "shipped",         label: "Shipped" },
      { id: "delivered",       label: "Delivered" },
      { id: "repeat_purchase", label: "Repeat Purchase" },
    ],
    automationTemplates: [
      { id: "welcome-series", label: "Welcome Series", description: "Start onboarding after a first purchase." },
      { id: "cart-recovery",  label: "Cart Recovery",  description: "Recovery messaging for abandoned carts." },
      { id: "vip-nurture",    label: "VIP Nurture",    description: "Tag and prioritize high-value customers." },
    ],
    questionnaireSections: [
      {
        id: "shopping-profile", label: "Shopping Profile",
        fields: [
          { id: "favorite_category",   label: "Favorite Category" },
          { id: "average_order_value", label: "Average Order Value" },
          { id: "preferred_channel",   label: "Preferred Channel" },
        ],
      },
    ],
    navigationLabels: {
      contacts:      "Customers",
      pipeline:      "Order Pipeline",
      questionnaire: "Customer Profile",
    },
  },
];
