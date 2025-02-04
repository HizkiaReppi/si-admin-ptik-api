<?php

namespace App\Http\Requests\Lecturers;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLecturerExperiencesRequest extends FormRequest
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
            '*.position' => ['required_with:*.organization', 'string', 'min:3', 'max:100'],
            '*.organization' => ['required_with:*.position', 'string', 'min:2', 'max:255'],
            '*.description' => ['nullable', 'string'],
            '*.start_date' => ['nullable', 'date', 'before_or_equal:*.end_date'],
            '*.end_date' => ['nullable', 'date', 'after_or_equal:*.start_date'],
            '*.is_current' => ['boolean'],
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
            '*.position.required_with' => 'Posisi/Jabatan wajib diisi.',
            '*.position.string' => 'Posisi/Jabatan harus berupa string.',
            '*.position.min' => 'Posisi/Jabatan harus memiliki setidaknya 3 karakter.',
            '*.position.max' => 'Posisi/Jabatan maksimal 100 karakter.',
            '*.organization.required_with' => 'Organisasi wajib diisi.',
            '*.organization.string' => 'Organisasi harus berupa string.',
            '*.organization.min' => 'Organisasi harus memiliki setidaknya 2 karakter.',
            '*.organization.max' => 'Organisasi maksimal 255 karakter.',
            '*.description.string' => 'Deskripsi harus berupa string.',
            '*.start_date.date' => 'Tanggal mulai harus berupa tanggal.',
            '*.start_date.before_or_equal' => 'Tanggal mulai harus sebelum atau sama dengan Tanggal selesai.',
            '*.end_date.date' => 'Tanggal selesai harus berupa tanggal.',
            '*.end_date.after_or_equal' => 'Tanggal selesai harus setelah atau sama dengan Tanggal mulai.',
            '*.is_current.boolean' => 'Is Current harus berupa benar atau salah.',
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
            '*.position' => 'Posisi/Jabatan',
            '*.organization' => 'Organisasi',
            '*.description' => 'Deskripsi',
            '*.start_date' => 'Tanggal Mulai',
            '*.end_date' => 'Tanggal Selesai',
            '*.is_current' => 'Masih Aktif',
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
