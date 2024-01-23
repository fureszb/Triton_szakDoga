<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('megrendeles', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('Ugyfel_ID')->default(1);
            $table->string('Megrendeles_Nev');
            $table->string('Objektum_Cim');
            $table->boolean('Alairt_e')->default(false);
            $table->string('Pdf_EleresiUt')->nullable();
            $table->timestamps();

            $table->foreign('Ugyfel_ID')->references('Ugyfel_ID')->on('ugyfel');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('megrendeles');
    }
};
