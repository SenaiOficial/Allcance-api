<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Validation\Validator;

class FeedsRequest extends FormRequest
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
            'is_event' => ['required', 'boolean'],
            'event_date' => ['nullable', 'date'],
            'event_time' => ['nullable', 'size:6'],
            'event_location' => ['nullable', 'max:100'],
            'title' => ['required', 'max:100'],
            'description' => ['required', 'max: 1000'],
            'image' => ['nullable', 'mimes: jpeg,png,jpg', 'max: 2048']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        
        // Log dos erros de validação
        Log::error('Erro de validação: ' . $errors->toJson());

        parent::failedValidation($validator);
    }
}
