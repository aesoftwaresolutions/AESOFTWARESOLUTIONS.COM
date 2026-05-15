export interface TenantOption {
  id: string;
  label: string;
  description?: string;
}

export interface TenantStatus {
  id: string;
  label: string;
  description?: string;
}

export interface TenantContactsConfig {
  entityLabel: string;
  entityLabelPlural: string;
  defaultSource: string;
  defaultStatus: string;
  statuses: TenantStatus[];
  sources: TenantOption[];
}

export interface TenantBillingConfig {
  tiers: TenantOption[];
}

export interface TenantOpportunityStage {
  id: string;
  label: string;
  description?: string;
}

export interface TenantAutomationTemplate {
  id: string;
  label: string;
  description: string;
}

export interface TenantQuestionnaireSection {
  id: string;
  label: string;
  fields: TenantOption[];
}

export interface TenantPreset {
  id: string;
  name: string;
  description: string;
  industry: string;
  contacts: TenantContactsConfig;
  billing: TenantBillingConfig;
  pipelineStages: TenantOpportunityStage[];
  automationTemplates: TenantAutomationTemplate[];
  questionnaireSections: TenantQuestionnaireSection[];
  navigationLabels?: Partial<Record<
    | "dashboard" | "contacts" | "pipeline" | "appointments"
    | "orders" | "subscriptions" | "reports"
    | "settings" | "website" | "questionnaire",
    string
  >>;
}
