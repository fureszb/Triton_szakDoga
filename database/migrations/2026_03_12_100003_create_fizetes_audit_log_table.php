<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fizetes_audit_log', function (Blueprint $table) {
            // ─── Azonosítók ────────────────────────────────────────────────────
            $table->bigIncrements('log_id');

            // Minden nullable – egy log bejegyzés érinthet egyet vagy többet is
            $table->unsignedBigInteger('szamla_id')->nullable();
            $table->unsignedBigInteger('fizetes_id')->nullable();
            $table->unsignedBigInteger('megrendeles_id')->nullable();

            // Ki hajtotta végre? NULL = automatikus rendszerfolyamat (pl. scheduler, webhook)
            $table->unsignedBigInteger('user_id')->nullable();

            // ─── Esemény típusa ────────────────────────────────────────────────
            // szamla_kiallitva     → új számla létrehozva
            // dijbekero_kiallitva  → díjbekérő létrehozva
            // statusz_valtozas     → szamla státusz megváltozott
            // fizetes_rogzitve     → fizetési rekord létrehozva/módosítva
            // fizetes_teljesult    → sikeres fizetés beérkezett
            // fizetes_sikertelen   → sikertelen fizetési kísérlet
            // storno_kiallitva     → stornó számla kiállítva
            // billingo_szinkron    → Billingo API szinkronizáció
            // emlekeztetokuldve    → emlékeztető email kiküldve
            // manualis_fizetes     → admin manuálisan rögzítette fizetettnek
            $table->enum('esemeny', [
                'szamla_kiallitva',
                'dijbekero_kiallitva',
                'statusz_valtozas',
                'fizetes_rogzitve',
                'fizetes_teljesult',
                'fizetes_sikertelen',
                'storno_kiallitva',
                'billingo_szinkron',
                'emlekeztetokuldve',
                'manualis_fizetes',
            ]);

            // ─── Változás adatai (JSON) ────────────────────────────────────────
            // Tárolja a módosítás előtti és utáni állapotot
            // pl. regi_ertek: {"statusz": "fuggoben"}, uj_ertek: {"statusz": "fizetve"}
            $table->json('regi_ertek')->nullable();
            $table->json('uj_ertek')->nullable();

            // ─── Kiegészítő adatok ─────────────────────────────────────────────
            $table->text('megjegyzes')->nullable();
            $table->string('ip_cim', 45)->nullable();    // IPv4 (15) és IPv6 (45) is fér

            // ─── CSAK created_at – audit log soha nem módosítható ─────────────
            $table->timestamp('created_at')->useCurrent();
            // Nincs updated_at! Audit log rekord immutable.

            // ─── Foreign keys (nullable-ek, restrict helyett cascade – ha törlünk, log marad) ─
            $table->foreign('szamla_id')
                  ->references('szamla_id')->on('szamlak')
                  ->onDelete('set null');

            $table->foreign('fizetes_id')
                  ->references('fizetes_id')->on('fizetesek')
                  ->onDelete('set null');

            $table->foreign('megrendeles_id')
                  ->references('Megrendeles_ID')->on('megrendeles')
                  ->onDelete('set null');

            $table->foreign('user_id')
                  ->references('User_ID')->on('users')
                  ->onDelete('set null');

            // ─── Indexek ───────────────────────────────────────────────────────
            $table->index('esemeny');
            $table->index('created_at');
            $table->index(['szamla_id', 'esemeny']);
            $table->index(['megrendeles_id', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fizetes_audit_log');
    }
};
