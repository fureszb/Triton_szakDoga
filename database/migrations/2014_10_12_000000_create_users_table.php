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
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
       
        DB::table('users')->insert([
            [
                'name' => 'Bence', 
                'email' =>'fureszb@gmail.com',
                'password' => bcrypt('Jelszo12'),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Bence2', 
                'email' =>'frsz.bence@gmail.com',
                'password' => bcrypt('Jelszo12'),
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
