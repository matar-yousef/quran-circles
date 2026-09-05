<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayah extends Model
{
    protected $table = 'ayahs';

    // العلاقة العكسية: كل آية تنتمي لسورة واحدة
    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_id', 'number');
    }
}
