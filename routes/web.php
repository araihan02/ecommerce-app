<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any?}', function () {
    return view('welcome'); // atau nama blade utama Anda
})->where('any', '.*');
