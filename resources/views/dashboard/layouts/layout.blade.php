<!doctype html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AC</title>
    <link rel="stylesheet" href="{{ asset('layouts/dashboard/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('layouts/dashboard/dataTables.bootstrap5.css') }}">
    @stack('stylesheet')
</head>
<body>

<div class="container mt-5">
    @yield('content')
</div>

<script src="{{ asset('layouts/dashboard/jquery-3.7.1.js') }}"></script>
<script src="{{ asset('layouts/dashboard/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('layouts/dashboard/dataTables.js') }}"></script>
<script src="{{ asset('layouts/dashboard/dataTables.bootstrap5.js') }}"></script>
@stack('script')
</body>
</html>
