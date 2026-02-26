<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\WorshipController;
use App\Http\Controllers\CongregationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
    Route::get('/change-password', [HomeController::class, 'changePassword'])->name('home.change-password');
    Route::post('/change-password/save', [HomeController::class, 'savePassword'])->name('home.change-password.save');
    Route::get('/reset-password', [HomeController::class, 'resetPassword'])->name('home.reset-password');
    Route::post('/reset-password/reset', [HomeController::class, 'reset'])->name('home.reset-password.reset');

    Route::prefix('kpi')->group(function () {
        Route::get('/now', [KpiController::class, 'now'])->name('kpi.now');
        Route::get('/absent', [KpiController::class, 'absent'])->name('kpi.absent');
        Route::get('/spirit', [KpiController::class, 'spirit'])->name('kpi.spirit');
        Route::get('/report', [KpiController::class, 'report'])->name('kpi.report');
        Route::get('/status', [KpiController::class, 'status'])->name('kpi.status');
        Route::get('/identity', [KpiController::class, 'identity'])->name('kpi.identity');
        Route::get('/inactive', [KpiController::class, 'inactive'])->name('kpi.inactive');
        Route::get('/member/{member_id}', [KpiController::class, 'member'])->name('kpi.member');
        Route::get('/notentered', [KpiController::class, 'notentered'])->name('kpi.notentered');
    });

    Route::prefix('worship')->middleware('group.leader')->group(function () {
        Route::get('/', [WorshipController::class, 'index'])->name('worship.index');
        Route::get('/{report_id}/attendance', [WorshipController::class, 'showAttendance'])->name('worship.attendance');
        Route::get('/{report_id}/spirituality', [WorshipController::class, 'showSpirituality'])->name('worship.spirituality');
        Route::post('/{report_id}/save', [WorshipController::class, 'save'])->name('worship.save');
    });

    Route::prefix('congregation')->group(function () {
        Route::get('/', [CongregationController::class, 'index'])->name('congregation.index');
        Route::get('/regist', [CongregationController::class, 'regist'])->name('congregation.regist');
        Route::post('/regist/store', [CongregationController::class, 'store'])->name('congregation.store');
        Route::get('/{member_id}', [CongregationController::class, 'show'])->name('congregation.show');
        Route::post('/{member_id}/save', [CongregationController::class, 'save'])->name('congregation.save');
    });
});

require __DIR__.'/auth.php';
