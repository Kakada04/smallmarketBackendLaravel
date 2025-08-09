<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
Route::get('/', function () {
    return view('welcome');
});



Route::get('/qr-test', function () {
    return QrCode::size(200)->generate('Hello QR');
});

