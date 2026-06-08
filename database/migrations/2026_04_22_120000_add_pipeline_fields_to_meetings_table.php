<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->string('pipeline_status', 32)->default('idle')->after('status_rapat');
            $table->string('pipeline_stage', 64)->nullable()->after('pipeline_status');
            $table->text('pipeline_error')->nullable()->after('pipeline_stage');
            $table->timestamp('pipeline_started_at')->nullable()->after('pipeline_error');
            $table->timestamp('pipeline_completed_at')->nullable()->after('pipeline_started_at');
            $table->json('openai_usage_total')->nullable()->after('pipeline_completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('meetings', function (Blueprint $table) {
            $table->dropColumn([
                'pipeline_status',
                'pipeline_stage',
                'pipeline_error',
                'pipeline_started_at',
                'pipeline_completed_at',
                'openai_usage_total',
            ]);
        });
    }
};
