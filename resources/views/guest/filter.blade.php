<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Bingkai | DigiBAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #080808; color: white; touch-action: pan-y; }
        .glass-panel { background: rgba(20, 20, 25, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); }
        #preview-canvas { max-width: 100%; max-height: 55vh; width: auto; height: auto; background: #ffffff; border-radius: 8px; box-shadow: 0 15px 50px rgba(0,0,0,0.6); display: block; margin: 0 auto; cursor: zoom-in; transition: transform 0.2s; }
        #preview-canvas:hover { transform: scale(1.02); }
        .zoom-active { cursor: grab; }
        .zoom-active:active { cursor: grabbing; }
        .hide-scroll::-webkit-scrollbar { height: 6px; }
        .hide-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px; }
        .hide-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 10px; }
    </style>
</head>
<body class="min-h-screen flex flex-col p-4 md:p-8">

    <div class="w-full max-w-6xl mx-auto flex flex-col md:flex-row gap-8 relative z-10">
        
        <div class="w-full md:w-[45%] flex flex-col items-center justify-center relative">
            <h1 class="text-3xl font-black tracking-tight mb-1 text-center">
                <img src="{{ asset('Images/Logo_DigiBAR.png') }}" alt="Logo DigiBAR" class="h-10 md:h-12 object-contain mx-auto">
            </h1>
            <p class="text-xs text-blue-400 font-bold tracking-widest uppercase mt-3 mb-6 text-center">📸 Pratinjau Foto Anda</p>
            
            <div class="w-full flex flex-col items-center justify-center p-2 relative group">
                <div class="absolute top-4 bg-black/60 text-white text-xs px-3 py-1.5 rounded-full backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-10 border border-white/20">
                    🔍 Klik foto untuk cek detail
                </div>
                <canvas id="preview-canvas" title="Klik untuk memperbesar"></canvas>
            </div>
        </div>

        <div class="w-full md:w-[55%] flex flex-col gap-6">
            
            <div class="glass-panel p-6 rounded-3xl">
                <h3 class="text-xs font-bold text-slate-400 mb-4 tracking-widest uppercase flex justify-between">
                    <span>1. Pilih Bingkai</span>
                </h3>
                <div class="flex overflow-x-auto gap-4 pb-2 hide-scroll">
                    <?php if (count($frames) > 0): ?>
                        <?php foreach ($frames as $frame): ?>
                            <?php
                                $layout = $frame->layout_data;
                                if(empty($layout)) $layout = [['x'=>5,'y'=>5,'w'=>90,'h'=>90]];
                                $encodedLayout = base64_encode(is_string($layout) ? $layout : json_encode($layout));
                            ?>
                            <button type="button" 
                                    class="btn-pilih-bingkai flex-shrink-0 w-20 md:w-24 border-0 bg-transparent cursor-pointer hover:scale-105 transition-transform outline-none focus:ring-4 focus:ring-blue-500 rounded-xl"
                                    data-img="/render-frame/{{ $frame->file_name }}"
                                    data-layout="{{ $encodedLayout }}">
                                <img src="/render-frame/{{ $frame->file_name }}" class="w-full h-auto rounded-xl border border-white/10 pointer-events-none shadow-lg">
                                <p class="text-[10px] md:text-[11px] text-center mt-3 font-semibold text-slate-300 pointer-events-none">{{ $frame->nama_frame }}</p>
                            </button>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-xs text-blue-400">Belum ada bingkai yang diupload.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="glass-panel p-6 rounded-3xl">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xs font-bold text-slate-400 tracking-widest uppercase">2. Koleksi Tone Warna</h3>
                </div>
                
                <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
                    <button type="button" class="btn-filter active p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="none">
                        <div class="text-2xl mb-1.5">✨</div>
                        <div class="text-xs font-black leading-tight mb-1.5 text-white">Original</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Warna asli sensor kamera yang jujur dan natural.</div>
                    </button>
                    
                    @if(optional($setting)->retro_warm_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="retro-warm">
                        <div class="text-2xl mb-1.5">☀️</div>
                        <div class="text-xs font-black text-amber-400 leading-tight mb-1.5">Retro Warm</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Kekuningan hangat, wajah glowing layaknya golden hour.</div>
                    </button>
                    @endif

                    @if(optional($setting)->retro_cool_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="retro-cool">
                        <div class="text-2xl mb-1.5">❄️</div>
                        <div class="text-xs font-black text-teal-400 leading-tight mb-1.5">Classic Cool</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Rona kebiruan sejuk. Nostalgia ala majalah tahun 90-an.</div>
                    </button>
                    @endif

                    @if(optional($setting)->cinematic_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="cinematic">
                        <div class="text-2xl mb-1.5">🎬</div>
                        <div class="text-xs font-black text-emerald-400 leading-tight mb-1.5">Cinematic</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Warna diredupkan ciptakan kesan dramatis bioskop.</div>
                    </button>
                    @endif

                    @if(optional($setting)->vibrant_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="vibrant">
                        <div class="text-2xl mb-1.5">💎</div>
                        <div class="text-xs font-black text-blue-400 leading-tight mb-1.5">Ultra Clear</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Paling terang, tajam, dan warna-warni super jernih.</div>
                    </button>
                    @endif

                    @if(optional($setting)->gritty_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="gritty">
                        <div class="text-2xl mb-1.5">🎞️</div>
                        <div class="text-xs font-black text-rose-400 leading-tight mb-1.5">Gritty Vintage</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Bintik kasar (grain) & kilatan cahaya oranye retro.</div>
                    </button>
                    @endif

                    @if(optional($setting)->bw_classic_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="bw-classic">
                        <div class="text-2xl mb-1.5">🖤</div>
                        <div class="text-xs font-black text-slate-300 leading-tight mb-1.5">Mono Classic</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Hitam putih kontras tinggi. Elegan dan artistik.</div>
                    </button>
                    @endif

                    @if(optional($setting)->sepia_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="sepia">
                        <div class="text-2xl mb-1.5">🤎</div>
                        <div class="text-xs font-black text-orange-300 leading-tight mb-1.5">Sepia Dream</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Nuansa cokelat pudar layaknya foto antik jadul.</div>
                    </button>
                    @endif

                    @if(optional($setting)->pastel_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="pastel">
                        <div class="text-2xl mb-1.5">🌸</div>
                        <div class="text-xs font-black text-pink-400 leading-tight mb-1.5">Soft Pastel</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Cerah dengan sentuhan pink lembut. Mulus ala Korea.</div>
                    </button>
                    @endif

                    @if(optional($setting)->moody_active)
                    <button type="button" class="btn-filter p-3 rounded-2xl border border-white/10 hover:border-blue-500/40 text-center bg-white/5 transition-all focus:outline-none flex flex-col justify-start h-full" data-filter="moody">
                        <div class="text-2xl mb-1.5">🌑</div>
                        <div class="text-xs font-black text-indigo-400 leading-tight mb-1.5">Moody Dark</div>
                        <div class="text-[10px] text-slate-200 font-medium leading-snug">Kontras tajam & bayangan pekat untuk gaya cool.</div>
                    </button>
                    @endif
                </div>
            </div>

            <div class="flex flex-col gap-3 mt-auto">
                <div class="flex gap-2 md:gap-3">
                    <button type="button" id="btn-ulang" class="w-1/3 bg-white/5 hover:bg-white/10 text-slate-300 font-bold py-3 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs transition-all border border-white/10 flex flex-col md:flex-row items-center justify-center gap-1 text-center">
                        <span class="text-base">🗑️</span> Buang Ulang
                    </button>
                    
                    <button type="button" id="btn-ganti" class="w-1/3 bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 font-bold py-3 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs transition-all border border-blue-500/30 flex flex-col md:flex-row items-center justify-center gap-1 text-center">
                        <span class="text-base">🔄</span> Ganti Pilihan
                    </button>

                    <button type="button" id="btn-download" class="w-1/3 bg-slate-700/50 hover:bg-slate-700/80 text-white font-bold py-3 md:py-4 rounded-xl md:rounded-2xl text-[10px] md:text-xs transition-all border border-white/10 flex flex-col md:flex-row items-center justify-center gap-1 text-center">
                        <span class="text-base">⬇️</span> Unduh Foto
                    </button>
                </div>
                
                <button type="button" id="btn-cetak" class="w-full bg-gradient-to-r from-[#003eb3] to-[#002e8a] hover:from-[#002e8a] hover:to-[#001f5c] text-white font-black py-4 md:py-5 rounded-2xl md:rounded-3xl text-base md:text-lg shadow-[0_10px_40px_rgba(0,62,179,0.4)] transition-all cursor-pointer">
                    🖨️ Simpan & Cetak Foto
                </button>
            </div>
            
        </div>
    </div>

    <!-- Modals -->
    <div id="modal-zoom" class="fixed inset-0 bg-black/95 backdrop-blur-xl z-[60] flex items-center justify-center p-0 hidden">
        <div id="zoom-hint-anim" class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 flex flex-col items-center justify-center bg-[#1a0a0a]/80 border border-white/10 px-8 py-6 rounded-[2rem] shadow-2xl backdrop-blur-md text-white transition-opacity duration-700 opacity-0 pointer-events-none z-[70]">
            <div class="flex gap-6 mb-4 items-center justify-center"><div class="animate-[pulse_1s_ease-in-out_infinite] text-5xl transform -rotate-45">👆</div><div class="animate-[pulse_1s_ease-in-out_infinite] text-5xl transform rotate-[135deg]" style="animation-delay: 0.5s">👆</div></div>
            <p class="text-lg font-black tracking-wide text-center uppercase">Zoom & Geser</p>
            <p class="text-sm text-slate-300 text-center mt-1">untuk cek detail foto</p>
        </div>
        <div class="relative w-full h-full flex items-center justify-center overflow-hidden touch-none" id="zoom-container">
            <button type="button" id="btn-tutup-zoom" class="absolute top-6 right-6 text-white bg-black/50 hover:bg-blue-600/80 rounded-full w-12 h-12 flex items-center justify-center text-2xl transition-colors z-[80] shadow-xl border border-white/20 backdrop-blur-sm">✕</button>
            <img id="gambar-zoom" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl origin-center select-none pointer-events-none">
        </div>
    </div>

    <div id="modal-antrian" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
        <div class="glass-panel w-full max-w-md p-8 rounded-3xl text-center border border-white/20 shadow-2xl scale-100 transition-transform">
            <div class="w-20 h-20 bg-blue-600/20 text-blue-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-4 border border-blue-600/30">🎉</div>
            <h2 class="text-2xl font-black tracking-tight mb-2">Berhasil!</h2>
            <p class="text-slate-400 text-sm mb-6">Foto Anda telah tersimpan dan siap dicetak.</p>
            
            <div class="bg-black/40 border border-white/10 rounded-2xl p-6 mb-6 overflow-hidden w-full flex flex-col items-center">
                <span class="text-xs uppercase tracking-widest text-slate-400 font-bold block mb-1">Cetak Atas Nama</span>
                <div class="w-full flex justify-center items-center">
                    <span id="nomor-antrian-teks" class="text-4xl md:text-5xl font-black text-blue-500 tracking-wider whitespace-nowrap origin-center" style="display: inline-block; transition: transform 0.2s;">--</span>
                </div>
            </div>
            
            <button type="button" id="btn-tutup-modal" class="w-full bg-[#003eb3] hover:bg-[#002e8a] text-white font-black py-4 rounded-2xl text-base shadow-lg transition-all cursor-pointer">Selesai & Kembali ke Beranda</button>
        </div>
    </div>

    <div id="modal-error" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-[#1a0a0a] border border-red-500/30 w-full max-w-sm p-8 rounded-3xl text-center shadow-2xl">
            <div class="w-20 h-20 bg-red-500/20 text-red-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-4 border border-red-500/30">⚠️</div>
            <h2 class="text-xl font-black tracking-tight mb-2 text-white">Oops, Gagal!</h2>
            <p id="error-teks" class="text-red-400 text-sm mb-6">Terjadi kesalahan pada sistem.</p>
            <button type="button" id="btn-tutup-error" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-4 rounded-2xl text-sm transition-all cursor-pointer">Tutup & Coba Lagi</button>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('preview-canvas'); const ctx = canvas.getContext('2d'); canvas.width = 1200; canvas.height = 1800;
        ctx.fillStyle = '#ffffff'; ctx.fillRect(0, 0, 1200, 1800); ctx.fillStyle = '#1e293b'; ctx.font = 'bold 35px Arial'; ctx.textAlign = 'center'; ctx.fillText("✨ SILAKAN KLIK BINGKAI ✨", 600, 900);

        let fotoWajah = []; let bingkaiAktif = null; let layoutAktif = []; let filterAktif = 'none';

        const mentah = localStorage.getItem('digibar_raw_photos');
        if (mentah) { try { let arr = JSON.parse(mentah); if (Array.isArray(arr)) { arr.forEach(function(b64) { let img = new Image(); img.src = b64; fotoWajah.push(img); }); } } catch(e) {} }

        let noiseCanvasPattern = null;
        function getNoisePattern(ctxTarget) {
            if (!noiseCanvasPattern) {
                let offscreen = document.createElement('canvas');
                offscreen.width = 250; offscreen.height = 250;
                let oCtx = offscreen.getContext('2d');
                for (let i = 0; i < 2500; i++) {
                    oCtx.fillStyle = 'rgba(0,0,0,0.08)';
                    oCtx.fillRect(Math.random() * 250, Math.random() * 250, 1.5, 1.5);
                    oCtx.fillStyle = 'rgba(255,255,255,0.06)';
                    oCtx.fillRect(Math.random() * 250, Math.random() * 250, 1.5, 1.5);
                }
                noiseCanvasPattern = ctxTarget.createPattern(offscreen, 'repeat');
            }
            return noiseCanvasPattern;
        }

        function terapkanPresetAnalog(ctxTarget, x, y, w, h, tipe) {
            if (tipe === 'none' || tipe === 'vibrant' || tipe === 'cinematic') return; 
            ctxTarget.save(); ctxTarget.globalCompositeOperation = "color";
            if (tipe === 'retro-warm') { ctxTarget.fillStyle = "rgba(230, 180, 100, 0.20)"; } else if (tipe === 'retro-cool') { ctxTarget.fillStyle = "rgba(100, 180, 150, 0.15)"; } else if (tipe === 'gritty') { ctxTarget.fillStyle = "rgba(255, 120, 50, 0.25)";  } else if (tipe === 'pastel') { ctxTarget.fillStyle = "rgba(255, 180, 200, 0.15)"; } else if (tipe === 'moody') { ctxTarget.fillStyle = "rgba(20, 40, 60, 0.25)"; } else if (tipe === 'sepia') { ctxTarget.fillStyle = "rgba(180, 130, 70, 0.20)"; }
            ctxTarget.fillRect(x, y, w, h); ctxTarget.restore();
            
            if (tipe === 'retro-warm' || tipe === 'gritty') {
                ctxTarget.save(); let gradient = ctxTarget.createLinearGradient(x, y, x + (w * 0.5), y + (h * 0.5));
                if (tipe === 'retro-warm') { gradient.addColorStop(0, "rgba(255, 180, 50, 0.15)"); gradient.addColorStop(1, "rgba(255, 255, 255, 0)"); } else { gradient.addColorStop(0, "rgba(255, 80, 0, 0.3)"); gradient.addColorStop(0.5, "rgba(255, 150, 0, 0.1)"); gradient.addColorStop(1, "rgba(255, 255, 255, 0)"); }
                ctxTarget.globalCompositeOperation = "screen"; ctxTarget.fillStyle = gradient; ctxTarget.fillRect(x, y, w, h); ctxTarget.restore();
            }
            
            if (['retro-warm', 'retro-cool', 'gritty', 'bw-classic', 'sepia'].includes(tipe)) {
                ctxTarget.save(); 
                ctxTarget.globalCompositeOperation = "overlay"; 
                ctxTarget.fillStyle = getNoisePattern(ctxTarget);
                ctxTarget.globalAlpha = (tipe === 'gritty') ? 1.0 : 0.4;
                ctxTarget.fillRect(x, y, w, h);
                ctxTarget.restore();
            }
        }

        function gambarKanvas() {
            if (!bingkaiAktif) return;
            ctx.fillStyle = '#ffffff'; ctx.fillRect(0, 0, canvas.width, canvas.height);
            let slots = Array.isArray(layoutAktif) ? layoutAktif : Object.values(layoutAktif);
            for (let i = 0; i < slots.length; i++) {
                if (i >= fotoWajah.length) break;
                let slot = slots[i]; let foto = fotoWajah[i]; if (!foto || !foto.width) continue; 
                let percentX = parseFloat(slot.x) || 0; let percentY = parseFloat(slot.y) || 0; let percentW = parseFloat(slot.w) || 100; let percentH = parseFloat(slot.h) || 100;
                let sx = (percentX / 100) * canvas.width; let sy = (percentY / 100) * canvas.height; let sw = (percentW / 100) * canvas.width; let sh = (percentH / 100) * canvas.height;
                
                let scale = Math.max(sw / foto.width, sh / foto.height); 
                let dw = foto.width * scale; 
                let dh = foto.height * scale; 
                let dx = sx + (sw - dw) / 2; 
                let dy = sy + (sh - dh) * 0.25;
                
                ctx.save(); ctx.beginPath(); ctx.rect(sx, sy, sw, sh); ctx.clip();
                if (filterAktif === 'retro-warm') { ctx.filter = 'contrast(85%) saturate(110%) brightness(105%) sepia(20%)'; } else if (filterAktif === 'retro-cool') { ctx.filter = 'contrast(90%) saturate(105%) brightness(110%) sepia(5%)'; } else if (filterAktif === 'gritty') { ctx.filter = 'contrast(80%) saturate(90%) brightness(100%) sepia(40%)'; } else if (filterAktif === 'cinematic') { ctx.filter = 'contrast(120%) saturate(80%) brightness(95%) sepia(10%)'; } else if (filterAktif === 'vibrant') { ctx.filter = 'contrast(125%) saturate(130%) brightness(105%)'; } else if (filterAktif === 'bw-classic') { ctx.filter = 'grayscale(100%) contrast(120%) brightness(105%)'; } else if (filterAktif === 'sepia') { ctx.filter = 'sepia(100%) contrast(90%) brightness(95%)'; } else if (filterAktif === 'pastel') { ctx.filter = 'contrast(90%) saturate(85%) brightness(115%) sepia(5%) hue-rotate(-10deg)'; } else if (filterAktif === 'moody') { ctx.filter = 'contrast(130%) saturate(70%) brightness(80%)'; } else { ctx.filter = 'none'; }
                
                ctx.drawImage(foto, dx, dy, dw, dh); ctx.restore(); terapkanPresetAnalog(ctx, sx, sy, sw, sh, filterAktif);
            }
            ctx.filter = 'none'; ctx.drawImage(bingkaiAktif, 0, 0, canvas.width, canvas.height);
        }

        const tombolBingkai = document.querySelectorAll('.btn-pilih-bingkai');
        tombolBingkai.forEach(function(btn) {
            btn.addEventListener('click', function() {
                let url = this.getAttribute('data-img'); let b64 = this.getAttribute('data-layout');
                try { let parsed = JSON.parse(atob(b64)); layoutAktif = Array.isArray(parsed) ? parsed : (typeof parsed === 'object' && parsed !== null ? Object.values(parsed) : [{x:5, y:5, w:90, h:90}]); } catch (e) { layoutAktif = [{x:5, y:5, w:90, h:90}]; }
                let img = new Image(); img.onload = function() { bingkaiAktif = img; canvas.width = img.width || 1200; canvas.height = img.height || 1800; gambarKanvas(); }; img.src = url;
            });
        });
        if(tombolBingkai.length > 0) { setTimeout(() => { tombolBingkai[0].click(); }, 200); }

        const tombolFilter = document.querySelectorAll('.btn-filter');
        tombolFilter.forEach(function(btn) {
            btn.addEventListener('click', function() {
                filterAktif = this.getAttribute('data-filter');
                tombolFilter.forEach(b => { b.classList.remove('active', 'border-blue-500'); b.classList.add('border-white/10'); b.style.backgroundColor = 'rgba(255,255,255,0.05)'; });
                this.classList.add('active', 'border-blue-500'); this.classList.remove('border-white/10'); this.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
                
                if(bingkaiAktif) {
                    requestAnimationFrame(() => { setTimeout(() => { gambarKanvas(); }, 10); });
                }
            });
        });

        const modalZoom = document.getElementById('modal-zoom'); const gambarZoom = document.getElementById('gambar-zoom'); const zoomContainer = document.getElementById('zoom-container'); const hintAnim = document.getElementById('zoom-hint-anim');
        let scale = 1, posX = 0, posY = 0, isDragging = false, startX, startY, initialClickX, initialClickY, hintTimeout;
        function setTransform() { gambarZoom.style.transform = `translate(${posX}px, ${posY}px) scale(${scale})`; }
        function resetZoomState() { scale = 1; posX = 0; posY = 0; setTransform(); zoomContainer.classList.remove('zoom-active'); }
        canvas.addEventListener('click', function() { if(!bingkaiAktif) return; gambarZoom.src = canvas.toDataURL('image/jpeg', 1.0); modalZoom.classList.remove('hidden'); resetZoomState(); hintAnim.classList.remove('opacity-0'); hintAnim.classList.add('opacity-100'); clearTimeout(hintTimeout); hintTimeout = setTimeout(() => { hintAnim.classList.remove('opacity-100'); hintAnim.classList.add('opacity-0'); }, 3000); });
        
        function onPointerDown(e) { if (e.target.id === 'btn-tutup-zoom') return; isDragging = true; let clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX; let clientY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY; initialClickX = clientX; initialClickY = clientY; startX = clientX - posX; startY = clientY - posY; if(scale > 1) zoomContainer.classList.add('zoom-active'); }
        function onPointerMove(e) { if (!isDragging) return; e.preventDefault(); let clientX = e.type.includes('mouse') ? e.clientX : e.touches[0].clientX; let clientY = e.type.includes('mouse') ? e.clientY : e.touches[0].clientY; if (scale > 1) { posX = clientX - startX; posY = clientY - startY; setTransform(); } }
        function onPointerUp(e) { if (!isDragging) return; isDragging = false; zoomContainer.classList.remove('zoom-active'); let clientX = e.type.includes('mouse') ? e.clientX : (e.changedTouches ? e.changedTouches[0].clientX : initialClickX); let clientY = e.type.includes('mouse') ? e.clientY : (e.changedTouches ? e.changedTouches[0].clientY : initialClickY); let moveDistance = Math.hypot(clientX - initialClickX, clientY - initialClickY); if (moveDistance < 5 && e.target === zoomContainer) { modalZoom.classList.add('hidden'); resetZoomState(); hintAnim.classList.add('opacity-0'); } }
        
        zoomContainer.addEventListener('mousedown', onPointerDown); window.addEventListener('mousemove', onPointerMove, {passive: false}); window.addEventListener('mouseup', onPointerUp); zoomContainer.addEventListener('touchstart', onPointerDown, {passive: false}); zoomContainer.addEventListener('touchmove', onPointerMove, {passive: false}); zoomContainer.addEventListener('touchend', onPointerUp);
        document.getElementById('btn-tutup-zoom').addEventListener('click', function() { modalZoom.classList.add('hidden'); resetZoomState(); hintAnim.classList.add('opacity-0'); });
        zoomContainer.addEventListener('wheel', (e) => { e.preventDefault(); const delta = Math.sign(e.deltaY) * -0.2; scale += delta; scale = Math.min(Math.max(1, scale), 6); if (scale === 1) { posX = 0; posY = 0; } setTransform(); }, {passive: false});
        
        let initialPinchDistance = null; 
        zoomContainer.addEventListener('touchstart', (e) => { if (e.touches.length === 2) { isDragging = false; initialPinchDistance = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY); } }, {passive: false});
        zoomContainer.addEventListener('touchmove', (e) => { if (e.touches.length === 2 && initialPinchDistance) { e.preventDefault(); const currentDistance = Math.hypot(e.touches[0].clientX - e.touches[1].clientX, e.touches[0].clientY - e.touches[1].clientY); const distanceChange = currentDistance - initialPinchDistance; scale += distanceChange * 0.01; scale = Math.min(Math.max(1, scale), 6); initialPinchDistance = currentDistance; if (scale === 1) { posX = 0; posY = 0; } setTransform(); } }, {passive: false}); 
        zoomContainer.addEventListener('touchend', (e) => { if (e.touches.length < 2) initialPinchDistance = null; });

        document.getElementById('btn-ulang').addEventListener('click', function() { localStorage.removeItem('digibar_raw_photos'); localStorage.removeItem('digibar_all_photos'); window.location.href = '/studio'; });
        document.getElementById('btn-ganti').addEventListener('click', function() { window.location.href = '/studio?reselect=true'; });
        document.getElementById('btn-download').addEventListener('click', function() { if(!bingkaiAktif) return showError("Pilih bingkai terlebih dahulu untuk diunduh!"); const link = document.createElement('a'); link.download = 'DigiBAR_Photobooth_' + Date.now() + '.jpg'; link.href = canvas.toDataURL('image/jpeg', 1.0); link.click(); });

        function fireConfetti() {
            var duration = 3000; var animationEnd = Date.now() + duration; var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 9999 };
            function randomInRange(min, max) { return Math.random() * (max - min) + min; }
            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now(); if (timeLeft <= 0) return clearInterval(interval); 
                var particleCount = 50 * (timeLeft / duration);
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
            }, 250);
        }

        function showError(message) { document.getElementById('error-teks').innerText = message; document.getElementById('modal-error').classList.remove('hidden'); document.getElementById('btn-cetak').innerHTML = "🖨️ Simpan & Cetak Foto"; document.getElementById('btn-cetak').disabled = false; }
        document.getElementById('btn-tutup-error').addEventListener('click', function() { document.getElementById('modal-error').classList.add('hidden'); });

        document.getElementById('btn-cetak').addEventListener('click', function() {
            if (!bingkaiAktif) return showError("Pilih bingkai terlebih dahulu!");
            this.innerHTML = "Memproses Roll Film... ⏳"; this.disabled = true;
            let base64Image = canvas.toDataURL('image/jpeg', 0.9); 
            fetch('/api/cetak-foto', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }, body: JSON.stringify({ nama: {!! json_encode(session('guest_name') ?? 'Tamu') !!}, gambar: base64Image }) })
            .then(async res => { const rawText = await res.text(); try { const data = JSON.parse(rawText); if (!res.ok) throw new Error((data && data.message) || "Koneksi gagal."); return data; } catch (err) { throw new Error("Server menolak! Coba perkecil ukuran atau muat ulang halaman."); } })
            .then(data => {
                if(data && data.status === 'success') {
                    localStorage.removeItem('digibar_raw_photos'); localStorage.removeItem('digibar_all_photos');
                    
                    let namaAntrean = data.antrean || 'Tamu';
                    let textEl = document.getElementById('nomor-antrian-teks');
                    
                    textEl.innerText = namaAntrean; 
                    textEl.style.transform = 'scale(1)';
                    
                    document.getElementById('modal-antrian').classList.remove('hidden'); 
                    
                    setTimeout(() => {
                        let parentEl = textEl.parentElement;
                        if (textEl.scrollWidth > parentEl.clientWidth) {
                            let scaleRatio = parentEl.clientWidth / textEl.scrollWidth;
                            textEl.style.transform = `scale(${scaleRatio - 0.05})`; 
                        }
                    }, 50);
                    
                    fireConfetti();
                } else { showError(data ? data.message : 'Terjadi kesalahan sistem.'); }
            }).catch(err => { showError(err.message || "Gagal terhubung ke server."); });
        });

        document.getElementById('btn-tutup-modal').addEventListener('click', function() { window.location.href = "/"; });
    </script>
</body>
</html>