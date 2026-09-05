<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    protected $table = 'surahs';

    public function ayahs()
    {
        return $this->hasMany(Ayah::class, 'surah_id', 'number');
    }
}
