<table class="table table-sm table-bordered">
    <thead class="table-light">
    <tr>
        <th>اسم المادة</th>
        <th>الدرجة</th>
    </tr>
    </thead>
    <tbody>
    @forelse($subjects as $item)
        <tr>
            <td><input name="subject_title[]" type="text" class="form-control text-danger" value="{{ $item->title }}" readonly></td>
            <td><input name="degree[]" type="text" class="form-control" placeholder="مثال: 15 من 25"></td>
        </tr>
    @empty
    @endforelse
    </tbody>
</table>
