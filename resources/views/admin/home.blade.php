<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiBAR | Operational Dashboard</title>
    
    <!-- Import Font Inter & Tailwind CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Konfigurasi Warna & Animasi -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        maroon: { 50: '#fff5f5', 100: '#ffe3e3', 600: '#7b1113', 700: '#5a0c0e' },
                        slate: { 800: '#1e293b', 900: '#0f172a' },
                        brand: { blue: '#003eb3', hover: '#002e8a' } // Warna biru konsisten
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS Minimal untuk Date Input -->
    <style>
        input[type="date"]::-webkit-calendar-picker-indicator { cursor: pointer; opacity: 0.6; transition: 0.2s; }
        input[type="date"]::-webkit-calendar-picker-indicator:hover { opacity: 1; }
        .glass-header { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-blue selection:text-white">

    <!-- TOP NAVIGATION -->
    <nav class="glass-header border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Brand & Logo Gambar (Sudah Dibesarkan) -->
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('Images/Logo_DigiBAR.png') }}" alt="Logo DigiBAR" class="h-10 md:h-12 object-contain drop-shadow-sm">
                    </div>
                    <div class="hidden sm:block border-l-2 border-slate-200 pl-4 ml-2">
                        <h1 class="text-sm font-bold text-slate-900 leading-tight">Business Digital Agency</h1>
                        <p class="text-xs font-semibold text-brand-blue">Operational Dashboard</p>
                    </div>
                </div>
                
                <!-- Menu Links -->
                <div class="flex items-center gap-2 md:gap-6 text-sm font-semibold text-slate-500">
                    <a href="/admin-home" class="text-brand-blue border-b-2 border-brand-blue px-2 py-5 transition-colors">Jadwal Kegiatan</a>
                    <a href="/admin-home/frame" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Frame</a>
                    <a href="/admin-home/filter" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Filter</a>
                    <a href="/admin-home/settings" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Pengaturan</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in-up">
        
        <!-- HEADER HALAMAN -->
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Manajemen Kegiatan</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Kelola event, akses photobooth, dan cetak foto dalam satu panel.</p>
            </div>
            <div class="bg-blue-50 text-brand-blue px-4 py-2 rounded-xl text-sm font-bold border border-blue-100 shadow-sm">
                Total Event Aktif: {{ $events->where('waktu_tutup', '>=', now()->format('Y-m-d'))->count() }}
            </div>
        </div>

        <!-- FORM CARD (SAAS STYLE) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow overflow-hidden mb-12">
            <div class="bg-slate-50/50 border-b border-slate-200 px-6 py-4 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Kegiatan Baru
                </h3>
            </div>
            
            <form action="{{ route('admin.event.save') }}" method="POST" class="p-6 md:p-8">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Kegiatan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_kegiatan" placeholder="Contoh: AWS Summit Jakarta / SM Award 2026" required
                        class="w-full rounded-xl border-slate-300 border bg-slate-50 px-4 py-3.5 text-slate-900 font-medium focus:bg-white focus:ring-2 focus:ring-brand-blue focus:border-brand-blue focus:outline-none transition-all shadow-sm">
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Tanggal Buka -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Mulai Buka Akses <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input type="date" id="waktu_buka" name="waktu_buka" onchange="resetTutup()" required
                                class="w-full rounded-xl border-slate-300 border bg-slate-50 px-4 py-3.5 text-slate-900 font-medium focus:bg-white focus:ring-2 focus:ring-brand-blue focus:border-brand-blue focus:outline-none transition-all shadow-sm">
                        </div>
                    </div>
                    
                    <!-- Preset Durasi -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Set Durasi Cepat</label>
                        <div class="flex gap-2 h-[52px]">
                            <button type="button" onclick="setDurasi(1)" class="flex-1 bg-white border border-slate-300 text-slate-600 rounded-xl text-xs font-bold hover:bg-blue-50 hover:text-brand-blue hover:border-blue-300 transition-all shadow-sm">1 Hari</button>
                            <button type="button" onclick="setDurasi(7)" class="flex-1 bg-white border border-slate-300 text-slate-600 rounded-xl text-xs font-bold hover:bg-blue-50 hover:text-brand-blue hover:border-blue-300 transition-all shadow-sm">1 Minggu</button>
                            <button type="button" onclick="setDurasi(30)" class="flex-1 bg-white border border-slate-300 text-slate-600 rounded-xl text-xs font-bold hover:bg-blue-50 hover:text-brand-blue hover:border-blue-300 transition-all shadow-sm">1 Bulan</button>
                        </div>
                    </div>

                    <!-- Tanggal Tutup -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Otomatis Tutup Pada <span class="text-red-500">*</span></label>
                        <input type="date" id="waktu_tutup" name="waktu_tutup" required
                            class="w-full rounded-xl border-slate-300 border bg-slate-50 px-4 py-3.5 text-slate-900 font-medium focus:bg-white focus:ring-2 focus:ring-brand-blue focus:border-brand-blue focus:outline-none transition-all shadow-sm disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed">
                    </div>
                </div>

                <!-- Action Bar -->
                <div class="flex justify-end pt-5 border-t border-slate-100">
                    <button type="submit" class="bg-brand-blue hover:bg-brand-hover text-white font-bold py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Data Kegiatan
                    </button>
                </div>
            </form>
        </div>

        <!-- TITLE LIST EVENT -->
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Daftar Kegiatan Aktif
            </h3>
        </div>

        <!-- EVENT CARDS GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($events as $event)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-lg transition-all flex flex-col relative group overflow-hidden">
                
                <div class="p-6 flex-grow flex flex-col">
                    <!-- Delete Button -->
                    <a href="{{ route('admin.event.delete', $event->id) }}" onclick="return confirm('Yakin ingin menghapus permanen kegiatan ini?')" 
                       class="absolute top-4 right-4 text-slate-300 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-all opacity-0 group-hover:opacity-100 z-10" title="Hapus Kegiatan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </a>

                    <!-- Badge Status -->
                    @if(\Carbon\Carbon::parse($event->waktu_tutup)->isPast())
                        <div class="inline-flex mb-3">
                            <span class="bg-rose-50 border border-rose-200 text-rose-700 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-widest">
                                Kedaluwarsa
                            </span>
                        </div>
                    @else
                        <div class="inline-flex mb-3">
                            <span class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-widest flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Aktif
                            </span>
                        </div>
                    @endif

                    <h3 class="text-xl font-black text-slate-900 mb-4 pr-6 leading-snug line-clamp-2" title="{{ $event->nama_kegiatan }}">{{ $event->nama_kegiatan }}</h3>
                    
                    <div class="space-y-3 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 rounded-full bg-slate-300 mr-3"></div>
                            <span class="text-slate-500 w-16 font-medium">Buka</span>
                            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($event->waktu_buka)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <div class="w-2 h-2 rounded-full bg-slate-300 mr-3"></div>
                            <span class="text-slate-500 w-16 font-medium">Tutup</span>
                            <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($event->waktu_tutup)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <!-- Area Tombol Aksi (Tersusun Rapi) -->
                    <div class="grid grid-cols-3 gap-2 mt-auto">
                        <a href="/admin-home/event/{{ $event->id }}/qr" class="flex flex-col items-center justify-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 font-bold text-[11px] py-2.5 rounded-xl transition-all text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                            QR Akses
                        </a>
                        
                        <a href="/admin-home/event/{{ $event->id }}/scanner" class="flex flex-col items-center justify-center gap-1.5 bg-blue-50 text-brand-blue border border-blue-200 hover:bg-blue-100 font-bold text-[11px] py-2.5 rounded-xl transition-all text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Scanner
                        </a>

                        <a href="/admin-home/event/{{ $event->id }}/gallery" class="flex flex-col items-center justify-center gap-1.5 bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold text-[11px] py-2.5 rounded-xl transition-all text-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Galeri
                        </a>
                    </div>
                </div>

                <!-- Tombol Lihat Database -->
                <a href="/admin-home/event/{{ $event->id }}/database" class="block w-full text-center bg-slate-50 hover:bg-brand-blue hover:text-white border-t border-slate-200 text-slate-700 font-extrabold text-sm py-4 transition-all mt-auto flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    Database Antrean Cetak
                </a>
            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl border-2 border-dashed border-slate-200 p-12 text-center">
                <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-100">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-700 mb-1">Belum Ada Kegiatan</h3>
                <p class="text-sm text-slate-500">Silakan buat kegiatan baru melalui formulir di atas.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Script Pengaturan Tanggal -->
    <script>
        function setDurasi(hari) {
            let bukaInput = document.getElementById('waktu_buka');
            let tutupInput = document.getElementById('waktu_tutup');
            
            // Jika tanggal mulai belum diisi, set ke hari ini
            if (!bukaInput.value) {
                let today = new Date();
                today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
                bukaInput.value = today.toISOString().split('T')[0];
            }
            
            let dateBuka = new Date(bukaInput.value);
            dateBuka.setDate(dateBuka.getDate() + hari);
            
            tutupInput.value = dateBuka.toISOString().split('T')[0];
            tutupInput.setAttribute('disabled', 'true');
            tutupInput.classList.add('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            
            // Buat input hidden agar value tetap terkirim meski form input disabled
            let hiddenInput = document.getElementById('hidden_waktu_tutup');
            if(!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.id = 'hidden_waktu_tutup';
                hiddenInput.name = 'waktu_tutup';
                tutupInput.parentNode.appendChild(hiddenInput);
            }
            hiddenInput.value = tutupInput.value;
        }

        function resetTutup() {
            let tutupInput = document.getElementById('waktu_tutup');
            tutupInput.removeAttribute('disabled');
            tutupInput.classList.remove('bg-slate-100', 'text-slate-400', 'cursor-not-allowed');
            tutupInput.value = '';
            
            let hiddenInput = document.getElementById('hidden_waktu_tutup');
            if(hiddenInput) hiddenInput.remove();
        }
    </script>
</body>
</html>