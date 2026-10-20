<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Voucher;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminController
{
    public function index()
    {
        // Mengambil semua kegiatan terbaru
        $events = Event::orderBy('created_at', 'desc')->get();
        return view('admin.home', compact('events'));
    }

    public function saveEvent(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_buka' => 'required',
            'waktu_tutup' => 'required',
        ]);

        Event::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_buka' => $request->waktu_buka,
            'waktu_tutup' => $request->waktu_tutup,
        ]);

        return back()->with('success', 'Jadwal kegiatan berhasil disimpan!');
    }
    
    // Fungsi delete event (opsional untuk tombol tong sampah)
    public function destroyEvent($id)
    {
        Event::findOrFail($id)->delete();
        return back();
    }

    // ==========================================
    // MENU KELOLA HALAMAN EVENT
    // ==========================================

    public function manageQR($id)
    {
        $event = Event::findOrFail($id);
        
        // Menarik data tiket/voucher permanen dari database
        $vouchers = Voucher::where('event_id', $id)
                        ->where('status', 'available')
                        ->get();

        return view('admin.manage_qr', compact('event', 'vouchers'));
    }

    public function manageScanner($id)
    {
        $event = Event::findOrFail($id);
        // Memanggil file UI manage_scanner.blade.php
        return view('admin.manage_scanner', compact('event'));
    }

    // Fungsi Endpoint API untuk memvalidasi hasil scan kamera
    public function processScan(Request $request, $id)
    {
        $token = $request->token;
        // Cari voucher berdasarkan token DAN harus cocok dengan event_id acara ini
        $voucher = Voucher::where('token', $token)->where('event_id', $id)->first();

        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'Silang Merah! QR Code ini bukan untuk acara ini.']);
        }
        if ($voucher->status !== 'available') {
            return response()->json(['status' => 'error', 'message' => 'QR Code sudah hangus / pernah dicetak!']);
        }

        // Buka gembok sesi studio untuk tamu
        session(['active_token' => $token, 'event_id' => $id]);

        return response()->json(['status' => 'success']);
    }

    public function manageDatabase($id)
    {
        $event = Event::findOrFail($id);
        
        // Tarik foto HANYA yang berasal dari event ini
        $photos = \App\Models\Photo::where('event_id', $id)->orderBy('created_at', 'desc')->get();
        
        $list = $photos->where('status', 'pending');
        $ongoing = $photos->where('status', 'progress');
        $done = $photos->where('status', 'completed');

        // Memanggil file UI manage_database.blade.php beserta variabel antreannya
        return view('admin.manage_database', compact('event', 'list', 'ongoing', 'done'));
    }

    // ==========================================
    // LOGIKA GENERATE QR KHUSUS EVENT
    // ==========================================

    public function generateEventQR(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $jumlah = $request->jumlah ?? 1;

        // Looping untuk membuat voucher secara permanen di Database
        for ($i = 0; $i < $jumlah; $i++) {
            $token = Str::random(10);
            
            // Menyimpan voucher dengan ikatan event_id
            Voucher::create([
                'token' => $token, 
                'status' => 'available', 
                'quota' => 1,
                'event_id' => $event->id // Pastikan kolom ini sudah ada di tabel vouchers
            ]);
        }

        // Kembali ke halaman manageQR dengan pesan sukses
        return back()->with('success', "$jumlah Tiket baru berhasil dibuat dan disimpan permanen!");
    }

    // ==========================================
    // MENU FRAME & FILTER
    // ==========================================

    public function manageFrame()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        // Tarik data frame dari database untuk ditampilkan ke galeri
        $frames = \App\Models\Frame::orderBy('created_at', 'desc')->get(); 
        return view('admin.frame', compact('events', 'frames'));
    }

    // Fungsi baru untuk menghapus frame
    public function deleteFrame($id)
    {
        $frame = \App\Models\Frame::findOrFail($id);
        
        // Hapus file PNG dari folder public/templates (agar hard disk tidak penuh)
        $filePath = public_path('templates/' . $frame->file_name);
        if (file_exists($filePath)) {
            @unlink($filePath);
        }
        
        $frame->delete();
        return back()->with('success', 'Frame berhasil dihapus dari sistem!');
    }

    public function saveFrame(Request $request)
    {
        // 1. CEK KEAMANAN: Apakah file benar-benar ada dan terkirim?
        if (!$request->hasFile('frame_file')) {
            return back()->with('error', 'Gagal: File gambar tidak terbaca! Pastikan Anda sudah memilih gambar PNG dan ukurannya tidak terlalu besar.');
        }

        // 2. Ambil file gambar yang diunggah
        $file = $request->file('frame_file');
        
        // 3. Beri nama unik agar tidak bentrok, lalu simpan ke folder public/templates
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('templates'), $fileName);

        // 4. Simpan data ke Database
        \App\Models\Frame::create([
            'nama_frame' => $request->nama_frame,
            'tipe_frame' => $request->tipe_frame,
            'event_id' => $request->tipe_frame === 'khusus' ? $request->event_id : null,
            'ukuran_cetak' => $request->ukuran_cetak,
            'file_name' => $fileName,
            'layout_data' => json_encode($request->slots) // Koordinat diubah jadi format JSON
        ]);

        return back()->with('success', 'Frame dan Koordinat Layout berhasil disimpan!');
    }

    // ==========================================
    // MENU PENGATURAN
    // ==========================================

    public function settings()
    {
        return view('admin.settings');
    }

    public function manageFilter()
    {
        // Tarik data pengaturan pertama (jika belum ada, akan bernilai null)
        $setting = \App\Models\FilterSetting::first();
        return view('admin.filter', compact('setting'));
    }

    public function saveFilter(Request $request)
    {
        // Cari setting yang sudah ada, atau buat instansiasi baru jika masih kosong
        $setting = \App\Models\FilterSetting::first() ?? new \App\Models\FilterSetting();
        
        // Simpan status centang (checkbox) dan teks
        $setting->bw_active = $request->has('bw_active');
        $setting->vintage_active = $request->has('vintage_active');
        $setting->contrast_active = $request->has('contrast_active');
        $setting->ai_active = $request->has('ai_active');
        $setting->ai_model = $request->ai_model;
        $setting->ai_api_key = $request->ai_api_key;
        
        $setting->save();

        return back()->with('success', 'Pengaturan Filter dan API Key AI berhasil disimpan!');
    }

    // ==========================================
    // MENU LOGIN & OTENTIKASI
    // ==========================================

    // Menampilkan halaman form login
    public function halamanLogin()
    {
        return view('admin.login');
    }

    // Memproses data yang diketik saat tombol login ditekan (Ini kode nomor 3)
    public function prosesLogin(Request $request)
    {
        // Cek apakah username dan password cocok dengan yang ada di database
        $kredensial = $request->only('username', 'password');

        if (\Illuminate\Support\Facades\Auth::attempt($kredensial)) {
            // Jika berhasil, buat sesi pelindung dan arahkan ke dashboard utama
            $request->session()->regenerate();
            return redirect()->intended('/admin-home');
        }

        // Jika gagal (salah ketik/password salah), kembalikan dengan pesan error
        return back()->with('error', 'Username atau Password salah!');
    }
    
    // Fungsi untuk Logout
    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

   // ==========================================
    // MENU KELOLA STATUS FOTO (ANTREAN & PRINT FISIK)
    // ==========================================

    public function updatePhotoStatus($id, $status)
    {
        // Cari foto berdasarkan ID
        $photo = \App\Models\Photo::findOrFail($id);
        
        // 1. UBAH STATUS DAN SIMPAN KE DATABASE TERLEBIH DAHULU (PENTING!)
        $photo->status = $status;
        $photo->save();
        
        // 2. JIKA STATUS MENJADI "PROGRESS", TEMBAK KE PRINTER TANPA MENUNGGU
        if ($status === 'progress') {
            $pathFile = storage_path('app/public/ready_to_print/' . $photo->file_name);
            
            if (file_exists($pathFile)) {
                // Perintah start /B membuat proses berjalan mandiri di background Windows
                $perintahPrint = "start /B powershell -WindowStyle Hidden -Command \"Start-Process -FilePath '$pathFile' -Verb Print\"";
                
                // pclose(popen) memaksa server PHP melempar tugas ke printer lalu langsung menyelesaikan loading halaman (Fire and Forget)
                pclose(popen($perintahPrint, "r"));
            } else {
                return back()->with('error', 'Status berhasil dipindah, tetapi gagal print karena file foto tidak ditemukan!');
            }
        }

        // Sesuaikan notifikasi yang muncul di layar
        $pesan = $status === 'progress' 
                 ? 'Sedang diproses: Foto pindah antrean dan dikirim ke printer!' 
                 : 'Status antrean foto berhasil diperbarui!';

        return back()->with('success', $pesan);
    }

    public function manageGallery($id)
    {
        // Cari event berdasarkan ID
        $event = \App\Models\Event::findOrFail($id);
        
        // Tarik semua foto yang statusnya 'mentahan' untuk event ini
        $photos = \App\Models\Photo::where('event_id', $id)
                    ->where('status', 'mentahan')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.gallery', compact('event', 'photos'));
    }
}