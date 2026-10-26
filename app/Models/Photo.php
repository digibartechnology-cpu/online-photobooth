<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    // Menambahkan 'nama' agar Laravel mengizinkan penyimpanan nama tamu
    protected $fillable = ['file_name', 'status', 'event_id', 'nama'];
}