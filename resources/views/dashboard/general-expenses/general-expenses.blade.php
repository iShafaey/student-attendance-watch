@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <h5>مصروفات عمومية</h5>
                    </div>
                    <div class="float-start">
                        <div class="btn-group" role="group" aria-label="Basic example" dir="ltr">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#new-expenses">اضف جديد</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped dataTable" style="width:100%">
                        <thead>
                        <tr>
                            <th>نوع المعاملة</th>
                            <th>القيمة</th>
                            <th>مدفوع الي</th>
                            <th>الشهر</th>
                            <th>التاريخ</th>
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
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">تسجيل مصروف جديد</h1>
                    </div>
                    <div class="modal-body">
                        <form id="add-new-expenses" action="{{ route('general-expenses.store') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="type" class="form-label">نوع المعاملة</label>
                                <select name="type" id="type" class="select-plus-tags" data-tag-max="1" multiple>
                                    @forelse($types as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="amount" class="form-label">قيمة المدفوعات</label>
                                <input name="amount" id="amount" class="form-control" type="number" step="0.01" />
                            </div>
                            <div class="mb-3">
                                <label for="creditor" class="form-label">مدفوع الي</label>
                                <select name="creditor" id="creditor" class="select-plus-tags" data-tag-max="1" multiple>
                                    @forelse($creditors as $creditor)
                                        <option value="{{ $creditor }}">{{ $creditor }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">التاريخ</label>
                                <input name="date" id="date" class="form-control" type="date" value="{{ date('Y-m-d') }}" />
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
                        <form action="{{ route('general-expenses.remove') }}" method="post">
                            @csrf
                            <div class="mb-3 mt-3">
                                <input type="hidden" name="id" id="id">
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

@push('script')
    <script>
        $(document).ready(function () {
            var table = $('.dataTable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                deferRender: true,
                stateSave: false,
                ajax: '{{ route('general-expenses.ajax') }}',
                columns: [
                    {data: 'type', name: 'type'},
                    {data: 'amount', name: 'amount'},
                    {data: 'creditor', name: 'creditor'},
                    {data: 'month_name', name: 'month_name'},
                    {data: 'date', name: 'date'},

                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
                }
            });

            $('.dataTable tbody').on('click', 'tr', function () {
                console.log(table.row(this).data());
                var data = table.row(this).data();
                $('#id').val(data.id);
                $('#delete-expenses').modal('show');
            });
        });
    </script>
@endpush
