@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-xs">
        <div class="d-flex align-items-center gap-3">
            <div style="font-size: 2.2rem;">✏️</div>
            <div>
                <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.25rem;">تعديل الخطة القرآنية</h3>
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">الطالب: <span class="fw-bold text-dark">{{ $plan->student->full_name }}</span></p>
            </div>
        </div>
        <div>
            <a href="{{ route('student-plans.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold shadow-xs font-cairo text-nowrap" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <x-alert />

    <div class="card border-0 shadow-xs bg-white rounded-4 p-4 p-md-5">
        <form action="{{ route('student-plans.update', $plan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label for="student_id" class="form-label fw-bold text-dark text-xs mb-2">الطالب المستهدف</label>
                    <select name="student_id" id="student_id" class="form-select rounded-pill py-2.5 px-3 text-sm border-light bg-light" required>
                        <option value="">اختر الطالب</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $plan->student_id == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="plan_type" class="form-label fw-bold text-dark text-xs mb-2">نوع الخطة</label>
                    <select name="plan_type" id="plan_type" class="form-select rounded-pill py-2.5 px-3 text-sm border-light bg-light" required>
                        <option value="حفظ" {{ $plan->plan_type == 'حفظ' ? 'selected' : '' }}>حفظ جديد</option>
                        <option value="مراجعة" {{ $plan->plan_type == 'مراجعة' ? 'selected' : '' }}>مراجعة وتثبيت</option>
                        <option value="حفظ ومراجعة" {{ $plan->plan_type == 'حفظ ومراجعة' ? 'selected' : '' }}>حفظ ومراجعة</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="duration" class="form-label fw-bold text-dark text-xs mb-2">المدة (بالأشهر)</label>
                    <input type="number" name="duration" id="duration" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light" value="{{ $plan->duration }}" min="1" required>
                </div>

                <div class="col-md-6">
                    <label for="days_per_week" class="form-label fw-bold text-dark text-xs mb-2">عدد أيام التسميع أسبوعياً</label>
                    <input type="number" name="days_per_week" id="days_per_week" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light" value="{{ $plan->days_per_week }}" min="1" max="7" required>
                </div>

                <div class="col-md-6">
                    <label for="daily_hifz" class="form-label fw-bold text-dark text-xs mb-2">مقدار الحفظ اليومي (صفحة)</label>
                    <input type="number" name="daily_hifz" id="daily_hifz" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light" value="{{ $plan->daily_hifz }}" min="1">
                </div>

                <div class="col-md-6">
                    <label for="daily_muraja" class="form-label fw-bold text-dark text-xs mb-2">مقدار المراجعة اليومي (صفحة)</label>
                    <input type="number" name="daily_muraja" id="daily_muraja" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light" value="{{ $plan->daily_muraja }}">
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-5 pt-3 border-top border-light">
                <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold text-xs shadow-xs font-cairo">
                    💾 حفظ التعديلات
                </button>
                <a href="{{ route('student-plans.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold text-xs shadow-xs">
                    إلغاء
                </a>
            </div>
        </form>
    </div>

</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .text-xs {
        font-size: 0.8rem !important;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #fff !important;
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1) !important;
    }
</style>
@endsection