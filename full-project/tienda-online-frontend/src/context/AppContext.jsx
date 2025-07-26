import React, { createContext, useState, useEffect } from 'react'

// Crea el contexto global de la aplicación
export const AppContext = createContext()

export function AppProvider({ children }) {
  // Estado del token, inicializado desde localStorage si existe
  const [token, setToken] = useState(() => {
    return localStorage.getItem('token') || null
  })

  // Estado del carrito, inicializado desde localStorage o vacío
  const [carrito, setCarrito] = useState(() => {
    const guardado = localStorage.getItem('carrito')
    return guardado ? JSON.parse(guardado) : []
  })

  // Guarda el carrito en localStorage cada vez que cambia
  useEffect(() => {
    localStorage.setItem('carrito', JSON.stringify(carrito))
  }, [carrito])

  // Función para iniciar sesión y guardar token en estado y localStorage
  const login = (newToken) => {
    setToken(newToken)
    localStorage.setItem('token', newToken)
  }

  // Función para cerrar sesión, limpiar token y carrito de estado y localStorage
  const logout = () => {
    setToken(null)
    localStorage.removeItem('token')
    setCarrito([])
    localStorage.removeItem('carrito')
  }

  // Añade un producto al carrito o aumenta cantidad si ya existe
  const addToCart = (producto) => {
    setCarrito(prev => {
      const exist = prev.find(p => p.id === producto.id)
      if (exist) {
        return prev.map(p =>
          p.id === producto.id
            ? { ...p, cantidad: p.cantidad + 1 }
            : p
        )
      }
      return [...prev, { ...producto, cantidad: 1 }]
    })
  }

  // Elimina un producto del carrito por id
  const removeFromCart = (id) => {
    setCarrito(prev => prev.filter(p => p.id !== id))
  }

  // Incrementa la cantidad de un producto en el carrito
  const increaseQty = (id) => {
    setCarrito(prev =>
      prev.map(p =>
        p.id === id ? { ...p, cantidad: p.cantidad + 1 } : p
      )
    )
  }

  // Disminuye la cantidad de un producto, eliminándolo si llega a 0
  const decreaseQty = (id) => {
    setCarrito(prev =>
      prev.reduce((acc, p) => {
        if (p.id === id && p.cantidad > 1) {
          acc.push({ ...p, cantidad: p.cantidad - 1 })
        } else if (p.id !== id) {
          acc.push(p)
        }
        return acc
      }, [])
    )
  }

  // Vacía el carrito y limpia localStorage
  const clearCart = () => {
    setCarrito([])
    localStorage.removeItem('carrito')
  }

  // Provee el contexto con estados y funciones a los componentes hijos
  return (
    <AppContext.Provider
      value={{
        token,
        login,
        logout,
        carrito,
        addToCart,
        removeFromCart,
        increaseQty,
        decreaseQty,
        clearCart
      }}
    >
      {children}
    </AppContext.Provider>
  )
}
