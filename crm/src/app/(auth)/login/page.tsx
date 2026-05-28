"use client";

import { useState } from "react";
import { signIn } from "next-auth/react";
import { useRouter } from "next/navigation";
import { tenantConfig } from "@/lib/tenant";

export default function LoginPage() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setLoading(true);
    setError("");

    const result = await signIn("credentials", {
      email,
      password,
      redirect: false,
    });

    if (result?.error) {
      setError("Invalid email or password");
      setLoading(false);
    } else {
      router.push("/dashboard");
    }
  }

  return (
    <div className="w-full max-w-md px-6">
      {/* Logo */}
      <div className="text-center mb-10">
        <div className="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gold/10 mb-4">
          <svg viewBox="0 0 40 40" fill="none" className="w-10 h-10 text-gold">
            <path d="M20 3L5 10v12c0 8.5 6.4 16.4 15 18 8.6-1.6 15-9.5 15-18V10L20 3z" stroke="currentColor" strokeWidth="1.5" fill="none" />
            <path d="M12 20l4-8 4 5 4-5 4 8" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" fill="none" />
            <path d="M12 20h16" stroke="currentColor" strokeWidth="1.5" strokeLinecap="round" />
            <circle cx="20" cy="26" r="1.5" fill="currentColor" opacity="0.6" />
          </svg>
        </div>
        <h1 className="text-2xl font-display text-[#EDE8DC]">{tenantConfig.brand.name}</h1>
        <p className="text-[#5A5550] text-sm mt-1">{tenantConfig.brand.tagline || "Admin Portal"}</p>
      </div>

      {/* Form */}
      <div className="bg-[#1c1c1c] border border-[rgba(237,232,220,0.10)] rounded-xl p-8">
        <form onSubmit={handleSubmit} className="space-y-5">
          {error && (
            <div className="bg-red-500/10 border border-red-500/30 text-red-400 text-sm rounded-lg px-4 py-3">
              {error}
            </div>
          )}

          <div>
            <label className="block text-sm font-medium text-[#BAB5AF] mb-1.5">Email</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              className="w-full px-4 py-2.5 bg-[#141414] border border-[rgba(237,232,220,0.12)] rounded-lg text-[#EDE8DC] placeholder:text-[#5A5550] focus:outline-none focus:ring-2 focus:ring-gold/40 focus:border-gold transition"
              placeholder={tenantConfig.seed.adminEmail || "admin@company.com"}
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-[#BAB5AF] mb-1.5">Password</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
              className="w-full px-4 py-2.5 bg-[#141414] border border-[rgba(237,232,220,0.12)] rounded-lg text-[#EDE8DC] placeholder:text-[#5A5550] focus:outline-none focus:ring-2 focus:ring-gold/40 focus:border-gold transition"
              placeholder="••••••••••"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full py-2.5 bg-gold hover:bg-gold-light text-[#141414] font-semibold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {loading ? "Signing in..." : "Sign In"}
          </button>
        </form>
      </div>

      <p className="text-center text-[#5A5550] text-xs mt-6">
        {tenantConfig.brand.name} &bull; Confidential
      </p>
    </div>
  );
}
