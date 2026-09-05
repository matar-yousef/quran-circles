<?php

namespace App\Services;

use App\Models\DailyTracking;
use App\Models\Halaqa;

class HalaqaService
{
    public function getHalaqaDetailsData(int $halaqaId): array
    {
        $halaqa = Halaqa::with([
            'students.dailyTrackings.details.surah',
            'students.dailyTrackings.details.toSurah',
            'users',
        ])->findOrFail($halaqaId);

        $totalStudents = $halaqa->students->count();

        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');

        $monthlyPages = DailyTracking::whereHas('student', function ($q) use ($halaqaId) {
            $q->where('halaqa_id', $halaqaId);
        })
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('is_present', 1)
            ->sum('hifz_pages');

        foreach ($halaqa->students as $student) {
            $totalRecords = $student->dailyTrackings->count();

            $student->attendance_percentage = $totalRecords > 0
                ? round(($student->dailyTrackings->where('is_present', 1)->count() / $totalRecords) * 100, 1)
                : 0;
        }

        $activeStudents = $halaqa->students->filter(function ($student) {
            return $student->dailyTrackings
                ->where('date', '>=', now()->subDays(30)->format('Y-m-d'))
                ->filter(function ($tracking) {
                    return $tracking->is_present == 1 && (
                        $tracking->hifz_pages > 0 ||
                        $tracking->muraja_pages > 0
                    );
                })->count() > 0;
        })->count();

        return compact('halaqa', 'totalStudents', 'monthlyPages', 'activeStudents');
    }
}
