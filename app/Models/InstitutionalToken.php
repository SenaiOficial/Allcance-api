<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalToken extends Model
{
    use HasFactory;

    protected $table = 'generate_institutional_token';
    protected $fillable = ['institutional_token'];
}
