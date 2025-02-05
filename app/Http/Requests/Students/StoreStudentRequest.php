<?php

namespace App\Http\Requests\Students;

use App\Classes\ApiResponseClass;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
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
        $rules = [
            'name' => ['required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]*$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'min:4', Rule::unique('users', 'email')],
            'photo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'lecturerId1' => ['required', Rule::exists('lecturers', 'id')],
            'nim' => ['required', 'string', 'max:15', 'min:4', Rule::unique('students', 'nim'), Rule::unique('users', 'username'), 'regex:/^[0-9]*$/'],
            'entryYear' => ['required', 'integer', 'digits:4', 'min:' . date('Y') - 8, 'max:' . (date('Y'))],
            'class' => ['required', Rule::in(['reguler', 'rpl'])],
            'gender' => ['required', Rule::in(['Male', 'Female'])],
            'concentration' => ['required', 'string', Rule::in(['RPL', 'Multimedia', 'TKJ'])],
            'phoneNumber' => ['nullable', 'string', 'max:20', 'regex:/^08[0-9]+$/'],
        ];

        if ($this->lecturerId2 && $this->lecturerId2 != "") {
            $rules['lecturerId2'] = ['required', Rule::exists('lecturers', 'id'), 'different:lecturerId1'];
        } else {
            $rules['lecturerId2'] = ['nullable'];
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
            'lecturerId1.required' => 'Dosen Pembimbing 1 wajib diisi.',
            'lecturerId1.exists' => 'Dosen Pembimbing 1 tidak ditemukan.',
            'nim.required' => 'Nomor Induk Mahasiswa wajib diisi.',
            'nim.string' => 'Nomor Induk Mahasiswa harus berupa string.',
            'nim.max' => 'Nomor Induk Mahasiswa maksimal 15 karakter.',
            'nim.min' => 'Nomor Induk Mahasiswa minimal 4 karakter.',
            'nim.unique' => 'Nomor Induk Mahasiswa sudah terdaftar.',
            'nim.regex' => 'Nomor Induk Mahasiswa hanya boleh berisi angka.',
            'entryYear.required' => 'Tahun Masuk wajib diisi.',
            'entryYear.integer' => 'Tahun Masuk harus berupa angka.',
            'entryYear.digits' => 'Tahun Masuk harus 4 digit.',
            'entryYear.min' => 'Tahun Masuk minimal ' . (date('Y') - 8) . '.',
            'entryYear.max' => 'Tahun Masuk maksimal ' . date('Y') . '.',
            'class.required' => 'Kelas wajib diisi.',
            'class.in' => 'Kelas harus valid (reguler, rpl).',
            'gender.required' => 'Jenis Kelamin wajib diisi.',
            'gender.in' => 'Jenis Kelamin harus valid (Laki-Laki atau Perempuan).',
            'concentration.required' => 'Konsentrasi wajib diisi.',
            'concentration.in' => 'Konsentrasi harus valid (RPL, Multimedia, TKJ).',
            'phoneNumber.string' => 'Nomor Telepon harus berupa string.',
            'phoneNumber.max' => 'Nomor Telepon maksimal 20 karakter.',
            'phoneNumber.regex' => 'Nomor Telepon hanya boleh berisi angka dan diawali dengan 08.',
            'lecturerId2.required' => 'Dosen Pembimbing 2 wajib diisi.',
            'lecturerId2.exists' => 'Dosen Pembimbing 2 tidak ditemukan.',
            'lecturerId2.different' => 'Dosen Pembimbing 2 harus berbeda dengan Dosen Pembimbing 1.',
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
            'lecturerId1' => 'Dosen Pembimbing 1',
            'lecturerId2' => 'Dosen Pembimbing 2',
            'nim' => 'Nomor Induk Mahasiswa',
            'entryYear' => 'Tahun Masuk',
            'class' => 'Kelas',
            'gender' => 'Jenis Kelamin',
            'concentration' => 'Konsentrasi',
            'phoneNumber' => 'Nomor Telepon',
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
