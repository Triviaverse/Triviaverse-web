-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Okt 16. 09:37
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
-- Adatbázis: `triviaverse`
--
CREATE DATABASE IF NOT EXISTS `triviaverse` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `triviaverse`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kerdesek`
--

CREATE TABLE IF NOT EXISTS `kerdesek` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kerdes` text NOT NULL,
  `targy` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kerdesek_targy_foreign` (`targy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `password_reset_tokens`
--

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `pontok`
--

CREATE TABLE IF NOT EXISTS `pontok` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `targy` bigint(20) UNSIGNED NOT NULL,
  `osszpont` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pontok_targy_foreign` (`targy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `targyak`
--

CREATE TABLE IF NOT EXISTS `targyak` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `targyneve` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `valaszok`
--

CREATE TABLE IF NOT EXISTS `valaszok` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kerdes_id` bigint(20) UNSIGNED NOT NULL,
  `valasz` text NOT NULL,
  `helyes` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `valaszok_kerdes_id_foreign` (`kerdes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `kerdesek`
--
ALTER TABLE `kerdesek`
  ADD CONSTRAINT `kerdesek_targy_foreign` FOREIGN KEY (`targy`) REFERENCES `targyak` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `pontok`
--
ALTER TABLE `pontok`
  ADD CONSTRAINT `pontok_targy_foreign` FOREIGN KEY (`targy`) REFERENCES `targyak` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `valaszok`
--
ALTER TABLE `valaszok`
  ADD CONSTRAINT `valaszok_kerdes_id_foreign` FOREIGN KEY (`kerdes_id`) REFERENCES `kerdesek` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
