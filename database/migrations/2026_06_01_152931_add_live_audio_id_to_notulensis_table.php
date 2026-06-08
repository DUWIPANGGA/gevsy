<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notulensis', function (Blueprint $table) {
            $table->foreignId('live_audio_id')->nullable()->constrained('live_audios')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notulensis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('live_audio_id');
        });
    }
};
