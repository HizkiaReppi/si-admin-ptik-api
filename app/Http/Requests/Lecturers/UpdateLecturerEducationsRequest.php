<?php

namespace App\Http\Requests\Lecturers;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLecturerEducationsRequest extends FormRequest
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
        $currentYear = date('Y');

        return [
            '*.degree' => ['required_with:*.field_of_study', 'string', 'max:50', 'in:S1,S2,S3'],
            '*.field_of_study' => ['required_with:*.degree', 'string', 'min:3', 'max:150'],
            '*.institution' => ['nullable', 'string', 'max:200'],
            '*.graduation_year' => ['nullable', 'digits:4', 'integer', 'min:1900', 'max:' . $currentYear],
            '*.thesisTitle' => ['nullable', 'string', 'max:255'],
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
            '*.degree.required_with' => 'Jenjang Studi wajib diisi.',
            '*.degree.string' => 'Jenjang Studi harus berupa string.',
            '*.degree.max' => 'Jenjang Studi maksimal 50 karakter.',
            '*.degree.in' => 'Jenjang Studi harus valid (S1, S2, S3).',
            '*.field_of_study.required_with' => 'Bidang studi wajib diisi.',
            '*.field_of_study.string' => 'Bidang studi harus berupa string.',
            '*.field_of_study.min' => 'Bidang studi harus memiliki setidaknya 3 karakter.',
            '*.field_of_study.max' => 'Bidang studi maksimal 150 karakter.',
            '*.institution.max' => 'Institusi maksimal 200 karakter.',
            '*.institution.string' => 'Institusi harus berupa string.',
            '*.graduation_year.digits' => 'Tahun Lulus harus berupa angka.',
            '*.graduation_year.integer' => 'Tahun Lulus harus berupa angka bulat.',
            '*.graduation_year.min' => 'Tahun Lulus minimal 1900.',
            '*.graduation_year.max' => 'Tahun Lulus maksimal ' . date('Y') . '.',
            '*.thesisTitle.max' => 'Judul Tesis maksimal 255 karakter.',
            '*.thesisTitle.string' => 'Judul Tesis harus berupa string.',
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
            '*.degree' => 'Jenjang Studi',
            '*.field_of_study' => 'Bidang Studi',
            '*.institution' => 'Institusi',
            '*.graduation_year' => 'Tahun Lulus',
            '*.thesisTitle' => 'Judul Tesis',
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
