@extends('layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-5" dir="rtl" style="max-width: 950px; background-color: #f8fafc; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5">
                <div class="card-body text-center">

                    <div class="mb-3">
                        <span class="display-3 p-3 bg-warning-subtle text-warning rounded-circle d-inline-flex align-items-center justify-content-center shadow-xs" style="width: 85px; height: 85px;">
                            🏆
                        </span>
                    </div>

                    <h2 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.6rem;">طالب الشهر المثالي</h2>
                    <p class="text-secondary small mb-4 font-system">نماذج مشرفة في الانضباط وحفظ كتاب الله عز وجل</p>

                    @if(isset($idealStudent) && $idealStudent)
                    <div class="p-4 bg-light border border-light-subtle rounded-4 shadow-xs mb-4">
                        <h3 class="fw-bold text-success mb-2 font-cairo" style="font-size: 1.3rem;">{{ $idealStudent->full_name }}</h3>
                        <p class="text-secondary small mb-3 d-flex flex-wrap justify-content-center align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill font-system">الصف: {{ $idealStudent->grade }}</span>
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill font-system">الحلقة: {{ $idealStudent->halaqa->name ?? 'غير محددة' }}</span>
                        </p>

                        <hr class="text-muted opacity-25 my-3">

                        <div class="row text-center mt-3 justify-content-center g-3">
                            <div class="col-sm-4">
                                <div class="p-3 bg-white rounded-3 shadow-xs border border-light h-100 d-flex flex-column justify-content-center">
                                    <span class="d-block text-muted small mb-1 font-system">إنجاز الحفظ</span>
                                    <span class="fw-bold text-success fs-5 font-system">{{ number_format($idealStudent->total_hifz, 1) }} صفحة</span>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="p-3 bg-white rounded-3 shadow-xs border border-light h-100 d-flex flex-column justify-content-center">
                                    <span class="d-block text-muted small mb-1 font-system">إنجاز المراجعة</span>
                                    <span class="fw-bold text-primary fs-5 font-system">{{ number_format($idealStudent->total_review, 1) }} صفحة</span>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="p-3 bg-white rounded-3 shadow-xs border border-light h-100 d-flex flex-column justify-content-center">
                                    <span class="d-block text-muted small mb-1 font-system">أيام الحضور</span>
                                    <span class="fw-bold text-info fs-5 font-system">{{ $idealStudent->present_days }} يوم</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert bg-success-subtle text-success border-0 rounded-3 py-3 mb-4 fw-semibold font-system" style="font-size: 0.95rem;">
                        🌟 مبارك للطالب هذا التميز والالتزام في حفظ كتاب الله!
                    </div>
                    @else
                    <div class="p-5 bg-light rounded-4 my-4">
                        <i class="bi bi-info-circle text-muted fs-2 d-block mb-2"></i>
                        <p class="text-muted mb-0 font-system">لا توجد بيانات كافية حتى الآن لتحديد الطالب المثالي لهذا الشهر.</p>
                    </div>
                    @endif

                    <div class="mt-4 text-center">
                        <a href="{{ url()->previous() }}" class="btn btn-light border border-light text-secondary rounded-pill px-5 py-2 fw-semibold font-system shadow-xs" style="font-size: 0.9rem;">
                            <i class="bi bi-arrow-right ms-1"></i> العودة لإدارة الطلاب
                        </a>
                    </div>

                </div>
            </div>

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
</style>
@endsection