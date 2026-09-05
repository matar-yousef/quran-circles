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
        return 11;
    }

    public function model(array $row)
    {
        if (empty($row[1])) {
            return null;
        }
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
            'full_name' => $row[1] ?? 'طالب جديد',
            'birth_date' => $birthDate,
            'student_id_number' => $row[3] ?? '000000000',
            'guardian_phone' => $row[4] ?? '0000000000',
            'address' => $row[5] ?? 'غير محدد',
            'father_id_number' => $row[6] ?? '000000000',
            'father_full_name' => $row[7] ?? 'غير متوفر',
            'grade' => $row[8] ?? 'غير محدد',
            'current_juz' => 1,
            'halaqa_id' => $this->halaqaId,
        ]);
    }
}
