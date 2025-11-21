-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-11-2025 a las 04:28:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `your_project_name`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `full_description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `modal_id` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `short_description`, `full_description`, `image`, `modal_id`, `is_active`, `order`, `created_at`, `updated_at`) VALUES
(6, 'Encuentro de todas las comunidades de la Redonda', 'Encuentro de todas las comunidades', 'El pasado 15/08 se realizo el encuentro que involucro a todas las comunidades tanto jovenes como mayores', 'announcements/1762300131_img-20250719-wa0077.jpg', 'modal_aHgsXXtK', 1, 1, '2025-11-05 02:48:26', '2025-11-05 02:48:51'),
(8, 'Corpus Christi 2025', 'La comunidad joven el dia 21/06/25 fue parte del Corpus Christi', 'El pasado 21/06/25 la comunidad joven y la comunidad la parroquia de Santa Ana fue parte del Corpus Christi', 'announcements/1762302383_img-20250622-wa0041.jpg', 'modal_F7twTjly', 1, 2, '2025-11-05 03:26:23', '2025-11-05 03:26:23'),
(9, 'Peña Folklorica 14/11/25', 'Compra tus entradas para la peña de La Redonda en Tamarisco', 'Se acerca la ultima peña comunitaria folklorica de La Redonda este 14 de noviembre de 2025 en tamarisco', 'announcements/1762302590_imagen-de-whatsapp-2025-10-27-a-las-211415-03c12cd8.jpg', 'modal_U69uGotq', 1, 0, '2025-11-05 03:29:50', '2025-11-05 03:29:50'),
(10, 'Peña Folklorica 14/11/25', 'El dìa 14/11/25 se llevará a cabo la peña folklorica comunitaria de La Redonda', 'El dìa 14/11/25 se llevará a cabo la peña folklorica comunitaria de La Redonda', 'announcements/1762554979_recurso-55.png', 'modal_OZwTe7Up', 1, 4, '2025-11-06 16:09:00', '2025-11-08 01:36:19'),
(11, 'Retiro de Jovenes', 'WGREGRG', 'REG3R5GRGRG3', 'announcements/1763217861_img-20250622-wa0041.jpg', 'modal_BrduRabV', 1, 3, '2025-11-15 17:44:21', '2025-11-15 17:44:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donations`
--

CREATE TABLE `donations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `frequency` varchar(255) NOT NULL,
  `card_holder` varchar(255) NOT NULL,
  `card_last_four` varchar(4) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `donations`
--

INSERT INTO `donations` (`id`, `amount`, `frequency`, `card_holder`, `card_last_four`, `email`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 10000.00, 'once', 'Tomas Espiro', '8959', 'test@laredonda.com', 'completed', NULL, '2025-10-22 02:00:58', '2025-10-22 02:00:58'),
(2, 12000.00, 'weekly', 'Tomas Espiro', '1545', 'admin@laredonda.com', 'completed', NULL, '2025-10-30 20:26:33', '2025-10-30 20:26:33'),
(3, 1500.00, 'biweekly', 'Tomas Espiro', '1555', 'totiespiro@gmail.com', 'completed', NULL, '2025-11-15 17:41:14', '2025-11-15 17:41:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evangelio_diario`
--

CREATE TABLE `evangelio_diario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contenido` text NOT NULL,
  `referencia` varchar(255) NOT NULL,
  `fecha` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `evangelio_diario`
--

INSERT INTO `evangelio_diario` (`id`, `contenido`, `referencia`, `fecha`, `created_at`, `updated_at`) VALUES
(1, '\"Porque tanto amó Dios al mundo que dio a su Hijo único, para que todo el que crea en él no perezca, sino que tenga vida eterna. Porque Dios no envió a su Hijo para juzgar al mundo, sino para que el mundo se salve por él.\"', 'Juan 3:16-18', '2025-10-30', '2025-10-31 01:21:26', '2025-10-31 01:21:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` enum('jovenes','adultos','mayores','especiales') NOT NULL,
  `age_range` varchar(255) DEFAULT NULL,
  `meeting_days` varchar(255) DEFAULT NULL,
  `meeting_time` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `group_materials`
--

CREATE TABLE `group_materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `group_role` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `group_materials`
--

INSERT INTO `group_materials` (`id`, `user_id`, `group_role`, `title`, `description`, `file_path`, `file_name`, `file_type`, `file_size`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 5, 'acutis', 'La Redonda', 'betbetrbe', 'group-materials/acutis/9dJPIpdqGoVvqdfrmZsncYWEkH695egr5B15Aq73.pdf', 'La Redonda Joven  Funcionalidades.pdf', 'pdf', 4346699, 1, '2025-11-08 20:41:23', '2025-11-08 20:41:23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `intentions`
--

CREATE TABLE `intentions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `intentions`
--

INSERT INTO `intentions` (`id`, `type`, `name`, `email`, `message`, `status`, `user_id`, `created_at`, `updated_at`) VALUES
(11, 'salud', 'Tomas', 'totiespiro@gmail.com', 'Intención de salud', 'pending', NULL, '2025-10-30 20:38:50', '2025-10-30 20:38:50'),
(12, 'accion-gracias', 'Juan', 'totiespiro@gmail.com', 'Intención de accion-gracias', 'pending', NULL, '2025-10-30 20:39:34', '2025-10-30 20:39:34'),
(13, 'salud', 'Pedro', 'test@laredonda.com', 'Intención de salud', 'pending', NULL, '2025-10-30 20:40:18', '2025-10-30 20:40:18'),
(14, 'salud', 'Tomas', 'tomas.espiro@davinci.edu.ar', 'Intención de salud', 'pending', NULL, '2025-11-15 17:42:09', '2025-11-15 17:42:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '2025_10_16_212725_add_social_fields_to_users_table', 1),
(8, '2025_10_16_220110_update_users_table_for_simple_auth', 1),
(19, '2014_10_12_000000_create_users_table', 2),
(20, '2014_10_12_100000_create_password_reset_tokens_table', 2),
(21, '2019_08_19_000000_create_failed_jobs_table', 2),
(22, '2019_12_14_000001_create_personal_access_tokens_table', 2),
(23, '2025_10_16_214641_create_intentions_table', 2),
(24, '2025_10_16_214646_create_donations_table', 2),
(25, '2025_10_16_223007_create_notifications_table', 2),
(26, '2025_10_17_174146_create_announcements_table', 2),
(27, '2025_10_17_181159_add_role_to_users_table', 2),
(28, '2025_10_17_195431_create_groups_table', 3),
(29, '2025_10_30_221548_crear_tabla_evangelio_diario', 4),
(30, '2025_11_08_003003_add_superadmin_user', 5),
(31, '2025_11_08_003217_add_superadmin_user', 6),
(32, '2025_11_08_003701_create_role_user_table', 7),
(33, '2025_11_08_012308_populate_roles_and_migrate_data', 8),
(34, '2025_11_08_024340_create_diario_entries_table', 9),
(35, '2025_11_08_164600_create_diario_entries_table', 10),
(36, '2025_11_08_164714_encrypt_existing_diario_entries', 10),
(37, '2025_11_08_170239_create_group_materials_table', 11),
(38, '2025_11_18_220959_add_diario_columns_to_users_table', 12),
(39, '2025_11_18_221137_drop_diario_entries_table', 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'Super Administrador', 'Acceso completo al sistema', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(2, 'admin', 'Administrador', 'Administración del sistema', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(3, 'admin_grupo_parroquial', 'Admin Grupo Parroquial', 'Administración de grupos parroquiales', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(4, 'catequesis', 'Catequesis', 'Responsable de catequesis', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(5, 'juveniles', 'Juveniles', 'Responsable de grupo juvenil', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(6, 'acutis', 'Acutis', 'Responsable de grupo Acutis', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(7, 'juan_pablo', 'Juan Pablo', 'Responsable de grupo Juan Pablo', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(8, 'coro', 'Coro', 'Responsable del coro', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(9, 'san_joaquin', 'San Joaquín', 'Responsable de grupo San Joaquín', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(10, 'santa_ana', 'Santa Ana', 'Responsable de grupo Santa Ana', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(11, 'ardillas', 'Ardillas', 'Responsable de grupo Ardillas', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(12, 'costureras', 'Costureras', 'Responsable de grupo Costureras', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(13, 'misioneros', 'Misioneros', 'Responsable de grupo Misioneros', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(14, 'caridad_comedor', 'Caridad y Comedor', 'Responsable de caridad y comedor', '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(15, 'user', 'Usuario', 'Usuario básico', '2025-11-08 04:23:27', '2025-11-08 04:23:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `diario_data` text DEFAULT NULL,
  `last_diario_entry` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `diario_data`, `last_diario_entry`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@laredonda.com', NULL, '$2y$12$3pRn7/rg3daEb1EdIbrFeeLy2iNyObytv.Kz0thd4Iu.YDLg.sa2y', '[{\"id\":2,\"title\":\"fvbreberbe\",\"content\":\"{\\\"nodes\\\":[]}\",\"type\":\"mapa_conceptual\",\"color\":\"#3b82f6\",\"is_favorite\":false,\"created_at\":\"2025-11-19 00:56:15\",\"updated_at\":\"2025-11-19 01:19:08\"}]', '2025-11-19 04:19:08', 'admin', NULL, '2025-10-17 21:54:40', '2025-11-19 04:19:08'),
(4, 'Super Administrador', 'superadmin@laredonda.com', NULL, '$2y$12$ky31tyVRjmk7KrUTtOUV/u73en.oP3uFMxMqAQdD1twnG/AfhIctG', NULL, NULL, 'superadmin', NULL, '2025-11-08 03:32:31', '2025-11-08 03:32:31'),
(5, 'Tomas Espiro', 'tomas.espiro@davinci.edu.ar', NULL, '$2y$12$s2.rVM8wEn5w7QYad6FKhuiurIdUV4eIKcSzEDiP030HkHtREvFHy', '[{\"id\":1,\"title\":\"vrvrv\",\"content\":\"<p>btebtgbet<\\/p>\",\"type\":\"texto\",\"color\":\"#3b82f6\",\"is_favorite\":false,\"created_at\":\"2025-11-18 23:48:08\",\"updated_at\":\"2025-11-18 23:48:08\"},{\"id\":2,\"title\":\"THTHBTHTHTHE\",\"content\":\"[{\\\"text\\\":\\\"bfgbrfberfberbbtr\\\",\\\"completed\\\":false}]\",\"type\":\"lista\",\"color\":\"#3b82f6\",\"is_favorite\":true,\"created_at\":\"2025-11-18 23:48:29\",\"updated_at\":\"2025-11-19 00:27:09\"}]', '2025-11-19 03:27:09', 'user', NULL, '2025-11-08 06:34:50', '2025-11-19 03:27:09'),
(6, 'Jeremias Gonzalez', 'totiespiro@gmail.com', NULL, '$2y$12$8rUmRexmvUjHaUvH29mHGOkZ66F/7yqPqO.ldAntR5lUNEMWUcuWi', NULL, NULL, 'user', NULL, '2025-11-08 20:22:07', '2025-11-08 20:22:07'),
(7, 'Gerardo Muñoz', 'test@laredonda.com', NULL, '$2y$12$nj4.9tcN6bBxdiY/J2r.zOckEkaTQ/hur.czVO/ucgaoZwwawMQU6', NULL, NULL, 'user', NULL, '2025-11-08 20:28:31', '2025-11-08 20:28:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_roles`
--

INSERT INTO `user_roles` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(3, 4, 1, '2025-11-08 04:23:27', '2025-11-08 04:23:27'),
(6, 5, 6, NULL, NULL),
(7, 5, 3, NULL, NULL),
(8, 6, 3, NULL, NULL),
(9, 6, 7, NULL, NULL),
(10, 7, 6, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `announcements_modal_id_unique` (`modal_id`);

--
-- Indices de la tabla `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donations_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `evangelio_diario`
--
ALTER TABLE `evangelio_diario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `evangelio_diario_fecha_unique` (`fecha`);

--
-- Indices de la tabla `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `group_materials`
--
ALTER TABLE `group_materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_materials_group_role_is_active_index` (`group_role`,`is_active`),
  ADD KEY `group_materials_user_id_group_role_index` (`user_id`,`group_role`);

--
-- Indices de la tabla `intentions`
--
ALTER TABLE `intentions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intentions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_roles_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `user_roles_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `donations`
--
ALTER TABLE `donations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evangelio_diario`
--
ALTER TABLE `evangelio_diario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `group_materials`
--
ALTER TABLE `group_materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `intentions`
--
ALTER TABLE `intentions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `donations`
--
ALTER TABLE `donations`
  ADD CONSTRAINT `donations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `group_materials`
--
ALTER TABLE `group_materials`
  ADD CONSTRAINT `group_materials_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `intentions`
--
ALTER TABLE `intentions`
  ADD CONSTRAINT `intentions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `user_roles`
--
ALTER TABLE `user_roles`
  ADD CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
