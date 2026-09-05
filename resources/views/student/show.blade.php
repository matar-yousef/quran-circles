@extends('layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 gap-3 bg-white p-4 rounded-4 border border-1 border-light shadow-sm">
        <div>
            <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.4rem;">
                <i class="bi bi-person-badge text-success me-2"></i> الملف الشخصي للطالب: {{ $student->full_name }}
            </h3>
            <p class="text-secondary mb-0 font-system" style="font-size: 0.85rem;">عرض تفاصيل الطالب، معلومات ولي الأمر، وإحصائيات المتابعة اليومية.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-start justify-content-md-end">
            <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-outline-primary px-4 py-2 rounded-pill fw-semibold shadow-xs font-system d-inline-flex align-items-center" style="font-size: 0.85rem;">
                <i class="bi bi-pencil-square me-1"></i> تعديل البيانات
            </a>
            <a href="{{ route('student.index') }}" class="btn btn-sm btn-light px-4 py-2 rounded-pill fw-semibold text-muted border border-light font-system d-inline-flex align-items-center" style="font-size: 0.85rem;">
                <i class="bi bi-arrow-right me-1"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6 col-sm-6">
            <div class="card border-0 rounded-4 p-4 bg-white h-100 position-relative overflow-hidden card-custom-hover shadow-sm">
                <div class="position-absolute start-0 top-0 bottom-0 bg-success" style="width: 4px;"></div>
                <span class="text-muted fw-bold d-block mb-1 font-system" style="font-size: 0.75rem;">إجمالي صفحات الحفظ</span>
                <h3 class="fw-extrabold text-success mb-0 font-cairo" style="letter-spacing: -0.5px;">{{ $totalHifzPages ?? 0 }} <span class="fs-6 fw-normal text-muted font-system">صفحة</span></h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6">
            <div class="card border-0 rounded-4 p-4 bg-white h-100 position-relative overflow-hidden card-custom-hover shadow-sm">
                <div class="position-absolute start-0 top-0 bottom-0 bg-primary" style="width: 4px;"></div>
                <span class="text-muted fw-bold d-block mb-1 font-system" style="font-size: 0.75rem;">إجمالي صفحات المراجعة</span>
                <h3 class="fw-extrabold text-primary mb-0 font-cairo" style="letter-spacing: -0.5px;">{{ $totalMurajaPages ?? 0 }} <span class="fs-6 fw-normal text-muted font-system">صفحة</span></h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6">
            <div class="card border-0 rounded-4 p-4 bg-white h-100 position-relative overflow-hidden card-custom-hover shadow-sm">
                <div class="position-absolute start-0 top-0 bottom-0 bg-info" style="width: 4px;"></div>
                <span class="text-muted fw-bold d-block mb-1 font-system" style="font-size: 0.75rem;">نسبة الحضور</span>
                <h3 class="fw-extrabold text-info mb-0 font-cairo" style="letter-spacing: -0.5px;">{{ $attendancePercentage ?? 0 }}%</h3>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-sm-6">
            <div class="card border-0 rounded-4 p-4 bg-white h-100 position-relative overflow-hidden card-custom-hover shadow-sm">
                <div class="position-absolute start-0 top-0 bottom-0 bg-warning" style="width: 4px;"></div>
                <span class="text-muted fw-bold d-block mb-1 font-system" style="font-size: 0.75rem;">التقييم الأغلب</span>
                <h3 class="fw-extrabold text-warning mb-0 font-cairo" style="letter-spacing: -0.5px;">{{ $mostFrequentRating ?? '-' }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 bg-white rounded-4 h-100 overflow-hidden shadow-sm">
                <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex align-items-center">
                    <div class="bg-success-subtle text-success p-2 rounded-3 me-2 fs-6 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-person"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0 font-cairo" style="font-size: 1.1rem;">البيانات الشخصية</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush font-system" style="font-size: 0.9rem;">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">الاسم الرباعي:</span>
                            <span class="fw-bold text-dark text-end">{{ $student->full_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">رقم هوية الطالب:</span>
                            <span class="fw-bold text-dark text-end" dir="ltr">{{ $student->student_id_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">الصف الدراسي:</span>
                            <span class="fw-bold text-dark text-end">{{ $student->grade }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">الجزء الحالي:</span>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">الجزء {{ $student->current_juz }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">عنوان السكن:</span>
                            <span class="fw-bold text-dark text-end">{{ $student->address }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 bg-white rounded-4 h-100 overflow-hidden shadow-sm">
                <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex align-items-center">
                    <div class="bg-primary-subtle text-primary p-2 rounded-3 me-2 fs-6 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0 font-cairo" style="font-size: 1.1rem;">بيانات ولي الأمر</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush font-system" style="font-size: 0.9rem;">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">اسم الأب الرباعي:</span>
                            <span class="fw-bold text-dark text-end">{{ $student->father_full_name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">رقم هوية الأب:</span>
                            <span class="fw-bold text-dark text-end" dir="ltr">{{ $student->father_id_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">رقم جوال ولي الأمر:</span>
                            <span class="fw-bold text-primary text-end" dir="ltr">{{ $student->guardian_phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">الحلقة التابعة لها:</span>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">{{ $student->halaqa->name ?? 'غير محددة' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 border-light">
                            <span class="text-muted">تاريخ التسجيل:</span>
                            <span class="fw-bold text-dark text-end">{{ $student->created_at ? $student->created_at->format('Y-m-d') : '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 bg-white rounded-4 overflow-hidden shadow-sm">
        <div class="card-header bg-white py-3 px-4 border-bottom border-light d-flex align-items-center">
            <div class="bg-warning-subtle text-warning p-2 rounded-3 me-2 fs-6 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                <i class="bi bi-graph-up"></i>
            </div>
            <h5 class="fw-bold text-dark mb-0 font-cairo" style="font-size: 1.1rem;">سجل المتابعة اليومية (آخر السجلات)</h5>
        </div>
        <div class="card-body p-0">
            @if(isset($recentTrackings) && $recentTrackings->isEmpty())
            <div class="text-center py-5">
                <div class="display-4 text-muted opacity-50 mb-2"><i class="bi bi-clipboard-x"></i></div>
                <p class="text-muted mb-0 font-system" style="font-size: 0.9rem;">لا توجد سجلات متابعة يومية مسجلة لهذا الطالب حتى الآن.</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-center font-system">
                    <thead class="table-light text-secondary font-cairo" style="font-size: 0.8rem;">
                        <tr>
                            <th class="py-3 px-3 text-muted">#</th>
                            <th class="py-3">التاريخ</th>
                            <th class="py-3">حالة الحضور</th>
                            <th class="py-3">حفظ (صفحات)</th>
                            <th class="py-3">مراجعة (صفحات)</th>
                            <th class="py-3">التقييم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentTrackings ?? [] as $tracking)
                        <tr class="border-light">
                            <td class="text-muted px-3" style="font-size: 0.8rem;">{{ $loop->iteration }}</td>
                            <td class="text-secondary" style="font-size: 0.85rem;" dir="ltr">{{ $tracking->date ?? '-' }}</td>
                            <td>
                                @if($tracking->is_present == 1)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">حاضر</span>
                                @elseif($tracking->is_present == 2)
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">مستأذن</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">غائب</span>
                                @endif
                            </td>
                            <td><span class="text-success fw-bold" style="font-size: 0.9rem;">{{ $tracking->hifz_pages ?? 0 }}</span></td>
                            <td><span class="text-primary fw-bold" style="font-size: 0.9rem;">{{ $tracking->muraja_pages ?? 0 }}</span></td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-1 rounded-pill" style="font-size: 0.75rem;">{{ $tracking->rating ?? '-' }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }

    .font-system {
        font-family: 'Segoe UI', Tahoma, sans-serif !important;
    }

    .card-custom-hover {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-custom-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05) !important;
    }
</style>
@endsection