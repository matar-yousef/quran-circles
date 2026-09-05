<?php

namespace App\Http\Requests;

use App\Models\Exam;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userHalaqa = Auth::user()->halaqas()->with('students')->first();
        $allowedStudentIds = $userHalaqa ? $userHalaqa->students->pluck('id')->toArray() : [];

        if ($this->isMethod('POST')) {
            $studentId = $this->input('student_id');

            return in_array($studentId, $allowedStudentIds);
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $examId = $this->route('id') ?? $this->route('exam');
            $exam = Exam::find($examId);

            if (! $exam) {
                return false;
            }

            $targetStudentId = $this->input('student_id', $exam->student_id);

            return in_array($exam->student_id, $allowedStudentIds) && in_array($targetStudentId, $allowedStudentIds);
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer'],
            'exam_type' => ['required', 'in:single,collective'],
            'parts_number' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'exam_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'يرجى اختيار الطالب.',
            'exam_type.required' => 'يرجى تحديد نوع الاختبار.',
            'exam_type.in' => 'نوع الاختبار غير صالح.',
            'parts_number.required' => 'يرجى إدخال أجزاء الاختبار.',
            'grade.required' => 'يرجى إدخال الدرجة.',
            'grade.numeric' => 'الدرجة يجب أن تكون رقماً.',
            'grade.min' => 'الدرجة لا يمكن أن تكون أقل من 0.',
            'grade.max' => 'الدرجة لا يمكن أن تتجاوز 100.',
            'exam_date.required' => 'يرجى تحديد تاريخ الاختبار.',
            'exam_date.date' => 'صيغة التاريخ غير صحيحة.',
        ];
    }
}
