import { useEffect, useMemo, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";
import toast from "react-hot-toast";
import { ArrowLeft, CheckCircle2, ExternalLink, XCircle } from "lucide-react";
import useAuthStore from "../../store/authStore";
import {
  approveAdminSeller,
  fetchAdminSeller,
  rejectAdminSeller,
  updateAdminSeller,
} from "../../services/api";

const field = (label, value) => (
  <div className="rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30 p-4">
    <div className="text-xs font-semibold uppercase tracking-wide text-gray-400">{label}</div>
    <div className="mt-2 text-sm font-medium text-gray-900 dark:text-white break-words">
      {value || "Not provided"}
    </div>
  </div>
);

const mediaCard = (url, label, key) => (
  <a
    key={key}
    href={url}
    target="_blank"
    rel="noreferrer"
    className="group overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900"
  >
    {/\.(png|jpe?g|webp|gif|svg)(?:$|[?#])/i.test(url || "") ? (
      <img src={url} alt={label} className="h-40 w-full object-cover" />
    ) : (
      <div className="flex h-40 items-center justify-center bg-gray-50 dark:bg-gray-800 text-sm text-gray-500">
        Open file
      </div>
    )}
    <div className="flex items-center justify-between px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
      <span className="truncate">{label}</span>
      <ExternalLink size={15} className="shrink-0 opacity-60 group-hover:opacity-100" />
    </div>
  </a>
);

export default function AdminSellerDetails() {
  const { id } = useParams();
  const navigate = useNavigate();
  const hasPermission = useAuthStore((state) => state.hasPermission);
  const canApprove = hasPermission("sellers.approve");
  const canReject = hasPermission("sellers.reject");
  const canEdit = hasPermission("sellers.edit");
  const canViewProducts = hasPermission("products.view");

  const [seller, setSeller] = useState(null);
  const [loading, setLoading] = useState(true);
  const [processing, setProcessing] = useState(false);
  const [rejectReason, setRejectReason] = useState("");
  const [showReject, setShowReject] = useState(false);
  
  const [showEditModal, setShowEditModal] = useState(false);
  const [editForm, setEditForm] = useState({
    name: "",
    email: "",
    phone: "",
    designation: "",
    alternate_email: "",
    office_contact: "",
    alternate_mobile: "",
    whatsapp_number: "",
    company_name: "",
    company_phone: "",
    company_address: "",
    tax_id_type: "GSTIN",
    tax_id_number: "",
    seller_status: "pending",
    seller_rejection_reason: "",
  });

  const loadSeller = async () => {
    try {
      setLoading(true);
      const { data } = await fetchAdminSeller(id);
      setSeller(data);
      setEditForm({
        name: data.name || "",
        email: data.email || "",
        phone: data.phone || "",
        designation: data.designation || "",
        alternate_email: data.alternate_email || "",
        office_contact: data.office_contact || "",
        alternate_mobile: data.alternate_mobile || "",
        whatsapp_number: data.whatsapp_number || "",
        company_name: data.company_name || "",
        company_phone: data.company_phone || "",
        company_address: data.company_address || "",
        tax_id_type: data.tax_id_type || "GSTIN",
        tax_id_number: data.tax_id_number || "",
        seller_status: data.seller_status || "pending",
        seller_rejection_reason: data.seller_rejection_reason || "",
      });
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load seller");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadSeller();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [id]);

  const mediaItems = useMemo(() => {
    if (!seller) return [];

    const businessProfile = seller.business_profile || {};
    const files = [];

    if (businessProfile.logo) files.push({ label: "Company Logo", url: businessProfile.logo });

    (businessProfile.company_photos || []).forEach((url, index) => {
      files.push({ label: `Company Photo ${index + 1}`, url });
    });

    (businessProfile.certifications || []).forEach((url, index) => {
      files.push({ label: `Certification ${index + 1}`, url });
    });

    (businessProfile.awards || []).forEach((url, index) => {
      files.push({ label: `Award ${index + 1}`, url });
    });

    Object.entries(businessProfile.documents_json || {}).forEach(([key, value]) => {
      if (value) files.push({ label: key.replace(/_/g, " "), url: value });
    });

    return files;
  }, [seller]);

  const handleApprove = async () => {
    try {
      setProcessing(true);
      await approveAdminSeller(id);
      toast.success("Seller approved");
      loadSeller();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to approve seller");
    } finally {
      setProcessing(false);
    }
  };

  const handleReject = async () => {
    try {
      setProcessing(true);
      await rejectAdminSeller(id, { reason: rejectReason || null });
      toast.success("Seller rejected");
      setShowReject(false);
      loadSeller();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to reject seller");
    } finally {
      setProcessing(false);
    }
  };

  const handleSaveEdit = async (e) => {
    e.preventDefault();
    try {
      setProcessing(true);
      // Construct exact payload for update endpoint
      const payload = {
        name: editForm.name,
        email: editForm.email || null,
        phone: editForm.phone,
        designation: editForm.designation || null,
        alternate_email: editForm.alternate_email || null,
        office_contact: editForm.office_contact || null,
        alternate_mobile: editForm.alternate_mobile || null,
        whatsapp_number: editForm.whatsapp_number || null,
        company_name: editForm.company_name || null,
        company_phone: editForm.company_phone || null,
        company_address: editForm.company_address || null,
        tax_id_type: editForm.tax_id_type || null,
        tax_id_number: editForm.tax_id_number || null,
        seller_status: editForm.seller_status,
      };

      if (editForm.seller_status === "rejected") {
        payload.seller_rejection_reason = editForm.seller_rejection_reason || "Rejected by admin";
      }

      await updateAdminSeller(id, payload);
      toast.success("Seller details updated successfully");
      setShowEditModal(false);
      loadSeller();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to update seller");
    } finally {
      setProcessing(false);
    }
  };

  const formatProductPrice = (product) => {
    const currency = product.currency || "INR";
    const min = Number(product.price || 0).toLocaleString();
    if (product.price_to) {
      const max = Number(product.price_to).toLocaleString();
      return `${currency} ${min} - ${max}`;
    }
    return `${currency} ${min}`;
  };

  if (loading) {
    return <div className="p-6 text-sm text-gray-500 dark:text-gray-400">Loading seller details...</div>;
  }

  if (!seller) {
    return <div className="p-6 text-sm text-gray-500 dark:text-gray-400">Seller not found.</div>;
  }

  const businessProfile = seller.business_profile || {};
  const bankDetail = seller.bank_detail || {};
  const sellerStatus = seller.seller_status || "pending";
  const location = [
    businessProfile.city_name || seller.city?.name,
    businessProfile.state_name || seller.city?.state,
    businessProfile.country_code || seller.country_code,
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
            Back to sellers
          </button>

          <div>
            <div className="flex flex-wrap items-center gap-3">
              <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
                {businessProfile.company_name || seller.company_name || seller.name}
              </h1>
              <span
                className={`rounded-full px-3 py-1 text-xs font-semibold ${
                  sellerStatus === "approved"
                    ? "bg-green-100 text-green-700"
                    : sellerStatus === "rejected"
                      ? "bg-red-100 text-red-700"
                      : "bg-yellow-100 text-yellow-700"
                }`}
              >
                {sellerStatus.toUpperCase()}
              </span>
            </div>
            <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">
              Detailed review of the seller onboarding submission, uploaded documents, business metadata, and existing products.
            </p>
          </div>
        </div>

        <div className="flex flex-wrap gap-2">
          {canEdit ? (
            <button
              onClick={() => setShowEditModal(true)}
              disabled={processing}
              className="inline-flex items-center gap-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 text-sm font-semibold disabled:opacity-60"
            >
              Edit Details
            </button>
          ) : null}

          {sellerStatus === "pending" && canApprove ? (
            <button
              onClick={handleApprove}
              disabled={processing}
              className="inline-flex items-center gap-2 rounded-xl bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 disabled:opacity-60"
            >
              <CheckCircle2 size={16} />
              {processing ? "Approving..." : "Approve Seller"}
            </button>
          ) : null}

          {sellerStatus === "pending" && canReject ? (
            <button
              onClick={() => setShowReject(true)}
              disabled={processing}
              className="inline-flex items-center gap-2 rounded-xl border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 disabled:opacity-60"
            >
              <XCircle size={16} />
              Reject Seller
            </button>
          ) : null}
        </div>
      </div>

      <div className="grid gap-6 xl:grid-cols-[1.35fr_1fr]">
        <div className="space-y-6">
          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Account Snapshot</h2>
            <div className="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
              {field("Contact Person", seller.name)}
              {field("Email", seller.email)}
              {field("Phone", seller.phone)}
              {field("Designation", seller.designation)}
              {field("Login Identifier", seller.login_identifier)}
              {field("Location", location)}
              {field("Submitted At", seller.seller_submitted_at ? new Date(seller.seller_submitted_at).toLocaleString() : "")}
              {field("Approved At", seller.seller_verified_at ? new Date(seller.seller_verified_at).toLocaleString() : "")}
              {field("Rejection Reason", seller.seller_rejection_reason)}
            </div>
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Business Details</h2>
            <div className="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
              {field("Company Name", businessProfile.company_name || seller.company_name)}
              {field("Company Phone", businessProfile.company_phone || seller.company_phone)}
              {field("Ownership Type", businessProfile.ownership_type)}
              {field("Year Established", businessProfile.year_established)}
              {field("Employee Count", businessProfile.employee_count)}
              {field("Annual Turnover", [businessProfile.annual_turnover_currency, businessProfile.annual_turnover_amount, businessProfile.annual_turnover_unit].filter(Boolean).join(" "))}
              {field("Address", businessProfile.formatted_address || businessProfile.address_line || seller.company_address)}
              {field("Preferred Cities", (businessProfile.preferred_cities || []).join(", "))}
              {field("Preferred States", (businessProfile.preferred_states || []).join(", "))}
              {field("Tax ID", `${seller.tax_id_type || ""} ${seller.tax_id_number || ""}`.trim())}
              {field("Office Contact", seller.office_contact)}
              {field("WhatsApp", seller.whatsapp_number)}
              {field("Alternate Mobile", seller.alternate_mobile)}
              {field("Alternate Email", seller.alternate_email)}
            </div>

            {businessProfile.company_description ? (
              <div className="mt-4 rounded-xl border border-gray-100 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-900/30 p-4">
                <div className="text-xs font-semibold uppercase tracking-wide text-gray-400">Company Description</div>
                <p className="mt-2 text-sm leading-6 text-gray-700 dark:text-gray-200">
                  {businessProfile.company_description}
                </p>
              </div>
            ) : null}
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Products</h2>
            <div className="mt-4 grid gap-4 md:grid-cols-3">
              {field("Total Products", seller.products_count)}
              {field("Live Products", seller.live_products_count)}
              {field("Under Review", seller.pending_products_count)}
            </div>

            <div className="mt-4 overflow-x-auto">
              <table className="min-w-full text-sm">
                <thead className="text-left text-gray-500">
                  <tr>
                    <th className="py-3 pr-4">Product</th>
                    <th className="py-3 pr-4">Category</th>
                    <th className="py-3 pr-4">Status</th>
                    <th className="py-3 pr-4">Price</th>
                    <th className="py-3">Action</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-100 dark:divide-gray-700">
                  {(seller.products || []).map((product) => (
                    <tr key={product.id}>
                      <td className="py-3 pr-4 font-medium text-gray-900 dark:text-white">{product.name}</td>
                      <td className="py-3 pr-4 text-gray-500 dark:text-gray-400">
                        {[product.category?.name, product.subcategory?.name].filter(Boolean).join(" / ") || "Not set"}
                      </td>
                      <td className="py-3 pr-4 text-gray-500 dark:text-gray-400">{product.status}</td>
                      <td className="py-3 pr-4 text-gray-500 dark:text-gray-400">
                        {formatProductPrice(product)}
                      </td>
                      <td className="py-3">
                        {canViewProducts ? (
                          <Link
                            to={`/admin/products/${product.id}`}
                            className="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700"
                          >
                            View Product
                            <ExternalLink size={14} />
                          </Link>
                        ) : (
                          <span className="text-sm text-gray-400">No access</span>
                        )}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </section>
        </div>

        <div className="space-y-6">
          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Bank Details</h2>
            <div className="mt-4 grid gap-4">
              {field("Account Type", bankDetail.account_type)}
              {field("Account Holder", bankDetail.account_holder_name)}
              {field("Account Number", bankDetail.account_number)}
              {field("IFSC Code", bankDetail.ifsc_code)}
              {field("Linked Mobile", bankDetail.linked_mobile)}
            </div>
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Uploads & Media</h2>
            {mediaItems.length ? (
              <div className="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                {mediaItems.map((item, index) => mediaCard(item.url, item.label, `${item.label}-${index}`))}
              </div>
            ) : (
              <div className="mt-4 text-sm text-gray-500 dark:text-gray-400">No uploaded media found.</div>
            )}
          </section>

          <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
            <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Certifications & Notes</h2>
            <div className="mt-4 grid gap-4">
              {field("Standard Certification", businessProfile.standard_certification)}
              {field("Membership / Affiliations", businessProfile.membership_affiliations)}
              {field("Awards / Recognition", businessProfile.award_recognition)}
              {field("Payment Modes", (businessProfile.payment_modes || []).join(", "))}
            </div>
          </section>
        </div>
      </div>

      {showReject ? (
        <div className="fixed inset-0 z-50 bg-black/40 p-4 flex items-center justify-center">
          <div className="w-full max-w-lg rounded-2xl border bg-white dark:bg-gray-800 shadow-xl">
            <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
              <div>
                <h3 className="font-semibold text-gray-900 dark:text-white">Reject Seller</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  {businessProfile.company_name || seller.name}
                </p>
              </div>
              <button className="text-sm text-gray-500 hover:text-gray-700" onClick={() => setShowReject(false)}>
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
                <button className="rounded-xl border px-4 py-2 text-sm font-semibold" onClick={() => setShowReject(false)}>
                  Cancel
                </button>
                <button
                  onClick={handleReject}
                  disabled={processing}
                  className="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700 disabled:opacity-60"
                >
                  {processing ? "Rejecting..." : "Reject Seller"}
                </button>
              </div>
            </div>
          </div>
        </div>
      ) : null}

      {showEditModal ? (
        <div className="fixed inset-0 z-50 bg-black/40 p-4 flex items-center justify-center overflow-y-auto">
          <div className="w-full max-w-3xl rounded-2xl border bg-white dark:bg-gray-800 shadow-xl my-8">
            <div className="px-5 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-white dark:bg-gray-800 rounded-t-2xl">
              <div>
                <h3 className="font-semibold text-lg text-gray-900 dark:text-white">Edit Seller Details</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">
                  Modify contact details, alternate contacts, company details, and status.
                </p>
              </div>
              <button
                className="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white font-semibold"
                onClick={() => setShowEditModal(false)}
              >
                Close
              </button>
            </div>

            <form onSubmit={handleSaveEdit} className="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
              {/* Contact Person Details */}
              <div>
                <h4 className="text-sm font-bold text-gray-700 dark:text-gray-300 border-b pb-2 mb-4">
                  Contact Person Details
                </h4>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Name <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      required
                      value={editForm.name}
                      onChange={(e) => setEditForm({ ...editForm, name: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Email
                    </label>
                    <input
                      type="email"
                      value={editForm.email}
                      onChange={(e) => setEditForm({ ...editForm, email: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Phone <span className="text-red-500">*</span>
                    </label>
                    <input
                      type="text"
                      required
                      value={editForm.phone}
                      onChange={(e) => setEditForm({ ...editForm, phone: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Designation
                    </label>
                    <input
                      type="text"
                      value={editForm.designation}
                      onChange={(e) => setEditForm({ ...editForm, designation: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                </div>
              </div>

              {/* Alternate Contacts */}
              <div>
                <h4 className="text-sm font-bold text-gray-700 dark:text-gray-300 border-b pb-2 mb-4">
                  Alternate Contacts
                </h4>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Alternate Email
                    </label>
                    <input
                      type="email"
                      value={editForm.alternate_email}
                      onChange={(e) => setEditForm({ ...editForm, alternate_email: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Office Contact
                    </label>
                    <input
                      type="text"
                      value={editForm.office_contact}
                      onChange={(e) => setEditForm({ ...editForm, office_contact: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Alternate Mobile
                    </label>
                    <input
                      type="text"
                      value={editForm.alternate_mobile}
                      onChange={(e) => setEditForm({ ...editForm, alternate_mobile: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      WhatsApp Number
                    </label>
                    <input
                      type="text"
                      value={editForm.whatsapp_number}
                      onChange={(e) => setEditForm({ ...editForm, whatsapp_number: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                </div>
              </div>

              {/* Company Details */}
              <div>
                <h4 className="text-sm font-bold text-gray-700 dark:text-gray-300 border-b pb-2 mb-4">
                  Company Details
                </h4>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div className="sm:col-span-2">
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Company Name
                    </label>
                    <input
                      type="text"
                      value={editForm.company_name}
                      onChange={(e) => setEditForm({ ...editForm, company_name: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Company Phone
                    </label>
                    <input
                      type="text"
                      value={editForm.company_phone}
                      onChange={(e) => setEditForm({ ...editForm, company_phone: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Tax ID Type
                    </label>
                    <select
                      value={editForm.tax_id_type}
                      onChange={(e) => setEditForm({ ...editForm, tax_id_type: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    >
                      <option value="GSTIN">GSTIN</option>
                      <option value="PAN">PAN</option>
                      <option value="VAT">VAT</option>
                      <option value="TIN">TIN</option>
                      <option value="EIN">EIN</option>
                      <option value="Other">Other</option>
                    </select>
                  </div>
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Tax ID Number
                    </label>
                    <input
                      type="text"
                      value={editForm.tax_id_number}
                      onChange={(e) => setEditForm({ ...editForm, tax_id_number: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                  <div className="sm:col-span-2">
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Company Address
                    </label>
                    <input
                      type="text"
                      value={editForm.company_address}
                      onChange={(e) => setEditForm({ ...editForm, company_address: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    />
                  </div>
                </div>
              </div>

              {/* Status & Rejection */}
              <div>
                <h4 className="text-sm font-bold text-gray-700 dark:text-gray-300 border-b pb-2 mb-4">
                  Seller Status
                </h4>
                <div className="grid gap-4 sm:grid-cols-2">
                  <div>
                    <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                      Status <span className="text-red-500">*</span>
                    </label>
                    <select
                      value={editForm.seller_status}
                      onChange={(e) => setEditForm({ ...editForm, seller_status: e.target.value })}
                      className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                    >
                      <option value="none">None</option>
                      <option value="pending">Pending</option>
                      <option value="approved">Approved</option>
                      <option value="rejected">Rejected</option>
                    </select>
                  </div>
                  {editForm.seller_status === "rejected" && (
                    <div className="sm:col-span-2">
                      <label className="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">
                        Rejection Reason
                      </label>
                      <textarea
                        rows={2}
                        value={editForm.seller_rejection_reason}
                        onChange={(e) => setEditForm({ ...editForm, seller_rejection_reason: e.target.value })}
                        placeholder="Enter rejection reason..."
                        className="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-4 py-2 text-sm text-gray-900 dark:text-white"
                      />
                    </div>
                  )}
                </div>
              </div>

              <div className="flex justify-end gap-2 sticky bottom-0 bg-white dark:bg-gray-800 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button
                  type="button"
                  className="rounded-xl border px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700"
                  onClick={() => setShowEditModal(false)}
                  disabled={processing}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
                  disabled={processing}
                >
                  {processing ? "Saving..." : "Save Changes"}
                </button>
              </div>
            </form>
          </div>
        </div>
      ) : null}
    </div>
  );
}
