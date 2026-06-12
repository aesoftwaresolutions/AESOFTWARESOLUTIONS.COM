"use client";

import { useState, useEffect } from "react";
import { useRouter } from "next/navigation";
import { tenantConfig } from "@/lib/tenant";
import { BrandLogo } from "@/components/layout/ae-logo";

export default function SetupPage() {
  const router = useRouter();
  const [step, setStep] = useState<"checking" | "setup" | "done" | "already_setup">("checking");
  const [name, setName] = useState(tenantConfig.seed.adminName);
  const [email, setEmail] = useState(tenantConfig.seed.adminEmail);
  const [password, setPassword] = useState("");
  const [confirmPassword, setConfirmPassword] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");

  useEffect(() => {
    fetch("/api/setup")
      .then((r) => r.json())
      .then((data) => {
        if (data.setupRequired) {
          setStep("setup");
        } else {
          setStep("already_setup");
        }
      })
      .catch(() => setStep("setup"));
  }, []);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setError("");

    if (password.length < 8) {
      setError("Password must be at least 8 characters.");
      return;
    }
    if (password !== confirmPassword) {
      setError("Passwords do not match.");
      return;
    }

    setLoading(true);
    try {
      const res = await fetch("/api/setup", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, email, password }),
      });
      const data = await res.json();

      if (res.ok) {
        setStep("done");
      } else {
        setError(data.error || "Setup failed.");
      }
    } catch {
      setError("Network error. Please try again.");
    } finally {
      setLoading(false);
    }
  }

  const accentColor = tenantConfig.theme.colors.accent;

  return (
    <div className="min-h-screen bg-cream flex items-center justify-center p-4">
      <div className="w-full max-w-md">
        {/* Logo + Brand */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4" style={{ backgroundColor: accentColor + "20" }}>
            <BrandLogo className="h-10 w-10" style={{ color: accentColor } as React.CSSProperties} />
          </div>
          <h1 className="text-3xl font-display text-gray-900">{tenantConfig.brand.name}</h1>
          <p className="text-gray-500 mt-1">{tenantConfig.brand.tagline}</p>
        </div>

        <div className="bg-white rounded-2xl border border-gray-200 shadow-lg p-8">
          {/* Checking state */}
          {step === "checking" && (
            <div className="text-center py-8">
              <div className="w-8 h-8 border-2 border-gray-300 border-t-gray-900 rounded-full animate-spin mx-auto mb-4" />
              <p className="text-gray-500">Checking setup status...</p>
            </div>
          )}

          {/* Already setup */}
          {step === "already_setup" && (
            <div className="text-center py-6">
              <div className="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg className="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                  <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <h2 className="text-xl font-semibold text-gray-900 mb-2">Already Configured</h2>
              <p className="text-gray-500 mb-6">
                Your {tenantConfig.brand.name} CRM is already set up with an admin account.
              </p>
              <button
                onClick={() => router.push("/login")}
                className="w-full py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90"
                style={{ backgroundColor: accentColor }}
              >
                Go to Login
              </button>
            </div>
          )}

          {/* Setup form */}
          {step === "setup" && (
            <>
              <div className="text-center mb-6">
                <h2 className="text-xl font-semibold text-gray-900">Initial Setup</h2>
                <p className="text-sm text-gray-500 mt-1">Create your admin account to get started</p>
              </div>

              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                  <input
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    required
                    placeholder="John Smith"
                    className="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent transition"
                    style={{ "--tw-ring-color": accentColor + "40" } as React.CSSProperties}
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                  <input
                    type="email"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    required
                    placeholder="admin@company.com"
                    className="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent transition"
                    style={{ "--tw-ring-color": accentColor + "40" } as React.CSSProperties}
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                  <input
                    type="password"
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    required
                    minLength={8}
                    placeholder="Min 8 characters"
                    className="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent transition"
                    style={{ "--tw-ring-color": accentColor + "40" } as React.CSSProperties}
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-700 mb-1.5">Confirm Password</label>
                  <input
                    type="password"
                    value={confirmPassword}
                    onChange={(e) => setConfirmPassword(e.target.value)}
                    required
                    minLength={8}
                    placeholder="Re-enter password"
                    className="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent transition"
                    style={{ "--tw-ring-color": accentColor + "40" } as React.CSSProperties}
                  />
                </div>

                {error && (
                  <div className="bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-sm text-red-700">
                    {error}
                  </div>
                )}

                <button
                  type="submit"
                  disabled={loading}
                  className="w-full py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90 disabled:opacity-50 mt-2"
                  style={{ backgroundColor: accentColor }}
                >
                  {loading ? (
                    <span className="flex items-center justify-center gap-2">
                      <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                      Creating Account...
                    </span>
                  ) : (
                    "Create Admin Account"
                  )}
                </button>
              </form>

              <div className="mt-6 pt-4 border-t border-gray-100">
                <p className="text-xs text-gray-400 text-center">
                  This creates a superadmin account with full access to {tenantConfig.brand.name} CRM.
                  You can add more users after logging in.
                </p>
              </div>
            </>
          )}

          {/* Success */}
          {step === "done" && (
            <div className="text-center py-6">
              <div className="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style={{ backgroundColor: accentColor + "20" }}>
                <svg className="w-8 h-8" style={{ color: accentColor }} fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                  <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                </svg>
              </div>
              <h2 className="text-xl font-semibold text-gray-900 mb-2">Setup Complete!</h2>
              <p className="text-gray-500 mb-2">
                Your admin account has been created.
              </p>
              <p className="text-sm text-gray-400 mb-6">
                Log in with <strong className="text-gray-600">{email}</strong> and the password you just set.
              </p>
              <button
                onClick={() => router.push("/login")}
                className="w-full py-3 rounded-xl font-semibold text-white transition-all hover:opacity-90"
                style={{ backgroundColor: accentColor }}
              >
                Go to Login
              </button>
            </div>
          )}
        </div>

        {/* Footer */}
        <p className="text-center text-xs text-gray-400 mt-6">
          Powered by {tenantConfig.brand.name} CRM Platform
        </p>
      </div>
    </div>
  );
}
