<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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


            return response()->json(['message' => 'Endereço criado com sucesso']);
        } catch (\Exception $e) {
            Log::error('Erro ao criar endereço: ' . $e->getMessage());
            return response()->json(['errors' => $e->getMessage()], 500);
        }    
    }
}
