<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('szerelo_szolgaltatas', function (Blueprint $table) {
            $table->unsignedBigInteger('Szerelo_ID');
            $table->unsignedBigInteger('Szolgaltatas_ID');
            $table->timestamps();

            $table->foreign('Szerelo_ID')->references('Szerelo_ID')->on('szerelo');
            $table->foreign('Szolgaltatas_ID')->references('Szolgaltatas_ID')->on('szolgaltatas');

            $table->primary(['Szerelo_ID', 'Szolgaltatas_ID']);
        });
        DB::table('szerelo_szolgaltatas')->insert([
            [
                'Szerelo_ID' => '1',
                'Szolgaltatas_ID' => '1', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Szerelo_ID' => '1',
                'Szolgaltatas_ID' => '2', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Szerelo_ID' => '2',
                'Szolgaltatas_ID' => '1', 
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Szerelo_ID' => '2',
                'Szolgaltatas_ID' => '2', 
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('szerelo_szolgaltatas');
    }
};


