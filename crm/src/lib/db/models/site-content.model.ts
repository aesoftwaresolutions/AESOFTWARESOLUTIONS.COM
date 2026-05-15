import mongoose, { Schema, type Document } from "mongoose";

// ──────────────────────────────────────────
// Sub-schemas
// ──────────────────────────────────────────

const BenefitSchema = new Schema(
  { icon: String, title: String, desc: String },
  { _id: false }
);

const PlanSchema = new Schema(
  {
    name: String,
    price: String,
    firstMonth: String,
    savings: String,
    popular: Boolean,
    features: [String],
  },
  { _id: false }
);

const TestimonialSchema = new Schema(
  {
    name: String,
    age: Number,
    protocol: String,
    quote: String,
    rating: Number,
    stat: String,
    statLabel: String,
  },
  { _id: false }
);

const FAQSchema = new Schema({ q: String, a: String }, { _id: false });

const StatSchema = new Schema(
  { icon: String, value: String, label: String },
  { _id: false }
);

// ──────────────────────────────────────────
// Main schema
// ──────────────────────────────────────────

export interface ISiteContent extends Document {
  key: "main"; // singleton — always "main"
  hero: {
    badge: string;
    eyebrow: string;
    headline: string;
    subheadline: string;
    description: string;
    ctaPrimary: string;
    ctaSecondary: string;
  };
  benefits: {
    heading: string;
    description: string;
    benefits: { icon: string; title: string; desc: string }[];
  };
  pricing: {
    heading: string;
    description: string;
    plans: {
      name: string;
      price: string;
      firstMonth: string;
      savings: string;
      popular: boolean;
      features: string[];
    }[];
  };
  testimonials: {
    heading: string;
    description: string;
    testimonials: {
      name: string;
      age: number;
      protocol: string;
      quote: string;
      rating: number;
      stat: string;
      statLabel: string;
    }[];
  };
  faq: {
    heading: string;
    faqs: { q: string; a: string }[];
  };
  cta: {
    heading: string;
    description: string;
    buttonText: string;
  };
  stats: {
    stats: { icon: string; value: string; label: string }[];
  };
  updatedAt: Date;
  updatedBy?: string;
}

const SiteContentSchema = new Schema<ISiteContent>(
  {
    key: { type: String, default: "main", unique: true },
    hero: {
      badge: String,
      eyebrow: String,
      headline: String,
      subheadline: String,
      description: String,
      ctaPrimary: String,
      ctaSecondary: String,
    },
    benefits: {
      heading: String,
      description: String,
      benefits: [BenefitSchema],
    },
    pricing: {
      heading: String,
      description: String,
      plans: [PlanSchema],
    },
    testimonials: {
      heading: String,
      description: String,
      testimonials: [TestimonialSchema],
    },
    faq: {
      heading: String,
      faqs: [FAQSchema],
    },
    cta: {
      heading: String,
      description: String,
      buttonText: String,
    },
    stats: {
      stats: [StatSchema],
    },
    updatedBy: String,
  },
  { timestamps: true }
);

export const SiteContent =
  mongoose.models.SiteContent ||
  mongoose.model<ISiteContent>("SiteContent", SiteContentSchema);
