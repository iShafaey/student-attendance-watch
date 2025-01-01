@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="card mb-5">
                <div class="card-header">
                    <h5>
                        الاعدادات العامة
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="post">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله حضور الطالب:</label>
                            <textarea class="form-control" name="attendance_in_message" rows="1">{{ option('attendance_in_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله إنصراف الطالب:</label>
                            <textarea class="form-control" name="attendance_out_message" rows="1">{{ option('attendance_out_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله غياب الطالب:</label>
                            <textarea class="form-control" name="attendance_absence_message" rows="1">{{ option('attendance_absence_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رسالة تنبيه بأنه لم يتم دفع المصاريف:</label>
                            <textarea class="form-control" name="expenses_reminder_message" rows="1">{{ option('expenses_reminder_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله دفع الطالب المصاريف:</label>
                            <textarea class="form-control" name="expenses_message" rows="1">{{ option('expenses_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله نتيجه الطالب بالامتحان:</label>
                            <textarea class="form-control" name="exam_message" rows="1">{{ option('exam_message') }}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <h6 class="mb-2">
                                وقت الانتظار بين الرسائل
                                <sup>
                                    <small class="text-danger">(بالثواني)</small>
                                </sup>
                            </h6>
                            <div class="row">
                                <div class="col-auto">
                                    <label>الادني</label>
                                    <input type="number" name="delay_min" class="form-control" value="{{ option('delay_min') }}">
                                </div>
                                <div class="col-auto">
                                    <label>الاعلي</label>
                                    <input type="number" name="delay_max" class="form-control" value="{{ option('delay_max') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-header">
                    <div class="float-end">
                        <h5>الصفوف الدراسية</h5>
                    </div>
                    <div class="float-start">
                        <div class="btn-group" role="group" aria-label="Basic example" dir="ltr">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-class">اضف صف
                                جديد
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped dataTable-classes" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الصف</th>
                            <th>تاريخ الاضافة</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-header">
                    <div class="float-end">
                        <h5>المواد الدراسية</h5>
                    </div>
                    <div class="float-start">
                        <div class="btn-group" role="group" aria-label="Basic example" dir="ltr">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-subject">اضف
                                مادة جديد
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped dataTable-subjects" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المادة</th>
                            <th>الصف</th>
                            <th>تاريخ الاضافة</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-header">
                    <div class="float-end">
                        <h5>قواعد الحضور والانصراف</h5>
                    </div>
                </div>
                <div class="card-body">
                    <form id="attendance-rule" method="post" action="{{ route('attendance-rule.update') }}">
                        @csrf
                        <table class="table table-striped" style="width:100%">
                            <thead>
                            <tr>
                                <th>الصف الدراسي</th>
                                <th>السبت</th>
                                <th>الأحد</th>
                                <th>الاثنين</th>
                                <th>الثلاثاء</th>
                                <th>الاربعاء</th>
                                <th>الخميس</th>
                                <th>الجمعة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($classes as $class)
                                <tr>
                                    <td>{{ $class->title }}</td>
                                    <td><input {{ attendanceRole($class->id, "saturday") ? "checked" : "" }} name="saturday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                    <td><input {{ attendanceRole($class->id, "sunday") ? "checked" : "" }} name="sunday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                    <td><input {{ attendanceRole($class->id, "monday") ? "checked" : "" }} name="monday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                    <td><input {{ attendanceRole($class->id, "tuesday") ? "checked" : "" }} name="tuesday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                    <td><input {{ attendanceRole($class->id, "wednesday") ? "checked" : "" }} name="wednesday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                    <td><input {{ attendanceRole($class->id, "thursday") ? "checked" : "" }} name="thursday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                    <td><input {{ attendanceRole($class->id, "friday") ? "checked" : "" }} name="friday[]" value="{{ $class->id }}" type="checkbox" class="form-check-input" /></td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="$('#attendance-rule').submit();" class="btn btn-primary">حفظ البيانات</button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-class" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">اضف صف جديد</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-classes" action="{{ route('settings.new-class') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">اسم الصف</label>
                                <input name="title" id="title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="subjects" class="form-label">المواد الدراسية</label>
                                <select class="form-control select-plus-tags" name="subjects[]" id="subjects" multiple></select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                        <button onclick="$('#add-new-classes').submit();" type="button" class="btn btn-primary">
                            حفظ البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-class" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">تعديل الصف</h1>
                    </div>
                    <div class="modal-body">
                        <form id="edit-new-classes" action="{{ route('settings.update-class') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="_title" class="form-label">اسم الصف</label>
                                <input name="_title" id="_title" class="form-control">
                            </div>
                            <input type="hidden" name="_id" id="_id" value="">
                        </form>

                        <form id="remove-class" method="post" action="{{ route('settings.remove-class') }}">
                            @csrf
                            <input type="hidden" name="_id" id="_id" value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                                <button onclick="$('#edit-new-classes').submit();" type="button" class="btn btn-primary me-2">
                                    حفظ البيانات
                                </button>
                            </div>
                            <button onclick="$('#remove-class').submit();" type="button" class="btn btn-danger">
                                حذف الصف
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="new-subject" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">اضف مادة جديد</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-subject" action="{{ route('settings.new-subject') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">اسم المادة</label>
                                <input name="title" id="title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="class_id" class="form-label">اختر الصف</label>
                                <select class="form-control" name="class_id" id="class_id" required>
                                    @forelse($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->title }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                        <button onclick="$('#add-new-subject').submit();" type="button" class="btn btn-primary">
                            حفظ البيانات
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit-subject" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">تعديل المادة</h1>
                    </div>
                    <div class="modal-body">
                        <form id="update-new-subject" action="{{ route('settings.update-subject') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="_title" class="form-label">اسم المادة</label>
                                <input name="_title" id="_title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="_class_id" class="form-label">اختر الصف</label>
                                <select class="form-control" name="_class_id" id="_class_id" required>
                                    @forelse($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->title }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <input type="hidden" name="_id" id="_id" value="">
                        </form>
                        <form id="remove-subject" method="post" action="{{ route('settings.remove-subject') }}">
                            @csrf
                            <input type="hidden" name="_id" id="_id" value="">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                                <button onclick="$('#update-new-subject').submit();" type="button" class="btn btn-primary me-2">
                                    حفظ البيانات
                                </button>
                            </div>
                            <button onclick="$('#remove-subject').submit();" type="button" class="btn btn-danger">
                                حذف المادة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheet')
    <style>
        table tr td:first-child {
            background-color: #f0f8ff;
            font-weight: bold;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            let tableClasses = $('.dataTable-classes').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                deferRender: true, // true to faster
                stateSave: false,
                ajax: '{{ route('ajax.classes') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'created_at', name: 'created_at'},

                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
                }
            });

            let tableSubjects = $('.dataTable-subjects').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                deferRender: true, // true to faster
                stateSave: false,
                ajax: '{{ route('ajax.subjects') }}',
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'title', name: 'title'},
                    {data: 'class_name', name: 'class_name'},
                    {data: 'created_at', name: 'created_at'},

                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
                }
            });

            $('.dataTable-classes tbody').on('click', 'tr', function () {
                var data = tableClasses.row(this).data();

                $('#edit-new-classes #_title').val(data.title);
                $('#edit-new-classes #_id').val(data.id);
                $('#remove-class #_id').val(data.id);

                $('#edit-class').modal('show');
            });

            $('.dataTable-subjects tbody').on('click', 'tr', function () {
                var data = tableSubjects.row(this).data();

                $('#update-new-subject #_title').val(data.title);
                $('#update-new-subject #_class_id').val(data.class_id).change();
                $('#update-new-subject #_id').val(data.id);
                $('#remove-subject #_id').val(data.id);

                $('#edit-subject').modal('show');
            });
        });
    </script>
@endpush
