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
    
    public function adminUser()
    {
        return $this->belongsTo(UserAdmin::class, 'admin_user_id', 'id');
    }
}
