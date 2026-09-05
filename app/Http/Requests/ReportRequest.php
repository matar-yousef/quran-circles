<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        return $user->halaqas()->exists();
    }

    public function rules(): array
    {
        return [
            'type' => ['nullable', 'in:monthly,weekly'],
            'month' => ['nullable', 'integer', 'between:1,12'],
            'year' => ['nullable', 'integer', 'digits:4'],
            'week' => ['nullable', 'integer', 'between:1,4'],
        ];
    }
}
