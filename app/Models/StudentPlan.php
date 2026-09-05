<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StudentPlan extends Model
{
    protected $fillable = [
        'student_id',
        'plan_type',
        'duration',
        'days_per_week',
        'daily_hifz',
        'daily_muraja',
        'total_days',
        'pages_per_month',
        'start_date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getTotalTargetDaysAttribute()
    {
        return $this->duration * $this->days_per_week * 4;
    }

    public function getTotalAttendedAttribute()
    {
        return DailyTracking::where('student_id', $this->student_id)
            ->where('is_present', 1)
            ->where('date', '>=', $this->start_date)
            ->where('date', '<=', $this->end_date)
            ->count();
    }

    public function getEndDateAttribute()
    {
        return Carbon::parse($this->start_date)->addMonths($this->duration);
    }

    public function getIsOverdueAttribute()
    {
        return now()->greaterThan($this->end_date);
    }

    public function getTotalTargetPagesAttribute()
    {
        $days = $this->total_target_days ?? 0;

        if ($this->plan_type === 'حفظ') {
            return ($this->daily_hifz ?? 0) * $days;
        } elseif ($this->plan_type === 'مراجعة') {
            return ($this->daily_muraja ?? 0) * $days;
        } elseif ($this->plan_type === 'حفظ ومراجعة') {
            return (($this->daily_hifz ?? 0) + ($this->daily_muraja ?? 0)) * $days;
        }

        return 0;
    }


    public function getTotalAchievedPagesAttribute()
    {
        $startDate = $this->start_date;
        $endDate = $this->end_date;

        $effectiveEndDate = $endDate->gt(Carbon::today()) ? Carbon::today() : $endDate;

        $query = DailyTracking::where('student_id', $this->student_id)
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $effectiveEndDate);

        if ($this->plan_type === 'حفظ') {
            return $query->sum('hifz_pages');
        } elseif ($this->plan_type === 'مراجعة') {
            return $query->sum('muraja_pages');
        } elseif ($this->plan_type === 'حفظ ومراجعة') {
            $totals = $query->selectRaw('sum(hifz_pages) as total_hifz, sum(muraja_pages) as total_muraja')->first();

            return ($totals->total_hifz ?? 0) + ($totals->total_muraja ?? 0);
        }

        return 0;
    }

    public function getProgressPercentageAttribute()
    {
        $target = $this->total_target_pages;
        if ($target <= 0) {
            return 0;
        }

        $achieved = $this->total_achieved_pages;
        $percentage = ($achieved / $target) * 100;

        return min(100, round($percentage));
    }
}
