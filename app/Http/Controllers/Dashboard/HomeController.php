<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\StudentRecord;
use App\Models\StudentSubject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use function Symfony\Component\Translation\t;

class HomeController extends Controller {
    public function index() {
        $classes = StudentClass::get();

        return view('dashboard.home.index', [
            'classes' => $classes
        ]);
    }

    public function attendance() {
        return view('dashboard.attendance.index');
    }

    public function settings() {
        $classes = StudentClass::get();

        return view('dashboard.settings.index', [
            'classes' => $classes
        ]);
    }

    public function examResults() {
        $students = Student::get();

        return view('dashboard.exam-results.index', [
            'students' => $students
        ]);
    }

    public function expenses() {
        $students = Student::get();

        return view('dashboard.expenses.index', [
            'students' => $students
        ]);
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
            ->editColumn('class', function ($value) {
                return $value?->student_class?->title ?? "[غير موجود]";
            })
            ->addColumn('class_id', function ($value) {
                return $value->class;
            })
            ->addColumn('student_id', function ($value) {
                return $value->id . " ({$value->student_code})";
            })
            ->rawColumns(['student_name'])
            ->make(true);
    }

    public function examResultsData() {
        $data = StudentRecord::whereNotNull('exam_result_datetime')->get()->reverse();
        return Datatables::of($data)
            ->addColumn('student_id', function ($value) {
                return $value?->student?->student_code ?? "0000";
            })
            ->editColumn('student_name', function ($value) {
                return $value?->student?->fullName() ?? "[غير موجود]";
            })
            ->editColumn('created_at', function ($value) {
                return Carbon::parse($value?->created_at)->format('H:i Y-m-d');
            })
            ->editColumn('exam_result', function ($value) {
                $data = $value->exam_result;

                return <<<HTML
                    <button type="button" class="btn btn-sm btn-info" title="{$data}">
                        النتيجة
                    </button>
                HTML;

            })
            ->editColumn('status', function ($value) {
                if ($value->status == 'pending'):
                    return '<lable class="badge bg-warning">في الانتظار</lable>';
                elseif ($value->status == 'sent'):
                    return '<lable class="badge bg-success">تم الارسال</lable>';
                elseif ($value->status == 'blacklist'):
                    return '<lable class="badge bg-dark">قائمة سوداء</lable>';
                else:
                    return '<lable class="badge bg-danger">فشل الارسال</lable>';
                endif;
            })
            ->rawColumns(['created_at', 'status', 'exam_result'])
            ->make(true);
    }

    public function classes() {
        $data = StudentClass::get()->reverse();
        return Datatables::of($data)
            ->editColumn('created_at', function ($value) {
                return $value?->created_at?->format('Y-m-d H:i') ?? "[غير موجود]";
            })
            ->rawColumns(['student_name'])
            ->make(true);
    }

    public function subjects() {
        $data = StudentSubject::get()->reverse();
        return Datatables::of($data)
            ->editColumn('class_name', function ($value) {
                return $value?->student_class->title ?? "[غير موجود]";
            })
            ->editColumn('created_at', function ($value) {
                return $value?->created_at?->format('Y-m-d H:i') ?? "[غير موجود]";
            })
            ->rawColumns(['created_at'])
            ->make(true);
    }

    public function getExpenses() {
        $data = StudentRecord::whereNotNull('expenses_datetime')->orWhereNotNull('expenses_reminder_datetime')->get()->reverse();
        return Datatables::of($data)
            ->addColumn('student_id', function ($value) {
                return $value?->student?->student_code ?? "0000";
            })
            ->addColumn('paid_status', function ($value) {
                return null !== $value?->expenses_reminder_datetime ? "<span class='badge bg-danger'>تنبيه بالدفع</span>" : "<span class='badge bg-success'>تم الدفع</span>";
            })
            ->editColumn('student_name', function ($value) {
                return $value?->student?->fullName() ?? "[غير موجود]";
            })
            ->editColumn('created_at', function ($value) {
                return Carbon::parse($value?->created_at)->format('H:i Y-m-d');
            })
            ->editColumn('status', function ($value) {
                if ($value->status == 'pending'):
                    return '<lable class="badge bg-warning">في الانتظار</lable>';
                elseif ($value->status == 'sent'):
                    return '<lable class="badge bg-success">تم الارسال</lable>';
                elseif ($value->status == 'blacklist'):
                    return '<lable class="badge bg-dark">قائمة سوداء</lable>';
                else:
                    return '<lable class="badge bg-danger">فشل الارسال</lable>';
                endif;
            })
            ->rawColumns(['created_at', 'status', 'paid_status'])
            ->make(true);
    }

    public function newStudent(Request $request) {
        try {
            Student::create($request->all());
        } catch (\Exception $th) {
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
        // Generateing Students List
        $students = Student::select(['student_code', 'student_name', 'father_name', 'class', 'join_date'])->get();
        $students_list = collect($students)->map(function ($student) {
            return [
                'student_code' => $student->student_code,
                'student_name' => $student->student_name . " " . $student->father_name,
                'class_name' => $student?->student_class?->title ?? "[غير موجود]",
                'join_date' => $student->join_date,
            ];
        })->toArray();

        $data = [
            ['student_code', 'student_name', 'class', 'join_date'],
            ...$students_list
        ];

        $filename = 'students_list' . '.csv';
        $filePath = 'exports/' . $filename;

        $handle = fopen(public_path('downloads/' . $filePath), 'w');

        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);

        // Generateing Students Barcode List
        $students_list = collect($students)->map(function ($student) {
            return [
                'student_code' => $student->student_code,
                'student_name' => $student->student_name . " " . $student->father_name,
            ];
        })->toArray();

        $data = [
            ['student_code', 'student_name'],
            ...$students_list
        ];

        $filename = 'students_list_barcodes' . '.csv';
        $filePath = 'exports/' . $filename;

        $handle = fopen(public_path('downloads/' . $filePath), 'w');

        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

        foreach ($data as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);


        $directoryPath = public_path('\\downloads\\exports\\');

        exec("start explorer \"$directoryPath\"");

        return redirect()->back()->with('success', 'تم تصدير الملف بنجاح');

//        return response()->file(public_path('downloads/' . $filePath), [
//            'Content-Type' => 'text/csv',
//            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
//        ])->deleteFileAfterSend(true);
//        return response()->download(public_path('downloads/' . $filePath))->deleteFileAfterSend(true);
    }

    public function newClass(Request $request) {
        $newClass = StudentClass::create([
            'title' => $request->title,
        ]);

        foreach ($request->subjects as $subject) {
            StudentSubject::create([
                'class_id' => $newClass->id,
                'title' => $subject,
            ]);
        }

        return redirect()->back()->with('success', 'تم حفظ الصف الدراسي بنجاح');
    }

    public function updateClass(Request $request) {
        StudentClass::find($request->_id)->update([
            'title' => $request->_title,
        ]);

        return redirect()->back()->with('success', 'تم تعديل الصف الدراسي بنجاح');
    }

    public function removeClass(Request $request) {
        StudentClass::find($request->_id)->delete();
        StudentSubject::where('class_id', $request->_id)->delete();

        return redirect()->back()->with('success', 'تم حذف الصف والمواد الفرعيه الخاصه به بنجاح');
    }

    public function newSubject(Request $request) {
        StudentSubject::create($request->all());
        return redirect()->back()->with('success', 'تم حفظ المادة بنجاح');
    }

    public function updateSubject(Request $request) {
        StudentSubject::find($request->_id)->update([
            'title' => $request->_title,
            'class_id' => $request->_class_id,
        ]);

        return redirect()->back()->with('success', 'تم تعديل المادة بنجاح');
    }

    public function removeSubject(Request $request) {
        StudentSubject::find($request->_id)->delete();

        return redirect()->back()->with('success', 'تم حذف المادة بنجاح');
    }

    public function getSubjectsRender(Request $request) {
        $student = Student::find($request->value);
        $subjects = StudentSubject::whereClassId($student->class)->get()->reverse();

        return view('dashboard.home.subjects-rendered', [
            'subjects' => $subjects
        ])->render();
    }

    public function newExamResults(Request $request) {
        $data = [
            "subject_title" => $request->subject_title,
            "degree" => $request->degree
        ];

        $student_data = Student::find($request->student_id);
        $exam_result_datetime = Carbon::now();
        $exam_result = ConvertArrayToText($data);

        StudentRecord::create([
            'student_id' => $student_data->id,
            'exam_result_datetime' => $exam_result_datetime,
            'exam_result' => $exam_result,
            'phone_number' => $student_data->country_code . $student_data->phone_number,
        ]);

        return redirect()->back()->with('success', 'تم حفظ المادة بنجاح');
    }

    public function deleteExamResults(Request $request) {
        StudentRecord::find($request->id)->delete();
        return redirect()->back()->with('success', 'تم حذف النتيجه بنجاح');
    }

    public function deleteExpenses(Request $request) {
        StudentRecord::find($request->id)->delete();
        return redirect()->back()->with('success', 'تم حذف السداد بنجاح');
    }

    public function studentNewExpenses(Request $request) {
        $student_data = Student::find($request->student_id);

        StudentRecord::create([
            'student_id' => $student_data->id,
            'phone_number' => $student_data->country_code . $student_data->phone_number,
            'expenses_datetime' => Carbon::now(),
            'expenses_value' => $request->expenses_value
        ]);

        return redirect()->back()->with('success', 'تم حفظ المادة بنجاح');
    }

    public function deleteStudent(Request $request) {
        Student::find($request->id)->delete();
        return redirect()->back()->with('success', 'تم حذف الطالب بنجاح');
    }

    public function studentsBlacklist() {
        $filePath = public_path('services/whatsapp-sender/blacklist.txt');
        if (File::exists($filePath)) {
            $blacklists = File::lines($filePath)->map(function ($line) {
                return preg_replace('/^\+20/', '', $line);
            })->filter()->toArray();

            $students = Student::whereIn('phone_number', $blacklists)->get();
        } else {
            $students = [];
        }

        return Datatables::of($students)
            ->editColumn('student_name', function ($value) {
                return $value->student_name . " " . $value->father_name;
            })
            ->editColumn('class', function ($value) {
                return $value?->student_class?->title ?? "[غير موجود]";
            })
            ->addColumn('class_id', function ($value) {
                return $value->class;
            })
            ->addColumn('student_id', function ($value) {
                return $value->id . " ({$value->student_code})";
            })
            ->addColumn('actions', function ($value) {
                return '<a href="'.route('ajax.students-blacklist.remove-phone-number', [$value->country_code . $value->phone_number]).'" class="btn btn-sm btn-danger">حذف</a>';
            })
            ->rawColumns(['student_name', 'actions'])
            ->make(true);
    }

    public function removeStudentsBlacklist(Request $request, $phone_number) {
        $numberToRemove = $phone_number;
        $filePath = public_path('services/whatsapp-sender/blacklist.txt');

        if (File::exists($filePath)) {
            $blacklist = File::lines($filePath)->toArray();

            $blacklist = array_filter($blacklist, function($line) use ($numberToRemove) {
                return $line !== $numberToRemove;
            });

            File::put($filePath, implode(PHP_EOL, $blacklist) . PHP_EOL);

            return redirect()->back()->with('success', 'تم نقل الرقم الي القائمة البيضاء');
        } else {
            return redirect()->back()->with('danger', 'لا يوجد ملف يحتوي عل القائمة السوداء');
        }
    }
}
