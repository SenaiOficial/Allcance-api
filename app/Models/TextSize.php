<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextSize extends Model
{
    use HasFactory;

    protected $table = 'text_size';
    protected $fillable = ['description'];
}
