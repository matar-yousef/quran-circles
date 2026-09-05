<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'exam_type',
        'parts_number',
        'grade',
        'exam_date',
        'notes',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
