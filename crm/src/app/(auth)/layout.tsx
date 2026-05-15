export default function AuthLayout({ children }: { children: React.ReactNode }) {
  return (
    <div className="min-h-screen bg-[#0F0F0F] flex items-center justify-center">
      {children}
    </div>
  );
}
