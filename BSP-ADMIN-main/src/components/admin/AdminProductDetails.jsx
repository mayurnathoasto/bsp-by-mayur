import { useEffect, useMemo, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import toast from "react-hot-toast";
import { ArrowLeft, CheckCircle2, ExternalLink, Trash2 } from "lucide-react";
import useAuthStore from "../../store/authStore";
import {
  approveAdminProduct,
  rejectAdminProduct,
  fetchAdminProduct,
  deleteAdminProduct,
} from "../../services/api";

const detailCard = (label, value) => (
  <div className="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30 p-4">
    <div className="text-xs font-semibold uppercase tracking-wide text-gray-400">{label}</div>
    <div className="mt-2 text-sm font-medium text-gray-900 dark:text-white break-words">
      {value || "Not provided"}
    </div>
  </div>
);

export default function AdminProductDetails() {
  const { id } = useParams();
  const navigate = useNavigate();
  const hasPermission = useAuthStore((state) => state.hasPermission);
  const canApprove = hasPermission("products.approve");
  const canReject = hasPermission("products.reject") || hasPermission("products.approve");
  const canDelete = hasPermission("products.delete");
  const canViewSellers = hasPermission("sellers.view");

  const [product, setProduct] = useState(null);
  const [loading, setLoading] = useState(true);
  const [processing, setProcessing] = useState(false);
  const [activeImage, setActiveImage] = useState("");
  const [showReject, setShowReject] = useState(false);
  const [rejectReason, setRejectReason] = useState("");

  const loadProduct = async () => {
    try {
      setLoading(true);
      const { data } = await fetchAdminProduct(id);
      setProduct(data);
      setActiveImage((data.images || [])[0] || "");
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load product");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProduct();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [id]);

  const handleApprove = async () => {
    try {
      setProcessing(true);
      await approveAdminProduct(id);
      toast.success("Product approved");
      loadProduct();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to approve product");
    } finally {
      setProcessing(false);
    }
  };

  const handleReject = async () => {
    try {
      setProcessing(true);
      await rejectAdminProduct(id, { reason: rejectReason || null });
      toast.success("Product rejected successfully");
      setShowReject(false);
      loadProduct();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to reject product");
    } finally {
      setProcessing(false);
    }
  };

  const handleDelete = async () => {
    if (!window.confirm("Are you sure you want to delete this product? This action cannot be undone.")) {
      return;
    }

    try {
      setProcessing(true);
      await deleteAdminProduct(id);
      toast.success("Product deleted successfully");
      navigate("/admin/products");
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to delete product");
    } finally {
      setProcessing(false);
    }
  };

  const specs = useMemo(() => product?.specifications || [], [product]);

  if (loading) {
    return <div className="p-6 text-sm text-gray-500 dark:text-gray-400">Loading product details...</div>;
  }

  if (!product) {
    return <div className="p-6 text-sm text-gray-500 dark:text-gray-400">Product not found.</div>;
  }

  const sellerLocation = [
    product?.user?.business_profile?.city_name,
    product?.user?.business_profile?.state_name,
    product?.country_code || product?.user?.business_profile?.country_code,
  ]
    .filter(Boolean)
    .join(", ");

  return (
    <div className="space-y-6">
      <div className="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div className="space-y-3">
          <button
            onClick={() => navigate(-1)}
            className="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
          >
            <ArrowLeft size={16} />
            Back to products
          </button>

          <div>
            <div className="flex flex-wrap items-center gap-3">
              <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{product.name}</h1>
              <span
                className={`rounded-full px-3 py-1 text-xs font-semibold ${
                  product.status === "live"
                    ? "bg-green-100 text-green-700"
                    : product.status === "rejected"
                      ? "bg-red-100 text-red-700"
                      : "bg-yellow-100 text-yellow-700"
                }`}
              >
                {product.status === "live" ? "LIVE" : product.status === "rejected" ? "REJECTED" : "UNDER REVIEW"}
              </span>
            </div>
            <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">
              Dedicated review page for the full product submission, media gallery, commercial fields, and seller snapshot.
            </p>
          </div>
        </div>

        <div className="flex flex-wrap gap-2">
          {product.status === "pending" && canApprove ? (
            <button
              onClick={handleApprove}
              disabled={processing}
              className="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-60"
            >
              <CheckCircle2 size={16} />
              {processing ? "Approving..." : "Approve Product"}
            </button>
          ) : null}

          {product.status === "pending" && canReject ? (
            <button
              onClick={() => {
                setRejectReason("");
                setShowReject(true);
              }}
              disabled={processing}
              className="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
            >
              Reject Product
            </button>
          ) : null}

          {canDelete && (
            <button
              onClick={handleDelete}
              disabled={processing}
              className="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
            >
              <Trash2 size={16} />
              {processing ? "Deleting..." : "Delete Product"}
            </button>
          )}
        </div>
      </div>

      <div className="grid gap-6 xl:grid-cols-[1.15fr_1fr]">
        <div className="space-y-6">
          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <div className="grid gap-4 lg:grid-cols-[1fr_auto]">
              <div className="overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {activeImage ? (
                  <img src={activeImage} alt={product.name} className="h-[360px] w-full object-cover" />
                ) : (
                  <div className="flex h-[360px] items-center justify-center text-sm text-gray-500">No product image uploaded</div>
                )}
              </div>

              <div className="grid grid-cols-4 gap-3 lg:grid-cols-1">
                {(product.images || []).map((image, index) => (
                  <button
                    key={`${image}-${index}`}
                    onClick={() => setActiveImage(image)}
                    className={`overflow-hidden rounded-xl border ${activeImage === image ? "border-blue-500" : "border-gray-200 dark:border-gray-700"}`}
                  >
                    <img src={image} alt={`${product.name} ${index + 1}`} className="h-20 w-20 object-cover" />
                  </button>
                ))}
              </div>
            </div>
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Commercial Details</h2>
            <div className="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
              {detailCard("Price", product.price_to
                ? `${product.currency || "INR"} ${Number(product.price || 0).toLocaleString()} - ${Number(product.price_to).toLocaleString()}`
                : `${product.currency || "INR"} ${Number(product.price || 0).toLocaleString()}`)}
              {detailCard("MOQ", product.min_order_quantity)}
              {detailCard("Lead Time", product.lead_time_display || [product.lead_time_value, product.lead_time_unit].filter(Boolean).join(" "))}
              {detailCard("Country", product.country_code)}
              {detailCard("Shipping Options", product.shipping_display || (product.shipping_options || []).join(", "))}
              {detailCard("Payment Options", product.payment_display || (product.payment_options || []).join(", "))}
              {detailCard("Sample MOQ", product.sample_moq)}
              {detailCard("Warranty", product.warranty)}
              {detailCard("Support Option", product.support_option)}
            </div>
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Description</h2>
            <p className="mt-4 text-sm leading-6 text-gray-700 dark:text-gray-200">
              {product.description || "No description provided."}
            </p>
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Specifications</h2>
            {specs.length ? (
              <div className="mt-4 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                {specs.map((spec) => (
                  <div
                    key={spec.id}
                    className="grid grid-cols-1 gap-2 border-b border-gray-100 dark:border-gray-700 px-4 py-3 md:grid-cols-[220px_1fr]"
                  >
                    <div className="text-sm font-semibold text-gray-500 dark:text-gray-400">{spec.spec_key}</div>
                    <div className="text-sm text-gray-900 dark:text-white">{spec.spec_value || "Not provided"}</div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="mt-4 text-sm text-gray-500 dark:text-gray-400">No extra specifications added.</div>
            )}
          </section>
        </div>

        <div className="space-y-6">
          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Classification</h2>
            <div className="mt-4 grid gap-4">
              {detailCard("Category", product?.category?.name)}
              {detailCard("Subcategory", product?.subcategory?.name)}
              {detailCard("Status", product.status)}
              {detailCard("Product ID", product.id)}
            </div>
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Seller Snapshot</h2>
            <div className="mt-4 grid gap-4">
              {detailCard("Seller", product?.user?.name)}
              {detailCard("Email", product?.user?.email)}
              {detailCard("Phone", product?.user?.phone)}
              {detailCard("Company", product?.user?.business_profile?.company_name)}
              {detailCard("Location", sellerLocation)}
            </div>

            {canViewSellers ? (
              <Link
                to={`/admin/sellers/${product?.user?.id}`}
                className="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700"
              >
                View Seller Details
                <ExternalLink size={14} />
              </Link>
            ) : null}
          </section>
        </div>
      </div>

      {showReject ? (
        <div className="fixed inset-0 z-50 bg-black/40 p-4 flex items-center justify-center">
          <div className="w-full max-w-lg rounded-2xl border bg-white dark:bg-gray-800 shadow-xl">
            <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
              <div>
                <h3 className="font-semibold text-gray-900 dark:text-white">Reject Product</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  {product?.name}
                </p>
              </div>
              <button
                className="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white"
                onClick={() => setShowReject(false)}
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
                  onClick={() => setShowReject(false)}
                  disabled={processing}
                >
                  Cancel
                </button>
                <button
                  className="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                  onClick={handleReject}
                  disabled={processing}
                >
                  {processing ? "Rejecting..." : "Reject Product"}
                </button>
              </div>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
