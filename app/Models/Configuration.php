<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configuration';
    protected $fillable = [
        'user_id',
        'type',
        'text_size_id',
        'color_blindness_id'
    ];

    public function text()
    {
        return $this->belongsTo(TextSize::class, 'text_size_id', 'id');
    }

    public function blindness()
    {
        return $this->belongsTo(ColorBlindness::class, 'color_blindness_id', 'id');
    }
}
