<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseClass;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('categories')],
            'requirements' => ['sometimes', 'array'],
            'requirements.*.name' => ['required', 'string', 'max:255'],
            'requirements.*.type ' => ['required', 'string', Rule::in(['document', 'photo', 'text'])],
            'requirements.*.file' => ['sometimes', 'file', 'mimes:pdf,doc,docx'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category wajib diisi.',
            'name.string' => 'Category harus berupa string.',
            'name.min' => 'Category minimal terdiri dari 2 karakter.',
            'name.max' => 'Category maksimal terdiri dari 255 karakter.',
            'name.unique' => 'Category sudah terdaftar.',
            'requirements.array' => 'Field requirements harus berupa array.',
            'requirements.*.name.required' => 'Nama file wajib diisi.',
            'requirements.*.name.string' => 'Nama file harus berupa string.',
            'requirements.*.name.max' => 'Nama file maksimal terdiri dari 255 karakter.',
            'requirements.*.type.required' => 'Tipe file wajib diisi.',
            'requirements.*.type.in' => 'Tipe file harus berupa document, photo, atau text.',
            'requirements.*.file.file' => 'File yang anda pilih bukan berupa file.',
            'requirements.*.file.mimes' => 'File yang anda pilih harus berupa file PDF, DOC, atau DOCX.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()));
    }
}
