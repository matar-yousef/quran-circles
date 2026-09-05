<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentPlanRequest;
use App\Models\Student;
use App\Models\StudentPlan;
use Illuminate\Support\Facades\Auth;

class StudentPlanController extends Controller
{
    public function index()
    {
        $halaqaIds = Auth::user()->halaqas()->pluck('halaqa.id');
        $studentIds = Student::whereIn('halaqa_id', $halaqaIds)->pluck('id');

        $plans = StudentPlan::whereIn('student_id', $studentIds)
            ->with('student')
            ->latest()
            ->get();

        return view('student-plan.index', compact('plans'));
    }

    public function create()
    {
        $halaqaIds = Auth::user()->halaqas()->pluck('halaqa.id');

        $students = Student::whereIn('halaqa_id', $halaqaIds)
            ->whereDoesntHave('studentPlan')
            ->get();

        return view('student-plan.create', compact('students'));
    }

    public function store(StudentPlanRequest $request)
    {
        $validatedData = $request->validated();

        $halaqaIds = Auth::user()->halaqas()->pluck('id');
        $studentIds = Student::whereIn('halaqa_id', $halaqaIds)->pluck('id')->toArray();

        if (! in_array($validatedData['student_id'], $studentIds)) {
            abort(403, 'غير مسموح: هذا الطالب ليس تابعاً لحلقاتك.');
        }

        StudentPlan::create([
            'student_id' => $validatedData['student_id'],
            'plan_type' => $validatedData['plan_type'],
            'duration' => $validatedData['duration'],
            'days_per_week' => $validatedData['days_per_week'],
            'daily_hifz' => $validatedData['daily_hifz'] ?? 0,
            'daily_muraja' => $validatedData['daily_muraja'] ?? 0,
            'start_date' => $validatedData['start_date'],
        ]);

        return redirect()->route('student-plans.index')->with('success', 'تم إضافة خطة قرآنية جديدة بنجاح!');
    }

    public function show($id)
    {
        $plan = StudentPlan::with('student')->findOrFail($id);

        $this->authorize('manage', $plan);

        return view('student-plan.show', compact('plan'));
    }

    public function edit($id)
    {
        $plan = StudentPlan::findOrFail($id);

        $this->authorize('manage', $plan);

        $halaqaIds = Auth::user()->halaqas()->pluck('halaqa.id');
        $students = Student::whereIn('halaqa_id', $halaqaIds)->get();

        return view('student-plan.edit', compact('plan', 'students'));
    }

    public function update(StudentPlanRequest $request, $id)
    {
        $plan = StudentPlan::findOrFail($id);

        $this->authorize('manage', $plan);

        $validatedData = $request->validated();

        $halaqaIds = Auth::user()->halaqas()->pluck('id');
        $studentIds = Student::whereIn('halaqa_id', $halaqaIds)->pluck('id')->toArray();

        if (! in_array($validatedData['student_id'], $studentIds)) {
            abort(403, 'غير مسموح: هذا الطالب ليس تابعاً لحلقاتك.');
        }

        $plan->update([
            'student_id' => $validatedData['student_id'],
            'plan_type' => $validatedData['plan_type'],
            'duration' => $validatedData['duration'],
            'days_per_week' => $validatedData['days_per_week'],
            'start_date' => $validatedData['start_date'],
            'daily_hifz' => $validatedData['daily_hifz'] ?? 0,
            'daily_muraja' => $validatedData['daily_muraja'] ?? 0,
        ]);

        return redirect()->route('student-plans.index')->with('success', 'تم تحديث الخطة القرآنية بنجاح!');
    }

    public function destroy($id)
    {
        $plan = StudentPlan::findOrFail($id);

        $this->authorize('manage', $plan);

        $plan->delete();

        return redirect()->route('student-plans.index')->with('success', 'تم حذف الخطة القرآنية بنجاح!');
    }
}
