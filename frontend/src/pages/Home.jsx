
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import {
    obtenerPublicaciones,
    crearPublicacion,
} from "../services/posts";
import { obtenerComunidades } from "../services/communities";

export default function Home() {
    const { user } = useAuth();

    const [posts, setPosts] = useState([]);
    const [communities, setCommunities] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState("");
    const [message, setMessage] = useState("");

    const [title, setTitle] = useState("");
    const [content, setContent] = useState("");
    const [communityId, setCommunityId] = useState("");
    const [image, setImage] = useState("");
    const [publishing, setPublishing] = useState(false);

    async function cargarDatos() {
        setLoading(true);
        setError("");

        try {
            const [postsData, communitiesData] = await Promise.all([
                obtenerPublicaciones(),
                obtenerComunidades(),
            ]);

            setPosts(
                Array.isArray(postsData)
                    ? postsData
                    : postsData.data ?? []
            );

            setCommunities(
                Array.isArray(communitiesData)
                    ? communitiesData
                    : communitiesData.data ?? []
            );
        } catch (err) {
            setError(
                err.response?.data?.message ||
                "No se pudieron cargar las publicaciones."
            );
        } finally {
            setLoading(false);
        }
    }

    useEffect(() => {
        cargarDatos();
    }, []);

    async function handlePublish(e) {
        e.preventDefault();
        setError("");
        setMessage("");
        setPublishing(true);

        try {
            await crearPublicacion({
                user_id: user.id,
                community_id: Number(communityId),
                title,
                content,
                image: image.trim() || null,
            });

            setTitle("");
            setContent("");
            setCommunityId("");
            setImage("");
            setMessage("¡Publicación creada correctamente!");

            await cargarDatos();
        } catch (err) {
            const errores = err.response?.data?.errors;

            setError(
                errores
                    ? Object.values(errores).flat().join(" ")
                    : err.response?.data?.message ||
                      "No se pudo crear la publicación."
            );
        } finally {
            setPublishing(false);
        }
    }

    return (
        <div className="home-page">
            <header className="home-header">
                <h1>Bienvenido a Rincón Gamer</h1>
                <p>
                    Hola, {user?.name}. Comparte tus experiencias,
                    descubrimientos y aventuras con otros jugadores.
                </p>
            </header>

            <section className="post-editor">
                <h2>Crear publicación</h2>

                <form onSubmit={handlePublish}>
                    <label>
                        Comunidad
                        <select
                            value={communityId}
                            onChange={(e) => setCommunityId(e.target.value)}
                            required
                        >
                            <option value="">
                                Selecciona una comunidad
                            </option>

                            {communities.map((community) => (
                                <option
                                    key={community.id}
                                    value={community.id}
                                >
                                    {community.name}
                                </option>
                            ))}
                        </select>
                    </label>

                    <label>
                        Título
                        <input
                            value={title}
                            onChange={(e) => setTitle(e.target.value)}
                            maxLength={150}
                            placeholder="¿De qué quieres hablar?"
                            required
                        />
                    </label>

                    <label>
                        Contenido
                        <textarea
                            value={content}
                            onChange={(e) => setContent(e.target.value)}
                            placeholder="Cuéntale algo a la comunidad..."
                            rows={5}
                            required
                        />
                    </label>

                    <label>
                        URL de imagen (opcional)
                        <input
                            type="url"
                            value={image}
                            onChange={(e) => setImage(e.target.value)}
                            placeholder="https://ejemplo.com/imagen.jpg"
                        />
                    </label>

                    <button
                        className="button"
                        type="submit"
                        disabled={
                            publishing ||
                            communities.length === 0
                        }
                    >
                        {publishing ? "Publicando..." : "Publicar"}
                    </button>
                </form>

                {message && (
                    <p className="post-success">{message}</p>
                )}

                {error && <p className="error">{error}</p>}
            </section>

            <section className="feed-section">
                <div className="feed-heading">
                    <h2>Publicaciones recientes</h2>

                    <span className="counter">
                        {posts.length} publicaciones
                    </span>
                </div>

                {loading ? (
                    <p className="message">
                        Cargando publicaciones...
                    </p>
                ) : posts.length === 0 ? (
                    <p className="empty">
                        Todavía no hay publicaciones.
                        ¡Sé el primero en compartir algo!
                    </p>
                ) : (
                    <div className="posts-list">
                        {posts.map((post) => (
                            <article
                                className="post-card"
                                key={post.id}
                            >
                                <div className="post-author">
                                    <div className="post-avatar">
                                        {post.user?.avatar_url ? (
                                            <img
                                                src={post.user.avatar_url}
                                                alt={`Avatar de ${
                                                    post.user.name
                                                }`}
                                                loading="lazy"
                                            />
                                        ) : (
                                            <span>
                                                {post.user?.name
                                                    ?.charAt(0)
                                                    .toUpperCase() || "?"}
                                            </span>
                                        )}
                                    </div>

                                    <div className="post-author-info">
                                        <strong>
                                            {post.user?.name ||
                                                "Usuario"}
                                        </strong>

                                        <span>
                                            {post.community?.name ||
                                                "Comunidad"}
                                        </span>
                                    </div>
                                </div>

                                <h3>{post.title}</h3>

                                <p>{post.content}</p>

                                {post.image && (
                                    <img
                                        className="post-image"
                                        src={post.image}
                                        alt={post.title}
                                        loading="lazy"
                                    />
                                )}

                                <Link
                                    className="post-community-link"
                                    to="/comunidades"
                                >
                                    Ver comunidades
                                </Link>
                            </article>
                        ))}
                    </div>
                )}
            </section>
        </div>
    );
}
