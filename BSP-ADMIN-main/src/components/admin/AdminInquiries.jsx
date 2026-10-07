import React, { useEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import {
  fetchAdminInquiries,
  fetchAdminInquiry,
  updateInquiryNotes,
  fetchInquiryDistributeOptions,
  distributeInquiry,
} from "../../services/api";
import { Phone, Mail, MessageSquare, Share2, Save, Search } from "lucide-react";

const statusOptions = [
  { label: "All", value: "" },
  { label: "New", value: "new" },
  { label: "Contacted", value: "contacted" },
  { label: "Closed", value: "closed" },
];

export default function AdminInquiries() {
  const [filters, setFilters] = useState({
    q: "",
    status: "",
    country: "",
    city: "",
    seller: "",
  });

  const [loading, setLoading] = useState(false);
  const [items, setItems] = useState([]);
  const [pageMeta, setPageMeta] = useState({ current_page: 1, last_page: 1, total: 0 });

  const [detailLoading, setDetailLoading] = useState(false);
  const [selectedId, setSelectedId] = useState(null);
  const [detail, setDetail] = useState(null);

  // New features state
  const [adminNotes, setAdminNotes] = useState("");
  const [savingNotes, setSavingNotes] = useState(false);
  const [showDistribute, setShowDistribute] = useState(false);
  const [distributeLoading, setDistributeLoading] = useState(false);
  const [distributeSellers, setDistributeSellers] = useState([]);
  const [selectedSellerIds, setSelectedSellerIds] = useState([]);
  const [distributeSearch, setDistributeSearch] = useState("");
  const [distributing, setDistributing] = useState(false);

  const canPrev = useMemo(() => pageMeta.current_page > 1, [pageMeta]);
  const canNext = useMemo(() => pageMeta.current_page < pageMeta.last_page, [pageMeta]);

  const loadRows = async (page = 1) => {
    setLoading(true);
    try {
      const res = await fetchAdminInquiries({ ...filters, page });
      const payload = res.data || {};
      setItems(payload.data || []);
      setPageMeta({
        current_page: payload.current_page || 1,
        last_page: payload.last_page || 1,
        total: payload.total || 0,
      });
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to load enquiries");
    } finally {
      setLoading(false);
    }
  };

  const openDetail = async (id) => {
    setSelectedId(id);
    setDetail(null);
    setDetailLoading(true);
    setAdminNotes("");
    setShowDistribute(false);
    try {
      const res = await fetchAdminInquiry(id);
      const data = res.data || null;
      setDetail(data);
      setAdminNotes(data?.admin_notes || "");
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to load enquiry details");
    } finally {
      setDetailLoading(false);
    }
  };

  const handleSaveNotes = async () => {
    if (!detail?.id) return;
    setSavingNotes(true);
    try {
      await updateInquiryNotes(detail.id, adminNotes);
      toast.success("Notes saved successfully");
      setDetail((prev) => (prev ? { ...prev, admin_notes: adminNotes } : null));
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to save notes");
    } finally {
      setSavingNotes(false);
    }
  };

  const handleToggleDistribute = async () => {
    if (showDistribute) {
      setShowDistribute(false);
      return;
    }

    setShowDistribute(true);
    setDistributeLoading(true);
    setSelectedSellerIds([]);
    setDistributeSearch("");
    try {
      const res = await fetchInquiryDistributeOptions(detail.id);
      setDistributeSellers(res.data || []);
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to load distribution options");
      setShowDistribute(false);
    } finally {
      setDistributeLoading(false);
    }
  };

  const handleDistributeInquiry = async () => {
    if (selectedSellerIds.length === 0) return;
    setDistributing(true);
    try {
      const res = await distributeInquiry(detail.id, selectedSellerIds);
      toast.success(res.data?.message || "Enquiry distributed successfully");
      setShowDistribute(false);
      setSelectedSellerIds([]);
      loadRows(pageMeta.current_page);
    } catch (e) {
      toast.error(e?.response?.data?.message || "Failed to distribute enquiry");
    } finally {
      setDistributing(false);
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
    const next = { q: "", status: "", country: "", city: "", seller: "" };
    setFilters(next);
    setTimeout(() => loadRows(1), 0);
  };

  return (
    <div className="p-6">
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 className="text-2xl font-semibold text-gray-900 dark:text-white">Product Enquiries</h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">
            Review product enquiry submissions from buyers and guests. Total: {pageMeta.total}
          </p>
        </div>
      </div>

      <div className="mt-6 rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
        <div className="grid grid-cols-1 md:grid-cols-5 gap-3">
          <input
            type="text"
            placeholder="Search buyer / product / message"
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
            value={filters.q}
            onChange={(e) => setFilters((s) => ({ ...s, q: e.target.value }))}
          />

          <select
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
            value={filters.status}
            onChange={(e) => setFilters((s) => ({ ...s, status: e.target.value }))}
          >
            {statusOptions.map((o) => (
              <option key={o.value} value={o.value}>
                {o.label}
              </option>
            ))}
          </select>

          <input
            type="text"
            placeholder="Country code (IN, AE, US)"
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
            value={filters.country}
            onChange={(e) => setFilters((s) => ({ ...s, country: e.target.value.toUpperCase() }))}
          />

          <input
            type="text"
            placeholder="Seller city"
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
            value={filters.city}
            onChange={(e) => setFilters((s) => ({ ...s, city: e.target.value }))}
          />

          <input
            type="text"
            placeholder="Seller / company"
            className="rounded-md border border-gray-200 bg-white px-3 py-2 text-sm text-gray-900 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
            value={filters.seller}
            onChange={(e) => setFilters((s) => ({ ...s, seller: e.target.value }))}
          />
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
            {loading ? "Loading..." : `${items.length} enquiries on this page`}
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
                <th className="px-4 py-3">Buyer</th>
                <th className="px-4 py-3">Product</th>
                <th className="px-4 py-3">Seller</th>
                <th className="px-4 py-3">Location</th>
                <th className="px-4 py-3">Status</th>
                <th className="px-4 py-3">Submitted</th>
                <th className="px-4 py-3">Action</th>
              </tr>
            </thead>
            <tbody>
              {!loading && items.length === 0 ? (
                <tr>
                  <td className="px-4 py-6 text-gray-500 dark:text-gray-400" colSpan={7}>
                    No enquiries found.
                  </td>
                </tr>
              ) : (
                items.map((row) => (
                  <tr key={row.id} className="border-t border-gray-100 dark:border-gray-700">
                    <td className="px-4 py-3">
                      <div className="font-medium text-gray-900">{row.buyer_name || "—"}</div>
                      <div className="text-gray-500 text-xs">{row.buyer_email || row.buyer_phone || "—"}</div>
                    </td>
                    <td className="px-4 py-3">
                      <div className="font-medium">{row.product_name || "—"}</div>
                      <div className="text-gray-500 text-xs">{row.product_country_code || "—"}</div>
                    </td>
                    <td className="px-4 py-3">
                      <div className="font-medium">{row.seller_company_name || row.seller_name || "—"}</div>
                      <div className="text-gray-500 text-xs">{row.seller_email || "—"}</div>
                    </td>
                    <td className="px-4 py-3 text-gray-600 dark:text-gray-400">
                      {[row.seller_city_name, row.seller_state_name, row.seller_country_code].filter(Boolean).join(", ") || "—"}
                    </td>
                    <td className="px-4 py-3">
                      <span className="rounded-full border border-gray-200 bg-gray-50 px-2 py-1 text-xs text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        {row.status || "new"}
                      </span>
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
        <div className="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
          <div className="w-full max-w-2xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-gray-800 border border-gray-100 dark:border-gray-700 flex flex-col max-h-[90vh]">
            
            {/* Header */}
            <div className="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20">
              <div>
                <h2 className="text-lg font-bold text-gray-900 dark:text-white">Enquiry Detail</h2>
                <p className="text-xs text-gray-500 dark:text-gray-400">ID: {selectedId}</p>
              </div>
              <button
                className="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 transition"
                onClick={() => {
                  setSelectedId(null);
                  setDetail(null);
                }}
              >
                Close
              </button>
            </div>

            {/* Scrollable Body */}
            <div className="p-6 overflow-y-auto space-y-6 flex-1 text-sm custom-scrollbar">
              {detailLoading ? (
                <div className="flex items-center justify-center py-12 text-gray-500 dark:text-gray-400">
                  <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                  <span>Loading details...</span>
                </div>
              ) : !detail ? (
                <div className="text-red-500 py-8 text-center font-medium">Failed to load enquiry details.</div>
              ) : (
                <div className="space-y-6">
                  
                  {/* Grid of Product, Seller, and Info */}
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    {/* Product Card */}
                    <div className="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                      <div className="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Product Information</div>
                      <div className="font-semibold text-gray-900 dark:text-white text-base">{detail.product?.name || "—"}</div>
                      <div className="text-xs text-gray-500 mt-1">Country of Origin: <span className="font-medium text-gray-700 dark:text-gray-300">{detail.product?.country_code || "Global"}</span></div>
                    </div>

                    {/* Original Seller Card */}
                    <div className="p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700">
                      <div className="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Original Target Seller</div>
                      <div className="font-semibold text-gray-900 dark:text-white text-base">{detail.seller?.company_name || detail.seller?.name || "—"}</div>
                      <div className="text-xs text-gray-500 mt-1">Contact Name: <span className="text-gray-700 dark:text-gray-300">{detail.seller?.name || "—"}</span></div>
                      <div className="text-xs text-gray-400 mt-0.5">{detail.seller?.email || "—"}</div>
                      <div className="text-xs text-gray-400 mt-0.5">
                        {[detail.seller?.city_name, detail.seller?.state_name, detail.seller?.country_code].filter(Boolean).join(", ") || "No location data"}
                      </div>
                    </div>

                  </div>

                  {/* Buyer Contact Details with Quick Buttons */}
                  <div className="p-4 rounded-xl bg-gradient-to-r from-blue-50/50 to-indigo-50/30 dark:from-blue-900/10 dark:to-indigo-900/5 border border-blue-100/50 dark:border-blue-900/30">
                    <div className="text-xs font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 mb-3">Buyer Contact Profile</div>
                    
                    <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                      <div className="space-y-1">
                        <div className="font-bold text-gray-900 dark:text-white text-base">{detail.buyer?.name || "—"}</div>
                        <div className="text-xs text-gray-600 dark:text-gray-300">{detail.buyer?.email || "—"}</div>
                        <div className="text-xs text-gray-600 dark:text-gray-300 font-mono">{detail.buyer?.phone || "—"}</div>
                      </div>

                      {/* Quick Buttons */}
                      <div className="flex flex-wrap gap-2 items-center">
                        {detail.buyer?.phone && (
                          <>
                            <a
                              href={`tel:${detail.buyer.phone}`}
                              className="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm shadow-blue-500/10 transition"
                              title="Direct Call"
                            >
                              <Phone size={13} />
                              <span>Call</span>
                            </a>
                            <a
                              href={`https://wa.me/${detail.buyer.phone.replace(/[^0-9]/g, "")}`}
                              target="_blank"
                              rel="noreferrer"
                              className="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm shadow-emerald-500/10 transition"
                              title="Chat on WhatsApp"
                            >
                              <MessageSquare size={13} />
                              <span>WhatsApp</span>
                            </a>
                          </>
                        )}
                        {detail.buyer?.email && (
                          <a
                            href={`mailto:${detail.buyer.email}`}
                            className="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg text-white bg-violet-600 hover:bg-violet-700 shadow-sm shadow-violet-500/10 transition"
                            title="Send Email"
                          >
                            <Mail size={13} />
                            <span>Email</span>
                          </a>
                        )}
                      </div>
                    </div>
                  </div>

                  {/* Enquiry Status & Message */}
                  <div className="space-y-3">
                    <div className="flex items-center justify-between">
                      <div className="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Enquiry Message</div>
                      <span className="rounded-full px-2.5 py-0.5 text-xs font-bold uppercase border border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-900/50 dark:bg-blue-900/20 dark:text-blue-300">
                        Status: {detail.status || "new"}
                      </span>
                    </div>
                    <div className="p-4 rounded-xl bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700 text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed shadow-inner">
                      {detail.message || "No message supplied."}
                    </div>
                    <div className="text-xs text-gray-400 dark:text-gray-500">
                      Submitted on: {detail.created_at ? new Date(detail.created_at).toLocaleString() : "—"}
                    </div>
                  </div>

                  {/* Central Enquiry Distribution Section */}
                  <div className="border-t border-gray-100 dark:border-gray-700 pt-5">
                    <div className="flex items-center justify-between mb-3">
                      <div>
                        <div className="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Central Enquiry Distribution</div>
                        <p className="text-xs text-gray-500 mt-0.5">Forward this enquiry directly to other matching sellers in the same category.</p>
                      </div>
                      <button
                        type="button"
                        onClick={handleToggleDistribute}
                        className={`inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg border transition ${
                          showDistribute 
                            ? "bg-gray-100 border-gray-300 text-gray-800 dark:bg-gray-700 dark:border-gray-600 dark:text-white" 
                            : "bg-blue-50 border-blue-200 text-blue-700 hover:bg-blue-100/50 dark:bg-blue-900/20 dark:border-blue-950 dark:text-blue-300 dark:hover:bg-blue-900/30"
                        }`}
                      >
                        <Share2 size={13} />
                        {showDistribute ? "Hide Distribution" : "Distribute enquiry"}
                      </button>
                    </div>

                    {showDistribute && (
                      <div className="mt-3 p-4 rounded-xl bg-gray-50 dark:bg-gray-900/60 border border-gray-100 dark:border-gray-700/80 space-y-3 animate-fadeIn">
                        {distributeLoading ? (
                          <div className="flex items-center justify-center py-6 text-gray-500 dark:text-gray-400 gap-2">
                            <div className="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                            <span className="text-xs">Finding matching approved sellers...</span>
                          </div>
                        ) : distributeSellers.length === 0 ? (
                          <div className="text-xs text-gray-500 text-center py-4">
                            No other approved sellers found for this product's subcategory or category.
                          </div>
                        ) : (
                          <>
                            <div className="flex flex-col sm:flex-row gap-2">
                              <div className="relative flex-1">
                                <Search size={14} className="absolute left-3 top-2.5 text-gray-400" />
                                <input
                                  type="text"
                                  placeholder="Search sellers by name, company, or city..."
                                  className="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-200 bg-white text-xs text-gray-950 dark:border-gray-700 dark:bg-gray-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-blue-500"
                                  value={distributeSearch}
                                  onChange={(e) => setDistributeSearch(e.target.value)}
                                />
                              </div>
                              <button
                                type="button"
                                onClick={() => {
                                  const filtered = distributeSellers.filter(s => {
                                    const searchStr = `${s.name} ${s.company_name} ${s.city_name} ${s.state_name} ${s.country_code}`.toLowerCase();
                                    return searchStr.includes(distributeSearch.toLowerCase());
                                  });
                                  const allSelected = filtered.every(s => selectedSellerIds.includes(s.id));
                                  if (allSelected) {
                                    const filteredIds = filtered.map(s => s.id);
                                    setSelectedSellerIds(prev => prev.filter(id => !filteredIds.includes(id)));
                                  } else {
                                    const filteredIds = filtered.map(s => s.id);
                                    setSelectedSellerIds(prev => [...new Set([...prev, ...filteredIds])]);
                                  }
                                }}
                                className="px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                              >
                                Toggle Page Select
                              </button>
                            </div>

                            <div className="max-h-[180px] overflow-y-auto border border-gray-200 dark:border-gray-850 rounded-lg bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-750 custom-scrollbar">
                              {distributeSellers
                                .filter(s => {
                                  const searchStr = `${s.name} ${s.company_name} ${s.city_name} ${s.state_name} ${s.country_code}`.toLowerCase();
                                  return searchStr.includes(distributeSearch.toLowerCase());
                                })
                                .map(s => {
                                  const isChecked = selectedSellerIds.includes(s.id);
                                  return (
                                    <label
                                      key={s.id}
                                      className="flex items-start gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-900/30 cursor-pointer transition select-none"
                                    >
                                      <input
                                        type="checkbox"
                                        className="mt-0.5 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        checked={isChecked}
                                        onChange={() => {
                                          if (isChecked) {
                                            setSelectedSellerIds(prev => prev.filter(id => id !== s.id));
                                          } else {
                                            setSelectedSellerIds(prev => [...prev, s.id]);
                                          }
                                        }}
                                      />
                                      <div className="text-xs">
                                        <div className="font-bold text-gray-900 dark:text-white">
                                          {s.company_name || s.name}
                                        </div>
                                        {s.company_name && (
                                          <div className="text-gray-500 dark:text-gray-400 font-medium">Contact: {s.name}</div>
                                        )}
                                        <div className="text-gray-400 mt-0.5 font-medium">
                                          {[s.city_name, s.state_name, s.country_code].filter(Boolean).join(", ") || "No location info"}
                                        </div>
                                      </div>
                                    </label>
                                  );
                                })}
                            </div>

                            <div className="flex items-center justify-between border-t border-gray-100 dark:border-gray-700 pt-3">
                              <div className="text-xs font-semibold text-gray-500 dark:text-gray-400">
                                {selectedSellerIds.length} sellers selected
                              </div>
                              <button
                                type="button"
                                onClick={handleDistributeInquiry}
                                disabled={selectedSellerIds.length === 0 || distributing}
                                className="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition shadow-md shadow-blue-500/10"
                              >
                                {distributing ? "Distributing..." : "Confirm Distribution"}
                              </button>
                            </div>
                          </>
                        )}
                      </div>
                    )}
                  </div>

                  {/* Internal Notes Section */}
                  <div className="border-t border-gray-100 dark:border-gray-700 pt-5">
                    <div className="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Internal Staff Notes</div>
                    <div className="space-y-2">
                      <textarea
                        className="w-full min-h-[90px] rounded-lg border border-gray-200 bg-white p-3 text-xs text-gray-950 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-gray-750 dark:bg-gray-900 dark:text-white placeholder:text-gray-400 dark:placeholder:text-gray-500"
                        placeholder="Add internal remarks, staff follow-up history, or private comments about this enquiry..."
                        value={adminNotes}
                        onChange={(e) => setAdminNotes(e.target.value)}
                      />
                      <div className="flex justify-end">
                        <button
                          type="button"
                          onClick={handleSaveNotes}
                          disabled={savingNotes}
                          className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 transition shadow-md shadow-blue-500/10"
                        >
                          <Save size={13} />
                          {savingNotes ? "Saving..." : "Save Notes"}
                        </button>
                      </div>
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
