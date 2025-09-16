# ShortLink API 🔗

API de acortamiento de URLs construida con PHP y el framework Slim. Esta API permite a los usuarios autenticados crear y gestionar URLs cortas de manera segura.

## Tecnologías Utilizadas 🛠️

- **PHP 8.x**: Lenguaje de programación principal
- **Slim Framework 4.x**: Framework PHP ligero para crear APIs RESTful
- **MySQL**: Base de datos relacional para almacenar las URLs
- **Keycloak**: Servidor de autenticación y autorización
- **JWT**: Para la autenticación basada en tokens
- **Composer**: Gestor de dependencias de PHP

## Características Principales ✨

- Acortamiento de URLs con validación de seguridad
- Autenticación mediante JWT y Keycloak
- Validación de URLs contra una lista negra de dominios y palabras
- Redirección de URLs cortas
- Listado de URLs por usuario
- Soporte exclusivo para URLs HTTPS

## Estructura del Proyecto 📁

```
├── public/
│   └── index.php          # Punto de entrada de la aplicación
├── src/
│   ├── config/           # Configuraciones (DB, blacklist)
│   ├── controllers/      # Controladores de la aplicación
│   ├── middleware/       # Middleware de autenticación
│   ├── models/           # Modelos de datos
│   └── routes/           # Definición de rutas
├── vendor/               # Dependencias de Composer
└── script/              
    └── shortener.sql     # Script para crear la base de datos
```

## Endpoints de la API 🌐

### POST /shorten
- **Descripción**: Acorta una URL proporcionada
- **Autenticación**: Requerida (Bearer Token)
- **Body**: `{"url": "https://ejemplo.com"}`
- **Respuesta**: `{"short_url": "http://dominio/abc123"}`

### GET /my-urls
- **Descripción**: Obtiene todas las URLs del usuario autenticado
- **Autenticación**: Requerida (Bearer Token)
- **Respuesta**: Lista de URLs del usuario

### GET /{code}
- **Descripción**: Redirecciona a la URL original
- **Autenticación**: No requerida
- **Respuesta**: Redirección 302 a la URL original

## Seguridad 🔒

- Autenticación mediante tokens JWT verificados contra Keycloak
- Validación de URLs contra una lista negra de dominios maliciosos
- Soporte exclusivo para URLs HTTPS
- Validación de claims del token (issuer, audience)
- Protección contra URLs maliciosas mediante blacklist

## Configuración y Despliegue 🚀

1. Clonar el repositorio
2. Instalar dependencias: `composer install`
3. Configurar variables de entorno en `.env`:
   ```env
   DB_HOST=localhost
   DB_NAME=shortlink
   DB_USER=usuario
   DB_PASS=contraseña
   BASE_URL=http://localhost:8100
   KC_ISSUER=https://tu-keycloak/realms/tu-realm
   KC_CLIENT_ID=tu-cliente
   JWKS_URL=https://tu-keycloak/realms/tu-realm/protocol/openid-connect/certs
   ```
4. Iniciar el servidor: `composer start`

## Autor 👨‍💻

Gustavo Cuyutupa - [gustavocuyutupa@outlook.com](mailto:gustavocuyutupa@outlook.com)