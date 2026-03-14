<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fizetesek', function (Blueprint $table) {
            // ─── Azonosítók ────────────────────────────────────────────────────
            $table->bigIncrements('fizetes_id');
            $table->unsignedBigInteger('szamla_id');
            $table->unsignedBigInteger('megrendeles_id');   // denormalizált – gyors riporting
            $table->unsignedBigInteger('ugyfel_id');        // denormalizált – gyors riporting

            // ─── Fizetési adatok ───────────────────────────────────────────────
            $table->enum('fizetes_mod', ['stripe', 'banki_atutalas'])->default('stripe');
            $table->decimal('osszeg', 12, 2);
            $table->char('deviza', 3)->default('HUF');      // jövőbeli bővíthetőségért

            // ─── Státusz ──────────────────────────────────────────────────────
            // fuggoben    → tranzakció elindult, visszajelzés még nem érkezett
            // fizetve     → sikeresen lezárult
            // sikertelen  → bank/Stripe visszautasította
            // visszateritve → refund/visszaterhelés
            $table->enum('statusz', ['fuggoben', 'fizetve', 'sikertelen', 'visszateritve'])->default('fuggoben');

            // ─── Stripe adatok (nullable – banki átutalásnál nem szükséges) ───
            $table->string('stripe_session_id', 255)->nullable()->unique();
            $table->string('stripe_payment_intent_id', 255)->nullable()->unique();

            // ─── Banki átutalás adatok (nullable – Stripe-nál nem szükséges) ──
            // Az ügyfél által a közleménybe írt azonosító (pl. "MR-2026-0042")
            $table->string('banki_hivatkozas', 100)->nullable();

            // ─── Timestamps ───────────────────────────────────────────────────
            $table->timestamp('fizetes_idopontja')->nullable();  // fizetés teljesítésének időpontja
            $table->text('megjegyzes')->nullable();
            $table->timestamps();

            // ─── Foreign keys ──────────────────────────────────────────────────
            $table->foreign('szamla_id')
                  ->references('szamla_id')->on('szamlak')
                  ->onDelete('restrict');

            $table->foreign('megrendeles_id')
                  ->references('Megrendeles_ID')->on('megrendeles')
                  ->onDelete('restrict');

            $table->foreign('ugyfel_id')
                  ->references('Ugyfel_ID')->on('ugyfel')
                  ->onDelete('restrict');

            // ─── Indexek ───────────────────────────────────────────────────────
            $table->index('statusz');
            $table->index('fizetes_idopontja');
            $table->index(['ugyfel_id', 'statusz']);
            $table->index('banki_hivatkozas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fizetesek');
    }
};
