
import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { registrar } from "../services/auth";

export default function Register() {
    const navigate = useNavigate();

    const [name, setName] = useState("");
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [error, setError] = useState("");
    const [enviando, setEnviando] = useState(false);

    async function handleSubmit(e) {
        e.preventDefault();
        setError("");
        setEnviando(true);

        try {
            await registrar({ name, email, password });
            navigate("/login", { replace: true });
        } catch (err) {
            const errores = err.response?.data?.errors;

            if (errores) {
                setError(
                    Object.values(errores).flat().join(" ")
                );
            } else {
                setError(
                    err.response?.data?.message ||
                    "No se pudo completar el registro."
                );
            }
        } finally {
            setEnviando(false);
        }
    }

    return (
        <main className="auth-page">
            <section className="auth-card">
                <h1>Crear cuenta</h1>
                <p>Únete a Rincón Gamer.</p>

                <form onSubmit={handleSubmit}>
                    <label htmlFor="name">Nombre de usuario</label>
                    <input
                        id="name"
                        type="text"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        required
                    />

                    <label htmlFor="email">Correo electrónico</label>
                    <input
                        id="email"
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        required
                    />

                    <label htmlFor="password">Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                        minLength={8}
                        required
                    />

                    {error && <p className="auth-error">{error}</p>}

                    <button type="submit" disabled={enviando}>
                        {enviando ? "Registrando..." : "Crear cuenta"}
                    </button>
                </form>

                <p className="auth-footer">
                    ¿Ya tienes cuenta?{" "}
                    <Link to="/login">Inicia sesión</Link>
                </p>
            </section>
        </main>
    );
}
