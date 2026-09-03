-- 1. Crear tabla fm_archivos
CREATE TABLE `fm_archivos` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `recurso_id`  INT UNSIGNED  NOT NULL,
  `nombre`      VARCHAR(255)  NOT NULL,
  `descripcion` TEXT          NOT NULL,
  `archivo`     VARCHAR(255)  NULL,
  `link`        VARCHAR(500)  NULL,
  `orden`       INT           NULL,
  `created_at`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `recurso_id` (`recurso_id`),
  CONSTRAINT `fk_archivo_recurso` FOREIGN KEY (`recurso_id`) REFERENCES `fm_recursos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Migrar datos existentes
INSERT INTO `fm_archivos` (`recurso_id`, `nombre`, `descripcion`, `archivo`, `link`, `orden`)
SELECT `id`, `nombre`, `descripcion`, `archivo`, `link`, `orden`
FROM `fm_recursos`;

-- 3. Quitar columnas que se mueven a fm_archivos
ALTER TABLE `fm_recursos`
  DROP COLUMN `nombre`,
  DROP COLUMN `descripcion`,
  DROP COLUMN `archivo`,
  DROP COLUMN `link`,
  DROP COLUMN `orden`;
