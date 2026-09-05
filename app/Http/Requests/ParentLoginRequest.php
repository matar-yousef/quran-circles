<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // مسموح للجميع بالوصول لصفحة تسجيل الدخول
    }

    public function rules(): array
    {
        return [
            'student_id_number' => ['required', 'string', 'exists:students,student_id_number'],
            'full_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id_number.required' => 'يرجى إدخال رقم هويّة الطالب.',
            'student_id_number.exists' => 'رقم الهوية المدخل غير مسجل لدينا.',
            'full_name.string' => 'اسم الطالب يجب أن يكون نصاً صالحاً.',
        ];
    }
}
