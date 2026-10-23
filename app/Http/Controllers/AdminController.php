<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Voucher;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage; // Wajib ditambahkan untuk akses Storage

class AdminController extends Controller
{
    public function index()
    {
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
        $vouchers = Voucher::where('event_id', $id)
                        ->where('status', 'available')
                        ->get();

        return view('admin.manage_qr', compact('event', 'vouchers'));
    }

    public function manageScanner($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.manage_scanner', compact('event'));
    }

    public function processScan(Request $request, $id)
    {
        $token = $request->token;
        $voucher = Voucher::where('token', $token)->where('event_id', $id)->first();

        if (!$voucher) {
            return response()->json(['status' => 'error', 'message' => 'Silang Merah! QR Code ini bukan untuk acara ini.']);
        }
        if ($voucher->status !== 'available') {
            return response()->json(['status' => 'error', 'message' => 'QR Code sudah hangus / pernah dicetak!']);
        }

        session(['active_token' => $token, 'event_id' => $id]);
        return response()->json(['status' => 'success']);
    }

    public function manageDatabase($id)
    {
        $event = Event::findOrFail($id);
        $photos = \App\Models\Photo::where('event_id', $id)->orderBy('created_at', 'desc')->get();
        
        $list = $photos->where('status', 'pending');
        $ongoing = $photos->where('status', 'progress');
        $done = $photos->where('status', 'completed');

        return view('admin.manage_database', compact('event', 'list', 'ongoing', 'done'));
    }

    // ==========================================
    // LOGIKA GENERATE QR KHUSUS EVENT
    // ==========================================

    public function generateEventQR(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $jumlah = $request->jumlah ?? 1;

        for ($i = 0; $i < $jumlah; $i++) {
            $token = Str::random(10);
            Voucher::create([
                'token' => $token, 
                'status' => 'available', 
                'quota' => 1,
                'event_id' => $event->id
            ]);
        }
        return back()->with('success', "$jumlah Tiket baru berhasil dibuat dan disimpan permanen!");
    }

    // ==========================================
    // MENU FRAME & FILTER
    // ==========================================

    public function manageFrame()
    {
        $events = Event::orderBy('created_at', 'desc')->get();
        $frames = \App\Models\Frame::orderBy('created_at', 'desc')->get(); 
        return view('admin.frame', compact('events', 'frames'));
    }

    public function deleteFrame($id)
    {
        $frame = \App\Models\Frame::findOrFail($id);
        
        // PERBAIKAN: Hapus dari Storage Public agar aman
        if (Storage::disk('public')->exists('templates/' . $frame->file_name)) {
            Storage::disk('public')->delete('templates/' . $frame->file_name);
        }
        
        $frame->delete();
        return back()->with('success', 'Frame berhasil dihapus dari sistem!');
    }

    public function saveFrame(Request $request)
    {
        if (!$request->hasFile('frame_file')) {
            return back()->with('error', 'Gagal: File gambar tidak terbaca!');
        }

        $file = $request->file('frame_file');
        
        // PERBAIKAN: Hilangkan spasi di nama file & simpan ke Storage Public permanen
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->storeAs('templates', $fileName, 'public');

        \App\Models\Frame::create([
            'nama_frame' => $request->nama_frame,
            'tipe_frame' => $request->tipe_frame,
            'event_id' => $request->tipe_frame === 'khusus' ? $request->event_id : null,
            'ukuran_cetak' => $request->ukuran_cetak,
            'file_name' => $fileName,
            'layout_data' => json_encode($request->slots)
        ]);

        return back()->with('success', 'Frame dan Koordinat Layout berhasil disimpan!');
    }

    // ==========================================
    // MENU PENGATURAN & FILTER
    // ==========================================

    public function settings()
    {
        return view('admin.settings');
    }

    public function manageFilter()
    {
        $setting = \App\Models\FilterSetting::first();
        return view('admin.filter', compact('setting'));
    }

    public function saveFilter(Request $request)
    {
        $setting = \App\Models\FilterSetting::first() ?? new \App\Models\FilterSetting();
        
        // PERBAIKAN: Sinkronisasi 9 kolom filter baru, cabut filter AI
        $setting->retro_warm_active = $request->has('retro_warm_active');
        $setting->retro_cool_active = $request->has('retro_cool_active');
        $setting->cinematic_active = $request->has('cinematic_active');
        $setting->vibrant_active = $request->has('vibrant_active');
        $setting->gritty_active = $request->has('gritty_active');
        $setting->bw_classic_active = $request->has('bw_classic_active');
        $setting->sepia_active = $request->has('sepia_active');
        $setting->pastel_active = $request->has('pastel_active');
        $setting->moody_active = $request->has('moody_active');
        
        // Matikan AI karena sudah dihapus fiturnya
        $setting->ai_active = false;
        
        $setting->save();

        return back()->with('success', 'Pengaturan Filter berhasil disimpan!');
    }

    // ==========================================
    // MENU LOGIN & OTENTIKASI
    // ==========================================

    public function halamanLogin()
    {
        return view('admin.login');
    }

    public function prosesLogin(Request $request)
    {
        $kredensial = $request->only('username', 'password');
        if (\Illuminate\Support\Facades\Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin-home');
        }
        return back()->with('error', 'Username atau Password salah!');
    }
    
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
        $photo = \App\Models\Photo::findOrFail($id);
        $photo->status = $status;
        $photo->save();
        
        if ($status === 'progress') {
            $pathFile = storage_path('app/public/ready_to_print/' . $photo->file_name);
            
            if (file_exists($pathFile)) {
                $perintahPrint = "start /B powershell -WindowStyle Hidden -Command \"Start-Process -FilePath '$pathFile' -Verb Print\"";
                pclose(popen($perintahPrint, "r"));
            } else {
                return back()->with('error', 'Status berhasil dipindah, tetapi gagal print karena file foto tidak ditemukan!');
            }
        }

        $pesan = $status === 'progress' 
                 ? 'Sedang diproses: Foto pindah antrean dan dikirim ke printer!' 
                 : 'Status antrean foto berhasil diperbarui!';

        return back()->with('success', $pesan);
    }

    public function manageGallery($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $photos = \App\Models\Photo::where('event_id', $id)
                    ->where('status', 'mentahan')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('admin.gallery', compact('event', 'photos'));
    }
}