<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Frame & Tata Letak | DigiBAR</title>
    
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
                        brand: { blue: '#003eb3', hover: '#002e8a' }
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

    <style>
        .glass-header { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
        .hide-scroll::-webkit-scrollbar { width: 4px; height: 4px; }
        .hide-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-blue selection:text-white">

    <!-- TOP NAVIGATION -->
    <nav class="glass-header border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 flex items-center">
                        <img src="{{ asset('Images/Logo_DigiBAR.png') }}" alt="Logo DigiBAR" class="h-10 md:h-12 object-contain drop-shadow-sm">
                    </div>
                    <div class="hidden sm:block border-l-2 border-slate-200 pl-4 ml-2">
                        <h1 class="text-sm font-bold text-slate-900 leading-tight">Business Digital Agency</h1>
                        <p class="text-xs font-semibold text-brand-blue">Operational Dashboard</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-2 md:gap-6 text-sm font-semibold text-slate-500">
                    <a href="/admin-home" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Jadwal Kegiatan</a>
                    <a href="/admin-home/frame" class="text-brand-blue border-b-2 border-brand-blue px-2 py-5 transition-colors">Frame</a>
                    <a href="/admin-home/filter" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Filter</a>
                    <a href="/admin-home/settings" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Pengaturan</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in-up">
        
        <?php if(session('success')): ?>
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-bold flex items-center gap-3 shadow-sm animate-fade-in-up">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-xl">✅</div>
            {{ session('success') }}
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="mb-6 p-4 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl font-bold flex items-center gap-3 shadow-sm animate-fade-in-up">
            <div class="w-8 h-8 bg-rose-100 rounded-full flex items-center justify-center text-xl">⚠️</div>
            {{ session('error') }}
        </div>
        <?php endif; ?>

        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Editor Tata Letak Frame</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Atur ukuran cetak, unggah bingkai, dan petakan posisi foto secara presisi.</p>
            </div>
        </div>

        <form action="/admin-home/frame/save" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf
            
            <!-- KOLOM KIRI -->
            <div class="lg:col-span-7 space-y-8">
                
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-slate-50/50 border-b border-slate-200 px-6 py-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 rounded bg-brand-blue text-white flex items-center justify-center text-xs">1</span>
                            Identitas & Privasi Frame
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Frame <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_frame" placeholder="Contoh: Wedding Floral 3 Grid" required 
                                class="w-full rounded-xl border-slate-300 border bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 focus:bg-white focus:ring-2 focus:ring-brand-blue focus:border-brand-blue outline-none transition-all shadow-sm">
                        </div>

                        <div class="mb-5">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Peruntukan Frame <span class="text-red-500">*</span></label>
                            <select id="tipe_frame" name="tipe_frame" class="w-full rounded-xl border-slate-300 border bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-brand-blue focus:border-brand-blue outline-none transition-all shadow-sm" onchange="toggleEventSelect()">
                                <option value="umum">🌍 Umum (Tampil di Semua Acara)</option>
                                <option value="khusus">🔒 Khusus (Hanya di Acara Tertentu)</option>
                            </select>
                        </div>

                        <div id="event_select_container" class="hidden p-5 bg-blue-50 border border-blue-100 rounded-xl transition-all duration-300">
                            <label class="block text-sm font-bold text-brand-blue mb-2">Kunci ke Acara: <span class="text-red-500">*</span></label>
                            <select name="event_id" class="w-full rounded-xl border-blue-200 border bg-white px-4 py-3 text-sm font-semibold text-slate-800 focus:ring-2 focus:ring-brand-blue outline-none shadow-sm">
                                <option value="">-- Silakan Pilih Acara --</option>
                                <?php foreach($events as $event): ?>
                                    <option value="{{ $event->id }}">{{ $event->nama_kegiatan }}</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-slate-50/50 border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 rounded bg-brand-blue text-white flex items-center justify-center text-xs">2</span>
                            Mapping Posisi Foto
                        </h3>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <select id="ukuranCetak" name="ukuran_cetak" class="flex-1 rounded-xl border-slate-300 border px-3 py-2 text-sm font-bold bg-amber-50 text-amber-800 outline-none shadow-sm cursor-pointer" onchange="ubahKanvas()">
                                <option value="strip">🎞️ Strip (5x15 cm)</option>
                                <option value="4r">🖼️ 4R (10x15 cm)</option>
                            </select>
                            <select id="jumlahFoto" class="flex-1 rounded-xl border-slate-300 border px-3 py-2 text-sm font-bold bg-white text-slate-800 outline-none shadow-sm cursor-pointer" onchange="generateSlots()">
                                <option value="1">1 Foto</option>
                                <option value="2">2 Foto</option>
                                <option value="3" selected>3 Foto</option>
                                <option value="4">4 Foto</option>
                            </select>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-8 p-5 border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <label class="block text-sm font-bold text-slate-700 mb-3 cursor-pointer">File Bingkai (Wajib PNG Transparan) <span class="text-red-500">*</span></label>
                            <input type="file" name="frame_file" id="frameUpload" accept="image/png" required 
                                class="block w-full text-sm text-slate-500 cursor-pointer
                                file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 
                                file:text-xs file:font-bold file:bg-slate-800 file:text-white 
                                hover:file:bg-slate-700 hover:file:shadow-md transition-all">
                            <p id="rekomendasiUkuran" class="text-xs font-semibold text-brand-blue mt-3 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Rekomendasi ukuran: 600x1800 px (Rasio 1:3)
                            </p>
                        </div>
                        
                        <div id="slotInputsContainer" class="space-y-4"></div>
                    </div>
                </div>

                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-brand-blue hover:bg-brand-hover text-white font-extrabold py-4 rounded-xl shadow-md hover:shadow-lg transition-all text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Konfigurasi Frame
                </button>
            </div>

            <!-- KOLOM KANAN -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-24">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                        <svg class="w-5 h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Live Preview Layar
                    </h3>
                    
                    <div class="flex justify-center bg-slate-50/80 rounded-xl p-4 border border-slate-200 min-h-[500px] items-center pattern-dots-sm">
                        <div id="canvasArea" class="relative bg-white shadow-lg overflow-hidden bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHJlY3Qgd2lkdGg9IjEwIiBoZWlnaHQ9IjEwIiBmaWxsPSIjZWVlIi8+PHJlY3QgeD0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2ZmZiIvPjxyZWN0IHk9IjEwIiB3aWR0aD0iMTAiIGhlaWdodD0iMTAiIGZpbGw9IiNmZmYiLz48cmVjdCB4PSIxMCIgeT0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2VlZSIvPjwvc3ZnPg==')] transition-all duration-300 w-[150px] h-[450px] rounded-sm ring-1 ring-slate-200">
                            <img id="framePreview" src="" class="absolute top-0 left-0 w-full h-full object-cover z-20 pointer-events-none hidden">
                            <div id="slotsPreviewContainer" class="absolute top-0 left-0 w-full h-full z-10"></div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="mt-12 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">
            <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-2 border-b border-slate-100 pb-4">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Koleksi Frame Tersimpan
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                <?php if(count($frames) > 0): ?>
                    <?php foreach($frames as $frame): ?>
                        <div class="border border-slate-200 rounded-2xl p-4 bg-slate-50 hover:bg-white hover:shadow-lg transition-all relative group flex flex-col h-full">
                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur text-[10px] font-black px-2 py-1 rounded-md shadow-sm border border-slate-200 z-10 tracking-wider">
                                {{ strtoupper($frame->ukuran_cetak) }}
                            </div>
                            
                            <div class="w-full h-48 bg-slate-100 rounded-xl mb-4 overflow-hidden border border-slate-200 flex items-center justify-center bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHJlY3Qgd2lkdGg9IjEwIiBoZWlnaHQ9IjEwIiBmaWxsPSIjZWVlIi8+PHJlY3QgeD0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2ZmZiIvPjxyZWN0IHk9IjEwIiB3aWR0aD0iMTAiIGhlaWdodD0iMTAiIGZpbGw9IiNmZmYiLz48cmVjdCB4PSIxMCIgeT0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2VlZSIvPjwvc3ZnPg==')]">
                                <!-- PERBAIKAN: Menggunakan asset storage dengan fallback cerdas -->
                                <img src="{{ asset('storage/templates/' . $frame->file_name) }}" onerror="this.src='{{ asset('templates/' . $frame->file_name) }}'" class="h-full object-contain drop-shadow-md p-2">
                            </div>
                            
                            <div class="flex-grow">
                                <h4 class="font-black text-slate-800 text-sm mb-1 line-clamp-2" title="{{ $frame->nama_frame }}">{{ $frame->nama_frame }}</h4>
                                <div class="flex items-center gap-1 text-[11px] font-semibold text-slate-500 mb-4 bg-slate-200/50 w-fit px-2 py-1 rounded-md">
                                    <?php if($frame->tipe_frame == 'khusus'): ?>
                                        <span class="text-rose-600">🔒 Khusus Acara</span>
                                    <?php else: ?>
                                        <span class="text-emerald-600">🌍 Umum</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <a href="/admin-home/frame/delete/{{ $frame->id }}" onclick="return confirm('Yakin ingin menghapus frame ini beserta file fotonya secara permanen?')" 
                               class="block w-full text-center bg-white border border-slate-200 hover:border-rose-300 text-slate-600 hover:bg-rose-50 hover:text-rose-600 font-bold text-xs py-2.5 rounded-xl transition-all mt-auto shadow-sm">
                                Hapus Frame
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full flex flex-col items-center justify-center py-16 border-2 border-dashed border-slate-200 rounded-2xl text-center bg-slate-50/50">
                        <div class="w-16 h-16 bg-white text-slate-400 rounded-full flex items-center justify-center mb-4 border border-slate-200 shadow-sm">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Belum Ada Frame</h3>
                        <p class="text-sm text-slate-500 font-medium">Desain dan simpan template frame Anda di sini.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- LOGIKA JAVASCRIPT -->
    <script>
        function toggleEventSelect() {
            const tipe = document.getElementById('tipe_frame').value;
            const container = document.getElementById('event_select_container');
            const eventSelect = container.querySelector('select');
            if(tipe === 'khusus') {
                container.classList.remove('hidden');
                eventSelect.setAttribute('required', 'true');
            } else {
                container.classList.add('hidden');
                eventSelect.removeAttribute('required');
                eventSelect.value = "";
            }
        }

        function ubahKanvas() {
            const ukuran = document.getElementById('ukuranCetak').value;
            const kanvas = document.getElementById('canvasArea');
            const teksRekomendasi = document.getElementById('rekomendasiUkuran');

            if (ukuran === 'strip') {
                kanvas.className = "relative bg-white shadow-lg overflow-hidden bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHJlY3Qgd2lkdGg9IjEwIiBoZWlnaHQ9IjEwIiBmaWxsPSIjZWVlIi8+PHJlY3QgeD0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2ZmZiIvPjxyZWN0IHk9IjEwIiB3aWR0aD0iMTAiIGhlaWdodD0iMTAiIGZpbGw9IiNmZmYiLz48cmVjdCB4PSIxMCIgeT0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2VlZSIvPjwvc3ZnPg==')] transition-all duration-300 w-[150px] h-[450px] rounded-sm ring-1 ring-slate-200";
                teksRekomendasi.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Rekomendasi ukuran: 600x1800 px (Rasio 1:3)';
            } else {
                kanvas.className = "relative bg-white shadow-lg overflow-hidden bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHJlY3Qgd2lkdGg9IjEwIiBoZWlnaHQ9IjEwIiBmaWxsPSIjZWVlIi8+PHJlY3QgeD0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2ZmZiIvPjxyZWN0IHk9IjEwIiB3aWR0aD0iMTAiIGhlaWdodD0iMTAiIGZpbGw9IiNmZmYiLz48cmVjdCB4PSIxMCIgeT0iMTAiIHdpZHRoPSIxMCIgaGVpZ2h0PSIxMCIgZmlsbD0iI2VlZSIvPjwvc3ZnPg==')] transition-all duration-300 w-[240px] h-[360px] rounded-sm ring-1 ring-slate-200";
                teksRekomendasi.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Rekomendasi ukuran: 1200x1800 px (Rasio 2:3)';
            }
        }

        document.getElementById('frameUpload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if(file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('framePreview');
                    preview.src = event.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        function adjustVal(id, delta) {
            const input = document.getElementById(id);
            let val = parseFloat(input.value) || 0;
            input.value = (val + delta).toFixed(0);
            updatePreview();
        }

        function generateSlots() {
            const jumlah = parseInt(document.getElementById('jumlahFoto').value);
            const slotInputsContainer = document.getElementById('slotInputsContainer');
            const slotsPreviewContainer = document.getElementById('slotsPreviewContainer');
            
            slotInputsContainer.innerHTML = '';
            slotsPreviewContainer.innerHTML = '';

            for(let i = 1; i <= jumlah; i++) {
                const defY = (i - 1) * (100 / jumlah) + 2; 
                const defH = (100 / jumlah) - 4;

                const htmlInput = `
                    <div class="p-5 border border-slate-200 rounded-xl bg-slate-50 relative hover:border-brand-blue transition-colors shadow-sm">
                        <div class="absolute -top-3 left-4 bg-brand-blue text-white text-[10px] font-black px-3 py-1 rounded-md shadow-sm tracking-widest uppercase">FOTO ${i}</div>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-2">
                            
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-1.5 text-center uppercase tracking-wider">X (Kiri) %</label>
                                <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-brand-blue focus-within:border-brand-blue transition-all">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-r border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('x_${i}', -1)">-</button>
                                    <input type="number" id="x_${i}" name="slots[${i}][x]" value="5" class="w-full text-center text-sm font-semibold py-1.5 outline-none" oninput="updatePreview()">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-l border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('x_${i}', 1)">+</button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-1.5 text-center uppercase tracking-wider">Y (Atas) %</label>
                                <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-brand-blue focus-within:border-brand-blue transition-all">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-r border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('y_${i}', -1)">-</button>
                                    <input type="number" id="y_${i}" name="slots[${i}][y]" value="${defY.toFixed(0)}" class="w-full text-center text-sm font-semibold py-1.5 outline-none" oninput="updatePreview()">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-l border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('y_${i}', 1)">+</button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-1.5 text-center uppercase tracking-wider">Lebar %</label>
                                <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-brand-blue focus-within:border-brand-blue transition-all">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-r border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('w_${i}', -1)">-</button>
                                    <input type="number" id="w_${i}" name="slots[${i}][w]" value="90" class="w-full text-center text-sm font-semibold py-1.5 outline-none" oninput="updatePreview()">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-l border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('w_${i}', 1)">+</button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 mb-1.5 text-center uppercase tracking-wider">Tinggi %</label>
                                <div class="flex items-center bg-white border border-slate-300 rounded-lg overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-brand-blue focus-within:border-brand-blue transition-all">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-r border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('h_${i}', -1)">-</button>
                                    <input type="number" id="h_${i}" name="slots[${i}][h]" value="${defH.toFixed(0)}" class="w-full text-center text-sm font-semibold py-1.5 outline-none" oninput="updatePreview()">
                                    <button type="button" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 border-l border-slate-300 font-bold text-slate-600 transition-colors" onclick="adjustVal('h_${i}', 1)">+</button>
                                </div>
                            </div>

                        </div>
                    </div>
                `;
                slotInputsContainer.insertAdjacentHTML('beforeend', htmlInput);

                const htmlBox = `
                    <div id="preview_box_${i}" class="absolute bg-brand-blue/60 border border-white backdrop-blur-[2px] flex items-center justify-center text-white font-black text-xl shadow-lg transition-all duration-200 rounded-sm">
                        ${i}
                    </div>
                `;
                slotsPreviewContainer.insertAdjacentHTML('beforeend', htmlBox);
            }
            updatePreview();
        }

        function updatePreview() {
            const jumlah = parseInt(document.getElementById('jumlahFoto').value);
            for(let i = 1; i <= jumlah; i++) {
                const box = document.getElementById(`preview_box_${i}`);
                if(box) {
                    box.style.left = (document.getElementById(`x_${i}`).value || 0) + '%';
                    box.style.top = (document.getElementById(`y_${i}`).value || 0) + '%';
                    box.style.width = (document.getElementById(`w_${i}`).value || 0) + '%';
                    box.style.height = (document.getElementById(`h_${i}`).value || 0) + '%';
                }
            }
        }

        window.onload = function() { 
            ubahKanvas();
            generateSlots(); 
        };
    </script>
</body>
</html>