
import { useEffect, useState } from "react";
import { useAuth } from "../context/AuthContext";
import {
    actualizarPerfil,
    subirAvatar,
    obtenerPerfil,
} from "../services/auth";

export default function Profile() {
    const { user } = useAuth();

    const [perfil, setPerfil] = useState(user);
    const [name, setName] = useState(user?.name || "");
    const [email, setEmail] = useState(user?.email || "");
    const [archivo, setArchivo] = useState(null);
    const [preview, setPreview] = useState("");
    const [guardando, setGuardando] = useState(false);
    const [subiendo, setSubiendo] = useState(false);
    const [mensaje, setMensaje] = useState("");
    const [error, setError] = useState("");

    useEffect(() => {
        async function cargarPerfil() {
            try {
                const datos = await obtenerPerfil();
                setPerfil(datos);
                setName(datos.name);
                setEmail(datos.email);
            } catch (err) {
                setError(
                    err.response?.data?.message ||
                    "No se pudo cargar el perfil."
                );
            }
        }

        cargarPerfil();
    }, []);

    useEffect(() => {
        if (!archivo) {
            setPreview("");
            return;
        }

        const url = URL.createObjectURL(archivo);
        setPreview(url);

        return () => URL.revokeObjectURL(url);
    }, [archivo]);

    async function guardarDatos(e) {
        e.preventDefault();
        setError("");
        setMensaje("");
        setGuardando(true);

        try {
            const datos = await actualizarPerfil({ name, email });
            setPerfil(datos);
            setMensaje("Perfil actualizado correctamente.");
        } catch (err) {
            setError(
                err.response?.data?.message ||
                "No se pudo actualizar el perfil."
            );
        } finally {
            setGuardando(false);
        }
    }

    async function guardarAvatar() {
        if (!archivo) return;

        setError("");
        setMensaje("");
        setSubiendo(true);

        try {
            const respuesta = await subirAvatar(archivo);
            setPerfil(respuesta.user);
            setArchivo(null);
            setMensaje("Foto de perfil actualizada correctamente.");
        } catch (err) {
            const errores = err.response?.data?.errors;
            setError(
                errores
                    ? Object.values(errores).flat().join(" ")
                    : err.response?.data?.message ||
                      "No se pudo subir la fotografía."
            );
        } finally {
            setSubiendo(false);
        }
    }

    return (
        <div className="profile-page">
            <header className="profile-header">
                <h1>Mi perfil</h1>
                <p>Personaliza tu información en Rincón Gamer.</p>
            </header>

            <section className="profile-card">
                <div className="profile-identity">
                    <div className="profile-avatar">
                        {preview || perfil?.avatar_url ? (
                            <img
                                src={preview || perfil.avatar_url}
                                alt="Foto de perfil"
                            />
                        ) : (
                            <span>
                                {perfil?.name?.charAt(0).toUpperCase() || "?"}
                            </span>
                        )}
                    </div>

                    <div>
                        <h2>{perfil?.name || "Usuario"}</h2>
                        <p>{perfil?.email}</p>
                    </div>
                </div>

                <div className="profile-upload">
                    <label htmlFor="avatar">
                        Seleccionar foto de perfil
                    </label>

                    <input
                        id="avatar"
                        type="file"
                        accept="image/png,image/jpeg,image/webp"
                        onChange={(e) =>
                            setArchivo(e.target.files?.[0] || null)
                        }
                    />

                    <button
                        className="button"
                        type="button"
                        onClick={guardarAvatar}
                        disabled={!archivo || subiendo}
                    >
                        {subiendo ? "Subiendo..." : "Guardar fotografía"}
                    </button>

                    <small>Formatos JPG, PNG o WebP. Máximo 2 MB.</small>
                </div>
            </section>

            <section className="profile-card">
                <h2>Editar información</h2>

                <form className="profile-form" onSubmit={guardarDatos}>
                    <label>
                        Nombre de usuario
                        <input
                            value={name}
                            onChange={(e) => setName(e.target.value)}
                            required
                        />
                    </label>

                    <label>
                        Correo electrónico
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                        />
                    </label>

                    <button
                        className="button"
                        type="submit"
                        disabled={guardando}
                    >
                        {guardando ? "Guardando..." : "Guardar cambios"}
                    </button>
                </form>
            </section>

            {mensaje && <p className="profile-success">{mensaje}</p>}
            {error && <p className="error">{error}</p>}
        </div>
    );
}
