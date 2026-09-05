@extends('layouts.parent-layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .parent-report-wrapper {
        font-family: 'Cairo', sans-serif !important;
        background-color: #f8fafc;
        min-height: 90vh;
        padding-bottom: 3rem;
    }

    .report-card {
        border: none;
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.04);
        border-radius: 1.25rem;
        background: #ffffff;
    }

    .stat-card {
        border: none;
        border-radius: 1.25rem;
        box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }

    .table-custom th {
        font-weight: 600;
        background-color: #f8fafc !important;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        font-size: 0.9rem;
    }

    .table-custom td {
        vertical-align: middle;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .desktop-table-view {
            display: none !important;
        }

        .mobile-cards-view {
            display: block !important;
        }
    }

    @media (min-width: 769px) {
        .desktop-table-view {
            display: block !important;
        }

        .mobile-cards-view {
            display: none !important;
        }
    }
</style>

<div class="container-fluid px-3 py-4 parent-report-wrapper" dir="rtl">

    <div class="card report-card mb-4 position-relative overflow-hidden">
        <div class="position-absolute top-0 start-0 h-100 bg-success" style="width: 6px;"></div>
        <div class="card-body p-4">
            <div class="d-flex align-items-start align-items-md-center gap-3 mb-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; font-size: 1.5rem;">
                    👨‍🎓
                </div>
                <div>
                    <h4 class="fw-bold text-dark mb-1 fs-5">تقرير إنجاز الطالب: <span class="text-success">{{ $student->full_name }}</span></h4>
                    <p class="text-muted mb-0 small">تابع أداء ابنك، حفظه، وحضوره اليومي بكل سهولة</p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4 pt-2 border-top text-secondary" style="font-size: 0.88rem;">
                <span class="bg-light px-3 py-2 rounded-pill border">📚 الصف: <strong class="text-dark">{{ $student->grade }}</strong></span>
                <span class="bg-light px-3 py-2 rounded-pill border">🆔 الهوية: <strong class="text-dark">{{ $student->student_id_number }}</strong></span>
                <span class="bg-light px-3 py-2 rounded-pill border">📖 الحلقة: <strong class="text-dark">{{ $student->halaqa->name ?? 'غير محددة' }}</strong></span>
                <span class="bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">الجزء الحالي: {{ $student->current_juz ?? '-' }}</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card stat-card bg-white h-100 border-top border-primary border-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-bold mb-2">إنجاز اليوم (الصفحات)</h6>
                        <h3 class="fw-bold text-primary mb-0">{{ $todayProgress ?? 0 }} <span class="fs-6 text-muted fw-normal">صفحة</span></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.3rem;">
                        ☀️
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card stat-card bg-white h-100 border-top border-success border-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-bold mb-2">إنجاز الأسبوع الحالي</h6>
                        <h3 class="fw-bold text-success mb-0">{{ $weeklyProgress ?? 0 }} <span class="fs-6 text-muted fw-normal">صفحة</span></h3>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.3rem;">
                        📈
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card stat-card bg-white h-100 border-top border-info border-4">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted fw-bold mb-2">إنجاز الشهر الحالي</h6>
                        <h3 class="fw-bold text-info mb-0">{{ $monthlyProgress ?? 0 }} <span class="fs-6 text-muted fw-normal">صفحة</span></h3>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.3rem;">
                        ⭐
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-card mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <span class="fw-bold text-dark d-flex align-items-center gap-2">
                    <span>📌</span> ملخص الحضور والغياب (الشهر الحالي):
                </span>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold">✅ حاضر: {{ $presentCount ?? 0 }}</span>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-semibold">❌ غائب: {{ $absentCount ?? 0 }}</span>
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-25 px-3 py-2 rounded-pill fw-semibold">⚠️ مستأذن: {{ $excusedCount ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="card report-card mb-4">
        <div class="card-body bg-white py-3 px-4">
            <form action="{{ route('parent.search') }}" method="POST" class="d-flex align-items-center justify-content-between gap-3 flex-wrap">
                @csrf
                <input type="hidden" name="full_name" value="{{ $student->full_name }}">
                <input type="hidden" name="student_id_number" value="{{ $student->student_id_number }}">

                <span class="fw-bold text-secondary">عرض سجلات الفترة:</span>

                <div class="d-flex align-items-center gap-2 flex-wrap w-100 w-md-auto">
                    <button type="submit" name="period" value="today" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold flex-grow-1 flex-md-grow-0 {{ ($period ?? 'all') == 'today' ? 'btn-primary shadow-sm' : 'btn-light text-secondary border' }}">
                        📅 اليوم
                    </button>
                    <button type="submit" name="period" value="week" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold flex-grow-1 flex-md-grow-0 {{ ($period ?? 'all') == 'week' ? 'btn-success shadow-sm' : 'btn-light text-secondary border' }}">
                        📆 الأسبوع
                    </button>
                    <button type="submit" name="period" value="month" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold flex-grow-1 flex-md-grow-0 {{ ($period ?? 'all') == 'month' ? 'btn-info text-white shadow-sm' : 'btn-light text-secondary border' }}">
                        🗓️ الشهر
                    </button>
                    <button type="submit" name="period" value="all" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold flex-grow-1 flex-md-grow-0 {{ ($period ?? 'all') == 'all' ? 'btn-dark shadow-sm' : 'btn-light text-secondary border' }}">
                        📂 الكل
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card report-card overflow-hidden">
        <div class="card-header bg-white py-3.5 px-4 border-bottom">
            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center gap-2">
                <span>📋</span> سجل المتابعات التفصيلي
            </h5>
        </div>

        <div class="card-body p-0">
            <div class="desktop-table-view table-responsive">
                <table class="table table-custom table-hover align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th class="py-3 px-3">التاريخ</th>
                            <th class="py-3 px-3">حالة الحضور</th>
                            <th class="py-3 px-3 text-start ps-4">تفاصيل الحفظ والمراجعة</th>
                            <th class="py-3 px-3">التقييم</th>
                            <th class="py-3 px-3 text-start ps-4">ملاحظات المعلم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trackings as $tracking)
                        <tr>
                            <td class="fw-bold text-dark px-3">{{ $tracking->date }}</td>
                            <td class="px-3">
                                @if($tracking->is_present == 1)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill">حاضر</span>
                                @elseif($tracking->is_present == 0)
                                <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1.5 rounded-pill">غائب</span>
                                @else
                                <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-1.5 rounded-pill">مستأذن</span>
                                @endif
                            </td>
                            <td class="text-start ps-4 px-3">
                                @if($tracking->details && $tracking->details->count() > 0)
                                <ul class="mb-0 list-unstyled">
                                    @foreach($tracking->details as $detail)
                                    <li class="py-1">
                                        <span class="badge {{ $detail->type == 'hifz' ? 'bg-primary' : 'bg-info' }} bg-opacity-75 px-2 py-1">
                                            {{ $detail->type == 'hifz' ? 'حفظ' : 'مراجعة' }}
                                        </span>
                                        <span class="text-dark fw-medium small">سورة ({{ $detail->surah->name ?? '-' }}) آية {{ $detail->from_ayah }}</span>
                                        @if($detail->to_ayah)
                                        <span class="text-muted small">إلى {{ $detail->to_ayah }}</span>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <span class="text-muted small">لا توجد تفاصيل مسجلة</span>
                                @endif
                            </td>
                            <td class="px-3">
                                <span class="fw-bold text-success small">{{ $tracking->rating ?? '-' }}</span>
                            </td>
                            <td class="text-start ps-4 text-secondary px-3 small">{{ $tracking->notes ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-muted py-5 text-center">
                                <div class="py-4">
                                    <span style="font-size: 2.5rem;">📭</span>
                                    <p class="mt-2 mb-0 text-secondary">لا توجد سجلات متابعة مسجلة في هذه الفترة.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mobile-cards-view p-3 bg-light">
                @forelse($trackings as $tracking)
                <div class="card border-0 shadow-sm rounded-4 mb-3 p-3 bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <span class="fw-bold text-dark">📅 {{ $tracking->date }}</span>
                        <div>
                            @if($tracking->is_present == 1)
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-1.5 rounded-pill">حاضر</span>
                            @elseif($tracking->is_present == 0)
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-1.5 rounded-pill">غائب</span>
                            @else
                            <span class="badge bg-warning bg-opacity-10 text-dark px-3 py-1.5 rounded-pill">مستأذن</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="text-muted d-block mb-1 small">📖 تفاصيل الحفظ والمراجعة:</span>
                        @if($tracking->details && $tracking->details->count() > 0)
                        <ul class="mb-0 list-unstyled ps-0">
                            @foreach($tracking->details as $detail)
                            <li class="py-1">
                                <span class="badge {{ $detail->type == 'hifz' ? 'bg-primary' : 'bg-info' }} bg-opacity-75 px-2 py-1">
                                    {{ $detail->type == 'hifz' ? 'حفظ' : 'مراجعة' }}
                                </span>
                                <span class="text-dark fw-medium small">سورة ({{ $detail->surah->name ?? '-' }}) آية {{ $detail->from_ayah }}</span>
                                @if($detail->to_ayah)
                                <span class="text-muted small">إلى {{ $detail->to_ayah }}</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <span class="text-muted small">لا توجد تفاصيل مسجلة</span>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                        <div>
                            <span class="text-muted small">التقييم:</span>
                            <span class="fw-bold text-success small">{{ $tracking->rating ?? '-' }}</span>
                        </div>
                        <div class="text-truncate text-secondary" style="max-width: 60%;">
                            <span class="text-muted small">ملاحظات:</span> <span class="small">{{ $tracking->notes ?? '-' }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-muted py-5 text-center bg-white rounded-4">
                    <span style="font-size: 2.5rem;">📭</span>
                    <p class="mt-2 mb-0 text-secondary">لا توجد سجلات متابعة مسجلة في هذه الفترة.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection