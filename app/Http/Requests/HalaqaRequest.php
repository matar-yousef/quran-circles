<?php

namespace App\Http\Requests;

use App\Models\Halaqa;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HalaqaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($this->isMethod('POST')) {
            return true;
        }

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $halaqaId = $this->route('id') ?? $this->route('halaqa');
            $halaqa = Halaqa::find($halaqaId);

            if (! $halaqa) {
                return false;
            }

            return $halaqa->users()->where('users.id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $halaqaId = $this->route('id') ?? $this->route('halaqa');

        return [
            'name' => [
                'required',
                $this->isMethod('POST')
                    ? 'unique:halaqa,name'
                    : 'unique:halaqa,name,' . $halaqaId,
            ],
            'meeting_time' => 'required|date_format:H:i',
            'min_hifz_pages' => 'nullable|integer|min:1',
            'min_muraja_pages' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الحلقة مطلوب',
            'name.unique' => 'اسم الحلقة موجود مسبقاً',
            'meeting_time.required' => 'وقت اللقاء مطلوب',
            'meeting_time.date_format' => 'صيغة وقت اللقاء غير صحيحة',
            'min_hifz_pages.integer' => 'الحد الأدنى للحفظ يجب أن يكون رقمًا',
            'min_hifz_pages.min' => 'الحد الأدنى للحفظ يجب أن يكون 1 على الأقل',
            'min_muraja_pages.integer' => 'الحد الأدنى للمراجعة يجب أن يكون رقمًا',
            'min_muraja_pages.min' => 'الحد الأدنى للمراجعة يجب أن يكون 1 على الأقل',
        ];
    }
}
