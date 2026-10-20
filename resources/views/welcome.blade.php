<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DigiBAR Photobooth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #080808; color: white; }
    </style>
</head>
<body class="min-h-screen flex flex-col p-6 items-center justify-center bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNCkiLz48L3N2Zz4=')]">

    <div class="w-full max-w-2xl text-center flex flex-col items-center">
        
        <!-- Logo DigiBAR -->
        <img src="{{ asset('Images/Logo_DigiBAR.png') }}" alt="Logo DigiBAR" class="h-16 md:h-20 object-contain mb-3 drop-shadow-[0_0_15px_rgba(255,255,255,0.1)]">
        
        <p class="text-sm md:text-base text-blue-400 font-bold tracking-widest uppercase mb-12">Online Photobooth Studio</p>

        <!-- KOTAK UTAMA -->
        <div class="w-full bg-[#111111] p-8 md:p-12 rounded-[2.5rem] border border-white/10 shadow-2xl relative overflow-hidden">
            <!-- Hiasan Cahaya Belakang -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#003eb3] rounded-full blur-[80px] opacity-40"></div>
            
            <!-- Perubahan Teks Santai & Profesional -->
            <h2 class="text-3xl md:text-4xl font-bold mb-3">Halo! 👋</h2>
            <p class="text-slate-400 text-base md:text-lg mb-10 px-4">Pilih event kamu di bawah ini, lalu scan voucher untuk memulai sesi foto.</p>

            <div class="flex flex-col gap-4 max-h-[45vh] overflow-y-auto pr-2 custom-scrollbar mb-8">
                
                <?php if(isset($events) && count($events) > 0): ?>
                    <?php foreach($events as $event): ?>
                        <a href="/event/<?php echo $event->id; ?>/scan" class="group relative flex items-center justify-between p-5 md:p-6 rounded-3xl bg-white/5 border border-white/10 hover:border-[#003eb3]/60 hover:bg-[#003eb3]/20 transition-all cursor-pointer">
                            <div class="text-left">
                                <h3 class="text-white font-bold text-xl md:text-2xl group-hover:text-blue-400 transition-colors"><?php echo $event->nama_kegiatan; ?></h3>
                                <p class="text-sm text-slate-500 mt-2">
                                    Berlaku: <?php echo \Carbon\Carbon::parse($event->waktu_buka)->format('d M Y'); ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-white/10 flex items-center justify-center group-hover:bg-[#003eb3] group-hover:text-white transition-all text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 border border-dashed border-white/10 rounded-3xl">
                        <p class="text-slate-500 text-base">Belum ada event yang aktif saat ini.</p>
                    </div>
                <?php endif; ?>

            </div>

            <!-- TOMBOL WHATSAPP BELI TIKET -->
            <div class="border-t border-white/10 pt-8 mt-4">
                <p class="text-slate-400 text-sm mb-4">Belum memiliki voucher?</p>
                <a href="https://wa.me/628559000857?text=Halo%20Admin%20DigiBAR,%20saya%20ingin%20membeli%20voucher%20Photobooth!" target="_blank" class="inline-flex items-center justify-center gap-3 w-full md:w-auto px-8 py-4 bg-[#25D366] hover:bg-[#1EBE5D] text-white font-bold rounded-2xl transition-all shadow-[0_4px_20px_rgba(37,211,102,0.3)]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z"/>
                    </svg>
                    Beli Voucher via WhatsApp
                </a>
            </div>

        </div>

        <p class="text-slate-600 text-xs mt-10">© <?php echo date('Y'); ?> DigiBAR Group. All rights reserved.</p>
    </div>

    <!-- Tombol Admin Rahasia -->
    <a href="{{ route('login') }}" class="fixed top-6 right-6 text-[10px] text-white/20 hover:text-white/50 tracking-widest uppercase transition-colors z-50">
        Admin Panel
    </a>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }
    </style>
</body>
</html>