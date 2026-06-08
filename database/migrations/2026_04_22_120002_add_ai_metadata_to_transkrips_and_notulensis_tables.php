<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transkrips', function (Blueprint $table) {
            $table->string('openai_model', 64)->nullable()->after('hasil_transkrip');
            $table->json('openai_usage')->nullable()->after('openai_model');
        });

        Schema::table('notulensis', function (Blueprint $table) {
            $table->json('structured_summary')->nullable()->after('ringkasan');
            $table->string('openai_model', 64)->nullable()->after('structured_summary');
            $table->string('prompt_version', 32)->nullable()->after('openai_model');
            $table->json('openai_usage')->nullable()->after('prompt_version');
        });
    }

    public function down(): void
    {
        Schema::table('transkrips', function (Blueprint $table) {
            $table->dropColumn(['openai_model', 'openai_usage']);
        });

        Schema::table('notulensis', function (Blueprint $table) {
            $table->dropColumn(['structured_summary', 'openai_model', 'prompt_version', 'openai_usage']);
        });
    }
};
