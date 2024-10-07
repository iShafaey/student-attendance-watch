<?php

use App\Http\Controllers\Dashboard\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache', function () {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    return redirect()->back();
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/settings', [HomeController::class, 'settings'])->name('settings.index');
Route::post('/settings/update', [HomeController::class, 'updateSettings'])->name('settings.update');
Route::get('/students/attendance', [HomeController::class, 'attendance'])->name('student.attendance');
Route::post('/students/new', [HomeController::class, 'newStudent'])->name('new.student');
Route::post('/students/update', [HomeController::class, 'updateStudent'])->name('update.student');
Route::get('/students/export', [HomeController::class, 'studentExport'])->name('students.export');
Route::get('/ajax/students', [HomeController::class, 'students'])->name('ajax.students');
