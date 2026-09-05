@extends('layouts.master')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;" dir="rtl">

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mb-4 p-4 bg-white rounded-4 border border-1 border-light shadow-xs">
        <div class="d-flex align-items-center gap-3">
            <div style="font-size: 2.2rem;">📋</div>
            <div>
                <h3 class="fw-bold text-dark mb-1 font-cairo" style="font-size: 1.25rem;">سجل اختبارات الطلاب</h3>
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">إدارة ومتابعة اختبارات الحفظ (المنفردة والمجتمعة) ونتائج الطلاب</p>
            </div>
        </div>
        <div>
            <a href="{{ route('exams.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-xs font-cairo text-nowrap" style="font-size: 0.85rem;">
                <i class="fas fa-plus ms-1"></i> تسجيل اختبار جديد
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-xs mb-4 text-sm" role="alert">
        <i class="fas fa-check-circle ms-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($exams->isEmpty())
    <div class="card border-0 shadow-xs my-5 text-center py-5 bg-white rounded-4">
        <div class="card-body">
            <div class="mb-3 display-1 text-muted opacity-50">
                📝
            </div>
            <h4 class="fw-bold text-dark mb-2 font-cairo">لا توجد اختبارات مسجلة حالياً</h4>
            <p class="text-secondary max-w-md mx-auto mb-4 text-sm">
                قم بتسجيل اختبارات الطلاب (منفرد أو مجتمع) لتظهر نتائجهم وأجزائهم في التقرير الشهري.
            </p>
            <a href="{{ route('exams.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-semibold shadow-xs font-cairo">
                ➕ تسجيل أول اختبار
            </a>
        </div>
    </div>
    @else
    <div class="card border-0 shadow-xs bg-white rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center font-system exam-table">
                    <thead>
                        <tr>
                            <th class="py-3 px-3 text-muted bg-table-header">#</th>
                            <th class="py-3 text-start pe-4 text-muted bg-table-header">اسم الطالب</th>
                            <th class="py-3 text-muted bg-table-header">نوع الاختبار</th>
                            <th class="py-3 text-muted bg-table-header">الأجزاء / السور</th>
                            <th class="py-3 text-muted bg-table-header">الدرجة</th>
                            <th class="py-3 text-muted bg-table-header">تاريخ الاختبار</th>
                            <th class="py-3 px-3 text-muted bg-table-header">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr class="border-light transition-all">
                            <td class="text-muted text-xs px-3 fw-semibold" data-label="الرقم:">{{ $loop->iteration }}</td>
                            <td class="fw-bold text-dark text-start pe-4 text-sm" data-label="اسم الطالب:">
                                {{ $exam->student->full_name ?? '-' }}
                            </td>
                            <td data-label="نوع الاختبار:">
                                @if($exam->exam_type == 'single')
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-3 py-1.5 rounded-pill text-xs fw-semibold">منفرد (جزء واحد)</span>
                                @else
                                <span class="badge bg-warning-subtle text-dark border border-warning-subtle px-3 py-1.5 rounded-pill text-xs fw-semibold">مجتمع (3 أجزاء فأكثر)</span>
                                @endif
                            </td>
                            <td class="text-secondary text-xs fw-semibold" data-label="الأجزاء / السور:">
                                <span class="text-dark">{{ $exam->parts_number }}</span>
                            </td>
                            <td data-label="الدرجة:">
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold text-xs">{{ $exam->grade }}</span>
                            </td>
                            <td class="text-secondary text-xs" data-label="تاريخ الاختبار:">
                                {{ $exam->exam_date }}
                            </td>
                            <td data-label="الإجراءات:" class="px-3">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('exams.edit', $exam->id) }}" class="btn btn-sm btn-outline-primary px-3 py-1.5 rounded-pill fw-semibold text-xs shadow-xs">✏️ تعديل</a>
                                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الاختبار؟');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-1.5 rounded-pill fw-semibold text-xs shadow-xs">🗑️ حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
    .font-cairo {
        font-family: 'Cairo', sans-serif !important;
    }

    .font-system {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .shadow-xs {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
    }

    .text-xs {
        font-size: 0.75rem !important;
    }

    .bg-table-header {
        background-color: #f8fafc !important;
        font-size: 0.8rem;
        letter-spacing: 0.3px;
        border-bottom: 1px solid #e2e8f0;
    }

    .table> :not(caption)>*>* {
        padding: 1rem 0.75rem;
    }

    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    @media (max-width: 768px) {
        .exam-table thead {
            display: none;
        }

        .exam-table,
        .exam-table tbody,
        .exam-table tr,
        .exam-table td {
            display: block;
            width: 100%;
        }

        .exam-table tr {
            background: #fff;
            margin-bottom: 12px;
            padding: 14px;
            border-radius: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid #edf2f7;
        }

        .exam-table td {
            text-align: right !important;
            padding: 8px 0 !important;
            border: none !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .exam-table td::before {
            content: attr(data-label);
            font-weight: bold;
            color: #718096;
            font-size: 0.8rem;
            font-family: 'Cairo', sans-serif;
        }

        .exam-table td:last-child {
            justify-content: center;
            margin-top: 10px;
            padding-top: 12px !important;
            border-top: 1px dashed #edf2f7 !important;
        }

        .exam-table td:last-child::before {
            display: none;
        }
    }
</style>
@endsection