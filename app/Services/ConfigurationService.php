<?php

namespace App\Services;

use App\Models\Configuration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfigurationService
{
  protected $user;

  public function __construct()
  {
    $this->user = auth(getActiveGuard())->user();
  }

  private static function getSizes(): array
  {
    return [
      'Padrão',
      'Muito pequeno',
      'Pequeno',
      'Normal',
      'Grande',
      'Muito grande'
    ];
  }

  private static function getColorBlindness(): array
  {
    return [
      'Padrão',
      'Deuteranopia',
      'Protanopia',
      'Tritanopia'
    ];
  }

  public function getOptions(): JsonResponse
  {
    $text = $this->getSizes();
    $color = $this->getColorBlindness();

    return response()->json([
      'text_sizes' => $text,
      'color_types' => $color
    ]);
  }

  public function createConfig(Request $request)
  {
    $user = $this->user;
    $user_id = $user->id;
    $type = $user->getTable();

    try {
      $requestData = $request->validate([
        'text_size' => 'required|string|in:Muito pequeno,Pequeno,Normal,Grande,Muito grande',
        'color_blindness' => 'nullable|string|in:Padrão,Deuteranopia,Protanopia,Tritanopia'
      ]);

      $config = Configuration::where('user_id', $user_id)
        ->where('type', $type)
        ->first();

      $colorBlindness = $this->checkBlindnesDefault($requestData['color_blindness']);

      if ($config) {
        $config->update(array_merge($requestData, ['color_blindness' => $colorBlindness]));
      } else {
        Configuration::create([
          'user_id' => $user_id,
          'type' => $type,
          'text_size' => $requestData['text_size'],
          'color_blindness' => $colorBlindness
        ]);
      }

      return response()->json([
        'success' => true,
        'message' => 'Configuração salva!'
      ], 200);
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'Formato inválido!',
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function getConfig(Request $request)
  {
    $user = $this->user;

    try {
      $config = $user->configs->first();

      if ($config) {
        $configs = [
          'text_size' => $config->text_size,
          'color_blindness' => $config->color_blindness
        ];

        return response()->json([
          'success' => true,
          'configuration' => $configs,
        ], 200);
      } else return;
    } catch (\Exception $e) {
      return response()->json([
        'success' => false,
        'error' => $e->getMessage()
      ], 500);
    }
  }

  private function checkBlindnesDefault($res)
  {
    if ($res === 'Padrão') return null;

    return $res;
  }
}
