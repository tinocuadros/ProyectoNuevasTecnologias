import { useState } from "react";
import "./Navbar.css";

function Navbar() {
    const [openMenu, setOpenMenu] = useState(null);

    const toggleMenu = (menu) => {
        setOpenMenu(openMenu === menu ? null : menu);
    };

    return (
        <nav className="navbar">
            <div className="navbar-container">
                {/* LOGO */}
                <div className="logo">VetSync</div>

                {/* MENU */}
                <ul className="menu">
                    {/* ADMIN */}
                    <li
                        className="menu-item"
                        onMouseEnter={() => setOpenMenu("admin")}
                        onMouseLeave={() => setOpenMenu(null)}
                    >
                        <button
                            className="menu-link"
                            onClick={() => toggleMenu("admin")}
                        >
                            Administración
                            <svg
                                style={{ marginLeft: "6px" }}
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M6 9l6 6 6-6"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                />
                            </svg>
                        </button>

                        <ul
                            className={`dropdown ${openMenu === "admin" ? "show" : ""}`}
                        >
                            <li className="dropdown-item">Usuarios</li>
                            <li className="dropdown-item">Permisos</li>
                            <li className="submenu-parent">
                                <div className="dropdown-item d-flex justify-content-between align-items-center">
                                    <span>Ejemplo</span>
                                    <span>››</span>
                                </div>

                                <ul className="submenu">
                                    <li className="dropdown-item">Usuarios</li>
                                    <li>Subitem 2.2</li>

                                    <li className="submenu-parent">
                                        <div className="dropdown-item">
                                            Nivel 3
                                        </div>

                                        <ul className="submenu">
                                            <li>Nivel 3.1</li>
                                            <li>Nivel 3.2</li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li
                        className="menu-item"
                        onMouseEnter={() => setOpenMenu("Modulos")}
                        onMouseLeave={() => setOpenMenu(null)}
                    >
                        <button
                            className="menu-link"
                            onClick={() => toggleMenu("Modulos")}
                        >
                            Modulos
                            <svg
                                style={{ marginLeft: "6px" }}
                                width="14"
                                height="14"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M6 9l6 6 6-6"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                />
                            </svg>
                        </button>

                        <ul
                            className={`dropdown ${openMenu === "Modulos" ? "show" : ""}`}
                        >
                            <li className="dropdown-item">Propietarios</li>
                            <li className="dropdown-item">Clientes</li>
                            <li className="dropdown-item">
                                Historias Clínicasx
                            </li>
                            <li className="submenu-parent">
                                <div className="dropdown-item d-flex justify-content-between align-items-center">
                                    <span>Ejemplo</span>
                                    <span>››</span>
                                </div>

                                <ul className="submenu">
                                    <li className="dropdown-item">Usuarios</li>
                                    <li>Subitem 2.2</li>

                                    <li className="submenu-parent">
                                        <div className="dropdown-item">
                                            Nivel 3
                                        </div>

                                        <ul className="submenu">
                                            <li>Nivel 3.1</li>
                                            <li>Nivel 3.2</li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    );
}

export default Navbar;
