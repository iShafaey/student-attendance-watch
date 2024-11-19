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
                    <h4>تقرير الإرادات والمصروفات</h4>
                    <br>
                    <div class="card">
                        <form class="card-body" method="get" dir="rtl" action="" style="text-align: right;">
                            <div class="row">
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
                            <h5 class="text-dark">التفاصيل</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <th>من تاريخ</th>
                                    <th>الي تاريخ</th>
                                    <th>المدفوعات</th>
                                    <th>المصروفات</th>
                                    <th>الصافي</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>{{ request()->get('from') ?? "-" }}</td>
                                    <td>{{ request()->get('to') ?? "-" }}</td>
                                    <td>{{ count($expensesRecords) > 0 ? $expensesRecords->sum('expenses_value') ?? 0 : 0 }} ج.م</td>
                                    <td>{{ $finances->sum('amount') }} ج.م</td>
                                    <td>{{ (count($expensesRecords) > 0 ? $expensesRecords->sum('expenses_value') ?? 0 : 0) - $finances->sum('amount') }} ج.م</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="text-primary">الإرادات</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                <tr>
                                    <th>اسم الطالب</th>
                                    <th>رقم الهاتف</th>
                                    <th>الصف</th>
                                    <th>القيمة الاصلية</th>
                                    <th>القيمة المدفوعة</th>
                                    <th>الفرق</th>
                                    <th>اليوم</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($expensesRecords as $item)
                                    <tr>
                                        <td>{{ $item?->student?->fullName() }}</td>
                                        <td>{{ $item?->student?->phone_number }}</td>
                                        <td>{{ $item?->student?->student_class?->title }}</td>
                                        <td>{{ $item?->student?->fees }}</td>
                                        <td>{{ $item?->expenses_value }}</td>
                                        <td>{{ $item?->student?->fees - $item?->expenses_value }}</td>
                                        <td>{{ $item?->expenses_datetime?->translatedFormat('l') }}</td>
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
                            <h5 class="text-primary">المصروفات</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                <tr>
                                    <th>نوع المعاملة</th>
                                    <th>القيمة</th>
                                    <th>مدفوع الي</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($finances as $finance)
                                        <tr>
                                            <td>{{ $finance->type }}</td>
                                            <td>{{ $finance->amount }}</td>
                                            <td>{{ $finance->creditor }}</td>
                                            <td>{{ $finance->date }}</td>
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
