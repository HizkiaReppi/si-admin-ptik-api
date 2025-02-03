<?php

namespace App\Http\Requests;

use App\Classes\ApiResponseClass;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLecturerProfilesRequest extends FormRequest
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
            '*.platform' => ['required', 'string', 'max:50', 'in:pddikti,google_scholar,sinta,scopus,researchgate,orcid,linkedin,other'],
            '*.profileUrl' => ['required', 'string', 'min:3', 'max:255', 'url'],
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
            '*.platform.required' => 'Platform wajib diisi.',
            '*.platform.string' => 'Platform harus berupa string.',
            '*.platform.max' => 'Platform maksimal 50 karakter.',
            '*.platform.in' => 'Platform harus valid (PDDIKTI, Google Scholar, Sinta, Scopus, Research Gate, Orcid, Linkedin, Lainnya).',
            '*.profileUrl.required' => 'Alamat Url Platform studi wajib diisi.',
            '*.profileUrl.string' => 'Alamat Url Platform studi harus berupa string.',
            '*.profileUrl.min' => 'Alamat Url Platform studi harus memiliki setidaknya 3 karakter.',
            '*.profileUrl.max' => 'Alamat Url Platform studi maksimal 255 karakter.',
            '*.profileUrl.url' => 'Alamat Url Platform studi harus valid URL.',
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
            '*.platform' => 'Platform',
            '*.profileUrl' => 'Alamat Url Platform',
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
