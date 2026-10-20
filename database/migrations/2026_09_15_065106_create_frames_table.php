<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('frames', function (Blueprint $table) {
            $table->id();
            $table->string('nama_frame');
            $table->string('tipe_frame')->default('umum');
            $table->foreignId('event_id')->nullable();
            $table->string('ukuran_cetak')->default('strip');
            $table->string('file_name'); // Nama file PNG yang disimpan
            $table->text('layout_data')->nullable(); // Menyimpan koordinat kotak foto (JSON)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('frames');
    }
};
