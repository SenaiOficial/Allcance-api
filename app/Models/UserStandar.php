<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;


class UserStandar extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $guard = 'standar';
    protected $table = 'standar_user';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'cpf',
        'date_of_birth',
        'marital_status',
        'gender',
        'state',
        'city',
        'email',
        'password',
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

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
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
}
