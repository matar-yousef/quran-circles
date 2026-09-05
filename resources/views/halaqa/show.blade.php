@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-4 rounded-4 shadow-xs border border-1 border-light">
        <div>
            <h3 class="fw-bold text-dark mb-1 font-cairo">🕌 حلقة: {{ $halaqa->name }}</h3>
            <p class="text-secondary text-sm mb-0 font-system">إدارة طلاب الحلقة ومتابعة أداء التسميع والإنجاز</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('halaqa.edit', $halaqa->id) }}" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-semibold font-system">✏️ تعديل البيانات</a>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light px-3 rounded-pill fw-semibold text-muted border border-light font-system">🔙 العودة للرئيسية</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-xs rounded-4 p-4 bg-white card-hover h-100 position-relative overflow-hidden">
                <div class="position-absolute start-0 top-0 bottom-0 bg-primary" style="width: 4px;"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-xs fw-bold d-block font-system mb-1">إجمالي الطلاب</span>
                        <h2 class="fw-extrabold text-dark mb-0 dashboard-num">{{ $totalStudents }} <span class="fs-6 fw-normal text-muted font-system">طالب</span></h2>
                    </div>
                    <div class="text-primary bg-primary-subtle rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">👨‍🎓</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-xs rounded-4 p-4 bg-white card-hover h-100 position-relative overflow-hidden">
                <div class="position-absolute start-0 top-0 bottom-0 bg-success" style="width: 4px;"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-xs fw-bold d-block font-system mb-1">الطلاب المنتظمون (هذا الشهر)</span>
                        <h2 class="fw-extrabold text-success mb-0 dashboard-num">{{ $activeStudents }} <span class="fs-6 fw-normal text-muted font-system">طالب</span></h2>
                    </div>
                    <div class="text-success bg-success-subtle rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">✅</div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-xs rounded-4 p-4 bg-white card-hover h-100 position-relative overflow-hidden">
                <div class="position-absolute start-0 top-0 bottom-0 bg-info" style="width: 4px;"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted text-xs fw-bold d-block font-system mb-1">مجموع إنجاز الصفحات (هذا الشهر)</span>
                        <h2 class="fw-extrabold text-info mb-0 dashboard-num">{{ $monthlyPages }} <span class="fs-6 fw-normal text-muted font-system">صفحة</span></h2>
                    </div>
                    <div class="text-info bg-info-subtle rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.25rem;">📖</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-xs bg-white rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom border-light">
            <h5 class="fw-bold text-dark mb-0 font-cairo fs-5">👥 قائمة طلاب الحلقة والتسميع</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center font-system student-table">
                    <thead>
                        <tr>
                            <th class="py-3 px-3 text-muted bg-table-header rounded-start-custom">#</th>
                            <th class="py-3 text-start pe-4 bg-table-header">اسم الطالب</th>
                            <th class="py-3 bg-table-header">الجزء الحالي</th>
                            <th class="py-3 bg-table-header">آخر تاريخ تسميع</th>
                            <th class="py-3 bg-table-header">التقييم العام</th>
                            <th class="py-3 bg-table-header">نسبة الحضور</th>
                            <th class="py-3 px-3 bg-table-header rounded-end-custom">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($halaqa->students as $student)
                        @php
                        $lastTracking = $student->dailyTrackings
                        ->filter(function($tracking) {
                        return $tracking->is_present == 1 && (
                        $tracking->hifz_pages > 0 ||
                        $tracking->muraja_pages > 0 ||
                        ($tracking->details && $tracking->details->count() > 0)
                        );
                        })
                        ->sortByDesc('date')
                        ->first();

                        $latestRating = $lastTracking ? $lastTracking->rating : null;
                        @endphp
                        <tr class="border-light transition-all">
                            <td class="text-muted text-xs px-3 fw-semibold" data-label="الرقم:">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark text-start pe-4 text-sm" data-label="اسم الطالب:">
                                {{ $student->full_name }}
                            </td>
                            <td data-label="الجزء الحالي:">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1.5 rounded-pill text-xs fw-semibold">
                                    الجزء {{ $student->current_juz ?? '-' }}
                                </span>
                            </td>
                            <td class="text-secondary text-xs" data-label="آخر تاريخ تسميع:">
                                {!! $lastTracking ? \Carbon\Carbon::parse($lastTracking->date)->format('Y-m-d') : '<span class="text-muted">لا يوجد تسميع</span>' !!}
                            </td>
                            <td data-label="التقييم العام:">
                                @if($latestRating)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold text-xs">
                                    {{ $latestRating }}
                                </span>
                                @else
                                <span class="text-muted text-xs">-</span>
                                @endif
                            </td>
                            <td data-label="نسبة الحضور:">
                                <span class="badge bg-light text-secondary border px-3 py-1.5 rounded-pill text-xs fw-bold">
                                    {{ $student->attendance_percentage ?? 0 }}%
                                </span>
                            </td>
                            <td data-label="الإجراءات:" class="px-3">
                                <a href="{{ route('daily-tracking.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-success px-3 py-1.5 rounded-pill fw-semibold text-xs shadow-xs action-btn">
                                    ⚡ تسميع سريع
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-muted py-5 text-sm font-system">لا يوجد طلاب مضافون لهذه الحلقة حتى الآن.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    .font-cairo,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    th {
        font-family: 'Cairo', sans-serif !important;
    }

    .font-system,
    body,
    p,
    span,
    small,
    td,
    .btn,
    .badge {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .dashboard-num {
        font-family: 'Cairo', sans-serif !important;
        letter-spacing: -0.5px;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .card-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05) !important;
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    .text-xs {
        font-size: 0.75rem !important;
    }

    .bg-table-header {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-size: 0.8rem;
        letter-spacing: 0.3px;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
    }

    .table> :not(caption)>*>* {
        padding: 1rem 0.75rem;
    }

    .action-btn {
        transition: all 0.2s ease;
    }

    .action-btn:hover {
        background-color: #198754 !important;
        color: #fff !important;
        transform: translateY(-1px);
    }

    @media (max-width: 768px) {
        .student-table thead {
            display: none;
        }

        .student-table,
        .student-table tbody,
        .student-table tr,
        .student-table td {
            display: block;
            width: 100%;
        }

        .student-table tr {
            background: #fff;
            margin-bottom: 12px;
            padding: 14px;
            border-radius: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
        }

        .student-table td {
            text-align: right !important;
            padding: 8px 0 !important;
            border: none !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .student-table td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #718096;
            font-size: 0.8rem;
            font-family: 'Cairo', sans-serif;
        }

        .student-table td:last-child {
            justify-content: center;
            margin-top: 10px;
            padding-top: 12px !important;
            border-top: 1px dashed #edf2f7 !important;
        }

        .student-table td:last-child::before {
            display: none;
        }

        .action-btn {
            width: 100%;
            padding-top: 8px;
            padding-bottom: 8px;
        }
    }
</style>
@endsection