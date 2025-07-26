🛍️ Tienda Online

Tienda Online es una aplicación fullstack que simula una tienda en línea, desarrollada con Laravel (API) y React (frontend). Permite navegar productos, gestionar un carrito de compras, autenticarse con Google y consultar el historial de compras.

🔧 Tecnologías Utilizadas
Backend: Laravel, JWT, Laravel Socialite, PHPUnit

Frontend: React, Context API, Tailwind CSS

Autenticación: OAuth2 con Google

CI/CD: GitHub Actions (test automático antes de merge)

📁 Estructura del Proyecto

📦 tienda-online

├── tienda-online-api        # API en Laravel
└── tienda-online-frontend   # Frontend en React

🚀 Instalación

1. Clonar el repositorio
   
bash
git clone https://github.com/tu-usuario/tienda-online.git
cd tienda-online

2. Configurar el Backend
   
bash
cd tienda-online-api
cp .env.example .env
Configura tu base de datos en .env

Añade tus credenciales de Google OAuth (client_id, client_secret)

Ejecuta migraciones y seeders. Sirve para poblar la base de datos con productos cada vez que se inicia el proyecto, ya que por defecto está vacía:

bash
php artisan migrate --seed

Genera la clave del proyecto:

bash
php artisan key:generate

Levanta el servidor local:

bash
php artisan serve

3. Configurar el Frontend:

bash
cd ../tienda-online-frontend
npm install

Agrega las variables de entorno con tu backend URL y credenciales OAuth:

env
VITE_API_URL=http://localhost:8000/api
VITE_GOOGLE_CLIENT_ID=...

Inicia el frontend:

bash
npm run dev


✨ Funcionalidades Principales
Función	Descripción
🔐 Autenticación	Inicio de sesión con Google (OAuth2)
🛒 Carrito	Añadir, ver y eliminar productos
🛍️ Comprar	Simulación de compra: registro y descuento de stock
📜 Historial	Ver órdenes anteriores del usuario
📦 Productos	Visualización de productos con imagen, nombre y precio
💡 Responsive	Diseño adaptado para móviles y escritorio
👨‍💻 Buenas Prácticas del Proyecto
🔀 Trabajo con ramas siguiendo nomenclatura:

feature/<funcionalidad>

bugfix/<corrección>

✅ Tests unitarios y de integración cubren las funciones clave

🚫 No se permite push directo a main sin pasar tests

📦 CI/CD con pruebas automáticas en GitHub Actions

🧪 Ejecutar Pruebas

Backend (PHPUnit)
bash
php artisan test

Frontend (si se añaden con Jest o Vitest)
bash
npm run test

🔐 Autenticación con Google
Se integra con Laravel Socialite en el backend y Google Login en React. Al iniciar sesión, se genera un token JWT y se almacena en el estado global.

🧠 Gestión del Estado (React)
Context API controla la sesión del usuario y el carrito

Permite persistencia de productos agregados

📑 Documentación Técnica
Endpoints documentados con Postman Collection

Incluye:

Login

Lista de productos

Carrito

Compra

Historial

Puedes importar la colección en Postman para probar la API.

💡 Valoraciones Extra
📋 Interface intuitiva, clara y accesible

🔄 CI/CD funcional y operativo

🔐 Manejo de errores y sesión

🧵 Contribuir
Crea una nueva rama:

bash
git checkout -b feature/nueva-funcionalidad
Commits claros y descriptivos

Push y PR solo a ramas secundarias

No borrar ramas tras merge (por política CI/CD)
