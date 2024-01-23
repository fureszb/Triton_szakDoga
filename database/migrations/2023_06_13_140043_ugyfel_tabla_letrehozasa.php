<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ugyfel', function (Blueprint $table){
            $table->id('Ugyfel_ID');
            $table->string('Nev');
            $table->string('Email');
            $table->string('Telefonszam');
            $table->string('Szamlazasi_Nev');
            $table->string('Szamlazasi_Cim');
            $table->string('Adoszam')->nullable();
            $table->timestamps();
        });

        DB::table('ugyfel')->insert([
            [
                'Ugyfel_ID' => '1',
                'Nev' => 'Gipsz Jakab', 
                'Email' =>"valami@gmail.com",
                'Telefonszam' => '06301234567',
                'Szamlazasi_Nev' => 'Gipsz Jakab', 
                'Szamlazasi_Cim' =>"2310 Szigetszentmiklós, Kossuth utca, 12",
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ugyfel');
    }
};
