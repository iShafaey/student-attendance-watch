<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\StudentAttendance;
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

    public function scannerDetection($barcode) {
        $barcode = preg_replace('/[^\p{L}\p{N}\s]/u', '', $barcode);
        $searchTerm = "%" . $barcode . "%";
        try {
            $student = Student::whereStudentCode($barcode)->firstOrFail();
//            $student = Student::inRandomOrder()->first();

            StudentAttendance::create([
                'student_id' => $student->id,
                'phone_number' => $student->country_code . $student->phone_number,
                'attendance_datetime' => Carbon::now()->toDateTimeString(),
            ]);

            $this->alert('success', 'تم حضور الطالب بنجاح', [
                'toast' => true
            ]);

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
        return StudentAttendance::orderByDesc('created_at')->paginate($this->perPage);
    }

    public function render()
    {
        $items = $this->attendances();

        return view('livewire.student-attendances', ['items' => $items]);
    }
}
