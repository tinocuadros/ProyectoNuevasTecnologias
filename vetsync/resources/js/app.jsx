import React from "react";
import ReactDOM from "react-dom/client";
import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap-icons/font/bootstrap-icons.css";
import Navbar from "./components/Navbar";

function App() {
    return (
        <>
            <Navbar />

            <div className="container mt-4">
                <h2>Contenido dinámico</h2>
            </div>
        </>
    );
}

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
