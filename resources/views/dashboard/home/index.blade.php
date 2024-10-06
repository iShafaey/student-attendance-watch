@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4">لوحة التحكم</h4>
        </div>
        <div class="col-md-4">
            <div class="list-group">
                <button type="button" class="list-group-item list-group-item-action"><b>اضف طالب جديد</b></button>
                <button type="button" class="list-group-item list-group-item-action active">الطلاب</button>
                <button type="button" class="list-group-item list-group-item-action">سجل الحضور</button>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">الطلاب</div>
                <div class="card-body">
                    <table class="table table-striped dataTable" style="width:100%">
                        <thead>
                        <tr>
                            <th>اسم الطالب</th>
                            <th>ولي الامر</th>
                            <th>العمر</th>
                            <th>رقم الموبايل</th>
                            <th>الصف الدراسي</th>
                            <th>المصاريف</th>
                            <th>تاريخ الانضمام</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheet')
    <style>
        .dt-paging{
            direction: ltr;
        }
    </style>
@endpush

@push('script')
    <script>
        $('.dataTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
            }
        });
    </script>
@endpush
