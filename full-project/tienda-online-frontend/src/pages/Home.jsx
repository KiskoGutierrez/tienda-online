import React, { useEffect, useState, useContext } from 'react'
import { fetchProductos } from '../services/api'
import ProductoCard from '../components/ProductoCard'
import { AppContext } from '../context/AppContext'

export default function Home() {
  // Estado para almacenar productos
  const [productos, setProductos] = useState([])
  // Función para agregar productos al carrito desde contexto
  const { addToCart } = useContext(AppContext)
  // Estado para manejar carga y errores
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState('')

  // Efecto que carga productos al montar el componente
  useEffect(() => {
    fetchProductos()
      .then(response => {
        const body = response.data
        // Verifica que body.data sea un array, si no, asigna vacío
        const lista = Array.isArray(body.data) ? body.data : []

        // Log para verificar productos cargados
        console.log('🧪 Productos cargados:', lista)

        setProductos(lista)
      })
      .catch((err) => {
        console.error('❌ Error al obtener productos:', err)
        setError('No se pudieron cargar los productos.')
      })
      .finally(() => {
        setLoading(false)
      })
  }, [])

  // Mostrar mensaje mientras carga
  if (loading) {
    return <p className="text-center mt-8">Cargando productos…</p>
  }

  // Mostrar mensaje de error si hay fallo
  if (error) {
    return <p className="text-center mt-8 text-red-600">{error}</p>
  }

  // Renderiza la lista de productos con componente ProductoCard
  return (
    <div className="max-w-6xl mx-auto p-4">
      <h1 className="text-3xl font-bold mb-6">Catálogo de Productos</h1>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {productos.map(prod => (
          <ProductoCard
            key={prod.id}
            producto={prod}
            onAdd={addToCart}
          />
        ))}
      </div>
    </div>
  )
}
