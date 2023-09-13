<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    protected $fillabel = [
        'first_name',
        'last_name',
        'phone_number',
        'cpf',
        'date_of_birth',
        'marital_status',
        'gender',
        'email',
        'password'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function getAge()
    {
        return now()->diffInYears($this->date_of_birth);
    }
}
