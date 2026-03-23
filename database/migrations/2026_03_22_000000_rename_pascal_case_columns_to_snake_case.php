<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PascalCase → snake_case oszlopátnevezés az összes régi táblán.
 * MariaDB 10.4 kompatibilis: ALTER TABLE ... CHANGE COLUMN szintaxist használ
 * (RENAME COLUMN csak MariaDB 10.5.2-től elérhető).
 *
 * Érintett táblák: users, varos, ugyfel, szolgaltatas, szerelo,
 *                  megrendeles, munka, anyag, felhasznalt_anyag,
 *                  szerelo_szolgaltatas
 *
 * ⚠️  Futtatás előtt MINDIG készíts adatbázis-mentést!
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // ── FK constraint-ok eldobása (az érintett táblákról) ─────────────

        DB::statement('ALTER TABLE `ugyfel` DROP FOREIGN KEY `ugyfel_varos_id_foreign`');
        DB::statement('ALTER TABLE `ugyfel` DROP FOREIGN KEY `ugyfel_user_id_foreign`');

        DB::statement('ALTER TABLE `megrendeles` DROP FOREIGN KEY `megrendeles_ugyfel_id_foreign`');
        DB::statement('ALTER TABLE `megrendeles` DROP FOREIGN KEY `megrendeles_varos_id_foreign`');

        DB::statement('ALTER TABLE `munka` DROP FOREIGN KEY `munka_megrendeles_id_foreign`');
        DB::statement('ALTER TABLE `munka` DROP FOREIGN KEY `munka_szerelo_id_foreign`');
        DB::statement('ALTER TABLE `munka` DROP FOREIGN KEY `munka_szolgaltatas_id_foreign`');

        DB::statement('ALTER TABLE `felhasznalt_anyag` DROP FOREIGN KEY `felhasznalt_anyag_anyag_id_foreign`');
        DB::statement('ALTER TABLE `felhasznalt_anyag` DROP FOREIGN KEY `felhasznalt_anyag_munka_id_foreign`');

        DB::statement('ALTER TABLE `szerelo_szolgaltatas` DROP FOREIGN KEY `szerelo_szolgaltatas_szerelo_id_foreign`');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` DROP FOREIGN KEY `szerelo_szolgaltatas_szolgaltatas_id_foreign`');

        // ── users ──────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `users` CHANGE COLUMN `User_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');

        // ── varos ──────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `varos` CHANGE COLUMN `Varos_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `varos` CHANGE COLUMN `Nev` `nev` varchar(255) NOT NULL');
        // Irny_szam: szándékosan meghagyva (nem szerepelt az eredeti rename listán)

        // ── ugyfel ─────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Ugyfel_ID` `id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Varos_ID` `varos_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `User_ID` `user_id` bigint(20) unsigned DEFAULT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Nev` `nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Email` `email` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Telefonszam` `telefonszam` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Szamlazasi_Nev` `szamlazasi_nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Szamlazasi_Cim` `szamlazasi_cim` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `Adoszam` `adoszam` varchar(255) DEFAULT NULL');

        // ── szolgaltatas ───────────────────────────────────────────────────
        DB::statement('ALTER TABLE `szolgaltatas` CHANGE COLUMN `Szolgaltatas_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `szolgaltatas` CHANGE COLUMN `Tipus` `tipus` varchar(255) NOT NULL');

        // ── szerelo ────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `szerelo` CHANGE COLUMN `Szerelo_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `szerelo` CHANGE COLUMN `Nev` `nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `szerelo` CHANGE COLUMN `Telefonszam` `telefonszam` varchar(255) NOT NULL');

        // ── megrendeles ────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Megrendeles_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Ugyfel_ID` `ugyfel_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Varos_ID` `varos_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Megrendeles_Nev` `megrendeles_nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Utca_Hazszam` `utca_hazszam` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Statusz` `statusz` tinyint(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `Pdf_EleresiUt` `pdf_eleresi_ut` varchar(255) DEFAULT NULL');

        // ── munka ──────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Munka_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Megrendeles_ID` `megrendeles_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Szerelo_ID` `szerelo_id` bigint(20) unsigned DEFAULT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Szolgaltatas_ID` `szolgaltatas_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Leiras` `leiras` text NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Munkakezdes_Idopontja` `munkakezdes_idopontja` datetime NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `Munkabefejezes_Idopontja` `munkabefejezes_idopontja` datetime NOT NULL');

        // ── anyag ──────────────────────────────────────────────────────────
        DB::statement('ALTER TABLE `anyag` CHANGE COLUMN `Anyag_ID` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `anyag` CHANGE COLUMN `Nev` `nev` text NOT NULL');
        DB::statement('ALTER TABLE `anyag` CHANGE COLUMN `Mertekegyseg` `mertekegyseg` text NOT NULL');

        // ── felhasznalt_anyag ──────────────────────────────────────────────
        DB::statement('ALTER TABLE `felhasznalt_anyag` CHANGE COLUMN `Munka_ID` `munka_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `felhasznalt_anyag` CHANGE COLUMN `Anyag_ID` `anyag_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `felhasznalt_anyag` CHANGE COLUMN `Mennyiseg` `mennyiseg` int(11) NOT NULL');

        // ── szerelo_szolgaltatas ───────────────────────────────────────────
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` CHANGE COLUMN `Szerelo_ID` `szerelo_id` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` CHANGE COLUMN `Szolgaltatas_ID` `szolgaltatas_id` bigint(20) unsigned NOT NULL');

        // ── FK constraint-ok visszaállítása (új oszlopnevekkel) ────────────

        DB::statement('ALTER TABLE `ugyfel` ADD CONSTRAINT `ugyfel_varos_id_foreign` FOREIGN KEY (`varos_id`) REFERENCES `varos` (`id`)');
        DB::statement('ALTER TABLE `ugyfel` ADD CONSTRAINT `ugyfel_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL');

        DB::statement('ALTER TABLE `megrendeles` ADD CONSTRAINT `megrendeles_ugyfel_id_foreign` FOREIGN KEY (`ugyfel_id`) REFERENCES `ugyfel` (`id`)');
        DB::statement('ALTER TABLE `megrendeles` ADD CONSTRAINT `megrendeles_varos_id_foreign` FOREIGN KEY (`varos_id`) REFERENCES `varos` (`id`)');

        DB::statement('ALTER TABLE `munka` ADD CONSTRAINT `munka_megrendeles_id_foreign` FOREIGN KEY (`megrendeles_id`) REFERENCES `megrendeles` (`id`)');
        DB::statement('ALTER TABLE `munka` ADD CONSTRAINT `munka_szerelo_id_foreign` FOREIGN KEY (`szerelo_id`) REFERENCES `szerelo` (`id`) ON DELETE SET NULL');
        DB::statement('ALTER TABLE `munka` ADD CONSTRAINT `munka_szolgaltatas_id_foreign` FOREIGN KEY (`szolgaltatas_id`) REFERENCES `szolgaltatas` (`id`)');

        DB::statement('ALTER TABLE `felhasznalt_anyag` ADD CONSTRAINT `felhasznalt_anyag_anyag_id_foreign` FOREIGN KEY (`anyag_id`) REFERENCES `anyag` (`id`)');
        DB::statement('ALTER TABLE `felhasznalt_anyag` ADD CONSTRAINT `felhasznalt_anyag_munka_id_foreign` FOREIGN KEY (`munka_id`) REFERENCES `munka` (`id`)');

        DB::statement('ALTER TABLE `szerelo_szolgaltatas` ADD CONSTRAINT `szerelo_szolgaltatas_szerelo_id_foreign` FOREIGN KEY (`szerelo_id`) REFERENCES `szerelo` (`id`)');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` ADD CONSTRAINT `szerelo_szolgaltatas_szolgaltatas_id_foreign` FOREIGN KEY (`szolgaltatas_id`) REFERENCES `szolgaltatas` (`id`)');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // FK-k eldobása
        DB::statement('ALTER TABLE `ugyfel` DROP FOREIGN KEY `ugyfel_varos_id_foreign`');
        DB::statement('ALTER TABLE `ugyfel` DROP FOREIGN KEY `ugyfel_user_id_foreign`');
        DB::statement('ALTER TABLE `megrendeles` DROP FOREIGN KEY `megrendeles_ugyfel_id_foreign`');
        DB::statement('ALTER TABLE `megrendeles` DROP FOREIGN KEY `megrendeles_varos_id_foreign`');
        DB::statement('ALTER TABLE `munka` DROP FOREIGN KEY `munka_megrendeles_id_foreign`');
        DB::statement('ALTER TABLE `munka` DROP FOREIGN KEY `munka_szerelo_id_foreign`');
        DB::statement('ALTER TABLE `munka` DROP FOREIGN KEY `munka_szolgaltatas_id_foreign`');
        DB::statement('ALTER TABLE `felhasznalt_anyag` DROP FOREIGN KEY `felhasznalt_anyag_anyag_id_foreign`');
        DB::statement('ALTER TABLE `felhasznalt_anyag` DROP FOREIGN KEY `felhasznalt_anyag_munka_id_foreign`');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` DROP FOREIGN KEY `szerelo_szolgaltatas_szerelo_id_foreign`');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` DROP FOREIGN KEY `szerelo_szolgaltatas_szolgaltatas_id_foreign`');

        // Visszaállítás fordított sorrendben
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` CHANGE COLUMN `szerelo_id` `Szerelo_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` CHANGE COLUMN `szolgaltatas_id` `Szolgaltatas_ID` bigint(20) unsigned NOT NULL');

        DB::statement('ALTER TABLE `felhasznalt_anyag` CHANGE COLUMN `munka_id` `Munka_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `felhasznalt_anyag` CHANGE COLUMN `anyag_id` `Anyag_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `felhasznalt_anyag` CHANGE COLUMN `mennyiseg` `Mennyiseg` int(11) NOT NULL');

        DB::statement('ALTER TABLE `anyag` CHANGE COLUMN `id` `Anyag_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `anyag` CHANGE COLUMN `nev` `Nev` text NOT NULL');
        DB::statement('ALTER TABLE `anyag` CHANGE COLUMN `mertekegyseg` `Mertekegyseg` text NOT NULL');

        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `id` `Munka_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `megrendeles_id` `Megrendeles_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `szerelo_id` `Szerelo_ID` bigint(20) unsigned DEFAULT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `szolgaltatas_id` `Szolgaltatas_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `leiras` `Leiras` text NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `munkakezdes_idopontja` `Munkakezdes_Idopontja` datetime NOT NULL');
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `munkabefejezes_idopontja` `Munkabefejezes_Idopontja` datetime NOT NULL');

        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `id` `Megrendeles_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `ugyfel_id` `Ugyfel_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `varos_id` `Varos_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `megrendeles_nev` `Megrendeles_Nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `utca_hazszam` `Utca_Hazszam` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `statusz` `Statusz` tinyint(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE `megrendeles` CHANGE COLUMN `pdf_eleresi_ut` `Pdf_EleresiUt` varchar(255) DEFAULT NULL');

        DB::statement('ALTER TABLE `szerelo` CHANGE COLUMN `id` `Szerelo_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `szerelo` CHANGE COLUMN `nev` `Nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `szerelo` CHANGE COLUMN `telefonszam` `Telefonszam` varchar(255) NOT NULL');

        DB::statement('ALTER TABLE `szolgaltatas` CHANGE COLUMN `id` `Szolgaltatas_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `szolgaltatas` CHANGE COLUMN `tipus` `Tipus` varchar(255) NOT NULL');

        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `id` `Ugyfel_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `varos_id` `Varos_ID` bigint(20) unsigned NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `user_id` `User_ID` bigint(20) unsigned DEFAULT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `nev` `Nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `email` `Email` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `telefonszam` `Telefonszam` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `szamlazasi_nev` `Szamlazasi_Nev` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `szamlazasi_cim` `Szamlazasi_Cim` varchar(255) NOT NULL');
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `adoszam` `Adoszam` varchar(255) DEFAULT NULL');

        DB::statement('ALTER TABLE `varos` CHANGE COLUMN `id` `Varos_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
        DB::statement('ALTER TABLE `varos` CHANGE COLUMN `nev` `Nev` varchar(255) NOT NULL');

        DB::statement('ALTER TABLE `users` CHANGE COLUMN `id` `User_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT');

        // FK-k visszaállítása az eredeti oszlopnevekkel
        DB::statement('ALTER TABLE `ugyfel` ADD CONSTRAINT `ugyfel_varos_id_foreign` FOREIGN KEY (`Varos_ID`) REFERENCES `varos` (`Varos_ID`)');
        DB::statement('ALTER TABLE `ugyfel` ADD CONSTRAINT `ugyfel_user_id_foreign` FOREIGN KEY (`User_ID`) REFERENCES `users` (`User_ID`) ON DELETE SET NULL');
        DB::statement('ALTER TABLE `megrendeles` ADD CONSTRAINT `megrendeles_ugyfel_id_foreign` FOREIGN KEY (`Ugyfel_ID`) REFERENCES `ugyfel` (`Ugyfel_ID`)');
        DB::statement('ALTER TABLE `megrendeles` ADD CONSTRAINT `megrendeles_varos_id_foreign` FOREIGN KEY (`Varos_ID`) REFERENCES `varos` (`Varos_ID`)');
        DB::statement('ALTER TABLE `munka` ADD CONSTRAINT `munka_megrendeles_id_foreign` FOREIGN KEY (`Megrendeles_ID`) REFERENCES `megrendeles` (`Megrendeles_ID`)');
        DB::statement('ALTER TABLE `munka` ADD CONSTRAINT `munka_szerelo_id_foreign` FOREIGN KEY (`Szerelo_ID`) REFERENCES `szerelo` (`Szerelo_ID`) ON DELETE SET NULL');
        DB::statement('ALTER TABLE `munka` ADD CONSTRAINT `munka_szolgaltatas_id_foreign` FOREIGN KEY (`Szolgaltatas_ID`) REFERENCES `szolgaltatas` (`Szolgaltatas_ID`)');
        DB::statement('ALTER TABLE `felhasznalt_anyag` ADD CONSTRAINT `felhasznalt_anyag_anyag_id_foreign` FOREIGN KEY (`Anyag_ID`) REFERENCES `anyag` (`Anyag_ID`)');
        DB::statement('ALTER TABLE `felhasznalt_anyag` ADD CONSTRAINT `felhasznalt_anyag_munka_id_foreign` FOREIGN KEY (`Munka_ID`) REFERENCES `munka` (`Munka_ID`)');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` ADD CONSTRAINT `szerelo_szolgaltatas_szerelo_id_foreign` FOREIGN KEY (`Szerelo_ID`) REFERENCES `szerelo` (`Szerelo_ID`)');
        DB::statement('ALTER TABLE `szerelo_szolgaltatas` ADD CONSTRAINT `szerelo_szolgaltatas_szolgaltatas_id_foreign` FOREIGN KEY (`Szolgaltatas_ID`) REFERENCES `szolgaltatas` (`Szolgaltatas_ID`)');

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
};
