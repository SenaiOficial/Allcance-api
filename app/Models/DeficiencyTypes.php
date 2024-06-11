<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeficiencyTypes extends Model
{
    use HasFactory;

    protected $table = 'deficiency_types';
    protected $labels = ['description'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
