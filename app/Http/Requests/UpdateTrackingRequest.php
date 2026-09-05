<?php

namespace App\Http\Requests;

use App\Models\DailyTracking;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTrackingRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        if (!$user) {
            return false;
        }

        // التقاط سجل المتابعة اليومية من الـ Route Parameter الصحيح
        $tracking = $this->route('daily_tracking') ?? $this->route('tracking') ?? $this->route('id');

        if (!$tracking instanceof DailyTracking) {
            $tracking = DailyTracking::find($tracking);
        }

        if (!$tracking) {
            return false;
        }

        // التحقق من الصلاحية باستخدام الـ Policy أو ربط الحلقات
        $userHalaqaIds = $user->halaqas()->pluck('id');

        return $tracking->student && $userHalaqaIds->contains($tracking->student->halaqa_id);
    }

    public function rules(): array
    {
        return [
            'date'              => ['required', 'date'],
            'is_present'        => ['required', 'integer', 'in:0,1,2'],
            'rating'            => ['nullable', 'string'],
            'notes'             => ['nullable', 'string'],
            'surahs'            => ['nullable', 'array'],
            'surahs.*.type'     => ['nullable', 'in:hifz,muraja'],
            'surahs.*.from_surah_id' => ['nullable', 'exists:surahs,id'],
            'surahs.*.to_surah_id'   => ['nullable', 'exists:surahs,id'],
            'surahs.*.from_ayah'     => ['nullable', 'integer', 'min:1'],
            'surahs.*.to_ayah'       => ['nullable', 'integer', 'min:1'],
        ];
    }
}
