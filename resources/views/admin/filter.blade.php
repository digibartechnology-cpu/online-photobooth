<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Filter | DigiBAR</title>
    
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
                    <a href="/admin-home/frame" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Frame</a>
                    <a href="/admin-home/filter" class="text-brand-blue border-b-2 border-brand-blue px-2 py-5 transition-colors">Filter</a>
                    <a href="/admin-home/settings" class="hover:text-slate-900 hover:bg-slate-100 px-3 py-2 rounded-lg transition-all">Pengaturan</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 animate-fade-in-up">
        
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-bold flex items-center gap-3 shadow-sm animate-fade-in-up">
            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center text-xl">✅</div>
            {{ session('success') }}
        </div>
        @endif

        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 border-b border-slate-200 pb-4">
            <div>
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Konfigurasi Filter Studio</h2>
                <p class="text-sm text-slate-500 mt-1 font-medium">Atur dan aktifkan preset warna yang ingin ditampilkan pada layar photobooth tamu.</p>
            </div>
        </div>

        <form action="/admin-home/filter/save" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            @csrf
            
            <!-- KOLOM KIRI (Form Pengaturan) -->
            <div class="lg:col-span-7 space-y-8">
                
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="bg-slate-50/50 border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                            <span class="w-6 h-6 rounded bg-brand-blue text-white flex items-center justify-center text-xs">1</span>
                            Koleksi Filter Warna
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-xs font-semibold text-slate-500 mb-5 flex items-center gap-1">
                            <svg class="w-4 h-4 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Filter Original (Normal) selalu aktif secara otomatis. Centang opsi di bawah untuk menambah pilihan bagi tamu.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">☀️ Retro Warm</span>
                                    <span class="text-[11px] font-medium text-slate-500">Kekuningan hangat, efek golden hour.</span>
                                </div>
                                <input type="checkbox" name="retro_warm_active" {{ optional($setting)->retro_warm_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Retro Warm" data-filter="contrast(85%) saturate(110%) sepia(20%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">❄️ Classic Cool</span>
                                    <span class="text-[11px] font-medium text-slate-500">Rona kebiruan sejuk ala majalah 90-an.</span>
                                </div>
                                <input type="checkbox" name="retro_cool_active" {{ optional($setting)->retro_cool_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Classic Cool" data-filter="contrast(90%) saturate(105%) opacity(90%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">🎬 Cinematic</span>
                                    <span class="text-[11px] font-medium text-slate-500">Warna diredupkan dramatis ala bioskop.</span>
                                </div>
                                <input type="checkbox" name="cinematic_active" {{ optional($setting)->cinematic_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Cinematic" data-filter="contrast(120%) saturate(80%) brightness(95%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">💎 Ultra Clear</span>
                                    <span class="text-[11px] font-medium text-slate-500">Paling terang, tajam, dan jernih.</span>
                                </div>
                                <input type="checkbox" name="vibrant_active" {{ optional($setting)->vibrant_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Ultra Clear" data-filter="contrast(125%) saturate(130%) brightness(105%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">🎞️ Gritty Vintage</span>
                                    <span class="text-[11px] font-medium text-slate-500">Kesan kasar dengan kilat cahaya retro.</span>
                                </div>
                                <input type="checkbox" name="gritty_active" {{ optional($setting)->gritty_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Gritty Vintage" data-filter="contrast(80%) saturate(90%) sepia(40%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">🖤 Mono Classic</span>
                                    <span class="text-[11px] font-medium text-slate-500">Hitam putih kontras tinggi yang artistik.</span>
                                </div>
                                <input type="checkbox" name="bw_classic_active" {{ optional($setting)->bw_classic_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Mono Classic" data-filter="grayscale(100%) contrast(120%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">🤎 Sepia Dream</span>
                                    <span class="text-[11px] font-medium text-slate-500">Nuansa cokelat pudar ala foto jadul.</span>
                                </div>
                                <input type="checkbox" name="sepia_active" {{ optional($setting)->sepia_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Sepia Dream" data-filter="sepia(100%) contrast(90%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">🌸 Soft Pastel</span>
                                    <span class="text-[11px] font-medium text-slate-500">Cerah merona dengan sentuhan pink lembut.</span>
                                </div>
                                <input type="checkbox" name="pastel_active" {{ optional($setting)->pastel_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Soft Pastel" data-filter="contrast(90%) saturate(85%) sepia(5%) hue-rotate(-10deg)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-brand-blue hover:shadow-sm transition-all bg-slate-50/30 md:col-span-2 lg:col-span-1">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">🌑 Moody Dark</span>
                                    <span class="text-[11px] font-medium text-slate-500">Kontras tajam & bayangan pekat.</span>
                                </div>
                                <input type="checkbox" name="moody_active" {{ optional($setting)->moody_active ? 'checked' : '' }} class="filter-toggle w-5 h-5 accent-brand-blue rounded cursor-pointer" data-name="Moody Dark" data-filter="contrast(130%) saturate(70%) brightness(80%)">
                            </label>

                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan diletakkan di bawah form mirip desain Frame Editor -->
                <button type="submit" class="w-full flex items-center justify-center gap-2 bg-brand-blue hover:bg-brand-hover text-white font-extrabold py-4 rounded-xl shadow-md hover:shadow-lg transition-all text-sm uppercase tracking-wider">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                    Simpan Konfigurasi Filter
                </button>
            </div>

            <!-- KOLOM KANAN (Visual Preview) -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-24">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                        <svg class="w-5 h-5 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Live Preview Layar
                    </h3>
                    
                    <div class="flex justify-center bg-slate-50/80 rounded-xl p-4 border border-slate-200 items-center pattern-dots-sm">
                        <div class="w-full max-w-[280px] aspect-[3/4] bg-white rounded-lg shadow-md overflow-hidden relative group ring-1 ring-slate-200">
                            <img src="https://images.unsplash.com/photo-1511556820780-d912e42b4980?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover transition-all duration-500 filter" id="preview_image">
                            
                            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-slate-900/80 to-transparent p-4 text-white text-xs font-semibold text-center">
                                Efek Terakhir Dipilih:<br>
                                <span id="preview_label" class="text-amber-400 text-sm font-bold mt-1 inline-block">✨ Original (Normal)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
    </div>

    <script>
        document.querySelectorAll('.filter-toggle').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const img = document.getElementById('preview_image');
                const textLabel = document.getElementById('preview_label');
                
                if (e.target.checked && e.target.dataset.filter) {
                    img.style.filter = e.target.dataset.filter;
                    textLabel.innerHTML = `✨ ${e.target.dataset.name}`;
                } else {
                    img.style.filter = "none";
                    textLabel.innerHTML = "✨ Original (Normal)";
                }
            });
        });
    </script>
</body>
</html>