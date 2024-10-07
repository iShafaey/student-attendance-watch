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
                    <form action="{{ route('settings.update') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="mb-2">رساله إعلام ولي الامر</label>
                            <textarea class="form-control" name="message">{{ option('message') }}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <h6 class="mb-2">وقت الانتظار بين الرسائل</h6>
                            <div class="row">
                                <div class="col-auto">
                                    <label>الادني</label>
                                    <input type="number" name="delay_min" class="form-control" value="{{ option('delay_min') }}">
                                </div>
                                <div class="col-auto">
                                    <label>الاعلي</label>
                                    <input type="number" name="delay_max" class="form-control" value="{{ option('delay_max') }}">
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">حفظ البيانات</button>
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
