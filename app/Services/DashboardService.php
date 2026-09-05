<?php

namespace App\Services;

use App\Models\DailyTracking;
use App\Models\Student;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData($userHalaqa, string $filter): array
    {
        $halaqaId = $userHalaqa->id;

        switch ($filter) {
            case 'week':
                $startDate = Carbon::now()->startOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                $endDate = Carbon::now()->endOfWeek(Carbon::FRIDAY)->format('Y-m-d');
                break;
            case 'month':
                $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;
            case 'day':
            default:
                $startDate = Carbon::today()->format('Y-m-d');
                $endDate = Carbon::today()->format('Y-m-d');
                break;
        }

        $totalStudents = Student::where('halaqa_id', $halaqaId)->count();

        $query = DailyTracking::whereBetween('date', [$startDate, $endDate])
            ->whereHas('student', function ($q) use ($halaqaId) {
                $q->where('halaqa_id', $halaqaId);
            });

        $totalRecords = (clone $query)->count();

        if ($totalRecords > 0) {
            $presentCount = (clone $query)->where('is_present', 1)->count();
            $absentCount = (clone $query)->whereIn('is_present', [0, 2])->count();

            $attendancePercentage = round(($presentCount / $totalRecords) * 100, 1);
            $absencePercentage = round(($absentCount / $totalRecords) * 100, 1);
        } else {
            $attendancePercentage = 0;
            $absencePercentage = 0;
        }

        $presentQuery = (clone $query)->where('is_present', 1);

        $totalHifzPages = round($presentQuery->sum('hifz_pages'), 2);
        $totalReviewPages = round($presentQuery->sum('muraja_pages'), 2);

        $daysCount = (clone $query)->distinct('date')->count('date');
        $daysCount = $daysCount > 0 ? $daysCount : 1;

        $minHifzDaily = $userHalaqa->min_hifz_pages ?? 0;
        $minMurajaDaily = $userHalaqa->min_muraja_pages ?? 0;

        $targetHifzTotal = $minHifzDaily * $daysCount;
        $targetMurajaTotal = $minMurajaDaily * $daysCount;

        $achievedCount = 0;
        $notAchievedCount = 0;

        $allStudents = Student::where('halaqa_id', $halaqaId)
            ->with(['daily_tracking' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                    ->where('is_present', 1);
            }])->get();

        foreach ($allStudents as $student) {
            $studentHifz = $student->daily_tracking->sum('hifz_pages');
            $studentMuraja = $student->daily_tracking->sum('muraja_pages');

            $isAchieved = false;

            if ($minHifzDaily > 0 && $minMurajaDaily > 0) {
                $isAchieved = ($studentHifz >= $targetHifzTotal || $studentMuraja >= $targetMurajaTotal);
            } elseif ($minHifzDaily > 0) {
                $isAchieved = ($studentHifz >= $targetHifzTotal);
            } elseif ($minMurajaDaily > 0) {
                $isAchieved = ($studentMuraja >= $targetMurajaTotal);
            }

            if ($isAchieved) {
                $achievedCount++;
            } else {
                $notAchievedCount++;
            }
        }

        $mostFrequentRating = (clone $query)
            ->where('is_present', 1)
            ->whereNotNull('rating')
            ->select('rating')
            ->groupBy('rating')
            ->orderByRaw('COUNT(*) DESC')
            ->first()?->rating ?? 'لا يوجد تقييمات';

        $today = Carbon::today()->format('Y-m-d');
        $todayStudents = Student::where('halaqa_id', $halaqaId)
            ->with(['daily_tracking' => function ($q) use ($today) {
                $q->whereDate('date', $today)
                    ->with(['details.surah', 'details.toSurah']);
            }])->get();

        $frequentAbsentees = Student::where('halaqa_id', $halaqaId)
            ->withCount(['daily_tracking as absence_count' => function ($q) use ($startDate, $endDate) {
                $q->whereIn('is_present', [0, 2])
                    ->whereBetween('date', [$startDate, $endDate]);
            }])
            ->having('absence_count', '>', 0)
            ->orderBy('absence_count', 'desc')
            ->take(5)
            ->get();

        $inactiveStudents = Student::where('halaqa_id', $halaqaId)
            ->whereDoesntHave('daily_tracking', function ($q) {
                $q->where('is_present', 1)
                    ->where('date', '>=', Carbon::now()->subDays(14)->format('Y-m-d'));
            })->get();

        $weekDays = ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        $dailyPagesData = [];

        $weekStart = Carbon::now()->startOfWeek(Carbon::SATURDAY);
        for ($i = 0; $i < 7; $i++) {
            $currentDay = (clone $weekStart)->addDays($i)->format('Y-m-d');

            $dayPages = DailyTracking::whereDate('date', $currentDay)
                ->where('is_present', 1)
                ->whereHas('student', function ($q) use ($halaqaId) {
                    $q->where('halaqa_id', $halaqaId);
                })
                ->sum('hifz_pages');

            $dailyPagesData[] = round($dayPages, 2);
        }

        $studentLatestRatings = (clone $query)
            ->where('is_present', 1)
            ->whereNotNull('rating')
            ->orderBy('date', 'desc')
            ->get()
            ->unique('student_id');

        $ratingsGroup = $studentLatestRatings->groupBy('rating')->map->count()->toArray();

        return [
            'filter' => $filter,
            'totalStudents' => $totalStudents,
            'attendancePercentage' => $attendancePercentage,
            'absencePercentage' => $absencePercentage,
            'achievedCount' => $achievedCount,
            'notAchievedCount' => $notAchievedCount,
            'totalHifzPages' => $totalHifzPages,
            'totalReviewPages' => $totalReviewPages,
            'mostFrequentRating' => $mostFrequentRating,
            'todayStudents' => $todayStudents,
            'frequentAbsentees' => $frequentAbsentees,
            'inactiveStudents' => $inactiveStudents,
            'weekDays' => $weekDays,
            'dailyPagesData' => $dailyPagesData,
            'ratingLabels' => array_keys($ratingsGroup),
            'ratingValues' => array_values($ratingsGroup),
        ];
    }
}
