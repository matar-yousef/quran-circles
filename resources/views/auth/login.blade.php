@extends('layouts.master')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center min-vh-100 py-5" dir="rtl">

    <div class="text-center mb-4">
        <h2 class="fw-bold text-success">نظام حلقات القرآن</h2>
        <p class="text-muted">تسجيل الدخول للمحفظين والمشرفين</p>
    </div>

    <div class="card border-0 shadow-sm p-4 w-100" style="max-width: 480px; border-radius: 16px;">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-bold text-dark">البريد الإلكتروني</label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label fw-bold text-dark">كلمة المرور</label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" id="password" name="password" required placeholder="********">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-success btn-lg fw-bold text-white shadow-sm" style="border-radius: 10px;">
                    تسجيل الدخول
                </button>
            </div>

            <div class="text-center mt-3">
                <span class="text-muted">ليس لديك حساب؟</span>
                <a href="{{ route('register') }}" class="text-success text-decoration-none fw-bold ms-1">إنشاء حساب جديد</a>
            </div>
        </form>
    </div>
</div>
@endsection