<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            // Kolom untuk menyimpan nama file GIF animasi
            $table->string('file_gif')->nullable()->after('file_name');
        });
    }

    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {$table->dropColumn('file_gif');
        });
    }
};