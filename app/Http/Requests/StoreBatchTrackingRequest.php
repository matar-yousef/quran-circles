<?php

namespace App\Http\Requests;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;

class StoreBatchTrackingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->halaqas()->exists();
    }

    public function rules(): array
    {
        $userHalaqaIds = auth()->user()->halaqas()->pluck('id');

        return [
            'tracking_date' => ['required', 'date'],
            'tracking' => ['required', 'array'],
            'tracking.*.student_id' => [
                'required',
                'integer',
                'exists:students,id',
                function ($attribute, $value, $fail) use ($userHalaqaIds) {
                    $isValidStudent = Student::where('id', $value)
                        ->whereIn('halaqa_id', $userHalaqaIds)
                        ->exists();

                    if (! $isValidStudent) {
                        $fail('عذراً، أحد الطلاب المختارين لا ينتمي لحلقتك.');
                    }
                },
            ],
            'tracking.*.is_present' => ['required', 'integer', 'in:0,1,2'],
            'tracking.*.rating' => ['nullable', 'string'],
            'tracking.*.notes' => ['nullable', 'string'],
            'tracking.*.surahs' => ['nullable', 'array'],
            'tracking.*.surahs.*.type' => ['nullable', 'in:hifz,muraja'],
            'tracking.*.surahs.*.from_surah_id' => ['nullable', 'exists:surahs,id'],
            'tracking.*.surahs.*.to_surah_id' => ['nullable', 'exists:surahs,id'],
            'tracking.*.surahs.*.from_ayah' => ['nullable', 'integer', 'min:1'],
            'tracking.*.surahs.*.to_ayah' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
