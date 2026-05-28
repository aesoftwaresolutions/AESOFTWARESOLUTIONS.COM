"use client";

import { useEffect, useState, useRef } from "react";
import { Plus, X, CheckCircle2, Circle, Kanban, List } from "lucide-react";
import { formatDate } from "@/lib/utils";
import { TASK_STATUS, TASK_PRIORITY } from "@/lib/constants";
import { Tooltip } from "@/components/ui/tooltip";

const PRIORITY_DESCRIPTIONS: Record<string, string> = {
  urgent: "Urgent — requires immediate action today",
  high: "High — should be addressed within 24 hours",
  medium: "Medium — complete within the week",
  low: "Low — handle when time permits",
};

const STATUS_DESCRIPTIONS: Record<string, string> = {
  todo: "Not yet started",
  in_progress: "Actively being worked on",
  done: "Completed successfully",
  cancelled: "No longer needed or relevant",
};

interface TaskItem {
  _id: string;
  title: string;
  description?: string;
  status: string;
  priority: string;
  dueDate?: string;
  contactId?: { firstName: string; lastName: string };
  assignedTo?: { name: string };
  createdAt?: string;
}

const PRIORITY_BADGE_COLORS: Record<string, string> = {
  urgent: "bg-red-100 text-red-700",
  high: "bg-orange-100 text-orange-700",
  medium: "bg-yellow-100 text-yellow-700",
  low: "bg-blue-100 text-blue-700",
};

const STATUS_BADGE_COLORS: Record<string, string> = {
  todo: "bg-gray-100 text-gray-600",
  in_progress: "bg-blue-100 text-blue-700",
  done: "bg-green-100 text-green-700",
  cancelled: "bg-red-100 text-red-600",
};

const BOARD_COLUMN_HEADERS: Record<string, string> = {
  todo: "bg-gray-100",
  in_progress: "bg-blue-100",
  done: "bg-green-100",
  cancelled: "bg-red-100",
};

export default function TasksPage() {
  const [tasks, setTasks] = useState<TaskItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [view, setView] = useState<"list" | "board">("list");
  const [searchTerm, setSearchTerm] = useState("");
  const [priorityFilter, setPriorityFilter] = useState("all");
  const [statusFilter, setStatusFilter] = useState("all");
  const [modalOpen, setModalOpen] = useState(false);
  const [editingTask, setEditingTask] = useState<TaskItem | null>(null);
  const [newTaskStatus, setNewTaskStatus] = useState<string | null>(null);
  const [form, setForm] = useState({
    title: "",
    description: "",
    priority: "medium" as const,
    status: "todo",
    dueDate: "",
  });
  const searchTimeoutRef = useRef<NodeJS.Timeout>();

  function loadTasks() {
    const params = new URLSearchParams();
    if (priorityFilter !== "all") params.append("priority", priorityFilter);
    if (statusFilter !== "all") params.append("status", statusFilter);

    setLoading(true);
    fetch(`/api/tasks?${params.toString()}`)
      .then((r) => r.json())
      .then((d) => setTasks(d.tasks || []))
      .finally(() => setLoading(false));
  }

  useEffect(() => {
    loadTasks();
  }, [priorityFilter, statusFilter]);

  function handleSearchChange(value: string) {
    setSearchTerm(value);
    if (searchTimeoutRef.current) clearTimeout(searchTimeoutRef.current);
    searchTimeoutRef.current = setTimeout(() => {
      // Search is handled client-side by filtering tasks
    }, 400);
  }

  function filteredTasks(): TaskItem[] {
    return tasks.filter((t) => {
      const matchesSearch = !searchTerm ||
        t.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
        (t.description || "").toLowerCase().includes(searchTerm.toLowerCase());
      return matchesSearch;
    });
  }

  function openCreateModal(status?: string) {
    setEditingTask(null);
    setNewTaskStatus(status || null);
    setForm({
      title: "",
      description: "",
      priority: "medium",
      status: status || "todo",
      dueDate: "",
    });
    setModalOpen(true);
  }

  function openEditModal(task: TaskItem) {
    setEditingTask(task);
    setNewTaskStatus(null);
    setForm({
      title: task.title,
      description: task.description || "",
      priority: task.priority as any,
      status: task.status,
      dueDate: task.dueDate ? task.dueDate.split("T")[0] : "",
    });
    setModalOpen(true);
  }

  function closeModal() {
    setModalOpen(false);
    setEditingTask(null);
    setNewTaskStatus(null);
  }

  async function handleSaveTask(e: React.FormEvent) {
    e.preventDefault();
    if (!form.title.trim()) return;

    const body = {
      title: form.title,
      description: form.description,
      priority: form.priority,
      status: form.status,
      ...(form.dueDate && { dueDate: form.dueDate }),
    };

    try {
      if (editingTask) {
        // Update existing task
        const res = await fetch("/api/tasks", {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ id: editingTask._id, ...body }),
        });
        if (res.ok) {
          const updated = await res.json();
          setTasks((prev) =>
            prev.map((t) => (t._id === editingTask._id ? updated : t))
          );
          closeModal();
          loadTasks();
        }
      } else {
        // Create new task
        const res = await fetch("/api/tasks", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(body),
        });
        if (res.ok) {
          closeModal();
          loadTasks();
        }
      }
    } catch (error) {
      console.error("Error saving task:", error);
    }
  }

  async function deleteTask(id: string) {
    if (!confirm("Are you sure you want to delete this task?")) return;
    try {
      const res = await fetch(`/api/tasks`, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
      });
      if (res.ok) {
        setTasks((prev) => prev.filter((t) => t._id !== id));
      }
    } catch (error) {
      console.error("Error deleting task:", error);
    }
  }

  async function updateTaskStatus(id: string, newStatus: string) {
    try {
      const res = await fetch("/api/tasks", {
        method: "PATCH",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, status: newStatus }),
      });
      if (res.ok) {
        setTasks((prev) =>
          prev.map((t) => (t._id === id ? { ...t, status: newStatus } : t))
        );
      }
    } catch (error) {
      console.error("Error updating task status:", error);
    }
  }

  const visibleTasks = filteredTasks();
  const sortedTasks = [...visibleTasks].sort((a, b) => {
    if (!a.dueDate && !b.dueDate) return 0;
    if (!a.dueDate) return 1;
    if (!b.dueDate) return -1;
    return new Date(a.dueDate).getTime() - new Date(b.dueDate).getTime();
  });

  function isOverdue(dueDate?: string, status?: string): boolean {
    return !!(
      dueDate &&
      new Date(dueDate) < new Date() &&
      status !== "done" &&
      status !== "cancelled"
    );
  }

  function formatTaskDate(dateStr?: string): string {
    if (!dateStr) return "";
    return formatDate(dateStr);
  }

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-display text-gray-900">Tasks</h1>
          <p className="text-sm text-gray-500 mt-1">{visibleTasks.length} total tasks</p>
        </div>
        <div className="flex items-center gap-3">
          <div className="flex gap-2 bg-white border border-gray-200 rounded-lg p-1">
            <Tooltip content="List view — tasks as a sortable table with priority and due dates" side="bottom">
              <button
                onClick={() => setView("list")}
                className={`p-2 rounded transition ${
                  view === "list"
                    ? "bg-gold text-navy"
                    : "text-gray-600 hover:text-gray-900"
                }`}
              >
                <List className="w-4 h-4" />
              </button>
            </Tooltip>
            <Tooltip content="Board view — tasks organized in columns by status (kanban)" side="bottom">
              <button
                onClick={() => setView("board")}
                className={`p-2 rounded transition ${
                  view === "board"
                    ? "bg-gold text-navy"
                    : "text-gray-600 hover:text-gray-900"
                }`}
              >
                <Kanban className="w-4 h-4" />
              </button>
            </Tooltip>
          </div>
          <Tooltip content="Create a new task and assign it a priority, due date, and status" side="left">
            <button
              onClick={() => openCreateModal()}
              className="inline-flex items-center gap-2 px-4 py-2.5 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition"
            >
              <Plus className="w-4 h-4" /> New Task
            </button>
          </Tooltip>
        </div>
      </div>

      {/* Filter Bar */}
      <div className="bg-white rounded-xl border border-gray-200 p-4 space-y-4">
        <div>
          <label className="block text-xs font-medium text-gray-600 mb-2">
            Search
          </label>
          <input
            type="text"
            placeholder="Search by title or description..."
            value={searchTerm}
            onChange={(e) => handleSearchChange(e.target.value)}
            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
          />
        </div>
        <div className="grid grid-cols-2 gap-4">
          <div>
            <label className="block text-xs font-medium text-gray-600 mb-2">
              Priority
            </label>
            <select
              value={priorityFilter}
              onChange={(e) => setPriorityFilter(e.target.value)}
              className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
            >
              <option value="all">All Priorities</option>
              {TASK_PRIORITY.map((p) => (
                <option key={p} value={p}>
                  {p.charAt(0).toUpperCase() + p.slice(1)}
                </option>
              ))}
            </select>
          </div>
          <div>
            <label className="block text-xs font-medium text-gray-600 mb-2">
              Status
            </label>
            <select
              value={statusFilter}
              onChange={(e) => setStatusFilter(e.target.value)}
              className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
            >
              <option value="all">All Statuses</option>
              {TASK_STATUS.map((s) => (
                <option key={s} value={s}>
                  {s.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase())}
                </option>
              ))}
            </select>
          </div>
        </div>
      </div>

      {/* Content */}
      {loading ? (
        <div className="space-y-3">
          {[...Array(4)].map((_, i) => (
            <div
              key={i}
              className="h-16 bg-white rounded-xl border border-gray-200 animate-pulse"
            />
          ))}
        </div>
      ) : visibleTasks.length === 0 ? (
        <div className="text-center py-12 bg-white rounded-xl border border-gray-200">
          <p className="text-gray-400">No tasks found</p>
        </div>
      ) : view === "list" ? (
        <ListViewTable
          tasks={sortedTasks}
          onEdit={openEditModal}
          onDelete={deleteTask}
          onStatusChange={updateTaskStatus}
          isOverdue={isOverdue}
          formatDate={formatTaskDate}
        />
      ) : (
        <BoardView
          tasks={visibleTasks}
          onEdit={openEditModal}
          onCreateInColumn={openCreateModal}
          onStatusChange={updateTaskStatus}
          isOverdue={isOverdue}
          formatDate={formatTaskDate}
        />
      )}

      {/* Modal */}
      {modalOpen && (
        <TaskModal
          task={editingTask}
          form={form}
          setForm={setForm}
          onSave={handleSaveTask}
          onClose={closeModal}
          isEditing={!!editingTask}
        />
      )}
    </div>
  );
}

interface ListViewTableProps {
  tasks: TaskItem[];
  onEdit: (task: TaskItem) => void;
  onDelete: (id: string) => void;
  onStatusChange: (id: string, status: string) => void;
  isOverdue: (dueDate?: string, status?: string) => boolean;
  formatDate: (dateStr?: string) => string;
}

function ListViewTable({
  tasks,
  onEdit,
  onDelete,
  onStatusChange,
  isOverdue,
  formatDate,
}: ListViewTableProps) {
  return (
    <div className="space-y-3">
      {tasks.map((task) => (
        <div
          key={task._id}
          className={`bg-white rounded-xl border border-gray-200 p-4 cursor-pointer hover:shadow-md transition ${
            task.status === "done" ? "opacity-60" : ""
          }`}
        >
          <div className="flex items-center gap-4">
            <div
              className="flex-1 min-w-0"
              onClick={() => onEdit(task)}
              role="button"
            >
              <div
                className={`font-medium ${
                  task.status === "done"
                    ? "line-through text-gray-400"
                    : "text-gray-900"
                }`}
              >
                {task.title}
              </div>
              {task.description && (
                <p className="text-xs text-gray-500 mt-1">{task.description}</p>
              )}
              <div className="flex items-center gap-3 mt-3 flex-wrap">
                <Tooltip content={PRIORITY_DESCRIPTIONS[task.priority] || task.priority} side="top">
                  <span
                    className={`inline-block px-2 py-1 rounded-full text-xs font-medium cursor-default ${
                      PRIORITY_BADGE_COLORS[task.priority] || "bg-gray-100"
                    }`}
                  >
                    {task.priority.charAt(0).toUpperCase() + task.priority.slice(1)}
                  </span>
                </Tooltip>
                <Tooltip content={STATUS_DESCRIPTIONS[task.status] || task.status} side="top">
                  <span
                    className={`inline-block px-2 py-1 rounded-full text-xs font-medium cursor-default ${
                      STATUS_BADGE_COLORS[task.status] || "bg-gray-100"
                    }`}
                  >
                    {task.status.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase())}
                  </span>
                </Tooltip>
                {task.dueDate && (
                  <Tooltip
                    content={
                      isOverdue(task.dueDate, task.status)
                        ? "This task is past its due date and needs immediate attention"
                        : `Due ${formatDate(task.dueDate)}`
                    }
                    side="top"
                  >
                    <span
                      className={`text-xs cursor-default ${
                        isOverdue(task.dueDate, task.status)
                          ? "text-red-500 font-medium"
                          : "text-gray-500"
                      }`}
                    >
                      {isOverdue(task.dueDate, task.status) ? "Overdue: " : "Due: "}
                      {formatDate(task.dueDate)}
                    </span>
                  </Tooltip>
                )}
                {task.contactId && (
                  <span className="text-xs text-gray-500">
                    {task.contactId.firstName} {task.contactId.lastName}
                  </span>
                )}
                {task.assignedTo && (
                  <span className="text-xs text-gray-500">
                    Assigned to {task.assignedTo.name}
                  </span>
                )}
              </div>
            </div>
            <div className="flex items-center gap-2 shrink-0">
              <select
                value={task.status}
                onChange={(e) => {
                  e.stopPropagation();
                  onStatusChange(task._id, e.target.value);
                }}
                className="px-2 py-1 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold/50"
              >
                {TASK_STATUS.map((s) => (
                  <option key={s} value={s}>
                    {s.replace(/_/g, " ")}
                  </option>
                ))}
              </select>
              <Tooltip content="Delete this task permanently" side="left">
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    onDelete(task._id);
                  }}
                  className="p-1 text-gray-400 hover:text-red-500 transition"
                >
                  <X className="w-4 h-4" />
                </button>
              </Tooltip>
            </div>
          </div>
        </div>
      ))}
    </div>
  );
}

interface BoardViewProps {
  tasks: TaskItem[];
  onEdit: (task: TaskItem) => void;
  onCreateInColumn: (status: string) => void;
  onStatusChange: (id: string, status: string) => void;
  isOverdue: (dueDate?: string, status?: string) => boolean;
  formatDate: (dateStr?: string) => string;
}

function BoardView({
  tasks,
  onEdit,
  onCreateInColumn,
  onStatusChange,
  isOverdue,
  formatDate,
}: BoardViewProps) {
  const columns = TASK_STATUS;

  return (
    <div className="grid grid-cols-4 gap-4">
      {columns.map((status) => {
        const columnTasks = tasks.filter((t) => t.status === status);
        return (
          <div
            key={status}
            className="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden flex flex-col"
          >
            <div
              className={`px-4 py-3 font-semibold text-sm text-gray-900 border-b border-gray-200 flex items-center justify-between ${
                BOARD_COLUMN_HEADERS[status]
              }`}
            >
              <span>
                {status.replace(/_/g, " ").replace(/\b\w/g, (l) => l.toUpperCase())}{" "}
                ({columnTasks.length})
              </span>
              <button
                onClick={() => onCreateInColumn(status)}
                className="text-gray-600 hover:text-gray-900 transition"
                title="Add task to this column"
              >
                <Plus className="w-4 h-4" />
              </button>
            </div>
            <div className="flex-1 overflow-y-auto p-3 space-y-3">
              {columnTasks.map((task) => (
                <div
                  key={task._id}
                  onClick={() => onEdit(task)}
                  className={`bg-white rounded-lg border border-gray-200 p-3 cursor-pointer hover:shadow-md transition space-y-2 ${
                    task.status === "done" ? "opacity-60" : ""
                  }`}
                >
                  <div
                    className={`font-medium text-sm ${
                      task.status === "done"
                        ? "line-through text-gray-400"
                        : "text-gray-900"
                    }`}
                  >
                    {task.title}
                  </div>
                  <div className="flex items-center gap-2 flex-wrap">
                    <span
                      className={`inline-block px-2 py-0.5 rounded text-xs font-medium ${
                        PRIORITY_BADGE_COLORS[task.priority] || "bg-gray-100"
                      }`}
                    >
                      {task.priority.charAt(0).toUpperCase() +
                        task.priority.slice(1)}
                    </span>
                  </div>
                  {task.dueDate && (
                    <div
                      className={`text-xs ${
                        isOverdue(task.dueDate, task.status)
                          ? "text-red-500 font-medium"
                          : "text-gray-500"
                      }`}
                    >
                      {formatDate(task.dueDate)}
                    </div>
                  )}
                  {task.contactId && (
                    <div className="text-xs text-gray-500">
                      {task.contactId.firstName} {task.contactId.lastName}
                    </div>
                  )}
                </div>
              ))}
              {columnTasks.length === 0 && (
                <div className="text-center py-8 text-gray-400 text-xs">
                  No tasks
                </div>
              )}
            </div>
          </div>
        );
      })}
    </div>
  );
}

interface TaskModalProps {
  task: TaskItem | null;
  form: {
    title: string;
    description: string;
    priority: string;
    status: string;
    dueDate: string;
  };
  setForm: (form: any) => void;
  onSave: (e: React.FormEvent) => void;
  onClose: () => void;
  isEditing: boolean;
}

function TaskModal({
  task,
  form,
  setForm,
  onSave,
  onClose,
  isEditing,
}: TaskModalProps) {
  return (
    <div
      className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
      onClick={onClose}
    >
      <div
        className="bg-white rounded-xl w-full max-w-md p-6"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="flex items-center justify-between mb-4">
          <h2 className="text-lg font-semibold text-gray-900">
            {isEditing ? "Edit Task" : "New Task"}
          </h2>
          <button
            onClick={onClose}
            className="text-gray-400 hover:text-gray-600 transition"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        <form onSubmit={onSave} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-900 mb-1">
              Title *
            </label>
            <input
              type="text"
              value={form.title}
              onChange={(e) => setForm({ ...form, title: e.target.value })}
              required
              className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
              placeholder="Task title"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-900 mb-1">
              Description
            </label>
            <textarea
              value={form.description}
              onChange={(e) => setForm({ ...form, description: e.target.value })}
              rows={3}
              className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
              placeholder="Task description"
            />
          </div>

          <div className="grid grid-cols-2 gap-3">
            <div>
              <label className="block text-sm font-medium text-gray-900 mb-1">
                Priority
              </label>
              <select
                value={form.priority}
                onChange={(e) => setForm({ ...form, priority: e.target.value })}
                className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
              >
                {TASK_PRIORITY.map((p) => (
                  <option key={p} value={p}>
                    {p.charAt(0).toUpperCase() + p.slice(1)}
                  </option>
                ))}
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-900 mb-1">
                Status
              </label>
              <select
                value={form.status}
                onChange={(e) => setForm({ ...form, status: e.target.value })}
                className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
              >
                {TASK_STATUS.map((s) => (
                  <option key={s} value={s}>
                    {s.replace(/_/g, " ")}
                  </option>
                ))}
              </select>
            </div>
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-900 mb-1">
              Due Date
            </label>
            <input
              type="date"
              value={form.dueDate}
              onChange={(e) => setForm({ ...form, dueDate: e.target.value })}
              className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gold/50"
            />
          </div>

          <div className="flex justify-end gap-3 pt-4">
            <button
              type="button"
              onClick={onClose}
              className="px-4 py-2 border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition"
            >
              Cancel
            </button>
            <button
              type="submit"
              className="px-4 py-2 bg-gold hover:bg-gold-light text-navy font-semibold rounded-lg text-sm transition"
            >
              {isEditing ? "Update Task" : "Create Task"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
