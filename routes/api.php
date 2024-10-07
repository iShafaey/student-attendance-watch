<?php

use App\Http\Controllers\RestApi\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/v1.0'], function () {
    Route::get('/attendances/students/get-numbers', [ApiController::class, 'getNumbers']);
    Route::post('/attendances/students/update-status', [ApiController::class, 'updateStatus']);
});
