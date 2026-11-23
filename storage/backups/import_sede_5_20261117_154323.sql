DROP TABLE `users`;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `rol`, `estado`, `sede_id`, `remember_token`, `created_at`, `updated_at`) VALUES (999, X, Y, Z, 1, 1, 5, NULL, NULL, NULL)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
