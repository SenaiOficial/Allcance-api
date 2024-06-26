<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:11'],
            'cpf' => ['required', 'unique:users', 'unique:standar_user', 'min:11', 'max:14'],
            'date_of_birth' => ['required', 'date'],
            'marital_status' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pcd_users', 'unique:standar_user', 'unique:admin_user'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
            'confirm_password' => ['required', 'same:password'],
            'cep' => ['required', 'string', 'max:8'],
            'country' => ['required', 'string', 'max:30'],
            'state' => ['required', 'string', 'max:30'],
            'city' => ['required', 'string', 'max:50'],
            'neighborhood' => ['required', 'string', 'max:50'],
            'street' => ['required', 'string', 'max:255'],
            'street_number' => ['required', 'string', 'max:10'],
            'street_complement' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:20'],
            'job' => ['required', 'boolean'],
            'pcd_type' => ['required', 'int'],
            'pcd' => ['required', 'array', 'max:255'],
            'pcd_acquired' => ['required', 'boolean'],
            'needed_assistance' => ['required', 'boolean'],
            'get_transport' => ['required', 'boolean'],
            'transport_access' => ['nullable', 'boolean']
        ];
        // if ($errors = $request->validator->errors()) {
        // }
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        
        // Log dos erros de validação
        Log::error('Erro de validação: ' . $errors->toJson());

        parent::failedValidation($validator);
    }
}
