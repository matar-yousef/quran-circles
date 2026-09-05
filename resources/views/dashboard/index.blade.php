@extends('layouts.master')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4" style="background-color: #f7f9fc; min-height: 100vh;">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3 bg-white p-3 rounded-4 shadow-sm border border-light">
        <div>
            <h3 class="fw-bold text-dark mb-1 font-cairo">📊 لوحة التحليلات والإحصائيات</h3>
            <p class="text-muted small mb-0 font-system">نظرة عامة ومباشرة على أدوات الحلقة وإنجازات الطلاب</p>
        </div>

        <div class="btn-group shadow-sm rounded-pill p-1 bg-light border" role="group">
            <a href="{{ route('dashboard', ['filter' => 'day']) }}" class="btn btn-sm px-3 rounded-pill fw-bold font-system {{ $filter == 'day' ? 'btn-primary text-white shadow-sm' : 'text-secondary border-0 bg-transparent' }}">اليوم</a>
            <a href="{{ route('dashboard', ['filter' => 'week']) }}" class="btn btn-sm px-3 rounded-pill fw-bold font-system {{ $filter == 'week' ? 'btn-primary text-white shadow-sm' : 'text-secondary border-0 bg-transparent' }}">هذا الأسبوع</a>
            <a href="{{ route('dashboard', ['filter' => 'month']) }}" class="btn btn-sm px-3 rounded-pill fw-bold font-system {{ $filter == 'month' ? 'btn-primary text-white shadow-sm' : 'text-secondary border-0 bg-transparent' }}">هذا الشهر</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('student.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm border-start border-primary border-4 rounded-4 p-3 bg-white card-hover h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <span class="text-muted small fw-bold d-block font-system mb-1">إجمالي الطلاب</span>
                            <h3 class="fw-bold text-dark mb-0 mt-1 dashboard-num">{{ $totalStudents ?? 0 }}</h3>
                            <small class="text-primary fw-semibold font-system" style="font-size: 0.75rem;">عرض قائمة الطلاب 👈</small>
                        </div>
                        <div class="fs-2 text-primary bg-primary-subtle p-3 rounded-4 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">👨‍🎓</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('daily-tracking.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm border-start border-success border-4 rounded-4 p-3 bg-white card-hover h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <span class="text-muted small fw-bold d-block font-system mb-1">نسبة الحضور</span>
                            <h3 class="fw-bold text-success mb-0 mt-1 dashboard-num">{{ $attendancePercentage ?? 0 }}%</h3>
                            <small class="text-muted font-system" style="font-size: 0.75rem;">نسبة الغياب: <span class="text-danger fw-semibold">{{ $absencePercentage ?? 0 }}%</span></small>
                        </div>
                        <div class="fs-2 text-success bg-success-subtle p-3 rounded-4 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">📅</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('daily-tracking.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm border-start border-info border-4 rounded-4 p-3 bg-white card-hover h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <span class="text-muted small fw-bold d-block font-system mb-1">إنجاز الحفظ</span>
                            <h3 class="fw-bold text-info mb-0 mt-1 dashboard-num">{{ $totalHifzPages ?? 0 }} <span class="fs-6 fw-normal text-muted font-system">صفحة</span></h3>
                            <small class="text-info fw-semibold font-system" style="font-size: 0.75rem;">إجمالي محفوظات الفترة</small>
                        </div>
                        <div class="fs-2 text-info bg-info-subtle p-3 rounded-4 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">📖</div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-xl-3">
            <a href="{{ route('daily-tracking.index') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm border-start border-warning border-4 rounded-4 p-3 bg-white card-hover h-100">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-start">
                            <span class="text-muted small fw-bold d-block font-system mb-1">إنجاز المراجعة</span>
                            <h3 class="fw-bold text-warning mb-0 mt-1 dashboard-num">{{ $totalReviewPages ?? 0 }} <span class="fs-6 fw-normal text-muted font-system">صفحة</span></h3>
                            <small class="text-warning fw-semibold font-system" style="font-size: 0.75rem;">إجمالي مراجعات الفترة</small>
                        </div>
                        <div class="fs-2 text-warning bg-warning-subtle p-3 rounded-4 d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">🔄</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3 font-cairo">🎯 حالة إنجاز الطلاب للفترة المحددة</h5>
                <div class="d-flex justify-content-around text-center gap-3">
                    <div class="p-3 bg-light rounded-4 w-50 border border-light-subtle d-flex flex-column justify-content-center">
                        <span class="text-muted small d-block font-system mb-1">أتموا المطلوب</span>
                        <h3 class="fw-bold text-success mb-0 dashboard-num">{{ $achievedCount ?? 0 }}</h3>
                    </div>
                    <div class="p-3 bg-light rounded-4 w-50 border border-light-subtle d-flex flex-column justify-content-center">
                        <span class="text-muted small d-block font-system mb-1">لم يتموا المطلوب</span>
                        <h3 class="fw-bold text-danger mb-0 dashboard-num">{{ $notAchievedCount ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
                <h5 class="fw-bold text-dark mb-3 font-cairo">⭐ تقييم الحلقة السائد</h5>
                <div class="d-flex align-items-center justify-content-center h-75 py-2">
                    <h3 class="fw-bold text-primary mb-0 bg-light px-5 py-3 rounded-4 border w-100 text-center font-cairo shadow-sm">
                        {{ $mostFrequentRating ?? 'لا يوجد' }}
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4 class="fw-bold text-dark mb-0 font-cairo fs-5">📊 التحليلات البيانية للحلقة</h4>
        </div>

        <div class="col-xl-8 mb-3 mb-xl-0">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <h6 class="fw-bold text-dark mb-4 font-cairo">📈 مستوى إنجاز الحفظ اليومي (مقارنة صفحات الحفظ المسجلة خلال الأسبوع)</h6>

                @php
                $pagesArray = $dailyPagesData ?? [0,0,0,0,0,0,0];
                $maxPage = max(array_merge($pagesArray, [5]));
                $daysArray = $weekDays ?? ['السبت', 'الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
                @endphp

                <div class="d-flex align-items-end justify-content-around bg-light p-3 rounded-4 overflow-x-auto border border-light-subtle" style="height: 230px; min-width: 280px;">
                    @foreach($daysArray as $index => $day)
                    @php
                    $val = $pagesArray[$index] ?? 0;
                    $heightPercent = ($val / $maxPage) * 100;
                    $barHeight = $heightPercent > 10 ? $heightPercent : 10;
                    @endphp
                    <div class="d-flex flex-column align-items-center h-100 justify-content-end w-100 mx-1">
                        <span class="small fw-bold text-primary mb-1 font-system" style="font-size: 11px;">{{ $val }} صفحة</span>
                        <div class="w-100 rounded-top bg-primary transition-all shadow-sm" style="height: {{ $barHeight }}%; min-height: 8px; opacity: {{ $val > 0 ? '1' : '0.3' }};"></div>
                        <span class="small text-muted mt-2 fw-semibold font-system" style="font-size: 12px; white-space: nowrap;">{{ $day }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100">
                <h6 class="fw-bold text-dark mb-4 font-cairo">🎯 نسبة توزيع تقييمات الطلاب</h6>

                <div class="d-flex flex-column justify-content-center gap-3">
                    @if(!empty($ratingLabels) && count($ratingLabels) > 0)
                    @php
                    $totalRatingsSum = array_sum($ratingValues ?? [1]);
                    if($totalRatingsSum == 0) $totalRatingsSum = 1;
                    @endphp
                    @foreach($ratingLabels as $index => $label)
                    @php
                    $count = $ratingValues[$index] ?? 0;
                    $percent = round(($count / $totalRatingsSum) * 100);
                    $colors = ['bg-success', 'bg-primary', 'bg-warning', 'bg-info', 'bg-danger', 'bg-secondary'];
                    $barColor = $colors[$index % count($colors)];
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between small fw-bold mb-1 font-system">
                            <span>{{ $label }}</span>
                            <span class="text-muted">{{ $count }} طالب (<span class="dashboard-num">{{ $percent }}%</span>)</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 8px; background-color: #f1f5f9;">
                            <div class="progress-bar {{ $barColor }} rounded-pill" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center text-muted py-5 font-system small">
                        لا توجد تقييمات مسجلة للفترة الحالية
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h4 class="fw-bold text-dark mb-0 font-cairo fs-5">⚠️ تنبيهات الحلقة</h4>
        </div>

        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 border-top border-warning border-4">
                <h6 class="fw-bold text-dark mb-3 font-cairo">الطلاب المتوقفون عن الحفظ (أكثر من أسبوع)</h6>
                @if(isset($inactiveStudents) && $inactiveStudents->count() > 0)
                <ul class="list-group list-group-flush font-system">
                    @foreach($inactiveStudents as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="fw-semibold">{{ $student->full_name }}</span>
                        <span class="badge bg-warning-subtle text-warning fw-bold px-3 py-1 rounded-pill">متوقف</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-3 text-muted small font-system">
                    👍 جميع الطلاب متفاعلون بالحفظ
                </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 bg-white rounded-4 h-100 border-top border-danger border-4">
                <h6 class="fw-bold text-dark mb-3 font-cairo">الطلاب الأكثر غياباً (خلال آخر 7 أيام)</h6>
                @if(isset($frequentAbsentees) && $frequentAbsentees->count() > 0)
                <ul class="list-group list-group-flush font-system">
                    @foreach($frequentAbsentees as $student)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                        <span class="fw-semibold">{{ $student->full_name }}</span>
                        <span class="badge bg-danger-subtle text-danger fw-bold px-3 py-1 rounded-pill">متكرر الغياب</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="text-center py-3 text-muted small font-system">
                    👍 لا يوجد طلاب متكرري الغياب
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark mb-0 font-cairo fs-5">📝 متابعة اليوم السريعة <span class="text-muted fw-normal fs-6 font-system">({{ \Carbon\Carbon::today()->format('Y-m-d') }})</span></h4>
        </div>

        <div class="col-12">
            @forelse($todayStudents ?? [] as $index => $student)
            @php
            $todayTrack = $student->daily_tracking->first();
            @endphp

            <div class="card border-0 shadow-sm bg-white rounded-4 p-3 mb-3 d-md-none">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom border-light">
                    <span class="fw-bold text-dark font-cairo">
                        <span class="badge bg-light text-secondary border me-1">#{{ $index + 1 }}</span>
                        {{ $student->full_name }}
                    </span>
                    <div>
                        @if($todayTrack)
                        <span class="badge {{ $todayTrack->is_present ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-1 rounded-pill fw-semibold">
                            {{ $todayTrack->is_present ? 'حاضر' : 'غائب' }}
                        </span>
                        @else
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill">لم يتم الرصد</span>
                        @endif
                    </div>
                </div>

                <div class="mb-2 font-system small">
                    <span class="text-muted d-block mb-1 fw-bold">مواضع التسميع:</span>
                    <div class="bg-light p-2 rounded-3">
                        @if($todayTrack)
                        @php $hasContent = false; @endphp

                        @if($todayTrack->details && $todayTrack->details->count() > 0)
                        @foreach($todayTrack->details as $detail)
                        @php $hasContent = true; @endphp
                        <div class="mb-1">
                            <span class="badge bg-primary text-white px-2 py-0.5 rounded-2" style="font-size: 10px;">
                                {{ $detail->type == 'hifz' ? 'حفظ' : 'مراجعة' }}
                            </span>
                            <span class="text-secondary" style="font-size: 0.85rem;">
                                {{ $detail->surah->name ?? 'سورة ' . $detail->surah_id }}
                                آية {{ $detail->from_ayah }}
                                @if($detail->to_surah_id && $detail->to_surah_id != $detail->surah_id)
                                إلى {{ $detail->toSurah->name ?? 'سورة ' . $detail->to_surah_id }}
                                @endif
                                إلى آية {{ $detail->to_ayah }}
                            </span>
                        </div>
                        @endforeach
                        @endif

                        @if(!$hasContent && (!empty($todayTrack->hifz_surah) || !empty($todayTrack->hifz_from_ayah)))
                        @php $hasContent = true; @endphp
                        <div>
                            <span class="badge bg-primary text-white px-2 py-0.5 rounded-2" style="font-size: 10px;">حفظ</span>
                            <span class="text-secondary" style="font-size: 0.85rem;">
                                سورة {{ $todayTrack->hifz_surah }} آية {{ $todayTrack->hifz_from_ayah }} إلى آية {{ $todayTrack->hifz_to_ayah }}
                            </span>
                        </div>
                        @endif

                        @if(!$hasContent) <span class="text-muted">-</span> @endif
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-light">
                    <div>
                        <span class="text-muted small fw-bold">التقييم: </span>
                        @if($todayTrack && $todayTrack->rating)
                        <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-bold">{{ $todayTrack->rating }}</span>
                        @else
                        <span class="text-muted small">-</span>
                        @endif
                    </div>
                    <div>
                        @if($todayTrack)
                        <a href="{{ route('daily-tracking.edit', $todayTrack->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold" style="font-size: 0.8rem;">تعديل</a>
                        @else
                        <a href="{{ route('daily-tracking.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-success px-3 rounded-pill fw-semibold" style="font-size: 0.8rem;">تسميع</a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center text-muted d-md-none">
                لا يوجد طلاب مسجلين في هذه الحلقة حالياً.
            </div>
            @endforelse

            <div class="card border-0 shadow-sm bg-white rounded-4 overflow-hidden d-none d-md-block">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 text-center font-system">
                        <thead class="table-light text-secondary font-cairo">
                            <tr>
                                <th class="py-3">#</th>
                                <th class="py-3 text-start pe-4">اسم الطالب</th>
                                <th class="py-3">حالة الحضور</th>
                                <th class="py-3">مواضع التسميع</th>
                                <th class="py-3">التقييم</th>
                                <th class="py-3">إجراء سريع</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayStudents ?? [] as $index => $student)
                            @php
                            $todayTrack = $student->daily_tracking->first();
                            @endphp
                            <tr>
                                <td class="text-muted">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark text-start pe-4">{{ $student->full_name }}</td>
                                <td>
                                    @if($todayTrack)
                                    <span class="badge {{ $todayTrack->is_present ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-1 rounded-pill fw-semibold">
                                        {{ $todayTrack->is_present ? 'حاضر' : 'غائب' }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill">لم يتم الرصد</span>
                                    @endif
                                </td>
                                <td>
                                    @if($todayTrack)
                                    @php $hasContent = false; @endphp

                                    @if($todayTrack->details && $todayTrack->details->count() > 0)
                                    @foreach($todayTrack->details as $detail)
                                    @php $hasContent = true; @endphp
                                    <div class="mb-1">
                                        <span class="badge bg-primary text-white px-2 py-1 rounded-2" style="font-size: 10px;">
                                            {{ $detail->type == 'hifz' ? 'حفظ' : 'مراجعة' }}
                                        </span>
                                        <span class="small text-secondary">
                                            {{ $detail->surah->name ?? 'سورة ' . $detail->surah_id }}
                                            آية {{ $detail->from_ayah }}
                                            @if($detail->to_surah_id && $detail->to_surah_id != $detail->surah_id)
                                            إلى {{ $detail->toSurah->name ?? 'سورة ' . $detail->to_surah_id }}
                                            @endif
                                            إلى آية {{ $detail->to_ayah }}
                                        </span>
                                    </div>
                                    @endforeach
                                    @endif

                                    @if(!$hasContent && (!empty($todayTrack->hifz_surah) || !empty($todayTrack->hifz_from_ayah)))
                                    @php $hasContent = true; @endphp
                                    <div class="mb-1">
                                        <span class="badge bg-primary text-white px-2 py-1 rounded-2" style="font-size: 10px;">حفظ</span>
                                        <span class="small text-secondary">
                                            سورة {{ $todayTrack->hifz_surah }} آية {{ $todayTrack->hifz_from_ayah }} إلى آية {{ $todayTrack->hifz_to_ayah }}
                                        </span>
                                    </div>
                                    @endif

                                    @if(!$hasContent) - @endif
                                    @else - @endif
                                </td>
                                <td>
                                    @if($todayTrack && $todayTrack->rating)
                                    <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill fw-bold">{{ $todayTrack->rating }}</span>
                                    @else - @endif
                                </td>
                                <td>
                                    @if($todayTrack)
                                    <a href="{{ route('daily-tracking.edit', $todayTrack->id) }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold" style="font-size: 0.8rem;">تعديل</a>
                                    @else
                                    <a href="{{ route('daily-tracking.create', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-success px-3 rounded-pill fw-semibold" style="font-size: 0.8rem;">تسميع</a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-muted py-4">لا يوجد طلاب مسجلين في هذه الحلقة حالياً.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .font-cairo,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    th {
        font-family: 'Cairo', sans-serif !important;
    }

    .font-system,
    body,
    p,
    span,
    small,
    td,
    .btn,
    .badge {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
    }

    .dashboard-num {
        font-family: 'Cairo', sans-serif !important;
        letter-spacing: -0.5px;
    }

    .card-hover {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .card-hover:hover {
        transform: translateY(-4px);
        box-shadow: 0 .75rem 1.5rem rgba(0, 0, 0, .08) !important;
    }

    .transition-all {
        transition: height 0.4s ease-in-out;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 10px !important;
            padding-right: 10px !important;
        }
    }
</style>
@endsection