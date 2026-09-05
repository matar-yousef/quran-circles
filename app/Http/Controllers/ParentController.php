<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParentLoginRequest;
use App\Services\ParentTrackingService;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    protected ParentTrackingService $trackingService;

    public function __construct(ParentTrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function showLoginForm()
    {
        return view('parent.login');
    }

    public function trackProgress(ParentLoginRequest $request)
    {
        $validated = $request->validated();
        $studentIdNumber = $validated['student_id_number'] ?? null;
        $fullName = $validated['full_name'] ?? null;
        $period = $request->input('period', 'month');

        $data = $this->trackingService->getStudentProgress($fullName, $studentIdNumber, $period);

        if (! $data) {
            return redirect()->route('parent.login')->withErrors(['msg' => 'لم يتم العثور على طالب برقم الهوية المدخل.']);
        }

        return view('parent.dashboard', $data);
    }
}
