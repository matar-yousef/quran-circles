<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyTracking extends Model
{
    protected $table = 'daily_tracking';

    protected $fillable = [
        'student_id',
        'date',
        'rating',
        'is_present',
        'hifz_pages',
        'muraja_pages',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // جلب كافة تفاصيل السور المرتبطة بهذا السجل (حفظ ومراجعة متعددة)
    public function details()
    {
        return $this->hasMany(DailyTrackingDetail::class, 'daily_tracking_id');
    }

    // جلب تفاصيل الحفظ المتعددة فقط
    public function hifzDetails()
    {
        return $this->hasMany(DailyTrackingDetail::class, 'daily_tracking_id')->where('type', 'hifz');
    }

    // جلب تفاصيل المراجعة المتعددة فقط
    public function murajaDetails()
    {
        return $this->hasMany(DailyTrackingDetail::class, 'daily_tracking_id')->where('type', 'muraja');
    }
}
