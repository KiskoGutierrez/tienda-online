// src/utils/getImageUrl.js

/**
 * Devuelve la URL pública de la imagen de un producto.
 * Si filename ya empieza por '/' o 'http', la devuelve tal cual.
 * Si no, la concatena con /images/productos/.
 */
export const getImageUrl = (filename) => {
  if (!filename) {
    // Ruta a una imagen por defecto si falta filename
    return '/images/productos/default.png'
  }
  if (filename.startsWith('http') || filename.startsWith('/')) {
    return filename
  }
  return `/images/productos/${filename}`
}
