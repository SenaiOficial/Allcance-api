<?php

namespace App\Services;

use App\Models\ColorBlindness;
use App\Models\Configuration;
use App\Models\TextSize;
use DB;
use Illuminate\Http\Request;

class ConfigService
{
  protected $guard;

  public function __construct()
  {
    $this->guard = getActiveGuard();
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
    $user = auth($this->guard)->user();
    $user_id = $user->id;
    $type = $user->getTable();

    try {
      $requestData = $request->validate([
        'text_size_id' => 'required',
        'color_blindness_id' => 'required'
      ]);

      $config = Configuration::where('user_id', $user_id)
        ->where('type', $type)
        ->first();

      if ($config) {
        $config->update($requestData);
      } else {
        Configuration::create([
          'user_id' => $user_id,
          'type' => $type,
          'text_size_id' => $requestData['text_size_id'],
          'color_blindness_id' => $requestData['color_blindness_id']
        ]);
      }

      return response()->json(['message' => 'Configuração salva!']);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  public function getConfig(Request $request)
  {
    $user = auth($this->guard)->user();

    try {
      $config = Configuration::select('text_size_id', 'color_blindness_id')
        ->where('user_id', '=', $user->id)
        ->where('type', '=', $user->getTable())
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
