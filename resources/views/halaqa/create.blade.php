@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-4 rounded-4 border border-1 border-light" style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="font-family: 'Cairo', sans-serif !important;">➕ إضافة حلقة جديدة</h3>
            <p class="text-secondary mb-0" style="font-size: 0.85rem; font-family: 'Segoe UI', Tahoma, sans-serif;">إدخال تفاصيل الحلقة الجديدة وأهداف الحفظ والمراجعة</p>
        </div>
        <div>
            <a href="{{ route('halaqa.index') }}" class="btn btn-sm btn-light px-3 rounded-pill fw-semibold text-muted border border-light" style="font-size: 0.85rem;">🔙 العودة لإدارة الحلقة</a>
        </div>
    </div>

    <x-alert />

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 bg-white rounded-4 p-4 p-md-5" style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);">
                <form action="{{ route('halaqa.store') }}" method="post">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-dark" style="font-family: 'Cairo', sans-serif !important; font-size: 0.9rem;">اسم الحلقة</label>
                        <input type="text" name="name" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" id="name" value="{{ old('name') }}" placeholder="ادخل اسم الحلقة" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="mb-3">
                        <label for="meeting_time" class="form-label fw-bold text-dark" style="font-family: 'Cairo', sans-serif !important; font-size: 0.9rem;">وقت اللقاء</label>
                        <input type="time" name="meeting_time" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" id="meeting_time" value="{{ old('meeting_time') }}" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="mb-3">
                        <label for="min_hifz_pages" class="form-label fw-bold text-dark" style="font-family: 'Cairo', sans-serif !important; font-size: 0.9rem;">الحد الأدنى للحفظ (صفحة)</label>
                        <input type="number" name="min_hifz_pages" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" value="{{ old('min_hifz_pages') }}" min="1" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="mb-4">
                        <label for="min_muraja_pages" class="form-label fw-bold text-dark" style="font-family: 'Cairo', sans-serif !important; font-size: 0.9rem;">الحد الأدنى للمراجعة (صفحة)</label>
                        <input type="number" name="min_muraja_pages" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" value="{{ old('min_muraja_pages') }}" min="1" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-2">
                        <a href="{{ route('halaqa.index') }}" class="btn btn-light px-4 rounded-pill fw-semibold text-muted border border-light" style="font-size: 0.9rem;">إلغاء</a>
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-semibold shadow-xs" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif !important;">حفظ</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

<style>
    .form-control:focus {
        background-color: #fff !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }
</style>
@endsection