<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestions extends Model
{
    use HasFactory;

    protected $table = 'suggestions';
    protected $fillable = ['user_id', 'content', 'approved'];

    public function user()
    {
        return $this->belongsTo(UserPcd::class);
    }
}
