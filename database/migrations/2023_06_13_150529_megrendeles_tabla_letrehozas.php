<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('megrendeles', function (Blueprint $table){
            $table->bigIncrements('Megrendeles_ID');
            $table->unsignedBigInteger('Ugyfel_ID');
            $table->unsignedBigInteger('Objektum_ID');
            $table->string('Megrendeles_Nev');
            $table->string('Utca_Hazszam');
            $table->boolean('Alairt_e')->default(false);
            $table->string('Pdf_EleresiUt')->nullable();
            $table->timestamps();

            $table->foreign('Ugyfel_ID')->references('Ugyfel_ID')->on('ugyfel');
            $table->foreign('Objektum_ID')->references('Objektum_ID')->on('objektum');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('megrendeles');
    }
};
