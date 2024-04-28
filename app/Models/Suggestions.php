<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestions extends Model
{
    use HasFactory;

    protected $table = 'suggestions';
    protected $fillable = [
        'type',
        'user_id',
        'user',
        'content',
        'approved'
    ];

    protected $cast = [
        'approved' => 'boolean'
    ];

    protected $hidden = [
        'user_id',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(UserPcd::class);
    }
}
