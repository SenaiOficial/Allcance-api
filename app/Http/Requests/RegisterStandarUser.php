<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStandarUser extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'cpf' => ['required', 'unique:users', 'unique:standar_user,cpf', 'min:11', 'max:14'],
            'date_of_birth' => ['required', 'date'],
            'marital_status' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'state' => ['required'],
            'city' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:standar_user'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
        ];
    }
}
