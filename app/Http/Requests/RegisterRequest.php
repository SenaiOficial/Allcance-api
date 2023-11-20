<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'phone_number' => ['required', 'string', 'max:20'],
            'cpf' => ['required', 'unique:users', 'unique:standar_user', 'min:11', 'max:14'],
            'date_of_birth' => ['required', 'date'],
            'marital_status' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:standar_user', 'unique:admin_user'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
            'cep' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'street_number' => ['required', 'string', 'max:255'],
            'street_complement' => ['required', 'string', 'max:255'],
            'color' => ['required', 'string', 'max:255'],
            'job' => ['required', 'boolean'],
            'pcd_type' => ['required', 'string', 'max:255'],
            'pcd' => ['required', 'string', 'max:255'],
            'pcd_acquired' => ['required', 'boolean'],
            'needed_assistance' => ['required', 'boolean'],
        ];
    }
}
