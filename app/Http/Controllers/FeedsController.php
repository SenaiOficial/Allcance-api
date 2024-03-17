<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedsRequest;
use App\Services\FeedsService;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
  protected $feedsService;

  public function __construct(FeedsService $feedsService)
  {
    $this->feedsService = $feedsService;
  }

  public function get()
  {
    return $this->feedsService->get();
  }

  public function store(FeedsRequest $request)
  {
    return $this->feedsService->store($request);
  }

  public function delete(Request $request, $id)
  {
    return $this->feedsService->delete($request, $id);
  }

  public function getRanking()
  {
    return $this->feedsService->getHighlightsInstitutions();
  }
}