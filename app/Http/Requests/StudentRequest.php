<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        $userHalaqaIds = $user->halaqas()->pluck('id');

        if ($userHalaqaIds->isEmpty()) {
            return false;
        }

        $studentId = $this->route('student') ?? $this->route('id');
        if ($studentId) {
            return Student::where('id', $studentId)
                ->whereIn('halaqa_id', $userHalaqaIds)
                ->exists();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $studentId = $this->route('student') ?? $this->route('id');

        $validateQuadrupleName = function ($attribute, $value, $fail) {
            if (empty($value)) {
                return;
            }

            $cleanName = trim(preg_replace('/\s+/', ' ', $value));
            $normalizedName = preg_replace('/عبد\s+ال/u', 'عبدال', $cleanName);
            $words = explode(' ', $normalizedName);

            if (count($words) !== 4) {
                $fail($attribute === 'full_name' ? 'اسم الطالب يجب أن يكون رباعياً.' : 'اسم الأب يجب أن يكون رباعياً.');
            }
        };

        return [
            'full_name' => ['required', 'string', $validateQuadrupleName],
            'grade' => ['required', 'string'],
            'address' => ['required', 'min:5'],
            'student_id_number' => [
                'required',
                'min:9',
                $this->isMethod('POST')
                    ? 'unique:students,student_id_number'
                    : 'unique:students,student_id_number,'.$studentId,
            ],
            'birth_date' => ['required', 'date', 'before:'.now()->subYears(5)->format('Y-m-d')],
            'father_full_name' => ['required', 'string', $validateQuadrupleName],
            'father_id_number' => ['required', 'min:9'],
            'guardian_phone' => ['required', 'min:10'],
            'current_juz' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'اسم الطالب مطلوب',
            'grade.required' => 'الصف الدراسي مطلوب',
            'address.required' => 'عنوان السكن مطلوب',
            'address.min' => 'عنوان السكن قصير جداً',
            'student_id_number.required' => 'رقم هوية الطالب مطلوب',
            'student_id_number.min' => 'رقم هوية الطالب يجب أن يكون 9 أرقام على الأقل',
            'student_id_number.unique' => 'رقم هوية الطالب موجود مسبقاً',
            'birth_date.required' => 'تاريخ الميلاد مطلوب',
            'birth_date.before' => 'عمر الطالب يجب أن يكون 5 سنوات على الأقل',
            'father_full_name.required' => 'اسم الأب مطلوب',
            'father_id_number.required' => 'رقم هوية الأب مطلوب',
            'father_id_number.min' => 'رقم هوية الأب يجب أن يكون 9 أرقام على الأقل',
            'guardian_phone.required' => 'رقم جوال ولي الأمر مطلوب',
            'guardian_phone.min' => 'رقم الجوال يجب أن يكون 10 أرقام على الأقل',
            'current_juz.required' => 'الجزء الحالي مطلوب',
        ];
    }
}
