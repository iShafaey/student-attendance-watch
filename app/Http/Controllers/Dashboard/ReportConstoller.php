<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Finance;
use App\Models\Student;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportConstoller extends Controller {
    public function index() {
        return view('dashboard.reports.index');
    }

    public function students(Request $request) {
        $dateRange = [
            Carbon::parse($request->from)->startOfDay()->toDateTimeString(),
            Carbon::parse($request->to)->endOfDay()->toDateTimeString()
        ];

        $students = Student::get();
        $studentData = Student::find($request->student_id);

        $expensesRecords = StudentRecord::with('student')
            ->whereStudentId($request->student_id)
            ->whereBetween('created_at', $dateRange)
            ->whereNotNull('expenses_datetime')
            ->select('student_id', 'status', 'phone_number', 'created_at', 'expenses_datetime', 'expenses_value', 'expenses_reminder_datetime', DB::raw('COUNT(*) as total'))
            ->groupBy('student_id', 'status', 'phone_number', 'created_at', 'expenses_datetime', 'expenses_value', 'expenses_reminder_datetime')
            ->get();

        if ($expensesRecords->sum('total') === 0) {
            $expensesRecords = [];
        }

        $attendanceRecords = StudentRecord::with('student')
            ->whereStudentId($request->student_id)
            ->whereBetween('created_at', $dateRange)
            ->whereNotNull('attendance_in_datetime')
            ->select('student_id', 'status', 'phone_number', 'created_at', 'attendance_in_datetime', 'attendance_out_datetime', DB::raw('COUNT(*) as total'))
            ->groupBy('student_id', 'status', 'phone_number', 'created_at', 'attendance_in_datetime', 'attendance_out_datetime')
            ->get();

        if ($attendanceRecords->sum('total') === 0) {
            $attendanceRecords = [];
        }

        $absenceRecords = StudentRecord::with('student')
            ->whereStudentId($request->student_id)
            ->whereBetween('created_at', $dateRange)
            ->whereNotNull('absence_datetime')
            ->select('student_id', 'status', 'phone_number', 'created_at', 'absence_datetime', DB::raw('COUNT(*) as total'))
            ->groupBy('student_id', 'status', 'phone_number', 'created_at', 'absence_datetime')
            ->get();

        if ($absenceRecords->sum('total') === 0) {
            $absenceRecords = [];
        }

        $examResultRecords = StudentRecord::with('student')
            ->whereStudentId($request->student_id)
            ->whereNotNull('exam_result_datetime')
            ->whereBetween('created_at', $dateRange)
            ->select('student_id', 'status', 'phone_number', 'created_at', 'exam_result_datetime', 'exam_result', DB::raw('COUNT(*) as total'))
            ->groupBy('student_id', 'status', 'phone_number', 'created_at', 'exam_result_datetime', 'exam_result')
            ->get();

        if ($examResultRecords->sum('total') === 0) {
            $examResultRecords = [];
        }

        return view('dashboard.reports.students', [
            'students' => $students,
            'studentData' => $studentData,
            'attendanceRecords' => $attendanceRecords,
            'absenceRecords' => $absenceRecords,
            'expensesRecords' => $expensesRecords,
            'examResultRecords' => $examResultRecords,
        ]);
    }

    public function finances(Request $request) {
        $dateRange = [
            Carbon::parse($request->from)->startOfDay()->toDateTimeString(),
            Carbon::parse($request->to)->endOfDay()->toDateTimeString()
        ];

        $expensesRecords = StudentRecord::with('student')
            ->whereBetween('created_at', $dateRange)
            ->whereNotNull('expenses_datetime')
            ->select('student_id', 'status', 'phone_number', 'created_at', 'expenses_datetime', 'expenses_value', 'expenses_reminder_datetime', DB::raw('COUNT(*) as total'))
            ->groupBy('student_id', 'status', 'phone_number', 'created_at', 'expenses_datetime', 'expenses_value', 'expenses_reminder_datetime')
            ->get();

        if ($expensesRecords->sum('total') === 0) {
            $expensesRecords = [];
        }

        $finances = Finance::whereBetween('date', $dateRange)->get();

        return view('dashboard.reports.finances', [
            'expensesRecords' => $expensesRecords,
            'finances' => $finances
        ]);
    }
}
