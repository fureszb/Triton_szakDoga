<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('ugyfel', function (Blueprint $table) {
            $table->index('Nev');
        });
    }

    public function down(): void
    {
        Schema::table('ugyfel', function (Blueprint $table) {
            $table->dropIndex(['Nev']);
        });
    }
};
