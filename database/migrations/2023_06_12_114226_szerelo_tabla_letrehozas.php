<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('szerelo', function (Blueprint $table){
            $table->id('Szerelo_ID');
            $table->string('Nev');
            $table->string('Telefonszam');
            $table->timestamps();
        });

        DB::table('szerelo')->insert([
            [
                'Nev' => 'Ádám',
                'Telefonszam' => '06302984409',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Nev' => 'Béla',
                'Telefonszam' => '06302994409',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }


    public function down(): void
    {
        Schema::dropIfExists('szerelo');
    }
};
