<?php

namespace App\Http\Requests\Thread;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class ThreadStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    public function prepareForValidation()
    {
        $this->merge([
            'plain_content' => trim(strip_tags($this->input('content', ''))),
        ]);
    }

    #[Override]
    public function messages()
    {
        return  [
            'plain_content' => 'The content field is required.',
            'plain_content.min' => 'Must have at least one character.',
            'plain_content.max' => 'Must have less than 5000 characters.'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:4', 'max:100'],
            'content' => ['required', 'string'],
            'plain_content' => ['required', 'min:1', 'max:5000'],
        ];
    }
}
