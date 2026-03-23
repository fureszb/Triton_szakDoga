<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cegadatok', function (Blueprint $table) {
            $table->id();
            $table->string('nev')->default('TRITON SECURITY KFT.');
            $table->string('szekhelycim')->default('1234 Budapest, Minta utca 1.');
            $table->string('adoszam')->default('12345678-2-42');
            $table->string('cegjegyzekszam')->default('01-09-123456');
            $table->string('telefon')->default('+36 1 234 5678');
            $table->string('email')->default('info@tritonsecurity.hu');
            $table->string('bankszamlaszam')->nullable();
            $table->string('web')->nullable();
            $table->timestamps();
        });

        // Insert the default singleton row
        DB::table('cegadatok')->insert([
            'nev' => 'TRITON SECURITY KFT.',
            'szekhelycim' => '1234 Budapest, Minta utca 1.',
            'adoszam' => '12345678-2-42',
            'cegjegyzekszam' => '01-09-123456',
            'telefon' => '+36 1 234 5678',
            'email' => 'info@tritonsecurity.hu',
            'bankszamlaszam' => null,
            'web' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('cegadatok');
    }
};
