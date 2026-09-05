<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyTrackingDetail extends Model
{
    use HasFactory;

    protected $table = 'daily_tracking_details';

    protected $fillable = [
        'daily_tracking_id',
        'type',
        'surah_id',
        'from_ayah',
        'to_surah_id', // أضفنا الحقل هنا
        'to_ayah',
    ];

    // علاقة سورة البداية
    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }

    // علاقة سورة النهاية
    public function toSurah()
    {
        return $this->belongsTo(Surah::class, 'to_surah_id');
    }

    public function dailyTracking()
    {
        return $this->belongsTo(DailyTracking::class, 'daily_tracking_id');
    }
}
