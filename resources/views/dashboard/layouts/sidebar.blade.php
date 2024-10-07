<div class="col-md-12">
    <h4 class="mb-4">لوحة المراقبة</h4>
</div>
<div class="col-md-4">
    <div class="list-group">
        <a href="{{ route('home') }}" class="list-group-item list-group-item-action {{ request()->route()->named('home') ? 'active' : '' }}">الطلاب</a>
        <a href="{{ route('student.attendance') }}" class="list-group-item list-group-item-action {{ request()->route()->named('student.attendance') ? 'active' : '' }}">سجل الحضور</a>
        <a href="{{ route('settings.index') }}" class="list-group-item list-group-item-action {{ request()->route()->named('settings.index') ? 'active' : '' }}">الاعدادات العامة</a>
    </div>
</div>
