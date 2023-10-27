<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PcdRequest extends FormRequest
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
            'color' => ['required', 'string', 'max:255'],
            'job' => ['required', 'boolean'],
            'pcd_type' => ['required', 'string', 'max:255'],
            'pcd' => ['required', 'string', 'max:255'],
            'pcd_acquired' => ['required', 'boolean'],
            'needed_assistance' => ['required', 'boolean'],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }
}
