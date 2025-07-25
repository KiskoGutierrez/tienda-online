import React from 'react'
import { getImageUrl } from '../utils/getImageUrl'

export default function ProductoCard({ producto, onAdd }) {
  const precioNum =
    typeof producto.precio === 'number'
      ? producto.precio
      : parseFloat(producto.precio) || 0

  const sinStock = producto.stock <= 0
  const imgUrl = getImageUrl(producto.imagen)

  // 🔍 Logs para debug de imagen
  console.log('📷 imagen recibida:', producto.imagen)
  console.log('🌐 URL construida:', imgUrl)

  return (
    <div className="border rounded-lg shadow p-4 flex flex-col">
      <img
        src={imgUrl}
        alt={producto.nombre}
        onError={(e) => {
          e.target.src = '/images/productos/default.png'
        }}
        className="w-full h-40 object-cover mb-4 rounded bg-gray-100"
      />

      <h2 className="text-xl font-semibold mb-2">{producto.nombre}</h2>

      <p className={`mb-2 ${sinStock ? 'text-red-600' : 'text-gray-700'}`}>
        {sinStock ? 'Agotado' : `Stock disponible: ${producto.stock}`}
      </p>

      <p className="text-gray-700 mb-4">
        {precioNum.toLocaleString('es-ES', {
          style: 'currency',
          currency: 'EUR'
        })}
      </p>

      <button
        onClick={() => onAdd(producto)}
        disabled={sinStock}
        className={`mt-auto py-2 rounded text-white ${
          sinStock
            ? 'bg-gray-400 cursor-not-allowed'
            : 'bg-blue-600 hover:bg-blue-700'
        }`}
      >
        {sinStock ? 'Sin stock' : 'Añadir al carrito'}
      </button>
    </div>
  )
}