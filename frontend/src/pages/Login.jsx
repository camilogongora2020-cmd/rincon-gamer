
import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";

export default function Login() {
    const navigate = useNavigate();
    const { login } = useAuth();

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const [enviando, setEnviando] = useState(false);

    async function handleSubmit(e) {
        e.preventDefault();
        setError("");
        setEnviando(true);

        try {
            await login(email, password);
            navigate("/", { replace: true });
        } catch (err) {
            if (err.response?.status === 401) {
                setError("Correo o contraseña incorrectos.");
            } else {
                setError(
                    err.response?.data?.message ||
                    err.message ||
                    "No se pudo iniciar sesión."
                );
            }
        } finally {
            setEnviando(false);
        }
    }

    return (
        <main className="auth-page">
            <section className="auth-card">
                <h1>Rincón Gamer</h1>
                <p>Inicia sesión para entrar a tu comunidad.</p>

                <form onSubmit={handleSubmit}>
                    <label htmlFor="email">Correo electrónico</label>
                    <input
                        id="email"
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="correo@ejemplo.com"
                        required
                    />

                    <label htmlFor="password">Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        placeholder="Tu contraseña"
                        required
                    />

                    {error && <p className="auth-error">{error}</p>}

                    <button type="submit" disabled={enviando}>
                        {enviando ? "Ingresando..." : "Iniciar sesión"}
                    </button>
                </form>

                <p className="auth-footer">
                    ¿No tienes cuenta?{" "}
                    <Link to="/registro">Regístrate</Link>
                </p>
            </section>
        </main>
    );
}
