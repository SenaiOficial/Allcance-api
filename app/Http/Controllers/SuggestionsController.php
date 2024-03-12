<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SuggestionsService;

class SuggestionsController extends Controller
{
    protected $suggestionsService;

    public function __construct(SuggestionsService $suggestionsService)
    {
        $this->suggestionsService = $suggestionsService;
    }

    public function store(Request $request)
    {
        return $this->suggestionsService->store($request);
    }

    public function update(Request $request, $id)
    {
        return $this->suggestionsService->update($request, $id);
    }

    public function delete(Request $request, $id)
    {
        return $this->suggestionsService->delete($request, $id);
    }

    public function showApproved()
    {
        return $this->suggestionsService->showApproved();
    }

    public function showSuggestionsReq()
    {
        return $this->suggestionsService->showSuggestionsReq();
    }
}
