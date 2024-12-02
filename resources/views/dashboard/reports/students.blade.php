@extends('dashboard.layouts.layout')

@push('script')
    <script>
        $('.dataTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
            }
        });
    </script>
@endpush

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <h4>تقرير الطلاب</h4>
                    <br>
                    <div class="card">
                        <form class="card-body" method="get" dir="rtl" action="" style="text-align: right;">
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="fromDate">الطالب</label>
                                    <select class="select-plus" name="student_id">
{{--                                        <option value="*">أختر الكل</option>--}}
                                        <option selected disabled>اختر طالب...</option>
                                        @forelse($students as $student)
                                            <option value="{{ $student->id }}" {{ $student->id == request()->get('student_id') ? "selected" : "" }}>[{{ $student->student_code }}] {{ $student->fullName() }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="fromDate">من تاريخ</label>
                                    <input type="date" value="{{ request()->get('from') }}" name="from" class="form-control" id="fromDate">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="toDate">الي تاريخ</label>
                                    <input type="date" name="to" value="{{ request()->get('to') }}" class="form-control" id="toDate">
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>&nbsp;</label>
                                    <br>
                                    <button type="submit" class="btn btn-primary">عرض التقارير</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-dark">بيانات الطالب</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <th>اسم الطالب</th>
                                    <th>رقم الهاتف</th>
                                    <th>الصف</th>
                                    <th>ايام الحضور / الانصراف</th>
                                    <th>ايام الغياب</th>
                                    <th>مجموع المدفوعات</th>
                                    <th>من تاريخ</th>
                                    <th>الي تاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $studentData?->fullName() ?? "-" }}</td>
                                        <td>{{ $studentData?->phone_number ?? "-" }}</td>
                                        <td>{{ $studentData?->student_class?->title ?? "-" }}</td>
                                        <td>{{ count($attendanceRecords) }}</td>
                                        <td>{{ count($absenceRecords) }}</td>
                                        <td>{{ count($expensesRecords) > 0 ? $expensesRecords->sum('expenses_value') ?? "0" : "0" }} ج.م</td>
                                        <td>{{ request()->get('from') ?? "-" }}</td>
                                        <td>{{ request()->get('to') ?? "-" }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-success">الحضور والانصراف</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                <tr>
                                    <th>الحضور</th>
                                    <th>الانصراف</th>
                                    <th>اليوم</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendanceRecords as $item)
                                        <tr>
                                            <td>{{ $item?->attendance_in_datetime?->format('h:i A') }}</td>
                                            <td>{{ $item?->attendance_out_datetime?->format('h:i A') ?? "-" }}</td>
                                            <td>{{ $item?->created_at?->translatedFormat('l') }}</td>
                                            <td>{{ $item?->created_at?->format('Y-m-d') }}</td>
                                        </tr>
                                    @empty
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-danger">الغيابات</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>وقت تأكيد الغياب</th>
                                        <th>اليوم</th>
                                        <th>التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($absenceRecords as $item)
                                    <tr>
                                        <td>{{ $item?->absence_datetime?->format('h:i A') }}</td>
                                        <td>{{ $item?->absence_datetime?->translatedFormat('l') }}</td>
                                        <td>{{ $item?->created_at?->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-primary">المصاريف الدراسية</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                <tr>
                                    <th>القيمة الاصلية</th>
                                    <th>القيمة المدفوعة</th>
                                    <th>الفرق</th>
                                    <th>اليوم</th>
                                    <th>الشهر</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($expensesRecords as $item)
                                    <tr>
                                        <td>{{ $item?->student?->fees }}</td>
                                        <td>{{ $item?->expenses_value }}</td>
                                        <td>{{ $item?->student?->fees - $item?->expenses_value }}</td>
                                        <td>{{ $item?->expenses_datetime?->translatedFormat('l') }}</td>
                                        <td>{{ $item?->expenses_datetime?->translatedFormat('F') }}</td>
                                        <td>{{ $item?->created_at?->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-dark-emphasis">نتائج الامتحانات</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                <tr>
                                    <th>النتائج</th>
                                    <th>اليوم</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($examResultRecords as $item)
                                    <tr>
                                        <td>
                                            <div>
                                                {{ $item?->exam_result }}
                                            </div>
                                        </td>
                                        <td>{{ $item?->exam_result_datetime?->translatedFormat('l') }}</td>
                                        <td>{{ $item?->created_at?->format('Y-m-d') }}</td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
