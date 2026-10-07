import { useEffect, useState } from "react";
import { Navigate, Outlet } from "react-router-dom";
import useAuthStore from "../store/authStore";
import { fetchAdminMe } from "../services/api";

const ProtectedRoute = () => {
    const { isAuthenticated, setUser, logout } = useAuthStore();
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        let mounted = true;

        const refreshAdmin = async () => {
            if (!isAuthenticated) {
                if (mounted) setLoading(false);
                return;
            }

            try {
                const response = await fetchAdminMe();
                if (mounted) {
                    setUser(response.data?.admin || null);
                }
            } catch (error) {
                if (mounted) {
                    logout();
                }
            } finally {
                if (mounted) {
                    setLoading(false);
                }
            }
        };

        refreshAdmin();

        return () => {
            mounted = false;
        };
    }, [isAuthenticated, logout, setUser]);

    if (loading) {
        return <div className="flex min-h-screen items-center justify-center bg-gray-50 text-sm font-semibold text-gray-500">Loading admin panel...</div>;
    }

    return isAuthenticated ? <Outlet /> : <Navigate to="/" replace />;
};

export default ProtectedRoute;
