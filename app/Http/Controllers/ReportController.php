<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(ReportRequest $request)
    {
        $data = $this->reportService->generateReportData(Auth::user(), $request->validated());

        return view('reports.index', $data);
    }
}
