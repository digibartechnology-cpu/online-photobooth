<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FilterSetting extends Model {
    protected $fillable = ['bw_active', 'vintage_active', 'contrast_active', 'ai_active', 'ai_model', 'ai_api_key'];
}