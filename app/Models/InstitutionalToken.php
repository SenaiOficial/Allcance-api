<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalToken extends Model
{
    use HasFactory;

    protected $table = 'institutional_tokens';
    protected $fillable = [
        'user_id',
        'token',
        'expires_at'
    ];
}
