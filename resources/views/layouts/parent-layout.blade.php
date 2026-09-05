<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة ولي الأمر - نظام الحلقات</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif !important;
            background-color: #f4f6f4;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar-custom {
            background: linear-gradient(135deg, #eaf4ef 0%, #d8ede3 100%);
            border-bottom: 1px solid #bce1cd;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .navbar-brand {
            font-weight: 700;
            color: #0f172a !important;
            font-size: 1.25rem !important;
        }

        .footer-custom {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 0.88rem;
            margin-top: auto;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center gap-3 m-0 text-decoration-none" href="#">
                <span class="bg-success text-white p-2.5 rounded-3 fs-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">📖</span>
                <span>بوابة متابعة الطلاب <span class="text-success fw-bold">لأولياء الأمور</span></span>
            </a>

            <div class="d-flex align-items-center ms-auto">
                @if(Route::has('parent.logout'))
                <form action="{{ route('parent.logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger rounded-pill px-4 py-2 fw-semibold d-flex align-items-center gap-2 bg-white shadow-sm">
                        <span>🚪</span> تسجيل الخروج
                    </button>
                </form>
                @endif
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>