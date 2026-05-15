import { NextRequest, NextResponse } from "next/server";
import { getServerSession } from "next-auth";
import { authOptions } from "@/lib/auth";
import { connectDB } from "@/lib/db/connection";
import { SiteContent } from "@/lib/db/models/site-content.model";

// ── Static defaults (mirrors the monarch-mvc /src/content JSON files) ──────────
const DEFAULTS = {
  hero: {
    badge: "BOARD-CERTIFIED PHYSICIANS • LICENSED IN ALL 50 STATES",
    eyebrow: "EXECUTIVE PRECISION",
    headline: "MEN'S VITALITY CENTER",
    subheadline: "Clinician-Led. Personalized.",
    description:
      "Transform your health with physician-led hormone optimization. Advanced testing, premium treatment, ongoing clinical oversight. Built for men who demand excellence.",
    ctaPrimary: "Start Free Assessment →",
    ctaSecondary: "How It Works ↓",
  },
  benefits: {
    heading: "Why Executives Choose Monarch MVC",
    description:
      "Clinical excellence meets executive precision. Our physician-led platform delivers results through advanced science, personalized care, and premium service.",
    benefits: [
      {
        icon: "Activity",
        title: "Physician-Led Care",
        desc: "Board-certified physicians specialized in hormone optimization. Every treatment plan reviewed and approved by licensed medical professionals with deep expertise in men's health.",
      },
      {
        icon: "Search",
        title: "Advanced Testing",
        desc: "Comprehensive hormone panels analyzed by CLIA-certified laboratories. Results reviewed within 48 hours by your dedicated provider team for rapid, precise diagnosis.",
      },
      {
        icon: "Package",
        title: "Premium Delivery",
        desc: "Discrete, direct-to-door monthly shipments from FDA-registered 503B pharmacies. Complete injection kits with all supplies included for seamless treatment.",
      },
    ],
  },
  pricing: {
    heading: "Choose Your Protocol",
    description:
      "All plans include physician oversight, lab monitoring, and premium medication delivery.",
    plans: [
      {
        name: "Executive TRT Core",
        price: "$229",
        firstMonth: "$129",
        savings: "$100",
        popular: false,
        features: [
          "Testosterone replacement therapy",
          "Quarterly lab monitoring",
          "Board-certified physician oversight",
          "Monthly medication delivery",
          "Secure provider messaging",
          "Complete injection supplies",
        ],
      },
      {
        name: "Executive TRT + Performance",
        price: "$289",
        firstMonth: "$179",
        savings: "$110",
        popular: true,
        features: [
          "Everything in TRT Core",
          "ED medication included",
          "Performance optimization protocol",
          "Priority provider scheduling",
          "Vitamin supplementation",
          "Advanced metabolic monitoring",
        ],
      },
      {
        name: "Executive GLP-1 Metabolic Reset",
        price: "$399",
        firstMonth: "$249",
        savings: "$150",
        popular: false,
        features: [
          "GLP-1 medication (dose per clinical protocol)",
          "Monthly clinician oversight",
          "Quarterly lab monitoring",
          "Nutrition guidance",
          "Secure messaging",
        ],
      },
    ],
  },
  testimonials: {
    heading: "Real Results",
    description: "Hear from men who transformed their health with Monarch MVC protocols.",
    testimonials: [
      {
        name: "Marcus D.",
        age: 42,
        protocol: "Testosterone Optimization",
        quote:
          "After 8 weeks on TRT with Monarch MVC, my energy is through the roof. I'm hitting PRs in the gym again, sleeping better, and my wife says I'm a different man.",
        rating: 5,
        stat: "+180 ng/dL",
        statLabel: "Total T increase",
      },
      {
        name: "James R.",
        age: 38,
        protocol: "Medical Weight Loss",
        quote:
          "Semaglutide changed my life. I lost 47 lbs in 5 months without feeling deprived. The Monarch team adjusted my dose perfectly and monitored my labs every step of the way.",
        rating: 5,
        stat: "–47 lbs",
        statLabel: "In 5 months",
      },
      {
        name: "Robert K.",
        age: 51,
        protocol: "Blood Flow & Performance",
        quote:
          "Daily tadalafil has been a game-changer — not just for performance, but my workouts have improved too. The discreet delivery and physician consults made the whole process seamless.",
        rating: 5,
        stat: "100%",
        statLabel: "Confidence restored",
      },
      {
        name: "Anthony M.",
        age: 45,
        protocol: "Testosterone + Weight Loss",
        quote:
          "I stacked TRT with the weight loss protocol and the results are unreal. Down 35 lbs, up in muscle, and my bloodwork is the best it's been in 15 years.",
        rating: 5,
        stat: "–35 lbs",
        statLabel: "+ lean muscle gain",
      },
    ],
  },
  faq: {
    heading: "Frequently Asked Questions",
    faqs: [
      {
        q: "What is Monarch Men's Vitality Center (MVC)?",
        a: "Monarch MVC is a physician-led telehealth platform delivering premium care for men seeking hormone optimization, performance enhancement, and metabolic health.",
      },
      {
        q: "How does the treatment work?",
        a: "Our treatment begins with a comprehensive health assessment and lab testing. Once approved by our board-certified physicians, you'll receive a personalized treatment plan with monthly medication deliveries and ongoing clinical oversight.",
      },
      {
        q: "Is this FDA-approved?",
        a: "Yes. We only prescribe FDA-approved medications from FDA-registered 503B compounding pharmacies. All treatments are overseen by licensed, board-certified physicians following evidence-based protocols.",
      },
      {
        q: "How quickly will I see results?",
        a: "Most men report increased energy and improved mood within 2-4 weeks. Full optimization typically occurs within 3-6 months with regular monitoring and dosage adjustments.",
      },
      {
        q: "What if I'm not approved for treatment?",
        a: "If our physicians determine TRT isn't medically appropriate for you, you'll receive a full refund. We only treat patients when it's clinically indicated and safe.",
      },
    ],
  },
  cta: {
    heading: "Ready to Reclaim Your Edge?",
    description: "Start your free health assessment today. No commitment required.",
    buttonText: "Start Free Assessment →",
  },
  stats: {
    stats: [
      { icon: "Users", value: "1,000+", label: "Active Patients" },
      { icon: "Star", value: "4.8★", label: "Average Rating" },
      { icon: "Shield", value: "100%", label: "HIPAA Compliant" },
    ],
  },
};

// GET — public (monarch-mvc reads this)
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

    // Trigger monarchmvc.com redeploy — awaited so Vercel doesn't kill it before it fires
    const deployHook = process.env.VERCEL_MVC_DEPLOY_HOOK;
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
