<?php

namespace App\Domain\Note\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteStoreFromFileRequest extends FormRequest
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
            'title' => 'required|string',
            'file' => 'required|file|mimetypes:text/plain|max:5120',
        ];
    }
}
