<?php

namespace App\Http\Requests\Admin;

use App\Classes\ApiResponseClass;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreAdminRequest extends FormRequest
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
                'max:255',
                Rule::unique(User::class),
                'regex:/^[a-z][a-z\d_]*$/'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/', // At least 1 uppercase, 1 lowercase, 1 digit, 1 special character
                'confirmed',
            ],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
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
            'name.required' => 'Nama Lengkap wajib diisi.',
            'name.string' => 'Nama Lengkap harus berupa string.',
            'name.max' => 'Nama Lengkap maksimal 255 karakter.',
            'name.min' => 'Nama Lengkap minimal 2 karakter.',
            'name.regex' => 'Nama Lengkap hanya boleh berisi huruf.',
            'email.required' => 'Alamat Email wajib diisi.',
            'email.string' => 'Alamat Email harus berupa string.',
            'email.email' => 'Alamat Email harus valid.',
            'email.max' => 'Alamat Email maksimal 255 karakter.',
            'email.min' => 'Alamat Email minimal 4 karakter.',
            'email.unique' => 'Alamat Email sudah terdaftar.',
            'photo.image' => 'Foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat png, jpg, atau jpeg.',
            'photo.max' => 'Foto maksimal 2MB.',
            'username.required' => 'Username wajib diisi',
            'username.string' => 'Username harus berupa string',
            'username.min' => 'Username minimal 3 karakter',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username sudah terdaftar',
            'username.regex' => 'Username hanya boleh berisi huruf kecil, angka, dan underscore',
            'password.required' => 'Kata Sandi wajib diisi.',
            'password.string' => 'Kata Sandi harus berupa string.',
            'password.min' => 'Kata Sandi harus memiliki setidaknya 6 karakter.',
            'password.regex' => 'Kata Sandi harus memiliki setidaknya 1 huruf besar, 1 huruf kecil, 1 angka, dan 1 karakter spesial.',
            'password.confirmed' => 'Konfirmasi Kata Sandi tidak cocok.',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
            'gender.in' => 'Jenis Kelamin haruslah "Male" atau "Female".',
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
            'name' => 'Nama Lengkap',
            'email' => 'Alamat Email',
            'photo' => 'Foto',
            'gender' => 'Jenis Kelamin',
            'username' => 'Nama Pengguna',
            'password' => 'Kata Sandi',
        ];

        return parent::attributes();
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()->toArray())
        );
    }
}
