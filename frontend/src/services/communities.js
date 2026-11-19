
import api from "../api/axios";

const getList = (response) => {
    const data = response.data;

    if (Array.isArray(data)) {
        return data;
    }

    if (Array.isArray(data?.data)) {
        return data.data;
    }

    if (Array.isArray(data?.communities)) {
        return data.communities;
    }

    throw new Error("La API no devolvió una lista válida.");
};

// Obtener todas las comunidades
export const getCommunities = async () => {
    const response = await api.get("/communities");
    return getList(response);
};

// Nombre utilizado por Home.jsx
// Reutiliza la función existente para evitar duplicar solicitudes.
export const obtenerComunidades = getCommunities;

// Obtener una comunidad específica
export const getCommunity = async (id) => {
    const response = await api.get(`/communities/${id}`);
    return response.data;
};

// Crear una comunidad
export const createCommunity = async (data) => {
    const response = await api.post("/communities", data);
    return response.data;
};

// Actualizar una comunidad
export const updateCommunity = async (id, data) => {
    const response = await api.put(`/communities/${id}`, data);
    return response.data;
};

// Eliminar una comunidad
export const deleteCommunity = async (id) => {
    const response = await api.delete(`/communities/${id}`);
    return response.data;
};

// Obtener todos los videojuegos
export const getGames = async () => {
    const response = await api.get("/games");
    return getList(response);
};
