@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <h5>المصاريف الدراسية</h5>
                    </div>
                    <div class="float-start">
                        <div class="btn-group" role="group" aria-label="Basic example" dir="ltr">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-expenses">تسجيل سداد جديد</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped dataTable" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>كود الطالب</th>
                            <th>اسم الطالب</th>
                            <th>قيمة المدفوعات</th>
                            <th>حالة الدفع</th>
                            <th>إعلام ولي الامر</th>
                            <th>الشهر</th>
                            <th>تاريخ الاضافة</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-expenses" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">تسجيل سداد جديد</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-expenses" action="{{ route('students.new-expenses') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="student_code" class="form-label">اختر الطالب</label>
                                <select name="student_id" class="form-control student_code select-plus">
                                    <option value="">آختر طالب...</option>
                                    @forelse($students as $item)
                                        <option value="{{ $item->id }}">{{ $item->student_code }} - {{ $item->fullName() }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="expenses_value" class="form-label">قيمة المدفوعات</label>
                                <input name="expenses_value" id="expenses_value" class="form-control" type="number" step="0.01" />
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                        <button onclick="$('#add-new-expenses').submit();" type="button" class="btn btn-primary">
                            حفظ البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete-expenses" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">حذف السداد</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-exam-results" action="{{ route('students.delete-expenses') }}" method="post">
                            @csrf
                            <div class="mb-3 mt-3">
                                <input type="hidden" name="id" id="exam_result_id">
                                <button type="submit" class="btn btn-danger d-block w-100">حذف السداد</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('stylesheet')

@endpush

@push('script')
    <script>
        $(document).ready(function () {
            var table = $('.dataTable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                deferRender: true, // true to faster
                stateSave: false,
                ajax: '{{ route('ajax.expenses') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'student_id', name: 'student_id'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'expenses_value', name: 'expenses_value'},
                    {data: 'paid_status', name: 'paid_status'},
                    {data: 'status', name: 'status'},
                    {data: 'month_name', name: 'month_name'},
                    {data: 'created_at', name: 'created_at'},

                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
                }
            });

            $('.dataTable tbody').on('click', 'tr', function () {
                console.log(table.row(this).data());
                var data = table.row(this).data();
                $('#exam_result_id').val(data.id);
                $('#delete-expenses').modal('show');
            });

            setInterval(function() {
                table.ajax.reload(null, false);
            }, 25000);
        });
    </script>
@endpush
