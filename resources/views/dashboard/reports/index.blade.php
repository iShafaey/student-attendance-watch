@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        @include('dashboard.layouts.sidebar')
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-2">
                    <button onclick="window.location = '{{ route('reports.students') }}'" class="btn btn-outline-primary w-100 h-150">تقرير الطلاب</button>
                </div>
                <div class="col-md-2">
                    <button onclick="window.location = '{{ route('reports.finance') }}'" class="btn btn-outline-primary w-100 h-150">تقرير الإرادات والمصروفات</button>
                </div>
            </div>
        </div>
    </div>
@endsection
