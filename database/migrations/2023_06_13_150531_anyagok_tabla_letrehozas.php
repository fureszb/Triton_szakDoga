<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anyagok', function (Blueprint $table){
            $table->id('Anyag_ID');
            $table->text('Leiras');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anyagok');
    }
};

