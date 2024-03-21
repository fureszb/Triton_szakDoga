<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('munka', function (Blueprint $table) {
            // Először eltávolítjuk a meglévő külső kulcs megszorítást
            $table->dropForeign('munka_szerelo_id_foreign');

            // Ezután hozzáadjuk az új külső kulcs megszorítást az ON DELETE SET NULL viselkedéssel
            $table->foreign('Szerelo_ID')
                ->references('Szerelo_ID')->on('szerelo')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('munka', function (Blueprint $table) {
            // Visszaállítjuk az eredeti állapotot a down() metódusban
            $table->dropForeign('munka_szerelo_id_foreign');

            $table->foreign('Szerelo_ID')
                ->references('Szerelo_ID')->on('szerelo');
            // Itt nincs szükség onDelete részre, mert az alapértelmezett viselkedést szeretnénk
        });
    }
};
