<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all users to access this for CRUD operations
    }

    /**
     * Get the validation rules for the request.
     */
    public function rules(): array
    {
        return [
            'locale' => 'required|string|size:2',
            'key' => 'required|string|unique:translations,key',
            'value' => 'required|string',
        ];
    }
}
