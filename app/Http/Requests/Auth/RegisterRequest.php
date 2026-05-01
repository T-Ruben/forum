<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Override;
use Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // #[Override]
    // public function prepareForValidation()
    // {
    //     return sprintf('%04d-%02d-%02d',
    //         $this->year,
    //         $this->month,
    //         $this->day
    //     );
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:20', 'unique:users', 'regex:/^[a-zA-Z0-9._-]{3, 20}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'max:128', 'confirmed', RulesPassword::defaults()],
            'gender' => ['required'],
            'location' => ['nullable', 'string', 'max:75'],
            'year' => ['required', 'integer', 'min:1900', 'max:' . now()->year],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'day' => ['required', 'integer', 'min:1', 'max:31']
        ];
    }
}
