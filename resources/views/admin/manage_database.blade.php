<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Cetak | DigiBAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { brand: { blue: '#003eb3', hover: '#002e8a' } } } } }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased selection:bg-brand-blue selection:text-white">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16 items-center">
            <div class="flex items-center gap-2">
                <span class="text-xl font-black tracking-tight text-brand-blue">DIGI<span class="text-slate-900">BAR</span></span>
                <span class="text-slate-300 mx-2">|</span>
                <span class="text-sm font-semibold text-slate-600">Antrean Cetak Mesin</span>
            </div>
            <a href="/admin-home" class="text-sm font-semibold text-slate-500 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-4 py-2 rounded-lg transition">X Tutup</a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Antrean: {{ $event->nama_kegiatan }}</h2>
        </div>

        <!-- NAV TABS -->
        <div class="border-b border-slate-200 mb-8 flex gap-8">
            <button onclick="bukaTab(event, 'tab-list')" class="nav-btn active-tab border-b-2 border-brand-blue text-brand-blue font-bold py-4 text-sm hover:text-brand-hover transition">
                Pending <span class="bg-blue-50 text-brand-blue rounded-full px-2 py-0.5 ml-1 text-xs border border-blue-100">{{ $list->count() }}</span>
            </button>
            <button onclick="bukaTab(event, 'tab-ongoing')" class="nav-btn border-b-2 border-transparent text-slate-500 font-medium py-4 text-sm hover:text-slate-800 transition">
                Sedang Diproses <span class="bg-slate-100 text-slate-600 rounded-full px-2 py-0.5 ml-1 text-xs">{{ $ongoing->count() }}</span>
            </button>
            <button onclick="bukaTab(event, 'tab-done')" class="nav-btn border-b-2 border-transparent text-slate-500 font-medium py-4 text-sm hover:text-slate-800 transition">
                Selesai <span class="bg-slate-100 text-slate-600 rounded-full px-2 py-0.5 ml-1 text-xs">{{ $done->count() }}</span>
            </button>
        </div>

        <!-- TAB: ON LIST (PREPARE) -->
        <div id="tab-list" class="tab-content block animate-[fadeIn_0.3s_ease-in-out]">
            @if($list->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <?php foreach($list as $p): ?>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition">
                        <!-- PERBAIKAN: Menggunakan fungsi jalur bypass /render-print/ -->
                        <img src="/render-print/{{ $p->file_name }}" class="w-full rounded-xl mb-4 bg-slate-50 aspect-video object-contain border border-slate-100">
                        
                        <div class="flex justify-between items-center mb-3">
                            <div class="text-xs font-bold text-slate-400">#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2 py-1 rounded border border-blue-100 truncate max-w-[80px]" title="{{ $p->nama ?? 'Tamu' }}">
                                {{ $p->nama ?? 'Tamu' }}
                            </div>
                        </div>

                        <!-- PERBAIKAN: Jalur bypass untuk parameter cetak iframe -->
                        <button type="button" onclick="cetakFoto('{{ route('admin.photo.update', [$p->id, 'progress']) }}', '/render-print/{{ $p->file_name }}')" class="block w-full text-center bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 font-semibold text-xs py-2.5 rounded-lg transition cursor-pointer">
                            Proses Cetak →
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            @else
                <div class="text-center py-16 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-medium">Belum ada foto yang masuk antrean.</div>
            @endif
        </div>

        <!-- TAB: ON GOING -->
        <div id="tab-ongoing" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">
            @if($ongoing->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <?php foreach($ongoing as $p): ?>
                    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition relative overflow-hidden flex flex-col">
                        <div class="absolute top-0 left-0 w-full h-1 bg-brand-blue"></div>
                        
                        <!-- PERBAIKAN -->
                        <img src="/render-print/{{ $p->file_name }}" class="w-full rounded-xl mb-4 bg-slate-50 aspect-video object-contain border border-slate-100">
                        
                        <div class="flex justify-between items-center mb-4">
                            <div class="text-xs font-bold text-slate-400">#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-[10px] font-bold text-brand-blue bg-blue-50 px-2 py-1 rounded border border-blue-100 truncate max-w-[80px]" title="{{ $p->nama ?? 'Tamu' }}">
                                {{ $p->nama ?? 'Tamu' }}
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 mt-auto">
                            <a href="{{ route('admin.photo.update', [$p->id, 'completed']) }}" class="block w-full text-center bg-brand-blue hover:bg-brand-hover text-white font-semibold text-xs py-2.5 rounded-lg transition shadow-sm">
                                🖨 Selesai & Keluar
                            </a>
                            <a href="{{ route('admin.photo.update', [$p->id, 'pending']) }}" onclick="return confirm('Kertas macet atau Print gagal? Klik OK untuk mengembalikan foto ini ke antrean PENDING agar bisa diprint ulang.')" class="block w-full text-center bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-semibold text-[11px] py-2.5 rounded-lg transition">
                                ⚠️ Gagal (Print Ulang)
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            @else
                <div class="text-center py-16 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-medium">Kosong. Mesin cetak sedang istirahat.</div>
            @endif
        </div>

        <!-- TAB: DONE -->
        <div id="tab-done" class="tab-content hidden animate-[fadeIn_0.3s_ease-in-out]">
            @if($done->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    <?php foreach($done as $p): ?>
                    <div class="bg-white p-4 rounded-2xl border border-slate-100 opacity-60 grayscale-[30%]">
                        
                        <!-- PERBAIKAN -->
                        <img src="/render-print/{{ $p->file_name }}" class="w-full rounded-xl mb-4 bg-slate-50 aspect-video object-contain">
                        
                        <div class="flex justify-between items-center mb-1">
                            <div class="text-xs font-bold text-slate-400">#{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-[10px] font-bold text-slate-500 truncate max-w-[80px]" title="{{ $p->nama ?? 'Tamu' }}">
                                {{ $p->nama ?? 'Tamu' }}
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 text-emerald-600 font-bold text-xs mt-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            Telah Dicetak
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            @else
                <div class="text-center py-16 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 font-medium">Belum ada riwayat cetak selesai.</div>
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
                btn.classList.remove('border-brand-blue', 'text-brand-blue', 'active-tab');
                btn.classList.add('border-transparent', 'text-slate-500');
                btn.querySelector('span').classList.replace('bg-blue-50', 'bg-slate-100');
                btn.querySelector('span').classList.replace('text-brand-blue', 'text-slate-600');
                btn.querySelector('span').classList.remove('border', 'border-blue-100');
            });
            document.getElementById(tabName).classList.remove('hidden');
            document.getElementById(tabName).classList.add('block');
            evt.currentTarget.classList.remove('border-transparent', 'text-slate-500');
            evt.currentTarget.classList.add('border-brand-blue', 'text-brand-blue', 'active-tab');
            evt.currentTarget.querySelector('span').classList.replace('bg-slate-100', 'bg-blue-50');
            evt.currentTarget.querySelector('span').classList.replace('text-slate-600', 'text-brand-blue');
            evt.currentTarget.querySelector('span').classList.add('border', 'border-blue-100');
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
                        <img src="${imageUrl}" onload="eksekusiCetak()" />
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