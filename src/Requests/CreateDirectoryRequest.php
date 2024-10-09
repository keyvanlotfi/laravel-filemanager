<?php

namespace KeyvanLotfi\FileManager\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDirectoryRequest extends FormRequest
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
            'folder_name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'folder_name.required' => 'The folder name is required',
            'folder_name.string' => 'The folder name should be string',
            'folder_name.max' => 'The folder name max length is 255 chars',
        ];
    }
}
