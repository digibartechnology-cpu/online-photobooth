<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    // Saya sudah menambahkan 'event_id' di sini agar data tidak ditolak
    protected $fillable = ['file_name', 'status', 'event_id'];
}