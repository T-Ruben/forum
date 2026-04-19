<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'plain_content' =>   trim(strip_tags($this->input('content', ''))),
        ]);
    }

    public function messages(): array
    {
        return [
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
            'content' => ['required', 'string'],
            'plain_content' => ['required', 'min:1', 'max:5000'],
            'thread_id' => 'required|exists:threads,id',
            'parent_id' => 'nullable|exists:posts,id'
        ];
    }
}
