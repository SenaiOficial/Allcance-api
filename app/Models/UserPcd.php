<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\ResetPassword;

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
        'custom_token'
    ];

    protected $casts = [
        'pcd' => 'array'
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
