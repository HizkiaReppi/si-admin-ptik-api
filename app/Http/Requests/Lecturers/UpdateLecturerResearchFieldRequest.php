<?php

namespace App\Http\Requests\Lecturers;

use App\Classes\ApiResponseClass;
use App\Models\Lecturers\ResearchField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLecturerResearchFieldRequest extends FormRequest
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
            '*.field_name' => ['required', 'string', 'max:255', 'exists:' . ResearchField::class . ',id'],
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
            '*.field_name.required' => 'Bidang Riset wajib diisi',
            '*.field_name.string' => 'Bidang Riset harus berupa teks',
            '*.field_name.max' => 'Bidang Riset maksimal 255 karakter',
            '*.field_name.exists' => 'Bidang Riset tidak valid',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            '*.field_name' => 'Bidang Riset',
        ];

        return parent::attributes();
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()->toArray())
        );
    }
}
