<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Models\ResetPassword;

class UserAdmin extends Model implements Authenticatable
{
    use HasFactory;

    protected $table = 'admin_user';

    protected $fillable = [
        'institution_name',
        'telephone',
        'cnpj',
        'pass_code',
        'email',
        'password',
        'is_institution',
        'custom_token'
    ];

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
}
