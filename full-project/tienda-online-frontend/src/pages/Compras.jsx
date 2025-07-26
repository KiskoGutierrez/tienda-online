import { useEffect, useState, useContext } from 'react'
import { fetchCompras } from '../services/api'
import { AppContext } from '../context/AppContext'

export default function Compras() {
  // Obtiene token del contexto para autenticar peticiones
  const { token } = useContext(AppContext)
  // Estado para almacenar compras y posibles errores
  const [compras, setCompras] = useState([])
  const [error, setError] = useState('')

  // Efecto que se ejecuta al cambiar token, carga historial de compras
  useEffect(() => {
    if (token) {
      fetchCompras(token) // Llama a API para obtener compras
        .then((res) => setCompras(res.data.historial)) // Guarda compras
        .catch(() => setError('No se pudo cargar el historial')) // Captura error
    } else {
      setError('Debes iniciar sesión para ver tus compras') // Sin token, error
    }
  }, [token])

  // Si hay error muestra mensaje y no renderiza más
  if (error) return <p className="text-red-600 p-4">{error}</p>

  return (
    <div className="max-w-3xl mx-auto p-4">
      <h2 className="text-2xl font-bold mb-6">Mis Compras</h2>
      {compras.length === 0 ? (
        // Mensaje si no hay compras realizadas
        <p className="text-gray-600">No has realizado compras aún.</p>
      ) : (
        // Lista de compras
        <ul className="space-y-6">
          {compras.map((compra) => (
            <li key={compra.id} className="border rounded-lg p-4 shadow-sm bg-white">
              <div className="mb-2">
                {/* Fecha y total de la compra */}
                <p className="text-sm text-gray-500">Fecha: {compra.fecha}</p>
                <p className="text-sm text-gray-500">Total: {Number(compra.total).toFixed(2)}€</p>
              </div>
              <div>
                <p className="font-medium mb-2">Productos:</p>
                <ul className="ml-4 list-disc space-y-1">
                  {/* Lista de productos comprados con cantidad y subtotal */}
                  {compra.productos.map((p, idx) => (
                    <li key={idx} className="text-sm text-gray-700">
                      {p.nombre} - {p.cantidad} x {p.precio_unitario}€ = {p.subtotal.toFixed(2)}€
                    </li>
                  ))}
                </ul>
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  )
}
