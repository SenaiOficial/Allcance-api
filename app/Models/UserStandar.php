<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStandar extends Model
{
    use HasFactory;

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
        'custom_token'
    ];
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
}
