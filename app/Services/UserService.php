<?php

namespace App\Services;

use App\Models\UserPcd;
use App\Models\UserAdmin;
use App\Models\UserStandar;

class UserService
{
  protected $userPcd;
  protected $userStandar;
  protected $userAdmin;

  public function __construct(UserPcd $userPcd, UserStandar $userStandar, UserAdmin $userAdmin)
  {
    $this->userPcd = $userPcd;
    $this->userStandar = $userStandar;
    $this->userAdmin = $userAdmin;
  }

  public function findUserByToken($token)
  {
    if ($token === null) abort(404, 'Nenhum usuário encontrado');

    $userPcd = $this->userPcd->where('custom_token', $token)->first();
    $userAdmin = $this->userAdmin->where('custom_token', $token)->first();
    $userStandar = $this->userStandar->where('custom_token', $token)->first();

    return $userPcd ?? $userStandar ?? $userAdmin;
  }
}