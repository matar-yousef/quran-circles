<?php

namespace App\Services;

use App\Models\DailyTracking;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ParentTrackingService
{
    public function getStudentProgress(?string $fullName, ?string $studentId, string $period)
    {
        $fullName = $fullName ?? session('parent_student_name');
        $studentId = $studentId ?? session('parent_student_id');

        $student = Student::with('halaqa')
            ->where('full_name', 'like', '%' . $fullName . '%')
            ->where('student_id_number', $studentId)
            ->first();

        if (! $student) {
            return null;
        }

        session([
            'parent_student_name' => $student->full_name,
            'parent_student_id' => $student->student_id_number,
        ]);

        $allTrackings = DailyTracking::where('student_id', $student->id)->get();

        $todayProgress = $allTrackings->where('date', Carbon::today()->toDateString())->sum('hifz_pages');
        $weeklyProgress = $allTrackings->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('hifz_pages');
        $monthlyProgress = $allTrackings->where('date', '>=', Carbon::now()->startOfMonth())->sum('hifz_pages');

        $monthlyTrackings = $allTrackings->where('date', '>=', Carbon::now()->startOfMonth());
        $presentCount = $monthlyTrackings->where('is_present', 1)->count();
        $absentCount = $monthlyTrackings->where('is_present', 0)->count();
        $excusedCount = $monthlyTrackings->where('is_present', 2)->count();

        $query = DailyTracking::where('student_id', $student->id)->with('details');

        if ($period == 'today') {
            $query->whereDate('date', Carbon::today());
        } elseif ($period == 'week') {
            $query->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period == 'month') {
            $query->where('date', '>=', Carbon::now()->startOfMonth());
        }

        $trackings = $query->orderBy('date', 'desc')->get();

        return compact(
            'student',
            'todayProgress',
            'weeklyProgress',
            'monthlyProgress',
            'presentCount',
            'absentCount',
            'excusedCount',
            'trackings',
            'period'
        );
    }

    public function trackStudent(array $data)
    {
        $studentIdNumber = $data['student_id_number'] ?? $data['national_id'] ?? null;

        $student = Student::with(['dailyTrackings.details.surah', 'halaqa'])
            ->where('student_id_number', $studentIdNumber)
            ->first();

        if (! $student) {
            Log::warning('محاولة دخول ولي أمر فاشلة برقم هوية غير صحيح: ' . ($studentIdNumber ?? 'N/A'));

            return null;
        }

        Log::info('تمت مطابقة دخول ولي أمر بنجاح للطالب ID: ' . $student->id);

        return $student;
    }
}
