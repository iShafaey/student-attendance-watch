<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('run:check-absence')->dailyAt('19:00');
Schedule::command('run:check-expenses-not-paid')->monthly()->when(function () {
    $day = Carbon::now()->day;
    return in_array($day, [26, 27, 28]);
});

Schedule::call(function (){
    Log::info('schedule started at ' . now());
})->everyMinute();
