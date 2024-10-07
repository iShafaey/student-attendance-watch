<div wire:poll.5s>
    <table class="table table-striped dataTable" style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            <th>كود الطالب</th>
            <th>اسم الطالب</th>
            <th>وقت الحضور</th>
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
                <td dir="ltr">{{ $item?->attendance_datetime['time'] ?? "[غير معروف]" }}</td>
                <td>{{ $item?->attendance_datetime['date'] ?? "[غير معروف]" }}</td>
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
                <td colspan="6" class="text-center">لا يوجد سجل حضور في الوقت الحالي</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-4" dir="ltr">
        {{ $items->links() }}
    </div>
</div>
