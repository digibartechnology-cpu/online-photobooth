<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sesi Studio | DigiBAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Mencegah scroll dan mengunci layar */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            overscroll-behavior: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #050505;
            color: white;
            touch-action: none;
        }

        .glass-panel { background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.05); }
        
        /* PERBAIKAN 1: Efek Mirror (scaleX) Dihapus agar kamera asli apa adanya */
        
        #flash-overlay { opacity: 0; transition: opacity 0.15s ease-out; pointer-events: none; }
        .flash-active { opacity: 1 !important; }
        @keyframes pop { 0% { transform: scale(0.5); opacity: 0; } 50% { transform: scale(1.2); opacity: 1; } 100% { transform: scale(1); opacity: 0; } }
        .animate-pop { animation: pop 1s ease-in-out; }
        
        /* Scrollbar tersembunyi buat jaga-jaga kalau layar kependekan */
        .hide-scroll::-webkit-scrollbar { width: 0px; }
    </style>
</head>
<body class="flex flex-col bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz48L3N2Zz4=')]">

    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[80vh] bg-[#003eb3] rounded-[100%] opacity-10 blur-[150px] pointer-events-none z-0"></div>

    <!-- MAIN CONTAINER -->
    <div class="flex-1 w-full max-w-6xl mx-auto z-10 flex flex-col landscape:flex-row md:flex-row p-4 md:p-6 gap-4 md:gap-8 h-full relative">
        
        <!-- HEADER (Muncul di atas khusus saat HP berdiri/Portrait) -->
        <div class="flex justify-between items-center w-full landscape:hidden md:hidden shrink-0">
            <div>
                <span class="inline-block bg-white/10 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-white/10">Siapkan Senyum Terbaik</span>
                <h1 class="text-xl font-extrabold tracking-tight mt-1">Ready, <span class="text-blue-400">{{ session('guest_name') }}</span>! ✨</h1>
            </div>
        </div>

        <!-- CAMERA AREA -->
        <div class="flex-1 w-full h-full relative flex flex-col items-center justify-center min-w-0 min-h-0">
            
            <div class="relative w-full max-w-3xl max-h-full aspect-[4/3] glass-panel rounded-[2rem] p-2 shadow-2xl flex items-center justify-center shrink-0 bg-black">
                
                <div class="relative w-full h-full rounded-[1.5rem] overflow-hidden flex items-center justify-center">
                    <!-- PERBAIKAN 2: object-cover diganti jadi object-contain agar lensa tidak ter-zoom/terpotong -->
                    <video id="webcam" autoplay playsinline muted class="w-full h-full object-contain"></video>
                    <div id="flash-overlay" class="absolute inset-0 bg-white z-40"></div>
                    <div id="countdown-display" class="absolute inset-0 z-30 flex items-center justify-center text-[8rem] md:text-[12rem] font-black text-white drop-shadow-[0_10px_30px_rgba(0,0,0,0.8)] hidden">3</div>
                    <div class="absolute inset-0 z-20 pointer-events-none border-[2px] border-white/10 rounded-[1.5rem] m-2 md:m-4"></div>
                </div>

                <!-- Info Jepretan di pojok bawah kamera -->
                <div class="absolute bottom-4 md:bottom-6 left-4 md:left-6 right-4 md:right-6 flex justify-between px-4 py-2 bg-black/50 backdrop-blur-md rounded-2xl border border-white/10 text-[10px] sm:text-xs font-semibold text-slate-300 z-50">
                    <span>Udah foto: <strong id="photo-count" class="text-white">0</strong></span>
                    <span class="text-slate-400">(Sepuasnya!)</span>
                </div>

            </div>
        </div>

        <!-- CONTROL PANEL -->
        <div class="w-full landscape:w-64 md:w-72 lg:w-80 shrink-0 flex flex-col gap-3 justify-center bg-black/20 landscape:bg-transparent md:bg-transparent rounded-3xl p-3 md:p-0 overflow-y-auto hide-scroll max-h-full">
            
            <!-- HEADER VERSI DESKTOP & LANDSCAPE -->
            <div class="hidden landscape:block md:block mb-4 md:mb-6 text-center md:text-left">
                <span class="inline-block bg-white/10 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest border border-white/10 mb-2">Siapkan Senyum Terbaik</span>
                <h1 class="text-xl md:text-3xl font-extrabold tracking-tight">Ready, <br><span class="text-blue-400">{{ session('guest_name') }}</span>! ✨</h1>
            </div>

            <!-- Kontrol Fitur -->
            <div class="grid grid-cols-2 landscape:grid-cols-1 md:grid-cols-1 gap-2 w-full">
                <button id="flash-toggle-btn" onclick="toggleFlashSetting()" class="w-full bg-slate-900/80 border border-white/20 text-blue-400 text-[11px] md:text-xs font-bold rounded-xl py-3 outline-none hover:bg-slate-800 backdrop-blur-md flex items-center justify-center gap-1 transition-colors">
                    ⚡ <span id="flash-status-text">Flash: NYALA</span>
                </button>
                <div id="camera-selector-container" class="hidden">
                    <select id="camera-select" onchange="switchCamera()" class="w-full bg-slate-900/80 border border-white/20 text-white text-[11px] md:text-xs font-bold rounded-xl px-2 py-3 outline-none focus:ring-2 focus:ring-[#003eb3] backdrop-blur-md text-center appearance-none truncate"></select>
                </div>
            </div>

            <!-- Tombol Aksi Utama -->
            <div class="flex flex-col gap-2 mt-auto landscape:mt-4 md:mt-6">
                <button id="snap-btn" onclick="triggerSnap()" class="group relative w-full bg-white text-slate-900 font-extrabold py-4 md:py-6 rounded-2xl text-sm md:text-base shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)] transition-all flex items-center justify-center gap-2">
                    <span>📸 Jepret!</span>
                </button>
                <button id="finish-btn" onclick="openSelectionModal()" class="hidden group relative w-full bg-gradient-to-r from-[#003eb3] to-blue-600 text-white font-extrabold py-4 md:py-6 rounded-2xl text-sm md:text-base shadow-[0_0_20px_rgba(0,62,179,0.5)] transition-all flex items-center justify-center gap-2">
                    <span>Pilih Foto 👉</span>
                </button>
            </div>

        </div>

    </div>

    <!-- Modal Seleksi Foto -->
    <div id="selectionModal" class="hidden fixed inset-0 z-[9999] bg-black/95 flex flex-col p-4 md:p-8 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 w-full max-w-5xl mx-auto h-full rounded-3xl flex flex-col shadow-2xl relative overflow-hidden">
            <div class="flex justify-between items-center p-4 md:p-6 border-b border-white/10 shrink-0">
                <div>
                    <h2 class="text-lg md:text-2xl font-black text-white">Pilih Foto Paling Ciamik! ✨</h2>
                    <p class="text-[10px] md:text-sm text-slate-400 mt-1">Pilih jepretan mana aja yang mau masuk frame kamu.</p>
                </div>
                <button type="button" onclick="closeSelectionModal()" class="bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white rounded-full w-10 h-10 md:w-12 md:h-12 flex items-center justify-center font-bold text-lg transition-all flex-shrink-0">✕</button>
            </div>
            
            <div id="selection-grid" class="p-4 md:p-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 md:gap-4 overflow-y-auto flex-1 content-start"></div>
            
            <div class="p-4 md:p-6 border-t border-white/10 bg-slate-800/80 flex flex-row justify-between items-center gap-4 shrink-0">
                <span id="selected-counter" class="font-bold text-slate-300 text-xs md:text-base">Yang Dipilih: 0 foto</span>
                <button type="button" onclick="confirmSelection()" class="bg-[#003eb3] hover:bg-[#002e8a] text-white font-extrabold py-3 md:py-4 px-6 md:px-8 rounded-xl shadow-lg transition-all text-xs md:text-base">
                    Pakai Foto Ini 🔥
                </button>
            </div>
        </div>
    </div>

    <canvas id="canvas" class="hidden"></canvas>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('touchmove', function(e) {
            if (!e.target.closest('#selection-grid')) { e.preventDefault(); }
        }, { passive: false });

        const video = document.getElementById('webcam'); const canvas = document.getElementById('canvas'); const ctx = canvas.getContext('2d');
        const flash = document.getElementById('flash-overlay'); const countdownDisplay = document.getElementById('countdown-display');
        const snapBtn = document.getElementById('snap-btn'); const finishBtn = document.getElementById('finish-btn'); const photoCountText = document.getElementById('photo-count');
        const cameraSelect = document.getElementById('camera-select'); const cameraSelectorContainer = document.getElementById('camera-selector-container');
        const flashToggleBtn = document.getElementById('flash-toggle-btn'); const flashStatusText = document.getElementById('flash-status-text');

        let allPhotos = []; let selectedPhotos = []; let currentStream = null; let selectedDeviceId = null; let isSnapping = false; let isFlashEnabled = true;

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.get('reselect') === 'true') {
                const savedAll = localStorage.getItem('digibar_all_photos');
                if(savedAll) { allPhotos = JSON.parse(savedAll); photoCountText.innerText = allPhotos.length; finishBtn.classList.remove('hidden'); openSelectionModal(); }
            }
        };

        function toggleFlashSetting() {
            isFlashEnabled = !isFlashEnabled;
            if (isFlashEnabled) {
                flashStatusText.innerText = "Flash: NYALA"; flashToggleBtn.classList.replace('text-slate-400', 'text-blue-400');
            } else {
                flashStatusText.innerText = "Flash: MATI"; flashToggleBtn.classList.replace('text-blue-400', 'text-slate-400');
            }
        }

        // PERBAIKAN 3: Mancing izin kamera duluan agar list nama kamera di Safari terbuka semua
        async function getCameras() {
            try {
                // Pancing stream awal untuk mendapatkan izin (Permission)
                if (!currentStream) {
                    currentStream = await navigator.mediaDevices.getUserMedia({ video: true });
                    video.srcObject = currentStream;
                    video.play();
                }

                const devices = await navigator.mediaDevices.enumerateDevices(); 
                const videoDevices = devices.filter(device => device.kind === 'videoinput');
                
                if (videoDevices.length > 0) {
                    cameraSelect.innerHTML = '';
                    videoDevices.forEach((device, index) => { 
                        const option = document.createElement('option'); 
                        option.value = device.deviceId; 
                        option.text = device.label || `Kamera ${index + 1}`; 
                        cameraSelect.appendChild(option); 
                    });
                    
                    if (videoDevices.length > 1) {
                        cameraSelectorContainer.classList.remove('hidden'); 
                    }
                    
                    selectedDeviceId = videoDevices[0].deviceId; 
                    startCamera(selectedDeviceId);
                } else { 
                    startCamera(null); 
                }
            } catch (err) { 
                alert("Gagal mengakses kamera. Pastikan izin browser aktif."); 
            }
        }

        async function startCamera(deviceId) {
            if (currentStream) currentStream.getTracks().forEach(track => track.stop());
            const constraints = { video: { deviceId: deviceId ? { exact: deviceId } : undefined, width: { ideal: 1920 }, height: { ideal: 1080 } } };
            try { 
                currentStream = await navigator.mediaDevices.getUserMedia(constraints); 
                video.srcObject = currentStream; 
                video.play(); 
            } 
            catch (err) { console.log(err); }
        }

        function switchCamera() { selectedDeviceId = cameraSelect.value; startCamera(selectedDeviceId); }
        
        getCameras();

        async function triggerSnap() {
            if (isSnapping) return;
            isSnapping = true; snapBtn.disabled = true; snapBtn.classList.add('opacity-50');

            await runCountdown(3);
            if (isFlashEnabled) flash.classList.add('flash-active');
            
            setTimeout(() => {
                const ratio = video.videoWidth / video.videoHeight;
                // Tinggi maksimal kanvas agar hasil foto tidak pecah, tapi tidak terlalu berat
                canvas.height = 1080; 
                canvas.width = canvas.height * ratio;
                
                // PERBAIKAN 4: Menghapus logika flip (mirror) di sisi Canvas. Foto akan murni sesuai jepretan lensa
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const dataUrl = canvas.toDataURL('image/jpeg', 0.8);
                allPhotos.push(dataUrl); photoCountText.innerText = allPhotos.length;

                fetch('/api/simpan-mentahan', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ gambar: dataUrl })
                }).catch(e => console.log(e));

                if (isFlashEnabled) flash.classList.remove('flash-active');
                if (allPhotos.length > 0) finishBtn.classList.remove('hidden');

                snapBtn.disabled = false; snapBtn.classList.remove('opacity-50'); isSnapping = false;
            }, 50); 
        }

        function runCountdown(seconds) {
            return new Promise(resolve => {
                countdownDisplay.innerText = seconds; countdownDisplay.classList.remove('hidden'); countdownDisplay.classList.add('animate-pop');
                let counter = seconds;
                const interval = setInterval(() => {
                    counter--;
                    if (counter > 0) {
                        countdownDisplay.innerText = counter; countdownDisplay.classList.remove('animate-pop'); void countdownDisplay.offsetWidth; countdownDisplay.classList.add('animate-pop');
                    } else {
                        clearInterval(interval); countdownDisplay.classList.add('hidden'); countdownDisplay.classList.remove('animate-pop'); resolve();
                    }
                }, 1000); 
            });
        }

        function openSelectionModal() {
            const grid = document.getElementById('selection-grid'); grid.innerHTML = '';
            allPhotos.forEach((photo) => {
                const isSelected = selectedPhotos.includes(photo); const div = document.createElement('div');
                div.className = `relative aspect-[4/3] rounded-xl overflow-hidden border-4 cursor-pointer transition-all ${isSelected ? 'border-blue-500 scale-95 shadow-[0_0_15px_rgba(59,130,246,0.5)]' : 'border-transparent opacity-80 hover:opacity-100'}`;
                div.onclick = () => toggleSelectPhoto(photo);

                const img = document.createElement('img'); img.src = photo; img.className = 'w-full h-full object-cover';
                const badge = document.createElement('div');
                badge.className = `absolute top-2 right-2 w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center text-[10px] md:text-sm font-bold shadow-md ${isSelected ? 'bg-blue-500 text-white' : 'bg-black/60 text-white/40'}`;
                badge.innerText = isSelected ? (selectedPhotos.indexOf(photo) + 1) : '✓';

                div.appendChild(img); div.appendChild(badge); grid.appendChild(div);
            });
            updateSelectedCounter(); document.getElementById('selectionModal').classList.remove('hidden');
        }

        function closeSelectionModal() { document.getElementById('selectionModal').classList.add('hidden'); }
        function toggleSelectPhoto(photo) { const index = selectedPhotos.indexOf(photo); if (index > -1) { selectedPhotos.splice(index, 1); } else { selectedPhotos.push(photo); } openSelectionModal(); }
        function updateSelectedCounter() { document.getElementById('selected-counter').innerText = `Yang Dipilih: ${selectedPhotos.length} foto`; }

        function confirmSelection() {
            if (selectedPhotos.length === 0) { alert("Pilih minimal 1 foto ya!"); return; }
            const btn = document.querySelector('button[onclick="confirmSelection()"]'); if(btn) { btn.innerHTML = "Memproses... ⏳"; btn.disabled = true; }
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    try {
                        localStorage.setItem('digibar_all_photos', JSON.stringify(allPhotos)); localStorage.setItem('digibar_raw_photos', JSON.stringify(selectedPhotos)); window.location.replace("/filter-bingkai");
                    } catch (e) {
                        alert("Gagal memproses memori. Coba kurangi jumlah foto yang dipilih."); if(btn) { btn.innerHTML = "Pakai Foto Ini 🔥"; btn.disabled = false; }
                    }
                });
            });
        }
    </script>
</body>
</html>