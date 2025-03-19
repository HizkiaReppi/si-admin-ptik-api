<?php

namespace App\Http\Requests\Students;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateStudentAddressRequest extends FormRequest
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
            'province' => ['required', 'string', 'max:50'],
            'regency' => ['required', 'string', 'max:50'],
            'district' => ['required', 'string', 'max:50'],
            'village' => ['required', 'string', 'max:50'],
            'postalCode' => ['nullable', 'string', 'max:10', 'regex:/^[0-9]+$/'],
            'address' => ['nullable', 'string', 'max:255'],
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
            'province.required' => 'Nama Provinsi wajib diisi',
            'province.string' => 'Nama Provinsi harus berupa string',
            'province.max' => 'Nama Provinsi maksimal 50 karakter',
            'regency.required' => 'Kabupaten/Kota wajib diisi',
           'regency.string' => 'Kabupaten/Kota harus berupa string',
           'regency.max' => 'Kabupaten/Kota maksimal 50 karakter',
            'district.required' => 'Kecamatan wajib diisi',
            'district.string' => 'Kecamatan harus berupa string',
            'district.max' => 'Kecamatan maksimal 50 karakter',
            'village.required' => 'Desa/Kelurahan wajib diisi',
            'village.string' => 'Desa/Kelurahan harus berupa string',
            'village.max' => 'Desa/Kelurahan maksimal 50 karakter',
            'postalCode.required' => 'Kode Pos wajib diisi',
            'postalCode.string' => 'Kode Pos harus berupa string',
            'postalCode.max' => 'Kode Pos maksimal 10 karakter',
            'address.required' => 'Alamat wajib diisi',
            'address.string' => 'Alamat harus berupa string',
            'address.max' => 'Alamat maksimal 255 karakter',
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
            'province' => 'Provinsi',
            'regency' => 'Kabupaten/Kota',
            'district' => 'Kecamatan',
            'village' => 'Desa/Kelurahan',
            'postalCode' => 'Kode Pos',
            'address' => 'Alamat',
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
