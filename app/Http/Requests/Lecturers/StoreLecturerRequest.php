<?php

namespace App\Http\Requests\Lecturers;

use App\Classes\ApiResponseClass;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLecturerRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[a-zA-Z\s]*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:4', 'unique:' . User::class],
            'nip' => ['required', 'string', 'min:18', 'max:18', 'unique:' . Lecturer::class, 'regex:/^[0-9]*$/'],
            'nidn' => ['required', 'string', 'min:10', 'max:10', 'unique:' . Lecturer::class, 'regex:/^[0-9]*$/'],
            'frontDegree' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z\s.,]*$/'],
            'backDegree' => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-Z\s.,]*$/'],
            'position' => ['nullable', 'string', 'max:100'],
            'rank' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'string', 'in:PNS,Honorer,Kontrak'],
            'phoneNumber' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]*$/'],
            'address' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
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
            'name.required' => 'Nama wajib diisi.',
            'name.regex' => 'Nama harus berupa huruf.',
            'name.min' => 'Nama minimal 2 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.min' => 'Email minimal 4 karakter.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'nip.required' => 'NIP wajib diisi.',
            'nip.min' => 'NIP harus berisi 18 karakter.',
            'nip.max' => 'NIP harus berisi 18 karakter.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nip.regex' => 'NIP harus berupa angka.',
            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.min' => 'NIDN harus berisi 10 karakter.',
            'nidn.max' => 'NIDN harus berisi 10 karakter.',
            'nidn.unique' => 'NIDN sudah terdaftar.',
            'nidn.regex' => 'NIDN harus berupa angka.',
            'frontDegree.regex' => 'Gelar depan harus berupa huruf.',
            'backDegree.regex' => 'Gelar belakang harus berupa huruf.',
            'phoneNumber.regex' => 'Nomor telepon harus berupa angka.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berupa file dengan format jpeg, png, atau jpg.',
            'photo.max' => 'Foto maksimal 2MB.',
            'frontDegree.max' => 'Gelar depan maksimal 50 karakter.',
            'backDegree.max' => 'Gelar belakang maksimal 50 karakter.',
            'position.max' => 'Jabatan maksimal 100 karakter.',
            'rank.max' => 'Pangkat maksimal 100 karakter.',
            'type.in' => 'Tipe harus valid.',
            'phoneNumber.max' => 'Nomor telepon maksimal 20 karakter.',
            'address.string' => 'Alamat harus berupa teks.',
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
            'name' => 'Nama',
            'email' => 'Email',
            'nip' => 'NIP',
            'nidn' => 'NIDN',
            'frontDegree' => 'Gelar Depan',
            'backDegree' => 'Gelar Belakang',
            'position' => 'Jabatan',
            'rank' => 'Pangkat',
            'type' => 'Tipe',
            'phoneNumber' => 'Nomor Telepon',
            'address' => 'Alamat',
            'photo' => 'Foto',
        ];

        return parent::attributes();
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()));
    }
}
