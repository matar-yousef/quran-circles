<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class StudentService
{
    public function getStudentDetails(int $id): Student
    {
        $student = Student::with([
            'halaqa',
            'studentPlan',
            'dailyTrackings' => function ($q) {
                $q->with('details.surah')->orderByDesc('date');
            },
        ])->findOrFail($id);

        Gate::authorize('view', $student);

        return $student;
    }

    public function calculateStudentStatistics(Student $student): array
    {
        $trackings = $student->dailyTrackings;

        $totalRecords = $trackings->count();
        $presentCount = $trackings->where('is_present', 1)->count();
        $attendancePercentage = $totalRecords > 0 ? round(($presentCount / $totalRecords) * 100, 1) : 0;

        $totalHifzPages = $trackings->where('is_present', 1)->sum('hifz_pages');
        $totalMurajaPages = $trackings->where('is_present', 1)->sum('muraja_pages');

        $ratingCounts = $trackings->where('is_present', 1)
            ->whereNotNull('rating')
            ->groupBy('rating')
            ->map->count();

        $mostFrequentRating = $ratingCounts->sortDesc()->keys()->first() ?? 'لا يوجد تقييمات';
        $recentTrackings = $trackings->take(30);

        return compact(
            'attendancePercentage',
            'totalHifzPages',
            'totalMurajaPages',
            'mostFrequentRating',
            'recentTrackings'
        );
    }

    public function getIdealStudent(User $user)
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $halaqaIds = $user->halaqas()->pluck('halaqa.id');
        return Student::whereIn('halaqa_id', $halaqaIds)
            ->with(['halaqa', 'trackings' => function ($query) use ($startOfMonth) {
                $query->where('date', '>=', $startOfMonth);
            }])
            ->get()
            ->map(function ($student) {
                $totalHifz = $student->trackings->sum('hifz_pages');
                $totalReview = $student->trackings->sum('muraja_pages');
                $presentDays = $student->trackings->where('is_present', 1)->count();

                $ratingScore = $student->trackings->sum(function ($tracking) {
                    if ($tracking->rating == 'ممتاز') {
                        return 5;
                    }
                    if (in_array($tracking->rating, ['جيد جداً', 'جيد جدا'])) {
                        return 4;
                    }
                    if ($tracking->rating == 'جيد') {
                        return 3;
                    }

                    return 1;
                });

                $student->total_hifz = $totalHifz;
                $student->total_review = $totalReview;
                $student->present_days = $presentDays;
                $student->score = ($totalHifz * 5) + ($totalReview * 2) + ($presentDays * 3) + ($ratingScore * 2);

                return $student;
            })
            ->sortByDesc('score')
            ->first();
    }
}
