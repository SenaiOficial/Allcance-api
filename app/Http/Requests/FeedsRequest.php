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
        if (request()->isMethod('post')) {
            $rule = 'required';
        } else if (request()->isMethod('put')) {
            $rule = 'sometimes';
        }

        return [
            'is_event' => [$rule, 'boolean'],
            'event_date' => ['nullable', 'date'],
            'event_time' => ['nullable', 'size:6'],
            'event_location' => ['nullable', 'max:100'],
            'title' => [$rule, 'max:100'],
            'description' => [$rule, 'max: 1000'],
            'image' => ['nullable', 'max:2048', 'mimes:jpeg,jpg,png']
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
