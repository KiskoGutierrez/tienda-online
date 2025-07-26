import React, { useContext, useEffect, useState } from 'react'
import {
  BrowserRouter,
  Routes,
  Route,
  NavLink,
  Navigate
} from 'react-router-dom'
import './index.css'
import Home from './pages/Home'
import Login from './pages/Login'
import Compras from './pages/Compras'
import Carrito from './pages/Carrito'
import { AppContext } from './context/AppContext'
import { fetchUsuario } from './services/api'

export default function App() {
  const { carrito, token, logout } = useContext(AppContext)
  const [usuario, setUsuario] = useState(null)
  const [redirigir, setRedirigir] = useState(false)

  useEffect(() => {
    if (token) {
      fetchUsuario(token)
        .then((res) => setUsuario(res.data.user))
        .catch(() => setUsuario(null))
    } else {
      setUsuario(null)
    }
  }, [token])

  // Cierre de sesión con redirección
  const cerrarSesion = () => {
    logout()
    setRedirigir(true)
  }

  return (
    <BrowserRouter>
      <nav className="bg-white shadow p-4 flex items-center justify-between">
        <div className="flex gap-4">
          <NavLink
            to="/"
            className={({ isActive }) =>
              isActive ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-blue-600'
            }
          >
            Home
          </NavLink>
          <NavLink
            to="/login"
            className={({ isActive }) =>
              isActive ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-blue-600'
            }
          >
            Login
          </NavLink>
          <NavLink
            to="/compras"
            className={({ isActive }) =>
              isActive ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-blue-600'
            }
          >
            Compras
          </NavLink>
        </div>

        <div className="flex items-center gap-4">
          <NavLink
            to="/carrito"
            className="relative ml-6 sm:ml-0"
          >

            <svg
              xmlns="http://www.w3.org/2000/svg"
              className="h-6 w-6 text-gray-600 hover:text-blue-600"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                strokeWidth={2}
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7a1 1 0 00.9 1.45H17m0 0a1 1 0 001-1H6.4M17 13l1.35 2.7m0 0L20 21H4l1.35-5.3"
              />
            </svg>
            {carrito.length > 0 && (
              <span className="absolute -top-2 -right-2 bg-red-600 text-white text-xs rounded-full px-1">
                {carrito.length}
              </span>
            )}
          </NavLink>

          {usuario ? (
            <div className="flex items-center gap-2 text-sm text-green-700">
              <span>{usuario.name || usuario.email}</span>
              <button
                onClick={cerrarSesion}
                className="text-red-600 hover:underline ml-2"
              >
                Cerrar sesión
              </button>
            </div>
          ) : (
            <span className="text-sm text-red-600">No has iniciado sesión</span>
          )}
        </div>
      </nav>

      <main className="p-6">
        {redirigir && <Navigate to="/login" replace />}
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/compras" element={<Compras />} />
          <Route path="/carrito" element={<Carrito />} />
        </Routes>
      </main>
    </BrowserRouter>
  )
}