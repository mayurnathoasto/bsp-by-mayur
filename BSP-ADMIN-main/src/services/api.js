
import axios from "axios";
import useAuthStore from "../store/authStore";

const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || "http://127.0.0.1:8000/api",
});

api.interceptors.request.use((config) => {
  const token = localStorage.getItem("accessToken");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  config.headers.Accept = "application/json";
  return config;
});

api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      useAuthStore.getState().logout();

      if (typeof window !== "undefined" && window.location.pathname !== "/") {
        window.location.href = "/";
      }
    }

    return Promise.reject(error);
  }
);

export const getCloudinarySignature = async (
  folder = "buyer-seller-platform/admin/categories"
) => {
  const res = await api.post("/admin/cloudinary/sign", { folder });
  return res.data;
};

export const getBuyers = (params) => api.get("/admin/buyers", { params });

export const logoutAdmin = () => api.post("/admin/logout");
export const fetchAdminMe = () => api.get("/admin/me");

export default api;

export const uploadAdminFile = async (file, folder = 'categories') => {
  const formData = new FormData();
  formData.append('file', file);
  formData.append('folder', folder);

  const response = await api.post('/admin/upload', formData, {
    headers: {
      'Content-Type': 'multipart/form-data',
      Accept: 'application/json',
    },
  });

  return response.data;
};

export const fetchAdminInquiries = (params = {}) =>
  api.get("/admin/inquiries", { params });

export const fetchAdminInquiry = (id) =>
  api.get(`/admin/inquiries/${id}`);

export const updateInquiryNotes = (id, notes) =>
  api.put(`/admin/inquiries/${id}/notes`, { notes });

export const fetchInquiryDistributeOptions = (id) =>
  api.get(`/admin/inquiries/${id}/distribute-options`);

export const distributeInquiry = (id, sellerIds) =>
  api.post(`/admin/inquiries/${id}/distribute`, { seller_ids: sellerIds });

export const fetchAdminRequirements = (params = {}) =>
  api.get("/admin/requirements", { params });

export const fetchAdminRequirement = (id) =>
  api.get(`/admin/requirements/${id}`);

export const fetchAdminProducts = (params = {}) =>
  api.get("/admin/products", { params });

export const exportAdminProducts = (params = {}) =>
  api.get("/admin/products/export", {
    params,
    responseType: "blob",
  });

export const importAdminProducts = (file) => {
  const formData = new FormData();
  formData.append("file", file);
  return api.post("/admin/products/import", formData, {
    headers: {
      "Content-Type": "multipart/form-data",
    },
  });
};

export const fetchAdminProduct = (id) =>
  api.get(`/admin/products/${id}`);

export const approveAdminProduct = (id) =>
  api.post(`/admin/products/${id}/approve`);

export const rejectAdminProduct = (id, payload = {}) =>
  api.post(`/admin/products/${id}/reject`, payload);

export const deleteAdminProduct = (id) =>
  api.delete(`/admin/products/${id}`);

export const fetchAdminSellers = (params = {}) =>
  api.get("/admin/sellers", { params });

export const fetchAdminSeller = (id) =>
  api.get(`/admin/sellers/${id}`);

export const approveAdminSeller = (id) =>
  api.post(`/admin/sellers/${id}/approve`);

export const rejectAdminSeller = (id, payload = {}) =>
  api.post(`/admin/sellers/${id}/reject`, payload);

export const deleteAdminSeller = (id) =>
  api.delete(`/admin/sellers/${id}`);

export const updateAdminSeller = (id, payload = {}) =>
  api.put(`/admin/sellers/${id}`, payload);

export const fetchAdminStaff = () =>
  api.get("/admin/staff");

export const createAdminStaff = (payload) =>
  api.post("/admin/staff", payload);

export const updateAdminStaff = (id, payload) =>
  api.put(`/admin/staff/${id}`, payload);

export const deleteAdminStaff = (id) =>
  api.delete(`/admin/staff/${id}`);

export const fetchAdminNotifications = (params = {}) =>
  api.get("/admin/notifications", { params });

export const createAdminNotification = (payload) =>
  api.post("/admin/notifications", payload);

export const fetchAdminSupportCases = (params = {}) =>
  api.get("/admin/support-cases", { params });

export const fetchAdminSupportCase = (id) =>
  api.get(`/admin/support-cases/${id}`);

export const replyAdminSupportCase = (id, payload) =>
  api.post(`/admin/support-cases/${id}/reply`, payload);
