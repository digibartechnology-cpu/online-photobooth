<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    protected $fillable = ['nama_frame', 'tipe_frame', 'event_id', 'ukuran_cetak', 'file_name', 'layout_data'];
}
