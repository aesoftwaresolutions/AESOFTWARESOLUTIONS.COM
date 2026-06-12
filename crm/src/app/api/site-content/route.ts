import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SiteContent } from "@/lib/db/models/site-content.model";

// ── Static defaults for aesoftwaresolutions.com ────────────────────────────────
const DEFAULTS = {
  hero: {
    badge: "AI AUTOMATION • PENNSYLVANIA SMALL BUSINESS",
    eyebrow: "COMPLIANCE-AWARE OPERATIONAL INTELLIGENCE",
    headline: "AE SOFTWARE SOLUTIONS",
    subheadline: "Built for PA Small Business.",
    description:
      "We build AI automation workflows that recover missed leads, eliminate manual follow-up, and give Pennsylvania small businesses enterprise-grade systems at small-business cost.",
    ctaPrimary: "Get Free Assessment →",
    ctaSecondary: "See How It Works ↓",
  },
  benefits: {
    heading: "Why PA Businesses Choose AE Software",
    description:
      "We handle the build, testing, and ongoing monitoring so you can stay focused on the work — not the tools.",
    benefits: [
      {
        icon: "Zap",
        title: "Built & Deployed Fast",
        desc: "Most automation workflows are live within 2–5 business days. No lengthy onboarding, no bloated contracts — just working systems.",
      },
      {
        icon: "TrendingUp",
        title: "Revenue Recovery Focus",
        desc: "Every system we build targets a measurable revenue leak — missed calls, cold leads, no-shows, lost referrals. You see the ROI in the first 30 days.",
      },
      {
        icon: "Shield",
        title: "Pennsylvania-Local",
        desc: "We understand PA markets — PI law, home services, independent mortgage. Our workflows are built around the way PA buyers actually behave.",
      },
    ],
  },
  pricing: {
    heading: "Service Packages",
    description:
      "Flat-rate packages. No surprises. Everything includes setup, testing, and 30-day support.",
    plans: [
      {
        name: "Starter Automation",
        price: "$497",
        firstMonth: "$297",
        savings: "$200",
        popular: false,
        features: [
          "1 automation workflow built & deployed",
          "Lead capture or missed call text-back",
          "CRM integration",
          "30-day support included",
          "Monthly performance report",
        ],
      },
      {
        name: "Growth Stack",
        price: "$1,197",
        firstMonth: "$797",
        savings: "$400",
        popular: true,
        features: [
          "3 automation workflows",
          "Lead capture + follow-up sequences",
          "Review request automation",
          "Appointment reminders",
          "Monthly reporting dashboard",
          "60-day priority support",
        ],
      },
      {
        name: "Full Ops Build",
        price: "$2,497",
        firstMonth: "$1,797",
        savings: "$700",
        popular: false,
        features: [
          "5+ automation workflows",
          "Complete lead-to-close pipeline",
          "Custom CRM setup",
          "n8n workflow automation",
          "Quarterly strategy session",
          "Ongoing monitoring & optimization",
        ],
      },
    ],
  },
  testimonials: {
    heading: "Results from PA Businesses",
    description: "Real outcomes from real businesses across Pennsylvania.",
    testimonials: [
      {
        name: "Mike T.",
        age: 44,
        protocol: "Home Services — HVAC",
        quote:
          "We were losing jobs to competitors who picked up faster. After deploying the missed call text-back, we recovered 6 jobs in the first two weeks.",
        rating: 5,
        stat: "+$4,200",
        statLabel: "First month recovered",
      },
      {
        name: "Sarah M.",
        age: 38,
        protocol: "Personal Injury Law",
        quote:
          "Our intake was manual and slow. The automated follow-up sequence cut our lead response time from hours to seconds. Conversion is up significantly.",
        rating: 5,
        stat: "3x faster",
        statLabel: "Lead response time",
      },
      {
        name: "David L.",
        age: 51,
        protocol: "Independent Mortgage",
        quote:
          "I was losing overnight leads to automated competitors. The instant responder alone changed my close rate on internet leads.",
        rating: 5,
        stat: "+22%",
        statLabel: "Internet lead close rate",
      },
    ],
  },
  faq: {
    heading: "Frequently Asked Questions",
    faqs: [
      {
        q: "What does AE Software Solutions actually build?",
        a: "We build AI automation workflows — lead capture, follow-up sequences, missed call text-back, appointment reminders, review requests, and reporting systems — specifically for Pennsylvania small businesses in home services, legal, and mortgage.",
      },
      {
        q: "How long does setup take?",
        a: "Most single workflows are live within 2–5 business days. The Growth Stack typically takes 7–10 days. We handle all setup, testing, and integration with your existing tools.",
      },
      {
        q: "What tools do you integrate with?",
        a: "We connect with your website contact forms, Google Business Profile, most CRMs (HubSpot, custom), VoIP providers (RingCentral, Twilio), scheduling software (Jobber, Calendly, Housecall Pro), and invoicing tools.",
      },
      {
        q: "Do I need technical knowledge to use these systems?",
        a: "No. We build it, test it, and hand it off configured and running. You'll get a simple dashboard to monitor results. No coding required.",
      },
      {
        q: "What happens after the 30-day support period?",
        a: "You own the workflows. After the support period, systems run independently. We offer optional ongoing monitoring packages if you want us to keep an eye on things.",
      },
    ],
  },
  cta: {
    heading: "Find Your Biggest Revenue Leak",
    description: "Free 15-minute assessment. We'll show you exactly where your business is losing leads and what it would cost to fix it.",
    buttonText: "Book Free Assessment →",
  },
  stats: {
    stats: [
      { icon: "Zap", value: "48hr", label: "Average Deploy Time" },
      { icon: "TrendingUp", value: "4.9★", label: "Client Rating" },
      { icon: "MapPin", value: "PA-Based", label: "Local & Available" },
    ],
  },
};

// GET — public
export async function GET() {
  try {
    await connectDB();
    const doc = await SiteContent.findOne({ key: "main" }).lean() as any;
    if (!doc) {
      return NextResponse.json(DEFAULTS, {
        headers: {
          "Access-Control-Allow-Origin": "*",
          "Cache-Control": "s-maxage=60, stale-while-revalidate=300",
        },
      });
    }
    // Merge DB content over defaults so any missing fields use defaults
    const merged = {
      hero: { ...DEFAULTS.hero, ...(doc.hero || {}) },
      benefits: { ...DEFAULTS.benefits, ...(doc.benefits || {}) },
      pricing: { ...DEFAULTS.pricing, ...(doc.pricing || {}) },
      testimonials: { ...DEFAULTS.testimonials, ...(doc.testimonials || {}) },
      faq: { ...DEFAULTS.faq, ...(doc.faq || {}) },
      cta: { ...DEFAULTS.cta, ...(doc.cta || {}) },
      stats: { ...DEFAULTS.stats, ...(doc.stats || {}) },
    };
    return NextResponse.json(merged, {
      headers: {
        "Access-Control-Allow-Origin": "*",
        "Cache-Control": "s-maxage=60, stale-while-revalidate=300",
      },
    });
  } catch (err) {
    console.error("[site-content] GET error:", err);
    return NextResponse.json(DEFAULTS, {
      headers: { "Access-Control-Allow-Origin": "*" },
    });
  }
}

// OPTIONS — CORS preflight
export async function OPTIONS() {
  return new NextResponse(null, {
    headers: {
      "Access-Control-Allow-Origin": "*",
      "Access-Control-Allow-Methods": "GET, PATCH, OPTIONS",
      "Access-Control-Allow-Headers": "Content-Type",
    },
  });
}

// PATCH — requires CRM auth session
export async function PATCH(req: NextRequest) {
  const session = await getServerSession(authOptions);
  if (!session) return NextResponse.json({ error: "Unauthorized" }, { status: 401 });

  try {
    const body = await req.json();
    await connectDB();

    const updated = await SiteContent.findOneAndUpdate(
      { key: "main" },
      {
        $set: {
          ...body,
          key: "main",
          updatedBy: (session.user as any)?.email || "unknown",
        },
      },
      { upsert: true, new: true, runValidators: true }
    ).lean();

    // Optionally trigger a site redeploy via webhook
    const deployHook = process.env.SITE_DEPLOY_HOOK;
    if (deployHook) {
      await fetch(deployHook, { method: "POST" }).catch((e) =>
        console.error("[site-content] deploy hook failed:", e)
      );
    }

    return NextResponse.json({ success: true, updatedAt: (updated as any)?.updatedAt });
  } catch (err) {
    console.error("[site-content] PATCH error:", err);
    return NextResponse.json({ error: "Failed to save" }, { status: 500 });
  }
}
