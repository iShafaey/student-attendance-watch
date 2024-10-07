<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function Symfony\Component\Translation\t;

class HomeController extends Controller
{
    public function index(){
        return view('dashboard.home.index');
    }

    public function attendance(){
        return view('dashboard.attendance.index');
    }

    public function settings(){
        return view('dashboard.settings.index');
    }

    public function updateSettings(Request $request) {
        option($request->except('_token'));
        return redirect()->back()->with('success', 'تم تحديث الاعدادات بنجاح');
    }

    public function students() {
        $data = Student::get()->reverse();
        return Datatables::of($data)
            ->editColumn('student_name', function ($value) {
                return $value->student_name;
            })
            ->addColumn('student_id', function ($value) {
                return $value->id . " ({$value->student_code})";
            })
            ->rawColumns(['student_name'])
            ->make(true);
    }

    public function newStudent(Request $request) {
        try {
            Student::create($request->all());
        }catch (\Exception $th) {
            return redirect()->route('home')->with('error', $th->getMessage());
        }

        return redirect()->route('home')->with('success', 'تم اضافه الطالب بنجاح');
    }

    public function updateStudent(Request $request) {
        try {
            Student::find($request->student_id)->update($request->all());
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }

        return redirect()->back()->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function studentExport() {
        $students = Student::select(['student_code', 'student_name', 'father_name', 'class', 'join_date'])->get()->toArray();
        $students_list = collect($students)->map(function ($student) {
            return [
                'student_code' => $student['student_code'],
                'student_name' => $student['student_name'] . ' ' . $student['father_name'],
                'class' => $student['class'],
                'join_date' => $student['join_date'],
            ];
        })->toArray();

        $data = [
            ['كود الطالب', 'اسم الطالب', 'الصف', 'تاريخ الانضمام'],
            ...$students_list
        ];

        $filename = 'students.xls';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        foreach ($data as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
