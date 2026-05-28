"use client";

import { useEffect, useState, useRef, useCallback } from "react";
import {
  MessageSquare, Send, Search, Phone, Mail, Globe,
  Instagram, Twitter, User, ChevronRight, RefreshCw,
  CheckCheck, Clock, AlertCircle,
} from "lucide-react";
import { MESSAGE_CHANNELS } from "@/lib/constants";
import { Tooltip } from "@/components/ui/tooltip";

const MESSAGE_STATUS_DESCRIPTIONS: Record<string, string> = {
  queued: "Message is queued — waiting to be sent",
  sent: "Message sent to the provider (Twilio/SendGrid)",
  delivered: "Message confirmed delivered to recipient",
  failed: "Delivery failed — check provider logs",
  read: "Recipient has read the message",
};

interface Contact {
  _id: string;
  firstName: string;
  lastName: string;
  email?: string;
  phone?: string;
  status?: string;
}

interface Thread {
  _id: string;
  lastMessage: {
    _id: string;
    body: string;
    direction: string;
    channel: string;
    status: string;
    createdAt: string;
  };
  contact: Contact;
  totalMessages: number;
  unreadCount: number;
}

interface Message {
  _id: string;
  threadId: string;
  contactId: string;
  direction: "inbound" | "outbound";
  channel: string;
  subject?: string;
  body: string;
  sentBy?: { name: string };
  status: string;
  createdAt: string;
}

const CHANNEL_ICONS: Record<string, React.ElementType> = {
  email: Mail,
  sms: Phone,
  in_app: Globe,
  instagram_dm: Instagram,
  facebook_dm: Globe,
  twitter_dm: Twitter,
};

const CHANNEL_LABELS: Record<string, string> = {
  email: "Email",
  sms: "SMS",
  in_app: "In-App",
  instagram_dm: "Instagram DM",
  facebook_dm: "Facebook DM",
  twitter_dm: "Twitter DM",
};

const STATUS_ICON: Record<string, React.ElementType> = {
  queued: Clock,
  sent: CheckCheck,
  delivered: CheckCheck,
  failed: AlertCircle,
  read: CheckCheck,
};

const STATUS_COLOR: Record<string, string> = {
  queued: "text-gray-400",
  sent: "text-gray-500",
  delivered: "text-blue-500",
  failed: "text-red-500",
  read: "text-green-500",
};

function formatTime(d: string) {
  const date = new Date(d);
  const now = new Date();
  const diff = now.getTime() - date.getTime();
  if (diff < 60000) return "Just now";
  if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
  if (diff < 86400000) return date.toLocaleTimeString(undefined, { hour: "numeric", minute: "2-digit" });
  return date.toLocaleDateString(undefined, { month: "short", day: "numeric" });
}

function initials(c: Contact) {
  return `${c.firstName?.[0] || ""}${c.lastName?.[0] || ""}`.toUpperCase();
}

export default function MessagingPage() {
  const [threads, setThreads] = useState<Thread[]>([]);
  const [threadsLoading, setThreadsLoading] = useState(true);
  const [selectedThread, setSelectedThread] = useState<Thread | null>(null);
  const [messages, setMessages] = useState<Message[]>([]);
  const [messagesLoading, setMessagesLoading] = useState(false);
  const [search, setSearch] = useState("");
  const [compose, setCompose] = useState({ channel: "email", subject: "", body: "" });
  const [sending, setSending] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);
  const pollRef = useRef<NodeJS.Timeout>();

  const loadThreads = useCallback(() => {
    setThreadsLoading(true);
    fetch("/api/messages?mode=threads")
      .then((r) => r.json())
      .then((d) => setThreads(d.threads || []))
      .finally(() => setThreadsLoading(false));
  }, []);

  const loadMessages = useCallback((contactId: string) => {
    setMessagesLoading(true);
    fetch(`/api/messages?mode=messages&contactId=${contactId}`)
      .then((r) => r.json())
      .then((d) => setMessages(d.messages || []))
      .finally(() => setMessagesLoading(false));
  }, []);

  useEffect(() => {
    loadThreads();
  }, [loadThreads]);

  useEffect(() => {
    if (selectedThread) {
      loadMessages(selectedThread._id);
    }
  }, [selectedThread, loadMessages]);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages]);

  function selectThread(thread: Thread) {
    setSelectedThread(thread);
    setCompose({ channel: thread.lastMessage?.channel || "email", subject: "", body: "" });
  }

  async function handleSend(e: React.FormEvent) {
    e.preventDefault();
    if (!selectedThread || !compose.body.trim()) return;
    setSending(true);

    try {
      const res = await fetch("/api/messages", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          contactId: selectedThread._id,
          channel: compose.channel,
          subject: compose.subject || undefined,
          body: compose.body,
        }),
      });
      if (res.ok) {
        const msg = await res.json();
        setMessages((prev) => [...prev, msg]);
        setCompose((c) => ({ ...c, body: "", subject: "" }));
        loadThreads();
      }
    } finally {
      setSending(false);
    }
  }

  const filteredThreads = threads.filter((t) => {
    if (!search) return true;
    const name = `${t.contact?.firstName || ""} ${t.contact?.lastName || ""}`.toLowerCase();
    const email = t.contact?.email?.toLowerCase() || "";
    const phone = t.contact?.phone?.toLowerCase() || "";
    const q = search.toLowerCase();
    return name.includes(q) || email.includes(q) || phone.includes(q);
  });

  const totalUnread = threads.reduce((a, t) => a + (t.unreadCount || 0), 0);

  return (
    <div className="flex h-[calc(100vh-7rem)] gap-0 bg-white rounded-xl border border-gray-200 overflow-hidden">
      {/* Left Panel — Thread List */}
      <div className="w-80 flex-shrink-0 border-r border-gray-200 flex flex-col">
        {/* Header */}
        <div className="p-4 border-b border-gray-200">
          <div className="flex items-center justify-between mb-3">
            <div className="flex items-center gap-2">
              <h2 className="font-semibold text-gray-900">Inbox</h2>
              {totalUnread > 0 && (
                <span className="bg-gold text-navy text-xs font-bold px-2 py-0.5 rounded-full">
                  {totalUnread}
                </span>
              )}
            </div>
            <Tooltip content="Refresh inbox to load new messages" side="bottom">
              <button
                onClick={loadThreads}
                className="p-1.5 text-gray-400 hover:text-gray-600 transition"
              >
                <RefreshCw className="w-4 h-4" />
              </button>
            </Tooltip>
          </div>
          <div className="relative">
            <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              type="text"
              placeholder="Search contacts..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
            />
          </div>
        </div>

        {/* Thread List */}
        <div className="flex-1 overflow-y-auto">
          {threadsLoading ? (
            <div className="space-y-px p-2">
              {[...Array(5)].map((_, i) => (
                <div key={i} className="h-16 bg-gray-100 rounded-lg animate-pulse" />
              ))}
            </div>
          ) : filteredThreads.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-48 text-center px-4">
              <MessageSquare className="w-10 h-10 text-gray-300 mb-3" />
              <p className="text-sm text-gray-500">No conversations yet</p>
              <p className="text-xs text-gray-400 mt-1">Messages from contacts will appear here</p>
            </div>
          ) : (
            filteredThreads.map((t) => {
              const isSelected = selectedThread?._id === t._id;
              const ChannelIcon = CHANNEL_ICONS[t.lastMessage?.channel] || MessageSquare;
              return (
                <button
                  key={t._id}
                  onClick={() => selectThread(t)}
                  className={`w-full text-left px-4 py-3 flex items-start gap-3 hover:bg-gray-50 transition border-b border-gray-100 ${
                    isSelected ? "bg-gold/10 border-l-2 border-l-gold" : ""
                  }`}
                >
                  {/* Avatar */}
                  <div className="w-9 h-9 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold shrink-0">
                    {t.contact ? initials(t.contact) : <User className="w-4 h-4" />}
                  </div>

                  <div className="flex-1 min-w-0">
                    <div className="flex items-center justify-between mb-0.5">
                      <span className={`text-sm font-medium truncate ${isSelected ? "text-navy" : "text-gray-900"}`}>
                        {t.contact
                          ? `${t.contact.firstName} ${t.contact.lastName}`
                          : "Unknown Contact"}
                      </span>
                      <span className="text-xs text-gray-400 shrink-0 ml-2">
                        {t.lastMessage ? formatTime(t.lastMessage.createdAt) : ""}
                      </span>
                    </div>
                    <div className="flex items-center gap-1.5">
                      <Tooltip
                        content={CHANNEL_LABELS[t.lastMessage?.channel] || t.lastMessage?.channel || "Message"}
                        side="right"
                      >
                        <ChannelIcon className="w-3 h-3 text-gray-400 shrink-0 cursor-default" />
                      </Tooltip>
                      <p className={`text-xs truncate ${t.unreadCount > 0 ? "font-semibold text-gray-800" : "text-gray-500"}`}>
                        {t.lastMessage?.direction === "outbound" ? "You: " : ""}
                        {t.lastMessage?.body || "No messages"}
                      </p>
                    </div>
                  </div>

                  {t.unreadCount > 0 && (
                    <span className="bg-gold text-navy text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shrink-0">
                      {t.unreadCount}
                    </span>
                  )}
                </button>
              );
            })
          )}
        </div>
      </div>

      {/* Right Panel — Message Thread */}
      <div className="flex-1 flex flex-col min-w-0">
        {!selectedThread ? (
          <div className="flex flex-col items-center justify-center h-full text-center px-8">
            <MessageSquare className="w-16 h-16 text-gray-200 mb-4" />
            <h3 className="text-lg font-semibold text-gray-700">Select a conversation</h3>
            <p className="text-sm text-gray-400 mt-2 max-w-xs">
              Choose a contact from the left panel to view and send messages. SMS, Email, and social DMs are unified here.
            </p>
          </div>
        ) : (
          <>
            {/* Thread Header */}
            <div className="px-5 py-3.5 border-b border-gray-200 flex items-center gap-3 bg-white">
              <div className="w-8 h-8 rounded-full bg-navy text-white flex items-center justify-center text-xs font-bold shrink-0">
                {selectedThread.contact ? initials(selectedThread.contact) : <User className="w-4 h-4" />}
              </div>
              <div>
                <h3 className="font-semibold text-gray-900 text-sm">
                  {selectedThread.contact
                    ? `${selectedThread.contact.firstName} ${selectedThread.contact.lastName}`
                    : "Unknown Contact"}
                </h3>
                <div className="flex items-center gap-3 text-xs text-gray-400">
                  {selectedThread.contact?.email && <span>{selectedThread.contact.email}</span>}
                  {selectedThread.contact?.phone && <span>{selectedThread.contact.phone}</span>}
                </div>
              </div>
              <div className="ml-auto">
                <a
                  href={`/contacts/${selectedThread._id}`}
                  className="flex items-center gap-1 text-xs text-gold hover:text-gold-light transition"
                >
                  View Contact <ChevronRight className="w-3.5 h-3.5" />
                </a>
              </div>
            </div>

            {/* Messages */}
            <div className="flex-1 overflow-y-auto p-4 space-y-3 bg-gray-50">
              {messagesLoading ? (
                <div className="flex items-center justify-center h-20">
                  <RefreshCw className="w-5 h-5 text-gray-400 animate-spin" />
                </div>
              ) : messages.length === 0 ? (
                <div className="text-center py-12">
                  <p className="text-sm text-gray-400">No messages yet. Start the conversation below.</p>
                </div>
              ) : (
                messages.map((msg) => {
                  const isOut = msg.direction === "outbound";
                  const Icon = CHANNEL_ICONS[msg.channel] || MessageSquare;
                  const StatusIcon = STATUS_ICON[msg.status] || Clock;
                  return (
                    <div key={msg._id} className={`flex ${isOut ? "justify-end" : "justify-start"}`}>
                      <div className={`max-w-[70%] ${isOut ? "items-end" : "items-start"} flex flex-col gap-1`}>
                        {msg.subject && (
                          <div className={`text-xs font-semibold px-1 ${isOut ? "text-right text-gray-500" : "text-gray-500"}`}>
                            Re: {msg.subject}
                          </div>
                        )}
                        <div
                          className={`rounded-2xl px-4 py-2.5 text-sm whitespace-pre-wrap break-words ${
                            isOut
                              ? "bg-navy text-white rounded-br-sm"
                              : "bg-white text-gray-900 border border-gray-200 rounded-bl-sm"
                          }`}
                        >
                          {msg.body}
                        </div>
                        <div className={`flex items-center gap-1.5 text-xs text-gray-400 px-1 ${isOut ? "flex-row-reverse" : ""}`}>
                          <Icon className="w-3 h-3" />
                          <span>{formatTime(msg.createdAt)}</span>
                          {isOut && msg.sentBy && <span>· {msg.sentBy.name}</span>}
                          {isOut && (
                            <Tooltip content={MESSAGE_STATUS_DESCRIPTIONS[msg.status] || msg.status} side="top">
                              <StatusIcon className={`w-3 h-3 cursor-default ${STATUS_COLOR[msg.status] || "text-gray-400"}`} />
                            </Tooltip>
                          )}
                        </div>
                      </div>
                    </div>
                  );
                })
              )}
              <div ref={messagesEndRef} />
            </div>

            {/* Compose Bar */}
            <div className="border-t border-gray-200 bg-white p-4">
              <form onSubmit={handleSend} className="space-y-2">
                {/* Channel + Subject row */}
                <div className="flex items-center gap-3">
                  <Tooltip content="Choose the channel to send this message through" side="top">
                    <select
                      value={compose.channel}
                      onChange={(e) => setCompose({ ...compose, channel: e.target.value })}
                      className="px-2 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-gold/50 bg-white"
                    >
                      {MESSAGE_CHANNELS.map((ch) => (
                        <option key={ch} value={ch}>{CHANNEL_LABELS[ch] || ch}</option>
                      ))}
                    </select>
                  </Tooltip>
                  {compose.channel === "email" && (
                    <input
                      type="text"
                      placeholder="Subject"
                      value={compose.subject}
                      onChange={(e) => setCompose({ ...compose, subject: e.target.value })}
                      className="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
                    />
                  )}
                </div>

                {/* Body + Send */}
                <div className="flex items-end gap-2">
                  <textarea
                    value={compose.body}
                    onChange={(e) => setCompose({ ...compose, body: e.target.value })}
                    onKeyDown={(e) => {
                      if (e.key === "Enter" && !e.shiftKey) {
                        e.preventDefault();
                        handleSend(e as any);
                      }
                    }}
                    placeholder={`Type a ${CHANNEL_LABELS[compose.channel] || "message"}... (Enter to send, Shift+Enter for new line)`}
                    rows={2}
                    className="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50 resize-none"
                  />
                  <button
                    type="submit"
                    disabled={sending || !compose.body.trim()}
                    className="flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition disabled:opacity-40 shrink-0"
                  >
                    <Send className="w-4 h-4" />
                    {sending ? "Sending..." : "Send"}
                  </button>
                </div>
              </form>
            </div>
          </>
        )}
      </div>
    </div>
  );
}
