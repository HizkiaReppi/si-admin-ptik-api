<?php

namespace App\Http\Requests\Students;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateStudentInformationRequest extends FormRequest
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
            'nationalIdNumber' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'placeOfBirth' => ['required', 'string', 'max:50'],
            'dateOfBirth' => ['required', 'date', 'before:today'],
            'maritalStatus' => ['required', 'string', Rule::in(['Single', 'Married'])],
            'religion' => ['required', 'string', Rule::in(['Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu'])],
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
            'nationalIdNumber.required' => 'NIK wajib diisi',
            'nationalIdNumber.string' => 'NIK harus berupa string',
            'nationalIdNumber.max' => 'NIK maksimal 20 karakter',
            'nationalIdNumber.regex' => 'NIK harus berupa angka',
            'placeOfBirth.required' => 'Tempat lahir wajib diisi',
            'placeOfBirth.string' => 'Tempat lahir harus berupa string',
            'placeOfBirth.max' => 'Tempat lahir maksimal 50 karakter',
            'dateOfBirth.required' => 'Tanggal lahir wajib diisi',
            'dateOfBirth.date' => 'Tanggal lahir harus berupa tanggal',
            'dateOfBirth.before' => 'Tanggal lahir harus sebelum hari ini',
            'maritalStatus.required' => 'Status pernikahan wajib diisi',
            'maritalStatus.string' => 'Status pernikahan harus berupa string',
            'maritalStatus.in' => 'Status pernikahan harus "Single" atau "Married"',
            'religion.required' => 'Agama wajib diisi',
            'religion.string' => 'Agama harus berupa string',
            'religion.in' => 'Agama harus "Protestan", "Katolik", "Islam", "Hindu", "Buddha", atau "Konghucu"',
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
            'nationalIdNumber' => 'Nomor Induk Kependudukan',
            'placeOfBirth' => 'Tempat Lahir',
            'dateOfBirth' => 'Tanggal Lahir',
            'maritalStatus' => 'Status Pernikahan',
            'religion' => 'Agama',
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
