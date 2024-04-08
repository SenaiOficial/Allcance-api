<?php

namespace App\Services;

use App\Models\ColorBlindness;
use App\Models\Configuration;
use App\Models\TextSize;
use Illuminate\Http\Request;
use App\Services\UserService;

class ConfigService
{
  protected $userService;

  public function __construct(UserService $userService)
  {
    $this->userService = $userService;
  }

  private function getUser(Request $request)
  {
    $bearer = $request->bearerToken();
    $user = $this->userService->findUserByToken($bearer);
    return $user;
  }

  private function getDatas($model)
  {
    $data = $model::all(['id', 'description']);

    return response()->json($data);
  }
  public function getSizes()
  {
    return $this->getDatas(new TextSize);
  }

  public function getColorBlindness()
  {
    return $this->getDatas(new ColorBlindness);
  }

  public function createConfig(Request $request)
  {
    $user = $this->getUser($request);

    try {
      $requestData = $request->validate([
        'text_size_id' => 'required',
        'color_blindness_id' => 'required'
      ]);
      $requestData['pcd_user_id'] = $user->id;

      $config = new Configuration($requestData);
      $config->save();

      return response()->json(['message' => 'Configuração salva!']);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  public function getConfig(Request $request)
  {
    $user = $this->getUser($request);

    try {
      $config = Configuration::select('text_size_id', 'color_blindness_id')
        ->where('pcd_user_id', '=', $user->id)
        ->first();

      if ($config) {
        $textConfig = [
          'id' => $config->text->id,
          'value' => $config->text->description
        ];
        $colorConfig = [
          'id' => $config->blindness->id,
          'value' => $config->blindness->description
        ];

        return response()->json([
          'sucess' => true,
          'text' => $textConfig,
          'color' => $colorConfig
        ], 200);
      } else {
        return response()->json(['sucess' => false, 'message' => 'Configuração não encontrada!']);
      }
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }
}
