import React from 'react'
import ReactDOM from 'react-dom/client'
import './index.css'
import App from './App'
import { AppProvider } from './context/AppContext'
import { GoogleOAuthProvider } from '@react-oauth/google'

const CLIENT_ID = '205932944404-f8ieq3rafp1g4hof3vp1mfd23ekrn6kj.apps.googleusercontent.com'

// Logging para depurar
console.log('Google Client ID:', CLIENT_ID)
console.log('Mi origen actual:', window.location.origin)

const root = ReactDOM.createRoot(document.getElementById('root'))
root.render(
  <React.StrictMode>
    <AppProvider>
      <GoogleOAuthProvider clientId={CLIENT_ID}>
        <App />
      </GoogleOAuthProvider>
    </AppProvider>
  </React.StrictMode>
)