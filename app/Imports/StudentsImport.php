<?php

namespace App\Imports;

use App\Models\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentsImport implements ToModel, WithStartRow
{
    protected $halaqaId;

    public function __construct($halaqaId)
    {
        $this->halaqaId = $halaqaId;
    }

    public function startRow(): int
    {
        return 11; // البدء من الصف 11 حيث تبدأ البيانات الحقيقية
    }

    public function model(array $row)
    {
        // إذا كان اسم الطالب فارغاً، تخطي الصف
        if (empty($row[1])) {
            return null;
        }

        // معالجة تاريخ الميلاد (الموجود في العمود B / الفهرس 2)
        $birthDate = '2012-01-01';
        if (! empty($row[2])) {
            $val = $row[2];
            try {
                $birthDate = is_numeric($val)
                    ? Carbon::instance(Date::excelToDateTimeObject($val))->format('Y-m-d')
                    : Carbon::parse($val)->format('Y-m-d');
            } catch (\Exception $e) {
                $birthDate = '2012-01-01';
            }
        }

        return new Student([
            'full_name' => $row[1] ?? 'طالب جديد', // العمود الثاني: اسم الطالب
            'birth_date' => $birthDate,                 // العمود الثالث: تاريخ الميلاد
            'student_id_number' => $row[3] ?? '000000000', // العمود الرابع: هوية الطالب
            'guardian_phone' => $row[4] ?? '0000000000', // العمود الخامس: جوال ولي الأمر
            'address' => $row[5] ?? 'غير محدد', // العمود السادس: العنوان
            'father_id_number' => $row[6] ?? '000000000', // العمود السابع: هوية الأب
            'father_full_name' => $row[7] ?? 'غير متوفر', // العمود الثامن: اسم الأب
            'grade' => $row[8] ?? 'غير محدد', // العمود التاسع (أقصى اليسار): الصف
            'current_juz' => 1,
            'halaqa_id' => $this->halaqaId,
        ]);
    }
}
