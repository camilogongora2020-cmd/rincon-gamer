import api from "../api/axios";

const getList = (response) => {
    const data = response.data;

    if (Array.isArray(data)) {
        return data;
    }

    if (Array.isArray(data.data)) {
        return data.data;
    }

    if (Array.isArray(data.communities)) {
        return data.communities;
    }

    throw new Error("La API no devolvió una lista válida.");
};

export const getCommunities = async () => {
    const response = await api.get("/communities");
    return getList(response);
};

export const getCommunity = async (id) => {
    const response = await api.get(`/communities/${id}`);
    return response.data;
};

export const createCommunity = async (data) => {
    const response = await api.post("/communities", data);
    return response.data;
};

export const updateCommunity = async (id, data) => {
    const response = await api.put(`/communities/${id}`, data);
    return response.data;
};

export const deleteCommunity = async (id) => {
    const response = await api.delete(`/communities/${id}`);
    return response.data;
};

export const getGames = async () => {
    const response = await api.get("/games");
    return getList(response);
};