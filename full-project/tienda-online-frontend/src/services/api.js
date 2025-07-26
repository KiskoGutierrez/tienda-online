import axios from 'axios'

// Instancia axios configurada con baseURL y encabezados por defecto
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: { 'Content-Type': 'application/json' }
})

// Obtener todos los productos
export const fetchProductos = () => api.get('/productos')

// Login local con email y contraseña
export const loginLocal = (data) => api.post('/login', data)

// Login usando token de Google
export const loginGoogle = (credential) => api.post('/login/google', { credential })

// Obtener información del usuario autenticado, usando token Bearer
export const fetchUsuario = (token) =>
  api.get('/usuario', {
    headers: { Authorization: `Bearer ${token}` },
  })

// Obtener historial de compras del usuario autenticado
export const fetchCompras = (token) =>
  api.get('/compra', {
    headers: { Authorization: `Bearer ${token}` },
  })

// Confirmar una compra enviando array de productos (solo id y cantidad) con token
export function confirmarCompra(carrito, token) {
  if (!Array.isArray(carrito)) {
    throw new Error('🚫 El carrito no es un array')
  }

  // Extrae solo id y cantidad de cada producto
  const productos = carrito.map(p => ({
    id: p.id,
    cantidad: p.cantidad
  }))

  return api.post('/compra', { productos }, {
    headers: { Authorization: `Bearer ${token}` }
  })
}

export default api
