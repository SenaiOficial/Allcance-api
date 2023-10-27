<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Routing\Controller;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::all();
        return view('addresses.index', compact('addresses'));
    }

    public function store(AddressRequest $request, $userId)
    {
        try {
            $validatedData = $request->validated();
            
            $address = new Address();
            $address->fill($validatedData);
            $address->user_id = $userId;
            $address->save();

            return response()->json(['message' => 'Cadastrado com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }    
    }
}
