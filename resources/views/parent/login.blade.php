@extends('layouts.parent-layout')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .parent-portal-wrapper {
        font-family: 'Cairo', sans-serif !important;
        background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.04), transparent 40%),
            radial-gradient(circle at bottom left, rgba(5, 150, 105, 0.03), transparent 40%);
        min-height: 82vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }

    .portal-card {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.06);
        border-radius: 1.75rem;
        width: 100%;
        max-width: 440px;
        margin: 0 auto;
    }

    .portal-input {
        background-color: #f8fafc !important;
        border: 1.5px solid #e2e8f0 !important;
        transition: all 0.25s ease;
        text-align: right !important;
        font-size: 0.95rem !important;
    }

    .portal-input:focus {
        background-color: #fff !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1) !important;
    }

    .portal-btn {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px -4px rgba(16, 185, 129, 0.4);
    }

    .portal-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 22px -4px rgba(16, 185, 129, 0.5);
    }

    @media (max-width: 576px) {
        .parent-portal-wrapper {
            padding: 0.75rem;
        }

        .portal-card {
            border-radius: 1.5rem;
        }
    }
</style>

<div class="container parent-portal-wrapper" dir="rtl">
    <div class="w-100">

        <div class="portal-card">
            <div class="card-body p-4 p-md-4">

                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 65px; height: 65px; font-size: 1.75rem;">
                        📖
                    </div>
                    <h4 class="fw-bold text-dark mb-2" style="letter-spacing: -0.5px;">بوابة متابعة ولي الأمر</h4>
                    <p class="text-muted small mb-0 px-2" style="line-height: 1.5; font-size: 0.85rem;">أدخل بيانات الطالب أدناه للاطلاع الفوري على سجل الإنجاز والحفظ</p>
                </div>

                <x-alert />

                <form action="{{ route('parent.search') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="full_name" class="form-label fw-bold text-dark small mb-2 d-block text-right">
                            <span class="text-success ms-1">👤</span> اسم الطالب الثلاثي:
                        </label>
                        <input type="text" name="full_name" id="full_name" class="form-control rounded-pill py-2.5 px-3 portal-input" placeholder="أدخل اسم الطالب تماماً كما هو مسجل" value="{{ old('full_name') }}" required>
                    </div>

                    <div class="mb-4">
                        <label for="student_id_number" class="form-label fw-bold text-dark small mb-2 d-block text-right">
                            <span class="text-success ms-1">🆔</span> رقم هوية الطالب:
                        </label>
                        <input type="text" name="student_id_number" id="student_id_number" class="form-control rounded-pill py-2.5 px-3 portal-input" placeholder="أدخل رقم الهوية (9 أرقام)" value="{{ old('student_id_number') }}" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="portal-btn btn btn-success rounded-pill py-2.5 fw-bold fs-6 text-white w-100 d-flex align-items-center justify-content-center gap-2">
                            <span>استعلام عن الإنجاز</span>
                            <span style="font-size: 1.1rem;">🔍</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>

        <div class="text-center mt-3 text-muted small" style="opacity: 0.75; font-size: 0.75rem;">
            <span>نظام إدارة الحلقات القرآنية © 2026</span>
        </div>

    </div>
</div>
@endsection