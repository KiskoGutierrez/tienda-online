import React from 'react'
import { getImageUrl } from '../utils/getImageUrl'

export default function ProductoCard({ producto, onAdd }) {
  // Asegura que el precio sea un número, convierte si es string
  const precioNum =
    typeof producto.precio === 'number'
      ? producto.precio
      : parseFloat(producto.precio) || 0

  // Verifica si el producto está agotado
  const sinStock = producto.stock <= 0
  // Obtiene la URL completa de la imagen del producto
  const imgUrl = getImageUrl(producto.imagen)

  // 🔍 Logs para debug de imagen
  console.log('📷 imagen recibida:', producto.imagen)
  console.log('🌐 URL construida:', imgUrl)

  return (
    <div className="border rounded-lg shadow p-4 flex flex-col">
      {/* Imagen del producto con fallback en caso de error */}
      <img
        src={imgUrl}
        alt={producto.nombre}
        onError={(e) => {
          e.target.src = '/images/productos/default.png'
        }}
        className="w-full h-40 object-cover mb-4 rounded bg-gray-100"
      />

      {/* Nombre del producto */}
      <h2 className="text-xl font-semibold mb-2">{producto.nombre}</h2>

      {/* Indica stock disponible o mensaje de agotado */}
      <p className={`mb-2 ${sinStock ? 'text-red-600' : 'text-gray-700'}`}>
        {sinStock ? 'Agotado' : `Stock disponible: ${producto.stock}`}
      </p>

      {/* Muestra el precio con formato de moneda */}
      <p className="text-gray-700 mb-4">
        {precioNum.toLocaleString('es-ES', {
          style: 'currency',
          currency: 'EUR'
        })}
      </p>

      {/* Botón para añadir al carrito, deshabilitado si no hay stock */}
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
