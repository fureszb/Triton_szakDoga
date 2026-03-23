<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * A notifications táblán a "olvasatlan értesítések" lekérdezés gyorsítása.
 * Laravel alapértelmezetten csak (notifiable_type, notifiable_id) indexet hoz létre,
 * de a read_at szűrés (WHERE read_at IS NULL) full scan-t végez index nélkül.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Összetett index: notifiable lekérdezés + olvasatlan szűrés együtt gyors
            $table->index(['notifiable_type', 'notifiable_id', 'read_at'], 'notifications_notifiable_read_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_read_at_index');
        });
    }
};
