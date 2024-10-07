@extends('dashboard.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-4">لوحة التحكم</h4>
        </div>
        @include('dashboard.layouts.sidebar')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>
                        الاعدادات العامة
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('generate-barcodes.generating') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="mb-2">نوع الباركود</label>
                            <input class="form-control" type="text" name="barcode_type" value="{{ option('barcode_type', 'C39') }}">
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">طباعه الباركود الان</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('stylesheet')

@endpush

@push('script')

@endpush
