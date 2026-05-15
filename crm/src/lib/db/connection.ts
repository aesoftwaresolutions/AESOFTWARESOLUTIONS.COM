import mongoose from "mongoose";
import { setServers } from "dns";

// Fix for Windows: Node.js defaults to 127.0.0.1 DNS which blocks SRV lookups
setServers(["8.8.8.8", "1.1.1.1"]);

interface MongooseCache {
  conn: typeof mongoose | null;
  promise: Promise<typeof mongoose> | null;
}

declare global {
  // eslint-disable-next-line no-var
  var mongooseCache: MongooseCache | undefined;
}

const cached: MongooseCache = global.mongooseCache ?? { conn: null, promise: null };

if (!global.mongooseCache) {
  global.mongooseCache = cached;
}

export async function connectDB() {
  // Check MONGODB_URI inside the function — prevents build-time crash
  const MONGODB_URI = process.env.MONGODB_URI;
  if (!MONGODB_URI) {
    throw new Error("MONGODB_URI environment variable is not defined");
  }

  // Fast-path: return immediately if already connected
  if (cached.conn && mongoose.connection.readyState === 1) return cached.conn;

  // If readyState is disconnected/disconnecting, reset the cache
  if (cached.conn && mongoose.connection.readyState !== 1 && mongoose.connection.readyState !== 2) {
    cached.conn = null;
    cached.promise = null;
  }

  if (!cached.promise) {
    cached.promise = mongoose.connect(MONGODB_URI, {
      bufferCommands: false,
      maxPoolSize: 10,               // Serverless-appropriate pool size
      serverSelectionTimeoutMS: 5000, // Fail fast on unreachable cluster
      socketTimeoutMS: 45000,         // Close sockets after 45s inactivity
    }).catch((err) => {
      // Reset so the next call retries instead of returning a stale rejected promise
      cached.promise = null;
      cached.conn = null;
      throw err;
    });
  }

  cached.conn = await cached.promise;
  return cached.conn;
}

// Runtime connection event listeners (fire once on module load)
mongoose.connection.on("error", (err) => {
  console.error("[MongoDB] Connection error:", err);
  cached.conn = null;
  cached.promise = null;
});

mongoose.connection.on("disconnected", () => {
  console.warn("[MongoDB] Disconnected — next request will reconnect");
  cached.conn = null;
  cached.promise = null;
});
