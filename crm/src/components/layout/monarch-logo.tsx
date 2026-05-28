"use client";

/**
 * Brand Logo — renders the SVG logo defined in tenant config.
 * Uses currentColor for theme-adaptive rendering.
 */

import { tenantConfig } from "@/lib/tenant";

export function BrandLogo({ className = "h-8 w-8", style }: { className?: string, style?: React.CSSProperties }) {
  const { logo } = tenantConfig.brand;

  return (
    <svg
      viewBox={logo.viewBox || "0 0 40 40"}
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className={className}
      style={style}
      aria-label={logo.ariaLabel || tenantConfig.brand.name}
    >
      {logo.paths.map((path, i) => {
        if (path.element === "circle") {
          return (
            <circle
              key={i}
              cx={path.cx}
              cy={path.cy}
              r={path.r}
              fill={path.fill || "none"}
              opacity={path.opacity}
            />
          );
        }
        return (
          <path
            key={i}
            d={path.d}
            stroke={path.stroke}
            strokeWidth={path.strokeWidth}
            strokeLinecap={path.strokeLinecap as CanvasLineCap | undefined}
            strokeLinejoin={path.strokeLinejoin as CanvasLineJoin | undefined}
            fill={path.fill || "none"}
            opacity={path.opacity}
          />
        );
      })}
    </svg>
  );
}

// Backward-compat alias
export const MonarchLogo = BrandLogo;
