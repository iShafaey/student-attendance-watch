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
        try {
            $students = Student::with('student_class:id,title')->get()->groupBy('student_class.title');
            $classes = StudentClass::get();

            view()->share([
                '_students' => $students,
                '_classes' => $classes,
            ]);
        } catch (\Throwable $th) {}

        Carbon::setLocale('ar');
    }
}
