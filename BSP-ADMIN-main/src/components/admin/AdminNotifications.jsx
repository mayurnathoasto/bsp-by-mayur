import { useEffect, useState } from "react";
import toast from "react-hot-toast";
import { Bell, Megaphone, Search } from "lucide-react";
import {
  createAdminNotification,
  fetchAdminNotifications,
} from "../../services/api";

export default function AdminNotifications() {
  const [items, setItems] = useState([]);
  const [meta, setMeta] = useState({ current_page: 1, last_page: 1 });
  const [loading, setLoading] = useState(false);
  const [search, setSearch] = useState("");
  const [submitting, setSubmitting] = useState(false);
  const [form, setForm] = useState({
    title: "",
    message: "",
    action_url: "",
  });

  const loadNotifications = async (page = 1, nextSearch = search) => {
    try {
      setLoading(true);
      const response = await fetchAdminNotifications({
        page,
        search: nextSearch || undefined,
      });

      setItems(response.data?.data || []);
      setMeta({
        current_page: response.data?.current_page || 1,
        last_page: response.data?.last_page || 1,
      });
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load notifications");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadNotifications();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      setSubmitting(true);
      const response = await createAdminNotification(form);
      toast.success(response.data?.message || "Notification sent");
      setForm({ title: "", message: "", action_url: "" });
      loadNotifications(1, search);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to send notification");
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <div className="space-y-6">
      <div className="grid gap-6 xl:grid-cols-[1.05fr_1.35fr]">
        <section className="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
          <div className="flex items-center gap-3">
            <div className="rounded-2xl bg-amber-50 p-3 text-amber-600">
              <Megaphone size={22} />
            </div>
            <div>
              <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Send Announcement</h1>
              <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Create major notifications for all approved sellers.
              </p>
            </div>
          </div>

          <form onSubmit={handleSubmit} className="mt-6 space-y-4">
            <div>
              <label className="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Title
              </label>
              <input
                type="text"
                value={form.title}
                onChange={(event) => setForm((current) => ({ ...current, title: event.target.value }))}
                className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900"
                placeholder="Announcement title"
                required
              />
            </div>

            <div>
              <label className="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Message
              </label>
              <textarea
                rows="6"
                value={form.message}
                onChange={(event) => setForm((current) => ({ ...current, message: event.target.value }))}
                className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900"
                placeholder="Write the notification content"
                required
              />
            </div>

            <div>
              <label className="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Action URL
              </label>
              <input
                type="text"
                value={form.action_url}
                onChange={(event) => setForm((current) => ({ ...current, action_url: event.target.value }))}
                className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900"
                placeholder="Optional website link"
              />
            </div>

            <button
              type="submit"
              disabled={submitting}
              className="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
            >
              {submitting ? "Sending..." : "Send Notification"}
            </button>
          </form>
        </section>

        <section className="rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
          <div className="border-b border-gray-100 px-5 py-4 dark:border-gray-700">
            <div className="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div className="flex items-center gap-3">
                <div className="rounded-2xl bg-blue-50 p-3 text-blue-600">
                  <Bell size={22} />
                </div>
                <div>
                  <h2 className="text-xl font-bold text-gray-900 dark:text-white">Notification History</h2>
                  <p className="text-sm text-gray-500 dark:text-gray-400">
                    Review sent announcements and read counts.
                  </p>
                </div>
              </div>

              <form
                onSubmit={(event) => {
                  event.preventDefault();
                  loadNotifications(1, search);
                }}
                className="relative"
              >
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                <input
                  type="text"
                  value={search}
                  onChange={(event) => setSearch(event.target.value)}
                  placeholder="Search notifications..."
                  className="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm dark:border-gray-700 dark:bg-gray-900 lg:w-72"
                />
              </form>
            </div>
          </div>

          {loading ? (
            <div className="p-8 text-sm text-gray-500 dark:text-gray-400">Loading notifications...</div>
          ) : items.length === 0 ? (
            <div className="p-8 text-sm text-gray-500 dark:text-gray-400">No notifications found.</div>
          ) : (
            <div className="divide-y divide-gray-100 dark:divide-gray-700">
              {items.map((item) => (
                <article key={item.id} className="px-5 py-4">
                  <div className="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div className="min-w-0">
                      <h3 className="text-base font-semibold text-gray-900 dark:text-white">{item.title}</h3>
                      <p className="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">{item.message}</p>
                      <div className="mt-3 flex flex-wrap gap-4 text-xs text-gray-400 dark:text-gray-500">
                        <span>Type: {item.type || "announcement"}</span>
                        <span>Recipients: {item.recipients_count || 0}</span>
                        <span>Read: {item.read_count || 0}</span>
                        <span>Created: {item.created_at ? new Date(item.created_at).toLocaleString() : "N/A"}</span>
                      </div>
                    </div>
                    {item.action_url ? (
                      <a
                        href={item.action_url}
                        target="_blank"
                        rel="noreferrer"
                        className="rounded-xl border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-700"
                      >
                        Open Link
                      </a>
                    ) : null}
                  </div>
                </article>
              ))}
            </div>
          )}

          {meta.last_page > 1 ? (
            <div className="flex items-center justify-between border-t border-gray-100 px-5 py-4 dark:border-gray-700">
              <button
                type="button"
                disabled={meta.current_page <= 1}
                onClick={() => loadNotifications(meta.current_page - 1, search)}
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
                onClick={() => loadNotifications(meta.current_page + 1, search)}
                className="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
              >
                Next
              </button>
            </div>
          ) : null}
        </section>
      </div>
    </div>
  );
}
