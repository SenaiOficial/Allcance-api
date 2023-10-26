<?php

namespace App\Http\Controllers;

use App\Models\Pcd;
use App\Http\Requests\PcdRequest;

class PcdController extends Controller
{
    public function index()
    {
        $pcd = Pcd::all();
        return view("pcd.index", compact("pcd"));
    }

    public function store(PcdRequest $request, $userId)
    {
        try {
            $pcd = new Pcd();
            $pcd->user_id = $userId;
            $pcd->save();

            return response()->json(['message' => 'Cadastrado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['errors'=> $e->getMessage()], 500);
        }

    }
}
