<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('szamlak', function (Blueprint $table) {
            $table->string('sajat_pdf_path', 255)->nullable()->after('billingo_pdf_url')
                  ->comment('Saját sablon alapján generált PDF elérési útja (storage/app relatív)');
        });
    }

    public function down(): void
    {
        Schema::table('szamlak', function (Blueprint $table) {
            $table->dropColumn('sajat_pdf_path');
        });
    }
};
