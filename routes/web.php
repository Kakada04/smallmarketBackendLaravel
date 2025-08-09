<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File ;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-storage-link', function () {
    return response()->json([
        'exists' => file_exists(public_path('storage')),
        'is_link' => is_link(public_path('storage')),
        'target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
    ]);
});

Route::get('/storage/product_image/{filename}', function ($filename) {
    $path = storage_path('app/public/product_image/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    return response()->file($path);
});

Route::get('/qr-test', function () {
    return QrCode::size(200)->generate('Hello QR');
});

