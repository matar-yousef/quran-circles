@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-xs">
        <div class="d-flex align-items-center gap-3">
            <div style="font-size: 2.2rem;">✏️</div>
            <div>
                <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.25rem;">تعديل بيانات الاختبار القرآني</h3>
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">الطالب: <span class="fw-bold text-dark">{{ $exam->student->full_name ?? 'غير محدد' }}</span></p>
            </div>
        </div>
        <div>
            <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold shadow-xs font-cairo text-nowrap" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-right ms-1"></i> العودة للقائمة
            </a>
        </div>
    </div>

    <x-alert />

    <div class="card border-0 shadow-xs bg-white rounded-4 p-4 p-md-5">
        <form action="{{ route('exams.update', $exam->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark text-xs mb-2">الطالب المستهدف</label>
                    <select name="student_id" class="form-select rounded-pill py-2.5 px-3 text-sm border-light bg-light @error('student_id') is-invalid @enderror" required>
                        <option value="">اختر الطالب</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ (old('student_id', $exam->student_id) == $student->id) ? 'selected' : '' }}>
                            {{ $student->full_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('student_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark text-xs mb-2">نوع الاختبار</label>
                    <select name="exam_type" class="form-select rounded-pill py-2.5 px-3 text-sm border-light bg-light @error('exam_type') is-invalid @enderror" required>
                        <option value="">اختر نوع الاختبار</option>
                        <option value="single" {{ (old('exam_type', $exam->exam_type) == 'single') ? 'selected' : '' }}>منفرد (جزء واحد)</option>
                        <option value="collective" {{ (old('exam_type', $exam->exam_type) == 'collective') ? 'selected' : '' }}>مجتمع (3 أجزاء فأكثر)</option>
                    </select>
                    @error('exam_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold text-dark text-xs mb-2">رقم الجزء أو الأجزاء التي تم اختبارها</label>
                    <input type="text" name="parts_number" value="{{ old('parts_number', $exam->parts_number) }}" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light @error('parts_number') is-invalid @enderror" placeholder="مثال: الجزء الأول أو الأجزاء (1، 2، 3)" required>
                    @error('parts_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark text-xs mb-2">درجة الاختبار</label>
                    <input type="number" step="0.01" name="grade" value="{{ old('grade', $exam->grade) }}" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light @error('grade') is-invalid @enderror" min="0" max="100" placeholder="مثال: 98" required>
                    @error('grade')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark text-xs mb-2">تاريخ الاختبار</label>
                    <input type="date" name="exam_date" value="{{ old('exam_date', $exam->exam_date) }}" class="form-control rounded-pill py-2.5 px-3 text-sm border-light bg-light @error('exam_date') is-invalid @enderror" required>
                    @error('exam_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold text-dark text-xs mb-2">ملاحظات (اختياري)</label>
                    <textarea name="notes" rows="3" class="form-control rounded-4 p-3 text-sm border-light bg-light @error('notes') is-invalid @enderror" placeholder="أضف أي ملاحظات تود تسجيلها حول أداء الطالب...">{{ old('notes', $exam->notes) }}</textarea>
                    @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-5 pt-3 border-top border-light">
                <button type="submit" class="btn btn-success rounded-pill px-4 py-2 fw-semibold text-xs shadow-xs font-cairo">
                    💾 حفظ التعديلات
                </button>
                <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold text-xs shadow-xs">
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