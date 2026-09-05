<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        $userHalaqa = Auth::user()->halaqas()->first();

        if (! $userHalaqa) {
            return redirect()->route('halaqa.create')->with('warning', 'يرجى إنشاء الحلقة الخاصة بك أولاً...');
        }

        $filter = $request->get('filter', 'day');
        $data = $this->dashboardService->getDashboardData($userHalaqa, $filter);

        return view('dashboard.index', $data);
    }
}
