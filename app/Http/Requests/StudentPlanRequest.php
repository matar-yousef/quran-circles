<?php

namespace App\Http\Requests;

use App\Models\Student;
use App\Models\StudentPlan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentPlanRequest extends FormRequest
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

        $planId = $this->route('student_plan') ?? $this->route('id');
        if ($planId) {
            $plan = StudentPlan::with('student')->find($planId);
            if (! $plan || ! $plan->student || ! in_array($plan->student->halaqa_id, $userHalaqaIds->toArray())) {
                return false;
            }
        }

        if ($this->isMethod('POST') && $this->has('student_id')) {
            return Student::where('id', $this->input('student_id'))
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
        $planId = $this->route('student_plan') ?? $this->route('id');
        $userHalaqaIds = auth()->user()->halaqas()->pluck('id');

        return [
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('student_plans', 'student_id')->ignore($planId),
                function ($attribute, $value, $fail) use ($userHalaqaIds) {
                    $isValid = Student::where('id', $value)
                        ->whereIn('halaqa_id', $userHalaqaIds)
                        ->exists();

                    if (! $isValid) {
                        $fail('الطالب المختار غير تابع لحلقاتك.');
                    }
                },
            ],
            'plan_type' => ['required', 'string', 'in:حفظ,مراجعة,حفظ ومراجعة'],
            'duration' => ['required', 'integer', 'min:1'],
            'days_per_week' => ['required', 'integer', 'min:1', 'max:7'],
            'daily_hifz' => ['nullable', 'numeric', 'min:0', 'max:604', 'required_if:plan_type,حفظ,حفظ ومراجعة'],
            'daily_muraja' => ['nullable', 'numeric', 'min:0', 'max:604', 'required_if:plan_type,مراجعة,حفظ ومراجعة'],
            'start_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'يرجى اختيار الطالب المستهدف بالخطة.',
            'student_id.exists' => 'الطالب المحدد غير موجود في النظام.',
            'plan_type.required' => 'نوع ومسار الخطة القرآنية مطلوب.',
            'duration.required' => 'يرجى تحديد مدة الخطة بالأشهر.',
            'days_per_week.required' => 'حدد عدد أيام الالتزام في الأسبوع.',
            'days_per_week.max' => 'لا يمكن أن يتجاوز عدد الأيام 7 أيام.',
            'daily_hifz.required_if' => 'يرجى تحديد مقدار الحفظ اليومي المطلوب (عدد الصفحات).',
            'daily_muraja.required_if' => 'يرجى تحديد مقدار المراجعة اليومي المطلوب (عدد الصفحات).',
        ];
    }
}
