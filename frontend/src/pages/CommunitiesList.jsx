import { useEffect, useState } from "react";
import { Link } from "react-router-dom";

import {
    getCommunities,
    getGames,
    deleteCommunity,
} from "../services/communities";

function CommunitiesList() {
    const [communities, setCommunities] = useState([]);
    const [games, setGames] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");

    const loadData = async () => {
        try {
            setLoading(true);
            setError("");

            const [communitiesData, gamesData] = await Promise.all([
                getCommunities(),
                getGames(),
            ]);

            setCommunities(communitiesData);
            setGames(gamesData);
        } catch (error) {
            console.error(error);
            setError("No se pudieron cargar los datos. Verifica Laravel y la conexión.");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        loadData();
    }, []);

    const handleDelete = async (id) => {
        const confirmDelete = window.confirm(
            "¿Estás seguro de que deseas eliminar esta comunidad?"
        );

        if (!confirmDelete) return;

        try {
            await deleteCommunity(id);

            setCommunities((previous) =>
                previous.filter((community) => community.id !== id)
            );
        } catch (error) {
            console.error(error);
            alert("No fue posible eliminar la comunidad.");
        }
    };

    const getGameName = (gameId) => {
        const game = games.find((item) => item.id === gameId);
        return game ? game.name : `Juego #${gameId}`;
    };

    if (loading) {
        return <div className="message">Cargando comunidades...</div>;
    }

    return (
        <main className="container">
            <header className="header">
                <div>
                    <h1>Rincón Gamer</h1>
                    <p>Encuentra tu comunidad y comparte tu pasión por los videojuegos.</p>
                </div>

                <Link to="/comunidades/nueva" className="button">
                    + Crear comunidad
                </Link>
            </header>

            <section className="section-heading">
                <div>
                    <h2>Comunidades</h2>
                    <p>Explora las comunidades disponibles.</p>
                </div>

                <span className="counter">
                    {communities.length} comunidades
                </span>
            </section>

            {error && <div className="error">{error}</div>}

            {!error && communities.length === 0 && (
                <div className="empty">
                    <h3>No hay comunidades todavía</h3>
                    <p>¡Sé el primero en crear una!</p>
                </div>
            )}

            <section className="community-grid">
                {communities.map((community) => (
                    <article
                        className="community-card"
                        key={community.id}
                        style={{
                            borderTop: `4px solid ${community.color || "#7c3aed"}`,
                        }}
                    >
                        {community.image ? (
                            <img
                                src={community.image}
                                alt={community.name}
                                className="community-image"
                            />
                        ) : (
                            <div
                                className="community-placeholder"
                                style={{
                                    background: community.color || "#7c3aed",
                                }}
                            >
                                🎮
                            </div>
                        )}

                        <div className="community-content">
                            <span className="game-tag">
                                {getGameName(community.game_id)}
                            </span>

                            <h3>{community.name}</h3>

                            <p>
                                {community.description ||
                                    "Esta comunidad todavía no tiene descripción."}
                            </p>

                            <div className="card-actions">
                                <Link
                                    to={`/comunidades/${community.id}/editar`}
                                    className="button secondary"
                                >
                                    Editar
                                </Link>

                                <button
                                    className="button danger"
                                    onClick={() => handleDelete(community.id)}
                                >
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </article>
                ))}
            </section>
        </main>
    );
}

export default CommunitiesList;