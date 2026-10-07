import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { useEffect } from 'react';
import useThemeStore from './store/themeStore';
import { Toaster } from 'react-hot-toast';
import AdminUsers from './components/admin/AdminUsers';
import AdminSellers from './components/admin/AdminSellers';
import AdminCategories from './components/admin/AdminCategories';
import AdminProfile from './components/admin/AdminProfile';
import AdminLogin from './components/admin/AdminLogin';
import AdminLayout from './components/layouts/AdminLayout';
import ProtectedRoute from './components/ProtectedRoute';
import PermissionRoute from './components/PermissionRoute';
import './App.css';
import AdminInquiries from './components/admin/AdminInquiries';
import AdminRequirements from './components/admin/AdminRequirements';
import AdminProducts from './components/admin/AdminProducts';
import AdminProductDetails from './components/admin/AdminProductDetails';
import AdminSellerDetails from './components/admin/AdminSellerDetails';
import AdminStaff from './components/admin/AdminStaff';
import AdminNotifications from './components/admin/AdminNotifications';
import AdminSupportTickets from './components/admin/AdminSupportTickets';
import useAuthStore from './store/authStore';

function AdminHomeRedirect() {
  const hasPermission = useAuthStore((state) => state.hasPermission);

  if (hasPermission("notifications.manage")) {
    return <Navigate to="/admin/notifications" replace />;
  }

  if (hasPermission("support.view")) {
    return <Navigate to="/admin/support" replace />;
  }

  if (hasPermission("products.view")) {
    return <Navigate to="/admin/products" replace />;
  }

  if (hasPermission("sellers.view")) {
    return <Navigate to="/admin/sellers" replace />;
  }

  if (hasPermission("buyers.view")) {
    return <Navigate to="/admin/users" replace />;
  }

  return <Navigate to="/admin/profile" replace />;
}

function App() {
  const { theme } = useThemeStore();

  useEffect(() => {
    document.documentElement.classList.toggle('dark', theme === 'dark');
  }, [theme]);

  return (
    <Router>
      <Toaster position="top-right" />
      <Routes>
        <Route path="/" element={<AdminLogin />} />

        <Route element={<ProtectedRoute />}>
          <Route element={<AdminLayout />}>
            <Route path="/admin" element={<AdminHomeRedirect />} />
            <Route element={<PermissionRoute permission="buyers.view" />}>
              <Route path="/admin/users" element={<AdminUsers />} />
            </Route>
            <Route element={<PermissionRoute permission="sellers.view" />}>
              <Route path="/admin/sellers" element={<AdminSellers />} />
              <Route path="/admin/sellers/:id" element={<AdminSellerDetails />} />
            </Route>
            <Route element={<PermissionRoute permission="products.view" />}>
              <Route path="/admin/products" element={<AdminProducts />} />
              <Route path="/admin/products/:id" element={<AdminProductDetails />} />
            </Route>
            <Route element={<PermissionRoute permission="inquiries.view" />}>
              <Route path="/admin/inquiries" element={<AdminInquiries />} />
            </Route>
            <Route element={<PermissionRoute permission="requirements.view" />}>
              <Route path="/admin/requirements" element={<AdminRequirements />} />
            </Route>
            <Route element={<PermissionRoute permission="notifications.manage" />}>
              <Route path="/admin/notifications" element={<AdminNotifications />} />
            </Route>
            <Route element={<PermissionRoute permission="support.view" />}>
              <Route path="/admin/support" element={<AdminSupportTickets />} />
            </Route>
            <Route element={<PermissionRoute permission="categories.manage" />}>
              <Route path="/admin/categories" element={<AdminCategories />} />
            </Route>
            <Route element={<PermissionRoute permission="staff.manage" />}>
              <Route path="/admin/staff" element={<AdminStaff />} />
            </Route>
            <Route path="/admin/profile" element={<AdminProfile />} />
          </Route>
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
