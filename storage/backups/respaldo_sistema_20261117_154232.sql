-- =====================================================
-- Respaldo del Sistema DRFP v4.0
-- Generado: 17/11/2026 15:42
-- Generado por: Administrador General <admin@gmail.com>
-- Alcance: TODO EL SISTEMA
-- Formato: INSERT ... ON DUPLICATE KEY UPDATE (importación segura)
-- =====================================================

SET FOREIGN_KEY_CHECKS=0;
SET UNIQUE_CHECKS=0;

-- Tabla: sedes (26 registros)
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('1', 'san-felipe', 'Biblioteca Dr. Félix Pifano', 'esquina de la Calle 13 con Avenida 2, dentro del Complejo Cultural Andrés Bello en San Felipe, Estado Yaracuy.', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('2', 'san-felipe', 'S.L Luis Alberto Principal', 'Avenida libertador entre Calle 5 y 6, Sede del ateneo \"Cirilo Menoza\" Marin', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('3', 'san-felipe', 'S.L San Javier', 'Calle principal, Entre Calle 1 y 2 Casa de la Cultura San Juan Javier', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('4', 'san-felipe', 'B.C Moral y Luces', 'Casa de la Cultura, Comunnidad Valle Verde Trasnversal 3 Entre Calle 9 y 10 Transversal 3 entres calles 9 y 10', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('5', 'san-felipe', 'B.C Manuelita Sáenz', 'Urb. Juan José de Maya, manzana 4', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('6', 'san-felipe', 'B.C Ciudadela', 'Av. Robert Serra con Av. Williams Lara. Ciudadela', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('7', 'san-felipe', 'B.C Higuerón', 'Calle principal C.C. Higuerón', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('8', 'pena', 'B.P Soc. Oswaldo Méndez', 'Calle 19 esquina carrera 8 y 9 Yaritagua', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('9', 'pena', 'S.L Simón Rodríguez', 'Av. principal Las Velas casa vacacional. Las Velas', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('10', 'pena', 'B.C El Palmar', 'Calle principal, centro integral comunitario', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('11', 'bolivar', 'B.P Josefa Marín de Narváez', 'Calle Sucre, frente a la plaza Bolívar. Aroa', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('12', 'bolivar', 'P.P Ingrid Fernández', 'Calle principal, sector El Cruce, comunidad Quebrada Honda', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('13', 'nirgua', 'B.P Rómulo Gallegos', 'Av. 5 entre calles 8 y 9 frente a la plaza Bolívar', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('14', 'nirgua', 'S.L Euclides Herrera', 'Casa de la cultura \"Euclides Herrera\" frente a la plaza Bolívar de Salom', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('15', 'cocorote', 'Biblioteca Publica Simon Rodriguez', 'centro de la calle 7 entre Av 6 y 5', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('16', 'paez', 'B.P José Alfonso Daza', 'Casa de la cultura \"Alberto Beto Ramírez\" carrera 4 esquina calle 9. Sector El Silencio', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('17', 'paez', 'S.L Arturo Uslar Pietri', 'Frente a la plaza Bolívar comunidad La Blanquera', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('18', 'independencia', 'S.L Celsa Mendoza Reyes', 'Prolongación calle 39, módulo de funda comunal. Sabaneta', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('19', 'veroes', 'S.L Simón Rodríguez', 'Calle Ecuador, al lado de la plaza Bolívar comunidad Casimiro Vásquez', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('20', 'aristides-bastidas', 'B.P Julia Rosa de López', 'Casa de la cultura Arístides Bastidas calle principal sector centro. San Pablo', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('21', 'manuel-monge', 'B.P Simón Bolívar', 'Casa de cultura Alí Primera urb. El Centro II Yumare', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('22', 'san-felipe', '', '', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('23', 'urachiche', 'B.P Domingo Oviedo Parada', 'Casa de cultura José Félix Rivas entre calles Páez y Ayacucho Guama', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('24', 'sucre', 'B.P Mercedes Cordido', 'Casa de cultura José Félix Rivas entre calles Páez y Ayacucho Guama', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('25', 'bruzual', 'B.P Riben Suárez Moreno', 'Calle 11 cruce Av. 5 casa de cultura José Parra Chivacoa', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `sedes` (`id`, `comunidad`, `biblioteca`, `direccion`, `encargado_id`, `cedula`, `created_at`, `updated_at`) VALUES ('26', 'bruzual', 'B.C Poa Poa', 'Calle principal diagonal a la escuela, comunidad Poa Poa', NULL, NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `comunidad` = VALUES(`comunidad`), `biblioteca` = VALUES(`biblioteca`), `direccion` = VALUES(`direccion`), `encargado_id` = VALUES(`encargado_id`), `cedula` = VALUES(`cedula`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);

-- Tabla: autores (160 registros)
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('1', 'Miguel de Cervantes', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('2', 'William Shakespeare', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('3', 'Gabriel García Márquez', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('4', 'Jorge Luis Borges', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('5', 'Fyodor Dostoyevsky', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('6', 'León Tolstói', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('7', 'Victor Hugo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('8', 'Charles Dickens', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('9', 'Jane Austen', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('10', 'Mark Twain', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('11', 'Edgar Allan Poe', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('12', 'Franz Kafka', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('13', 'Hermann Hesse', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('14', 'Albert Camus', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('15', 'Jean-Paul Sartre', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('16', 'Simone de Beauvoir', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('17', 'Marcel Proust', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('18', 'James Joyce', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('19', 'Federico García Lorca', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('20', 'Antonio Machado', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('21', 'Gustavo Adolfo Bécquer', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('22', 'Lope de Vega', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('23', 'Francisco de Quevedo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('24', 'Rubén Darío', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('25', 'Pablo Neruda', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('26', 'Octavio Paz', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('27', 'Julio Cortázar', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('28', 'Mario Vargas Llosa', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('29', 'Carlos Fuentes', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('30', 'Juan Rulfo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('31', 'Miguel Ángel Asturias', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('32', 'Isabel Allende', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('33', 'Jorge Ibargüengoitia', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('34', 'Elena Poniatowska', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('35', 'Rosario Castellanos', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('36', 'Manuel Puig', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('37', 'Julio Ramón Ribeyro', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('38', 'César Vallejo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('39', 'José Martí', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('40', 'José Hernández', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('41', 'Domingo Faustino Sarmiento', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('42', 'Esteban Echeverría', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('43', 'Ricardo Güiraldes', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('44', 'Roberto Arlt', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('45', 'Adolfo Bioy Casares', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('46', 'Ernesto Sábato', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('47', 'Leopoldo Lugones', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('48', 'Horacio Quiroga', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('49', 'Delmira Agustini', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('50', 'Juana de Ibarbourou', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('51', 'Mario Benedetti', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('52', 'Juan Carlos Onetti', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('53', 'Felisberto Hernández', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('54', 'José Enrique Rodó', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('55', 'Gabriela Mistral', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('56', 'Vicente Huidobro', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('57', 'Nicanor Parra', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('58', 'Joaquín Edwards Bello', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('59', 'Mariano Latorre', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('60', 'Volodia Teitelboim', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('61', 'Rómulo Gallegos', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('62', 'Teresa de la Parra', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('63', 'Arturo Uslar Pietri', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('64', 'Miguel Otero Silva', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('65', 'Adriano González León', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('66', 'Salvador Garmendia', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('67', 'José Rafael Pocaterra', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('68', 'Rufino Blanco Fombona', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('69', 'Manuel Vicente Magallanes', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('70', 'Andrés Eloy Blanco', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('71', 'Aquiles Nazoa', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('72', 'Alfredo Sadel', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('73', 'Armando Reverón', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('74', 'Antonio Lauro', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('75', 'Ramón Díaz Sánchez', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('76', 'Miguel Ramón Utrera', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('77', 'Guillermo Meneses', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('78', 'Antonia Palacios', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('79', 'Luisa Josefina Hernández', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('80', 'Julio Garmendia', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('81', 'Pedro Sotillo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('82', 'Enrique Bernardo Núñez', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('83', 'Alfredo Armas Alfonzo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('84', 'Francisco Herrera Luque', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('85', 'Francisco Massiani', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('86', 'Edmundo Aray', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('87', 'Alfredo Chacón', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('88', 'Elizabeth Schön', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('89', 'Hannibal Vaux', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('90', 'Fernando Cabrices', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('91', 'Juan Sánchez Peláez', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('92', 'Rafael Cadenas', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('93', 'Eugenio Montejo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('94', 'Yolanda Osweta', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('95', 'Blanca Strepponi', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('96', 'Ana Enriqueta Terán', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('97', 'Ida Gramcko', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('98', 'Anteo Ortega', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('99', 'Sergio Medina', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('100', 'Delfina Torrealba', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('101', 'Hans Christian Andersen', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('102', 'Los Hermanos Grimm', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('103', 'Lewis Carroll', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('104', 'Robert Louis Stevenson', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('105', 'Jules Verne', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('106', 'H. G. Wells', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('107', 'Rudyard Kipling', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('108', 'Astrid Lindgren', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('109', 'Roald Dahl', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('110', 'Antoine de Saint-Exupéry', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('111', 'J. R. R. Tolkien', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('112', 'C. S. Lewis', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('113', 'Laura Ingalls Wilder', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('114', 'Louisa May Alcott', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('115', 'Johanna Spyri', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('116', 'Carlo Collodi', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('117', 'Daniel Defoe', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('118', 'Jonathan Swift', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('119', 'Mary Shelley', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('120', 'Bram Stoker', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('121', 'Arthur Conan Doyle', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('122', 'Agatha Christie', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('123', 'Jules Renard', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('124', 'Kenneth Grahame', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('125', 'Frances Hodgson Burnett', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('126', 'L. Frank Baum', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('127', 'Eleanor H. Porter', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('128', 'Louise Erdrich', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('129', 'Elena Fortún', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('130', 'Gloria Fuertes', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('131', 'Platón', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('132', 'Aristóteles', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('133', 'Sócrates', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('134', 'Sun Tzu', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('135', 'Niccolò Maquiavelo', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('136', 'Thomas More', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('137', 'René Descartes', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('138', 'Immanuel Kant', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('139', 'Friedrich Nietzsche', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('140', 'Karl Marx', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('141', 'Friedrich Engels', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('142', 'Simón Rodríguez', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('143', 'Simón Bolívar', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('144', 'Andrés Bello', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('145', 'Miguel de Unamuno', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('146', 'Ortega y Gasset', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('147', 'Bertrand Russell', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('148', 'Sigmund Freud', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('149', 'Carl Jung', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('150', 'Stephen Hawking', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('151', 'Carl Sagan', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('152', 'Charles Darwin', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('153', 'Isaac Newton', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('154', 'Marie Curie', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('155', 'Albert Einstein', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('156', 'Galileo Galilei', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('157', 'Johannes Kepler', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('158', 'Nicolás Copérnico', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('159', 'Antoine Lavoisier', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `autores` (`id`, `nombre`, `created_at`, `updated_at`) VALUES ('160', 'Alexander von Humboldt', '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `nombre` = VALUES(`nombre`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);

-- Tabla: bitacoras (32 registros)
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('1', '2', 'Recepcion General', 'logout', 'autenticacion', 'El usuario \'Recepcion General\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:35:12', '2026-11-17 14:35:12')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('2', '2', 'Recepcion General', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Recepcion General\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_14-35-12.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:35:13', '2026-11-17 14:35:13')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('3', NULL, 'Sistema', 'login-fallido', 'autenticacion', 'Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.', NULL, '{\"email\":\"admin@gmail.com\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:35:27', '2026-11-17 14:35:27')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('4', '1', 'Administrador General', 'login', 'autenticacion', 'El usuario \'Administrador General\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:35:39', '2026-11-17 14:35:39')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('5', '1', 'Administrador General', 'logout', 'autenticacion', 'El usuario \'Administrador General\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:36:02', '2026-11-17 14:36:02')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('6', '1', 'Administrador General', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_14-36-02.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:36:02', '2026-11-17 14:36:02')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('7', '3', 'Almacenista General', 'login-fallido', 'autenticacion', 'Acceso denegado por sede incorrecta: \'Almacenista General\' (al@gmail.com) intentó ingresar a una sede que no le corresponde.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:36:19', '2026-11-17 14:36:19')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('8', '3', 'Almacenista General', 'login', 'autenticacion', 'El usuario \'Almacenista General\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:37:02', '2026-11-17 14:37:02')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('9', '3', 'Almacenista General', 'logout', 'autenticacion', 'El usuario \'Almacenista General\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:37:13', '2026-11-17 14:37:13')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('10', '3', 'Almacenista General', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Almacenista General\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_14-37-13.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:37:13', '2026-11-17 14:37:13')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('11', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'login', 'autenticacion', 'El usuario \'Admin Biblioteca Publica Simon Rodriguez\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:49:52', '2026-11-17 14:49:52')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('12', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'logout', 'autenticacion', 'El usuario \'Admin Biblioteca Publica Simon Rodriguez\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:50:01', '2026-11-17 14:50:01')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('13', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Admin Biblioteca Publica Simon Rodriguez\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_14-50-01.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:50:01', '2026-11-17 14:50:01')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('14', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'login-fallido', 'autenticacion', 'Acceso denegado por sede incorrecta: \'Admin Biblioteca Publica Simon Rodriguez\' (admin15@drfp.com) intentó ingresar a una sede que no le corresponde.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:50:36', '2026-11-17 14:50:36')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('15', '25', 'Admin S.L Arturo Uslar Pietri', 'login-fallido', 'autenticacion', 'Acceso denegado por sede incorrecta: \'Admin S.L Arturo Uslar Pietri\' (admin17@drfp.com) intentó ingresar a una sede que no le corresponde.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:50:45', '2026-11-17 14:50:45')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('16', NULL, 'Sistema', 'login-fallido', 'autenticacion', 'Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.', NULL, '{\"email\":\"admin@gmail.com\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:51:03', '2026-11-17 14:51:03')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('17', '1', 'Administrador General', 'login', 'autenticacion', 'El usuario \'Administrador General\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:51:14', '2026-11-17 14:51:14')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('18', '1', 'Administrador General', 'logout', 'autenticacion', 'El usuario \'Administrador General\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:51:20', '2026-11-17 14:51:20')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('19', '1', 'Administrador General', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_14-51-20.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 14:51:20', '2026-11-17 14:51:20')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('20', NULL, 'Sistema', 'login-fallido', 'autenticacion', 'Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.', NULL, '{\"email\":\"admin@gmail.com\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:17:40', '2026-11-17 15:17:40')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('21', '1', 'Administrador General', 'login', 'autenticacion', 'El usuario \'Administrador General\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:17:48', '2026-11-17 15:17:48')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('22', '1', 'Administrador General', 'logout', 'autenticacion', 'El usuario \'Administrador General\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:17:54', '2026-11-17 15:17:54')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('23', '1', 'Administrador General', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_15-17-54.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:17:54', '2026-11-17 15:17:54')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('24', NULL, 'Sistema', 'login-fallido', 'autenticacion', 'Intento de acceso con credenciales incorrectas para el correo \'admin@gmail.com\'.', NULL, '{\"email\":\"admin@gmail.com\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:28:04', '2026-11-17 15:28:04')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('25', '1', 'Administrador General', 'login', 'autenticacion', 'El usuario \'Administrador General\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:28:19', '2026-11-17 15:28:19')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('26', '1', 'Administrador General', 'logout', 'autenticacion', 'El usuario \'Administrador General\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:28:46', '2026-11-17 15:28:46')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('27', '1', 'Administrador General', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Administrador General\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_15-28-46.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:28:46', '2026-11-17 15:28:46')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('28', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'login', 'autenticacion', 'El usuario \'Admin Biblioteca Publica Simon Rodriguez\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:29:36', '2026-11-17 15:29:36')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('29', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'logout', 'autenticacion', 'El usuario \'Admin Biblioteca Publica Simon Rodriguez\' cerró sesión.', NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:29:59', '2026-11-17 15:29:59')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('30', '23', 'Admin Biblioteca Publica Simon Rodriguez', 'backup', 'sistema', 'Se generó un respaldo de la base de datos al cerrar sesión \'Admin Biblioteca Publica Simon Rodriguez\'.', NULL, '{\"archivo\":\"respaldo_DRFP_2026-11-17_15-29-59.sql\"}', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:160.0) Gecko/20100101 Firefox/160.0', '2026-11-17 15:29:59', '2026-11-17 15:29:59')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('31', '1', 'Administrador General', 'login', 'autenticacion', 'El usuario \'Administrador General\' inició sesión correctamente.', NULL, NULL, '127.0.0.1', 'curl/8.13.0', '2026-11-17 15:41:38', '2026-11-17 15:41:38')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `bitacoras` (`id`, `user_id`, `user_name`, `accion`, `modulo`, `descripcion`, `datos_antes`, `datos_despues`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES ('32', '1', 'Administrador General', 'export', 'sistema', 'Se exportó el respaldo del sistema (254 registros en 19 tablas, alcance: todo el sistema).', NULL, '{\"archivo\":\"respaldo_sistema_20261117_154147.sql\",\"total\":254,\"tablas\":{\"sedes\":26,\"autores\":160,\"bitacoras\":31,\"users\":30,\"encargados\":0,\"salas\":7,\"pasillos\":0,\"estanterias\":0,\"personas\":0,\"inventario\":0,\"carnets\":0,\"prestamo\":0,\"prestamos_internos\":0,\"sanciones\":0,\"historial_danos_libros\":0,\"visitas\":0,\"visitas_guiadas\":0,\"planes_vacacionales\":0,\"actividades\":0}}', '127.0.0.1', 'curl/8.13.0', '2026-11-17 15:41:47', '2026-11-17 15:41:47')
ON DUPLICATE KEY UPDATE `user_id` = VALUES(`user_id`), `user_name` = VALUES(`user_name`), `accion` = VALUES(`accion`), `modulo` = VALUES(`modulo`), `descripcion` = VALUES(`descripcion`), `datos_antes` = VALUES(`datos_antes`), `datos_despues` = VALUES(`datos_despues`), `ip_address` = VALUES(`ip_address`), `user_agent` = VALUES(`user_agent`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);

-- Tabla: users (30 registros)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'Administrador General', 'admin@gmail.com', '$2y$10$N2q4wgwcHraQol3eLcnfqOyUOogAg94G3yMDNPe0WEnpP463N.uk6', '1', 'habilitado', '1', 'bxUzKUZpUKkvEd0S2MDelXXNXzW5mZtElwHjOhJylKSk04Vv3pJbzs7F0fq2', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'Recepcion General', 're@gmail.com', '$2y$10$ze/txbCMcK8Mq8Uo34v8MOS8wPULqBQ9HX4ka8.6M5yKWDKESqHmm', '2', 'habilitado', '1', 'ILVZymr4Db4F0BUP4O5Hs7KEqkQlc5T3KRb9ZlmxCbyvcVMvFn6XP34r8oE0', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('3', 'Almacenista General', 'al@gmail.com', '$2y$10$XzHM88IPyv4N06joSAz5AO3.4hJJgxnRa7TNjBzQfKuWrz9KGktBK', '3', 'habilitado', '1', '6dg8CjbQyYssle1HdM1HKSJNcqAUBqUB6m2i3vXAHEQf7Owh1MYhD8N5FzLW', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('4', 'cocorote', 'cocorote@gmail.com', '$2y$10$AiWanVTFZM7OABPACM0oq.ubymyGstaPBW8r/jUtkHWaq//JRZV2a', '1', 'habilitado', '15', NULL, '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('9', 'Admin Biblioteca Dr. Félix Pifano', 'admin01@drfp.com', '$2y$10$oPHqmX6wS5KpoMLY7edtvODLOM0byPdYarSOlPSBBCYsZtzfWDvEy', '1', 'habilitado', '1', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('10', 'Admin S.L Luis Alberto Principal', 'admin02@drfp.com', '$2y$10$zeRATcVBY9r8BAajeUUeR.qRyNLQbxsN81B7jd5IpDwdiAWtb7bXe', '1', 'habilitado', '2', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('11', 'Admin S.L San Javier', 'admin03@drfp.com', '$2y$10$RDJHqsvQlzV0xTu6EhSa.u6eo.AYApYhxbXLD1WT.m8sJZZkSiVGi', '1', 'habilitado', '3', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('12', 'Admin B.C Moral y Luces', 'admin04@drfp.com', '$2y$10$1Y06EklnzDckeechXXcG5eY1ihzS4.qj6VlgYqVlfiGaVBzEzqtPy', '1', 'habilitado', '4', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('13', 'Admin B.C Manuelita Sáenz', 'admin05@drfp.com', '$2y$10$fabJzA1BYhZIZ7estdeHcO/DkmwqBksYJjFFr1W1te1zYjMg2ZeZa', '1', 'habilitado', '5', 'Q0RruAoQBqjYEuIdmS0mfdu5ykocShTozBjtigeUdcjfBweFDY8mAmDAyXXX', '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('14', 'Admin B.C Ciudadela', 'admin06@drfp.com', '$2y$10$SWfLKwtH2YY4vzSmFwGAnOeJnovsCpCgyNwu2r21LWjvao7LsN9.a', '1', 'habilitado', '6', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('15', 'Admin B.C Higuerón', 'admin07@drfp.com', '$2y$10$hiikzbqLi0yS8XAzjIn8l.6bxzKoA.7rAkxuM0iUJgE1FN0ITUusG', '1', 'habilitado', '7', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('16', 'Admin B.P Soc. Oswaldo Méndez', 'admin08@drfp.com', '$2y$10$Dj0.g8gzCxVopmyrrV4C/OZksvUS/mG1nrANcEJdEn3BTxSllMAPW', '1', 'habilitado', '8', NULL, '2026-11-17 14:43:24', '2026-11-17 14:43:24')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('17', 'Admin S.L Simón Rodríguez', 'admin09@drfp.com', '$2y$10$dAI37N.tg2CVzpstx9RL3uwJtzp6H0BK9hiN9Jcrs0MJLtYc16Tei', '1', 'habilitado', '9', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('18', 'Admin B.C El Palmar', 'admin10@drfp.com', '$2y$10$oBVNTW2kTTp5X.uk3bCb4..tZ7ohfcLTer6w0/f1QE648MkzNaUN.', '1', 'habilitado', '10', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('19', 'Admin B.P Josefa Marín de Narváez', 'admin11@drfp.com', '$2y$10$CYoZniQS5SsS85UYFFFH3uFYS5NczdmqtE/FGO3edbeWk3TIdF.HO', '1', 'habilitado', '11', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('20', 'Admin P.P Ingrid Fernández', 'admin12@drfp.com', '$2y$10$RhvciKxqRxZQeS5CJSfhtuvh4BChbwc04LM4RY7qtnxJ.qYMlra8K', '1', 'habilitado', '12', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('21', 'Admin B.P Rómulo Gallegos', 'admin13@drfp.com', '$2y$10$WoURdjvzLVpCGl4LdN1GB.K9Typ95L1t0Q.nnWMydV5qfmQCx6556', '1', 'habilitado', '13', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('22', 'Admin S.L Euclides Herrera', 'admin14@drfp.com', '$2y$10$1aq28EZsuOc/2iUh.Jh0uu1Ei/QF4dw/p4VZSFeINYnojWkLL8GgS', '1', 'habilitado', '14', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('23', 'Admin Biblioteca Publica Simon Rodriguez', 'admin15@drfp.com', '$2y$10$iNa94oIpovEjVGfv678/HeB5o0V3am9ceOWdaV3Zt0aHf3jKnXL.2', '1', 'habilitado', '15', 'cmIYGXLEIM3EMmGYBp8bQ159BUHj0eZygJvq9aq3RtsCyaHy7WSqCKeGpPP7', '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('24', 'Admin B.P José Alfonso Daza', 'admin16@drfp.com', '$2y$10$gfVdhg2179vk5sMcoOu9mOUt0.jnfyGXdVGSvQXXqmNGuKCMqUNwu', '1', 'habilitado', '16', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('25', 'Admin S.L Arturo Uslar Pietri', 'admin17@drfp.com', '$2y$10$Hb2g63iPnjk.zjYMAfFKPOV4Hh6.wWC9dOROKEy0zyrT6Nbox78sK', '1', 'habilitado', '17', 'BjEOhryqjnVjZdqSrogQbFjLSFeiJ49d7SIfjq1VTZSNA4aJBdcU4DTvXeUB', '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('26', 'Admin S.L Celsa Mendoza Reyes', 'admin18@drfp.com', '$2y$10$t4rxmLqPbJcPd5zwtShdzOWdhRl3r6PDEQN0Be6MdYektLvyf3NFC', '1', 'habilitado', '18', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('27', 'Admin S.L Simón Rodríguez', 'admin19@drfp.com', '$2y$10$rZcICckpcEyzq4irWySzzec7jJmyvecpGOIjTA35RCvC/IU7CWo3y', '1', 'habilitado', '19', NULL, '2026-11-17 14:43:25', '2026-11-17 14:43:25')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('28', 'Admin B.P Julia Rosa de López', 'admin20@drfp.com', '$2y$10$XhhaOcEMGidqxV0kJcq9jOwsxuoKF9TuSsDPeFO.8LADg0v7fZtXu', '1', 'habilitado', '20', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('29', 'Admin B.P Simón Bolívar', 'admin21@drfp.com', '$2y$10$P.coLpi0H.mUudo7hJSF3OjxI3viqM8k3z80CP75rxGsQNIe3jOjW', '1', 'habilitado', '21', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('30', 'Admin Sede 22', 'admin22@drfp.com', '$2y$10$lnXGgOG2jY4tLhR.2g0.DOH9Pfmu8hA2.xyQ8rPvV.Gur061oQIRi', '1', 'habilitado', '22', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('31', 'Admin B.P Domingo Oviedo Parada', 'admin23@drfp.com', '$2y$10$8ECtGQWjEqzPMfaoUuqM3uuVdMlXMf0QTmUJPR394rW.gcbb7fZre', '1', 'habilitado', '23', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('32', 'Admin B.P Mercedes Cordido', 'admin24@drfp.com', '$2y$10$shSiga7wGyLt6H4Q7D/m6O9vEKyXQifAd52mSAYpUz.VXBzzzmGVq', '1', 'habilitado', '24', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('33', 'Admin B.P Riben Suárez Moreno', 'admin25@drfp.com', '$2y$10$/o.mVSCRIojNIgT1ECzm6e14wI8JZTxuQJrvbtREB6ekhtS8vxiiS', '1', 'habilitado', '25', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES ('34', 'Admin B.C Poa Poa', 'admin26@drfp.com', '$2y$10$V0opmJSea0dWahNky1ARVuv32pn1bfTy2Uug68dhKj3AbrUuafOBC', '1', 'habilitado', '26', NULL, '2026-11-17 14:43:26', '2026-11-17 14:43:26')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `email` = VALUES(`email`), `password` = VALUES(`password`), `rol` = VALUES(`rol`), `estado` = VALUES(`estado`), `sede_id` = VALUES(`sede_id`), `remember_token` = VALUES(`remember_token`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);

-- Tabla: encargados (0 registros)

-- Tabla: salas (7 registros)
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('1', '1', 'Sala General', 'Sala general de libros', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('2', '1', 'Sala Infantil', 'Sala de literatura infantil', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('3', '1', 'Centro didáctico', 'Centro didáctico', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('4', '1', 'Sala estadal', 'Sala estadal', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('5', '1', 'Centro de Investigación', 'Centro de investigación', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('6', '1', 'Administración', 'Administración', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);
INSERT INTO `salas` (`id`, `sede_id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES ('7', '1', 'Presidencia', 'Presidencia', '2026-11-17 14:32:40', '2026-11-17 14:32:40')
ON DUPLICATE KEY UPDATE `sede_id` = VALUES(`sede_id`), `nombre` = VALUES(`nombre`), `descripcion` = VALUES(`descripcion`), `created_at` = VALUES(`created_at`), `updated_at` = VALUES(`updated_at`);

-- Tabla: pasillos (0 registros)

-- Tabla: estanterias (0 registros)

-- Tabla: personas (0 registros)

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
