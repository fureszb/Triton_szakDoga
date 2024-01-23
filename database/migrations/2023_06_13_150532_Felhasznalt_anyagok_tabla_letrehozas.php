<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('felhasznalt_anyagok', function (Blueprint $table){
            $table->unsignedBigInteger('Munka_ID');
            $table->unsignedBigInteger('Anyag_ID');
            $table->primary(['Munka_ID', 'Anyag_ID']);

            $table->foreign('Munka_ID')->references('Munka_ID')->on('munkanaplo');
            $table->foreign('Anyag_ID')->references('Anyag_ID')->on('anyagok');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('felhasznalt_anyagok');
    }
};


