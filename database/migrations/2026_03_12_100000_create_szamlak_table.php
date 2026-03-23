<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('szamlak', function (Blueprint $table) {
            // ─── Azonosítók ────────────────────────────────────────────────────
            $table->bigIncrements('szamla_id');
            $table->unsignedBigInteger('megrendeles_id');
            $table->unsignedBigInteger('ugyfel_id');          // denormalizált – gyors join

            // ─── Típus & kapcsolt számlák ──────────────────────────────────────
            // dijbekero → nem jogilag kötelező, fizetés előtti kalkuláció
            // szamla    → végleges számla
            // storno    → érvénytelenítő számla
            $table->enum('szamla_tipus', ['dijbekero', 'szamla', 'storno'])->default('szamla');

            // Stornónál: melyik eredeti számlát érvényteleníti
            $table->unsignedBigInteger('storno_eredeti_id')->nullable();

            // Számláná: melyik díjbekérőből konvertálták (ha volt)
            $table->unsignedBigInteger('dijbekero_szamla_id')->nullable();

            // ─── Billingo integráció ───────────────────────────────────────────
            $table->bigInteger('billingo_id')->nullable()->unique();
            $table->string('billingo_szam', 50)->nullable();           // pl. "2026-00123"
            $table->string('billingo_pdf_url', 512)->nullable();

            // ─── Dátumok ───────────────────────────────────────────────────────
            $table->date('kiallitas_datum');
            $table->date('teljesites_datum');
            $table->date('fizetesi_hatarido');

            // ─── Fizetési mód (várható / előírt) ──────────────────────────────
            $table->enum('fizetesi_mod', ['stripe', 'banki_atutalas', 'keszpenz'])->default('stripe');

            // ─── Összegek (HUF, bruttó alapú rendszer) ────────────────────────
            // Számított értékek – a szamla_tetelek-ből összesítve
            $table->decimal('netto_osszeg', 12, 2)->default(0);
            $table->decimal('afa_osszeg', 12, 2)->default(0);
            $table->decimal('brutto_osszeg', 12, 2)->default(0);

            // ─── Státusz ──────────────────────────────────────────────────────
            // fuggoben  → kiállítva, fizetetlenül vár
            // fizetve   → beérkezett a fizetés
            // kesedelmes → határidő lejárt, még nem fizetve
            // stornozva → érvénytelen, storno számla kiállítva
            $table->enum('statusz', ['fuggoben', 'fizetve', 'kesedelmes', 'stornozva'])->default('fuggoben');

            // ─── Megjegyzés ───────────────────────────────────────────────────
            $table->text('megjegyzes')->nullable();

            $table->timestamps();
            $table->softDeletes();   // logikai törlés – számla soha ne törlődjön fizikailag

            // ─── Foreign keys ─────────────────────────────────────────────────
            $table->foreign('megrendeles_id')
                ->references('Megrendeles_ID')->on('megrendeles')
                ->onDelete('restrict');   // megrendelés nem törölhető, ha van számlája

            $table->foreign('ugyfel_id')
                ->references('Ugyfel_ID')->on('ugyfel')
                ->onDelete('restrict');

            $table->foreign('storno_eredeti_id')
                ->references('szamla_id')->on('szamlak')
                ->onDelete('restrict');

            $table->foreign('dijbekero_szamla_id')
                ->references('szamla_id')->on('szamlak')
                ->onDelete('set null');

            // ─── Indexek ──────────────────────────────────────────────────────
            $table->index('statusz');
            $table->index('fizetesi_hatarido');
            $table->index(['ugyfel_id', 'statusz']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('szamlak');
    }
};
