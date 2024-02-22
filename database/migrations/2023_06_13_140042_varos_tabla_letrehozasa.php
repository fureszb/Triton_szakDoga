<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varos', function (Blueprint $table) {
            $table->id('Varos_ID');
            $table->integer('Irny_szam');
            $table->string('Nev');
            $table->timestamps();
        });

        DB::table('varos')->insert([
            [   'Irny_szam' => 2310,
                'Nev' => 'Szigeszentmiklós',
                'created_at' => now(),
                'updated_at' => now()
            ],
            ['Irny_szam' => 2314,
                'Nev' => 'Halásztelek',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Irny_szam' => 2335,
                'Nev' => 'Taksony',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'Irny_szam' => 2330,
                'Nev' => 'Dunaharaszti',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('varos');
    }
};
