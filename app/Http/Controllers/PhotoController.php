<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Voucher;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function cetakKhusus(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string',
                'gambar' => 'required|string', 
            ]);

            $gambar = $request->gambar;
            $gambar = str_replace('data:image/jpeg;base64,', '', $gambar);
            $gambar = str_replace(' ', '+', $gambar);
            $dataGambarDecoded = base64_decode($gambar);

            $namaFile = 'cetak_' . time() . '_' . Str::slug($request->nama) . '.jpg';
            
            // SUDAH BENAR: Menggunakan Storage::disk('public')
            Storage::disk('public')->put('ready_to_print/' . $namaFile, $dataGambarDecoded);

            $eventId = session('event_id');

            $photo = Photo::create([
                'file_name' => $namaFile,
                'status' => 'pending',
                'event_id' => $eventId 
            ]);

            $token = session('active_token');
            if($token) {
                Voucher::where('token', $token)->update(['status' => 'completed']);
                session()->forget('active_token'); 
                session()->forget('guest_name'); 
                session()->forget('event_id'); 
            }

            $nomorAntrean = str_pad($photo->id, 3, '0', STR_PAD_LEFT);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto berhasil masuk antrean!',
                'antrean' => $nomorAntrean
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error Backend: ' . $e->getMessage() . ' (Baris ' . $e->getLine() . ')'
            ], 500);
        }
    }

    // ==================================================
    // FUNGSI BARU: MENYIMPAN FOTO UNLIMITED KE FOLDER SERVER
    // ==================================================
    public function simpanMentahan(Request $request)
    {
        try {
            $gambar = $request->gambar;
            $eventId = session('event_id');
            
            if ($gambar && $eventId) {
                $image_parts = explode(";base64,", $gambar);
                $image_base64 = base64_decode($image_parts[1]);
                
                $fileName = 'mentahan_' . time() . '_' . uniqid() . '.jpg';

                // PERBAIKAN: Simpan ke Storage Public agar aman dari hapus otomatis cloud
                Storage::disk('public')->put('mentahan/' . $fileName, $image_base64);

                Photo::create([
                    'event_id' => $eventId,
                    'file_name' => 'mentahan/' . $fileName,
                    'status' => 'mentahan' 
                ]);
            }
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}