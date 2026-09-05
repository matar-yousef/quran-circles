@extends('layouts.master')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<style>
    .ts-wrapper {
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }

    .ts-control {
        min-height: 44px !important;
        padding: 8px 16px !important;
        font-size: 0.9rem !important;
        border: 1px solid #ced4da !important;
        border-radius: 50rem !important;
        background-color: #ffffff !important;
    }

    .ts-control:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    .form-control,
    .form-select {
        border: 1px solid #ced4da !important;
        background-color: #ffffff !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
    }

    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }
</style>

<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <x-alert />

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-sm">
        <div>
            <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.25rem;">✏️ تعديل متابعة الطالب</h3>
            <p class="text-secondary mb-0" style="font-size: 0.85rem; font-family: 'Segoe UI', Tahoma, sans-serif;">
                تعديل بيانات متابعة الطالب: <span class="text-primary fw-bold">{{ $dailyTracking->student->full_name ?? '' }}</span>
            </p>
        </div>
        <div class="align-self-stretch align-self-md-auto text-start">
            <a href="{{ route('daily-tracking.index') }}" class="btn btn-sm btn-light px-4 py-2 rounded-pill fw-semibold text-muted border border-light font-cairo text-nowrap d-inline-block" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <div class="card border-0 bg-white rounded-4 shadow-sm mb-4">
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('daily-tracking.update', $dailyTracking->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-bold font-cairo" style="font-size: 0.85rem;">التاريخ</label>
                        <input type="date" name="date" class="form-control rounded-pill px-4 py-2" value="{{ old('date', $dailyTracking->date) }}" style="font-size: 0.9rem; height: 44px;" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-dark fw-bold font-cairo" style="font-size: 0.85rem;">حالة الحضور</label>
                        <select name="is_present" id="is_present_select" class="form-select rounded-pill px-4 py-2 attendance-select" style="font-size: 0.9rem; height: 44px;" required>
                            <option value="1" {{ old('is_present', $dailyTracking->is_present) == 1 ? 'selected' : '' }}>حاضر</option>
                            <option value="0" {{ old('is_present', $dailyTracking->is_present) == 0 ? 'selected' : '' }}>غائب</option>
                            <option value="2" {{ old('is_present', $dailyTracking->is_present) == 2 ? 'selected' : '' }}>مستأذن</option>
                        </select>
                    </div>
                </div>

                <hr class="text-muted opacity-25 my-4">

                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-3 font-cairo fs-6">📖 بيانات الحفظ والمراجعة</h5>

                    <div id="surahs-container" class="p-3 bg-light rounded-4 border border-2 border-light-subtle" style="opacity: {{ in_array(old('is_present', $dailyTracking->is_present), [0, 2]) ? '0.4' : '1' }};">

                        @php
                        $details = old('surahs');
                        if (!$details) {
                        $details = $dailyTracking->details ?? $dailyTracking->surahs ?? [];
                        }
                        $details = collect($details);
                        if ($details->isEmpty()) {
                        $details = [[]];
                        }
                        $isDisabled = in_array(old('is_present', $dailyTracking->is_present), [0, 2]) ? 'disabled' : '';
                        @endphp

                        @foreach($details as $index => $detail)
                        <div class="surah-row card p-3 mb-3 border border-1 border-light rounded-4 bg-white shadow-sm position-relative">
                            @if($index > 0)
                            <button type="button" class="btn btn-sm btn-danger remove-row position-absolute top-0 start-0 m-3 px-2 py-1 rounded-pill" style="font-size: 11px; z-index: 5;" title="حذف هذا المقطع">✕</button>
                            @endif

                            <div class="row g-3 align-items-center">
                                <div class="col-lg-2 col-md-3">
                                    <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">النوع</label>
                                    <select name="surahs[{{ $index }}][type]" class="form-select form-select-sm rounded-pill fw-bold text-primary" style="height: 38px; border: 1px solid #ced4da !important;" {{ $isDisabled }}>
                                        <option value="hifz" {{ data_get($detail, 'type') == 'hifz' ? 'selected' : '' }}>حفظ</option>
                                        <option value="muraja" {{ data_get($detail, 'type') == 'muraja' ? 'selected' : '' }}>مراجعة</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">من سورة</label>
                                    <select name="surahs[{{ $index }}][from_surah_id]" class="form-select form-select-sm searchable-surah" {{ $isDisabled }}>
                                        <option value="">اختر السورة</option>
                                        @foreach($surahs as $surah)
                                        <option value="{{ $surah->id }}" {{ (data_get($detail, 'from_surah_id') == $surah->id || data_get($detail, 'surah_id') == $surah->id) ? 'selected' : '' }}>
                                            {{ $surah->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">من آية</label>
                                    <input type="number" name="surahs[{{ $index }}][from_ayah]" class="form-control form-control-sm rounded-pill text-center" value="{{ data_get($detail, 'from_ayah', data_get($detail, 'ayah')) }}" min="1" placeholder="رقم الآية" style="height: 38px; border: 1px solid #ced4da !important;" {{ $isDisabled }}>
                                </div>
                                <div class="col-lg-3 col-md-2">
                                    <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">إلى سورة (اختياري)</label>
                                    <select name="surahs[{{ $index }}][to_surah_id]" class="form-select form-select-sm searchable-surah" {{ $isDisabled }}>
                                        <option value="">(نفس السورة)</option>
                                        @foreach($surahs as $surah)
                                        <option value="{{ $surah->id }}" {{ data_get($detail, 'to_surah_id') == $surah->id ? 'selected' : '' }}>
                                            {{ $surah->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-1 col-md-1">
                                    <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">إلى آية</label>
                                    <input type="number" name="surahs[{{ $index }}][to_ayah]" class="form-control form-control-sm rounded-pill text-center" value="{{ data_get($detail, 'to_ayah') }}" min="1" placeholder="الآية" style="height: 38px; border: 1px solid #ced4da !important;" {{ $isDisabled }}>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-4">
                    <button type="button" id="add-surah-row" class="btn btn-sm btn-success rounded-pill px-4 py-2 fw-semibold font-cairo d-inline-block shadow-sm" {{ in_array(old('is_present', $dailyTracking->is_present), [0, 2]) ? 'disabled' : '' }}>
                        <i class="fas fa-plus ms-1"></i> إضافة سورة أو مقطع آخر
                    </button>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-dark fw-bold font-cairo" style="font-size: 0.85rem;">التقييم</label>
                        <select name="rating" id="rating_select" class="form-select rounded-pill px-4 py-2" style="font-size: 0.9rem; height: 44px; border: 1px solid #ced4da !important;" {{ in_array(old('is_present', $dailyTracking->is_present), [0, 2]) ? 'disabled' : '' }}>
                            <option value="">بدون تقييم</option>
                            <option value="ممتاز" {{ old('rating', $dailyTracking->rating) == 'ممتاز' ? 'selected' : '' }}>ممتاز</option>
                            <option value="جيد جداً" {{ old('rating', $dailyTracking->rating) == 'جيد جداً' ? 'selected' : '' }}>جيد جداً</option>
                            <option value="جيد" {{ old('rating', $dailyTracking->rating) == 'جيد' ? 'selected' : '' }}>جيد</option>
                            <option value="مقبول" {{ old('rating', $dailyTracking->rating) == 'مقبول' ? 'selected' : '' }}>مقبول</option>
                            <option value="ضعيف" {{ old('rating', $dailyTracking->rating) == 'ضعيف' ? 'selected' : '' }}>ضعيف</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-dark fw-bold font-cairo" style="font-size: 0.85rem;">ملاحظات</label>
                        <textarea name="notes" class="form-control rounded-4 p-3" rows="3" style="font-size: 0.9rem; border: 1px solid #ced4da !important;">{{ old('notes', $dailyTracking->notes) }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-start align-items-center gap-2 pt-3 border-top border-light">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold shadow-sm font-cairo" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif; background-color: #198754; border-color: #198754;">تحديث البيانات 💾</button>
                    <a href="{{ route('daily-tracking.index') }}" class="btn btn-light rounded-pill px-4 py-2 fw-semibold text-muted border border-secondary-subtle" style="font-size: 0.9rem;">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    const allSurahs = @json($surahs);

    function initTomSelect(element) {
        if (element.tomselect) return;
        new TomSelect(element, {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            },
            placeholder: "ابحث عن السورة...",
            maxOptions: 120
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.searchable-surah').forEach(el => {
            initTomSelect(el);
        });

        const attendanceSelect = document.getElementById('is_present_select');
        if (attendanceSelect) {
            attendanceSelect.addEventListener('change', function() {
                const status = this.value;
                const container = document.getElementById('surahs-container');
                const ratingSelect = document.getElementById('rating_select');
                const addBtn = document.getElementById('add-surah-row');

                if (status === '0' || status === '2') {
                    if (container) container.style.opacity = '0.4';
                    if (addBtn) addBtn.disabled = true;
                    if (ratingSelect) {
                        ratingSelect.value = '';
                        ratingSelect.disabled = true;
                    }
                    container.querySelectorAll('input, select').forEach(input => {
                        if (input.tomselect) {
                            input.tomselect.disable();
                        } else {
                            input.disabled = true;
                        }
                    });
                } else {
                    if (container) container.style.opacity = '1';
                    if (addBtn) addBtn.disabled = false;
                    if (ratingSelect) ratingSelect.disabled = false;
                    container.querySelectorAll('input, select').forEach(input => {
                        if (input.tomselect) {
                            input.tomselect.enable();
                        } else {
                            input.disabled = false;
                        }
                    });
                }
            });
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target && (e.target.id === 'add-surah-row' || e.target.closest('#add-surah-row'))) {
            let container = document.getElementById('surahs-container');
            let firstRow = container.querySelector('.surah-row');
            if (!firstRow) return;

            let rowIndex = container.querySelectorAll('.surah-row').length;

            let surahOptionsFrom = '<option value="">اختر السورة</option>';
            let surahOptionsTo = '<option value="">(نفس السورة)</option>';
            allSurahs.forEach(surah => {
                surahOptionsFrom += `<option value="${surah.id}">${surah.name}</option>`;
                surahOptionsTo += `<option value="${surah.id}">${surah.name}</option>`;
            });

            let newRow = document.createElement('div');
            newRow.className = 'surah-row card p-3 mb-3 border border-1 border-light rounded-4 bg-white shadow-sm position-relative';
            newRow.innerHTML = `
                <button type="button" class="btn btn-sm btn-danger remove-row position-absolute top-0 start-0 m-3 px-2 py-1 rounded-pill" style="font-size: 11px; z-index: 5;" title="حذف هذا المقطع">✕</button>
                <div class="row g-3 align-items-center">
                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">النوع</label>
                        <select name="surahs[${rowIndex}][type]" class="form-select form-select-sm rounded-pill fw-bold text-primary" style="height: 38px; border: 1px solid #ced4da !important;">
                            <option value="hifz">حفظ</option>
                            <option value="muraja">مراجعة</option>
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">من سورة</label>
                        <select name="surahs[${rowIndex}][from_surah_id]" class="form-select form-select-sm searchable-surah">
                            ${surahOptionsFrom}
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-2">
                        <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">من آية</label>
                        <input type="number" name="surahs[${rowIndex}][from_ayah]" class="form-control form-control-sm rounded-pill text-center" min="1" placeholder="رقم الآية" style="height: 38px; border: 1px solid #ced4da !important;">
                    </div>
                    <div class="col-lg-3 col-md-2">
                        <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">إلى سورة (اختياري)</label>
                        <select name="surahs[${rowIndex}][to_surah_id]" class="form-select form-select-sm searchable-surah">
                            ${surahOptionsTo}
                        </select>
                    </div>
                    <div class="col-lg-1 col-md-1">
                        <label class="form-label text-dark fw-semibold" style="font-size: 0.75rem;">إلى آية</label>
                        <input type="number" name="surahs[${rowIndex}][to_ayah]" class="form-control form-control-sm rounded-pill text-center" min="1" placeholder="الآية" style="height: 38px; border: 1px solid #ced4da !important;">
                    </div>
                </div>
            `;
            container.appendChild(newRow);

            newRow.querySelectorAll('.searchable-surah').forEach(el => {
                initTomSelect(el);
            });
        }

        if (e.target && e.target.classList.contains('remove-row')) {
            let row = e.target.closest('.surah-row');
            let container = document.getElementById('surahs-container');
            if (container.querySelectorAll('.surah-row').length > 1) {
                row.remove();
            } else {
                alert('يجب أن تبقى سورة واحدة على الأقل.');
            }
        }
    });
</script>
@endsection