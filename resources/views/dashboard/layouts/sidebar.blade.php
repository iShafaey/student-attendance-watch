<div class="col-md-12">
    <h4 class="mb-4">لوحة التحكم</h4>
</div>
<div class="col-md-3">
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
