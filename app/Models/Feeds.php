<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    use HasFactory;

    protected $table = 'feeds';
    protected $fillable = [
        'admin_user_id',
        'profile_photo',
        'institution_name',
        'is_event',
        'event_date',
        'event_time',
        'event_location',
        'title',
        'description',
        'image',
        'published_at'
    ];

    protected $cast = [
        'is_event' => 'boolean',
    ];

    protected $hidden = [
        'admin_user_id',
        'image',
        'created_at',
        'updated_at',
        'published_at',
    ];
    
    public function adminUser()
    {
        return $this->belongsTo(UserAdmin::class, 'admin_user_id', 'id');
    }
}
