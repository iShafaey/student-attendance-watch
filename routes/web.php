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
Route::get('/students/attendance', [HomeController::class, 'attendance'])->name('students.attendance');
Route::get('/students/exam-results', [HomeController::class, 'examResults'])->name('students.exam-results');
Route::get('/students/expenses', [HomeController::class, 'expenses'])->name('students.expenses');
Route::post('/students/new', [HomeController::class, 'newStudent'])->name('new.student');
Route::post('/students/update', [HomeController::class, 'updateStudent'])->name('update.student');
Route::post('/students/delete', [HomeController::class, 'deleteStudent'])->name('student.delete');
Route::post('/students/new-expenses', [HomeController::class, 'studentNewExpenses'])->name('students.new-expenses');
Route::get('/students/export', [HomeController::class, 'studentExport'])->name('students.export');
Route::post('/students/new-exam-results', [HomeController::class, 'newExamResults'])->name('students.new-exam-results');
Route::post('/students/delete-exam-results', [HomeController::class, 'deleteExamResults'])->name('students.delete-exam-results');
Route::post('/students/delete-expenses', [HomeController::class, 'deleteExpenses'])->name('students.delete-expenses');
Route::get('/ajax/students', [HomeController::class, 'students'])->name('ajax.students');
Route::get('/ajax/students-blacklist', [HomeController::class, 'studentsBlacklist'])->name('ajax.students-blacklist');
Route::get('/ajax/students-blacklist/remove/{phone_number}', [HomeController::class, 'removeStudentsBlacklist'])->name('ajax.students-blacklist.remove-phone-number');
Route::get('/ajax/exam-results', [HomeController::class, 'examResultsData'])->name('ajax.exam-results');
Route::get('/ajax/classes', [HomeController::class, 'classes'])->name('ajax.classes');
Route::get('/ajax/subjects', [HomeController::class, 'subjects'])->name('ajax.subjects');
Route::get('/ajax/get-subjects-render', [HomeController::class, 'getSubjectsRender'])->name('ajax.get-subjects-render');
Route::get('/ajax/expenses', [HomeController::class, 'getExpenses'])->name('ajax.expenses');
Route::post('/settings/new-class', [HomeController::class, 'newClass'])->name('settings.new-class');
Route::post('/settings/update-class', [HomeController::class, 'updateClass'])->name('settings.update-class');
Route::post('/settings/remove-class', [HomeController::class, 'removeClass'])->name('settings.remove-class');
Route::post('/settings/new-subject', [HomeController::class, 'newSubject'])->name('settings.new-subject');
Route::post('/settings/update-subject', [HomeController::class, 'updateSubject'])->name('settings.update-subject');
Route::post('/settings/remove-subject', [HomeController::class, 'removeSubject'])->name('settings.remove-subject');
