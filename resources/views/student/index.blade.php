@extends('layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    @if(session('success'))
    <div class="alert alert-success border-0 rounded-4 shadow-xs mb-4 p-3 bg-success-subtle text-success" style="font-family: 'Segoe UI', Tahoma, sans-serif; font-size: 0.9rem;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger border-0 rounded-4 shadow-xs mb-4 p-3 bg-danger-subtle text-danger" style="font-family: 'Segoe UI', Tahoma, sans-serif; font-size: 0.9rem;">
        {{ session('error') }}
    </div>
    @endif

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-4 rounded-4 border border-1 border-light shadow-sm">
        <div>
            <h3 class="fw-bold text-dark mb-1 font-cairo">👨‍🎓 إدارة الطلاب</h3>
            <p class="text-secondary mb-0" style="font-size: 0.85rem; font-family: 'Segoe UI', Tahoma, sans-serif;">عرض وإدارة بيانات الطلاب، البحث، وتصدير أو استيراد السجلات</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" id="importForm" style="display: none;">
                @csrf
                <input type="file" name="file" id="excelFileinput" accept=".xlsx, .xls, .csv" onchange="document.getElementById('importForm').submit()">
            </form>

            <button type="button" class="btn btn-sm btn-success px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.85rem;" onclick="document.getElementById('excelFileinput').click();">
                <i class="fas fa-file-excel"></i> استيراد Excel
            </button>
            <a href="{{ route('student.create') }}" class="btn btn-sm btn-primary px-3 rounded-pill fw-semibold shadow-xs font-cairo" style="font-size: 0.85rem;">
                ➕ إضافة طالب جديد
            </a>
            <a href="{{ route('students.ideal') }}" class="btn btn-sm btn-warning px-3 rounded-pill fw-bold text-dark shadow-xs" style="font-size: 0.85rem;">
                🏆 الطالب المثالي للشهر
            </a>
        </div>
    </div>

    <div class="card border-0 bg-white rounded-4 mb-4 shadow-sm">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('student.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted fw-bold font-cairo" style="font-size: 0.8rem;">بحث باسم الطالب أو رقم الجوال</label>
                    <input type="text" name="search" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" placeholder="ابحث هنا..." value="{{ request('search') }}" style="font-size: 0.9rem;">
                </div>

                <div class="col-md-4">
                    <label class="form-label text-muted fw-bold font-cairo" style="font-size: 0.8rem;">الجزء الحالي</label>
                    <select name="juz" class="form-select rounded-pill px-4 py-2.5 border-light bg-light-subtle" style="font-size: 0.9rem;">
                        <option value="">جميع الأجزاء</option>
                        @for($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}" {{ request('juz') == $i ? 'selected' : '' }}>الجزء {{ $i }}</option>
                            @endfor
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-semibold shadow-xs font-cairo" style="font-size: 0.9rem;">بحث</button>
                    <a href="{{ route('student.index') }}" class="btn btn-light rounded-pill px-3 fw-semibold text-muted border border-light" style="font-size: 0.9rem;">إعادة ضبط</a>
                </div>
            </form>
        </div>
    </div>

    <div class="d-md-none mb-4">
        @forelse($students as $index => $student)
        <div class="card border-0 shadow-sm bg-white rounded-4 p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                <span class="fw-bold text-dark font-cairo fs-6">
                    <span class="badge bg-light text-secondary border me-1">#{{ $students->firstItem() ? $students->firstItem() + $index : $index + 1 }}</span>
                    {{ $student->full_name }}
                </span>
                <div>
                    <span class="badge bg-light text-dark border px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
                        الصف: {{ $student->grade ?? '-' }}
                    </span>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 font-system small">
                <div>
                    <span class="text-muted d-block mb-1">الجزء الحالي:</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
                        الجزء {{ $student->current_juz ?? '-' }}
                    </span>
                </div>
                <div class="text-start">
                    <span class="text-muted d-block mb-1">جوال ولي الأمر:</span>
                    <span dir="ltr" class="fw-semibold text-secondary" style="font-size: 0.85rem;">{{ $student->guardian_phone ?? '-' }}</span>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2 border-top border-light">
                <a href="{{ route('student.show', $student->id) }}" class="btn btn-sm btn-info text-white px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">
                    👁️ التفاصيل
                </a>
                <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">تعديل</a>
                <form action="{{ route('student.destroy', $student->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">حذف</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center text-muted">
            لا يوجد طلاب مطابقون لشروط البحث.
        </div>
        @endforelse
    </div>

    <div class="card border-0 bg-white rounded-4 overflow-hidden shadow-sm d-none d-md-block mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0 text-center font-system">
                    <thead class="table-light text-secondary font-cairo" style="font-size: 0.75rem;">
                        <tr>
                            <th class="py-3 px-3 text-muted">#</th>
                            <th class="py-3 text-start pe-4">اسم الطالب</th>
                            <th class="py-3">الصف</th>
                            <th class="py-3">الجزء الحالي</th>
                            <th class="py-3">جوال ولي الأمر</th>
                            <th class="py-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $index => $student)
                        <tr class="border-light">
                            <td class="text-muted px-3" style="font-size: 0.75rem;">{{ $students->firstItem() ? $students->firstItem() + $index : $index + 1 }}</td>
                            <td class="fw-bold text-dark text-start pe-4" style="font-size: 0.9rem;">{{ $student->full_name }}</td>
                            <td>
                                <span class="badge bg-light text-dark border px-3 py-1 rounded-pill" style="font-size: 0.75rem;">{{ $student->grade ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">
                                    الجزء {{ $student->current_juz ?? '-' }}
                                </span>
                            </td>
                            <td dir="ltr" class="text-center text-secondary" style="font-size: 0.85rem;">{{ $student->guardian_phone ?? '-' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('student.show', $student->id) }}" class="btn btn-sm btn-info text-white px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">
                                        👁️ التفاصيل
                                    </a>
                                    <a href="{{ route('student.edit', $student->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">تعديل</a>
                                    <form action="{{ route('student.destroy', $student->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-muted py-5" style="font-size: 0.9rem;">لا يوجد طلاب مطابقون لشروط البحث.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 bg-white rounded-4 shadow-sm py-3 px-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="text-muted" style="font-size: 0.85rem; font-family: 'Segoe UI', Tahoma, sans-serif;">
                عرض <span class="fw-bold text-dark">{{ $students->firstItem() ?? 0 }}</span>
                إلى <span class="fw-bold text-dark">{{ $students->lastItem() ?? 0 }}</span>
                من إجمالي <span class="fw-bold text-dark">{{ $students->total() }}</span> طالب
            </div>
            <div class="custom-pagination">
                {{ $students->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>

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
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    }

    .custom-pagination nav>div:first-child,
    .custom-pagination nav p,
    .custom-pagination .text-muted.small {
        display: none !important;
    }

    .custom-pagination .pagination {
        margin: 0;
        gap: 3px;
        direction: rtl;
    }

    .custom-pagination .page-item .page-link {
        border-radius: 50rem !important;
        color: #495057;
        padding: 6px 14px;
        font-size: 0.85rem;
        border: 1px solid #dee2e6;
    }

    .custom-pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: #fff;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('هل أنت متأكد من رغبتك في حذف هذا الطالب؟ لا يمكن التراجع عن هذا الإجراء.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection