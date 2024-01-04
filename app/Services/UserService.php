<?php

namespace App\Services;

use App\Models\UserPcd;
use App\Models\UserAdmin;
use App\Models\UserStandar;

Class UserService
{
  public function findUserByToken($token)
  {
    $userPcd = UserPcd::where('custom_token', $token)->first();

    if (!$userPcd) {
      $userAdmin = UserAdmin::where('custom_token', $token)->first();

      if ($userAdmin) {
        return $userAdmin;
      }
    }

    $standarUser = UserStandar::where('custom_token', $token)->first();

    if ($standarUser) {
      return $standarUser;
    }

    return $userPcd;
  }
}