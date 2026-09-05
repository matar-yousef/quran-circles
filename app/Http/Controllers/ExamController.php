<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Models\Student;

class ExamController extends Controller
{

    private function getAuthenticatedStudentIds()
    {
        $user = auth()->user();
        $halaqaIds = $user ? $user->halaqas()->pluck('halaqa.id') : collect();
        return Student::whereIn('halaqa_id', $halaqaIds)->pluck('id');
    }


    private function getAuthenticatedStudents()
    {
        $user = auth()->user();
        $halaqaIds = $user ? $user->halaqas()->pluck('halaqa.id') : collect();

        return Student::whereIn('halaqa_id', $halaqaIds)->get();
    }

    public function index()
    {
        $studentIds = $this->getAuthenticatedStudentIds();

        $exams = Exam::whereIn('student_id', $studentIds)
            ->with('student')
            ->latest()
            ->get();

        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        $students = $this->getAuthenticatedStudents();

        return view('exams.create', compact('students'));
    }

    public function store(ExamRequest $request)
    {
        Exam::create($request->validated());

        return redirect()->route('exams.index')->with('success', 'تم إضافة الاختبار بنجاح.');
    }

    public function edit($id)
    {
        $studentIds = $this->getAuthenticatedStudentIds();

        $exam = Exam::where('id', $id)
            ->whereIn('student_id', $studentIds)
            ->firstOrFail();

        $students = $this->getAuthenticatedStudents();

        return view('exams.edit', compact('exam', 'students'));
    }

    public function update(ExamRequest $request, $id)
    {
        $studentIds = $this->getAuthenticatedStudentIds();

        $exam = Exam::where('id', $id)
            ->whereIn('student_id', $studentIds)
            ->firstOrFail();

        $exam->update($request->validated());

        return redirect()->route('exams.index')->with('success', 'تم تعديل الاختبار بنجاح.');
    }

    public function destroy($id)
    {
        $studentIds = $this->getAuthenticatedStudentIds();

        $exam = Exam::where('id', $id)
            ->whereIn('student_id', $studentIds)
            ->firstOrFail();

        $exam->delete();

        return redirect()->route('exams.index')->with('success', 'تم حذف الاختبار بنجاح.');
    }
}
