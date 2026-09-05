<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    protected $table = 'surahs';

    // العلاقة: كل سورة تحتوي على عدة آيات
    public function ayahs()
    {
        return $this->hasMany(Ayah::class, 'surah_id', 'number'); // أو حسب مفتاح الربط في جدول الآيات
    }
}
