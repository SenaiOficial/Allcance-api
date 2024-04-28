<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDeficiency extends Model
{
    protected $table = 'pcd_user_deficiency';

    protected $fillable = [
        'pcd_user_id',
        'deficiency_id',
    ];

    protected $hidden = [
        'pcd_user_id',
        'deficiency_id',
        'created_at',
        'updated_at',
    ];

    public function deficiency()
    {
        return $this->belongsTo(Deficiency::class, 'deficiency_id', 'id');
    }
}
