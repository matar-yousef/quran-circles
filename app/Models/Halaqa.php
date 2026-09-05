<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Halaqa extends Model
{
    protected $table = 'halaqa';

    protected $fillable = [
        'name',
        'meeting_time',
        'min_hifz_pages',
        'min_muraja_pages',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'halaqa_user', 'halaqa_id', 'user_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->whereHas('users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        });
    }
}
