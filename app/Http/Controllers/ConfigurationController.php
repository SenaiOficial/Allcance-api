<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\ConfigService;

class ConfigurationController extends Controller
{
  protected $configService;

  public function __construct(ConfigService $configService)
  {
    $this->configService = $configService;
  }

  public function getSizes()
  {
    return $this->configService->getSizes();
  }

  public function getColorBlindness()
  {
    return $this->configService->getColorBlindness();
  }

  public function createConfig(Request $request)
  {
    return $this->configService->createConfig($request);
  }

  public function getConfig(Request $request)
  {
    return $this->configService->getConfig($request);
  }
}
