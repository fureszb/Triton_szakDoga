<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MariaDB 10.4: CHANGE COLUMN szükséges a típus+extra megadásához
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `id` `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `ugyfel` CHANGE COLUMN `id` `id` bigint(20) unsigned NOT NULL');
    }
};
