import { Navigate, Outlet } from "react-router-dom";
import useAuthStore from "../store/authStore";

export default function PermissionRoute({ permission, fallback = "/admin/profile" }) {
  const hasPermission = useAuthStore((state) => state.hasPermission);

  return hasPermission(permission) ? <Outlet /> : <Navigate to={fallback} replace />;
}
