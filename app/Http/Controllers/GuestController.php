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

    // 2. Halaman Scan QR Acara Spesifik
    public function scan($id)
    {
        $event = Event::findOrFail($id);
        
        $hariIni = Carbon::now('Asia/Jakarta');
        
        // Halang akses secara terus jika acara telah tamat tempoh
        if (Carbon::parse($event->waktu_tutup, 'Asia/Jakarta')->endOfDay()->lessThan($hariIni)) {
            return redirect('/')->with('error', 'Maaf, acara ini telah kedaluwarsa dan ditutup.');
        }

        return view('guest.scan', compact('event'));
    }

    // 2.5 Proses Pengesahan Scan QR dari Kamera
    public function validasiScan(Request $request, $id)
    {
        $token = $request->token;
        
        // Cari baucar/tiket yang sepadan dengan token DAN acara ini
        $voucher = \App\Models\Voucher::where('token', $token)
                        ->where('event_id', $id)
                        ->first();

        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'Sistem menolak! Kod QR tidak dijumpai atau salah acara.']);
        }
        
        if ($voucher->status !== 'available') {
            return response()->json(['status' => 'error', 'message' => 'Maaf, Kod QR ini telah digunakan atau hangus!']);
        }

        // Buka sesi studio untuk tetamu ini
        session(['active_token' => $token, 'event_id' => $id]);

        // Berikan isyarat berjaya ke frontend untuk pergi ke halaman isi nama automatik
        return response()->json(['status' => 'success', 'redirect' => url('/isi-nama')]);
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