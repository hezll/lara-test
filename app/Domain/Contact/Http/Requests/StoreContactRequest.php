<?php

namespace App\Domain\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => ['nullable', 'uuid'],
            'name' => ['required', 'string'],
            'phone' => ['required', 'regex:/^\+64\d{8,10}$/', 'unique:contacts,phone'],
            'email' => ['required', 'email', 'unique:contacts,email'],
        ];
    }
}
