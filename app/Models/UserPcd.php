<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserPcd extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guard = 'api';
    protected $table = 'pcd_users';
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'cpf',
        'date_of_birth',
        'marital_status',
        'gender',
        'email',
        'password',
        'cep',
        'country',
        'state',
        'city',
        'neighborhood',
        'street',
        'street_number',
        'street_complement',
        'color',
        'job',
        'pcd_type',
        'pcd',
        'pcd_acquired',
        'needed_assistance',
        'get_transport',
        'transport_access',
        'custom_token',
        'refresh_token'
    ];

    protected $casts = [
        'pcd' => 'array'
    ];

    protected $hidden = [
        'password',
        'custom_token',
        'refresh_token'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
