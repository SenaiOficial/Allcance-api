<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ColorBlindness extends Model
{
    use HasFactory;

    protected $table = 'color_blindness';
    protected $fillable = ['description'];
}
