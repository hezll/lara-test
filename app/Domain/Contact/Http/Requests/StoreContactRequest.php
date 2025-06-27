<?php

namespace App\Domain\Contact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Domain\Contact\Models\Contact;
use Illuminate\Validation\Rule;
/**
 * Class StoreContactRequest
 *
 * @package App\Domain\Contact\Http\Requests
 */

class StoreContactRequest extends FormRequest
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
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => [
                'required',
                'regex:/^\+61\d{9}$|^\+64\d{8,9}$/'
            ],
            'notes'  => ['nullable', 'string'],
            'tags'   => ['nullable', 'array'],
            'tags.*' => ['string'],
            'source' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Check duplicate phone
            if (Contact::where('phone', $this->phone)->exists()) {
                $validator->errors()->add('phone', 'Phone number already exists.');
            }

            // Check duplicate email
            if (Contact::where('email', $this->email)->exists()) {
                $validator->errors()->add('email', 'Email address already exists.');
            }
        });
    }
}
