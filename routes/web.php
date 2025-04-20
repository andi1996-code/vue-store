<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome'); // atau 'welcome' tergantung nama file blade kamu
})->where('any', '.*');
