<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Validasi Scanner | DigiBAR</title>
    <style>
        :root { --maroon: #7b1113; --navy: #000080; --bg-light: #f8f9fc; }
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg-light); margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .scanner-card { background: white; width: 100%; max-width: 500px; padding: 30px; border-radius: 16px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); text-align: center; position: relative; overflow: hidden; }
        .scanner-card::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 6px; background: var(--maroon); }
        
        .event-badge { display: inline-block; background: #e2e8f0; color: var(--navy); padding: 8px 16px; border-radius: 20px; font-weight: bold; font-size: 14px; margin-bottom: 20px; }
        
        #reader { width: 100%; border-radius: 12px; overflow: hidden; border: 4px solid #edf2f7; box-shadow: inset 0 0 20px rgba(0,0,0,0.05); margin-bottom: 20px; }
        
        .alert-box { padding: 18px; border-radius: 10px; font-weight: bold; font-size: 15px; display: none; transition: 0.3s; animation: popIn 0.3s; }
        @keyframes popIn { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        
        .btn-exit { display: block; width: 100%; padding: 15px; background: white; border: 2px solid #e2e8f0; color: #4a5568; font-weight: bold; border-radius: 10px; text-decoration: none; transition: 0.2s; box-sizing: border-box; }
        .btn-exit:hover { background: #edf2f7; color: var(--maroon); border-color: #cbd5e0; }
    </style>
</head>
<body>
    <div class="scanner-card">
        <h2 style="margin: 0 0 10px 0; color: var(--navy);">Gate Validasi Akses</h2>
        <div class="event-badge">🎟️ {{ $event->nama_kegiatan }}</div>
        
        <div id="reader"></div>
        <div id="alertBox" class="alert-box"></div>
        
        <a href="/admin-home" class="btn-exit" style="margin-top: 20px;">Tutup Scanner & Kembali</a>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const alertBox = document.getElementById('alertBox');
        let isScanning = true;

        // UPGRADE: Konfigurasi kamera dinamis seperti scanner tamu
        const cameraConfig = { 
            fps: 15, 
            qrbox: function(viewfinderWidth, viewfinderHeight) {
                let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                let size = Math.floor(minEdgeSize * 0.8);
                return { width: size, height: size };
            },
            formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
        };

        html5QrCode.start(
            { facingMode: "environment" }, 
            cameraConfig,
            (decodedText) => {
                if(!isScanning) return;
                isScanning = false; 

                // UPGRADE: Logika ekstraksi token bisa membaca URL panjang maupun URL pendek (/v/TOKEN)
                let tokenStr = decodedText;
                try { 
                    let urlObj = new URL(decodedText);
                    tokenStr = urlObj.searchParams.get("token") || urlObj.pathname.split('/').pop() || decodedText; 
                } catch(e) { }

                let tokenCSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/admin-home/event/{{ $event->id }}/scan', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': tokenCSRF },
                    body: JSON.stringify({ token: tokenStr })
                })
                .then(res => res.json())
                .then(data => {
                    alertBox.style.display = 'block';
                    if(data.status === 'success') {
                        alertBox.style.background = '#d1e7dd';
                        alertBox.style.color = '#0f5132';
                        alertBox.style.border = '2px solid #badbcc';
                        alertBox.innerHTML = "✅ Valid! Membuka Photobooth...";
                        // Matikan kamera sebelum pindah halaman
                        if (html5QrCode.getState() === 2) { html5QrCode.stop(); }
                        setTimeout(() => { window.location.href = '/studio'; }, 1000);
                    } else {
                        alertBox.style.background = '#f8d7da';
                        alertBox.style.color = '#842029';
                        alertBox.style.border = '2px solid #f5c2c7';
                        alertBox.innerHTML = "❌ " + data.message;
                        setTimeout(() => { isScanning = true; alertBox.style.display = 'none'; }, 2500);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alertBox.style.display = 'block';
                    alertBox.style.background = '#f8d7da';
                    alertBox.style.color = '#842029';
                    alertBox.style.border = '2px solid #f5c2c7';
                    alertBox.innerHTML = "❌ Terjadi kesalahan jaringan.";
                    setTimeout(() => { isScanning = true; alertBox.style.display = 'none'; }, 2500);
                });
            },
            (error) => { }
        ).catch(err => {
            console.log("Mencoba kamera alternatif...");
            html5QrCode.start({ facingMode: "user" }, cameraConfig, /* Callback sama seperti di atas... */);
        });
    </script>
</body>
</html>