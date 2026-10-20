<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['nama_kegiatan', 'waktu_buka', 'waktu_tutup'];
}