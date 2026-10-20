<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    // Tambahkan baris ini untuk memberi izin sistem menyimpan data ke kolom ini
    protected $fillable = [
        'event_id', 
        'token', 
        'status', 
        'quota'
    ];
}