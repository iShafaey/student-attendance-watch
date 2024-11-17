<div wire:poll.5s>
    @include('dashboard.layouts.message')

    <table class="table table-striped dataTable" style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            <th>كود الطالب</th>
            <th>اسم الطالب</th>
            <th>وقت الحضور</th>
            <th>وقت الانصراف</th>
            <th>التاريخ</th>
            <th>إعلام ولي الامر</th>
        </tr>
        </thead>
        <tbody>
        @forelse($items as $key => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item?->student?->student_code ?? "[غير معروف]" }}</td>
                <td>{{ $item?->student?->fullName() ?? "[غير معروف]" }}</td>
                @if(null != $item?->absence_datetime)
                    <td dir="rtl" colspan="2">
                        <span class="badge bg-danger w-100">لم يتم الحضور (غائب)</span>
                    </td>
                @else
                    <td dir="ltr">
                        <span class="badge bg-primary">{{ $item?->attendance_in_datetime->format('H:i A') ?? "[غير معروف]" }}</span>
                    </td>
                    <td dir="ltr">
                        <span class="badge bg-dark">{{ $item?->attendance_out_datetime ? $item?->attendance_out_datetime->format('H:i A') ?? "[غير معروف]" : "في الانتظار" }}</span>
                    </td>
                @endif
                <td>{{ $item?->created_at->format('Y-m-d') ?? "[غير معروف]" }}</td>
                <td>
                    @if($item->status == 'pending')
                        <lable class="badge bg-warning">في الانتظار</lable>
                    @elseif($item->status == 'sent')
                        <lable class="badge bg-success">تم الارسال</lable>
                    @elseif($item->status == 'blacklist')
                        <lable class="badge bg-dark">قائمة سوداء</lable>
                    @else
                        <lable class="badge bg-danger">فشل الارسال</lable>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">لا يوجد سجل حضور في الوقت الحالي</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td class="bg-light">
                <b wire:click="makeFilter('attendance_in_datetime')" class="badge bg-primary {{ $filter == "attendance_in_datetime" ? "bg-opacity-50" : "" }}">الحاضرين: {{ $attendanceCount }}</b>
            </td>
            <td class="bg-light">
                <b wire:click="makeFilter('attendance_out_datetime')" class="badge bg-dark {{ $filter == "attendance_out_datetime" ? "bg-opacity-50" : "" }}">المنصرفين: {{ $departureCount }}</b>
            </td>
            <td class="bg-light">
                <b wire:click="makeFilter('attendance_current')" class="badge {{ $currentCount < 1 ? "bg-success" : "bg-danger" }} {{ $filter == "attendance_current" ? "bg-opacity-50" : "" }}">الموجودين: {{ $currentCount }}</b>
            </td>
            <td class="bg-light">
                <b wire:click="makeFilter('absence_datetime')" class="badge bg-danger {{ $filter == "absence_datetime" ? "bg-opacity-50" : "" }}">الغائبين: {{ $absenceCount }}</b>
            </td>
            <td colspan="2"></td>
            <td rowspan="2" class="bg-light">
                <button wire:click="makeFilter(null)" type="button" class="btn btn-sm btn-primary w-100 mb-2">إفتراضي</button>
                <button wire:click="showAll" type="button" class="btn btn-sm btn-secondary w-100">اعرض الكل</button>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <div class="float-end">
                    @forelse($classes as $class)
                        <button wire:click="setCurrentClass({{ $class->id }})" type="button" class="btn btn-sm {{ $class->id == $currentClass ? "btn-info" : "btn-outline-info" }} mt-2">{{ $class->title }}</button>
                    @empty
                    @endforelse
                </div>
            </td>
            <td></td>
        </tr>
        </tfoot>
    </table>
    <div class="mt-4" dir="ltr">
        {{ $items->links() }}
    </div>
</div>
