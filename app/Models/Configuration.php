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
        'text_size',
        'color_blindness'
    ];
    protected $hidden = ['type'];
}
