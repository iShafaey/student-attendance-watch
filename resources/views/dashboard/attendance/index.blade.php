@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="float-end">
                        <div class="spinner-grow text-success" style="width: 15px; height: 15px;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        سجل الحضور
                    </h5>
                    <button class="btn btn-success float-start" data-bs-toggle="modal" data-bs-target="#AttendAsGroup">انصراف مجموعه</button>
                </div>
                <div class="card-body">
                    <livewire:student-attendances />

                    <div class="modal fade" id="AttendAsGroup" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5 float-end" id="staticBackdropLabel">انصراف ك مجموعة</h1>
                                </div>
                                <div class="modal-body">
                                    <form id="attendClass" action="{{ route('students.attendance.as-group') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="class" class="mb-1">اختر المجموعة</label>
                                                    <select name="class" id="class" class="form-control">
                                                        @forelse($classes as $class)
                                                            <option value="{{ $class->id }}">{{ $class->title }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-success" onclick="$('#attendClass').submit();">انصراف الكل الان</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
                                </div>
                            </div>
                        </div>
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
        // let table = $('.dataTable').DataTable({
        //     ordering: false,
        //     language: {
        //         url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/ar.json',
        //     }
        // });

        $(document).scannerDetection({
            timeBeforeScanTest: 200, // wait for the next character for upto 200ms
            avgTimeByChar: 40, // it's not a barcode if a character takes longer than 100ms
            preventDefault: false,
            endChar: [13],
            onComplete: function (barcode, qty) {
                validScan = true;
                console.log(barcode);
                Livewire.dispatch('scannerDetection', {barcode});
            },
            onError: function (string, qty) {
                console.log(string);
                // Livewire.emit('scannerDetection', string);
            }
        });

        window.addEventListener('keydown',function(e) {
            if (e.keyIdentifier=='U+000A' || e.keyIdentifier=='Enter' || e.keyCode==13) {
                if (e.target.nodeName=='INPUT' && e.target.type=='text') {
                    e.preventDefault();

                    return false;
                }
            }
        }, true);

        Livewire.on('run_err_sound', () => {
            err_sound();
        });

        Livewire.on('run_beep_sound', () => {
            beep();
        });

        document.addEventListener("visibilitychange", () => {
            if (document.hidden) {
                // $('.spinner-grow').toggleClass('text-success', 'text-danger');
            } else {
                // $('.spinner-grow').toggleClass('text-danger', 'text-success');
            }
        });
    </script>
@endpush
