
import api from "../api/axios";

export const registrar = async (datos) => {
    const respuesta = await api.post("/auth/register", datos);
    return respuesta.data;
};

export const iniciarSesion = async (email, password) => {
    const respuesta = await api.post("/auth/login", {
        email,
        password,
    });
    return respuesta.data;
};

export const obtenerPerfil = async () => {
    const respuesta = await api.get("/users/profile");
    return respuesta.data;
};

export const actualizarPerfil = async (datos) => {
    const respuesta = await api.put("/users/update", datos);
    return respuesta.data;
};

export const subirAvatar = async (archivo) => {
    const datos = new FormData();
    datos.append("avatar", archivo);

    const respuesta = await api.post("/users/avatar", datos, {
        headers: {
            "Content-Type": undefined,
        },
    });

    return respuesta.data;
};

export const cerrarSesion = async () => {
    const respuesta = await api.post("/auth/logout");
    return respuesta.data;
};
