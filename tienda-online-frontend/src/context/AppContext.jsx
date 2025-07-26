import React, { createContext, useState, useEffect } from 'react'

export const AppContext = createContext()

export function AppProvider({ children }) {
  const [token, setToken] = useState(() => {
    return localStorage.getItem('token') || null
  })

  const [carrito, setCarrito] = useState(() => {
    const guardado = localStorage.getItem('carrito')
    return guardado ? JSON.parse(guardado) : []
  })

  useEffect(() => {
    localStorage.setItem('carrito', JSON.stringify(carrito))
  }, [carrito])

  const login = (newToken) => {
    setToken(newToken)
    localStorage.setItem('token', newToken)
  }

  const logout = () => {
    setToken(null)
    localStorage.removeItem('token')
    setCarrito([])
    localStorage.removeItem('carrito')
  }

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

  const removeFromCart = (id) => {
    setCarrito(prev => prev.filter(p => p.id !== id))
  }

  const increaseQty = (id) => {
    setCarrito(prev =>
      prev.map(p =>
        p.id === id ? { ...p, cantidad: p.cantidad + 1 } : p
      )
    )
  }

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

  const clearCart = () => {
    setCarrito([])
    localStorage.removeItem('carrito')
  }

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