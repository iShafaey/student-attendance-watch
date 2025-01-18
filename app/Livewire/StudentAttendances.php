<?php

namespace App\Livewire;

use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\StudentClass;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class StudentAttendances extends Component {
    use LivewireAlert, WithPagination, WithoutUrlPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['scannerDetection'];
    public $tabActive = true;
    public $perPage = 10;
    public $attendanceCount = 0, $departureCount = 0, $absenceCount = 0, $currentCount = 0;
    public $filter, $currentClass, $classesFiltered = [], $classes, $currentDates;

    public function mount() {
        $this->classes = StudentClass::get()->toBase();
        $this->classesFiltered = Student::get()->pluck('id')->toArray();
        $this->currentDates = [Carbon::today(), Carbon::today()->addDay()];
    }

    public function scannerDetection($barcode) {
        $barcode = preg_replace('/[^\p{L}\p{N}\s]/u', '', $barcode);

        try {
            $student = Student::whereStudentCode($barcode)->firstOrFail();
//            $student = Student::first();

            $studentRecord = StudentRecord::where('student_id', $student->id)
                ->whereDate('attendance_in_datetime', Carbon::today())
                ->whereNull('attendance_out_datetime')
                ->first();

            if ($studentRecord) {
                // Check if the last attendance_in_datetime is within the last 15 minutes
                $lastInTime = $studentRecord->attendance_in_datetime;
                if ($lastInTime->diffInMinutes(Carbon::now()) < 15) {
                    $this->alert('warning', "لا يمكن تسجيل الإنصراف خلال 15 دقيقة من تسجيل الحضور. الدقائق المتبقية: " . intval(15 - $lastInTime->diffInMinutes(Carbon::now())) . " دقيقة ", [
                        'toast' => true,
                        'timer' => 30000,
                    ]);
                } else {
                    $studentRecord->update([
                        'attendance_out_datetime' => Carbon::now(),
                        'status' => 'pending',
                    ]);

                    $this->alert('success', "تم إنصراف الطالب <span class='text-primary'>{$student->fullName()}</span> بنجاح", [
                        'toast' => true,
                        'timer' => 30000,
                    ]);
                }
            } else {
                StudentRecord::create([
                    'student_id' => $student->id,
                    'attendance_in_datetime' => Carbon::now(),
                    'phone_number' => $student->country_code . $student->phone_number,
                ]);

                $this->alert('success', "تم حضور الطالب <span class='text-primary'>{$student->fullName()}</span> بنجاح", [
                    'toast' => true,
                    'timer' => 30000,
                ]);
            }

            $this->dispatch('run_beep_sound');

        } catch (\Throwable $th) {
            $this->alert('warning', 'لم يتم التعرف على الطالب', [
                'toast' => true,
                'timer' => 30000,
            ]);
            $this->dispatch('run_err_sound');
        }

        $this->dispatch('run_rerender_js');
    }

    public function attendances() {
        return StudentRecord::query()
            ->whereIn('student_id', $this->classesFiltered)
            ->whereBetween('updated_at', $this->currentDates)
            ->when($this->filter === 'attendance_current', function ($query) {
                return $query->whereNotNull('attendance_in_datetime')
                    ->whereNull('attendance_out_datetime');
            })
            ->when($this->filter === 'attendance_in_datetime', function ($query) {
                return $query->whereNotNull('attendance_in_datetime');
            })
            ->when($this->filter === 'attendance_out_datetime', function ($query) {
                return $query->whereNotNull('attendance_out_datetime');
            })
            ->when($this->filter === 'absence_datetime', function ($query) {
                return $query->whereNotNull('absence_datetime');
            })
            ->when(!in_array($this->filter, [
                'attendance_current',
                'attendance_in_datetime',
                'attendance_out_datetime',
                'absence_datetime'
            ]), function ($query) {
                return $query->where(function ($query) {
                    $query->whereNotNull('attendance_in_datetime')
                        ->orWhereNotNull('attendance_out_datetime')
                        ->orWhereNotNull('absence_datetime');
                });
            })
            ->orderByDesc('created_at')
            ->paginate($this->perPage);
    }

    public function getCounterInOut() {
        $records = StudentRecord::query()->whereIn('student_id', $this->classesFiltered)
            ->whereBetween('updated_at', $this->currentDates)
            ->when(!in_array($this->filter, [
                'attendance_current',
                'attendance_in_datetime',
                'attendance_out_datetime',
                'absence_datetime'
            ]), function ($query) {
                return $query->where(function ($query) {
                    $query->whereNotNull('attendance_in_datetime')
                        ->orWhereNotNull('attendance_out_datetime')
                        ->orWhereNotNull('absence_datetime');
                });
            })
            ->get();

        $this->attendanceCount = $records->whereNotNull('attendance_in_datetime')->count();

        $this->departureCount = $records->whereNotNull('attendance_out_datetime')->count();

        $this->absenceCount = $records->whereNotNull('absence_datetime')->count();

        $this->currentCount = ($this->attendanceCount - $this->departureCount);
    }

    public function makeFilter($filter) {
        $this->filter = $filter;
        if ($filter == null) {
            $this->setCurrentClass($filter);
            $this->currentDates = [Carbon::today(), Carbon::today()->addDay()];
        }
    }

    public function showAll() {
        $this->setCurrentClass(null);
        $this->currentDates = [Carbon::now()->subYears(2), Carbon::now()];
    }

    public function setCurrentClass($classId) {
        $this->currentClass = $classId;

        if (null == $classId) {
            $this->classesFiltered = Student::get()->pluck('id')->toArray();
        } else {
            $this->classesFiltered = Student::where('class', $this->currentClass)->pluck('id')->toArray();
        }

    }

    public function render() {
        $this->getCounterInOut();
        $items = $this->attendances();

        return view('livewire.student-attendances', ['items' => $items]);
    }
}
