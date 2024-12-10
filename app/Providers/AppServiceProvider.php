<?php

namespace App\Providers;

use App\Models\Student;
use App\Models\StudentClass;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $students = Student::with('student_class:id,title')->get()->groupBy('student_class.title');
        $classes = StudentClass::get();

        Carbon::setLocale('ar');

        view()->share([
            '_students' => $students,
            '_classes' => $classes,
        ]);
    }
}
