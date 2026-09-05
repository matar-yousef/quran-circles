<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'نظام إدارة حلقات القرآن الكريم' }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-emerald: #047857;
            --emerald-dark: #065f46;
            --emerald-light: #ecfdf5;
            --body-bg: #f4f6f4;
        }

        body {
            font-family: 'Cairo', sans-serif !important;
            background-color: var(--body-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #065f46 0%, #047857 100%);
            box-shadow: 0 4px 20px -2px rgba(5, 150, 105, 0.15);
            padding: 0.75rem 1.25rem;
        }

        .navbar-brand {
            font-weight: 700;
            color: #ffffff !important;
            font-size: 1.15rem;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 0.35rem;
            white-space: nowrap;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15);
        }

        .user-profile-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50rem;
            padding: 0.3rem 0.8rem 0.3rem 0.3rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        main {
            flex: 1;
        }

        .footer-custom {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.88rem;
            margin-top: auto;
        }

        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: #065f46;
                padding: 1rem;
                border-radius: 0.75rem;
                margin-top: 0.75rem;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }

            .user-profile-badge {
                width: 100%;
                justify-content: flex-start;
                border-radius: 0.5rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>

<body>

    @auth
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top">
        <div class="container-fluid px-2">

            <a class="navbar-brand d-flex align-items-center gap-2 me-0 text-decoration-none" href="{{ route('dashboard') }}">
                <div class="bg-white text-success rounded-3 shadow-sm d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 38px; height: 38px; font-size: 1.2rem;">
                    🕌
                </div>
                <span>نظام الحلقات</span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarMain">

                @php
                $myHalaqa = null;
                if (Auth::check()) {
                $myHalaqa = Auth::user()->halaqas()->first();
                }
                @endphp

                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-1 my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            📊 لوحة التحليلات
                        </a>
                    </li>

                    <li class="nav-item">
                        @if($myHalaqa)
                        <a class="nav-link {{ request()->routeIs('halaqa.show') ? 'active' : '' }}" href="{{ route('halaqa.show', $myHalaqa->id) }}">
                            📖 إدارة الحلقة
                        </a>
                        @else
                        <a class="nav-link disabled text-white-50" href="#" title="لا توجد حلقة مسندة لك حالياً">
                            📖 إدارة الحلقة
                        </a>
                        @endif
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student.*') && !request()->routeIs('student-plans*') ? 'active' : '' }}" href="{{ route('student.index') }}">
                            👨‍🎓 إدارة الطلاب
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('daily-tracking*') ? 'active' : '' }}" href="{{ route('daily-tracking.index') }}">
                            📝 المتابعة اليومية
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('student-plans*') ? 'active' : '' }}" href="{{ route('student-plans.index') }}">
                            📅 خطط الطلاب
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('exams.*') ? 'active' : '' }}" href="{{ route('exams.index') }}">
                            📋 اختبارات الطلاب
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            📊 التقارير
                        </a>
                    </li>
                </ul>

                <div class="d-flex flex-column flex-lg-row align-items-lg-center gap-3 pt-3 pt-lg-0 border-top border-lg-0 border-light border-opacity-10">

                    <div class="user-profile-badge text-white">
                        <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm flex-shrink-0" style="width: 34px; height: 34px; font-size: 0.85rem;">
                            {{ mb_substr(Auth::user()->name ?? 'م', 0, 1) }}
                        </div>
                        <div class="d-flex flex-column text-start pe-1">
                            <span class="fw-semibold lh-sm text-truncate" style="font-size: 0.85rem; max-width: 140px;">{{ Auth::user()->name ?? 'المحفّظ' }}</span>
                            <span class="text-white-50" style="font-size: 0.65rem;">مشرف / محفظ</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-light text-danger fw-semibold rounded-pill px-3 shadow-sm w-100 w-lg-auto d-flex align-items-center justify-content-center gap-1" title="تسجيل الخروج">
                            <span>خروج</span> <span>🚪</span>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>
    @endauth

    <main class="container-fluid px-3 px-md-4 py-4">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 bg-success text-white py-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-danger text-white py-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 bg-warning text-dark py-3" role="alert">
            <div class="d-flex align-items-center gap-2">
                <span>🔔</span>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>