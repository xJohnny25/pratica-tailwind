# Ventajas y desventajas: Librerías en local vs CDN

## Librerías en local (descargadas en el proyecto)

**Ventajas:**
- Funciona sin internet. Si no hay conexión, la web sigue funcionando.
- Control total: eliges la versión exacta y no cambia sin avisar.
- No dependes de que un servidor externo esté disponible.
- Más privacidad: no se hacen peticiones a terceros.
- Útil para desarrollo o entornos sin conexión.

**Desventajas:**
- Ocupa espacio en tu servidor/proyecto.
- Tú te encargas de actualizar la librería cuando salga una versión nueva.
- Cada usuario descarga el archivo desde tu servidor (no hay caché compartida).

---

## CDN (Content Delivery Network)

**Ventajas:**
- Si el usuario ya visitó otra web que usa el mismo CDN, el archivo puede estar en caché. Carga más rápida.
- Los CDN suelen ser rápidos (servidores repartidos por el mundo).
- No aumenta el tamaño de tu proyecto.
- Las actualizaciones las gestiona el proveedor del CDN.

**Desventajas:**
- Necesitas internet. Sin conexión, la librería no carga.
- Dependes de un servicio externo. Si el CDN cae, tu web deja de funcionar bien.
- Menos control sobre la versión (a no ser que fijes una URL concreta).
- Algunos navegadores bloquean peticiones a dominios externos (CORS, privacidad).

---