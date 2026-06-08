<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->foreignId('meeting_id')->nullable()->change();
            $table->foreign('meeting_id')->references('id')->on('meetings')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('agendas', function (Blueprint $table) {
            $table->dropForeign(['meeting_id']);
            $table->foreignId('meeting_id')->nullable(false)->change();
            $table->foreign('meeting_id')->references('id')->on('meetings')->cascadeOnDelete();
        });
    }
};
