
import {
    BrowserRouter,
    Routes,
    Route,
    Navigate,
} from "react-router-dom";

import { AuthProvider } from "./context/AuthContext";
import ProtectedRoute from "./components/ProtectedRoute";
import Layout from "./components/Layout";

import Login from "./pages/Login";
import Register from "./pages/Register";
import Home from "./pages/Home";
import CommunitiesList from "./pages/CommunitiesList";
import CommunityForm from "./pages/CommunityForm";
import Profile from "./pages/Profile";

function App() {
    return (
        <BrowserRouter>
            <AuthProvider>
                <Routes>
                    <Route path="/login" element={<Login />} />
                    <Route path="/registro" element={<Register />} />

                    <Route element={<ProtectedRoute />}>
                        <Route element={<Layout />}>
                            <Route path="/" element={<Home />} />

                            <Route
                                path="/comunidades"
                                element={<CommunitiesList />}
                            />

                            <Route
                                path="/comunidades/nueva"
                                element={<CommunityForm />}
                            />

                            <Route
                                path="/comunidades/:id/editar"
                                element={<CommunityForm />}
                            />

                            <Route
                                path="/perfil"
                                element={<Profile />}
                            />
                        </Route>
                    </Route>

                    <Route
                        path="*"
                        element={<Navigate to="/" replace />}
                    />
                </Routes>
            </AuthProvider>
        </BrowserRouter>
    );
}

export default App;
