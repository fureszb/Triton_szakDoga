<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('szolgaltatas', function (Blueprint $table){
            $table->id('Szolgaltatas_ID');
            $table->string('Tipus');
            $table->timestamps();
        });
        
        DB::table('szolgaltatas')->insert([
            [
                'Tipus' => 'Kamera telepítés', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Tipus' => 'Objektum őrzés', 
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
        
    }

    public function down(): void
    {
        Schema::dropIfExists('szolgaltatas');
    }
};

