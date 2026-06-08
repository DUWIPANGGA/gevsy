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
        Schema::table('rekaman_audio', function (Blueprint $table) {
            $table->string('tipe_rekaman', 16)->default('audio')->after('durasi');
        });
    }

    public function down(): void
    {
        Schema::table('rekaman_audio', function (Blueprint $table) {
            $table->dropColumn('tipe_rekaman');
        });
    }
};
