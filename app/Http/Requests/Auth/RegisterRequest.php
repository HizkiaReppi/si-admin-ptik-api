<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'regex:/^[a-zA-Z\s]*$/'
            ],
            'username' => [
                'required',
                'string',
                'min:3',
                'max:20',
                'unique:' . User::class,
                'regex:/^[0-9]*$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:' . User::class,
                'regex:/^[a-zA-Z0-9._%+-]+@unima\.ac\.id$/'
            ],
            'lecturer_id' => [
                'required',
                'string',
                'exists:lecturers,id'
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', // At least 1 uppercase, 1 lowercase, 1 digit, 1 special character
                'confirmed',
            ],
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama tidak boleh lebih dari 255 karakter.',
            'name.min' => 'Nama harus memiliki setidaknya 2 karakter.',
            'name.regex' => 'Nama hanya boleh berisi huruf.',

            'username.required' => 'Nomor Induk Mahasiswa wajib diisi.',
            'username.string' => 'Nomor Induk Mahasiswa harus berupa teks.',
            'username.min' => 'Nomor Induk Mahasiswa harus memiliki setidaknya 3 karakter.',
            'username.max' => 'Nomor Induk Mahasiswa tidak boleh lebih dari 255 karakter.',
            'username.unique' => 'Nomor Induk Mahasiswa sudah terdaftar.',
            'username.regex' => 'Nomor Induk Mahasiswa hanya boleh berisi angka.',

            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid. Contoh: example@gmail.com.',
            'email.max' => 'Email tidak boleh lebih dari 255 karakter.',
            'email.unique' => 'Email sudah terdaftar.',
            'email.regex' => 'Email harus menggunakan domain unima.ac.id.',

            'lecturer_id.required' => 'Dosen pembimbing akademik wajib diisi.',
            'lecturer_id.string' => 'Dosen pembimbing akademik harus berupa teks.',
            'lecturer_id.exists' => 'Dosen pembimbing akademik tidak ditemukan.',

            'password.required' => 'Kata sandi wajib diisi.',
            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi harus memiliki setidaknya 6 karakter.',
            'password.regex' => 'Kata sandi harus mengandung minimal 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter spesial (seperti @, _, #, $, %, ^, &, *, !, dll.).',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
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
        throw new HttpResponseException(
            ApiResponseClass::sendError(422, 'Validation errors', $validator->errors())
        );
    }
}