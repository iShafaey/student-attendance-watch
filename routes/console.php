<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Schedule::command('run:check-absence')->dailyAt('19:00')->withoutOverlapping();
Schedule::command('run:check-expenses-not-paid')->dailyAt("18:00")->withoutOverlapping();
Schedule::command('run:remove-old-records')->monthly("18:30")->withoutOverlapping();

//Schedule::call(function (){
//    Log::info('schedule started at ' . now());
//})->everyMinute();
