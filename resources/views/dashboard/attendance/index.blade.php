@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>
                        <div class="spinner-grow text-success" style="width: 15px; height: 15px;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        سجل الحضور
                    </h5>
                </div>
                <div class="card-body">
                    <livewire:student-attendances />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheet')
    <style>
        .dt-paging {
            direction: ltr;
        }

        th, td {
            text-align: center !important;
            padding: 10px;
        }

        td {
            cursor: pointer;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('layouts/dashboard/jquery.scannerdetection.min.js') }}"></script>
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
