<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            
            // INI YANG PALING PENTING: Mengikat Antrean Foto ke ID Kegiatan
            $table->foreignId('event_id')->nullable(); 
            
            $table->string('file_name');
            $table->string('status')->default('pending'); // pending, progress, completed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('photos');
    }
};