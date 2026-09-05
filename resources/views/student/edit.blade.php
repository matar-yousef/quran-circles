@extends('layouts.master')

@section('content')

<x-alert />

<div class="container py-4 py-md-5 px-3" style="max-width: 1000px;" dir="rtl">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 pb-3 border-bottom gap-3">
        <div>
            <h1 class="h3 fw-bold text-dark mb-1 font-cairo">
                <i class="bi bi-pencil-square text-success me-2"></i> تعديل بيانات الطالب: {{ $student->full_name }}
            </h1>
            <p class="text-muted small mb-0 font-system">قم بتحديث معلومات الطالب الشخصية، ولي الأمر، والمعلومات الأكاديمية بدقة.</p>
        </div>
        <a href="{{ route('student.index') }}" class="btn btn-light border text-secondary rounded-pill px-4 shadow-sm hover-shadow transition font-system" style="font-size: 0.85rem;">
            <i class="bi bi-arrow-right me-1"></i> رجوع للقائمة
        </a>
    </div>

    <form action="{{ route('student.update', $student->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <!-- Card 1: معلومات الطالب الشخصية -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white">
            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                <div class="bg-success-subtle text-success p-2 rounded-3 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person"></i>
                </div>
                <h4 class="h5 fw-bold text-dark mb-0 font-cairo">معلومات الطالب الشخصية</h4>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="full_name" class="form-label fw-semibold text-secondary small font-system">الاسم رباعي</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-fonts text-muted"></i></span>
                        <input type="text" class="form-control bg-light @error('full_name') is-invalid @enderror" id="full_name" name="full_name" required value="{{ old('full_name', $student->full_name) }}" placeholder="أدخل اسم الطالب رباعياً" style="font-size: 0.9rem;">
                    </div>
                    @error('full_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="student_id_number" class="form-label fw-semibold text-secondary small font-system">رقم هوية الطالب</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-card-text text-muted"></i></span>
                        <input type="text" class="form-control bg-light @error('student_id_number') is-invalid @enderror" id="student_id_number" name="student_id_number" required value="{{ old('student_id_number', $student->student_id_number) }}" placeholder="9 أرقام" style="font-size: 0.9rem;" dir="ltr">
                    </div>
                    @error('student_id_number')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="address" class="form-label fw-semibold text-secondary small font-system">مكان الإقامة</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-geo-alt text-muted"></i></span>
                        <input type="text" class="form-control bg-light @error('address') is-invalid @enderror" id="address" name="address" required value="{{ old('address', $student->address) }}" placeholder="المدينة / المنطقة / الحي" style="font-size: 0.9rem;">
                    </div>
                    @error('address')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="birth_date" class="form-label fw-semibold text-secondary small font-system">تاريخ الميلاد</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-calendar-event text-muted"></i></span>
                        <input type="date" class="form-control bg-light @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" required value="{{ old('birth_date', $student->birth_date) }}" style="font-size: 0.9rem;">
                    </div>
                    @error('birth_date')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Card 2: معلومات ولي الأمر -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white">
            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                <div class="bg-primary-subtle text-primary p-2 rounded-3 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-people"></i>
                </div>
                <h4 class="h5 fw-bold text-dark mb-0 font-cairo">معلومات ولي الأمر</h4>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="father_full_name" class="form-label fw-semibold text-secondary small font-system">اسم الأب رباعي</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-person-badge text-muted"></i></span>
                        <input type="text" class="form-control bg-light @error('father_full_name') is-invalid @enderror" id="father_full_name" name="father_full_name" required value="{{ old('father_full_name', $student->father_full_name) }}" placeholder="اسم الأب" style="font-size: 0.9rem;">
                    </div>
                    @error('father_full_name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="father_id_number" class="form-label fw-semibold text-secondary small font-system">رقم هوية الأب</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-card-heading text-muted"></i></span>
                        <input type="text" class="form-control bg-light @error('father_id_number') is-invalid @enderror" id="father_id_number" name="father_id_number" required value="{{ old('father_id_number', $student->father_id_number) }}" placeholder="رقم الهوية" style="font-size: 0.9rem;" dir="ltr">
                    </div>
                    @error('father_id_number')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="guardian_phone" class="form-label fw-semibold text-secondary small font-system">رقم جوال ولي الأمر</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-phone text-muted"></i></span>
                        <input type="tel" class="form-control bg-light @error('guardian_phone') is-invalid @enderror" id="guardian_phone" name="guardian_phone" required value="{{ old('guardian_phone', $student->guardian_phone) }}" placeholder="059xxxxxxx" style="font-size: 0.9rem;" dir="ltr">
                    </div>
                    @error('guardian_phone')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Card 3: المعلومات الأكاديمية -->
        <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mb-4 bg-white">
            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                <div class="bg-warning-subtle text-warning p-2 rounded-3 me-2 fs-5 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <h4 class="h5 fw-bold text-dark mb-0 font-cairo">المعلومات الأكاديمية</h4>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="current_juz" class="form-label fw-semibold text-secondary small font-system">الجزء الحالي (الحفظ)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-book text-muted"></i></span>
                        <input type="number" class="form-control bg-light @error('current_juz') is-invalid @enderror" id="current_juz" name="current_juz" min="1" max="30" required value="{{ old('current_juz', $student->current_juz) }}" placeholder="من 1 إلى 30" style="font-size: 0.9rem;">
                    </div>
                    @error('current_juz')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="grade" class="form-label fw-semibold text-secondary small font-system">الصف الدراسي</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-mortarboard text-muted"></i></span>
                        <select class="form-select bg-light @error('grade') is-invalid @enderror" id="grade" name="grade" required style="font-size: 0.9rem;">
                            <option value="" disabled>اختر الصف الدراسي...</option>
                            @foreach(config('grades.options', []) as $gradeOption)
                            <option value="{{ $gradeOption }}" {{ old('grade', $student->grade ?? '') == $gradeOption ? 'selected' : '' }}>
                                {{ $gradeOption }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('grade')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-wrap justify-content-start gap-2 mt-4">
            <button type="submit" class="btn btn-success px-5 py-2.5 rounded-pill fw-bold shadow-sm font-cairo" style="font-size: 0.95rem;">
                <i class="bi bi-check-lg ms-1"></i> حفظ التعديلات
            </button>
            <a href="{{ route('student.index') }}" class="btn btn-light border px-4 py-2.5 rounded-pill fw-semibold text-muted font-system text-center" style="font-size: 0.95rem;">إلغاء</a>
        </div>
    </form>
</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }

    .font-system {
        font-family: 'Segoe UI', Tahoma, sans-serif !important;
    }

    .form-control:focus,
    .form-select:focus {
        background-color: #fff !important;
        border-color: #198754 !important;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.1);
    }
</style>
@endsection