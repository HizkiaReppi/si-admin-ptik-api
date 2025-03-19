<?php

namespace App\Http\Requests\HeadOfDepartment;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class StoreHeadOfDepartmentRequest extends FormRequest
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
            'lecturer_id' => ['required', 'string', Rule::exists('lecturers', 'id')],
            'role' => ['required', 'string', Rule::in(['kajur', 'sekjur'])],
            'signiture_file' => ['nullable', 'file', 'mimes:png', 'max:1080'],
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
            'lecturer_id.exists' => 'Dosen tidak ditemukan',
            'lecturer_id.required' => 'Dosen tidak wajib diisi',
            'role.required' => 'Jabatan tidak wajib diisi',
            'role.in' => 'Jabatan tidak valid (Ketua Jurusan, Sekretaris Jurusan)',
            'signiture_file.file' => 'Tanda tangan harus berupa file',
            'signiture_file.mimes' => 'Tanda tangan harus berupa file PNG',
            'signiture_file.max' => 'Tanda tangan maksimal 1080 KB',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()));
    }
}
