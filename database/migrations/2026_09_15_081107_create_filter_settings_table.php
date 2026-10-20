<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('filter_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('bw_active')->default(true);
            $table->boolean('vintage_active')->default(true);
            $table->boolean('contrast_active')->default(false);
            $table->boolean('ai_active')->default(false);
            $table->string('ai_model')->nullable();
            $table->string('ai_api_key')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('filter_settings');
    }
};