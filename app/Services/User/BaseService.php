<?php

namespace App\Services\User;

use App\Models\UserAdmin;

class BaseService
{
  protected $admGuard = UserAdmin::GUARD;

  protected function getUserInfos($user)
  {
    $type = $this->getUserType($user);

    if ($type !== 'default') {
      $info = [
        'name' => $user->institution_name,
        'cnpj' => $user->cnpj,
        'type' => $type
      ];
    } else {
      $info = [
        'cpf' => $user->cpf,
        'type' => $type,
        'pcd' => $user->getGuard() === $this->admGuard
      ];
    }

    return $info;
  }

  protected function getUserType($user)
  {
    $type = 'default';

    if ($user->getGuard() === $this->admGuard) {
      $type = $user->is_admin ? $user->getGuard() : 'institution';
    }

    return $type;
  }
}
