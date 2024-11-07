<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class StudentAttendances extends Component
{
    use LivewireAlert, WithPagination, WithoutUrlPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['scannerDetection'];
    public $tabActive = true;
    public $perPage = 10;
    public $attendanceCount = 0, $departureCount = 0, $currentCount = 0;

    public function scannerDetection($barcode) {
        $barcode = preg_replace('/[^\p{L}\p{N}\s]/u', '', $barcode);
        try {
            $student = Student::whereStudentCode($barcode)->firstOrFail();
//            $student = Student::first();

//            StudentAttendance::create([
//                'student_id' => $student->id,
//                'phone_number' => $student->country_code . $student->phone_number,
//                'attendance_datetime' => Carbon::now()->toDateTimeString(),
//            ]);

            $studentRecord = StudentRecord::where('student_id', $student->id)
                ->whereDate('attendance_in_datetime', Carbon::today())
                ->whereNull('attendance_out_datetime');

            if ($studentRecord->exists()) {
                $studentRecord->update([
                    'attendance_out_datetime' => Carbon::now(),
                    'status' => 'pending'
                ]);

                $this->alert('success', 'تم إنصراف الطالب بنجاح', [
                    'toast' => true
                ]);
            } else {
                StudentRecord::create([
                    'student_id' => $student->id,
                    'attendance_in_datetime' => Carbon::now(),
                    'phone_number' => $student->country_code . $student->phone_number,
                ]);

                $this->alert('success', 'تم حضور الطالب بنجاح', [
                    'toast' => true
                ]);
            }

            $this->dispatch('run_beep_sound');

        } catch (\Throwable $th){
            $this->alert('warning', 'لم يتم التعرف على الطالب', [
                'toast' => true
            ]);
            $this->dispatch('run_err_sound');
        }

        $this->dispatch('run_rerender_js');
    }

    public function attendances() {
//        return StudentAttendance::orderByDesc('created_at')->paginate($this->perPage);
        return StudentRecord::whereNotNull('attendance_in_datetime')
            ->orWhereNotNull('attendance_out_datetime')
            ->orderByDesc('created_at')
            ->paginate($this->perPage);
    }

    public function getCounterInOut() {
        $records = StudentRecord::where(function($query) {
            $query->whereNotNull('attendance_in_datetime')
                ->orWhereNotNull('attendance_out_datetime');
        })
            ->whereDate('updated_at', Carbon::now())
            ->get();

        $this->attendanceCount = $records->whereNotNull('attendance_in_datetime')->count();

        $this->departureCount = $records->whereNotNull('attendance_out_datetime')->count();

        $this->currentCount = ($this->attendanceCount - $this->departureCount);
    }

    public function render()
    {
        $this->getCounterInOut();
        $items = $this->attendances();

        return view('livewire.student-attendances', ['items' => $items]);
    }
}
