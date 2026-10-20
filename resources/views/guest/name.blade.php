<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Isi Nama | DigiBAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #080808; color: white; }
        .glass-panel { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        
        /* --- KUMPULAN ANIMASI KEREN --- */
        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .anim-muncul {
            opacity: 0;
            animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        /* Delay agar munculnya bergantian (Cascade effect) */
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNCkiLz48L3N2Zz4=')]">
    
    <!-- Efek Cahaya Belakang CatchTime Blue -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-80 h-80 bg-[#003eb3] rounded-full blur-[120px] opacity-40 pointer-events-none"></div>

    <div class="w-full max-w-md z-10 relative">
        <!-- Box Utama: Muncul pertama -->
        <div class="glass-panel p-8 md:p-10 rounded-[2.5rem] shadow-2xl anim-muncul">
            
            <!-- Teks Sapaan: Muncul kedua -->
            <div class="text-center mb-8 anim-muncul delay-100">
                <div class="w-16 h-16 bg-blue-500/10 text-blue-400 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 border border-blue-500/20 shadow-[0_0_20px_rgba(0,62,179,0.3)]">
                    👋
                </div>
                <h2 class="text-3xl font-black mb-2 text-white tracking-tight">Halo, Bro/Sis!</h2>
                <p class="text-slate-400 text-sm md:text-base">Kenalan dulu yuk. Ketik nama kamu di bawah, biar nanti gampang pas cari fotonya.</p>
            </div>

            <!-- Form: Muncul ketiga -->
            <form action="/simpan-nama" method="POST" class="flex flex-col gap-6 anim-muncul delay-200">
                @csrf
                <div>
                    <input type="text" name="guest_name" required autocomplete="off" placeholder="Ketik nama kamu..." 
                           class="w-full bg-black/50 border border-white/20 text-white rounded-2xl px-6 py-4 text-center text-lg font-bold focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/50 transition-all placeholder:text-slate-600 placeholder:font-normal">
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-[#003eb3] to-[#0055ff] hover:from-[#002e8a] hover:to-[#003eb3] text-white font-extrabold py-4 rounded-2xl text-lg shadow-[0_10px_30px_rgba(0,62,179,0.3)] transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                    Lanjut Foto 📸
                </button>
            </form>
            
        </div>
        
        <!-- Copyright: Muncul terakhir -->
        <p class="text-slate-600 text-xs text-center mt-8 anim-muncul delay-300">© <?php echo date('Y'); ?> DigiBAR Group. All rights reserved.</p>
    </div>

</body>
</html>