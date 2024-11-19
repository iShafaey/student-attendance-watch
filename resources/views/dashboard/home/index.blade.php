@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <h5>الطلاب</h5>
                            </div>
                            <div class="float-start">
                                <div class="btn-group" role="group" aria-label="Basic example" dir="ltr">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#new_student">
                                        اضف طالب جديد
                                    </button>
                                    <a href="{{ route('students.export') }}" class="btn btn-dark" data-turbo="false" target="_self">تصدير الجميع</a>
                                    <button type="button" onclick="$('.stdExport').submit();" class="btn btn-outline-dark exportSelectedBtn" data-turbo="false" target="_self">تصدير المحدد</button>
                                    <form class="stdExport" method="post" action="{{ route('students.export') }}">
                                        @csrf
                                        <input class="studentsIds" type="hidden" name="students" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTable" style="width:100%">
                                <thead>
                                <tr>
                                    <th>تحديد</th>
                                    <th>ID</th>
                                    <th>CLASS_ID</th>
                                    <th>#</th>
                                    <th>اسم الطالب</th>
                                    <th>ولي الامر</th>
                                    <th>العمر</th>
                                    <th>الموبايل</th>
                                    <th>الصف</th>
                                    <th>المصاريف</th>
                                    <th>تاريخ الانضمام</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="float-end">
                                <h5>القائمة السوداء</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped dataTableBlacklist" style="width:100%">
                                <thead>
                                <tr>
                                    <th>اسم الطالب</th>
                                    <th>العمر</th>
                                    <th>رقم الهاتف</th>
                                    <th>الصف</th>
                                    <th>حذف من القائمة</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new_student" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">اضف طالب جديد</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add_new_student" action="{{ route('new.student') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="student_code" class="form-label">كود الطالب</label>
                                <input type="text" class="form-control" id="student_code" name="student_code" value="{{ GenerateRandomCode(7) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="student_name" class="form-label">اسم الطالب</label>
                                <input type="text" class="form-control" id="student_name" name="student_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="father_name" class="form-label">ولي الامر</label>
                                <input type="text" class="form-control" id="father_name" name="father_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">رقم الموبايل</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="age" class="form-label">العمر</label>
                                <input type="text" class="form-control" id="age" name="age" required>
                            </div>
                            <div class="mb-3">
                                <label for="class" class="form-label">الصف الدراسي</label>
                                {{--<input type="text" class="form-control" id="class" name="class" required>--}}
                                <select name="class" class="form-control select-plus" id="class" required>
                                    @forelse($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->title }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="join_date" class="form-label">تاريخ الانضمام</label>
                                <input type="date" class="form-control" id="join_date" name="join_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="fees" class="form-label">المصاريف</label>
                                <input type="number" step="0.01" class="form-control" id="fees" name="fees" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                        <button onclick="$('#add_new_student').submit();" type="button" class="btn btn-primary">
                            حفظ البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit_student" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">تحديث بيانات الطالب</h1>
                    </div>
                    <div class="modal-body">
                        <form id="update_current_student" action="{{ route('update.student') }}" method="post">
                            @csrf
                            <input type="hidden" id="student_id" name="student_id">
                            <div class="mb-3">
                                <label for="_student_code" class="form-label">كود الطالب</label>
                                <input type="text" class="form-control" id="_student_code" name="student_code" required>
                            </div>
                            <div class="mb-3">
                                <label for="_student_name" class="form-label">اسم الطالب</label>
                                <input type="text" class="form-control" id="_student_name" name="student_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="_father_name" class="form-label">ولي الامر</label>
                                <input type="text" class="form-control" id="_father_name" name="father_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="_phone_number" class="form-label">رقم الموبايل</label>
                                <input type="text" class="form-control" id="_phone_number" name="phone_number" required>
                            </div>
                            <div class="mb-3">
                                <label for="_age" class="form-label">العمر</label>
                                <input type="number" class="form-control" id="_age" name="age" required>
                            </div>
                            <div class="mb-3">
                                <label for="_class" class="form-label">الصف الدراسي</label>
                                {{--                                <input type="text" class="form-control" id="_class" name="class" required>--}}
                                <select name="class" class="form-control select-plus" id="_class" required>
                                    @forelse($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->title }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="_join_date" class="form-label">تاريخ الانضمام</label>
                                <input type="date" class="form-control" id="_join_date" name="join_date" required>
                            </div>
                            <div class="mb-3">
                                <label for="_fees" class="form-label">المصاريف</label>
                                <input type="number" step="0.01" class="form-control" id="_fees" name="fees" required>
                            </div>
                        </form>
                        <form id="delete_current_student" method="post" action="{{ route('student.delete') }}">
                            @csrf
                            <input type="hidden" name="id" id="_sid">
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                    <div class="d-flex justify-content-between m-3">
                        <div class="d-flex">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                            <button onclick="$('#update_current_student').submit();" type="button" class="btn btn-primary me-2">
                                حفظ البيانات
                            </button>
                        </div>
                        <button onclick="$('#delete_current_student').submit();" type="button" class="btn btn-danger">
                            حذف الطالب
                        </button>
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
                ajax: '{{ route('ajax.students') }}',
                columns: [
                    {data: 'checkStudent', name: 'checkStudent'},
                    {data: 'id', name: 'id', visible: false},
                    {data: 'class_id', name: 'class_id', visible: false},
                    {data: 'student_id', name: 'student_id'},
                    {data: 'student_name', name: 'student_name'},
                    {data: 'father_name', name: 'father_name'},
                    {data: 'age', name: 'age'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'class', name: 'class'},
                    {data: 'fees', name: 'fees'},
                    {data: 'join_date', name: 'join_date'},
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
                }
            });

            var tableBlacklist = $('.dataTableBlacklist').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                deferRender: true, // true to faster
                stateSave: false,
                ajax: '{{ route('ajax.students-blacklist') }}',
                columns: [
                    {data: 'student_name', name: 'student_name'},
                    {data: 'age', name: 'age'},
                    {data: 'phone_number', name: 'phone_number'},
                    {data: 'class', name: 'class'},
                    {data: 'actions', name: 'actions'},
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
                }
            });

            setInterval(function () {
                tableBlacklist.ajax.reload(null, false);
            }, 25000);

            $('.dataTable tbody').on('click', 'tr', function () {
                if ($(event.target).closest('td:first-child').find('input.form-check-input').is(event.target)) {
                    return;
                }

                console.log(table.row(this).data());
                var data = table.row(this).data();

                $('#student_id').val(data.id);
                $('#_sid').val(data.id);
                $('#_student_code').val(data.student_code);
                $('#_student_name').val(data.student_name);
                $('#_father_name').val(data.father_name);
                $('#_age').val(data.age);
                $('#_phone_number').val(data.phone_number);
                $('#_class').val(data.class_id).change();
                $('#_fees').val(data.fees);
                $('#_join_date').val(data.join_date);

                $('#edit_student').modal('show');
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.dataTable').on('change', 'input[name="check[]"]', function () {
                var selectedValues = $('.dataTable input[name="check[]"]:checked').map(function () {
                    return $(this).val();
                }).get();

                var selectedCount = $('.dataTable input[name="check[]"]:checked').length;

                $('.studentsIds').val(selectedValues.join(','));

                if (selectedCount > 0) {
                    var text = selectedCount === 1 ? 'تصدير طالب' : 'تصدير ' + selectedCount + ' طلاب';
                    $('.exportSelectedBtn').text(text);
                } else {
                    $('.exportSelectedBtn').text('تصدير المحدد');
                }

            });
        });
    </script>
@endpush
