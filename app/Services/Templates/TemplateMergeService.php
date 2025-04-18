<?php

namespace App\Services\Templates;

use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Storage;

class TemplateMergeService
{
    public function generateSuratSeminarProposal(array $data): string
    {
        $templatePath = storage_path('app/templates/permohonan-sk-seminar-proposal.docx');
        $outputName = 'permohonan_sk_seminar_proposal_' . now()->timestamp . '.docx';
        $outputPath = storage_path("app/public/documents/{$outputName}");

        $template = new TemplateProcessor($templatePath);

        // Replace placeholders
        $template->setValue('DOCUMENT_NUMBER', $data['documentNumber']);
        $template->setValue('STUDENT_NAME', $data['studentName']);
        $template->setValue('STUDENT_NIM', $data['studentNim']);
        $template->setValue('STUDENT_SEMESTER', $data['studentSemester']);
        $template->setValue('THESIS_TITLE', $data['thesisTitle']);
        $template->setValue('STUDENT_SUPERVISOR', $data['studentSupervisor']);
        $template->setValue('EXAMINER_1', $data['examiner1']);
        $template->setValue('EXAMINER_2', $data['examiner2']);
        $template->setValue('EXAMINER_3', $data['examiner3']);
        $template->setValue('DOCUMENT_DATE', $data['documentDate']);
        $template->setValue('HEAD_OF_DEPARTMENT_NAME', $data['headOfDepartmentName']);
        $template->setValue('HEAD_OF_DEPARTMENT_NIP', $data['headOfDepartmentNip']);

        $template->saveAs($outputPath);

        return "documents/{$outputName}";
    }

    public function generateSuratSeminarHasil(array $data): string
    {
        $templatePath = storage_path('app/templates/sk-ujian-hasil-penelitian.docx');
        $outputName = 'permohonan_sk_ujian_hasil_penelitian_' . now()->timestamp . '.docx';
        $outputPath = storage_path("app/public/documents/{$outputName}");

        $template = new TemplateProcessor($templatePath);

        // Replace placeholders
        $template->setValue('DOCUMENT_NUMBER', $data['documentNumber']);
        $template->setValue('STUDENT_NAME', $data['studentName']);
        $template->setValue('STUDENT_NIM', $data['studentNim']);
        $template->setValue('STUDENT_SEMESTER', $data['studentSemester']);
        $template->setValue('THESIS_TITLE', $data['thesisTitle']);
        $template->setValue('STUDENT_SUPERVISOR_1', $data['studentSupervisor1']);
        $template->setValue('STUDENT_SUPERVISOR_2', $data['studentSupervisor2']);
        $template->setValue('EXAMINER_1', $data['examiner1']);
        $template->setValue('EXAMINER_2', $data['examiner2']);
        $template->setValue('EXAMINER_3', $data['examiner3']);
        $template->setValue('DOCUMENT_DATE', $data['documentDate']);
        $template->setValue('HEAD_OF_DEPARTMENT_NAME', $data['headOfDepartmentName']);
        $template->setValue('HEAD_OF_DEPARTMENT_NIP', $data['headOfDepartmentNip']);

        $template->saveAs($outputPath);

        return "documents/{$outputName}";
    }

    public function generateSuratUjianKomprehensif(array $data): string
    {
        $templatePath = storage_path('app/templates/permohonan-ujian-komprehensif.docx');
        $outputName = 'permohonan_sk_ujian_komprehensif_' . now()->timestamp . '.docx';
        $outputPath = storage_path("app/public/documents/{$outputName}");

        $template = new TemplateProcessor($templatePath);

        // Replace placeholders
        $template->setValue('DOCUMENT_NUMBER', $data['documentNumber']);
        $template->setValue('STUDENT_NAME', $data['studentName']);
        $template->setValue('STUDENT_PLACE_DATE_OF_BIRTH', $data['studentPlaceDateOfBirth']);
        $template->setValue('STUDENT_NIM', $data['studentNim']);
        $template->setValue('STUDENT_CLASS', $data['studentClass']);
        $template->setValue('STUDENT_ENTRY_YEAR', $data['studentEntryYear']);
        $template->setValue('THESIS_TITLE', $data['thesisTitle']);
        $template->setValue('STUDENT_SUPERVISOR_1', $data['studentSupervisor1']);
        $template->setValue('STUDENT_SUPERVISOR_2', $data['studentSupervisor2']);
        $template->setValue('EXAMINER_1', $data['examiner1']);
        $template->setValue('EXAMINER_2', $data['examiner2']);
        $template->setValue('EXAMINER_3', $data['examiner3']);
        $template->setValue('EXAMINER_4', $data['examiner4']);
        $template->setValue('EXAMINER_5', $data['examiner5']);
        $template->setValue('DOCUMENT_DATE', $data['documentDate']);
        $template->setValue('HEAD_OF_DEPARTMENT_NAME', $data['headOfDepartmentName']);
        $template->setValue('HEAD_OF_DEPARTMENT_NIP', $data['headOfDepartmentNip']);

        $template->saveAs($outputPath);

        return "documents/{$outputName}";
    }
}
