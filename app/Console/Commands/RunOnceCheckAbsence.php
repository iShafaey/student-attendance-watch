<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RunOnceCheckAbsence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:check-absence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute check absence every day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentAttendanceRecords = StudentRecord::whereNotNull('attendance_in_datetime')->whereDate('created_at', Carbon::today())->get()->pluck('student_id')->toArray();
        $absenceStudents = Student::whereNotIn('id', $studentAttendanceRecords)->get();

        foreach ($absenceStudents as $absenceStudent) {
            StudentRecord::create([
                'student_id' => $absenceStudent->id,
                'absence_datetime' => Carbon::today(),
                'status' => 'pending',
                'phone_number' => $absenceStudent->country_code . $absenceStudent->phone_number,
            ]);
        }
    }
}
