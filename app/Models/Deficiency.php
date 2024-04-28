<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deficiency extends Model
{
    use HasFactory;

    protected $table = 'deficiency';
    protected $labels = ['description'];
    protected $fillable = ['description'];
    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function pcdUser()
    {
        return $this->belongsTo(UserPcd::class, 'pcd_id');
    }
}
