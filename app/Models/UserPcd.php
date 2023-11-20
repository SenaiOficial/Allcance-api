<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;

class UserPcd extends Model implements Authenticatable
{
    use HasFactory;

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
        'street',
        'street_number',
        'street_complement',
        'color',
        'job',
        'pcd_type',
        'pcd',
        'pcd_acquired',
        'needed_assistance',
        'custom_token'
    ];

    public function getAge()
    {
        return now()->diffInYears($this->date_of_birth);
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
