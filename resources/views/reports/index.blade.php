@extends('layouts.master')

@section('content')
<div class="container-fluid px-3 py-4" dir="rtl">

    <div class="card border-0 shadow-sm mb-4 p-3 d-print-none rounded-4">
        <div class="row align-items-center justify-content-between g-3">
            <div class="col-auto d-flex align-items-center gap-3">
                <div style="font-size: 2.2rem;">📊</div>
                <div>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">تقارير الطلاب</h4>
                    <p class="text-secondary mb-0" style="font-size: 0.85rem;">استعراض التقارير الشهرية والأسبوعية لأداء الطلاب وحفظهم</p>
                </div>
            </div>

            <div class="col">
                <form method="GET" action="{{ route('reports.index') }}" class="row g-2 justify-content-end align-items-center">

                    <div class="col-auto">
                        <select name="type" id="reportType" class="form-select form-select-sm rounded-pill py-2 px-3 text-xs border-light bg-light custom-select-rtl" onchange="toggleWeekField(this.value)">
                            <option value="monthly" {{ (isset($type) && $type == 'monthly') ? 'selected' : '' }}>تقرير شهري</option>
                            <option value="weekly" {{ (isset($type) && $type == 'weekly') ? 'selected' : '' }}>تقرير أسبوعي</option>
                        </select>
                    </div>

                    <div class="col-auto" id="weekFieldContainer" style="{{ (isset($type) && $type == 'weekly') ? '' : 'display: none;' }}">
                        <select name="week" class="form-select form-select-sm rounded-pill py-2 px-3 text-xs border-light bg-light custom-select-rtl">
                            <option value="1" {{ (isset($week) && $week == 1) ? 'selected' : '' }}>الأسبوع الأول</option>
                            <option value="2" {{ (isset($week) && $week == 2) ? 'selected' : '' }}>الأسبوع الثاني</option>
                            <option value="3" {{ (isset($week) && $week == 3) ? 'selected' : '' }}>الأسبوع الثالث</option>
                            <option value="4" {{ (isset($week) && $week == 4) ? 'selected' : '' }}>الأسبوع الرابع</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <select name="month" class="form-select form-select-sm rounded-pill py-2 px-3 text-xs border-light bg-light custom-select-rtl">
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}" {{ (isset($month) && $month == $m) ? 'selected' : '' }}>الشهر {{ $m }}</option>
                                @endfor
                        </select>
                    </div>

                    <div class="col-auto">
                        <select name="year" class="form-select form-select-sm rounded-pill py-2 px-3 text-xs border-light bg-light custom-select-rtl">
                            <option value="2026" {{ (isset($year) && $year == 2026) ? 'selected' : '' }}>2026</option>
                            <option value="2025" {{ (isset($year) && $year == 2025) ? 'selected' : '' }}>2025</option>
                        </select>
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 py-2 fw-semibold shadow-sm">عرض التقرير</button>
                    </div>

                    <div class="col-auto d-flex gap-2">
                        <button type="button" onclick="window.print()" class="btn btn-outline-secondary btn-sm rounded-pill px-3 py-2 fw-semibold shadow-sm">
                            🖨️ طباعة
                        </button>
                        <button type="button" onclick="exportTableToExcel('reportTable', 'student_report')" class="btn btn-outline-success btn-sm rounded-pill px-3 py-2 fw-semibold shadow-sm">
                            📥 تنزيل Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(isset($errorMessage))
    <div class="card border-0 shadow-sm rounded-4 text-center py-5 my-4 bg-white">
        <div class="card-body py-4">
            <div class="mb-3 text-warning" style="font-size: 3rem;">⚠️</div>
            <h4 class="fw-bold text-dark mb-2">عذراً، لا يمكن إصدار التقرير</h4>
            <p class="text-secondary mb-0 fs-6">{{ $errorMessage }}</p>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="reportTable" class="table table-bordered table-striped align-middle text-center mb-0" style="font-size: 13px;">
                    <thead class="table-success text-dark">
                        <tr>
                            <th rowspan="2" class="align-middle">اسم الطالب رباعي</th>
                            <th colspan="2">بداية الحفظ</th>
                            <th colspan="2">نهاية الحفظ</th>
                            <th rowspan="2" class="align-middle">عدد صفحات الحفظ</th>
                            <th rowspan="2" class="align-middle">عدد صفحات المراجعة</th>
                            <th rowspan="2" class="align-middle">الحفظ الكلي بالأجزاء</th>
                            <th colspan="2">الاختبارات المنفردة المنجزة</th>
                            <th colspan="2">الاختبارات المجتمعة المنجزة</th>
                            <th rowspan="2" class="align-middle">عدد أيام الحضور</th>
                            <th rowspan="2" class="align-middle">السنة الدراسية</th>
                            <th rowspan="2" class="align-middle">ملحوظات</th>
                        </tr>
                        <tr>
                            <th>السورة</th>
                            <th>الآية</th>
                            <th>السورة</th>
                            <th>الآية</th>
                            <th>رقم الجزء</th>
                            <th>الدرجة</th>
                            <th>رقم الأجزاء</th>
                            <th>الدرجة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $data)
                        <tr>
                            <td class="fw-bold text-start px-2">{{ $data['student']->full_name }}</td>
                            <td>{{ $data['start_hifz'] !== '-' ? explode(' - ', $data['start_hifz'])[0] : '-' }}</td>
                            <td>{{ $data['start_hifz'] !== '-' ? explode(' - ', $data['start_hifz'])[1] : '-' }}</td>
                            <td>{{ $data['end_hifz'] !== '-' ? explode(' - ', $data['end_hifz'])[0] : '-' }}</td>
                            <td>{{ $data['end_hifz'] !== '-' ? explode(' - ', $data['end_hifz'])[1] : '-' }}</td>
                            <td>{{ $data['total_hifz_pages'] }}</td>
                            <td>{{ $data['total_muraja_pages'] }}</td>
                            <td>{{ $data['current_juz'] ?? '-' }}</td>
                            <td>
                                @foreach($data['single_exams'] as $se)
                                <div>{{ $se->parts_number }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach($data['single_exams'] as $se)
                                <div>{{ $se->grade }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach($data['collective_exams'] as $ce)
                                <div>{{ $ce->parts_number }}</div>
                                @endforeach
                            </td>
                            <td>
                                @foreach($data['collective_exams'] as $ce)
                                <div>{{ $ce->grade }}</div>
                                @endforeach
                            </td>
                            <td>{{ $data['attendance_days'] }}</td>
                            <td>{{ $data['year'] }}</td>
                            <td>-</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="15" class="text-center py-4 text-muted">لا توجد بيانات مسجلة لهذه الفترة.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    .custom-select-rtl {
        background-position: left 0.75rem center !important;
        padding-left: 2rem !important;
        padding-right: 1rem !important;
    }
</style>

<script>
    function toggleWeekField(val) {
        const weekContainer = document.getElementById('weekFieldContainer');
        if (val === 'weekly') {
            weekContainer.style.display = 'block';
        } else {
            weekContainer.style.display = 'none';
        }
    }

    function exportTableToExcel(tableID, filename = '') {
        var tableSelect = document.getElementById(tableID);
        if (!tableSelect) return;

        var excelTemplate = `
            <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: 'Tahoma', 'Arial', sans-serif; direction: rtl; text-align: right; }
                    table { border-collapse: collapse; width: 100%; direction: rtl; }
                    th, td { border: 1px solid #b2b2b2; padding: 8px; text-align: center; vertical-align: middle; font-size: 12px; }
                    th { background-color: #d1e7dd; color: #0f5132; font-weight: bold; }
                </style>
            </head>
            <body dir="rtl">
                ${tableSelect.outerHTML}
            </body>
            </html>
        `;

        var dataType = 'application/vnd.ms-excel';
        filename = filename ? filename + '.xls' : 'student_report.xls';

        var blob = new Blob(['\ufeff', excelTemplate], {
            type: dataType
        });

        if (window.navigator && window.navigator.msSaveOrOpenBlob) {
            window.navigator.msSaveOrOpenBlob(blob, filename);
        } else {
            var downloadLink = document.createElement("a");
            downloadLink.href = URL.createObjectURL(blob);
            downloadLink.download = filename;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    }
</script>
@endsection