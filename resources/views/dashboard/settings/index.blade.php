@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-8">
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
                            <label class="mb-2">رساله حضور الطالب</label>
                            <textarea class="form-control" name="attendance_in_message" rows="1">{{ option('attendance_in_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله إنصراف الطالب</label>
                            <textarea class="form-control" name="attendance_out_message" rows="1">{{ option('attendance_out_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله دفع الطالب المصاريف</label>
                            <textarea class="form-control" name="expenses_message" rows="1">{{ option('expenses_message') }}</textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label class="mb-2">رساله نتيجه الطالب بالامتحان</label>
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
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-class">اضف صف جديد</button>
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
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-subject">اضف مادة جديد</button>
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
    </div>
@endsection

@push('stylesheet')

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
        });
    </script>
@endpush
