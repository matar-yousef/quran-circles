@extends('layouts.master')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

<x-alert />

<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">
    <form method="POST" action="{{ route('daily-tracking.batch.store') }}">
        @csrf

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-4 rounded-4 border border-1 border-light shadow-sm">
            <div>
                <h3 class="fw-bold text-dark mb-1 font-cairo">⚡ تسجيل المتابعة اليومية للطلاب</h3>
                <p class="text-secondary mb-0 font-system" style="font-size: 0.88rem;">تسجيل الحفظ والمراجعة والحضور بشكل جماعي لجميع الطلاب</p>
            </div>
            <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-pill border shadow-2xs" style="border-color: #cbd5e1 !important;">
                <label for="tracking_date" class="fw-bold mb-0 text-dark font-cairo text-nowrap" style="font-size: 0.85rem;">تاريخ المتابعة:</label>
                <input type="date" id="tracking_date" name="tracking_date" class="form-control form-control-sm rounded-pill px-3 border bg-white font-system" value="{{ date('Y-m-d') }}" required style="font-size: 0.85rem; border-color: #cbd5e1 !important; width: 145px;">
            </div>
        </div>

        <div class="d-md-none mb-4">
            @foreach($students as $index => $student)
            <div class="card border shadow-sm bg-white rounded-4 p-3 mb-3 student-card-mobile" style="border-color: #e2e8f0 !important;" data-student-index="{{ $index }}">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-cairo px-2 py-1" style="font-size: 0.75rem;">#{{ $index + 1 }}</span>
                        <span class="fw-bold text-dark font-cairo fs-6">{{ $student->full_name ?? $student->name }}</span>
                    </div>
                    <input type="hidden" name="tracking[{{ $index }}][student_id]" value="{{ $student->id }}">
                    <div>
                        <select name="tracking[{{ $index }}][is_present]" class="form-select form-select-sm attendance-select rounded-3 px-3 py-1 border font-system" data-student-index="{{ $index }}" style="font-size: 0.75rem; border-color: #cbd5e1 !important;">
                            <option value="1" selected>حاضر</option>
                            <option value="0">غائب</option>
                            <option value="2">مستأذن</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    @if($student->last_surah_id && $student->last_surah_name)
                    <div class="text-muted bg-light p-2 rounded-3 border font-system" style="font-size: 0.75rem; border-color: #e2e8f0 !important;">
                        📍 آخر وصول: سورة {{ $student->last_surah_name }} - آية {{ $student->last_ayah }}
                    </div>
                    @else
                    <div class="text-danger bg-danger-subtle p-2 rounded-3 border border-danger-subtle font-system" style="font-size: 0.75rem;">
                        ⚠️ لم يُسمع في المرة السابقة
                    </div>
                    @endif
                </div>

                <div class="mb-3 surahs-container-mobile" id="surahs-container-mobile-{{ $index }}">
                    <div class="surah-row p-2 border rounded-3 bg-light shadow-2xs mb-2" style="border-color: #cbd5e1 !important;">
                        <div class="row g-2 mb-2">
                            <div class="col-4">
                                <select name="tracking[{{ $index }}][surahs][0][type]" class="form-select form-select-sm fw-bold text-primary rounded-3 border font-cairo" style="font-size: 0.75rem; height: 32px; border-color: #cbd5e1 !important;">
                                    <option value="hifz">حفظ</option>
                                    <option value="muraja">مراجعة</option>
                                </select>
                            </div>
                            <div class="col-8">
                                <select name="tracking[{{ $index }}][surahs][0][from_surah_id]" class="form-select form-select-sm searchable-surah rounded-3 border font-system" style="font-size: 0.75rem;">
                                    <option value="">-- من سورة البداية --</option>
                                    @foreach($surahs as $surah)
                                    <option value="{{ $surah->id }}" {{ $student->last_surah_id == $surah->id ? 'selected' : '' }}>{{ $surah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 align-items-center">
                            <div class="col-4">
                                <input type="number" name="tracking[{{ $index }}][surahs][0][from_ayah]" class="form-control form-control-sm text-center rounded-3 border font-system" min="1" placeholder="من آية" value="{{ $student->last_ayah }}" style="font-size: 0.75rem; height: 32px; border-color: #cbd5e1 !important;">
                            </div>
                            <div class="col-5">
                                <select name="tracking[{{ $index }}][surahs][0][to_surah_id]" class="form-select form-select-sm searchable-surah rounded-3 border font-system" style="font-size: 0.75rem;">
                                    <option value="">نفس السورة</option>
                                    @foreach($surahs as $surah)
                                    <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="tracking[{{ $index }}][surahs][0][to_ayah]" class="form-control form-control-sm text-center rounded-3 border font-system" min="1" placeholder="إلى آية" style="font-size: 0.75rem; height: 32px; border-color: #cbd5e1 !important;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 pt-2 border-top">
                    <div class="col-6">
                        <select name="tracking[{{ $index }}][rating]" class="form-select form-select-sm rounded-3 border font-system" style="font-size: 0.75rem; height: 32px; border-color: #cbd5e1 !important;">
                            <option value="" selected>-- التقييم --</option>
                            <option value="ممتاز">ممتاز</option>
                            <option value="جيد جداً">جيد جداً</option>
                            <option value="جيد">جيد</option>
                            <option value="مقبول">مقبول</option>
                            <option value="ضعيف">ضعيف</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <input type="text" name="tracking[{{ $index }}][notes]" class="form-control form-control-sm rounded-3 border font-system text-right rtl-placeholder" placeholder="ملاحظات..." style="font-size: 0.75rem; height: 32px; border-color: #cbd5e1 !important;">
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="card border shadow-sm bg-white rounded-4 overflow-hidden d-none d-md-block mb-4" style="border-color: #cbd5e1 !important;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" style="border-color: #cbd5e1 !important;">
                        <thead class="text-dark font-cairo text-center shadow-2xs" style="font-size: 0.9rem; background-color: #f1f5f9 !important; border-bottom: 2px solid #cbd5e1;">
                            <tr>
                                <th style="width: 22%;" class="py-3 px-3 text-center">اسم الطالب</th>
                                <th style="width: 12%;" class="py-3 px-2 text-center">الحالة</th>
                                <th style="width: 40%;" class="py-3 px-3 text-center">نطاق الحفظ أو المراجعة</th>
                                <th style="width: 13%;" class="py-3 px-2 text-center">التقييم</th>
                                <th style="width: 13%;" class="py-3 px-3 text-center">ملاحظات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr class="student-row-desktop hover-row" data-student-index="{{ $index }}">
                                <td class="text-right px-3 py-3 align-middle bg-white" style="border-color: #e2e8f0 !important;">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-cairo px-2 py-1" style="font-size: 0.75rem;">#{{ $index + 1 }}</span>
                                        <span class="fw-bold text-dark font-cairo" style="font-size: 0.95rem;">{{ $student->full_name ?? $student->name }}</span>
                                    </div>
                                    <input type="hidden" name="tracking[{{ $index }}][student_id]" value="{{ $student->id }}">

                                    @if($student->last_surah_id && $student->last_surah_name)
                                    <div class="text-muted mt-1 d-flex align-items-center gap-1 font-system" style="font-size: 0.78rem; padding-right: 2px;">
                                        <span>📍 آخر وصول:</span>
                                        <span class="fw-semibold text-secondary">سورة {{ $student->last_surah_name }} - آية {{ $student->last_ayah }}</span>
                                    </div>
                                    @else
                                    <div class="text-danger mt-1 d-flex align-items-center gap-1 font-system" style="font-size: 0.78rem; padding-right: 2px;">
                                        <span>⚠️</span>
                                        <span>لم يُسمع في المرة السابقة</span>
                                    </div>
                                    @endif
                                </td>

                                <td class="text-center px-2 align-middle bg-white" style="border-color: #e2e8f0 !important;">
                                    <select name="tracking[{{ $index }}][is_present]" class="form-select form-select-sm attendance-select rounded-3 px-2 border fw-medium shadow-none font-system text-center" data-student-index="{{ $index }}" style="font-size: 0.85rem; height: 38px; border-color: #cbd5e1 !important;">
                                        <option value="1" selected>حاضر</option>
                                        <option value="0">غائب</option>
                                        <option value="2">مستأذن</option>
                                    </select>
                                </td>

                                <td class="text-right px-3 py-3 align-middle bg-white" style="border-color: #e2e8f0 !important;">
                                    <div id="surahs-container-{{ $index }}">
                                        <div class="surah-row p-3 border rounded-3 bg-light shadow-2xs mb-2 position-relative" style="border-color: #cbd5e1 !important;">
                                            <div class="row g-2 mb-2 align-items-center">
                                                <div class="col-3">
                                                    <select name="tracking[{{ $index }}][surahs][0][type]" class="form-select form-select-sm fw-bold text-primary rounded-3 border bg-white font-cairo" style="font-size: 0.82rem; height: 36px; border-color: #cbd5e1 !important;">
                                                        <option value="hifz">حفظ</option>
                                                        <option value="muraja">مراجعة</option>
                                                    </select>
                                                </div>
                                                <div class="col-6">
                                                    <select name="tracking[{{ $index }}][surahs][0][from_surah_id]" class="form-select form-select-sm searchable-surah font-system" style="font-size: 0.82rem;">
                                                        <option value="">-- من سورة البداية --</option>
                                                        @foreach($surahs as $surah)
                                                        <option value="{{ $surah->id }}" {{ $student->last_surah_id == $surah->id ? 'selected' : '' }}>{{ $surah->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <input type="number" name="tracking[{{ $index }}][surahs][0][from_ayah]" class="form-control form-control-sm text-center rounded-3 border bg-white font-system rtl-placeholder-center" min="1" placeholder="رقم الآية" value="{{ $student->last_ayah }}" style="font-size: 0.82rem; height: 36px; border-color: #cbd5e1 !important;">
                                                </div>
                                            </div>

                                            <div class="row g-2 align-items-center">
                                                <div class="col-3 text-right">
                                                    <span class="text-muted fw-bold font-system" style="font-size: 0.75rem;">إلى نهاية المقطع:</span>
                                                </div>
                                                <div class="col-5">
                                                    <select name="tracking[{{ $index }}][surahs][0][to_surah_id]" class="form-select form-select-sm searchable-surah font-system" style="font-size: 0.82rem;">
                                                        <option value="">(نفس السورة)</option>
                                                        @foreach($surahs as $surah)
                                                        <option value="{{ $surah->id }}">{{ $surah->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-3">
                                                    <input type="number" name="tracking[{{ $index }}][surahs][0][to_ayah]" class="form-control form-control-sm text-center rounded-3 border bg-white font-system rtl-placeholder-center" min="1" placeholder="رقم الآية" style="font-size: 0.82rem; height: 36px; border-color: #cbd5e1 !important;">
                                                </div>
                                                <div class="col-1 ps-0 text-center">
                                                    <button type="button" class="btn btn-sm btn-success w-100 add-row-btn px-0 fw-bold rounded-3 shadow-xs d-flex align-items-center justify-content-center" data-student-index="{{ $index }}" title="إضافة مقطع آخر" style="font-size: 0.9rem; height: 36px;">+</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center px-2 align-middle bg-white" style="border-color: #e2e8f0 !important;">
                                    <select name="tracking[{{ $index }}][rating]" class="form-select form-select-sm rounded-3 px-2 border shadow-none font-system text-center" style="font-size: 0.85rem; height: 38px; border-color: #cbd5e1 !important;">
                                        <option value="" selected>-- التقييم --</option>
                                        <option value="ممتاز">ممتاز</option>
                                        <option value="جيد جداً">جيد جداً</option>
                                        <option value="جيد">جيد</option>
                                        <option value="مقبول">مقبول</option>
                                        <option value="ضعيف">ضعيف</option>
                                    </select>
                                </td>

                                <td class="text-center px-3 align-middle bg-white" style="border-color: #e2e8f0 !important;">
                                    <input type="text" name="tracking[{{ $index }}][notes]" class="form-control form-control-sm rounded-3 px-3 border shadow-none font-system text-right rtl-placeholder" placeholder="ملاحظات..." style="font-size: 0.85rem; height: 38px; border-color: #cbd5e1 !important;">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card border shadow-sm bg-white rounded-4 py-3 px-3 px-md-4" style="border-color: #cbd5e1 !important;">
            <div class="row g-2 align-items-center justify-content-between">
                <div class="col-5 col-md-auto">
                    <a href="{{ route('daily-tracking.index') }}" class="btn btn-light rounded-pill w-100 px-3 px-md-4 fw-semibold text-muted border d-flex align-items-center justify-content-center font-system" style="font-size: 0.9rem; height: 44px; border-color: #cbd5e1 !important;">إلغاء</a>
                </div>
                <div class="col-7 col-md-auto">
                    <button type="submit" class="btn btn-success rounded-pill px-4 px-md-5 fw-semibold shadow-xs font-cairo d-flex align-items-center justify-content-center gap-2 text-nowrap" style="font-size: 0.92rem; height: 44px; min-width: auto; width: 100%;">
                        <span>حفظ المتابعة للكل</span>
                        <span>💾</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
        letter-spacing: -0.2px;
    }

    .font-system {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .rtl-placeholder {
        padding-right: 12px !important;
        padding-left: 12px !important;
    }

    .rtl-placeholder::placeholder {
        text-align: right !important;
        color: #94a3b8;
    }

    .rtl-placeholder-center::placeholder {
        text-align: center !important;
        color: #94a3b8;
    }

    .hover-row:hover {
        background-color: #f8fafc !important;
        transition: background-color 0.15s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #fff !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.12);
    }

    .surah-row {
        transition: all 0.2s ease;
    }

    .surah-row:hover {
        border-color: #94a3b8 !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>

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

        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('attendance-select')) {
                const studentIndex = e.target.getAttribute('data-student-index');
                const status = e.target.value;

                const containerDesktop = document.getElementById('surahs-container-' + studentIndex);
                const containerMobile = document.getElementById('surahs-container-mobile-' + studentIndex);
                const rowBlock = document.querySelector(`.student-row-desktop[data-student-index="${studentIndex}"]`) || document.querySelector(`.student-card-mobile[data-student-index="${studentIndex}"]`);

                const ratingSelect = rowBlock ? rowBlock.querySelector(`select[name="tracking[${studentIndex}][rating]"]`) : null;

                const containers = [containerDesktop, containerMobile];
                containers.forEach(container => {
                    if (!container) return;
                    if (status === '0' || status === '2') {
                        container.style.opacity = '0.4';
                        container.querySelectorAll('input, select').forEach(input => {
                            if (input.tomselect) {
                                input.tomselect.disable();
                            } else {
                                input.disabled = true;
                            }
                        });
                    } else {
                        container.style.opacity = '1';
                        container.querySelectorAll('input, select').forEach(input => {
                            if (input.tomselect) {
                                input.tomselect.enable();
                            } else {
                                input.disabled = false;
                            }
                        });
                    }
                });

                if (ratingSelect) {
                    if (status === '0' || status === '2') {
                        ratingSelect.value = '';
                        ratingSelect.disabled = true;
                    } else {
                        ratingSelect.disabled = false;
                    }
                }
            }
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('add-row-btn')) {
            e.preventDefault();

            const studentIndex = e.target.getAttribute('data-student-index');
            const container = document.getElementById('surahs-container-' + studentIndex);
            if (!container) return;

            const rowsCount = container.querySelectorAll('.surah-row').length;

            let surahOptionsFrom = '<option value="">-- من سورة البداية --</option>';
            let surahOptionsTo = '<option value="">(نفس السورة)</option>';
            allSurahs.forEach(surah => {
                surahOptionsFrom += `<option value="${surah.id}">${surah.name}</option>`;
                surahOptionsTo += `<option value="${surah.id}">${surah.name}</option>`;
            });

            const newRow = document.createElement('div');
            newRow.className = 'surah-row p-3 border rounded-3 bg-light shadow-2xs mb-2 position-relative';
            newRow.style.borderColor = '#cbd5e1';
            newRow.innerHTML = `
                <button type="button" class="btn btn-sm btn-danger remove-row-btn position-absolute top-0 start-0 m-1 px-2 py-0 rounded-pill" style="font-size: 10px; z-index: 5;" title="حذف هذا المقطع">✕</button>
                <div class="row g-2 mb-2 align-items-center pt-1">
                    <div class="col-3">
                        <select name="tracking[${studentIndex}][surahs][${rowsCount}][type]" class="form-select form-select-sm fw-bold text-primary rounded-3 border bg-white font-cairo" style="font-size: 0.82rem; height: 36px; border-color: #cbd5e1 !important;">
                            <option value="hifz">حفظ</option>
                            <option value="muraja">مراجعة</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="tracking[${studentIndex}][surahs][${rowsCount}][from_surah_id]" class="form-select form-select-sm searchable-surah font-system" style="font-size: 0.82rem;">
                            ${surahOptionsFrom}
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" name="tracking[${studentIndex}][surahs][${rowsCount}][from_ayah]" class="form-control form-control-sm text-center rounded-3 border bg-white font-system rtl-placeholder-center" min="1" placeholder="رقم الآية" style="font-size: 0.82rem; height: 36px; border-color: #cbd5e1 !important;">
                    </div>
                </div>
                <div class="row g-2 align-items-center">
                    <div class="col-3 text-right">
                        <span class="text-muted fw-bold font-system" style="font-size: 0.75rem;">إلى نهاية المقطع:</span>
                    </div>
                    <div class="col-5">
                        <select name="tracking[${studentIndex}][surahs][${rowsCount}][to_surah_id]" class="form-select form-select-sm searchable-surah font-system" style="font-size: 0.82rem;">
                            ${surahOptionsTo}
                        </select>
                    </div>
                    <div class="col-3">
                        <input type="number" name="tracking[${studentIndex}][surahs][${rowsCount}][to_ayah]" class="form-control form-control-sm text-center rounded-3 border bg-white font-system rtl-placeholder-center" min="1" placeholder="رقم الآية" style="font-size: 0.82rem; height: 36px; border-color: #cbd5e1 !important;">
                    </div>
                    <div class="col-1 ps-0 text-center">
                        <button type="button" class="btn btn-sm btn-success w-100 add-row-btn px-0 fw-bold rounded-3 shadow-xs d-flex align-items-center justify-content-center" data-student-index="${studentIndex}" title="إضافة مقطع آخر" style="font-size: 0.9rem; height: 36px;">+</button>
                    </div>
                </div>
            `;
            container.appendChild(newRow);

            newRow.querySelectorAll('.searchable-surah').forEach(el => {
                initTomSelect(el);
            });
        }

        if (e.target && e.target.classList.contains('remove-row-btn')) {
            e.preventDefault();
            const row = e.target.closest('.surah-row');
            if (row) {
                row.remove();
            }
        }
    });
</script>
@endsection