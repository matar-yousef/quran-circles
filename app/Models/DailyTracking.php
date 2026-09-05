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

    public function details()
    {
        return $this->hasMany(DailyTrackingDetail::class, 'daily_tracking_id');
    }
    public function hifzDetails()
    {
        return $this->hasMany(DailyTrackingDetail::class, 'daily_tracking_id')->where('type', 'hifz');
    }
    public function murajaDetails()
    {
        return $this->hasMany(DailyTrackingDetail::class, 'daily_tracking_id')->where('type', 'muraja');
    }
}
