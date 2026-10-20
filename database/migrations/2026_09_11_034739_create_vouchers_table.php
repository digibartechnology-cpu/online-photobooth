<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            
            // INI YANG PALING PENTING: Mengikat QR Code ke ID Kegiatan
            $table->foreignId('event_id')->nullable(); 
            
            $table->string('token')->unique(); // String unik dari QR Code
            $table->integer('quota')->default(1); // Jatah cetak per voucher
            $table->string('status')->default('available'); // available, in_use, completed
            $table->timestamp('expired_at')->nullable(); // Batas waktu voucher (opsional)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
};