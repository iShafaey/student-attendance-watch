<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckExpensesNotPaid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:check-expenses-not-paid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute check expenses not paid';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentAttendanceRecords = StudentRecord::whereNotNull('expenses_datetime')->whereMonth('created_at', Carbon::now()->month)->get()->pluck('student_id')->toArray();
        $absenceStudents = Student::whereNotIn('id', $studentAttendanceRecords)->get();

        foreach ($absenceStudents as $absenceStudent) {
            StudentRecord::create([
                'student_id' => $absenceStudent->id,
                'expenses_reminder_datetime' => Carbon::today(),
                'status' => 'pending',
                'phone_number' => $absenceStudent->country_code . $absenceStudent->phone_number,
                'expenses_value' => $absenceStudent->fees,
            ]);
        }
    }
}
