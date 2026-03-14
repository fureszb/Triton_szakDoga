<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A korábbi payment mezőket (amelyek most önálló szamlak/fizetesek táblákba kerültek)
 * eltávolítjuk a megrendeles táblából.
 *
 * Fontos: a down() metódus visszaállítja a mezőket, hogy rollback esetén ne vesszenek el adatok.
 * Éles migrálás előtt MINDIG készíts adatbázis mentést!
 */
return new class extends Migration
{
    public function up(): void
    {
        // Idempotens drop: csak a ténylegesen létező oszlopokat távolítjuk el
        $toRemove = [
            'Vegosszeg', 'FizetesiHatarido', 'Fizetve', 'Fizetve_Idopontja',
            'FizetesiMod', 'Stripe_Intent_ID', 'Billingo_ID', 'Billingo_Szam', 'Billingo_Pdf_Url',
        ];

        $existing = Schema::getColumnListing('megrendeles');
        $toDrop   = array_values(array_intersect($toRemove, $existing));

        if (!empty($toDrop)) {
            Schema::table('megrendeles', function (Blueprint $table) use ($toDrop) {
                $table->dropColumn($toDrop);
            });
        }
    }

    public function down(): void
    {
        Schema::table('megrendeles', function (Blueprint $table) {
            $table->decimal('Vegosszeg', 10, 2)->nullable()->after('Pdf_EleresiUt');
            $table->date('FizetesiHatarido')->nullable()->after('Vegosszeg');
            $table->boolean('Fizetve')->default(false)->after('FizetesiHatarido');
            $table->timestamp('Fizetve_Idopontja')->nullable()->after('Fizetve');
            $table->string('FizetesiMod')->nullable()->after('Fizetve_Idopontja');
            $table->string('Stripe_Intent_ID')->nullable()->after('FizetesiMod');
            $table->bigInteger('Billingo_ID')->nullable()->after('Stripe_Intent_ID');
            $table->string('Billingo_Szam')->nullable()->after('Billingo_ID');
            $table->string('Billingo_Pdf_Url')->nullable()->after('Billingo_Szam');
        });
    }
};
