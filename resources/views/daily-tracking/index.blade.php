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

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-sm" dir="rtl">
        <div class="d-flex align-items-center gap-3">
            <div style="font-size: 2.2rem;">📝</div>
            <div>
                <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.25rem;">المتابعة اليومية</h3>
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">إدارة ومتابعة سجلات الحفظ والمراجعة للطلاب اليومية</p>
            </div>
        </div>

        <div>
            <a href="{{ route('daily-tracking.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-xs font-cairo text-nowrap" style="font-size: 0.85rem;">
                <i class="fas fa-plus ms-1"></i> تسجيل متابعة اليوم (جماعي)
            </a>
        </div>
    </div>

    <div class="card border-0 bg-white rounded-4 mb-4 shadow-sm">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('daily-tracking.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label text-muted fw-bold font-cairo" style="font-size: 0.8rem;">اسم الطالب</label>
                    <select name="student_id" class="form-select rounded-pill px-4 py-2.5 border-light bg-light-subtle" style="font-size: 0.9rem;">
                        <option value="">جميع الطلاب</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                            {{ $student->full_name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label text-muted fw-bold font-cairo" style="font-size: 0.8rem;">التاريخ</label>
                    <input type="date" name="date" class="form-control rounded-pill px-4 py-2.5 border-light bg-light-subtle" value="{{ request('date') }}" style="font-size: 0.9rem;">
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill fw-semibold shadow-xs font-cairo" style="font-size: 0.9rem;">بحث</button>
                    <a href="{{ route('daily-tracking.index') }}" class="btn btn-light rounded-pill px-3 fw-semibold text-muted border border-light" style="font-size: 0.9rem;">إعادة ضبط</a>
                </div>
            </form>
        </div>
    </div>

    <div class="d-md-none mb-4">
        @forelse($trackings as $index => $item)
        @php
        $presentVal = $item->is_present ?? $item->status ?? 1;
        $hifzDetails = $item->details->where('type', 'hifz');
        $murajaDetails = $item->details->where('type', 'muraja');
        @endphp
        <div class="card border-0 shadow-sm bg-white rounded-4 p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                <span class="fw-bold text-dark font-cairo fs-6">
                    <span class="badge bg-light text-secondary border me-1">#{{ $trackings->firstItem() ? $trackings->firstItem() + $index : $index + 1 }}</span>
                    {{ $item->student->full_name ?? '-' }}
                </span>
                <div>
                    @if($presentVal == 1 || $presentVal == 'حاضر')
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">حاضر</span>
                    @elseif($presentVal == 0 || $presentVal == 'غائب')
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">غائب</span>
                    @else
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">مستأذن</span>
                    @endif
                </div>
            </div>

            <div class="mb-3 font-system small bg-light-subtle p-3 rounded-4 border border-light">
                <div class="mb-2">
                    <span class="text-muted fw-bold d-block mb-1" style="font-size: 0.75rem;">📖 الحفظ:</span>
                    @if($hifzDetails->count() > 0)
                    @foreach($hifzDetails as $detail)
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 rounded-pill mb-1" style="font-size: 0.75rem;">
                        {{ optional($detail->surah)->name }} (آية {{ $detail->from_ayah ?? '-' }} - {{ $detail->to_ayah ?? '-' }})
                    </span>
                    @endforeach
                    @else
                    <span class="text-muted fst-italic" style="font-size: 0.8rem;">لم يُسمع</span>
                    @endif
                </div>

                <div class="mb-2">
                    <span class="text-muted fw-bold d-block mb-1" style="font-size: 0.75rem;">🔄 المراجعة:</span>
                    @if($murajaDetails->count() > 0)
                    @foreach($murajaDetails as $detail)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill mb-1" style="font-size: 0.75rem;">
                        {{ optional($detail->surah)->name }} (آية {{ $detail->from_ayah ?? '-' }} - {{ $detail->to_ayah ?? '-' }})
                    </span>
                    @endforeach
                    @else
                    <span class="text-muted fst-italic" style="font-size: 0.8rem;">لم يُسمع</span>
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-light">
                    <span class="text-muted" style="font-size: 0.75rem;">التاريخ: {{ $item->date ?? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</span>
                    <div>
                        <span class="text-muted ms-1" style="font-size: 0.75rem;">التقييم:</span>
                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">{{ $item->rating ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-2 border-top border-light">
                <a href="{{ route('daily-tracking.edit', $item->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">تعديل</a>
                <form action="{{ route('daily-tracking.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">حذف</button>
                </form>
            </div>
        </div>
        @empty
        <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center text-muted">
            لا توجد سجلات متابعة مطابقة لشروط البحث.
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
                            <th class="py-3">التاريخ</th>
                            <th class="py-3 text-start pe-4">اسم الطالب</th>
                            <th class="py-3">الحضور</th>
                            <th class="py-3 text-start">الحفظ (السور والآيات)</th>
                            <th class="py-3 text-start">المراجعة (السور والآيات)</th>
                            <th class="py-3">التقييم</th>
                            <th class="py-3">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trackings as $index => $item)
                        @php
                        $presentVal = $item->is_present ?? $item->status ?? 1;
                        $hifzDetails = $item->details->where('type', 'hifz');
                        $murajaDetails = $item->details->where('type', 'muraja');
                        @endphp
                        <tr class="border-light">
                            <td class="text-muted px-3" style="font-size: 0.75rem;">{{ $trackings->firstItem() ? $trackings->firstItem() + $index : $index + 1 }}</td>
                            <td class="text-muted" style="font-size: 0.85rem;">{{ $item->date ?? \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
                            <td class="fw-bold text-dark text-start pe-4" style="font-size: 0.9rem;">{{ $item->student->full_name ?? '-' }}</td>

                            <td>
                                @if($presentVal == 1 || $presentVal == 'حاضر')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">حاضر</span>
                                @elseif($presentVal == 0 || $presentVal == 'غائب')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">غائب</span>
                                @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill" style="font-size: 0.75rem;">مستأذن</span>
                                @endif
                            </td>

                            <td class="text-start">
                                @if($hifzDetails->count() > 0)
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($hifzDetails as $detail)
                                    <li class="py-0.5">
                                        <strong class="text-primary">{{ optional($detail->surah)->name }}</strong>
                                        <span class="text-muted" style="font-size: 0.8rem;">({{ $detail->from_ayah ?? '-' }} إلى {{ $detail->to_ayah ?? '-' }})</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <span class="text-muted small fst-italic">لم يُسمع</span>
                                @endif
                            </td>

                            <td class="text-start">
                                @if($murajaDetails->count() > 0)
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($murajaDetails as $detail)
                                    <li class="py-0.5">
                                        <strong class="text-success">{{ optional($detail->surah)->name }}</strong>
                                        <span class="text-muted" style="font-size: 0.8rem;">({{ $detail->from_ayah ?? '-' }} إلى {{ $detail->to_ayah ?? '-' }})</span>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <span class="text-muted small fst-italic">لم يُسمع</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border px-3 py-1 rounded-pill" style="font-size: 0.75rem;">{{ $item->rating ?? '-' }}</span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('daily-tracking.edit', $item->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">تعديل</a>
                                    <form action="{{ route('daily-tracking.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-semibold shadow-xs" style="font-size: 0.75rem;">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-muted py-5" style="font-size: 0.9rem;">لا توجد سجلات متابعة مطابقة لشروط البحث.</td>
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
                عرض <span class="fw-bold text-dark">{{ $trackings->firstItem() ?? 0 }}</span>
                إلى <span class="fw-bold text-dark">{{ $trackings->lastItem() ?? 0 }}</span>
                من إجمالي <span class="fw-bold text-dark">{{ $trackings->total() }}</span> سجل
            </div>
            <div class="custom-pagination">
                {{ $trackings->links('pagination::bootstrap-5') }}
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
                if (!confirm('هل أنت متأكد من رغبتك في حذف سجل المتابعة هذا؟ لا يمكن التراجع عن هذا الإجراء.')) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection