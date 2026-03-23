<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fizetes_emlekeztetok', function (Blueprint $table) {
            // ─── Azonosítók ────────────────────────────────────────────────────
            $table->bigIncrements('emlekeztetok_id');
            $table->unsignedBigInteger('szamla_id');
            $table->unsignedBigInteger('megrendeles_id');
            $table->unsignedBigInteger('ugyfel_id');

            // ─── Kinek ment? ───────────────────────────────────────────────────
            // Külön tároljuk, mert az ügyfél email változhat idővel (historikus adat)
            $table->string('email_cim', 255);

            // ─── Emlékeztető típusa ────────────────────────────────────────────
            // harom_napos → 3 nappal fizetési határidő előtt
            // egy_napos   → 1 nappal fizetési határidő előtt
            // lejart      → határidő lejárta után (késedelmes)
            // manualis    → admin kézzel indította
            $table->enum('tipus', ['harom_napos', 'egy_napos', 'lejart', 'manualis'])->default('harom_napos');

            // ─── Küldés eredménye ──────────────────────────────────────────────
            $table->enum('statusz', ['sikeres', 'sikertelen'])->default('sikeres');
            $table->timestamp('kuldes_idopontja')->useCurrent();
            $table->text('hiba_uzenet')->nullable();   // Laravel Mail exception message ha sikertelen

            // ─── CSAK created_at ───────────────────────────────────────────────
            $table->timestamp('created_at')->useCurrent();
            // Nincs updated_at – az emlékeztető rekord immutable

            // ─── Foreign keys ──────────────────────────────────────────────────
            $table->foreign('szamla_id')
                ->references('szamla_id')->on('szamlak')
                ->onDelete('cascade');

            $table->foreign('megrendeles_id')
                ->references('Megrendeles_ID')->on('megrendeles')
                ->onDelete('cascade');

            $table->foreign('ugyfel_id')
                ->references('Ugyfel_ID')->on('ugyfel')
                ->onDelete('cascade');

            // ─── Indexek ───────────────────────────────────────────────────────
            $table->index(['szamla_id', 'tipus']);
            $table->index('kuldes_idopontja');
            $table->index('statusz');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fizetes_emlekeztetok');
    }
};
