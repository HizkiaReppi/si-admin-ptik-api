<?php

namespace App\Http\Requests\Students;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateStudentParentsRequest extends FormRequest
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
            'fatherName' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'motherName' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[a-zA-Z\s]+$/'],
            'fatherOccupation' => ['nullable', 'string', 'max:100'],
            'motherOccupation' => ['nullable', 'string', 'max:100'],
            'income' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]+$/'],
            'parentPhoneNumber' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
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
            'fatherName.required' => 'Nama Ayah wajib diisi',
            'fatherName.string' => 'Nama Ayah harus berupa string',
            'fatherName.min' => 'Nama Ayah minimal 2 karakter',
            'fatherName.max' => 'Nama Ayah maksimal 100 karakter',
            'fatherName.regex' => 'Nama Ayah hanya boleh berisi huruf',
            'motherName.required' => 'Nama Ibu wajib diisi',
            'motherName.string' => 'Nama Ibu harus berupa string',
            'motherName.min' => 'Nama Ayah minimal 2 karakter',
            'motherName.max' => 'Nama Ibu maksimal 100 karakter',
            'motherName.regex' => 'Nama Ibu hanya boleh berisi huruf',
            'fatherOccupation.string' => 'Pekerjaan Ayah harus berupa string',
            'fatherOccupation.max' => 'Pekerjaan Ayah maksimal 100 karakter',
            'motherOccupation.string' => 'Pekerjaan Ibu harus berupa string',
            'motherOccupation.max' => 'Pekerjaan Ibu maksimal 100 karakter',
            'income.string' => 'Penghasilan harus berupa string',
            'income.max' => 'Penghasilan maksimal 50 karakter',
            'income.regex' => 'Penghasilan hanya boleh berisi angka',
            'parentPhoneNumber.string' => 'Nomor Telepon Orang Tua harus berupa string',
            'parentPhoneNumber.max' => 'Nomor Telepon Orang Tua maksimal 20 karakter',
            'parentPhoneNumber.regex' => 'Nomor Telepon Orang Tua hanya boleh berisi angka',
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
            'fatherName' => 'Nama Ayah',
            'motherName' => 'Nama Ibu',
            'fatherOccupation' => 'Pekerjaan Ayah',
            'motherOccupation' => 'Pekerjaan Ibu',
            'income' => 'Penghasilan',
            'parentPhoneNumber' => 'Nomor Telepon Orang Tua',
        ];

        return parent::attributes();
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()->toArray()));
    }
}
