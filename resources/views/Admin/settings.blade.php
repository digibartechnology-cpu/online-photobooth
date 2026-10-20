<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan | DigiBAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { maroon: { 600: '#7b1113', 700: '#5a0c0e' }, slate: { 800: '#1e293b', 900: '#0f172a' } } } } }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- TOP NAVIGATION -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <span class="text-2xl font-black tracking-tight text-maroon-600">DIGI<span class="text-slate-900">BAR</span></span>
                </div>
                <div class="hidden sm:block border-l border-slate-200 pl-4 ml-2">
                    <h1 class="text-sm font-bold text-slate-900">Business Digital Agency</h1>
                    <p class="text-xs font-medium text-slate-500">Digital Operational</p>
                </div>
            </div>
            <div class="flex items-center gap-6 text-sm font-medium text-slate-600">
                <a href="/admin-home" class="hover:text-slate-900 transition py-5">Jadwal Kegiatan</a>
                <a href="/admin-home/frame" class="hover:text-slate-900 transition py-5">Frame</a>
                <a href="/admin-home/filter" class="hover:text-slate-900 transition py-5">Filter</a>
                <a href="/admin-home/settings" class="text-maroon-600 border-b-2 border-maroon-600 px-1 py-5">Pengaturan</a>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-900">Pengaturan Sistem</h2>
            <p class="text-sm text-slate-500 mt-1">Konfigurasi operasional dan keamanan aplikasi Photobooth.</p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                ⚙️ Konfigurasi Mesin Cetak
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ukuran Struk Thermal (QR)</label>
                    <select class="w-full rounded-lg border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600 outline-none">
                        <option>58mm (Printer Kasir Kecil)</option>
                        <option>80mm (Printer Thermal Besar)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Penyimpanan Foto Studio</label>
                    <select class="w-full rounded-lg border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600 outline-none">
                        <option>Lokal Server (Storage/App)</option>
                        <option disabled>Cloud Storage (Akan Datang)</option>
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button class="bg-slate-800 hover:bg-slate-900 text-white font-semibold py-2 px-6 rounded-lg text-sm transition">Simpan Konfigurasi</button>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 border-b border-slate-100 pb-3 flex items-center gap-2">
                🔒 Keamanan Akses
            </h3>
            
            <div class="space-y-4 max-w-md">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password Portal Admin Baru</label>
                    <input type="password" placeholder="Masukkan password baru" class="w-full rounded-lg border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" placeholder="Ketik ulang password" class="w-full rounded-lg border-slate-300 border px-4 py-2.5 text-sm focus:ring-2 focus:ring-maroon-600 outline-none">
                </div>
                <button class="bg-maroon-600 hover:bg-maroon-700 text-white font-semibold py-2 px-6 rounded-lg text-sm transition mt-2">Update Password</button>
            </div>
        </div>

    </div>
</body>
</html>