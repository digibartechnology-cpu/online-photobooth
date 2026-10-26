<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('filter_settings', function (Blueprint $table) {
            // Menambahkan kolom untuk 9 filter baru dengan nilai bawaan 0 (Mati)
            $table->boolean('retro_warm_active')->default(0);
            $table->boolean('retro_cool_active')->default(0);
            $table->boolean('cinematic_active')->default(0);
            $table->boolean('vibrant_active')->default(0);
            $table->boolean('gritty_active')->default(0);
            $table->boolean('bw_classic_active')->default(0);
            $table->boolean('sepia_active')->default(0);
            $table->boolean('pastel_active')->default(0);
            $table->boolean('moody_active')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('filter_settings', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn([
                'retro_warm_active',
                'retro_cool_active',
                'cinematic_active',
                'vibrant_active',
                'gritty_active',
                'bw_classic_active',
                'sepia_active',
                'pastel_active',
                'moody_active'
            ]);
        });
    }
};