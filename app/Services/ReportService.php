<?php

namespace App\Services;

use App\Models\DailyTracking;
use App\Models\Exam;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function generateReportData($user, array $filters)
    {
        $type = $filters['type'] ?? 'monthly';
        $month = $filters['month'] ?? date('m');
        $year = $filters['year'] ?? date('Y');
        $week = $filters['week'] ?? 1;

        $studentIds = $user->halaqas()
            ->with('students')
            ->get()
            ->pluck('students')
            ->flatten()
            ->pluck('id');

        $students = Student::whereIn('id', $studentIds)->get();

        if ($type == 'monthly') {
            $startDate = Carbon::create($year, $month, 1)->startOfMonth();
            $endDate = (clone $startDate)->endOfMonth();
        } else {
            $startDay = ($week - 1) * 7 + 1;
            $monthStart = Carbon::create($year, $month, 1);
            $startDate = (clone $monthStart)->addDays($startDay - 1)->startOfDay();

            if ($week == 4) {
                $endDate = (clone $monthStart)->endOfMonth();
            } else {
                $endDate = (clone $startDate)->addDays(6)->endOfDay();
            }
        }

        if ($endDate->isFuture()) {
            return [
                'reportData' => [],
                'type' => $type,
                'month' => $month,
                'year' => $year,
                'week' => $week,
                'errorMessage' => ($type == 'monthly')
                    ? 'عذراً، لم تتم شهر بعد، لا يمكن إصدار التقرير الشهري حتى اكتمال الشهر.'
                    : 'عذراً، لم تتم أسبوع بعد، لا يمكن إصدار التقرير الأسبوعي حتى اكتمال أيام الأسبوع.',
            ];
        }

        $hasData = DB::table('daily_tracking')
            ->whereIn('student_id', $studentIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->exists();

        $hasExams = Exam::whereIn('student_id', $studentIds)
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->exists();

        if (! $hasData && ! $hasExams) {
            return [
                'reportData' => [],
                'type' => $type,
                'month' => $month,
                'year' => $year,
                'week' => $week,
                'errorMessage' => 'عذراً، لا توجد أي بيانات أو حركات مسجلة لهذه الفترة.',
            ];
        }

        $allTrackings = DailyTracking::whereIn('student_id', $studentIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->with('hifzDetails.surah', 'hifzDetails.toSurah')
            ->orderBy('date', 'asc')
            ->get()
            ->groupBy('student_id');

        $allExams = Exam::whereIn('student_id', $studentIds)
            ->whereBetween('exam_date', [$startDate, $endDate])
            ->get()
            ->groupBy('student_id');

        $reportData = [];

        foreach ($students as $student) {
            $trackings = $allTrackings->get($student->id, collect());
            $exams = $allExams->get($student->id, collect());

            $totalHifzPages = $trackings->sum('hifz_pages');
            $totalMurajaPages = $trackings->sum('muraja_pages');
            $attendanceDays = $trackings->where('is_present', 1)->count();

            $allHifzDetails = $trackings->flatMap(fn ($t) => $t->hifzDetails);
            $firstHifzDetail = $allHifzDetails->first();
            $lastHifzDetail = $allHifzDetails->last();

            $startHifzText = '-';
            if ($firstHifzDetail) {
                $surahName = $firstHifzDetail->surah ? $firstHifzDetail->surah->name : '-';
                $startHifzText = "سورة {$surahName} - آية {$firstHifzDetail->from_ayah}";
            }

            $endHifzText = '-';
            if ($lastHifzDetail) {
                $surahName = $lastHifzDetail->toSurah ? $lastHifzDetail->toSurah->name : ($lastHifzDetail->surah ? $lastHifzDetail->surah->name : '-');
                $endHifzText = "سورة {$surahName} - آية {$lastHifzDetail->to_ayah}";
            }

            $reportData[] = [
                'student' => $student,
                'current_juz' => $student->current_juz,
                'start_hifz' => $startHifzText,
                'end_hifz' => $endHifzText,
                'total_hifz_pages' => $totalHifzPages,
                'total_muraja_pages' => $totalMurajaPages,
                'attendance_days' => $attendanceDays,
                'single_exams' => $exams->where('exam_type', 'single'),
                'collective_exams' => $exams->where('exam_type', 'collective'),
                'year' => $year,
            ];
        }

        return compact('reportData', 'type', 'month', 'year', 'week');
    }
}
