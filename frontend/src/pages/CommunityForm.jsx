import { useEffect, useState } from "react";
import { Link, useNavigate, useParams } from "react-router-dom";

import {
    createCommunity,
    getCommunity,
    getGames,
    updateCommunity,
} from "../services/communities";

function CommunityForm() {
    const { id } = useParams();
    const navigate = useNavigate();

    const editing = Boolean(id);

    const [games, setGames] = useState([]);
    const [loading, setLoading] = useState(editing);
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState("");

    const [form, setForm] = useState({
        name: "",
        description: "",
        game_id: "",
        creator_id: "",
        image: "",
        color: "#7c3aed",
    });

    useEffect(() => {
        const loadForm = async () => {
            try {
                setError("");

                const gamesData = await getGames();
                setGames(gamesData);

                if (editing) {
                    const community = await getCommunity(id);

                    setForm({
                        name: community.name || "",
                        description: community.description || "",
                        game_id: community.game_id || "",
                        creator_id: community.creator_id || "",
                        image: community.image || "",
                        color: community.color || "#7c3aed",
                    });
                }
            } catch (error) {
                console.error(error);
                setError("No se pudieron cargar los datos del formulario.");
            } finally {
                setLoading(false);
            }
        };

        loadForm();
    }, [id, editing]);

    const handleChange = (event) => {
        const { name, value } = event.target;

        setForm((previous) => ({
            ...previous,
            [name]: value,
        }));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();

        setError("");

        if (!form.name.trim() || !form.game_id || !form.creator_id) {
            setError("Completa el nombre, el videojuego y el creador.");
            return;
        }

        const data = {
            name: form.name.trim(),
            description: form.description.trim(),
            game_id: Number(form.game_id),
            creator_id: Number(form.creator_id),
            image: form.image.trim() || null,
            color: form.color,
        };

        try {
            setSaving(true);

            if (editing) {
                await updateCommunity(id, data);
            } else {
                await createCommunity(data);
            }

            navigate("/");
        } catch (error) {
            console.error(error);

            const validationErrors = error.response?.data?.errors;

            if (validationErrors) {
                setError(Object.values(validationErrors).flat().join(" "));
            } else {
                setError(
                    error.response?.data?.message ||
                    "No se pudo guardar la comunidad. Verifica los datos."
                );
            }
        } finally {
            setSaving(false);
        }
    };

    if (loading) {
        return <div className="message">Cargando formulario...</div>;
    }

    return (
        <main className="container form-container">
            <div className="form-header">
                <h1>{editing ? "Editar comunidad" : "Crear comunidad"}</h1>
                <p>
                    {editing
                        ? "Actualiza la información de tu comunidad."
                        : "Completa los datos para crear una nueva comunidad."}
                </p>
            </div>

            {error && <div className="error">{error}</div>}

            <form onSubmit={handleSubmit} className="community-form">
                <label>
                    Nombre de la comunidad
                    <input
                        type="text"
                        name="name"
                        value={form.name}
                        onChange={handleChange}
                        placeholder="Ej. Aventureros de Terraria"
                        required
                    />
                </label>

                <label>
                    Descripción
                    <textarea
                        name="description"
                        value={form.description}
                        onChange={handleChange}
                        placeholder="Describe de qué trata tu comunidad..."
                        rows="4"
                    />
                </label>

                <label>
                    Videojuego
                    <select
                        name="game_id"
                        value={form.game_id}
                        onChange={handleChange}
                        required
                    >
                        <option value="">Selecciona un videojuego</option>

                        {games.map((game) => (
                            <option key={game.id} value={game.id}>
                                {game.name}
                            </option>
                        ))}
                    </select>
                </label>

                <label>
                    ID del creador
                    <input
                        type="number"
                        name="creator_id"
                        value={form.creator_id}
                        onChange={handleChange}
                        min="1"
                        placeholder="Ej. 1"
                        required
                    />
                </label>

                <label>
                    URL de imagen (opcional)
                    <input
                        type="url"
                        name="image"
                        value={form.image}
                        onChange={handleChange}
                        placeholder="https://ejemplo.com/imagen.jpg"
                    />
                </label>

                <label>
                    Color de la comunidad
                    <div className="color-input">
                        <input
                            type="color"
                            name="color"
                            value={form.color}
                            onChange={handleChange}
                        />
                        <span>{form.color}</span>
                    </div>
                </label>

                <div className="form-actions">
                    <button
                        type="submit"
                        className="button"
                        disabled={saving}
                    >
                        {saving
                            ? "Guardando..."
                            : editing
                                ? "Guardar cambios"
                                : "Crear comunidad"}
                    </button>

                    <Link to="/" className="button secondary">
                        Cancelar
                    </Link>
                </div>
            </form>
        </main>
    );
}

export default CommunityForm;