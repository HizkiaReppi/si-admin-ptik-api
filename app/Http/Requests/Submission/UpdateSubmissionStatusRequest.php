<?php

namespace App\Http\Requests\Submission;

use App\Classes\ApiResponseClass;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateSubmissionStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $categorySlug = $this->route('categorySlug');

        $rules = [
            'status' => ['required', Rule::in(['in_review', 'faculty_review', 'completed', 'rejected'])],
            'reason' => ['required_if:status,rejected', 'string', 'max:1000'],
        ];

        if (in_array($categorySlug, ['sk-seminar-proposal', 'sk-ujian-hasil-penelitian'])) {
            $rules['examiners'][] = ['required_if:status,faculty_review', 'array', 'size:3'];
            $rules['examiners'][] = ['uuid', 'exists:lecturers,id'];
        }

        if ($categorySlug === 'permohonan-ujian-komprehensif') {
            $rules['examiners'][] = ['required_if:status,faculty_review', 'array', 'size:5'];
            $rules['examiners'][] = ['uuid', 'exists:lecturers,id'];
        }

        if ($categorySlug === 'permohonan-sk-pembimbing-skripsi') {
            $rules['supervisors'][] = ['required_if:status,faculty_review', 'array', 'size:2'];
            $rules['supervisors.*'][] = ['uuid', 'exists:lecturers,id'];
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Status pengajuan wajib diisi.',
            'status.in' => 'Status tidak valid.',
            'reason.required_if' => 'Alasan penolakan wajib diisi jika status ditolak.',
            'examiners.required_if' => 'Penguji wajib dipilih untuk status faculty_review.',
            'examiners.size' => 'Jumlah penguji harus :size orang.',
            'examiners.*.exists' => 'Penguji tidak valid.',
            'supervisors.required_if' => 'Pembimbing wajib dipilih untuk kategori permohonan-sk-pembimbing-skripsi.',
            'supervisors.size' => 'Jumlah pembimbing harus 2 orang.',
            'supervisors.*.exists' => 'Pembimbing tidak valid.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ApiResponseClass::sendError(422, 'Validation errors', $validator->errors()));
    }
}
