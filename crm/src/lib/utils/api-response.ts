import { NextRequest, NextResponse } from "next/server";
import { ZodError } from "zod";

/**
 * Standard API response helpers for consistency.
 */

export function successResponse<T>(data: T, status = 200) {
  return NextResponse.json({ success: true, data }, { status });
}

export function errorResponse(message: string, status = 400) {
  return NextResponse.json({ success: false, error: message }, { status });
}

export function validationError(error: ZodError) {
  return NextResponse.json(
    {
      success: false,
      error: "Validation failed",
      details: error.errors.map((e) => ({
        field: e.path.join("."),
        message: e.message,
      })),
    },
    { status: 422 }
  );
}

export function paginatedResponse<T>(
  data: T[],
  total: number,
  page: number,
  limit: number
) {
  return NextResponse.json({
    success: true,
    data,
    pagination: {
      total,
      page,
      limit,
      totalPages: Math.ceil(total / limit),
      hasMore: page * limit < total,
    },
  });
}

/**
 * Wrap an API route handler with a global error boundary.
 * Catches unhandled exceptions and returns a clean 500 response
 * instead of leaking stack traces.
 */
type RouteHandler = (req: NextRequest, context?: any) => Promise<NextResponse | Response>;

export function withErrorHandler(handler: RouteHandler): RouteHandler {
  return async (req: NextRequest, context?: any) => {
    try {
      return await handler(req, context);
    } catch (err: any) {
      console.error(`[API Error] ${req.method} ${req.nextUrl.pathname}:`, err);

      if (err instanceof ZodError) {
        return validationError(err);
      }

      const isDev = process.env.NODE_ENV === "development";
      return NextResponse.json(
        {
          success: false,
          error: "Internal server error",
          ...(isDev && { details: err.message, stack: err.stack }),
        },
        { status: 500 }
      );
    }
  };
}
