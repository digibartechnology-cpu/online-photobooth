<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Cetak | DigiBAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Inter', 'sans-serif'] }, 
                    colors: { 
                        brand: { blue: '#003eb3', hover: '#002e8a' },
                        slate: { 800: '#1e293b', 900: '#0f172a' }
                    } 
                } 
            } 
        }
    </script>
    <style>
        .glass-header { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
        .hide-scroll::-webkit-scrollbar { display: none; }
        .hide-scroll { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-blue selection:text-white">

    <!-- NAVBAR DISAMAKAN DENGAN HALAMAN FRAME -->
    <nav class="glass-header border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-2xl font-black tracking-tight text-brand-blue">DIGI<span class="text-slate-900">BAR</span></span>
                    </div>
                    <div class="hidden sm:block border-l-2 border-slate-200 pl-4 ml-2">
                        <h1 class="text-sm font-bold text-slate-900 leading-tight">Antrean Cetak Mesin</h1>
                        <p class="text-xs font-semibold text-brand-blue">Operational Dashboard</p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <a href="/admin-home" class="flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-4 py-2.5 rounded-xl transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        Tutup Antrean
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-[fadeIn_0.4s_ease-out]">
        
        <div class="mb-8 border-b border-slate-200 pb-6">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Antrean: {{ $event->nama_kegiatan }}</h2>
            <p class="text-sm text-slate-500 mt-1 font-medium">Pantau dan kelola antrean cetak foto mesin photobooth secara real-time.</p>
        </div>

        <!-- NAV TABS -->
        <div class="mb-8 flex gap-2 md:gap-4 overflow-x-auto hide-scroll pb-2">
            <button onclick="bukaTab(event, 'tab-list')" class="nav-btn active-tab flex items-center gap-2 bg-white border-2 border-brand-blue text-brand-blue font-bold py-3 px-6 rounded-xl text-sm transition-all whitespace-nowrap shadow-sm">
                Belum Dicetak 
                <span class="bg-brand-blue text-white rounded-md px-2 py-0.5 text-xs">{{ $list->count() }}</span>
            </button>
            <button onclick="bukaTab(event, 'tab-ongoing')" class="nav-btn flex items-center gap-2 bg-white border border-slate-200 text-slate-500 font-bold py-3 px-6 rounded-xl text-sm hover:border-slate-300 hover:text-slate-700 transition-all whitespace-nowrap shadow-sm">
                Sedang Diproses 
                <span class="bg-slate-100 text-slate-600 rounded-md px-2 py-0.5 text-xs">{{ $ongoing->count() }}</span>
            </button>
            <button onclick="bukaTab(event, 'tab-done')" class="nav-btn flex items-center gap-2 bg-white border border-slate-200 text-slate-500 font-bold py-3 px-6 rounded-xl text-sm hover:border-slate-300 hover:text-slate-700 transition-all whitespace-nowrap shadow-sm">
                Selesai 
                <span class="bg-slate-100 text-slate-600 rounded-md px-2 py-0.5 text-xs">{{ $done->count() }}</span>
            </button>
        </div>

        <!-- TAB: ON LIST (PREPARE) -->
        <div id="tab-list" class="tab-content block animate-[fadeIn_0.3s_ease-in-out]">
            @if($list->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <?php foreach($list as $p): ?>
                    <div class="bg-white p-5 rounded-[1.25rem] border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col h-full group">
                        
                        <!-- Area Gambar -->
                        <div class="w-full bg-slate-50 rounded-xl mb-5 overflow-hidden border border-slate-100 flex items-center justify-center relative aspect-[3/4] group-hover:border-brand-blue/30 transition-colors">
                            <img src="/render-print/{{ $p->file_name }}" class="absolute inset-0 w-full h-full object-contain p-1.5 drop-shadow-sm" onerror="this.outerHTML='<div class=\'text-[10px] text-slate-400 text-center px-4\'>Foto lama terhapus sistem cloud</div>'">
                        </div>
                        
                        <!-- Area Nama Tamu -->
                        <div class="mb-5 text-center mt-auto">
                            <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1.5">Cetak Atas Nama</span>
                            <span class="block text-lg font-black text-slate-800 truncate w-full px-2" title="{{ strtoupper($p->nama ?? 'TAMU') }}">
                                {{ strtoupper($p->nama ?? 'TAMU') }}
                            </span>
                        </div>

                        <!-- Tombol -->
                        <button type="button" onclick="cetakFoto('{{ route('admin.photo.update', [$p->id, 'progress']) }}', '/render-print/{{ $p->file_name }}')" class="w-full flex items-center justify-center gap-2 bg-brand-blue hover:bg-brand-hover text-white font-bold text-sm py-3.5 rounded-xl transition-all shadow-sm shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Proses Cetak
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 bg-white border border-dashed border-slate-300 rounded-3xl">
                    <span class="text-4xl mb-4">🖨️</span>
                    <h3 class="text-lg font-bold text-slate-700">Belum ada antrean cetak</h3>
                    <p class="text-sm text-slate-500 mt-1">Foto yang dijepret tamu akan otomatis muncul di sini.</p>
                </div>
            @endif
        </div>

        <!-- TAB: ON GOING -->
        <div id="tab-ongoing" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">
            @if($ongoing->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <?php foreach($ongoing as $p): ?>
                    <div class="bg-white p-5 rounded-[1.25rem] border border-blue-200 shadow-md hover:shadow-lg transition-all relative overflow-hidden flex flex-col h-full ring-4 ring-brand-blue/10">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-brand-blue animate-pulse"></div>
                        
                        <!-- Area Gambar -->
                        <div class="w-full bg-slate-50 rounded-xl mt-2 mb-5 overflow-hidden border border-slate-100 flex items-center justify-center relative aspect-[3/4]">
                            <img src="/render-print/{{ $p->file_name }}" class="absolute inset-0 w-full h-full object-contain p-1.5 drop-shadow-sm" onerror="this.outerHTML='<div class=\'text-[10px] text-slate-400 text-center px-4\'>Foto lama terhapus sistem cloud</div>'">
                        </div>
                        
                        <!-- Area Nama Tamu -->
                        <div class="mb-5 text-center mt-auto">
                            <span class="block text-[10px] uppercase tracking-widest text-brand-blue font-bold mb-1.5 animate-pulse">Sedang Mencetak...</span>
                            <span class="block text-lg font-black text-slate-800 truncate w-full px-2" title="{{ strtoupper($p->nama ?? 'TAMU') }}">
                                {{ strtoupper($p->nama ?? 'TAMU') }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-2 shrink-0">
                            <a href="{{ route('admin.photo.update', [$p->id, 'completed']) }}" class="flex items-center justify-center gap-2 w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm py-3 rounded-xl transition-all shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Selesai
                            </a>
                            <a href="{{ route('admin.photo.update', [$p->id, 'pending']) }}" onclick="return confirm('Kertas macet atau mesin error? Klik OK untuk mengembalikan foto ini ke antrean (Belum Dicetak) agar bisa diprint ulang.')" class="w-full text-center bg-slate-50 hover:bg-rose-50 text-slate-500 hover:text-rose-600 border border-slate-200 hover:border-rose-200 font-bold text-xs py-2.5 rounded-xl transition-all">
                                ⚠️ Ulangi Print
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 bg-white border border-dashed border-slate-300 rounded-3xl">
                    <span class="text-4xl mb-4">💤</span>
                    <h3 class="text-lg font-bold text-slate-700">Mesin sedang istirahat</h3>
                    <p class="text-sm text-slate-500 mt-1">Tidak ada foto yang sedang dalam proses cetak.</p>
                </div>
            @endif
        </div>

        <!-- TAB: DONE -->
        <div id="tab-done" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">
            @if($done->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <?php foreach($done as $p): ?>
                    <div class="bg-white p-5 rounded-[1.25rem] border border-slate-200 opacity-80 hover:opacity-100 grayscale-[15%] hover:grayscale-0 transition-all flex flex-col h-full shadow-sm">
                        
                        <!-- Area Gambar -->
                        <div class="w-full bg-slate-50 rounded-xl mb-5 overflow-hidden border border-slate-100 flex items-center justify-center relative aspect-[3/4]">
                            <img src="/render-print/{{ $p->file_name }}" class="absolute inset-0 w-full h-full object-contain p-1.5" onerror="this.outerHTML='<div class=\'text-[10px] text-slate-400 text-center px-4\'>Foto lama hilang</div>'">
                        </div>
                        
                        <!-- Area Nama Tamu -->
                        <div class="mb-4 text-center mt-auto">
                            <span class="block text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-1">Milik</span>
                            <span class="block text-sm font-black text-slate-600 truncate w-full px-2" title="{{ strtoupper($p->nama ?? 'TAMU') }}">
                                {{ strtoupper($p->nama ?? 'TAMU') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-center items-center gap-1.5 text-emerald-600 font-bold text-[10px] uppercase tracking-wider bg-emerald-50 py-2.5 rounded-xl border border-emerald-100 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Telah Dicetak
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 bg-white border border-dashed border-slate-300 rounded-3xl">
                    <span class="text-4xl mb-4">🗂️</span>
                    <h3 class="text-lg font-bold text-slate-700">Riwayat Cetak Kosong</h3>
                    <p class="text-sm text-slate-500 mt-1">Belum ada foto yang diselesaikan.</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        function bukaTab(evt, tabName) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('block');
            });
            document.querySelectorAll('.nav-btn').forEach(btn => {
                // Reset semua tab ke gaya tidak aktif
                btn.classList.remove('border-2', 'border-brand-blue', 'text-brand-blue', 'active-tab');
                btn.classList.add('border', 'border-slate-200', 'text-slate-500');
                
                let span = btn.querySelector('span');
                span.classList.remove('bg-brand-blue', 'text-white');
                span.classList.add('bg-slate-100', 'text-slate-600');
            });
            
            // Tampilkan tab yang dipilih
            document.getElementById(tabName).classList.remove('hidden');
            document.getElementById(tabName).classList.add('block');
            
            // Atur gaya tab aktif
            let activeBtn = evt.currentTarget;
            activeBtn.classList.remove('border', 'border-slate-200', 'text-slate-500');
            activeBtn.classList.add('border-2', 'border-brand-blue', 'text-brand-blue', 'active-tab');
            
            let activeSpan = activeBtn.querySelector('span');
            activeSpan.classList.remove('bg-slate-100', 'text-slate-600');
            activeSpan.classList.add('bg-brand-blue', 'text-white');
        }

        // FUNGSI PRINT DENGAN FORCE PAPER SIZE 5x15 CM (4R Photostrip)
        function cetakFoto(urlUpdate, imageUrl) {
            let iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
            
            let doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`
                <html>
                    <head>
                        <style>
                            @page {
                                size: 5cm 15cm; 
                                margin: 0; 
                            }
                            body {
                                margin: 0;
                                padding: 0;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                background-color: white;
                            }
                            img {
                                width: 100%;
                                height: 100%;
                                object-fit: cover; 
                            }
                        </style>
                    </head>
                    <body>
                        <img src="${imageUrl}" onload="eksekusiCetak()" onerror="alert('Gagal memuat foto untuk dicetak. Mungkin foto lama sudah terhapus.')" />
                        <script>
                            function eksekusiCetak() {
                                window.print();
                                setTimeout(() => {
                                    window.parent.location.href = '${urlUpdate}';
                                }, 1000);
                            }
                        <\/script>
                    </body>
                </html>
            `);
            doc.close();
        }
    </script>
</body>
</html>