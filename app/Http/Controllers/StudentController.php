<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Imports\StudentsImport;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    protected StudentService $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        $userHalaqaIds = Auth::user()->halaqas()->pluck('halaqa.id');

        $query = Student::whereIn('halaqa_id', $userHalaqaIds);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('guardian_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('juz')) {
            $query->where('current_juz', $request->juz);
        }

        $students = $query->latest()->paginate(15)->withQueryString();

        return view('student.index', compact('students'));
    }

    public function create()
    {
        return view('student.create');
    }

    public function show($id)
    {
        $student = Student::findOrFail($id);

        $this->authorize('view', $student);

        $studentData = $this->studentService->getStudentDetails($id);
        $stats = $this->studentService->calculateStudentStatistics($studentData);

        return view('student.show', array_merge(['student' => $studentData], $stats));
    }

    public function store(StudentRequest $request)
    {
        $userHalaqa = Auth::user()->halaqas()->first();

        if (! $userHalaqa) {
            return redirect()->route('dashboard')->with('error', 'لا توجد حلقة مسندة لك لإضافة طالب إليها.');
        }

        $validated = $request->validated();
        $validated['halaqa_id'] = $userHalaqa->id;

        Student::create($validated);

        return redirect()->route('student.index')->with('success', 'تم إضافة الطالب بنجاح');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);

        $this->authorize('update', $student);

        return view('student.edit', compact('student'));
    }

    public function update(StudentRequest $request, $id)
    {
        $student = Student::findOrFail($id);

        $this->authorize('update', $student);

        $student->update($request->validated());

        return redirect()->route('student.index')->with('success', 'تم تعديل بيانات الطالب بنجاح');
    }

    public function destroy($id)
    {
        $student = Student::findOrFail($id);

        $this->authorize('delete', $student);

        $student->delete();

        return redirect()->route('student.index')->with('success', 'تم حذف الطالب بنجاح');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            $userHalaqa = Auth::user()->halaqas()->first();

            if (! $userHalaqa) {
                return redirect()->back()->with('error', 'لا توجد حلقة مسندة لك لاستيراد الطلاب إليها.');
            }

            Excel::import(new StudentsImport($userHalaqa->id), $request->file('file'));

            return redirect()->back()->with('success', 'تم استيراد جميع الطلاب بنجاح تام! 🎉');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء الاستيراد: ' . $e->getMessage());
        }
    }

    public function idealStudent()
    {
        $idealStudent = $this->studentService->getIdealStudent(Auth::user());

        return view('student.ideal', compact('idealStudent'));
    }
}
