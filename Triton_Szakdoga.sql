-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2024. Máj 02. 20:51
-- Kiszolgáló verziója: 10.4.27-MariaDB
-- PHP verzió: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `triton`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `anyag`
--

CREATE TABLE `anyag` (
  `Anyag_ID` bigint(20) UNSIGNED NOT NULL,
  `Nev` text NOT NULL,
  `Mertekegyseg` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `anyag`
--

INSERT INTO `anyag` (`Anyag_ID`, `Nev`, `Mertekegyseg`, `created_at`, `updated_at`) VALUES
(1, 'kamera', 'db', '2024-05-02 16:49:48', '2024-05-02 16:49:48'),
(2, 'kábel', 'm', '2024-05-02 16:49:48', '2024-05-02 16:49:48'),
(3, 'kábel csatorna', 'db', '2024-05-02 16:49:48', '2024-05-02 16:49:48');

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
-- Tábla szerkezet ehhez a táblához `felhasznalt_anyag`
--

CREATE TABLE `felhasznalt_anyag` (
  `Munka_ID` bigint(20) UNSIGNED NOT NULL,
  `Anyag_ID` bigint(20) UNSIGNED NOT NULL,
  `Mennyiseg` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `megrendeles`
--

CREATE TABLE `megrendeles` (
  `Megrendeles_ID` bigint(20) UNSIGNED NOT NULL,
  `Ugyfel_ID` bigint(20) UNSIGNED NOT NULL,
  `Varos_ID` bigint(20) UNSIGNED NOT NULL,
  `Megrendeles_Nev` varchar(255) NOT NULL,
  `Utca_Hazszam` varchar(255) NOT NULL,
  `Alairt_e` tinyint(1) NOT NULL DEFAULT 0,
  `Pdf_EleresiUt` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(2, '2019_08_19_000000_create_failed_jobs_table', 1),
(3, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(4, '2023_06_12_113032_szolgaltatasok_letrehozas_tabla', 1),
(5, '2023_06_12_114226_szerelo_tabla_letrehozas', 1),
(6, '2023_06_13_140042_create_users_table', 1),
(7, '2023_06_13_140042_varos_tabla_letrehozasa', 1),
(8, '2023_06_13_140043_ugyfel_tabla_letrehozasa', 1),
(9, '2023_06_13_150529_megrendeles_tabla_letrehozas', 1),
(10, '2023_06_13_150530_munka_tabla_letrehozas', 1),
(11, '2023_06_13_150531_anyagok_tabla_letrehozas', 1),
(12, '2023_06_13_150532_Felhasznalt_anyagok_tabla_letrehozas', 1),
(13, '2023_06_13_150535_Szerelo_szolgaltatas_tabla_letrehozas', 1),
(14, '2023_06_14_085446_ugyfel_nev_rendez', 1),
(15, '2024_03_21_093007_modify_szerelo_id_on_munka_table', 1),
(16, '2024_03_21_093010_update_foreign_key_on_munka_table', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `munka`
--

CREATE TABLE `munka` (
  `Munka_ID` bigint(20) UNSIGNED NOT NULL,
  `Megrendeles_ID` bigint(20) UNSIGNED NOT NULL,
  `Szerelo_ID` bigint(20) UNSIGNED DEFAULT NULL,
  `Szolgaltatas_ID` bigint(20) UNSIGNED NOT NULL,
  `Leiras` text NOT NULL,
  `Munkakezdes_Idopontja` datetime NOT NULL,
  `Munkabefejezes_Idopontja` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Tábla szerkezet ehhez a táblához `szerelo`
--

CREATE TABLE `szerelo` (
  `Szerelo_ID` bigint(20) UNSIGNED NOT NULL,
  `Nev` varchar(255) NOT NULL,
  `Telefonszam` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `szerelo`
--

INSERT INTO `szerelo` (`Szerelo_ID`, `Nev`, `Telefonszam`, `created_at`, `updated_at`) VALUES
(1, 'Ádám', '06302984409', '2024-05-02 16:49:45', '2024-05-02 16:49:45'),
(2, 'Béla', '06302994409', '2024-05-02 16:49:45', '2024-05-02 16:49:45');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szerelo_szolgaltatas`
--

CREATE TABLE `szerelo_szolgaltatas` (
  `Szerelo_ID` bigint(20) UNSIGNED NOT NULL,
  `Szolgaltatas_ID` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `szerelo_szolgaltatas`
--

INSERT INTO `szerelo_szolgaltatas` (`Szerelo_ID`, `Szolgaltatas_ID`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(1, 2, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(1, 3, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(1, 4, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(2, 1, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(2, 2, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(2, 3, '2024-05-02 16:49:49', '2024-05-02 16:49:49'),
(2, 4, '2024-05-02 16:49:49', '2024-05-02 16:49:49');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `szolgaltatas`
--

CREATE TABLE `szolgaltatas` (
  `Szolgaltatas_ID` bigint(20) UNSIGNED NOT NULL,
  `Tipus` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `szolgaltatas`
--

INSERT INTO `szolgaltatas` (`Szolgaltatas_ID`, `Tipus`, `created_at`, `updated_at`) VALUES
(1, 'Telepítés', '2024-05-02 16:49:45', '2024-05-02 16:49:45'),
(2, 'Karbantartás', '2024-05-02 16:49:45', '2024-05-02 16:49:45'),
(3, 'Bővítés', '2024-05-02 16:49:45', '2024-05-02 16:49:45'),
(4, 'Egyéb', '2024-05-02 16:49:45', '2024-05-02 16:49:45');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `ugyfel`
--

CREATE TABLE `ugyfel` (
  `Ugyfel_ID` bigint(20) UNSIGNED NOT NULL,
  `Varos_ID` bigint(20) UNSIGNED NOT NULL,
  `User_ID` bigint(20) UNSIGNED DEFAULT NULL,
  `Nev` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Telefonszam` varchar(255) NOT NULL,
  `Szamlazasi_Nev` varchar(255) NOT NULL,
  `Szamlazasi_Cim` varchar(255) NOT NULL,
  `Adoszam` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `ugyfel`
--

INSERT INTO `ugyfel` (`Ugyfel_ID`, `Varos_ID`, `User_ID`, `Nev`, `Email`, `Telefonszam`, `Szamlazasi_Nev`, `Szamlazasi_Cim`, `Adoszam`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Ügyfél Béla', 'ugyfel@gmail.com', '06301234567', 'Ügyfél Béla', 'Kossuth utca, 12', NULL, '2024-05-02 16:49:47', '2024-05-02 16:49:47');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `User_ID` bigint(20) UNSIGNED NOT NULL,
  `nev` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Ugyfel',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`User_ID`, `nev`, `role`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin Elek', 'Admin', 'admin@gmail.com', NULL, '$2y$10$QVi9o4cjeZUQWaoeBkgBYuf8uj7OejBX63iiw4G4xw7Qw0sDGaTYa', NULL, '2024-05-02 16:49:46', '2024-05-02 16:49:46'),
(2, 'Üzletkőtő János', 'Uzletkoto', 'uzletkoto@gmail.com', NULL, '$2y$10$9WTcZ0ttV9kCVUVo0zJ8RuEuUTgkCoRi5Zvj8Yzj./c/FOwnR4OCO', NULL, '2024-05-02 16:49:46', '2024-05-02 16:49:46'),
(3, 'Ügyfél Béla', 'Ugyfel', 'ugyfel@gmail.com', NULL, '$2y$10$Zk9I8FL06x/yj/cApiyvxeubrZ7bobanoEIxGYagnRES7EgxGMYqe', NULL, '2024-05-02 16:49:46', '2024-05-02 16:49:46');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `varos`
--

CREATE TABLE `varos` (
  `Varos_ID` bigint(20) UNSIGNED NOT NULL,
  `Irny_szam` int(11) NOT NULL,
  `Nev` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `varos`
--

INSERT INTO `varos` (`Varos_ID`, `Irny_szam`, `Nev`, `created_at`, `updated_at`) VALUES
(1, 2310, 'Szigeszentmiklós', '2024-05-02 16:49:46', '2024-05-02 16:49:46'),
(2, 2314, 'Halásztelek', '2024-05-02 16:49:46', '2024-05-02 16:49:46'),
(3, 2335, 'Taksony', '2024-05-02 16:49:46', '2024-05-02 16:49:46'),
(4, 2330, 'Dunaharaszti', '2024-05-02 16:49:46', '2024-05-02 16:49:46');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `anyag`
--
ALTER TABLE `anyag`
  ADD PRIMARY KEY (`Anyag_ID`);

--
-- A tábla indexei `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- A tábla indexei `felhasznalt_anyag`
--
ALTER TABLE `felhasznalt_anyag`
  ADD PRIMARY KEY (`Munka_ID`,`Anyag_ID`),
  ADD KEY `felhasznalt_anyag_anyag_id_foreign` (`Anyag_ID`);

--
-- A tábla indexei `megrendeles`
--
ALTER TABLE `megrendeles`
  ADD PRIMARY KEY (`Megrendeles_ID`),
  ADD KEY `megrendeles_ugyfel_id_foreign` (`Ugyfel_ID`),
  ADD KEY `megrendeles_varos_id_foreign` (`Varos_ID`);

--
-- A tábla indexei `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `munka`
--
ALTER TABLE `munka`
  ADD PRIMARY KEY (`Munka_ID`),
  ADD KEY `munka_megrendeles_id_foreign` (`Megrendeles_ID`),
  ADD KEY `munka_szolgaltatas_id_foreign` (`Szolgaltatas_ID`),
  ADD KEY `munka_szerelo_id_foreign` (`Szerelo_ID`);

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
-- A tábla indexei `szerelo`
--
ALTER TABLE `szerelo`
  ADD PRIMARY KEY (`Szerelo_ID`);

--
-- A tábla indexei `szerelo_szolgaltatas`
--
ALTER TABLE `szerelo_szolgaltatas`
  ADD PRIMARY KEY (`Szerelo_ID`,`Szolgaltatas_ID`),
  ADD KEY `szerelo_szolgaltatas_szolgaltatas_id_foreign` (`Szolgaltatas_ID`);

--
-- A tábla indexei `szolgaltatas`
--
ALTER TABLE `szolgaltatas`
  ADD PRIMARY KEY (`Szolgaltatas_ID`);

--
-- A tábla indexei `ugyfel`
--
ALTER TABLE `ugyfel`
  ADD PRIMARY KEY (`Ugyfel_ID`),
  ADD UNIQUE KEY `ugyfel_email_unique` (`Email`),
  ADD KEY `ugyfel_varos_id_foreign` (`Varos_ID`),
  ADD KEY `ugyfel_user_id_foreign` (`User_ID`),
  ADD KEY `ugyfel_nev_index` (`Nev`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- A tábla indexei `varos`
--
ALTER TABLE `varos`
  ADD PRIMARY KEY (`Varos_ID`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `anyag`
--
ALTER TABLE `anyag`
  MODIFY `Anyag_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `megrendeles`
--
ALTER TABLE `megrendeles`
  MODIFY `Megrendeles_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT a táblához `munka`
--
ALTER TABLE `munka`
  MODIFY `Munka_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `szerelo`
--
ALTER TABLE `szerelo`
  MODIFY `Szerelo_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `szolgaltatas`
--
ALTER TABLE `szolgaltatas`
  MODIFY `Szolgaltatas_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `varos`
--
ALTER TABLE `varos`
  MODIFY `Varos_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `felhasznalt_anyag`
--
ALTER TABLE `felhasznalt_anyag`
  ADD CONSTRAINT `felhasznalt_anyag_anyag_id_foreign` FOREIGN KEY (`Anyag_ID`) REFERENCES `anyag` (`Anyag_ID`),
  ADD CONSTRAINT `felhasznalt_anyag_munka_id_foreign` FOREIGN KEY (`Munka_ID`) REFERENCES `munka` (`Munka_ID`);

--
-- Megkötések a táblához `megrendeles`
--
ALTER TABLE `megrendeles`
  ADD CONSTRAINT `megrendeles_ugyfel_id_foreign` FOREIGN KEY (`Ugyfel_ID`) REFERENCES `ugyfel` (`Ugyfel_ID`),
  ADD CONSTRAINT `megrendeles_varos_id_foreign` FOREIGN KEY (`Varos_ID`) REFERENCES `varos` (`Varos_ID`);

--
-- Megkötések a táblához `munka`
--
ALTER TABLE `munka`
  ADD CONSTRAINT `munka_megrendeles_id_foreign` FOREIGN KEY (`Megrendeles_ID`) REFERENCES `megrendeles` (`Megrendeles_ID`),
  ADD CONSTRAINT `munka_szerelo_id_foreign` FOREIGN KEY (`Szerelo_ID`) REFERENCES `szerelo` (`Szerelo_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `munka_szolgaltatas_id_foreign` FOREIGN KEY (`Szolgaltatas_ID`) REFERENCES `szolgaltatas` (`Szolgaltatas_ID`);

--
-- Megkötések a táblához `szerelo_szolgaltatas`
--
ALTER TABLE `szerelo_szolgaltatas`
  ADD CONSTRAINT `szerelo_szolgaltatas_szerelo_id_foreign` FOREIGN KEY (`Szerelo_ID`) REFERENCES `szerelo` (`Szerelo_ID`),
  ADD CONSTRAINT `szerelo_szolgaltatas_szolgaltatas_id_foreign` FOREIGN KEY (`Szolgaltatas_ID`) REFERENCES `szolgaltatas` (`Szolgaltatas_ID`);

--
-- Megkötések a táblához `ugyfel`
--
ALTER TABLE `ugyfel`
  ADD CONSTRAINT `ugyfel_user_id_foreign` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `ugyfel_varos_id_foreign` FOREIGN KEY (`Varos_ID`) REFERENCES `varos` (`Varos_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
