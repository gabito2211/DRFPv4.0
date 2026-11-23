-- ============================================================
-- SISTEMA DRFP - EXPORTACION DE BASE DE DATOS (INVENTARIO)
-- Generado por: Administrador General
-- Sede: Biblioteca Dr. Félix Pifano (General)
-- Fecha: 2026-11-16 17:03:18
-- Total de libros exportados: 1
-- ============================================================

SET FOREIGN_KEY_CHECKS=0;

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
