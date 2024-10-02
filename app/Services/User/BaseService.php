<?php

namespace App\Services\User;

use App\Models\UserAdmin;

class BaseService
{
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
        'pcd' => $user->getGuard() === UserAdmin::GUARD
      ];
    }

    return $info;
  }

  protected function getUserType($user)
  {
    $type = 'default';

    if ($user->getGuard() === UserAdmin::GUARD) {
      $type = $user->is_admin ? $user->getGuard() : 'institution';
    }

    return $type;
  }
}
