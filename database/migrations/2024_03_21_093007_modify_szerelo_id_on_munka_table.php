<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('munka', function (Blueprint $table) {
            // Először eltávolítjuk a jelenlegi külső kulcs megszorítást
            $table->dropForeign(['Szerelo_ID']);

            // Másodszor, módosítjuk az oszlopot, hogy elfogadjon NULL értékeket is
            $table->unsignedBigInteger('Szerelo_ID')->nullable()->change();

            // Végül, újra hozzáadjuk a külső kulcs megszorítást az ON DELETE SET NULL viselkedéssel
            $table->foreign('Szerelo_ID')->references('Szerelo_ID')->on('szerelo')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('munka', function (Blueprint $table) {
            $table->dropForeign(['Szerelo_ID']);
            $table->unsignedBigInteger('Szerelo_ID')->nullable(false)->change();
            $table->foreign('Szerelo_ID')->references('Szerelo_ID')->on('szerelo');
        });
    }
};
