<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `leiras` `leiras` TEXT NULL DEFAULT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `munka` CHANGE COLUMN `leiras` `leiras` TEXT NOT NULL');
    }
};
