
import {
    createContext,
    useContext,
    useEffect,
    useState,
} from "react";

import {
    iniciarSesion,
    obtenerPerfil,
    cerrarSesion,
} from "../services/auth";

const AuthContext = createContext(null);

const TOKEN_KEY = "rincon_gamer_token";

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        async function cargarUsuario() {
            const token = localStorage.getItem(TOKEN_KEY);

            if (!token) {
                setLoading(false);
                return;
            }

            try {
                const perfil = await obtenerPerfil();
                setUser(perfil);
            } catch {
                localStorage.removeItem(TOKEN_KEY);
                setUser(null);
            } finally {
                setLoading(false);
            }
        }

        cargarUsuario();
    }, []);

    async function login(email, password) {
        const respuesta = await iniciarSesion(email, password);

        if (!respuesta.token) {
            throw new Error("Laravel no devolvió un token.");
        }

        localStorage.setItem(TOKEN_KEY, respuesta.token);

        try {
            const perfil = await obtenerPerfil();
            setUser(perfil);
            return perfil;
        } catch (error) {
            localStorage.removeItem(TOKEN_KEY);
            setUser(null);
            throw error;
        }
    }

    async function logout() {
        try {
            await cerrarSesion();
        } finally {
            localStorage.removeItem(TOKEN_KEY);
            setUser(null);
        }
    }

    return (
        <AuthContext.Provider
            value={{
                user,
                loading,
                login,
                logout,
                isAuthenticated: Boolean(user),
            }}
        >
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error(
            "useAuth debe utilizarse dentro de AuthProvider"
        );
    }

    return context;
}
