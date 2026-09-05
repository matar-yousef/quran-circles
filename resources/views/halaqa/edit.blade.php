@extends('layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f8fafc; min-height: 100vh;">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-4 rounded-4 border border-1 border-light shadow-xs">
        <div>
            <h3 class="fw-bold text-dark mb-1" style="font-family: 'Cairo', sans-serif !important;">✏️ تعديل بيانات الحلقة</h3>
            <p class="text-secondary mb-0 text-sm" style="font-family: 'Segoe UI', Tahoma, sans-serif;">تحديث اسم الحلقة، أوقات اللقاء، وأهداف الحفظ والمراجعة</p>
        </div>
        <div>
            <a href="{{ route('halaqa.show', $halaqa->id) }}" class="btn btn-sm btn-light px-3 rounded-pill fw-semibold text-muted border border-light text-xs">🔙 العودة لإدارة الحلقة</a>
        </div>
    </div>

    <x-alert />

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 bg-white rounded-4 p-3 p-md-5 shadow-xs">
                <form action="{{ route('halaqa.update', $halaqa->id) }}" method="post">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-dark font-cairo" style="font-size: 0.9rem;">اسم الحلقة</label>
                        <input type="text" name="name" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" id="name" value="{{ old('name', $halaqa->name) }}" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="mb-3">
                        <label for="meeting_time" class="form-label fw-bold text-dark font-cairo" style="font-size: 0.9rem;">وقت اللقاء</label>
                        <input type="time" name="meeting_time" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" id="meeting_time" value="{{ old('meeting_time', \Carbon\Carbon::parse($halaqa->meeting_time)->format('H:i')) }}" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="mb-3">
                        <label for="min_hifz_pages" class="form-label fw-bold text-dark font-cairo" style="font-size: 0.9rem;">الحد الأدنى للحفظ (صفحة)</label>
                        <input type="number" name="min_hifz_pages" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" value="{{ old('min_hifz_pages', $halaqa->min_hifz_pages) }}" min="1" style="font-size: 0.9rem;" required>
                    </div>

                    <div class="mb-4">
                        <label for="min_muraja_pages" class="form-label fw-bold text-dark font-cairo" style="font-size: 0.9rem;">الحد الأدنى للمراجعة (صفحة)</label>
                        <input type="number" name="min_muraja_pages" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" value="{{ old('min_muraja_pages', $halaqa->min_muraja_pages) }}" min="1" style="font-size: 0.9rem;" required>
                    </div>


                    <div class="flex items-center gap-3 justify-start">
                        <button type="submit" class="btn btn-primary px-5 rounded-pill fw-semibold shadow-xs font-cairo" style="font-size: 0.9rem; font-family: 'Cairo', sans-serif; background-color: #198754; border-color: #198754;">تحديث البيانات</button>

                        <a href=" {{ route('halaqa.show', $halaqa->id) }}" class="btn btn-outline-secondary px-4 rounded-pill fw-semibold" style="font-size: 0.9rem;">إلغاء</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .form-control {
        border: 1px solid #cbd5e1 !important;
        background-color: #ffffff !important;
    }

    .form-control:focus {
        background-color: #fff !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    @media (max-width: 768px) {
        .action-buttons .btn {
            width: 100%;
            padding-top: 10px;
            padding-bottom: 10px;
        }
    }
</style>
@endsection