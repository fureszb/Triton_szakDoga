<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('User_ID');
            $table->string('nev');
            $table->string('role')->default('Ugyfel');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'nev' => 'Admin Elek',
                'role' => 'Admin',
                'email' =>'admin@gmail.com',
                'password' => bcrypt('1122'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nev' => 'Üzletkőtő János',
                'role' => 'Uzletkoto',
                'email' =>'uzletkoto@gmail.com',
                'password' => bcrypt('1122'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nev' => 'Ügyfél Béla',
                'role' => 'Ugyfel',
                'email' =>'ugyfel@gmail.com',
                'password' => bcrypt('1122'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
