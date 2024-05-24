<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class UserAdmin extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guard = 'admin';
    protected $table = 'admin_user';

    protected $fillable = [
        'institution_name',
        'profile_photo',
        'telephone',
        'cnpj',
        'pass_code',
        'email',
        'password',
        'is_institution',
        'is_admin',
        'custom_token',
        'refresh_token'
    ];

    protected $hidden = [
        'id',
        'password',
        'custom_token',
        'refresh_token',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getGuard()
    {
        return $this->guard;
    }

    public function configs()
    {
        return $this->hasMany(Configuration::class, 'user_id', 'id');
    }
}
