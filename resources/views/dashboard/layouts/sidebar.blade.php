<div class="col-md-12">
    <h4 class="mb-4">لوحة التحكم</h4>
</div>
<div class="col-md-3">
    <div class="row">
        <div class="col-md-12">
            <div class="list-group">
                <a href="{{ route('home') }}" class="list-group-item list-group-item-action {{ request()->route()->named('home') ? 'active' : '' }}">الطلاب</a>
                <a href="{{ route('students.attendance') }}" class="list-group-item list-group-item-action {{ request()->route()->named('students.attendance') ? 'active' : '' }}">سجل الحضور</a>
                <a href="{{ route('students.exam-results') }}" class="list-group-item list-group-item-action {{ request()->route()->named('students.exam-results') ? 'active' : '' }}">نتائج الامتحانات</a>
                <a href="{{ route('students.expenses') }}" class="list-group-item list-group-item-action {{ request()->route()->named('students.expenses') ? 'active' : '' }}">المصاريف الدراسية</a>
                <a href="{{ route('general-expenses.index') }}" class="list-group-item list-group-item-action {{ request()->route()->named('general-expenses.index') ? 'active' : '' }}">المصروفات العمومية</a>
                <a href="{{ route('reports.index') }}" class="list-group-item list-group-item-action {{ in_array(request()->route()->getName(), ['reports.index', 'reports.students', 'reports.finance']) ? 'active' : '' }}">التقارير العامة</a>
                <a href="{{ route('settings.index') }}" class="list-group-item list-group-item-action {{ request()->route()->named('settings.index') ? 'active' : '' }}">الاعدادات العامة</a>
            </div>
        </div>
        <div class="col-md-12 mt-3">
            <div class="list-group">
                <button type="button" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#bulkMessage">
                    إرسال رسائل جماعية
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkMessage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">إرسال رسائل جماعية</h1>
            </div>
            <div class="modal-body">
                <form id="bulk-message" action="{{ route('send.bulk-message') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <select name="bulk[]" class="form-control select-plus-general" id="_class" multiple required>
                                <optgroup label="* المجموعات">
                                    @forelse($_classes as $_class)
                                        <option value="class:{{ $_class->id }}">{{ $_class->title }}</option>
                                    @empty
                                    @endforelse
                                </optgroup>
                                <optgroup label="* الطلاب حسب المجموعة">
                                @forelse($_students as $key => $_students_list)
                                    <optgroup label="&nbsp;&nbsp; [{{ $key }}]">
                                        @forelse($_students_list as $_student)
                                            <option value="student:{{ $_student?->id }}">{{ $_student?->fullName() }}</option>
                                        @empty
                                        @endforelse
                                    </optgroup>
                                    @empty
                                    @endforelse
                                    </optgroup>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label>نص الرسالة</label>
                            <input name="message" class="form-control w-100" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <button onclick="$('#bulk-message').submit();" type="button" class="btn btn-primary w-25">ارسال</button>
            </div>
        </div>
    </div>
</div>
