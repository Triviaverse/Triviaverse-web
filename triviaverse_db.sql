-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Már 03. 14:32
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `triviaverse_db`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `answers`
--

CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer_text` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_03_124903_create_personal_access_tokens_table', 1),
(5, '2025_03_03_131910_create_quizzes_table', 2),
(6, '2025_03_03_131935_create_questions_table', 2),
(7, '2025_03_03_131956_create_answers_table', 2),
(8, '2025_03_03_132027_create_quiz_attempts_table', 2),
(9, '2025_03_03_132047_create_quiz_results_table', 2),
(10, '2025_03_03_132106_create_quiz_permissions_table', 2);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `personal_access_tokens`
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
-- Tábla szerkezet ehhez a táblához `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `type` enum('multiple_choice','single_choice','text') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `time_limit` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `created_by`, `is_active`, `time_limit`, `created_at`, `updated_at`) VALUES
(1, 'Dolorem quia et non aut non perspiciatis.', 27, 1, 12, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(2, 'Voluptate id exercitationem laudantium sunt assumenda.', 28, 1, 39, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(3, 'Consequatur maxime itaque aut velit animi ut possimus earum.', 29, 1, 46, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(4, 'Necessitatibus maiores aut est aut consequatur.', 30, 1, 17, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(5, 'Nulla veniam delectus sapiente eligendi adipisci dicta delectus.', 31, 1, 56, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(6, 'Dolore porro ducimus quidem maxime velit aut dolores.', 32, 1, 8, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(7, 'Quae in cumque ipsum voluptas reprehenderit eum.', 33, 1, 21, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(8, 'Repellat voluptas quasi voluptate quasi dolor.', 34, 1, 9, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(9, 'Quas sint natus iste mollitia.', 35, 1, 29, '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(10, 'Eveniet corporis est pariatur ipsam sapiente et.', 36, 1, 8, '2025-03-03 12:31:44', '2025-03-03 12:31:44');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quiz_permissions`
--

CREATE TABLE `quiz_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_attempt_id` bigint(20) UNSIGNED NOT NULL,
  `score_percentage` int(11) NOT NULL,
  `is_overridden` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@example.com', '2025-03-03 12:31:43', 'admin', '$2y$12$6eW3F54mffZ0evDOfsXkdOC7J6yfsy5rrM2RKbyqmCME.EwUnW1Dy', 'PyrETd2x8i', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(2, 'Enos Shields DVM', 'bode.lucas@example.org', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'ExAHFpYKCN', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(3, 'Johann Nicolas', 'dillan43@example.org', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', '42A4xFOCgJ', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(4, 'Maeve Russel', 'rblanda@example.com', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'd1FXjSJYyT', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(5, 'Gustave Gutmann', 'tbogisich@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', '8sw5Etut22', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(6, 'Kirsten Lueilwitz', 'stamm.philip@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'unzDf3XlDi', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(7, 'Ericka Schimmel MD', 'odare@example.net', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'ouhHECUGVv', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(8, 'Aileen Boyle', 'borer.hassan@example.net', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'hjMN1rbsQK', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(9, 'Lorenzo Pagac', 'yessenia.wiegand@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'ftqBIhjHXx', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(10, 'Prof. Ian Hoeger II', 'parker.antonio@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'i8NR60x0Db', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(11, 'Jeramy Mante', 'barrett.tremblay@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', '7wNKh4SPrH', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(12, 'Keara Padberg', 'yesenia91@example.com', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'RrvYgXFEVm', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(13, 'Jerad O\'Reilly', 'gerlach.josue@example.net', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', '5WuWu7DYeY', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(14, 'Mrs. Heidi Mann', 'kira.steuber@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'LqKSKxLbKV', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(15, 'Van Hudson', 'bernita.prohaska@example.com', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'yjZVYTtidJ', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(16, 'Ms. Jana Stamm II', 'romaguera.vance@example.com', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'r3zQRjGb0X', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(17, 'Abelardo Conroy', 'maiya31@example.com', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'RSmNRVPz5u', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(18, 'Corene Bergnaum', 'jed.sipes@example.net', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'HmqoV0K2JL', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(19, 'Ebony Gorczany', 'tyost@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'JNi2wcYYsm', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(20, 'Prof. Tremaine Ullrich', 'myrna.dickens@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'D6YsDINu38', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(21, 'Kassandra Altenwerth', 'ybatz@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'r7BCrO03We', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(22, 'Mr. Sigrid Dicki II', 'minerva.west@example.net', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'SpkIaWqMTI', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(23, 'Kelly Langosh', 'xullrich@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'RMkIFskjyu', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(24, 'Anastacio Torphy DVM', 'hahn.carlo@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', '0PKIF9oA4f', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(25, 'Miss Billie Hoeger I', 'mherman@example.org', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'RUZxdHe4V4', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(26, 'Petra Hackett', 'epollich@example.net', '2025-03-03 12:31:44', 'student', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'v8xvSQJmZS', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(27, 'Vince Roob', 'helga57@example.org', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'SfAFm5vkgW', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(28, 'Dr. Reynold Rath', 'hilma58@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'RhEXjiRWst', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(29, 'Alexzander Sawayn', 'nader.devan@example.com', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'mgyEppbMnD', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(30, 'Mr. Ryder Bednar MD', 'jlittel@example.com', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'eZvrCXMasF', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(31, 'Mrs. Patricia Ryan', 'mandy.kovacek@example.com', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'hTAwCTIJ1s', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(32, 'Ursula Schumm', 'angus.goodwin@example.org', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'YDFh94GlaC', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(33, 'Clay Durgan IV', 'howell.labadie@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'MvIQh5M4KR', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(34, 'Rhea Sauer', 'theresia15@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'VEaaEEi8H6', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(35, 'Bette Satterfield', 'terry.alicia@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', 'wo3FyAZ0if', '2025-03-03 12:31:44', '2025-03-03 12:31:44'),
(36, 'Prof. Willis Heller', 'elinor.abshire@example.net', '2025-03-03 12:31:44', 'teacher', '$2y$12$OP2JGRIX2Sqt3G/hSdbvUeLmVjmAVj5FdWF3pv7HgnYidrq0X08pO', '7v9WV28JsP', '2025-03-03 12:31:44', '2025-03-03 12:31:44');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_question_id_foreign` (`question_id`);

--
-- A tábla indexei `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- A tábla indexei `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- A tábla indexei `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- A tábla indexei `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- A tábla indexei `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- A tábla indexei `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- A tábla indexei `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- A tábla indexei `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_created_by_foreign` (`created_by`);

--
-- A tábla indexei `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_attempts_quiz_id_foreign` (`quiz_id`),
  ADD KEY `quiz_attempts_user_id_foreign` (`user_id`);

--
-- A tábla indexei `quiz_permissions`
--
ALTER TABLE `quiz_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_permissions_quiz_id_foreign` (`quiz_id`),
  ADD KEY `quiz_permissions_user_id_foreign` (`user_id`);

--
-- A tábla indexei `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_results_quiz_attempt_id_foreign` (`quiz_attempt_id`);

--
-- A tábla indexei `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `answers`
--
ALTER TABLE `answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT a táblához `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `quiz_permissions`
--
ALTER TABLE `quiz_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `quiz_permissions`
--
ALTER TABLE `quiz_permissions`
  ADD CONSTRAINT `quiz_permissions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_permissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_quiz_attempt_id_foreign` FOREIGN KEY (`quiz_attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
