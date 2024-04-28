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
        'job' => 'boolean',
        'pcd' => 'array',
        'pcd_acquired' => 'boolean',
        'needed_assistance' => 'boolean',
        'get_transport' => 'boolean',
        'transport_access' => 'boolean' 
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

    public function resetPasswords()
    {
        return $this->hasMany(ResetPassword::class, 'email', 'email');
    }

    public function deficiencyTypes()
    {
        return $this->belongsTo(DeficiencyTypes::class, 'pcd_type');
    }

    public function pcdDeficiencies()
    {
        return $this->hasMany(UserDeficiency::class, 'pcd_user_id', 'id');

    }
}
