<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Filter & AI | DigiBAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { maroon: { 50: '#fff5f5', 600: '#7b1113', 700: '#5a0c0e' }, slate: { 800: '#1e293b', 900: '#0f172a' } } } } }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16">
            <div class="flex items-center gap-4">
                <span class="text-2xl font-black tracking-tight text-maroon-600">DIGI<span class="text-slate-900">BAR</span></span>
                <div class="hidden sm:block border-l border-slate-200 pl-4 ml-2">
                    <h1 class="text-sm font-bold text-slate-900">Business Digital Agency</h1>
                </div>
            </div>
            <div class="flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="/admin-home" class="hover:text-slate-900 transition py-5">Jadwal Kegiatan</a>
                <a href="/admin-home/frame" class="hover:text-slate-900 transition py-5">Manajemen Frame</a>
                <a href="/admin-home/filter" class="text-maroon-600 border-b-2 border-maroon-600 px-1 py-5">Manajemen Filter</a>
                <a href="/admin-home/settings" class="hover:text-slate-900 transition py-5">Pengaturan</a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl font-bold flex items-center gap-2 animate-[fadeIn_0.5s_ease-in-out]">
            ✅ {{ session('success') }}
        </div>
        @endif

        <form action="/admin-home/filter/save" method="POST">
            @csrf
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">Konfigurasi Filter & AI Studio</h2>
                    <p class="text-sm text-slate-500 mt-1">Aktifkan efek warna standar atau atur integrasi AI (Animasi/Kartun) untuk mesin photobooth.</p>
                </div>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-6 rounded-lg shadow-sm transition-all text-sm">
                    💾 Simpan Pengaturan
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-slate-400"></div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-xl">🎨</span>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Filter Warna Dasar (Lokal)</h3>
                                <p class="text-xs text-slate-500">Berjalan instan di mesin photobooth tanpa internet.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">Hitam Putih (B&W)</span>
                                    <span class="text-xs text-slate-500">Filter monokrom klasik.</span>
                                </div>
                                <input type="checkbox" name="bw_active" {{ optional($setting)->bw_active ? 'checked' : '' }} class="w-5 h-5 accent-maroon-600 rounded" data-filter="grayscale(100%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">Sepia / Vintage</span>
                                    <span class="text-xs text-slate-500">Nuansa hangat kecoklatan antik.</span>
                                </div>
                                <input type="checkbox" name="vintage_active" {{ optional($setting)->vintage_active ? 'checked' : '' }} class="w-5 h-5 accent-maroon-600 rounded" data-filter="sepia(80%)">
                            </label>

                            <label class="flex items-center justify-between p-4 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition">
                                <div>
                                    <span class="font-bold text-slate-800 text-sm block">High Contrast</span>
                                    <span class="text-xs text-slate-500">Penajaman warna dramatis.</span>
                                </div>
                                <input type="checkbox" name="contrast_active" {{ optional($setting)->contrast_active ? 'checked' : '' }} class="w-5 h-5 accent-maroon-600 rounded" data-filter="contrast(150%) saturate(120%)">
                            </label>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-amber-500"></div>
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-xl">✨</span>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Filter AI Premium (Cloud API)</h3>
                                <p class="text-xs text-slate-500">Memerlukan koneksi internet di lokasi untuk proses Post-Processing.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 mb-6 bg-amber-50 border border-amber-200 p-4 rounded-xl">
                            <input type="checkbox" name="ai_active" id="ai_toggle" {{ optional($setting)->ai_active ? 'checked' : '' }} class="w-5 h-5 accent-amber-600 rounded" onchange="toggleAiSettings()">
                            <div>
                                <label for="ai_toggle" class="font-bold text-amber-900 text-sm cursor-pointer block">Aktifkan Fitur Transformasi AI</label>
                                <span class="text-xs text-amber-700">Tamu dapat memilih untuk mengubah foto mereka menjadi animasi.</span>
                            </div>
                        </div>

                        <div id="ai_settings" class="space-y-4 transition-opacity duration-300 pl-2 border-l-2 border-slate-100 {{ optional($setting)->ai_active ? '' : 'opacity-50 pointer-events-none' }}">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Gaya Animasi (AI Model)</label>
                                <select name="ai_model" class="w-full rounded-lg border-slate-300 border px-4 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none font-semibold text-slate-700">
                                    <option value="anime" {{ optional($setting)->ai_model == 'anime' ? 'selected' : '' }}>🌸 Japanese Anime Style</option>
                                    <option value="pixar" {{ optional($setting)->ai_model == 'pixar' ? 'selected' : '' }}>🎬 3D Pixar/Disney Style</option>
                                    <option value="comic" {{ optional($setting)->ai_model == 'comic' ? 'selected' : '' }}>🗯️ Comic Book / Pop Art</option>
                                    <option value="cyberpunk" {{ optional($setting)->ai_model == 'cyberpunk' ? 'selected' : '' }}>🌃 Neon Cyberpunk</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">API Key (Provider Layanan)</label>
                                <input type="password" name="ai_api_key" value="{{ optional($setting)->ai_api_key }}" placeholder="Contoh: sk-xxxxxxxxxxxxxxxxxxxxxxxx" class="w-full rounded-lg border-slate-300 border px-4 py-2 text-sm focus:ring-2 focus:ring-amber-500 outline-none">
                                <p class="text-[11px] text-slate-400 mt-1">Gunakan kunci API dari penyedia Stable Diffusion (misal: Replicate / Segmind).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-24">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 text-center">Visual Preview</h3>
                        
                        <div class="w-full aspect-[3/4] bg-slate-200 rounded-xl overflow-hidden relative shadow-inner group">
                            <img src="https://images.unsplash.com/photo-1511556820780-d912e42b4980?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover transition-all duration-500 filter" id="preview_image">
                            
                            <div class="absolute bottom-0 left-0 w-full bg-gradient-to-t from-black/70 to-transparent p-4 text-white text-xs font-semibold">
                                Simulasi Efek: <span id="preview_label" class="text-amber-400">Normal (Tanpa Filter)</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-4 text-center">Preview ini hanya ilustrasi efek CSS standar. Hasil AI akan berbeda tergantung model yang digunakan.</p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleAiSettings() {
            const toggle = document.getElementById('ai_toggle');
            const settings = document.getElementById('ai_settings');
            
            if(toggle.checked) {
                settings.classList.remove('opacity-50', 'pointer-events-none');
            } else {
                settings.classList.add('opacity-50', 'pointer-events-none');
            }
        }

        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                const img = document.getElementById('preview_image');
                const textLabel = document.getElementById('preview_label');
                
                if (e.target.id === 'ai_toggle' && e.target.checked) {
                    img.style.filter = "contrast(120%) saturate(150%) hue-rotate(15deg)";
                    textLabel.innerText = "Menerapkan AI Style...";
                } else if (e.target.checked && e.target.dataset.filter) {
                    img.style.filter = e.target.dataset.filter;
                    textLabel.innerText = "Filter Terpilih Aktif";
                } else {
                    img.style.filter = "none";
                    textLabel.innerText = "Normal (Tanpa Filter)";
                }
            });
        });
    </script>
</body>
</html>