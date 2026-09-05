@extends('layouts.master')

@section('content')

<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-sm">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.25rem; font-family: 'Cairo', sans-serif;">📖 إعداد خطة قرآنية جديدة للطالب</h3>
            <p class="text-secondary mb-0" style="font-size: 0.85rem; font-family: 'Segoe UI', Tahoma, sans-serif;">
                إضافة وتخصيص خطة حفظ أو مراجعة جديدة للطالب
            </p>
        </div>
        <div class="align-self-stretch align-self-md-auto text-start">
            <a href="{{ route('student-plans.index') }}" class="btn btn-sm btn-light px-4 py-2 rounded-pill fw-semibold text-muted border border-light text-nowrap d-inline-block w-100 w-md-auto text-center" style="font-size: 0.85rem; font-family: 'Cairo', sans-serif;">
                <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="card border-0 bg-white rounded-4 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">

            <x-alert />

            <form action="{{ route('student-plans.store') }}" method="post">
                @csrf

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="student_id" class="form-label text-dark fw-bold" style="font-size: 0.85rem; font-family: 'Cairo', sans-serif;">الطالب المستهدف</label>
                        <select class="form-select rounded-pill px-4 py-2" id="student_id" name="student_id" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;" required>
                            <option value="">اختر الطالب</option>
                            @foreach ($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="start_date" class="form-label text-dark fw-bold" style="font-size: 0.85rem; font-family: 'Cairo', sans-serif;">تاريخ بداية الخطة</label>
                        <input type="date" class="form-control rounded-pill px-4 py-2" id="start_date" name="start_date" value="{{ old('start_date') }}" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="plan_type" class="form-label text-dark fw-bold" style="font-size: 0.85rem; font-family: 'Cairo', sans-serif;">نوع ومسار الخطة</label>
                    <select class="form-select rounded-pill px-4 py-2" id="plan_type" name="plan_type" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;" required>
                        <option value="">اختر مسار الخطة</option>
                        <option value="حفظ" {{ old('plan_type') == 'حفظ' ? 'selected' : '' }}>حفظ جديد فقط</option>
                        <option value="مراجعة" {{ old('plan_type') == 'مراجعة' ? 'selected' : '' }}>مراجعة وتثبيت فقط</option>
                        <option value="حفظ ومراجعة" {{ old('plan_type') == 'حفظ ومراجعة' ? 'selected' : '' }}>مسار مزدوج (حفظ ومراجعة)</option>
                    </select>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label for="duration" class="form-label text-dark fw-bold" style="font-size: 0.85rem; font-family: 'Cairo', sans-serif;">المدة المستهدفة للخطة (بالأشهر)</label>
                        <input type="number" class="form-control rounded-pill px-4 py-2" id="duration" name="duration" value="{{ old('duration') }}" placeholder="مثال: 3 أشهر" min="1" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;" required>
                    </div>
                    <div class="col-md-6">
                        <label for="days_per_week" class="form-label text-dark fw-bold" style="font-size: 0.85rem; font-family: 'Cairo', sans-serif;">عدد أيام التسميع أسبوعياً</label>
                        <input type="number" class="form-control rounded-pill px-4 py-2" id="days_per_week" name="days_per_week" value="{{ old('days_per_week') }}" placeholder="من 1 إلى 7 أيام" min="1" max="7" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;" required>
                    </div>
                </div>

                <div class="mb-4 p-4 bg-light rounded-4 border border-2 border-light-subtle" id="hifz-amount-field" style="display: none;">
                    <label for="daily_hifz" class="form-label text-success fw-bold mb-2" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif;">🎯 مقدار الحفظ اليومي المطلوب</label>
                    <div class="input-group">
                        <input type="number" class="form-control rounded-pill px-4 py-2" id="daily_hifz" name="daily_hifz" value="{{ old('daily_hifz') }}" placeholder="أدخل عدد الصفحات" min="0" max="604" step="0.5" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;">
                        <span class="input-group-text bg-success text-white border-0 px-3 rounded-pill ms-2" style="font-size: 0.85rem;">صفحة / يومياً</span>
                    </div>
                </div>

                <div class="mb-4 p-4 bg-light rounded-4 border border-2 border-light-subtle" id="muraja-amount-field" style="display: none;">
                    <label for="daily_muraja" class="form-label text-info fw-bold mb-2" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif;">🔄 مقدار المراجعة والتثبيت اليومي</label>
                    <div class="input-group">
                        <input type="number" class="form-control rounded-pill px-4 py-2" id="daily_muraja" name="daily_muraja" value="{{ old('daily_muraja') }}" placeholder="أدخل عدد الصفحات" min="0" max="604" step="0.5" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;">
                        <span class="input-group-text bg-info text-white border-0 px-3 rounded-pill ms-2" style="font-size: 0.85rem;">صفحة / يومياً</span>
                    </div>
                </div>

                <div class="d-flex justify-content-start align-items-center gap-2 pt-3 border-top border-light">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm font-cairo" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif; background-color: #198754; border-color: #198754;">اعتماد وحفظ الخطة</button>
                    <a href="{{ route('student-plans.index') }}" class="btn btn-light rounded-pill px-4 py-2 fw-semibold text-muted border border-secondary-subtle" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif;">إلغاء</a>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    const planTypeSelect = document.getElementById('plan_type');
    const hifzAmountField = document.getElementById('hifz-amount-field');
    const murajaAmountField = document.getElementById('muraja-amount-field');
    const dailyHifzInput = document.getElementById('daily_hifz');
    const dailyMurajaInput = document.getElementById('daily_muraja');

    function handlePlanTypeFields() {
        const selectedValue = planTypeSelect.value;

        if (selectedValue === 'حفظ') {
            hifzAmountField.style.display = 'block';
            murajaAmountField.style.display = 'none';
            dailyHifzInput.setAttribute('required', 'required');
            dailyMurajaInput.removeAttribute('required');
            dailyMurajaInput.value = '';
        } else if (selectedValue === 'مراجعة') {
            hifzAmountField.style.display = 'none';
            murajaAmountField.style.display = 'block';
            dailyMurajaInput.setAttribute('required', 'required');
            dailyHifzInput.removeAttribute('required');
            dailyHifzInput.value = '';
        } else if (selectedValue === 'حفظ ومراجعة') {
            hifzAmountField.style.display = 'block';
            murajaAmountField.style.display = 'block';
            dailyHifzInput.setAttribute('required', 'required');
            dailyMurajaInput.setAttribute('required', 'required');
        } else {
            hifzAmountField.style.display = 'none';
            murajaAmountField.style.display = 'none';
            dailyHifzInput.removeAttribute('required');
            dailyMurajaInput.removeAttribute('required');
        }
    }

    handlePlanTypeFields();
    planTypeSelect.addEventListener('change', handlePlanTypeFields);
</script>

@endsection