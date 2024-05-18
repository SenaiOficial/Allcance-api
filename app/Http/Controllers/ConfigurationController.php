<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\ConfigurationService;

class ConfigurationController extends Controller
{
  protected $configService;

  public function __construct(ConfigurationService $configService)
  {
    $this->configService = $configService;
  }

  public function getOptions()
  {
    return $this->configService->getOptions();
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
