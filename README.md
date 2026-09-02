# FarmaMedia

Plataforma interna de gestión y visualización de recursos multimedia para Farmalisto.

---

## Tecnologías

- **PHP 8+** — backend sin frameworks, arquitectura MVC propia
- **MySQL** — base de datos relacional
- **Bootstrap 5.3** — estilos y componentes responsive
- **Bootstrap Icons** — iconografía
- **Google Fonts** — tipografías DM Sans y Space Grotesk
- **SweetAlert2** — alertas y confirmaciones
- **Apache** — servidor web con mod_rewrite (`.htaccess`)

---

## Requisitos para levantar en local

- PHP 8.0 o superior
- MySQL 5.7 o superior
- Apache con `mod_rewrite` habilitado
- Laragon, XAMPP o similar

### Pasos

1. Clona el repositorio en la carpeta de tu servidor local:
   ```bash
   git clone https://github.com/desarrollo-farmalisto/FarmaMedia.git
   ```

2. Crea la base de datos en MySQL:
   ```sql
   CREATE DATABASE farmamedia CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. Importa el esquema de tablas (solicitar archivo `.sql` al equipo).

4. Configura las variables en `config/app.php` (ver sección abajo).

5. Asegúrate de que la carpeta `public/uploads/` tenga permisos de escritura:
   ```bash
   chmod 755 public/uploads
   ```

6. Crea un virtualhost que apunte a la raíz del proyecto. Ejemplo en Laragon:
   - Dominio: `farmamedia.test`
   - Carpeta: `C:/laragon/www/FarmaMedia`

---

## Variables a configurar

Todas las variables están en `config/app.php`.

### Local

```php
const APP_URL  = 'http://farmamedia.test';

const DB_HOST = 'localhost';
const DB_NAME = 'farmamedia';
const DB_USER = 'root';
const DB_PASS = '';
```

### Producción

```php
const APP_URL  = 'https://tudominio.com';

const DB_HOST = 'localhost';       // host del servidor de producción
const DB_NAME = 'nombre_bd';       // nombre de la base de datos
const DB_USER = 'usuario_bd';      // usuario de la base de datos
const DB_PASS = 'contraseña_bd';   // contraseña de la base de datos
```

> `UPLOAD_PATH` y `UPLOAD_URL` se calculan automáticamente a partir de `APP_ROOT` y `APP_URL`, no requieren cambio manual.

---

## Roles de usuario

Los usuarios se gestionan directamente en la tabla `users` de la base de datos.

| Rol | Acceso |
|-----|--------|
| `admin` | Panel de administración + recursos |
| `watcher` | Solo visualización de recursos |

El acceso se valida por correo electrónico al ingresar.

---

## Base de datos

### Esquema de tablas

```sql
CREATE TABLE `users` (
  `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
  `nombre`     VARCHAR(150)    NOT NULL,
  `email`      VARCHAR(255)    NOT NULL,
  `password`   VARCHAR(255)    NOT NULL,
  `rol`        ENUM('admin','watcher') NOT NULL DEFAULT 'watcher',
  `status`     TINYINT(1)      NOT NULL DEFAULT 1,
  `created_at` DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME        NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fm_recursos` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(255)  NOT NULL,
  `descripcion` TEXT          NOT NULL,
  `tipo`        VARCHAR(50)   NOT NULL,
  `archivo`     VARCHAR(255)  NULL,
  `link`        VARCHAR(500)  NULL,
  `status`      TINYINT(1)    NOT NULL DEFAULT 1,
  `created_at`  DATETIME      NOT NULL,
  `updated_at`  DATETIME      NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fm_comments` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `description`    TEXT         NOT NULL,
  `fm_recurso_id`  INT UNSIGNED NOT NULL,
  `status`         TINYINT(1)   NOT NULL DEFAULT 1,
  `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     DATETIME     NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fm_recurso_id` (`fm_recurso_id`),
  CONSTRAINT `fk_comment_recurso` FOREIGN KEY (`fm_recurso_id`) REFERENCES `fm_recursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Usuarios iniciales

```sql
INSERT INTO `users` (`nombre`, `email`, `password`, `rol`, `status`, `created_at`) VALUES
('Wilmar Guerrero',     'wilmar.guerrero@farmalisto.com.co',    '', 'admin',   1, '2026-09-02 08:57:54'),
('Dennys Garcia',       'disenador.grafico@farmalisto.com.co',  '', 'admin',   1, '2026-09-02 08:58:37'),
('Edwin Montenegro',    'marketing@farmalisto.com.co',          '', 'admin',   1, '2026-09-02 08:59:14'),
('Visitado Farmalisto', 'reportes.redes@farmalisto.com.co',     '', 'watcher', 1, '2026-09-02 09:05:11');
```
