<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan Tiket | DigiBAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #050505; color: white; }
        #reader { border: none !important; background: transparent; }
        #reader video, #reader img { object-fit: cover; border-radius: 1.5rem; width: 100%; height: 100%; }
        #reader__dashboard_section_csr span { color: white !important; }
        #reader__dashboard_section_swaplink, #reader__dashboard_section_csr input { display: none; }
        
        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(30px) scale(0.95); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .anim-muncul { opacity: 0; animation: slideUpFade 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-4 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')]">

    <div class="w-full max-w-md text-center relative z-10 bg-[#111111] p-6 rounded-[2rem] border border-white/10 shadow-2xl anim-muncul">
        
        <div class="flex justify-between items-center mb-6 anim-muncul delay-100">
            <div class="text-left">
                <p class="text-blue-400 text-xs font-bold tracking-widest uppercase mb-1">Silakan Scan Voucher</p>
                <h1 class="text-xl font-extrabold text-white">{{ $event->nama_kegiatan }}</h1>
            </div>
            <a href="/" class="w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-all text-slate-300 shadow-sm">✕</a>
        </div>

        <div class="relative w-full aspect-[4/3] rounded-3xl p-1 mb-6 anim-muncul delay-200">
            <div class="absolute inset-0 bg-gradient-to-r from-[#003eb3] via-blue-400 to-[#003eb3] rounded-3xl opacity-30 blur-md animate-pulse"></div>
            <div class="relative w-full h-full bg-black rounded-3xl overflow-hidden border border-white/10 z-10">
                <div class="absolute top-4 left-4 w-12 h-12 border-t-4 border-l-4 border-white/70 rounded-tl-xl z-20"></div>
                <div class="absolute top-4 right-4 w-12 h-12 border-t-4 border-r-4 border-white/70 rounded-tr-xl z-20"></div>
                <div class="absolute bottom-4 left-4 w-12 h-12 border-b-4 border-l-4 border-white/70 rounded-bl-xl z-20"></div>
                <div class="absolute bottom-4 right-4 w-12 h-12 border-b-4 border-r-4 border-white/70 rounded-br-xl z-20"></div>
                <div class="absolute top-1/2 left-0 w-full h-[2px] bg-[#0055ff] shadow-[0_0_15px_#0055ff] z-30 opacity-80 animate-[ping_2.5s_ease-in-out_infinite]"></div>
                
                <div id="reader" class="w-full h-full absolute inset-0"></div>
            </div>
        </div>

        <div id="scan-result" class="hidden backdrop-blur-md border p-4 rounded-2xl text-sm font-bold transition-all duration-300 mb-4"></div>

        <div class="w-full anim-muncul delay-300">
            <input type="file" id="qr-upload" accept="image/*" class="hidden">
            <button type="button" id="btn-upload" class="w-full flex items-center justify-center gap-3 px-6 py-4 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/10 text-slate-200 text-sm font-semibold transition-all cursor-pointer shadow-sm hover:shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/><path d="M7.646 1.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 2.707V11.5a.5.5 0 0 1-1 0V2.707L5.354 4.854a.5.5 0 1 1-.708-.708l3-3z"/></svg>
                Upload QR dari Galeri
            </button>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const resultBox = document.getElementById('scan-result');
        const fileInput = document.getElementById('qr-upload');
        const btnUpload = document.getElementById('btn-upload');
        
        let html5Qrcode; let isCameraRunning = false; let isProcessing = false;

        function showNotification(message, isSuccess, isProcessingState = false) {
            resultBox.classList.remove('hidden');
            resultBox.className = "backdrop-blur-md border p-4 rounded-2xl text-sm font-bold transition-all duration-300 mb-4";
            if (isProcessingState) {
                resultBox.classList.add('bg-blue-500/10', 'border-blue-500/30', 'text-blue-400');
                resultBox.innerHTML = `⏳ ${message}`;
            } else if (isSuccess) {
                resultBox.classList.add('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-400');
                resultBox.innerHTML = `✨ ${message} <div class="text-xs font-normal text-emerald-500/70 mt-1">Membuka studio foto...</div>`;
            } else {
                resultBox.classList.add('bg-red-500/10', 'border-red-500/30', 'text-red-400');
                resultBox.innerHTML = `⚠️ ${message}`;
                setTimeout(() => { resultBox.classList.add('hidden'); }, 3000);
            }
        }

        // OTOMATIS CEK: Jika link URL bawa parameter ?token=, langsung bypass validasi tanpa buka kamera!
        const urlParams = new URLSearchParams(window.location.search);
        const urlToken = urlParams.get('token');
        if (urlToken) {
            showNotification("Token terdeteksi, memvalidasi...", false, true);
            prosesValidasiToken(urlToken);
        } else {
            startCamera();
        }

        function startCamera() {
            if (!html5Qrcode) { html5Qrcode = new Html5Qrcode("reader"); }
            html5Qrcode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } }, (decodedText) => {
                let extractedToken = decodedText;
                try { extractedToken = new URL(decodedText).searchParams.get("token") || decodedText; } catch(e) { }
                prosesValidasiToken(extractedToken);
            })
            .then(() => { isCameraRunning = true; }).catch(err => { console.log("Izin kamera belum diberikan."); });
        }

        btnUpload.addEventListener('click', () => { if (!isProcessing) fileInput.click(); });

        fileInput.addEventListener('change', async (e) => {
            if (e.target.files.length === 0) return;
            const file = e.target.files[0];
            isProcessing = true; showNotification("Menganalisis kode QR...", false, true);

            try {
                if (isCameraRunning) { await html5Qrcode.stop(); isCameraRunning = false; }
                const decodedText = await html5Qrcode.scanFile(file, true);
                let extractedToken = decodedText;
                try { extractedToken = new URL(decodedText).searchParams.get("token") || decodedText; } catch(e) { }
                prosesValidasiToken(extractedToken);
            } catch (err) {
                showNotification("Gagal! Gambar QR tidak terbaca.", false);
                fileInput.value = ""; isProcessing = false;
                setTimeout(() => { startCamera(); }, 2000);
            }
        });

        function prosesValidasiToken(token) {
            isProcessing = true;
            fetch(`/event/{{ $event->id }}/scan-validasi`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ token: token })
            })
            .then(res => res.json())
            .then(async data => {
                if (data.status === 'success') {
                    if (isCameraRunning && html5Qrcode) { try { await html5Qrcode.stop(); } catch(e) {} }
                    showNotification("Voucher Valid! 🎉", true);
                    setTimeout(() => window.location.href = data.redirect, 800);
                } else {
                    showNotification(data.message, false); fileInput.value = ""; isProcessing = false;
                    if (!urlToken && !isCameraRunning) { setTimeout(() => { startCamera(); }, 2000); }
                }
            }).catch(() => {
                showNotification("Koneksi terputus, silakan coba lagi.", false); isProcessing = false;
                if (!urlToken && !isCameraRunning) { setTimeout(() => { startCamera(); }, 2000); }
            });
        }
    </script>
</body>
</html>