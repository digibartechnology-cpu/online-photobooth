<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VoucherController
{
    public function redeem(Request $request)
    {
        // Menangkap parameter token dari URL QR Code (misal: domain.com/redeem?token=xYz123)
        $token = $request->query('token');

        if (!$token) {
            return redirect('/')->with('error', 'Token QR Code tidak ditemukan.');
        }

        // Mencari token di database
        $voucher = Voucher::where('token', $token)->first();

        // 1. Cek apakah token ada
        if (!$voucher) {
            return redirect('/')->with('error', 'Voucher tidak valid.');
        }

        // 2. Cek apakah token sudah selesai dipakai (completed) atau sedang dipakai (in_use)
        if ($voucher->status !== 'available') {
            return redirect('/')->with('error', 'Voucher ini sudah digunakan atau sedang aktif di perangkat lain.');
        }

        // 3. Cek kedaluwarsa (jika ada kolom expired_at)
        if ($voucher->expired_at && Carbon::now()->greaterThan($voucher->expired_at)) {
            return redirect('/')->with('error', 'Voucher sudah kedaluwarsa.');
        }

        // Jika semua lolos, kunci status menjadi in_use
        $voucher->update([
            'status' => 'in_use'
        ]);

        // Simpan token ke session perangkat tamu agar sistem mengenali mereka di halaman berikutnya
        session(['active_token' => $token]);

        // Arahkan tamu ke halaman input nama & studio foto
        return redirect()->route('photobooth.studio');
    }

    public function kasir()
    {
        return view('admin.kasir');
    }

    public function generate()
    {
        $token = \Illuminate\Support\Str::random(10);
        
        \App\Models\Voucher::create([
            'token' => $token,
            'status' => 'available',
            'quota' => 1
        ]);

        $url = route('voucher.redeem', ['token' => $token]);
        
        // Generate SVG QR Code (kita ubah formatnya menjadi string murni)
        $qrCode = (string) \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($url);

        // PERUBAHAN UTAMA: 
        // Kita tidak lagi menggunakan 'return back()->with()'.
        // Kita langsung merender view kasir beserta data QR-nya.
        return view('admin.kasir', [
            'qrCode' => $qrCode,
            'url' => $url
        ]);
    }
}