import { useEffect, useState } from "react";
import toast from "react-hot-toast";
import { Search } from "lucide-react";
import useAuthStore from "../../store/authStore";
import {
  fetchAdminSupportCases,
  replyAdminSupportCase,
} from "../../services/api";

const STATUS_OPTIONS = ["open", "under_review", "resolved", "closed"];

export default function AdminSupportTickets() {
  const hasPermission = useAuthStore((state) => state.hasPermission);
  const canReply = hasPermission("support.reply");
  const [items, setItems] = useState([]);
  const [meta, setMeta] = useState({ current_page: 1, last_page: 1 });
  const [search, setSearch] = useState("");
  const [type, setType] = useState("");
  const [status, setStatus] = useState("");
  const [loading, setLoading] = useState(false);
  const [selected, setSelected] = useState(null);
  const [replyForm, setReplyForm] = useState({ status: "under_review", admin_reply: "" });
  const [saving, setSaving] = useState(false);

  const loadCases = async (
    page = 1,
    nextType = type,
    nextStatus = status,
    nextSearch = search,
  ) => {
    try {
      setLoading(true);
      const response = await fetchAdminSupportCases({
        page,
        type: nextType || undefined,
        status: nextStatus || undefined,
        search: nextSearch || undefined,
      });

      const rows = response.data?.data || [];
      setItems(rows);
      setMeta({
        current_page: response.data?.current_page || 1,
        last_page: response.data?.last_page || 1,
      });

      if (rows.length === 0) {
        setSelected(null);
        return;
      }

      const nextSelected = rows.find((item) => item.id === selected?.id) || rows[0];
      setSelected(nextSelected);
      setReplyForm({
        status: nextSelected.status || "under_review",
        admin_reply: nextSelected.admin_reply || "",
      });
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load support cases");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadCases();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const selectCase = (item) => {
    setSelected(item);
    setReplyForm({
      status: item.status || "under_review",
      admin_reply: item.admin_reply || "",
    });
  };

  const handleReply = async (event) => {
    event.preventDefault();

    if (!selected) {
      return;
    }

    try {
      setSaving(true);
      const response = await replyAdminSupportCase(selected.id, replyForm);
      toast.success(response.data?.message || "Support case updated");
      await loadCases(meta.current_page, type, status, search);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to update support case");
    } finally {
      setSaving(false);
    }
  };

  return (
    <div className="space-y-6">
      <div className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div className="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Support Tickets & Reports</h1>
            <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
              Review user-raised tickets, handle reports, and send admin replies.
            </p>
          </div>

          <form
            onSubmit={(event) => {
              event.preventDefault();
              loadCases(1, type, status, search);
            }}
            className="flex flex-col gap-3 lg:flex-row"
          >
            <div className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
              <input
                type="text"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                placeholder="Search subject, user, order..."
                className="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm dark:border-gray-700 dark:bg-gray-900 lg:w-80"
              />
            </div>

            <select
              value={type}
              onChange={(event) => setType(event.target.value)}
              className="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900"
            >
              <option value="">All Types</option>
              <option value="ticket">Support Tickets</option>
              <option value="report">Reports</option>
            </select>

            <select
              value={status}
              onChange={(event) => setStatus(event.target.value)}
              className="rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm dark:border-gray-700 dark:bg-gray-900"
            >
              <option value="">All Statuses</option>
              {STATUS_OPTIONS.map((option) => (
                <option key={option} value={option}>
                  {option.replace("_", " ")}
                </option>
              ))}
            </select>

            <button
              type="submit"
              className="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
              Apply Filters
            </button>
          </form>
        </div>
      </div>

      <div className="grid gap-6 xl:grid-cols-[1fr_1.15fr]">
        <section className="rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
          <div className="border-b border-gray-100 px-5 py-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
            {loading ? "Loading support cases..." : `${items.length} cases on this page`}
          </div>

          {loading ? (
            <div className="p-8 text-sm text-gray-500 dark:text-gray-400">Loading support cases...</div>
          ) : items.length === 0 ? (
            <div className="p-8 text-sm text-gray-500 dark:text-gray-400">No support cases found.</div>
          ) : (
            <div className="divide-y divide-gray-100 dark:divide-gray-700">
              {items.map((item) => (
                <button
                  key={item.id}
                  type="button"
                  onClick={() => selectCase(item)}
                  className={`w-full px-5 py-4 text-left transition ${
                    selected?.id === item.id
                      ? "bg-blue-50/70 dark:bg-blue-900/10"
                      : "hover:bg-gray-50 dark:hover:bg-gray-700/50"
                  }`}
                >
                  <div className="flex items-start justify-between gap-3">
                    <div className="min-w-0">
                      <div className="flex flex-wrap items-center gap-2">
                        <h2 className="text-sm font-semibold text-gray-900 dark:text-white">{item.subject}</h2>
                        <span className="rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold uppercase text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                          {item.type}
                        </span>
                      </div>
                      <div className="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Raised by {item.user?.name || "Unknown user"}
                      </div>
                      <div className="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        {item.created_at ? new Date(item.created_at).toLocaleString() : "N/A"}
                      </div>
                    </div>
                    <span className="rounded-full bg-orange-50 px-2 py-1 text-[11px] font-semibold uppercase tracking-wide text-orange-600 dark:bg-orange-900/20 dark:text-orange-300">
                      {String(item.status || "").replace("_", " ")}
                    </span>
                  </div>
                </button>
              ))}
            </div>
          )}

          {meta.last_page > 1 ? (
            <div className="flex items-center justify-between border-t border-gray-100 px-5 py-4 dark:border-gray-700">
              <button
                type="button"
                disabled={meta.current_page <= 1}
                onClick={() => loadCases(meta.current_page - 1, type, status, search)}
                className="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
              >
                Previous
              </button>
              <span className="text-sm text-gray-500 dark:text-gray-400">
                Page {meta.current_page} of {meta.last_page}
              </span>
              <button
                type="button"
                disabled={meta.current_page >= meta.last_page}
                onClick={() => loadCases(meta.current_page + 1, type, status, search)}
                className="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
              >
                Next
              </button>
            </div>
          ) : null}
        </section>

        <section className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
          {selected ? (
            <div className="space-y-5">
              <div>
                <div className="flex flex-wrap items-center gap-2">
                  <h2 className="text-xl font-bold text-gray-900 dark:text-white">{selected.subject}</h2>
                  <span className="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold uppercase text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                    {selected.type}
                  </span>
                </div>
                <p className="mt-3 text-sm leading-7 text-gray-600 dark:text-gray-300">{selected.message}</p>
              </div>

              <div className="grid gap-3 text-sm text-gray-500 dark:text-gray-400 sm:grid-cols-2">
                <div><span className="font-semibold text-gray-700 dark:text-gray-200">Raised By:</span> {selected.user?.name || "N/A"}</div>
                <div><span className="font-semibold text-gray-700 dark:text-gray-200">Email:</span> {selected.user?.email || "N/A"}</div>
                <div><span className="font-semibold text-gray-700 dark:text-gray-200">Phone:</span> {selected.user?.phone || "N/A"}</div>
                <div><span className="font-semibold text-gray-700 dark:text-gray-200">Order Ref:</span> {selected.order_reference || "N/A"}</div>
                <div><span className="font-semibold text-gray-700 dark:text-gray-200">Reported User:</span> {selected.target_user?.name || "N/A"}</div>
                <div><span className="font-semibold text-gray-700 dark:text-gray-200">Current Status:</span> {String(selected.status || "").replace("_", " ")}</div>
              </div>

              {selected.context_url ? (
                <a
                  href={selected.context_url}
                  target="_blank"
                  rel="noreferrer"
                  className="inline-flex rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                >
                  Open Context URL
                </a>
              ) : null}

              <form onSubmit={handleReply} className="space-y-4 rounded-2xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                <div>
                  <label className="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Update Status
                  </label>
                  <select
                    value={replyForm.status}
                    onChange={(event) => setReplyForm((current) => ({ ...current, status: event.target.value }))}
                    disabled={!canReply}
                    className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900"
                  >
                    {STATUS_OPTIONS.map((option) => (
                      <option key={option} value={option}>
                        {option.replace("_", " ")}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Admin Reply
                  </label>
                  <textarea
                    rows="7"
                    value={replyForm.admin_reply}
                    onChange={(event) => setReplyForm((current) => ({ ...current, admin_reply: event.target.value }))}
                    disabled={!canReply}
                    className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900"
                    placeholder="Write the resolution or next steps"
                    required
                  />
                </div>

                {canReply ? (
                  <button
                    type="submit"
                    disabled={saving}
                    className="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                  >
                    {saving ? "Saving..." : "Save Reply"}
                  </button>
                ) : (
                  <div className="text-sm text-gray-500 dark:text-gray-400">
                    You can view support cases, but you do not have permission to reply.
                  </div>
                )}
              </form>
            </div>
          ) : (
            <div className="p-8 text-sm text-gray-500 dark:text-gray-400">Select a support case to view details.</div>
          )}
        </section>
      </div>
    </div>
  );
}
