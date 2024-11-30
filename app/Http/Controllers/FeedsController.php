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

  public function update(FeedsRequest $request, $id)
  {
    return $this->feedsService->update($request, $id);
  }

  public function delete(Request $request, $id)
  {
    return $this->feedsService->delete($request, $id);
  }

    public function cleanCache()
  {
    $this->feedsService->cleanCacheFeeds();
    return response()->json('Cache limpo!', 200);
  }
}
