@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-xs">
        <div class="d-flex align-items-center gap-3">
            <div style="font-size: 2.2rem;">📊</div>
            <div>
                <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.25rem;">تفاصيل الخطة القرآنية</h3>
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">الطالب: <span class="fw-bold text-dark">{{ $plan->student->full_name }}</span></p>
            </div>
        </div>
        <div>
            <a href="{{ route('student-plans.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold shadow-xs font-cairo text-nowrap" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-xs bg-white rounded-4 p-4 h-100">
                <h5 class="fw-bold text-dark mb-4 font-cairo border-bottom pb-3">📌 بيانات ومسار الخطة</h5>

                <div class="row g-3 text-sm">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">نوع ومسار الخطة:</span>
                            <span class="fw-bold text-dark">
                                {{ $plan->plan_type == 'حفظ' ? 'حفظ جديد' : ($plan->plan_type == 'مراجعة' ? 'مراجعة وتثبيت' : 'مسار مزدوج (حفظ ومراجعة)') }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">حالة الخطة:</span>
                            @if($plan->is_overdue)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-bold">⚠️ متأخرة</span>
                            @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">✅ في الموعد</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">المدة الإجمالية:</span>
                            <span class="fw-bold text-dark">{{ $plan->duration }} أشهر</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">أيام الحضور بالأسبوع:</span>
                            <span class="fw-bold text-dark">{{ $plan->days_per_week }} أيام</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">تاريخ الإنشاء:</span>
                            <span class="fw-bold text-dark">{{ $plan->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">تاريخ البداية:</span>
                            <span class="fw-bold text-dark">{{ $plan->start_date }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded-3">
                            <span class="text-muted d-block mb-1 text-xs">تاريخ الانتهاء المتوقع:</span>
                            <span class="fw-bold text-dark">{{ $plan->end_date->format('Y-m-d') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-primary-subtle border border-primary-subtle rounded-3">
                            <span class="text-primary d-block mb-1 text-xs fw-semibold">مقدار الحفظ اليومي:</span>
                            <span class="fw-bold text-primary">{{ $plan->daily_hifz ?? '-' }} صفحة</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-warning-subtle border border-warning-subtle rounded-3">
                            <span class="text-warning-emphasis d-block mb-1 text-xs fw-semibold">مقدار المراجعة اليومي:</span>
                            <span class="fw-bold text-warning-emphasis">{{ $plan->daily_muraja ?? '-' }} صفحة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-xs bg-white rounded-4 p-4 h-100 text-center d-flex flex-column justify-content-center align-items-center">
                <h5 class="fw-bold text-dark mb-4 font-cairo w-100 text-start border-bottom pb-3">📈 نسبة التقدم</h5>

                <div style="width: 160px; height: 160px; border-radius: 50%; background: conic-gradient(#198754 {{ $plan->progress_percentage }}%, #f1f5f9 {{ $plan->progress_percentage }}%); margin: 0 auto; display: flex; align-items: center; justify-content: center;" class="shadow-sm mb-3">
                    <div style="width: 130px; height: 130px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center;" class="fw-bold fs-3 text-dark font-cairo">
                        {{ $plan->progress_percentage }}%
                    </div>
                </div>
                <p class="text-secondary text-xs fw-semibold mb-0">إجمالي الإنجاز في الخطة الحالية</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-xs bg-white rounded-4 mt-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom border-light">
            <h5 class="fw-bold text-dark mb-0 font-cairo fs-5">📊 تفاصيل الأيام والمقاييس</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center font-system">
                    <thead class="bg-table-header">
                        <tr>
                            <th class="py-3 px-4 text-start text-muted">المقياس</th>
                            <th class="py-3 text-muted">العدد</th>
                            <th class="py-3 text-muted">المعدل اليومي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-light">
                            <td class="text-start px-4 fw-bold text-dark">المدة الإجمالية (أيام)</td>
                            <td><span class="badge bg-light text-dark border px-3 py-1 rounded-pill">{{ $plan->total_target_days }} يوم</span></td>
                            <td class="text-secondary">{{ $plan->duration * 30 }} (تقريباً)</td>
                        </tr>
                        <tr class="border-light">
                            <td class="text-start px-4 fw-bold text-dark">عدد الأيام المحققة (حضور)</td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill fw-bold">{{ $plan->total_attended }} يوم</span></td>
                            <td class="text-secondary">{{ $plan->days_per_week }} أيام / أسبوع</td>
                        </tr>
                        <tr class="border-light">
                            <td class="text-start px-4 fw-bold text-dark">الأيام المتبقية</td>
                            <td><span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill fw-bold">{{ max(0, $plan->total_target_days - $plan->total_attended) }} يوم</span></td>
                            <td class="text-secondary">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }

    .font-system {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .text-xs {
        font-size: 0.75rem !important;
    }

    .bg-table-header {
        background-color: #f8fafc !important;
        font-size: 0.8rem;
        letter-spacing: 0.3px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table> :not(caption)>*>* {
        padding: 1rem 0.75rem;
    }
</style>
@endsection