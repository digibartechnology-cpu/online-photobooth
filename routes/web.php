<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Models\User;
use App\Models\Event; 

// ==========================================
// 0. JALUR BYPASS GAMBAR (SOLUSI GAMBAR RUSAK/HILANG DI CLOUD)
// ==========================================

// Bypass untuk Frame
Route::get('/render-frame/{filename}', function ($filename) {
    $path = storage_path('app/public/templates/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
});

// Bypass untuk Foto Mentahan Studio
Route::get('/render-mentahan/{filename}', function ($filename) {
    // Bersihkan teks 'mentahan/' karena ikut tersimpan di database
    $filename = str_replace('mentahan/', '', $filename);
    
    $path = storage_path('app/public/mentahan/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
})->where('filename', '.*');

// Bypass untuk Foto Antrean Cetak
Route::get('/render-print/{filename}', function ($filename) {
    $path = storage_path('app/public/ready_to_print/' . $filename);
    if (file_exists($path)) {
        return response()->file($path);
    }
    abort(404);
});


// ==========================================
// 1. ALUR TAMU / PENGUNJUNG (PUBLIK)
// ==========================================

// Halaman Utama: Pintu Masuk & Pilih Event
Route::get('/', function () {
    $events = Event::orderBy('created_at', 'desc')->get();
    return view('welcome', compact('events'));
});

// Scanner QR & Validasi Nama
Route::get('/scan', [GuestController::class, 'scan']);
Route::get('/event/{id}/scan', [GuestController::class, 'scan']);
Route::post('/event/{id}/scan-validasi', [GuestController::class, 'validasiScan']); 
Route::get('/isi-nama', [GuestController::class, 'askName']);
Route::post('/simpan-nama', [GuestController::class, 'saveName']);

Route::get('/redeem', [VoucherController::class, 'redeem'])->name('voucher.redeem');

// Rute Super Pendek untuk QR Code (Langsung tembus ke sesi acara)
Route::get('/v/{token}', function($token) {
    $voucher = \App\Models\Voucher::where('token', $token)->first();
    if (!$voucher) {
        return redirect('/')->with('error', 'Silang Merah! QR Code tidak valid.');
    }
    if ($voucher->status !== 'available') {
        return redirect('/')->with('error', 'QR Code sudah hangus / pernah digunakan!');
    }
    // Buka gembok sesi & langsung lempar ke halaman isi nama (Bypass Native Kamera)
    session(['active_token' => $token, 'event_id' => $voucher->event_id]);
    return redirect('/isi-nama');
});

// Halaman Studio / Pengambilan Foto
Route::get('/studio', function () {
    if (!session('active_token') || !session('guest_name')) {
        return redirect('/')->with('error', 'Akses ditolak. Selesaikan tahapan scan dan nama.');
    }
    return view('guest.studio'); 
})->name('photobooth.studio');

// Halaman Pratinjau & Pilihan Filter + Frame
Route::get('/filter-bingkai', function () {
    if (!session('active_token') || !session('guest_name')) {
        return redirect('/')->with('error', 'Akses ditolak.');
    }
    // Tarik data frame dan pengaturan filter dari database
    $frames = \App\Models\Frame::orderBy('created_at', 'desc')->get();
    $setting = \App\Models\FilterSetting::first();
    
    return view('guest.filter', compact('frames', 'setting'));
})->name('photobooth.filter');


// ==========================================
// 2. OTENTIKASI & JALAN PINTAS AKUN
// ==========================================
Route::get('/login', [AdminController::class, 'halamanLogin'])->name('login');
Route::post('/proses-login', [AdminController::class, 'prosesLogin']);
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Rute darurat pembuat akun (Aman dari error, bisa dibuka berkali-kali)
Route::get('/buat-akun-rahasia', function() {
    $existingUser = User::where('username', 'admin')->first();
    
    if (!$existingUser) {
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'password' => Hash::make('rahasia123'),
            'role' => 'admin'
        ]);
        return "✅ Akun admin berhasil dibuat! Silakan buka <a href='/login'><b>http://localhost:8000/login</b></a>";
    }
    
    // Jika sudah ada, langsung paksa update passwordnya biar dijamin tembus
    $existingUser->password = Hash::make('rahasia123');
    $existingUser->save();
    
    return "⚠️ Akun admin sudah ada di database, dan passwordnya telah di-reset ulang ke 'rahasia123'! Silakan langsung buka <a href='/login'><b>http://localhost:8000/login</b></a>";
});


// ==========================================
// 3. DASHBOARD ADMIN / HOME
// ==========================================
Route::get('/admin-home', [AdminController::class, 'index'])->name('admin.home');
Route::post('/admin-home/event', [AdminController::class, 'saveEvent'])->name('admin.event.save');
Route::get('/admin-home/event/delete/{id}', [AdminController::class, 'destroyEvent'])->name('admin.event.delete');
Route::post('/admin-home/generate-bulk', [AdminController::class, 'generateBulk'])->name('admin.qr.bulk');
Route::get('/admin-home/photo/{id}/{status}', [AdminController::class, 'updatePhotoStatus'])->name('admin.photo.update');

// ---- Manajemen Spesifik Event ----
Route::get('/admin-home/event/{id}/qr', [AdminController::class, 'manageQR']);
Route::post('/admin-home/event/{id}/qr/generate', [AdminController::class, 'generateEventQR']);

Route::get('/admin-home/event/{id}/scanner', [AdminController::class, 'manageScanner']);
Route::post('/admin-home/event/{id}/scan', [AdminController::class, 'processScan']);

Route::get('/admin-home/event/{id}/database', [AdminController::class, 'manageDatabase']);
Route::get('/admin-home/event/{id}/gallery', [AdminController::class, 'manageGallery']);

// ---- Menu Frame & Filter ----
Route::get('/admin-home/frame', [AdminController::class, 'manageFrame'])->name('admin.frame');
Route::post('/admin-home/frame/save', [AdminController::class, 'saveFrame']);
Route::get('/admin-home/frame/delete/{id}', [AdminController::class, 'deleteFrame']);

Route::get('/admin-home/filter', [AdminController::class, 'manageFilter'])->name('admin.filter');
Route::post('/admin-home/filter/save', [AdminController::class, 'saveFilter']);

// ---- Menu Pengaturan ----
Route::get('/admin-home/settings', [AdminController::class, 'settings'])->name('admin.settings');


// ==========================================
// 4. API / ENDPOINT BACKGROUND
// ==========================================
Route::post('/api/cetak-foto', [PhotoController::class, 'cetakKhusus']);
Route::post('/api/simpan-mentahan', [PhotoController::class, 'simpanMentahan']);