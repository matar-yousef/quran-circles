<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DailyTrackingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\HalaqaController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/halaqa/create', [HalaqaController::class, 'create'])->name('halaqa.create');
    Route::post('/halaqa', [HalaqaController::class, 'store'])->name('halaqa.store');
});

Route::middleware(['auth', 'has.halaqa'])->group(function () {

    Route::resource('halaqa', HalaqaController::class)->except(['index', 'create', 'store']);

    Route::resource('student', StudentController::class);

    Route::post('daily-tracking/batch', [DailyTrackingController::class, 'storeBatch'])->name('daily-tracking.batch.store');
    Route::resource('daily-tracking', DailyTrackingController::class)->except(['show']);

    Route::resource('student-plan', StudentPlanController::class)->names('student-plans');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('exams', ExamController::class);

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::post('/students/import', [StudentController::class, 'importExcel'])->name('students.import');
    Route::get('/students/ideal', [StudentController::class, 'idealStudent'])->name('students.ideal');
});

Route::prefix('parent')->name('parent.')->group(function () {
    Route::get('/login', [ParentController::class, 'showLoginForm'])->name('login');

    Route::post('/search', [ParentController::class, 'trackProgress'])
        ->middleware('throttle:5,1')
        ->name('search');
});
