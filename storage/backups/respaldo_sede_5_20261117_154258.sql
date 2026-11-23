-- =====================================================
-- Respaldo del Sistema DRFP v4.0
-- Generado: 17/11/2026 15:42
-- Generado por: Admin B.C Manuelita Sáenz <admin05@drfp.com>
-- Alcance: Solo datos de la sede 5
-- Formato: INSERT ... ON DUPLICATE KEY UPDATE (importación segura)
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;
SET UNIQUE_CHECKS=0;

-- Tabla: users (1 registros)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('13', 'Admin B.C Manuelita Sáenz', 'admin05@drfp.com', '$2y$10$fabJzA1BYhZIZ7estdeHcO/DkmwqBksYJjFFr1W1te1zYjMg2ZeZa', '1', 'habilitado', '5', 'Q0RruAoQBqjYEuIdmS0mfdu5ykocShTozBjtigeUdcjfBweFDY8mAmDAyXXX', '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);

-- Tabla: encargados (0 registros)

-- Tabla: salas (0 registros)

-- Tabla: pasillos (0 registros)

-- Tabla: estanterias (0 registros)

-- Tabla: inventario (0 registros)

-- Tabla: carnets (0 registros)

-- Tabla: prestamo (0 registros)

-- Tabla: prestamos_internos (0 registros)

-- Tabla: sanciones (0 registros)

-- Tabla: historial_danos_libros (0 registros)

-- Tabla: visitas (0 registros)

-- Tabla: visitas_guiadas (0 registros)

-- Tabla: planes_vacacionales (0 registros)

-- Tabla: actividades (0 registros)

SET FOREIGN_KEY_CHECKS=1;
SET UNIQUE_CHECKS=1;
