
import api from "../api/axios";

// Obtener todas las publicaciones
export async function obtenerPublicaciones() {
    const respuesta = await api.get("/posts");
    return respuesta.data;
}

// Obtener una publicación específica
export async function obtenerPublicacion(id) {
    const respuesta = await api.get(`/posts/${id}`);
    return respuesta.data;
}

// Crear una publicación
export async function crearPublicacion(datos) {
    const respuesta = await api.post("/posts", datos);
    return respuesta.data;
}

// Actualizar una publicación
export async function actualizarPublicacion(id, datos) {
    const respuesta = await api.put(`/posts/${id}`, datos);
    return respuesta.data;
}

// Eliminar una publicación
export async function eliminarPublicacion(id) {
    const respuesta = await api.delete(`/posts/${id}`);
    return respuesta.data;
}
