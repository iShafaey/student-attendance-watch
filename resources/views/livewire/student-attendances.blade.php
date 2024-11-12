<div wire:poll.5s>
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
                    <b class="badge bg-primary">الحاضرين: {{ $attendanceCount }}</b>
                </td>
                <td class="bg-light">
                    <b class="badge bg-dark">المنصرفين: {{ $departureCount }}</b>
                </td>
                <td class="bg-light">
                    <b class="badge {{ $currentCount < 1 ? "bg-success" : "bg-danger" }}">الموجودين: {{ $currentCount }}</b>
                </td>
                <td class="bg-light">
                    <b class="badge bg-danger">الغائبين: {{ $absenceCount }}</b>
                </td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>
    <div class="mt-4" dir="ltr">
        {{ $items->links() }}
    </div>
</div>
