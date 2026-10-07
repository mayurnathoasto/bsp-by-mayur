import { Outlet, Link, useLocation } from "react-router-dom";
import {
  Users,
  User,
  Store,
  Package,
  LogOut,
  Sun,
  Moon,
  ChevronLeft,
  ChevronRight,
  Menu,
  X,
  Shapes,
  MessageSquare,
  ClipboardList,
  Bell,
} from 'lucide-react';
import useAuthStore from '../../store/authStore';
import useThemeStore from '../../store/themeStore';
import { logoutAdmin } from '../../services/api';
import Logo from '../../assets/logo/bsp_logo.png';
import { useState, useEffect } from 'react';


export default function AdminLayout() {
  const location = useLocation();
  const { logout, hasPermission } = useAuthStore();
  const { theme, toggleTheme } = useThemeStore();

  const [isCollapsed, setIsCollapsed] = useState(false);
  const [isMobileOpen, setIsMobileOpen] = useState(false);

  const navItems = [
    { path: "/admin/users", label: "Buyer Management", icon: Users, permission: "buyers.view" },
    { path: "/admin/sellers", label: "Seller Management", icon: Store, permission: "sellers.view" },
    { path: "/admin/products", label: "Product Management", icon: Package, permission: "products.view" },
    {
      label: "Product Enquiries",
      path: "/admin/inquiries",
      icon: MessageSquare,
      permission: "inquiries.view",
    },
    {
      label: "Bulk Requirements",
      path: "/admin/requirements",
      icon: ClipboardList,
      permission: "requirements.view",
    },
    {
      label: "Notifications",
      path: "/admin/notifications",
      icon: Bell,
      permission: "notifications.manage",
    },
    {
      label: "Support & Reports",
      path: "/admin/support",
      icon: MessageSquare,
      permission: "support.view",
    },
    { path: "/admin/categories", label: "Categories", icon: Shapes, permission: "categories.manage" },
    { path: "/admin/staff", label: "Staff Management", icon: Users, permission: "staff.manage" },
    { path: "/admin/profile", label: "Profile", icon: User },
  ];

  const isActive = (path) => {
    if (path === "/admin" && location.pathname === "/admin") return true;
    if (path !== "/admin" && location.pathname.startsWith(path)) return true;
    return false;
  };

  useEffect(() => {
    setIsMobileOpen(false);
  }, [location.pathname]);

  const handleLogout = async () => {
    try {
      await logoutAdmin();
    } catch (error) {
      // Local logout should still proceed even if the token is already expired.
    } finally {
      logout();
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 dark:bg-gray-900 font-sans flex flex-col">
      <header className="sticky top-0 z-40 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md px-4 sm:px-6 py-3 border-b border-gray-200 dark:border-gray-700 shadow-sm flex justify-between items-center h-16">
        <div className="flex items-center gap-3">
          <Link to="/admin/users" className="flex items-center gap-2 sm:gap-3">
            <img
              src={Logo}
              alt="buyersellerplatform Logo"
              className="h-8 sm:h-10 w-auto rounded object-contain"
            />
            <span className="text-lg sm:text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600 tracking-tight">
              BSP Admin
            </span>
          </Link>
        </div>

        <div className="flex items-center gap-2 sm:gap-4">
          <button
            onClick={toggleTheme}
            className="p-2 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition"
          >
            {theme === "light" ? <Moon size={20} /> : <Sun size={20} />}
          </button>

          <div className="h-6 w-px bg-gray-200 dark:bg-gray-700 mx-1 hidden sm:block"></div>

          <button
            onClick={handleLogout}
            className="flex items-center gap-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 px-3 py-2 rounded-lg transition font-medium"
          >
            <LogOut size={18} />
            <span className="hidden sm:inline">Logout</span>
          </button>

          {/* Right-aligned mobile menu toggle */}
          <button
            onClick={() => setIsMobileOpen(!isMobileOpen)}
            className="p-2 text-gray-600 dark:text-gray-300 md:hidden hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition"
            aria-label="Toggle navigation menu"
          >
            <Menu size={24} />
          </button>
        </div>
      </header>

      <div className="flex flex-1 overflow-hidden relative">
        {isMobileOpen && (
          <div
            className="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden"
            onClick={() => setIsMobileOpen(false)}
          />
        )}

        <aside
          className={`
            fixed md:relative z-50 h-[calc(100vh-64px)] md:h-auto
            bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col 
            transition-all duration-300 ease-in-out
            ${isMobileOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'}
            ${isCollapsed ? 'md:w-20' : 'md:w-64'}
            w-64 shadow-xl md:shadow-none
          `}
        >
          <div className="hidden md:flex p-4 justify-end">
            <button
              onClick={() => setIsCollapsed(!isCollapsed)}
              className="p-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-gray-500 hover:text-blue-600 transition"
            >
              {isCollapsed ? <ChevronRight size={16} /> : <ChevronLeft size={16} />}
            </button>
          </div>

          <div className="md:hidden flex justify-between items-center p-4 border-b border-gray-100 dark:border-gray-700">
            <span className="font-semibold text-gray-900 dark:text-white">Menu</span>
            <button
              onClick={() => setIsMobileOpen(false)}
              className="p-1 text-gray-500 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
            >
              <X size={20} />
            </button>
          </div>

          <nav className="flex-1 px-3 py-4 space-y-1 overflow-y-auto overflow-x-hidden">
            {navItems.filter((item) => hasPermission(item.permission)).map((item) => {
              const Icon = item.icon;
              const active = isActive(item.path);

              return (
                <Link
                  key={item.path}
                  to={item.path}
                  title={isCollapsed ? item.label : ""}
                  className={`flex items-center gap-3 px-3 py-3 rounded-xl transition-all duration-200 font-medium text-sm whitespace-nowrap
                    ${active
                      ? "bg-blue-600 text-white shadow-blue-500/30 shadow-lg"
                      : "text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white"
                    }
                    ${isCollapsed ? 'md:justify-center' : ''}
                  `}
                >
                  <div className={`min-w-[20px] flex items-center ${isCollapsed ? 'md:justify-center' : ''}`}>
                    <Icon size={20} className={active ? "text-white" : "text-gray-400 dark:text-gray-500 transition-colors"} />
                  </div>

                  <span className={`transition-opacity duration-200 ${isCollapsed ? 'md:hidden' : 'block'}`}>
                    {item.label}
                  </span>
                </Link>
              );
            })}
          </nav>
        </aside>

        <main className="flex-1 overflow-auto p-4 sm:p-6 md:p-8 relative w-full">
          <Outlet />
        </main>
      </div>
    </div>
  );
}
