-- ============================================================
-- SISTEMA DRFP - EXPORTACION DE BASE DE DATOS (INVENTARIO)
-- Generado por: Administrador General
-- Sede: Biblioteca Dr. Félix Pifano (General)
-- Plazo de registros: Rango personalizado (2026-11-16 al 2026-11-16)
-- Fecha de exportación: 2026-11-16 17:16:42
-- Total de libros exportados: 4
-- ============================================================

SET FOREIGN_KEY_CHECKS=0;

INSERT INTO `inventario` (`cota`, `sede_id`, `titulo`, `autor`, `volumen`, `categoria`, `ubicacion`, `ingreso`, `estado`, `observaciones`, `estanteria_id`, `dias_prestamo`, `created_at`, `updated_at`)
VALUES ('0500', 2, 'El Principito', 'Blas', 1, 'literatura', '2', 'Donacion 2026', 'bueno', NULL, NULL, NULL, '2026-11-16 15:57:42', '2026-11-16 16:11:45')
ON DUPLICATE KEY UPDATE
  `sede_id` = VALUES(`sede_id`),
  `titulo` = VALUES(`titulo`),
  `autor` = VALUES(`autor`),
  `volumen` = VALUES(`volumen`),
  `categoria` = VALUES(`categoria`),
  `ubicacion` = VALUES(`ubicacion`),
  `ingreso` = VALUES(`ingreso`),
  `estado` = VALUES(`estado`),
  `observaciones` = VALUES(`observaciones`),
  `estanteria_id` = VALUES(`estanteria_id`),
  `dias_prestamo` = VALUES(`dias_prestamo`),
  `updated_at` = VALUES(`updated_at`);

INSERT INTO `inventario` (`cota`, `sede_id`, `titulo`, `autor`, `volumen`, `categoria`, `ubicacion`, `ingreso`, `estado`, `observaciones`, `estanteria_id`, `dias_prestamo`, `created_at`, `updated_at`)
VALUES ('0505', 1, 'Don Quijote de la Mancha II', 'Blas', 3, 'literatura', '1', 'Donacion 2026', 'deteriorado', 'asasas', NULL, NULL, '2026-11-16 16:10:51', '2026-11-16 16:10:51')
ON DUPLICATE KEY UPDATE
  `sede_id` = VALUES(`sede_id`),
  `titulo` = VALUES(`titulo`),
  `autor` = VALUES(`autor`),
  `volumen` = VALUES(`volumen`),
  `categoria` = VALUES(`categoria`),
  `ubicacion` = VALUES(`ubicacion`),
  `ingreso` = VALUES(`ingreso`),
  `estado` = VALUES(`estado`),
  `observaciones` = VALUES(`observaciones`),
  `estanteria_id` = VALUES(`estanteria_id`),
  `dias_prestamo` = VALUES(`dias_prestamo`),
  `updated_at` = VALUES(`updated_at`);

INSERT INTO `inventario` (`cota`, `sede_id`, `titulo`, `autor`, `volumen`, `categoria`, `ubicacion`, `ingreso`, `estado`, `observaciones`, `estanteria_id`, `dias_prestamo`, `created_at`, `updated_at`)
VALUES ('111.1', 2, 'El principito', 'Miguel de Cervantes', 3, 'historia', '2', 'Donacion 2026', 'bueno', NULL, NULL, 4, '2026-11-16 16:11:18', '2026-11-16 16:11:18')
ON DUPLICATE KEY UPDATE
  `sede_id` = VALUES(`sede_id`),
  `titulo` = VALUES(`titulo`),
  `autor` = VALUES(`autor`),
  `volumen` = VALUES(`volumen`),
  `categoria` = VALUES(`categoria`),
  `ubicacion` = VALUES(`ubicacion`),
  `ingreso` = VALUES(`ingreso`),
  `estado` = VALUES(`estado`),
  `observaciones` = VALUES(`observaciones`),
  `estanteria_id` = VALUES(`estanteria_id`),
  `dias_prestamo` = VALUES(`dias_prestamo`),
  `updated_at` = VALUES(`updated_at`);

INSERT INTO `inventario` (`cota`, `sede_id`, `titulo`, `autor`, `volumen`, `categoria`, `ubicacion`, `ingreso`, `estado`, `observaciones`, `estanteria_id`, `dias_prestamo`, `created_at`, `updated_at`)
VALUES ('666.6', 3, 'Don Quijote de la Mancha II', 'Miguel de Cervantes', 2, 'historia', '3', 'Donacion 2026', 'bueno', NULL, NULL, NULL, '2026-11-16 16:19:09', '2026-11-16 16:19:09')
ON DUPLICATE KEY UPDATE
  `sede_id` = VALUES(`sede_id`),
  `titulo` = VALUES(`titulo`),
  `autor` = VALUES(`autor`),
  `volumen` = VALUES(`volumen`),
  `categoria` = VALUES(`categoria`),
  `ubicacion` = VALUES(`ubicacion`),
  `ingreso` = VALUES(`ingreso`),
  `estado` = VALUES(`estado`),
  `observaciones` = VALUES(`observaciones`),
  `estanteria_id` = VALUES(`estanteria_id`),
  `dias_prestamo` = VALUES(`dias_prestamo`),
  `updated_at` = VALUES(`updated_at`);

SET FOREIGN_KEY_CHECKS=1;
