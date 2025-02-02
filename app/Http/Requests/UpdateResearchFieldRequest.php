<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseClass;
use App\Models\Lecturers\ResearchField;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateResearchFieldRequest extends FormRequest
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
        $isResearchFieldExist = ResearchField::where('field_name', $this->field_name)->exists();

        $rules = [
            'description' => ['nullable', 'string'],
        ];

        if($this->field_name && !$isResearchFieldExist) {
            $rules['field_name'] = ['required', 'string', 'min:2', 'max:200', 'unique:' . ResearchField::class,];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'field_name.required' => 'Nama Bidang Riset wajib diisi.',
            'field_name.min' => 'Nama Bidang Riset minimal 2 karakter.',
            'field_name.max' => 'Nama Bidang Riset maksimal 200 karakter.',
            'field_name.unique' => 'Nama Bidang Riset sudah terdaftar.',
            'description.string' => 'Deskripsi harus berupa teks.',
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
            'field_name' => 'Nama Bidang Riset',
            'description' => 'Deskripsi Bidang Riset',
        ];

        return parent::attributes();
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()));
    }
}
