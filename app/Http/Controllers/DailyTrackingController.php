<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBatchTrackingRequest;
use App\Http\Requests\UpdateTrackingRequest;
use App\Models\DailyTracking;
use App\Models\Surah;
use App\Services\DailyTrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyTrackingController extends Controller
{
    public function __construct(protected DailyTrackingService $trackingService) {}

    public function index(Request $request)
    {
        $userHalaqa = Auth::user()->halaqas()->with('students')->first();
        $students = $userHalaqa ? $userHalaqa->students()->with('studentPlan')->get() : collect();
        $studentIds = $students->pluck('id');

        $query = DailyTracking::with(['student', 'details.surah'])
            ->whereIn('student_id', $studentIds);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $trackings = $query->latest()->paginate(15)->withQueryString();

        return view('daily-tracking.index', compact('trackings', 'students'));
    }

    public function create()
    {
        $userHalaqa = Auth::user()->halaqas()->with('students')->first();

        if (! $userHalaqa) {
            return redirect()->route('dashboard')->with('error', 'لا توجد حلقة مسندة لك حالياً.');
        }

        $students = $userHalaqa->students()->with('studentPlan')->get();

        $this->trackingService->attachLastHifzPosition($students);

        $surahs = Surah::orderBy('number')->get();

        return view('daily-tracking.batch', compact('students', 'userHalaqa', 'surahs'));
    }

    public function storeBatch(StoreBatchTrackingRequest $request)
    {
        try {
            $trackingData = $request->input('tracking', []);

            $userHalaqa = Auth::user()->halaqas()->with('students')->first();
            if (! $userHalaqa) {
                return back()->withErrors(['error' => 'لا توجد حلقة مسندة لك حالياً.']);
            }

            $allowedStudentIds = $userHalaqa->students()->pluck('id')->toArray();

            // التحقق الصارم من أن كل الطلاب ينتمون لحلقة المعلم الحالية
            foreach ($trackingData as $item) {
                $studentId = $item['student_id'] ?? null;
                if ($studentId && !in_array($studentId, $allowedStudentIds)) {
                    return back()->withInput()->withErrors(['error' => 'حاولت إدخال بيانات لطالب لا ينتمي إلى حلقتك.']);
                }
            }

            if (empty($trackingData)) {
                return back()->withErrors(['error' => 'لا توجد بيانات للحفظ.']);
            }

            $this->trackingService->validateSurahsLimits($trackingData);
            $this->trackingService->storeBatch($request->input('tracking_date'), $trackingData);

            return redirect()->route('daily-tracking.index')->with('success', 'تم حفظ متابعة الطلاب بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $dailyTracking = DailyTracking::with('details.surah')->findOrFail($id);

        $this->authorize('manage', $dailyTracking);

        return view('daily-tracking.show', compact('dailyTracking'));
    }

    public function edit($id)
    {
        $dailyTracking = DailyTracking::with('details')->findOrFail($id);

        $this->authorize('manage', $dailyTracking);

        $userHalaqa = Auth::user()->halaqas()->with('students')->first();
        $students = $userHalaqa ? $userHalaqa->students()->with('studentPlan')->get() : collect();
        $surahs = Surah::orderBy('number')->get();

        return view('daily-tracking.edit', compact('dailyTracking', 'students', 'surahs'));
    }

    public function update(UpdateTrackingRequest $request, $id)
    {
        $tracking = DailyTracking::findOrFail($id);

        $this->authorize('manage', $tracking);

        try {
            $this->trackingService->updateSingle($tracking, $request->validated());

            return redirect()->route('daily-tracking.index')->with('success', 'تم تعديل المتابعة بنجاح');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء التعديل: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $dailyTracking = DailyTracking::findOrFail($id);

        $this->authorize('manage', $dailyTracking);

        $dailyTracking->delete();

        return redirect()->route('daily-tracking.index')->with('success', 'تم الحذف بنجاح');
    }
}
