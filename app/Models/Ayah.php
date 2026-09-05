<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayah extends Model
{
    protected $table = 'ayahs';

    public function surah()
    {
        return $this->belongsTo(Surah::class, 'surah_id', 'number');
    }
}
