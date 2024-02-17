<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserStandar;
use App\Models\UserAdmin;
use App\Models\UserPcd;

class ResetPassword extends Model
{
    use HasFactory;

    protected $table = 'reset_passwords';
    protected $fillable = ['email', 'token'];

    public function updateUserPasswords($newPassword)
    {
        UserStandar::where('email', $this->email)->update(['password' => bcrypt($newPassword)]);
        UserAdmin::where('email', $this->email)->update(['password' => bcrypt($newPassword)]);
        UserPcd::where('email', $this->email)->update(['password' => bcrypt($newPassword)]);
    }
}
