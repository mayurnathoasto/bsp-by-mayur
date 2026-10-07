import React, { useEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import { fetchAdminRequirements, fetchAdminRequirement } from "../../services/api";

const financingOptions = [
  { label: "All", value: "" },
  { label: "Yes", value: "yes" },
  { label: "No", value: "no" },
];

const gstOptions = [
  { label: "All", value: "" },
  { label: "Yes", value: "yes" },
  { label: "No", value: "no" },
];

export default function AdminRequirements() {
  const [filters, setFilters] = useState({
    q: "",
    financing: "",
    gst_status: "",
  });

  const [loading, setLoading] = useState(false);
  const [items, setItems] = useState([]);
  const [pageMeta, setPageMeta] = useState({ current_page: 1, last_page: 1, total: 0 });

  const [detailLoading, setDetailLoading] = useState(false);
  const [selectedId, setSelectedId] = useState(null);
  const [detail, setDetail] = useState(null);

  const canPrev = useMemo(() => pageMeta.current_page > 1, [pageMeta]);
  const canNext = useMemo(() => pageMeta.current_page < pageMeta.last_page, [pageMeta]);

  const loadRows = async (page = 1) => {
    setLoading(true);
    try {
      const res = await fetchAdminRequirements({ ...filters, page });
      const payload = res.data || {};
      setItems(payload.data || []);
      setPageMeta({
        current_page: payload.current_page || 1,
        last_page: payload.last_page || 1,
        total: payload.total || 0,
      });
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to load requirements");
    } finally {
      setLoading(false);
    }
  };

  const openDetail = async (id) => {
    setSelectedId(id);
    setDetail(null);
    setDetailLoading(true);
    try {
      const res = await fetchAdminRequirement(id);
      setDetail(res.data || null);
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to load requirement details");
    } finally {
      setDetailLoading(false);
    }
  };

  useEffect(() => {
    loadRows(1);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const applyFilters = () => {
    loadRows(1);
  };

  const resetFilters = () => {
    const next = { q: "", financing: "", gst_status: "" };
    setFilters(next);
    setTimeout(() => loadRows(1), 0);
  };

  return (
    <div className="p-6">
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-semibold text-gray-900 dark:text-white">Bulk Requirements</h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">
            Review requirement submissions from buyers. Total: {pageMeta.total}
          </p>
        </div>
      </div>

      <div className="mt-6 rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-3">
          <input
            type="text"
            placeholder="Search product / user"
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
            value={filters.q}
            onChange={(e) => setFilters((s) => ({ ...s, q: e.target.value }))}
          />

          <select
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            value={filters.financing}
            onChange={(e) => setFilters((s) => ({ ...s, financing: e.target.value }))}
          >
            {financingOptions.map((o) => (
              <option key={o.value} value={o.value}>
                {o.label}
              </option>
            ))}
          </select>

          <select
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            value={filters.gst_status}
            onChange={(e) => setFilters((s) => ({ ...s, gst_status: e.target.value }))}
          >
            {gstOptions.map((o) => (
              <option key={o.value} value={o.value}>
                {o.label}
              </option>
            ))}
          </select>
        </div>

        <div className="mt-4 flex gap-2">
          <button
            className="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700"
            onClick={applyFilters}
            disabled={loading}
          >
            Apply
          </button>
          <button
            className="rounded-md border border-gray-200 bg-white px-4 py-2 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
            onClick={resetFilters}
            disabled={loading}
          >
            Reset
          </button>
        </div>
      </div>

      <div className="mt-6 overflow-hidden rounded-xl border border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
        <div className="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-700">
          <div className="text-sm text-gray-600 dark:text-gray-400">
            {loading ? "Loading..." : `${items.length} requirements on this page`}
          </div>
          <div className="flex items-center gap-2">
            <button
              className={`rounded-md border px-3 py-1.5 text-sm ${canPrev ? "border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700" : "border-gray-200 bg-gray-100 text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-500"}`}
              disabled={!canPrev || loading}
              onClick={() => loadRows(pageMeta.current_page - 1)}
            >
              Prev
            </button>
            <div className="text-sm text-gray-600 dark:text-gray-400">
              Page {pageMeta.current_page} / {pageMeta.last_page}
            </div>
            <button
              className={`rounded-md border px-3 py-1.5 text-sm ${canNext ? "border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700" : "border-gray-200 bg-gray-100 text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-500"}`}
              disabled={!canNext || loading}
              onClick={() => loadRows(pageMeta.current_page + 1)}
            >
              Next
            </button>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="min-w-full text-sm">
            <thead className="bg-gray-50 dark:bg-gray-900/50">
              <tr className="text-left text-gray-600 dark:text-gray-400">
                <th className="px-4 py-3">User</th>
                <th className="px-4 py-3">Product Name</th>
                <th className="px-4 py-3">Financing</th>
                <th className="px-4 py-3">GST</th>
                <th className="px-4 py-3">Submitted</th>
                <th className="px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              {!loading && items.length === 0 ? (
                <tr>
                  <td className="px-4 py-6 text-gray-500 dark:text-gray-400" colSpan={6}>
                    No requirements found.
                  </td>
                </tr>
              ) : (
                items.map((row) => (
                  <tr key={row.id} className="border-t border-gray-100 dark:border-gray-700">
                    <td className="px-4 py-3">
                      <div className="font-medium text-gray-900">{row.user_name || "Guest / Unknown"}</div>
                      <div className="text-gray-500 text-xs">{row.user_email || row.user_phone || "—"}</div>
                    </td>
                    <td className="px-4 py-3">
                      <div className="font-medium">{row.product_name || "—"}</div>
                    </td>
                    <td className="px-4 py-3 text-gray-600 dark:text-gray-400">
                      {row.financing || "—"}
                    </td>
                    <td className="px-4 py-3 text-gray-600 dark:text-gray-400">
                      {row.gst_status || "—"}
                    </td>
                    <td className="px-4 py-3 text-gray-600 dark:text-gray-400">
                      {row.created_at ? new Date(row.created_at).toLocaleString() : "—"}
                    </td>
                    <td className="px-4 py-3">
                      <button
                        className="rounded-md border border-gray-200 bg-white px-3 py-1.5 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
                        onClick={() => openDetail(row.id)}
                      >
                        View
                      </button>
                    </td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

      {selectedId && (
        <div className="fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
          <div className="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-gray-800">
            <div className="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
              <div>
                <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Requirement Detail</h2>
                <p className="text-xs text-gray-500 dark:text-gray-400">ID: {selectedId}</p>
              </div>
              <button
                className="rounded-md border border-gray-200 bg-white px-3 py-1.5 text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
                onClick={() => {
                  setSelectedId(null);
                  setDetail(null);
                }}
              >
                Close
              </button>
            </div>

            <div className="p-5">
              {detailLoading ? (
                <div className="text-gray-500 dark:text-gray-400">Loading detail...</div>
              ) : !detail ? (
                <div className="text-red-500">Failed to load detail.</div>
              ) : (
                <div className="space-y-5 text-sm">
                  <div>
                    <div className="text-xs uppercase tracking-wide text-gray-400 mb-1">User</div>
                    <div className="font-medium">{detail.user?.name || "Guest / Unknown"}</div>
                    <div className="text-gray-600">{detail.user?.email || "—"}</div>
                    <div className="text-gray-600">{detail.user?.phone || "—"}</div>
                  </div>

                  <div>
                    <div className="text-xs uppercase tracking-wide text-gray-400 mb-1">Product Name</div>
                    <div className="font-medium">{detail.product_name || "—"}</div>
                  </div>

                  <div>
                    <div className="text-xs uppercase tracking-wide text-gray-400 mb-1">Financing</div>
                    <div className="text-gray-600">{detail.financing || "—"}</div>
                  </div>

                  <div>
                    <div className="text-xs uppercase tracking-wide text-gray-400 mb-1">GST Status</div>
                    <div className="text-gray-600">{detail.gst_status || "—"}</div>
                  </div>

                  <div>
                    <div className="text-xs uppercase tracking-wide text-gray-400 mb-1">Voice Recording</div>
                    <div className="text-gray-600">
                      {detail.voice_recording ? detail.voice_recording : "Not available"}
                    </div>
                  </div>

                  <div>
                    <div className="text-xs uppercase tracking-wide text-gray-400 mb-1">Submitted</div>
                    <div className="text-gray-600">
                      {detail.created_at ? new Date(detail.created_at).toLocaleString() : "—"}
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
