<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekaman_audio', function (Blueprint $table) {
            $table->string('raw_recording_path')->nullable()->after('file_audio');
            $table->string('extracted_audio_path')->nullable()->after('raw_recording_path');
            $table->string('mime_type', 128)->nullable()->after('extracted_audio_path');
            $table->unsignedBigInteger('file_size_bytes')->nullable()->after('mime_type');
            $table->unsignedInteger('duration_seconds')->nullable()->after('file_size_bytes');
            $table->string('language', 16)->nullable()->after('duration_seconds');
            $table->text('processing_error')->nullable()->after('language');
        });
    }

    public function down(): void
    {
        Schema::table('rekaman_audio', function (Blueprint $table) {
            $table->dropColumn([
                'raw_recording_path',
                'extracted_audio_path',
                'mime_type',
                'file_size_bytes',
                'duration_seconds',
                'language',
                'processing_error',
            ]);
        });
    }
};
