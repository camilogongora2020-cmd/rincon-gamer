import { BrowserRouter, Routes, Route } from "react-router-dom";

import CommunitiesList from "./pages/CommunitiesList";
import CommunityForm from "./pages/CommunityForm";

function App() {
    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<CommunitiesList />} />

                <Route
                    path="/comunidades/nueva"
                    element={<CommunityForm />}
                />

                <Route
                    path="/comunidades/:id/editar"
                    element={<CommunityForm />}
                />
            </Routes>
        </BrowserRouter>
    );
}

export default App;