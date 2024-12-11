@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <h5>نتائج الامتحانات</h5>
                    </div>
                    <div class="float-start">
                        <div class="btn-group" role="group" aria-label="Basic example" dir="ltr">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-exam-results">اضف نتيجه جديدة</button>
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
                            <th>المواد</th>
                            <th>إعلام ولي الامر</th>
                            <th>الشهر</th>
                            <th>تاريخ الاضافة</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-exam-results" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">اضف نتيجه جديدة</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-exam-results" action="{{ route('students.new-exam-results') }}" method="post">
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
                                <label for="date" class="form-label">الشهر</label>
                                <input name="date" id="date" class="form-control" type="month" value="{{ date('m') }}"/>
                            </div>
                            <div class="mb-3">
                                <h5>المواد الدراسية</h5>
                                <div class="d-block subjects"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                        <button onclick="$('#add-new-exam-results').submit();" type="button" class="btn btn-primary">
                            حفظ البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete-exam-results" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">حذف النتيجة</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-exam-results" action="{{ route('students.delete-exam-results') }}" method="post">
                            @csrf
                            <div class="mb-3 mt-3">
                                <input type="hidden" name="id" id="exam_result_id">
                                <button type="submit" class="btn btn-danger d-block w-100">حذف النتيجة</button>
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
                ajax: '{{ route('ajax.exam-results') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'student_id', name: 'student_id'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'exam_result', name: 'exam_result'},
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
                $('#delete-exam-results').modal('show');
            });

            setInterval(function() {
                table.ajax.reload(null, false);
            }, 25000);

            $('.student_code').on('change', function() {
                let selectedValue = $(this).val();
                console.log(selectedValue);
                fetchSubjectsData(selectedValue);
            });

            function fetchSubjectsData(selectedValue) {
                if (selectedValue) {
                    $.ajax({
                        url: '{{ route('ajax.get-subjects-render') }}',
                        method: 'GET',
                        data: { value: selectedValue },
                        success: function(response) {
                            $('.subjects').html(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                } else {
                    $('.subjects').empty();
                }
            }

            $(document).on('show.bs.modal', function() {
                $('.subjects').empty();
                // $('.student_code').val($('.student_code option:first').val()).trigger('change');
            });
        });
    </script>
@endpush
