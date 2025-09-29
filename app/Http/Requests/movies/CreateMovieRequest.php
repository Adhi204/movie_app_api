<?php

namespace App\Http\Requests\movies;

use Illuminate\Foundation\Http\FormRequest;

class CreateMovieRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:50'],
            'year' => ['required', 'string', 'max:4'],
            'description' => ['nullable', 'string'],
            'poster' => ['nullable', 'image', 'max:1024', 'mimetypes:image/jpeg,image/png,image/gif,image/webp'],
        ];
    }
}
