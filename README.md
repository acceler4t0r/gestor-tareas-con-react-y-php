
# BRANGUS TAREAS

Sistema backend en PHP puro para la gestión de usuarios y tareas, con autenticación mediante JWT y rutas protegidas. Desarrollado como parte de un proyecto CRUD full-stack con React como frontend.

## Índice

1. [Tecnologías](#tecnologías)  
2. [Características](#características)  
3. [Requisitos Previos](#requisitos-previos)  
4. [Instalación](#instalación)  
   - [Backend (XAMPP)](#backend-xampp)  
   - [Frontend (React)](#frontend-react)  
5. [Uso](#uso)  
6. [Estructura de Carpetas](#estructura-de-carpetas)  
7. [Documentación de la API](#documentación-de-la-api)

---
## Tecnologías

- **Backend**: PHP 8.x, MySQL  
- **Servidor local**: XAMPP
- **Autenticación**: JWT manual (sin Composer)
- **Routing y Middleware**: personalizados

---

## Características

- Inicio de sesión con generación de token JWT  
- Validación de rutas protegidas mediante middleware  
- CRUD completo de tareas  
- Carga de variables de entorno desde archivo `.env`  
- Autoloader manual basado en namespaces  

---

## Requisitos Previos

- XAMPP (Apache + MySQL)
- Apache mod_rewrite activo.
- PHP 8.x
- Git (opcional pero recomendado)
- Node.js >= 22.x+
- npm
- Backend corriendo (link o referencia al repo del backend)

---

## Instalación y configuración

### Backend (XAMPP)

**1. Clonar o copiar el proyecto**  
   Coloca el contenido del repositorio en:  
   `C:\xampp\htdocs\brangus`

**2. Crear la base de datos**  
   Accede a `http://localhost/phpmyadmin` y crea una base de datos:  
   `brangus_db` (o el nombre que desees). También puedes importar el .sql de la rama `dev_back`.

  ```bash
    CREATE DATABASE '*BD_NAME*' CHARACTER SET utf8mb4; 

    USE '*BD_NAME*'; -- Tabla de usuarios 

    CREATE TABLE usuarios ( 
      id INT AUTO_INCREMENT PRIMARY KEY, 
      correo VARCHAR(100) NOT NULL UNIQUE, 
      contrasena VARCHAR(255) NOT NULL 
    ); -- Tabla de tareas 

    CREATE TABLE tareas ( 
      id INT AUTO_INCREMENT PRIMARY KEY, 
      titulo VARCHAR(100), 
      descripcion TEXT, 
      estado ENUM('pendiente', 'completada') DEFAULT 'pendiente', 
      fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
      usuario_id INT, 
      ciudad VARCHAR(100), -- ciudad detectada por IP (opcional) 
      clima_actual VARCHAR(100), -- clima obtenido (opcional) 
      frase_motivacional TEXT, -- frase opcional al crear tarea 
      FOREIGN KEY (usuario_id) REFERENCES usuarios(id) 
    ); 
  ```

**3. Crear el archivo `.env`** en la raíz del proyecto:

```bash
  DB_HOST=tu_host
  DB_NAME=nombre_base_datos
  DB_USER=usuario_db
  DB_PASS=tu_contraseña
  JWT_SECRET=clave_secreta_para_tokens
```

**4. Verifica que `.htaccess` esté configurado correctamente** en `public/.htaccess`:
```bash
  <IfModule mod_rewrite.c>
    Options +FollowSymLinks
    RewriteEngine On

    RewriteCond %{REQUEST_URI} !^/public/

    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
  </IfModule>

```

**5. Ejecuta Apache y MySQL desde el panel de XAMPP**

**6. Prueba la API visitando:** `http://localhost/brangus/public/test`

---

### Frontend (React)

**1. Clonar o copiar el proyecto**  
   Coloca el contenido del repositorio en:  
  ```bash
  git clone https://github.com/acceler4t0r/WilliamGuillermoRojasPerafan_rojaswilliam285-gmail.com.git
  cd WilliamGuillermoRojasPerafan_rojaswilliam285-gmail.com.git
  npm install
  ```

**2. Configurar URL base para la API**  
  ```bash
  const axiosInstance = axios.create({
    baseURL: 'http://localhost/_NOMBRE_CARPETA_/public'
  });
  ```

**5. Ejecuta npm start**

**6. Prueba la app visitando:** `http://localhost:3000`
---

## Uso

1. Asegúrate de que Apache y MySQL de XAMPP estén en ejecución.
2. Arranca el servidor de desarrollo de React (`npm start`).
3. Abre `http://localhost:3000` en tu navegador.
4. Inicia sesión y comienza a usar la aplicación.

---

## Estructura de Carpetas
```bash
  brangus/
  ├── App/
  │   ├── Controller/
  │   │   ├── LoginController.php
  │   │   └── TareaController.php
  │   ├── Middleware/
  │   │   └── AuthMiddleware.php
  │   └── Model/
  │       ├── UsuarioModel.php
  │       └── TareaModel.php
  ├── Public/
  │   ├── index.php
  │   └── .htaccess
  ├── Vendor/
  │   ├── AutoLoad.php
  │   ├── Connection.php
  │   ├── DB.php
  │   ├── EnvLoader.php
  │   ├── JWT.php
  │   └── Route.php
  ├── routes/
  │   └── api.php
  └── .env
```

## Documentación de la API

| Método | Endpoint             | Descripción                   | Protección|
| ------ | -------------------- | ---------------------         |---------- |
| POST   | `/login`             |Inicia sesión y genera un token|   **NO**  |
| GET    | `/tareas`            |Lista todas las tareas         |   **SI**  |
| POST   | `/tareas`            |Crea una nueva tarea           |   **SI**  | 
| PUT    | `/tareas/{id}`       |Actualiza una tarea            |   **SI**  |
| DELETE | `/tareas/{id}`       |Elimina una tarea              |   **SI**  |
| GET    | `/test`              |Ruta pública de prueba         |   **NO**  |


---


