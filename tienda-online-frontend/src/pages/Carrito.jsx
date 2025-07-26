import React, { useContext, useState } from 'react'
import { AppContext } from '../context/AppContext'
import { confirmarCompra } from '../services/api'
import { getImageUrl } from '../utils/getImageUrl'
import confetti from 'canvas-confetti'

export default function Carrito() {
  const {
    carrito,
    clearCart,
    removeFromCart,
    increaseQty,
    decreaseQty,
    token
  } = useContext(AppContext)

  const [mensaje, setMensaje] = useState('')
  const [error, setError] = useState('')
  const [cargando, setCargando] = useState(false)
  const [mostrarModal, setMostrarModal] = useState(false)

  const total = Array.isArray(carrito)
    ? carrito.reduce((acc, p) => acc + Number(p.precio) * p.cantidad, 0)
    : 0

  const lanzarConfeti = () =>
    confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 } })

  const handleCompra = async () => {
    if (!token) {
      setError('🚫 Debes iniciar sesión para confirmar la compra.')
      return
    }
    if (carrito.length === 0) {
      setError('🚫 Tu carrito está vacío.')
      return
    }

    const sinStock = carrito.filter(p => p.cantidad > p.stock)
    if (sinStock.length) {
      setError(`🚫 Sin stock de: ${sinStock.map(p => p.nombre).join(', ')}`)
      return
    }

    setCargando(true)
    setMensaje('')
    setError('')

    try {
      await confirmarCompra(carrito, token)
      clearCart()
      setMensaje('✅ Compra realizada con éxito')
      lanzarConfeti()
      setMostrarModal(true)
    } catch (err) {
      console.error('Error al confirmar compra:', err.response?.data || err)
      const msg =
        err.response?.data?.error ||
        err.response?.data?.message ||
        '❌ Error al confirmar la compra'
      setError(msg)
    } finally {
      setCargando(false)
    }
  }

  return (
    <div className="max-w-xl mx-auto p-4">
      <h2 className="text-xl font-bold mb-4">Tu Carrito</h2>

      {mensaje && <p className="text-green-600 mb-2">{mensaje}</p>}
      {error && <p className="text-red-600 mb-2">{error}</p>}

      {carrito.length === 0 ? (
        <p>No hay productos en el carrito.</p>
      ) : (
        <>
          <ul className="space-y-4">
            {carrito.map(producto => {
              const restante = producto.stock - producto.cantidad
              const sinMas = producto.cantidad >= producto.stock
              const imgUrl = getImageUrl(producto.imagen)

              return (
                <li
                  key={producto.id}
                  className="border p-4 rounded-lg flex gap-4 items-start"
                >
                  <img
                    src={imgUrl}
                    alt={producto.nombre}
                    onError={e => {
                      e.target.src = '/images/productos/default.png'
                    }}
                    className="w-24 h-24 object-cover rounded-md border"
                  />

                  <div className="flex-1">
                    <p className="font-semibold">{producto.nombre}</p>
                    <p
                      className={`text-sm mb-2 ${
                        restante > 0 ? 'text-gray-600' : 'text-red-600'
                      }`}
                    >
                      {restante > 0 ? `Quedan ${restante}` : 'Agotado'}
                    </p>

                    <div className="flex items-center space-x-2 mt-2">
                      <button
                        onClick={() => decreaseQty(producto.id)}
                        disabled={producto.cantidad <= 1}
                        className="px-2 text-lg bg-gray-200 rounded disabled:opacity-50"
                      >
                        –
                      </button>
                      <span>{producto.cantidad}</span>
                      <button
                        onClick={() => increaseQty(producto.id)}
                        disabled={sinMas}
                        className={`px-2 text-lg rounded text-white ${
                          sinMas
                            ? 'bg-gray-400 cursor-not-allowed'
                            : 'bg-blue-600 hover:bg-blue-700'
                        }`}
                      >
                        +
                      </button>
                    </div>

                    <p className="mt-2">
                      Precio unitario:{' '}
                      {Number(producto.precio).toLocaleString('es-ES', {
                        style: 'currency',
                        currency: 'EUR'
                      })}
                    </p>
                    <p className="text-sm text-gray-600">
                      Subtotal:{' '}
                      {(
                        Number(producto.precio) * producto.cantidad
                      ).toLocaleString('es-ES', {
                        style: 'currency',
                        currency: 'EUR'
                      })}
                    </p>
                  </div>

                  <button
                    onClick={() => removeFromCart(producto.id)}
                    className="text-red-600 hover:underline text-sm self-start"
                  >
                    ❌ Quitar
                  </button>
                </li>
              )
            })}
          </ul>

          <div className="mt-6 border-t pt-4 flex justify-between items-center">
            <p className="text-lg font-semibold">
              Total:{' '}
              {total.toLocaleString('es-ES', {
                style: 'currency',
                currency: 'EUR'
              })}
            </p>
            <button
              onClick={handleCompra}
              disabled={cargando}
              className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:opacity-60"
            >
              {cargando ? 'Confirmando...' : 'Confirmar Compra'}
            </button>
          </div>
        </>
      )}

      {mostrarModal && (
        <div className="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 text-center shadow-lg max-w-sm mx-auto">
            <h3 className="text-2xl font-bold mb-2">¡Gracias por tu compra! 🛍️</h3>
            <p className="text-gray-700 mb-4">
              Tu pedido se ha confirmado correctamente.
            </p>
            <button
              onClick={() => setMostrarModal(false)}
              className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
              Cerrar
            </button>
          </div>
        </div>
      )}
    </div>
  )
}