<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pcd extends Model
{
    use HasFactory;

    protected $table = 'pcd_camps';
    protected $primaryKey = 'id';
    protected $fillable = [
        'color',
        'job',
        'pcd_type',
        'pcd',
        'pcd_acquired',
        'needed_assistance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
