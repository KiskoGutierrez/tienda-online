import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: { 'Content-Type': 'application/json' }
})

// Productos
export const fetchProductos = () => api.get('/productos')

// Login local y Google
export const loginLocal = (data) => api.post('/login', data)
export const loginGoogle = (credential) => api.post('/login/google', { credential })

// Usuario autenticado
export const fetchUsuario = (token) =>
  api.get('/usuario', {
    headers: { Authorization: `Bearer ${token}` },
  })

// Historial de compras
export const fetchCompras = (token) =>
  api.get('/compra', {
    headers: { Authorization: `Bearer ${token}` },
  })

// Confirmar compra (envía array de productos con "id" y "cantidad")
export function confirmarCompra(carrito, token) {
  if (!Array.isArray(carrito)) {
    throw new Error('🚫 El carrito no es un array')
  }

  const productos = carrito.map(p => ({
    id: p.id,
    cantidad: p.cantidad
  }))

  return api.post('/compra', { productos }, {
    headers: { Authorization: `Bearer ${token}` }
  })
}

export default api