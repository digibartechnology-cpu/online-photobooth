<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem | DigiBAR Photobooth</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { maroon: { 50: '#fff5f5', 600: '#7b1113', 700: '#5a0c0e' }, slate: { 800: '#1e293b', 900: '#0f172a' } } } } }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 antialiased">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border-t-4 border-maroon-600 overflow-hidden">
        <div class="p-8">
            
            <!-- Logo & Branding -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-black tracking-tight text-maroon-600 mb-1">DIGI<span class="text-slate-900">BAR</span></h1>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Business Digital Agency</p>
                
                <div class="mt-8 bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800">Login Sistem Operasional</h2>
                    <p class="text-xs text-slate-500 mt-1">Masukkan kredensial akses untuk mengelola mesin photobooth.</p>
                </div>
            </div>

            <!-- Notifikasi Error Login -->
            @if(session('error'))
            <div class="mb-6 p-3 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg text-sm font-bold flex items-center justify-center gap-2 animate-[fadeIn_0.3s_ease-in-out]">
                ❌ {{ session('error') }}
            </div>
            @endif

            <!-- Form Login -->
            <form action="/proses-login" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Username Akses</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            👤
                        </div>
                        <input type="text" name="username" required placeholder="Ketik username Anda..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-maroon-600 outline-none transition bg-slate-50 focus:bg-white text-sm font-medium text-slate-800">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Password Akses</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            🔒
                        </div>
                        <input type="password" name="password" required placeholder="Ketik password..." class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-maroon-600 outline-none transition bg-slate-50 focus:bg-white text-sm font-medium text-slate-800">
                    </div>
                </div>

                <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-3.5 rounded-xl transition-all shadow-md mt-4 flex items-center justify-center gap-2">
                    Masuk ke Sistem Dashboard
                </button>
            </form>
            
        </div>
        
        <div class="bg-slate-100 p-4 text-center border-t border-slate-200">
            <p class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                &copy; {{ date('Y') }} DigiBAR Group. All Rights Reserved.
            </p>
        </div>
    </div>

</body>
</html>