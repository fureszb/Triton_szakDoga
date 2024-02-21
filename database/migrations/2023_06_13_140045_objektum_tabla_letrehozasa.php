<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('objektum', function (Blueprint $table) {
            $table->id('Objektum_ID');
            $table->string('Varos');
            $table->timestamps();
        });

        DB::table('objektum')->insert([
            [
                'Varos' => 'Szigeszentmiklós',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Varos' => 'Halásztelek',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Varos' => 'Taksony',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Varos' => 'Budapest',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('objektum');
    }
};
