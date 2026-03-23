<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('szamla_tetelek', function (Blueprint $table) {
            // ─── Azonosítók ────────────────────────────────────────────────────
            $table->bigIncrements('tetel_id');
            $table->unsignedBigInteger('szamla_id');

            // ─── Típus & kapcsolt entitások ────────────────────────────────────
            // anyag    → készletből vett anyag (anyagok tábla)
            // munkaora → technikus munkaideje (munka tábla)
            // egyeb    → kiszállás, licenc díj, stb. (szabad szöveges)
            $table->enum('tetel_tipus', ['anyag', 'munkaora', 'egyeb'])->default('egyeb');
            $table->unsignedBigInteger('anyag_id')->nullable();   // → anyagok
            $table->unsignedBigInteger('munka_id')->nullable();   // → munka

            // ─── Tétel adatok ──────────────────────────────────────────────────
            $table->string('nev', 255);                           // "Kültéri IP kamera", "Szerelési munkaóra", stb.
            $table->decimal('mennyiseg', 10, 3)->default(1);      // 3 tizedesjegy (pl. 2.500 méter)
            $table->string('mertekegyseg', 20)->default('db');    // db / óra / m / m² / km / ...

            // ─── Árak (nettó → ÁFA → bruttó) ──────────────────────────────────
            $table->decimal('egyseg_netto_ar', 12, 2);
            $table->tinyInteger('afa_kulcs')->default(27);        // 0 / 5 / 27 (%)
            $table->decimal('netto_osszeg', 12, 2);              // mennyiseg × egyseg_netto_ar
            $table->decimal('afa_osszeg', 12, 2);                // netto_osszeg × (afa_kulcs / 100)
            $table->decimal('brutto_osszeg', 12, 2);             // netto_osszeg + afa_osszeg

            // ─── Megjelenítési sorrend ─────────────────────────────────────────
            $table->unsignedTinyInteger('sorrend')->default(0);

            $table->timestamps();

            // ─── Foreign keys ──────────────────────────────────────────────────
            $table->foreign('szamla_id')
                ->references('szamla_id')->on('szamlak')
                ->onDelete('cascade');   // számla törlésével a tételek is törlődnek

            $table->foreign('anyag_id')
                ->references('Anyag_ID')->on('anyag')
                ->onDelete('set null');  // anyag törölhető, a tétel marad (history)

            $table->foreign('munka_id')
                ->references('Munka_ID')->on('munka')
                ->onDelete('set null');

            // ─── Indexek ───────────────────────────────────────────────────────
            $table->index(['szamla_id', 'sorrend']);
            $table->index('tetel_tipus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('szamla_tetelek');
    }
};
