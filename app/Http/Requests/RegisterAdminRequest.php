<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAdminRequest extends FormRequest
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
            'institution_name' => ['required', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'string'],
            'telephone' => ['required', 'string', 'max:20'],
            'cnpj' => ['required', 'unique:admin_user', 'size:14'],
            'pass_code' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:standar_user', 'unique:admin_user'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]+$/',
            ],
            'confirm_password' => ['required', 'same:password'],
        ];
    }
}
