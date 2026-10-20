<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DigiBAR Photobooth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bg-animated {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #4a0404, #0f172a);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #reader { width: 100%; height: 100%; border: none !important; background: transparent; }
        #reader__scan_region { width: 100% !important; height: 100% !important; overflow: hidden; border-radius: 1rem; }
        #reader video { width: 100% !important; height: 100% !important; object-fit: cover !important; border-radius: 1rem; }
        #reader__custom_canvas { display: none !important; }
    </style>
</head>
<body class="bg-animated min-h-screen flex flex-col items-center justify-center p-6 text-white relative overflow-x-hidden">
    
    <!-- Ornamen Cahaya Belakang -->
    <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-[#7b1113] rounded-full mix-blend-screen filter blur-[100px] opacity-40 animate-pulse pointer-events-none"></div>

    <div class="glass-card w-full max-w-sm rounded-[2rem] shadow-2xl p-8 text-center relative z-10 transition-all duration-500">
        
        <div class="w-16 h-16 bg-gradient-to-tr from-[#7b1113] to-red-500 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-[0_0_20px_rgba(123,17,19,0.5)] rotate-3">
            <span class="text-2xl drop-shadow-md">📸</span>
        </div>
        
        <h1 class="text-2xl font-extrabold tracking-tight mb-1">DIGIBAR</h1>
        <p class="text-[10px] font-bold text-red-400 mb-8 uppercase tracking-[0.3em]">Self Photo Studio</p>

        <h2 class="text-sm font-semibold text-slate-300 mb-4 text-left px-2">Mau foto di event mana nih? 👇</h2>
        
        <div class="space-y-3 mb-8">
            <!-- MENGGUNAKAN PHP MURNI AGAR KEBAL DARI ERROR COMPILER BLADE -->
            <?php if(count($events) > 0): ?>
                <?php foreach($events as$event): ?>
                    <button onclick="openScanner({{ $event->id }}, '{{$event->nama_kegiatan }}')" class="group relative w-full bg-white/5 hover:bg-white/10 border border-white/10 hover:border-[#7b1113]/50 text-white font-bold py-4 px-5 rounded-2xl transition-all duration-300 text-left flex justify-between items-center cursor-pointer">
                        <span class="relative z-10">{{ $event->nama_kegiatan }}</span>
                        <span class="relative z-10 bg-[#7b1113] w-8 h-8 rounded-full flex items-center justify-center text-sm group-hover:scale-110 transition-transform">→</span>
                    </button>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="p-4 glass-card rounded-2xl text-slate-400 text-sm">
                    Wah, lagi nggak ada event yang aktif nih.
                </div>
            <?php endif; ?>
        </div>

        <a href="https://wa.me/628559000857" target="_blank" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-slate-300 hover:text-white bg-green-600/20 border border-green-500/30 px-5 py-3 rounded-full transition-all hover:bg-green-600/40">
            <span>💬</span> Belum punya tiket? Chat Admin!
        </a>
    </div>

    <!-- MODAL POP-UP SCANNER KAMERA -->
    <div id="scannerModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-[#0f172a] border border-white/10 rounded-[2rem] w-full max-w-sm p-6 relative transform scale-95 transition-transform duration-300" id="modalContent">
            
            <button onclick="closeScanner()" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center bg-white/10 rounded-full text-white/70 hover:text-white hover:bg-[#7b1113] transition-colors z-20 cursor-pointer">
                ✕
            </button>

            <div class="text-center mb-4 mt-2">
                <span class="text-[10px] font-bold text-red-400 uppercase tracking-widest">Arahkan Tiket QR</span>
                <h3 id="modalEventName" class="text-lg font-bold text-white mt-1 leading-tight">Nama Event</h3>
            </div>

            <!-- Viewfinder Area -->
            <div class="relative w-full aspect-square rounded-2xl p-1 mb-2">
                <div class="absolute inset-0 bg-gradient-to-r from-[#7b1113] to-red-500 rounded-2xl opacity-40 blur-md animate-pulse"></div>
                
                <div class="relative w-full h-full bg-black rounded-2xl overflow-hidden border border-white/10 z-10 flex items-center justify-center">
                    <div class="absolute top-1/2 left-0 w-full h-[2px] bg-[#ff2a2d] shadow-[0_0_15px_#ff2a2d] z-30 opacity-70 animate-[ping_2s_ease-in-out_infinite]"></div>
                    <div id="reader" class="w-full h-full absolute inset-0 z-20"></div>
                </div>
            </div>

            <!-- Opsi Upload File Galeri -->
            <div class="mt-4 mb-2">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <div class="h-px bg-white/10 flex-1"></div>
                    <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">ATAU</span>
                    <div class="h-px bg-white/10 flex-1"></div>
                </div>
                
                <input type="file" id="qr-upload" class="hidden" accept="image/*">
                <button onclick="document.getElementById('qr-upload').click()" class="w-full bg-white/5 hover:bg-white/10 border border-white/10 text-slate-300 hover:text-white text-sm font-semibold py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 cursor-pointer">
                    <span class="text-lg">🖼️</span> Upload QR dari Galeri
                </button>
            </div>

            <div id="scan-result" class="hidden text-center p-3 mt-2 rounded-xl text-sm font-bold transition-all"></div>
        </div>
    </div>

    <!-- AREA KERJA BAYANGAN UNTUK MEMBACA FILE GALERI -->
    <div id="file-reader" style="position: absolute; top: -9999px; left: -9999px; width: 500px; height: 500px; opacity: 0; pointer-events: none; z-index: -1;"></div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const modal = document.getElementById('scannerModal');
        const modalContent = document.getElementById('modalContent');
        const resultBox = document.getElementById('scan-result');
        const fileInput = document.getElementById('qr-upload');
        
        let html5QrCode;
        let fileScanner = new Html5Qrcode("file-reader");
        let currentEventId = null;
        let isScanning = false;

        function openScanner(eventId, eventName) {
            currentEventId = eventId;
            document.getElementById('modalEventName').innerText = eventName;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
            }, 10);

            isScanning = true;
            resultBox.classList.add('hidden');
            fileInput.value = ''; 

            startCamera();
        }

        function startCamera() {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            html5QrCode.start(
                { facingMode: "environment" }, 
                { fps: 15, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 }, 
                onScanSuccess
            ).catch(err => {
                showNotification("Kamera gagal diakses. Anda tetap bisa menggunakan upload galeri di bawah.", false);
            });
        }

        function closeScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(() => html5QrCode.clear()).catch(err => console.log(err));
            } else if (html5QrCode) {
                html5QrCode.clear();
            }
            modal.classList.add('opacity-0');
            modalContent.classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
            isScanning = false;
        }

        function showNotification(message, isSuccess, isProcessing = false) {
            resultBox.classList.remove('hidden', 'bg-red-500/20', 'text-red-400', 'bg-emerald-500/20', 'text-emerald-400', 'bg-blue-500/20', 'text-blue-400');
            if (isProcessing) {
                resultBox.classList.add('bg-blue-500/20', 'text-blue-400');
                resultBox.innerHTML = `⏳ ${message}`;
            } else if (isSuccess) {
                resultBox.classList.add('bg-emerald-500/20', 'text-emerald-400');
                resultBox.innerHTML = `✨ ${message}`;
            } else {
                resultBox.classList.add('bg-red-500/20', 'text-red-400');
                resultBox.innerHTML = `⚠️ ${message}`;
                setTimeout(() => { isScanning = true; resultBox.classList.add('hidden'); }, 3000);
            }
        }

        function processToken(decodedText) {
            if (!isScanning) return; 
            isScanning = false; 

            let extractedToken = decodedText;
            try { extractedToken = new URL(decodedText).searchParams.get("token") || decodedText; } catch(e) {}

            fetch(`/event/${currentEventId}/scan-validasi`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ token: extractedToken })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showNotification("Voucher Valid! Let's go...", true);
                    if (html5QrCode && html5QrCode.isScanning) html5QrCode.stop().catch(e=>{}); 
                    setTimeout(() => window.location.href = data.redirect, 800);
                } else {
                    showNotification(data.message, false); 
                }
            }).catch(() => {
                showNotification("Koneksi gangguan nih, coba lagi ya.", false);
            });
        }

        function onScanSuccess(decodedText) {
            processToken(decodedText);
        }

        // LOGIKA UPLOAD GALERI YANG TERISOLASI
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length === 0) return;
            const imageFile = e.target.files[0];
            
            showNotification("Menganalisis tiket...", false, true);
            isScanning = false; 

            // Baca file tanpa menyentuh kamera utama
            fileScanner.scanFile(imageFile, false)
            .then(decodedText => {
                isScanning = true; 
                processToken(decodedText);
            })
            .catch(err => {
                showNotification("Nggak nemu QR Code di gambar ini. Pastikan gambarnya jelas ya!", false);
                setTimeout(() => {
                    isScanning = true;
                }, 2500);
            });
            
            e.target.value = '';
        });
    </script>
</body>
</html>