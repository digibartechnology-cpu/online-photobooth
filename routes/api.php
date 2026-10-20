<?php

use App\Http\Controllers\PhotoController;

Route::post('/cetak-foto', [PhotoController::class, 'cetakKhusus']);