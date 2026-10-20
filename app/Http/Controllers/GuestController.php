<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class GuestController
{
    // 1. Halaman Linktree Portal (Hanya papar acara yang belum tamat)
    public function portal()
    {
        // Kunci zona waktu ke Jakarta (WIB) agar perhitungan hari akurat 100%
        $hariIni = Carbon::today('Asia/Jakarta');

        $events = Event::orderBy('created_at', 'desc')->get()->filter(function ($event) use ($hariIni) {
            try {
                // Parse tanggal tutup dan tarik hingga jam 23:59:59 di hari tersebut
                $tutup = Carbon::parse($event->waktu_tutup, 'Asia/Jakarta')->endOfDay();
                
                // Jika waktu tutup MASIH LEBIH BESAR ATAU SAMA DENGAN hari ini, kembalikan true (tampilkan)
                return $tutup->greaterThanOrEqualTo($hariIni);
            } catch (\Exception $e) {
                // Jika format tanggal di database rusak, otomatis sembunyikan eventnya
                return false; 
            }
        })->values(); // SANGAT PENTING: Mereset ulang urutan array agar tidak ada nomor indeks yang bolong

        return view('guest.portal', compact('events'));
    }

    // 2. Halaman Scan QR Acara Spesifik (Diperbarui jadi Super Efisien)
    public function scan(Request $request, $id)
    {
        $event = \App\Models\Event::findOrFail($id);

        // LOGIKA BARU: Jika URL mengandung token (Artinya tamu scan QR Code pakai HP sendiri)
        if ($request->has('token')) {
            $token = $request->token;
            $voucher = \App\Models\Voucher::where('token', $token)->where('event_id', $id)->first();

            // Validasi Token
            if (!$voucher) {
                return redirect('/')->with('error', 'Silang Merah! QR Code tidak valid untuk acara ini.');
            }
            if ($voucher->status !== 'available') {
                return redirect('/')->with('error', 'QR Code sudah hangus / pernah digunakan!');
            }

            // Buka gembok sesi untuk HP tamu ini
            session(['active_token' => $token, 'event_id' => $id]);

            // BYPASS! Langsung lompat ke halaman isi nama tanpa buka web scanner lagi
            return redirect('/isi-nama');
        }

        // LOGIKA LAMA: Jika dibuka tanpa token (Misal: Tablet Kiosk di depan pintu)
        $hariIni = \Carbon\Carbon::now('Asia/Jakarta');
        
        // Halang akses jika acara telah tamat
        if (\Carbon\Carbon::parse($event->waktu_tutup, 'Asia/Jakarta')->endOfDay() < $hariIni) {
            return redirect('/')->with('error', 'Maaf, acara ini telah ditutup.');
        }

        return view('guest.scan', compact('event'));
    }

    public function validasiScan(Request $request, $id)
{
    $token = $request->token;
    $voucher = Voucher::where('token', $token)->where('event_id', $id)->first();

    if (!$voucher) {
        return response()->json(['status' => 'error', 'message' => 'QR Code ini bukan untuk acara ini.']);
    }
    if ($voucher->status !== 'available') {
        return response()->json(['status' => 'error', 'message' => 'QR Code sudah hangus / pernah digunakan!']);
    }

    // Buka gembok sesi studio untuk tamu
    session(['active_token' => $token, 'event_id' => $id]);

    // WAJIB ADA: Mengirim perintah redirect ke frontend
    return response()->json([
        'status' => 'success',
        'redirect' => '/isi-nama'
    ]);
}

    // 3. Halaman Input Nama
    public function askName()
    {
        // Halang tetamu masuk jika belum scan tiket
        if (!session('active_token')) {
            return redirect('/')->with('error', 'Sila pilih acara dan imbas Kod QR terlebih dahulu.');
        }
        return view('guest.name');
    }

    // 4. Proses Simpan Nama ke Sesi
    public function saveName(Request $request)
    {
        $request->validate(['guest_name' => 'required|string|max:50']);
        session(['guest_name' => $request->guest_name]);
        
        // Teruskan ke Studio Kamera
        return redirect()->route('photobooth.studio');
    }
}