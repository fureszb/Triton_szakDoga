<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ugyfel', function (Blueprint $table) {
            $table->unsignedBigInteger('Ugyfel_ID')->primary();
            $table->unsignedBigInteger('Varos_ID');
            $table->unsignedBigInteger('User_ID')->nullable();
            $table->string('Nev');
            $table->string('Email')->unique();
            $table->string('Telefonszam');
            $table->string('Szamlazasi_Nev');
            $table->string('Szamlazasi_Cim');
            $table->string('Adoszam')->nullable();
            $table->timestamps();

            $table->foreign('Varos_ID')->references('Varos_ID')->on('varos');
            $table->foreign('User_ID')->references('User_ID')->on('users')->onDelete('set null');


            //$table->index('Ugyfel_ID');
        });

        DB::table('ugyfel')->insert([
            [
                'Ugyfel_ID' => '1',
                'Varos_ID' => '1',
                'Nev' => 'Gipsz Jakab',
                'Email' => "valami@gmail.com",
                'Telefonszam' => '06301234567',
                'Szamlazasi_Nev' => 'Gipsz Jakab',
                'Szamlazasi_Cim' => "Kossuth utca, 12",
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
