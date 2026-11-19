
import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export default function ProtectedRoute() {
    const { loading, isAuthenticated } = useAuth();

    if (loading) {
        return <p className="auth-loading">Cargando sesión...</p>;
    }

    return isAuthenticated
        ? <Outlet />
        : <Navigate to="/login" replace />;
}
