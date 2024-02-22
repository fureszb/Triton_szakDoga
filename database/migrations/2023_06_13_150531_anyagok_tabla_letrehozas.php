<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anyag', function (Blueprint $table){
            $table->id('Anyag_ID');
            $table->text('Nev');
            $table->text('Mertekegyseg');
            $table->timestamps();
        });

        DB::table('anyag')->insert([
            [
                'Nev' => 'kamera',
                'Mertekegyseg' => 'db',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Nev' => 'kábel',
                'Mertekegyseg' => 'm',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Nev' => 'kábel csatorna',
                'Mertekegyseg' => 'db',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

    }

    public function down(): void
    {
        Schema::dropIfExists('anyag');
    }
};


