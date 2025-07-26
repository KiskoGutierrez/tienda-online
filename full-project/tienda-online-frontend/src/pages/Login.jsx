import React, { useState, useContext } from 'react'
import { useNavigate } from 'react-router-dom'
import {
  loginLocal as apiLoginLocal,
  loginGoogle as apiLoginGoogle
} from '../services/api'
import { AppContext } from '../context/AppContext'
import { GoogleLogin } from '@react-oauth/google'

export default function Login() {
  // Estados para email, contraseña, errores y carga
  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')
  const [error, setError] = useState('')
  const [loadingLogin, setLoadingLogin] = useState(false)

  // Contexto para login/logout y token
  const { login, logout, token, user } = useContext(AppContext)
  // Hook para navegación
  const navigate = useNavigate()

  // Si ya hay sesión activa, mostramos mensaje y botón para cerrar sesión
  if (token) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-100">
        <div className="bg-white p-6 rounded shadow text-center space-y-4">
          <h2 className="text-xl font-bold">Ya has iniciado sesión</h2>
          <p>
            Usando la cuenta: <strong>{user?.name || user?.email}</strong>
          </p>
          <button
            onClick={() => {
              logout()
              navigate('/login', { replace: true })
            }}
            className="mt-4 px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600"
          >
            Cerrar sesión
          </button>
        </div>
      </div>
    )
  }

  // Maneja login con email y contraseña
  const handleLocal = async (e) => {
    e.preventDefault()
    setError('')
    setLoadingLogin(true)

    try {
      const res = await apiLoginLocal({ email, password })
      const jwt = res.data.access_token
      login(jwt)
      // Redirige al home al iniciar sesión correctamente
      navigate('/', { replace: true })
    } catch {
      setError('🚫 Credenciales inválidas')
    } finally {
      setLoadingLogin(false)
    }
  }

  // Maneja login con Google usando el token recibido
  const handleGoogleSuccess = async ({ credential }) => {
    setError('')
    setLoadingLogin(true)

    if (!credential) {
      setError('🚫 No se recibió token de Google')
      setLoadingLogin(false)
      return
    }

    try {
      const res = await apiLoginGoogle(credential)
      const jwt = res.data.access_token
      login(jwt)
      navigate('/', { replace: true })
    } catch {
      setError('🚫 Login con Google fallido')
    } finally {
      setLoadingLogin(false)
    }
  }

  return (
    <div className="relative max-w-sm mx-auto p-4">
      {/* Overlay de carga mientras se inicia sesión */}
      {loadingLogin && (
        <div className="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center rounded">
          <div className="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin" />
          <p className="text-white mt-3">Iniciando sesión…</p>
        </div>
      )}

      <h1 className="text-2xl font-semibold mb-4">Iniciar Sesión</h1>

      {/* Mostrar errores */}
      {error && <p className="text-red-600 mb-2">{error}</p>}

      {/* Formulario login local */}
      <form onSubmit={handleLocal} className="space-y-4">
        <input
          type="email"
          placeholder="Email"
          value={email}
          onChange={e => setEmail(e.target.value)}
          className="w-full border p-2 rounded"
          disabled={loadingLogin}
        />
        <input
          type="password"
          placeholder="Contraseña"
          value={password}
          onChange={e => setPassword(e.target.value)}
          className="w-full border p-2 rounded"
          disabled={loadingLogin}
        />
        <button
          type="submit"
          disabled={loadingLogin}
          className="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 disabled:opacity-60 flex items-center justify-center"
        >
          {loadingLogin ? (
            <>
              <svg
                className="animate-spin h-5 w-5 mr-2 text-white"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
              >
                <circle
                  className="opacity-25"
                  cx="12"
                  cy="12"
                  r="10"
                  stroke="currentColor"
                  strokeWidth="4"
                />
                <path
                  className="opacity-75"
                  fill="currentColor"
                  d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z"
                />
              </svg>
              Iniciando…
            </>
          ) : (
            'Entrar'
          )}
        </button>
      </form>

      {/* Login con Google */}
      <div className="mt-6 flex justify-center">
        <GoogleLogin
          onSuccess={handleGoogleSuccess}
          onError={() => setError('🚫 Google Sign-In falló')}
          disabled={loadingLogin}
        />
      </div>
    </div>
  )
}
