import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import toast from "react-hot-toast";
import { Eye, Search, Trash2 } from "lucide-react";
import useAuthStore from "../../store/authStore";
import {
  approveAdminProduct,
  rejectAdminProduct,
  exportAdminProducts,
  importAdminProducts,
  fetchAdminProducts,
  deleteAdminProduct,
} from "../../services/api";

export default function AdminProducts() {
  const hasPermission = useAuthStore((state) => state.hasPermission);
  const canApprove = hasPermission("products.approve");
  const canReject = hasPermission("products.reject") || hasPermission("products.approve");
  const canDelete = hasPermission("products.delete");

  const [items, setItems] = useState([]);
  const [meta, setMeta] = useState(null);
  const [countryOptions, setCountryOptions] = useState([]);
  const [loading, setLoading] = useState(true);
  const [status, setStatus] = useState("pending");
  const [search, setSearch] = useState("");
  const [country, setCountry] = useState("");
  const [page, setPage] = useState(1);
  const [processingId, setProcessingId] = useState(null);
  const [exporting, setExporting] = useState(false);
  const [importing, setImporting] = useState(false);

  const [rejectModal, setRejectModal] = useState({ open: false, product: null });
  const [rejectReason, setRejectReason] = useState("");

  const loadProducts = async (
    pageToLoad = page,
    nextStatus = status,
    nextSearch = search,
    nextCountry = country,
  ) => {
    try {
      setLoading(true);

      const { data } = await fetchAdminProducts({
        page: pageToLoad,
        status: nextStatus || undefined,
        search: nextSearch || undefined,
        country: nextCountry || undefined,
      });

      setItems(data.data || []);
      setMeta(data);
      setCountryOptions(data.country_options || []);
      setPage(pageToLoad);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load products");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProducts(1, status, search, country);
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [status]);

  const handleSearchSubmit = (event) => {
    event.preventDefault();
    loadProducts(1, status, search, country);
  };

  const handleApprove = async (id) => {
    try {
      setProcessingId(id);
      const { data } = await approveAdminProduct(id);
      toast.success(data?.message || "Product approved");
      loadProducts(page, status, search, country);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to approve product");
    } finally {
      setProcessingId(null);
    }
  };

  const handleReject = async () => {
    if (!rejectModal.product) return;
    try {
      setProcessingId(rejectModal.product.id);
      await rejectAdminProduct(rejectModal.product.id, { reason: rejectReason || null });
      toast.success("Product rejected successfully");
      setRejectModal({ open: false, product: null });
      setRejectReason("");
      loadProducts(page, status, search, country);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to reject product");
    } finally {
      setProcessingId(null);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure you want to delete this product? This action cannot be undone.")) {
      return;
    }

    try {
      setProcessingId(id);
      await deleteAdminProduct(id);
      toast.success("Product deleted successfully");
      loadProducts(page, status, search, country);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to delete product");
    } finally {
      setProcessingId(null);
    }
  };

  const handleExport = async () => {
    try {
      setExporting(true);

      const response = await exportAdminProducts({
        status: status || undefined,
        search: search || undefined,
        country: country || undefined,
      });

      const blob = new Blob([response.data], { type: "text/csv;charset=utf-8;" });
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement("a");
      const filterLabel = status === "live" ? "live" : status === "pending" ? "under-review" : "all";
      link.href = url;
      link.download = `products-${filterLabel}.csv`;
      document.body.appendChild(link);
      link.click();
      link.remove();
      window.URL.revokeObjectURL(url);

      toast.success("Filtered products exported");
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to export products");
    } finally {
      setExporting(false);
    }
  };

  const handleImport = async (event) => {
    const file = event.target.files?.[0];
    if (!file) return;

    try {
      setImporting(true);
      const { data } = await importAdminProducts(file);
      toast.success(data?.message || "Products imported successfully");
      loadProducts(1, status, search, country);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to import products");
    } finally {
      setImporting(false);
      event.target.value = "";
    }
  };

  const statusBadge = (value) =>
    value === "live"
      ? "bg-green-100 text-green-700"
      : value === "rejected"
        ? "bg-red-100 text-red-700"
        : "bg-yellow-100 text-yellow-700";

  const formatProductPrice = (product) => {
    const currency = product.currency || "INR";
    const min = Number(product.price || 0).toLocaleString();
    if (product.price_to) {
      const max = Number(product.price_to).toLocaleString();
      return `${currency} ${min} - ${max}`;
    }
    return `${currency} ${min}`;
  };

  return (
    <div className="space-y-6">
      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-5">
        <div className="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
          <div>
            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Product Management</h1>
            <p className="text-sm text-gray-500 dark:text-gray-400 mt-1">
              Review seller products, filter by country, and open a dedicated product review page for full details and images.
            </p>
          </div>

          <div className="flex flex-col xl:items-end gap-3">
            <form onSubmit={handleSearchSubmit} className="flex flex-col lg:flex-row gap-3">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" size={18} />
                <input
                  type="text"
                  value={search}
                  onChange={(event) => setSearch(event.target.value)}
                  placeholder="Search product, seller, company..."
                  className="w-full lg:w-80 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 pl-10 pr-4 py-2.5 text-sm"
                />
              </div>

              <select
                value={country}
                onChange={(event) => setCountry(event.target.value)}
                className="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2.5 text-sm"
              >
                <option value="">All Countries</option>
                {countryOptions.map((option) => (
                  <option key={option.code} value={option.code}>
                    {option.name}
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

            <div className="flex flex-wrap items-center gap-3">
              <label className={`rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100 cursor-pointer flex items-center justify-center transition-all ${importing ? "opacity-60 pointer-events-none" : ""}`}>
                {importing ? "Importing..." : "Import Excel"}
                <input
                  type="file"
                  accept=".csv,text/csv"
                  onChange={handleImport}
                  disabled={importing}
                  className="hidden"
                />
              </label>

              <button
                type="button"
                onClick={handleExport}
                disabled={exporting}
                className="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 disabled:opacity-60"
              >
                {exporting ? "Exporting..." : "Export Excel"}
              </button>
            </div>
          </div>
        </div>

        <div className="flex flex-wrap gap-3 mt-5">
          {[
            { key: "", label: "All" },
            { key: "pending", label: "Under Review" },
            { key: "live", label: "Live" },
          ].map((item) => (
            <button
              key={item.key}
              onClick={() => setStatus(item.key)}
              className={`px-4 py-2 rounded-xl text-sm font-semibold border transition ${
                status === item.key
                  ? item.key === "live"
                    ? "bg-green-600 text-white border-green-600"
                    : item.key === "pending"
                      ? "bg-yellow-500 text-white border-yellow-500"
                      : "bg-blue-600 text-white border-blue-600"
                  : "bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700"
              }`}
            >
              {item.label}
            </button>
          ))}
        </div>
      </div>

      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div className="text-sm text-gray-500 dark:text-gray-400">
            {loading ? "Loading products..." : `${items.length} products on this page`}
          </div>
          <div className="text-sm text-gray-500 dark:text-gray-400">
            Page {meta?.current_page || 1} / {meta?.last_page || 1}
          </div>
        </div>

        {loading ? (
          <div className="p-8 text-sm text-gray-500 dark:text-gray-400">Loading products...</div>
        ) : items.length === 0 ? (
          <div className="p-8 text-sm text-gray-500 dark:text-gray-400">No products found.</div>
        ) : (
          <div className="divide-y divide-gray-100 dark:divide-gray-700">
            {items.map((product) => {
              const company = product?.user?.business_profile?.company_name || "Not provided";
              const seller = product?.user?.name || "Not provided";
              const location = [
                product?.user?.business_profile?.city_name,
                product?.user?.business_profile?.state_name,
                product?.country_code || product?.user?.business_profile?.country_code,
              ]
                .filter(Boolean)
                .join(", ");

              return (
                <div key={product.id} className="p-5 flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                  <div className="min-w-0">
                    <div className="flex flex-wrap items-center gap-3">
                      <h2 className="text-lg font-semibold text-gray-900 dark:text-white">{product.name}</h2>
                      <span className={`rounded-full px-2.5 py-1 text-xs font-semibold ${statusBadge(product.status)}`}>
                        {product.status === "live" ? "LIVE" : product.status === "rejected" ? "REJECTED" : "UNDER REVIEW"}
                      </span>
                    </div>

                    <div className="mt-3 grid gap-1 text-sm text-gray-500 dark:text-gray-400 sm:grid-cols-2 xl:grid-cols-3">
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Seller:</span> {seller}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Company:</span> {company}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Category:</span> {product?.category?.name || "Not set"}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Subcategory:</span> {product?.subcategory?.name || "Not set"}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Price:</span> {formatProductPrice(product)}</div>
                      <div><span className="font-medium text-gray-700 dark:text-gray-200">Country:</span> {product.country_code || "Not set"}</div>
                      <div className="sm:col-span-2 xl:col-span-3"><span className="font-medium text-gray-700 dark:text-gray-200">Location:</span> {location || "Not provided"}</div>
                    </div>
                  </div>

                  <div className="flex flex-wrap gap-2 shrink-0">
                    <Link
                      to={`/admin/products/${product.id}`}
                      className="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                      <Eye size={16} />
                      View Details
                    </Link>

                    {product.status === "pending" && canApprove ? (
                      <button
                        onClick={() => handleApprove(product.id)}
                        disabled={processingId === product.id}
                        className="rounded-xl bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-60"
                      >
                        {processingId === product.id ? "Approving..." : "Approve"}
                      </button>
                    ) : null}

                    {product.status === "pending" && canReject ? (
                      <button
                        onClick={() => {
                          setRejectReason("");
                          setRejectModal({ open: true, product });
                        }}
                        disabled={processingId === product.id}
                        className="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
                      >
                        Reject
                      </button>
                    ) : null}

                    {canDelete && (
                      <button
                        onClick={() => handleDelete(product.id)}
                        disabled={processingId === product.id}
                        className="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
                      >
                        <Trash2 size={16} />
                        {processingId === product.id ? "Deleting..." : "Delete"}
                      </button>
                    )}
                  </div>
                </div>
              );
            })}
          </div>
        )}

        {meta?.last_page > 1 ? (
          <div className="p-5 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <button
              disabled={!meta?.prev_page_url}
              onClick={() => loadProducts(page - 1, status, search, country)}
              className="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
            >
              Previous
            </button>
            <span className="text-sm text-gray-500 dark:text-gray-400">
              Page {meta?.current_page} of {meta?.last_page}
            </span>
            <button
              disabled={!meta?.next_page_url}
              onClick={() => loadProducts(page + 1, status, search, country)}
              className="rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
            >
              Next
            </button>
          </div>
        ) : null}
      </div>

      {rejectModal.open ? (
        <div className="fixed inset-0 z-50 bg-black/40 p-4 flex items-center justify-center">
          <div className="w-full max-w-lg rounded-2xl border bg-white dark:bg-gray-800 shadow-xl">
            <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
              <div>
                <h3 className="font-semibold text-gray-900 dark:text-white">Reject Product</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  {rejectModal.product?.name}
                </p>
              </div>
              <button
                className="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white"
                onClick={() => setRejectModal({ open: false, product: null })}
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
                className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-3 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
              />

              <div className="flex justify-end gap-2">
                <button
                  className="rounded-xl border px-4 py-2 text-sm font-semibold"
                  onClick={() => setRejectModal({ open: false, product: null })}
                  disabled={processingId === rejectModal.product?.id}
                >
                  Cancel
                </button>
                <button
                  className="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                  onClick={handleReject}
                  disabled={processingId === rejectModal.product?.id}
                >
                  {processingId === rejectModal.product?.id ? "Rejecting..." : "Reject Product"}
                </button>
              </div>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
