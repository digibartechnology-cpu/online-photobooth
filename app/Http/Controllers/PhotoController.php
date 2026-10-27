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

            // 1. Proses Gambar Utama (Cetak)
            $gambar = $request->gambar;
            $gambar = str_replace('data:image/jpeg;base64,', '', $gambar);
            $gambar = str_replace(' ', '+', $gambar);
            $dataGambarDecoded = base64_decode($gambar);

            $namaFile = 'cetak_' . time() . '_' . Str::slug($request->nama) . '.jpg';
            Storage::disk('public')->put('ready_to_print/' . $namaFile, $dataGambarDecoded);

            // 2. Proses Animasi GIF (Jika Ada)
            $namaFileGif = null;
            if ($request->has('gambar_gif') && !empty($request->gambar_gif)) {
                $gambarGif = str_replace('data:image/gif;base64,', '', $request->gambar_gif);
                $gambarGif = str_replace(' ', '+', $gambarGif);
                $dataGifDecoded = base64_decode($gambarGif);
                
                $namaFileGif = 'gif_' . time() . '_' . Str::slug($request->nama) . '.gif';
                Storage::disk('public')->put('ready_to_print/' . $namaFileGif, $dataGifDecoded);
            }

            $eventId = session('event_id');

            // 3. Simpan ke Database (Termasuk Kolom file_gif)
            $photo = Photo::create([
                'file_name' => $namaFile,
                'file_gif'  => $namaFileGif,
                'status'    => 'pending',
                'event_id'  => $eventId,
                'nama'      => strtoupper($request->nama) 
            ]);

            $token = session('active_token');
            if($token) {
                Voucher::where('token', $token)->update(['status' => 'completed']);
                session()->forget('active_token'); 
                session()->forget('event_id'); 
            }

            $namaTamu = strtoupper($request->nama);

            return response()->json([
                'status' => 'success',
                'message' => 'Foto berhasil masuk antrean!',
                'antrean' => $namaTamu
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error Backend: ' . $e->getMessage() . ' (Baris ' . $e->getLine() . ')'
            ], 500);
        }
    }

    public function simpanMentahan(Request $request)
    {
        try {
            $gambar = $request->gambar;
            $eventId = session('event_id');
            
            if ($gambar && $eventId) {
                $image_parts = explode(";base64,", $gambar);
                $image_base64 = base64_decode($image_parts[1]);
                
                $fileName = 'mentahan_' . time() . '_' . uniqid() . '.jpg';

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