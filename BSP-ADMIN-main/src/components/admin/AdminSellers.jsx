import { useEffect, useMemo, useState } from "react";
import { Link } from "react-router-dom";
import toast from "react-hot-toast";
import { Eye, Search, Trash2 } from "lucide-react";
import useAuthStore from "../../store/authStore";
import {
  approveAdminSeller,
  fetchAdminSellers,
  rejectAdminSeller,
  deleteAdminSeller,
} from "../../services/api";

const statusOptions = [
  { label: "Pending", value: "pending" },
  { label: "Approved", value: "approved" },
  { label: "Rejected", value: "rejected" },
];

export default function AdminSellers() {
  const hasPermission = useAuthStore((state) => state.hasPermission);
  const canApprove = hasPermission("sellers.approve");
  const canReject = hasPermission("sellers.reject");
  const canDelete = hasPermission("sellers.delete");

  const [status, setStatus] = useState("pending");
  const [search, setSearch] = useState("");
  const [loading, setLoading] = useState(false);
  const [items, setItems] = useState([]);
  const [pageMeta, setPageMeta] = useState({ current_page: 1, last_page: 1, total: 0 });
  const [processingId, setProcessingId] = useState(null);
  const [rejectReason, setRejectReason] = useState("");
  const [rejectModal, setRejectModal] = useState({ open: false, seller: null });

  const canPrev = useMemo(() => pageMeta.current_page > 1, [pageMeta]);
  const canNext = useMemo(() => pageMeta.current_page < pageMeta.last_page, [pageMeta]);

  const fetchSellers = async (page = 1, nextStatus = status, nextSearch = search) => {
    setLoading(true);
    try {
      const res = await fetchAdminSellers({
        status: nextStatus || undefined,
        search: nextSearch || undefined,
        page,
      });

      const payload = res.data;
      setItems(payload.data || []);
      setPageMeta({
        current_page: payload.current_page || 1,
        last_page: payload.last_page || 1,
        total: payload.total || 0,
      });
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load sellers");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchSellers(1, status, search);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [status]);

  const handleSearchSubmit = (event) => {
    event?.preventDefault?.();
    fetchSellers(1, status, search);
  };

  const handleApprove = async (sellerId) => {
    try {
      setProcessingId(sellerId);
      await approveAdminSeller(sellerId);
      toast.success("Seller approved");
      fetchSellers(pageMeta.current_page, status, search);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Approve failed");
    } finally {
      setProcessingId(null);
    }
  };

  const handleReject = async () => {
    if (!rejectModal.seller) return;

    try {
      setProcessingId(rejectModal.seller.id);
      await rejectAdminSeller(rejectModal.seller.id, { reason: rejectReason || null });
      toast.success("Seller rejected");
      setRejectModal({ open: false, seller: null });
      setRejectReason("");
      fetchSellers(pageMeta.current_page, status, search);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Reject failed");
    } finally {
      setProcessingId(null);
    }
  };

  const handleDelete = async (sellerId) => {
    if (!window.confirm("Are you sure you want to delete this seller? This will permanently remove all their products and data.")) {
      return;
    }

    try {
      setProcessingId(sellerId);
      await deleteAdminSeller(sellerId);
      toast.success("Seller deleted successfully");
      fetchSellers(pageMeta.current_page, status, search);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Delete failed");
    } finally {
      setProcessingId(null);
    }
  };

  return (
    <div className="space-y-6">
      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div className="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
          <div>
            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Seller Management</h1>
            <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
              Review business profiles, inspect submitted documents, and approve or reject seller onboarding requests.
            </p>
          </div>

          <div className="flex flex-col sm:flex-row gap-3">
            <form onSubmit={handleSearchSubmit} className="relative">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
              <input
                type="text"
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                placeholder="Search seller, email, company..."
                className="w-full sm:w-72 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 pl-10 pr-4 py-2.5 text-sm"
              />
            </form>

            <button
              type="button"
              onClick={handleSearchSubmit}
              className="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"
            >
              Search
            </button>

            <select
              className="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm"
              value={status}
              onChange={(event) => setStatus(event.target.value)}
            >
              {statusOptions.map((option) => (
                <option key={option.value} value={option.value}>
                  {option.label}
                </option>
              ))}
            </select>
          </div>
        </div>
      </div>

      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div className="text-sm text-gray-500 dark:text-gray-400">
            {loading ? "Loading sellers..." : `${items.length} sellers on this page • Total ${pageMeta.total}`}
          </div>

          <div className="flex items-center gap-2">
            <button
              className="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
              disabled={!canPrev || loading}
              onClick={() => fetchSellers(pageMeta.current_page - 1, status, search)}
            >
              Prev
            </button>
            <span className="text-sm text-gray-500 dark:text-gray-400">
              Page {pageMeta.current_page} / {pageMeta.last_page}
            </span>
            <button
              className="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
              disabled={!canNext || loading}
              onClick={() => fetchSellers(pageMeta.current_page + 1, status, search)}
            >
              Next
            </button>
          </div>
        </div>

        {loading ? (
          <div className="p-8 text-sm text-gray-500 dark:text-gray-400">Loading sellers...</div>
        ) : items.length === 0 ? (
          <div className="p-8 text-sm text-gray-500 dark:text-gray-400">No sellers found for this filter.</div>
        ) : (
          <div className="divide-y divide-gray-100 dark:divide-gray-700">
            {items.map((seller) => {
              const company = seller?.business_profile?.company_name || seller.company_name || "Not provided";
              const companyPhone = seller?.business_profile?.company_phone || seller.company_phone || "Not provided";
              const location = [
                seller?.business_profile?.city_name,
                seller?.business_profile?.state_name,
                seller?.business_profile?.country_code || seller.country_code,
              ]
                .filter(Boolean)
                .join(", ");

              return (
                <div key={seller.id} className="p-5 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                  <div className="min-w-0 space-y-2">
                    <div className="flex flex-wrap items-center gap-3">
                      <h2 className="text-lg font-semibold text-gray-900 dark:text-white">
                        {seller.name || "Unnamed seller"}
                      </h2>
                      <span
                        className={`rounded-full px-2.5 py-1 text-xs font-semibold ${
                          seller.seller_status === "approved"
                            ? "bg-green-100 text-green-700"
                            : seller.seller_status === "rejected"
                              ? "bg-red-100 text-red-700"
                              : "bg-yellow-100 text-yellow-700"
                        }`}
                      >
                        {seller.seller_status?.toUpperCase()}
                      </span>
                    </div>

                    <div className="grid gap-1 text-sm text-gray-500 dark:text-gray-400 sm:grid-cols-2 xl:grid-cols-3">
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Email:</span> {seller.email || "Not provided"}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Phone:</span> {seller.phone || "Not provided"}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Company:</span> {company}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Company Phone:</span> {companyPhone}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Products:</span> {seller.products_count ?? 0}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Location:</span> {location || "Not provided"}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Tax ID:</span> {seller.tax_id_type || "GSTIN"}: {seller.tax_id_number || "Not provided"}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Submitted:</span> {seller.seller_submitted_at ? new Date(seller.seller_submitted_at).toLocaleString() : "Not available"}</div>
                    </div>

                    {seller.seller_status === "rejected" && seller.seller_rejection_reason ? (
                      <div className="rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                        Rejection reason: {seller.seller_rejection_reason}
                      </div>
                    ) : null}
                  </div>

                  <div className="flex flex-col gap-2 shrink-0">
                    <Link
                      to={`/admin/sellers/${seller.id}`}
                      className="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                      <Eye size={16} />
                      View Details
                    </Link>

                    {canDelete ? (
                      <button
                        onClick={() => handleDelete(seller.id)}
                        disabled={processingId === seller.id}
                        className="inline-flex items-center justify-center gap-2 rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
                      >
                        <Trash2 size={16} />
                        {processingId === seller.id ? "Deleting..." : "Delete Seller"}
                      </button>
                    ) : null}

                    {seller.seller_status === "pending" && canApprove ? (
                      <button
                        onClick={() => handleApprove(seller.id)}
                        disabled={processingId === seller.id}
                        className="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-60"
                      >
                        {processingId === seller.id ? "Approving..." : "Approve"}
                      </button>
                    ) : null}

                    {seller.seller_status === "pending" && canReject ? (
                      <button
                        onClick={() => {
                          setRejectReason("");
                          setRejectModal({ open: true, seller });
                        }}
                        disabled={processingId === seller.id}
                        className="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
                      >
                        Reject
                      </button>
                    ) : null}
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>

      {rejectModal.open ? (
        <div className="fixed inset-0 z-50 bg-black/40 p-4 flex items-center justify-center">
          <div className="w-full max-w-lg rounded-2xl border bg-white dark:bg-gray-800 shadow-xl">
            <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
              <div>
                <h3 className="font-semibold text-gray-900 dark:text-white">Reject Seller</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  {rejectModal.seller?.business_profile?.company_name || rejectModal.seller?.name}
                </p>
              </div>
              <button
                className="text-sm text-gray-500 hover:text-gray-700"
                onClick={() => setRejectModal({ open: false, seller: null })}
              >
                Close
              </button>
            </div>

            <div className="p-5 space-y-4">
              <textarea
                rows={4}
                value={rejectReason}
                onChange={(event) => setRejectReason(event.target.value)}
                placeholder="Reason for rejection"
                className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 text-sm"
              />

              <div className="flex justify-end gap-2">
                <button
                  className="rounded-xl border px-4 py-2 text-sm font-semibold"
                  onClick={() => setRejectModal({ open: false, seller: null })}
                  disabled={processingId === rejectModal.seller?.id}
                >
                  Cancel
                </button>
                <button
                  className="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                  onClick={handleReject}
                  disabled={processingId === rejectModal.seller?.id}
                >
                  {processingId === rejectModal.seller?.id ? "Rejecting..." : "Reject Seller"}
                </button>
              </div>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
