
import { NavLink, Outlet, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export default function Layout() {
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    async function handleLogout() {
        try {
            await logout();
        } catch (error) {
            console.error("Error al cerrar sesión:", error);
        } finally {
            navigate("/login", { replace: true });
        }
    }

    return (
        <div className="app-layout">
            <aside className="app-sidebar">
                <div className="sidebar-brand">🎮 Rincón Gamer</div>

                <nav className="sidebar-nav">
                    <NavLink to="/" end>
                        Inicio
                    </NavLink>

                    <NavLink to="/comunidades">
                        Comunidades
                    </NavLink>

                    <NavLink to="/guias">
                        Guías
                    </NavLink>

                    <NavLink to="/perfil">
                        Mi perfil
                    </NavLink>
                </nav>

                <div className="sidebar-user">
                    <span>{user?.name}</span>
                    <button onClick={handleLogout}>
                        Cerrar sesión
                    </button>
                </div>
            </aside>

            <main className="app-main">
                <Outlet />
            </main>
        </div>
    );
}
