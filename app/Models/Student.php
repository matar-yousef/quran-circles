<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'full_name',
        'grade',
        'address',
        'student_id_number',
        'birth_date',
        'father_full_name',
        'father_id_number',
        'guardian_phone',
        'current_juz',
        'halaqa_id',
    ];

    // علاقة الحلقة
    public function halaqa()
    {
        return $this->belongsTo(Halaqa::class);
    }

    // علاقة المتابعة اليومية (بصيغة الجمع لتناسب الاتفاقية الرسمية في Laravel)
    public function dailyTrackings()
    {
        return $this->hasMany(DailyTracking::class);
    }

    // للإبقاء على التوافق إذا كنت تستخدم الاسم القديم في مكان آخر
    public function daily_tracking()
    {
        return $this->dailyTrackings();
    }

    // علاقة خطة الطالب
    public function studentPlan()
    {
        return $this->hasOne(StudentPlan::class);
    }

    public function student_plan()
    {
        return $this->studentPlan();
    }

    // علاقة دورات الطالب
    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function student_course()
    {
        return $this->studentCourses();
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    // جلب تاريخ آخر يوم قام فيه الطالب بالتسميع الفعلي (حفظ أو مراجعة)
    public function lastActiveTrackingDate()
    {
        return $this->dailyTrackings()
            ->where('is_present', 1)
            ->where(function ($query) {
                $query->where('hifz_pages', '>', 0)
                    ->orWhere('muraja_pages', '>', 0)
                    ->orWhereHas('details'); // إذا كان يعتمد على جدول التفاصيل
            })
            ->latest('date')
            ->value('date');
    }

    public function trackings()
    {
        return $this->hasMany(DailyTracking::class, 'student_id');
    }
}
