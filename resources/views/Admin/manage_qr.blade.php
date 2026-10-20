<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola QR | DigiBAR Group</title>
    <style>
        :root { --maroon: #7b1113; --navy: #000080; --bg-light: #f8f9fc; --text-dark: #2c3e50; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg-light); margin: 0; padding: 30px; color: var(--text-dark); }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-top: 6px solid var(--maroon); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #edf2f7; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: var(--navy); }
        .btn-back { background: #e2e8f0; color: #4a5568; padding: 10px 18px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 14px; transition: 0.2s; }
        .btn-back:hover { background: #cbd5e0; }
        
        .alert-success { background: #d1e7dd; color: #0f5132; padding: 15px 20px; border-radius: 8px; font-weight: bold; margin-bottom: 20px; border: 1px solid #badbcc; }
        
        .info-card { background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%); border-left: 5px solid var(--maroon); padding: 20px; border-radius: 8px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;}
        .info-text { display: flex; flex-direction: column; gap: 5px;}
        .info-card span { font-size: 14px; color: #718096; }
        .info-card strong { font-size: 20px; color: var(--maroon); }

        .form-section { background: #f7fafc; padding: 25px; border-radius: 10px; border: 1px dashed #cbd5e0; display: flex; justify-content: space-between; align-items: center; }
        .form-control { width: 120px; padding: 15px; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 16px; text-align: center; font-weight: bold; outline: none; transition: 0.3s; }
        .form-control:focus { border-color: var(--navy); }
        .btn-generate { background: var(--maroon); color: white; border: none; padding: 15px 30px; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn-generate:hover { background: #5a0c0e; transform: translateY(-2px); }

        .qr-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 20px; }
        .qr-item { background: white; padding: 20px; border-radius: 12px; border: 1px solid #edf2f7; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.03); display: flex; flex-direction: column; align-items: center; position: relative;}
        
        .qr-item img.qr-png-result { width: 100%; height: auto; max-width: 140px; margin-bottom: 10px; border-radius: 6px; }
        .token-text { font-size: 11px; color: #a0aec0; font-family: monospace; font-weight: bold; margin-bottom: 15px; letter-spacing: 1px; }
        
        .action-buttons { display: flex; flex-direction: column; gap: 8px; width: 100%; }
        .btn-action { padding: 8px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 12px; transition: 0.2s; }
        .btn-dl { background: #edf2f7; color: #2d3748; }
        .btn-dl:hover { background: #e2e8f0; }
        .btn-print { background: var(--navy); color: white; }
        .btn-print:hover { background: #000050; }
        
        .empty-state { text-align: center; padding: 40px; color: #a0aec0; border: 2px dashed #e2e8f0; border-radius: 12px; font-weight: 500; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Manajemen Tiket (QR Code)</h1>
            <a href="/admin-home" class="btn-back">← Kembali ke Utama</a>
        </div>

        @if(session('success'))
            <div class="alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="info-card">
            <div class="info-text">
                <span>Kegiatan Aktif:</span>
                <strong>{{ $event->nama_kegiatan }}</strong>
            </div>
            <div class="info-text" style="text-align: right;">
                <span>Durasi Validasi:</span>
                <strong>{{ \Carbon\Carbon::parse($event->waktu_buka)->format('d M Y') }} - {{ \Carbon\Carbon::parse($event->waktu_tutup)->format('d M Y') }}</strong>
            </div>
        </div>

        <div class="form-section">
            <div style="max-width: 60%;">
                <h3 style="margin: 0 0 5px 0; color: var(--navy);">Cetak Tiket QR Baru</h3>
                <p style="font-size: 13px; color: #718096; margin: 0;">Masukkan jumlah tiket untuk pengunjung. Tiket bisa dicetak ke thermal printer atau diunduh sebagai gambar.</p>
            </div>
            <form action="/admin-home/event/{{ $event->id }}/qr/generate" method="POST" style="display: flex; gap: 10px; align-items: center;">
                @csrf
                <input type="number" class="form-control" name="jumlah" value="1" min="1" placeholder="Qty" required>
                <button type="submit" class="btn-generate">+ Generate</button>
            </form>
        </div>

        <div style="margin-top: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; border-bottom: 2px solid #edf2f7; padding-bottom: 10px;">
                <h3 style="color: var(--navy); margin: 0;">Daftar Tiket Aktif (Belum Digunakan)</h3>
                <span style="background: #e2e8f0; color: #4a5568; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold;">
                    Total: {{ $vouchers->count() }}
                </span>
            </div>
            
            @if($vouchers->count() > 0)
                <div class="qr-grid">
                    @foreach($vouchers as $index =>$voucher)
                        <div class="qr-item">
                            <!-- SVG asli disembunyikan agar server PHP tidak crash -->
                            <div class="hidden-svg" style="display: none;">
                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->margin(4)->generate(url('/scan?token='.$voucher->token)) !!}
                            </div>
                            
                            <!-- Tempat munculnya PNG murni hasil konversi -->
                            <img id="qr-img-{{ $voucher->id }}" class="qr-png-result" src="" alt="Menyiapkan QR...">
                            
                            <div class="token-text">ID: {{ substr($voucher->token, 0, 8) }}</div>
                            
                            <div class="action-buttons">
                                <button class="btn-action btn-print" onclick="cetakThermal('qr-img-{{ $voucher->id }}', '{{ $event->nama_kegiatan }}', '{{ substr($voucher->token, 0, 8) }}')">🖨 Cetak Struk (Thermal)</button>
                                <button class="btn-action btn-dl" onclick="unduhQR('qr-img-{{ $voucher->id }}', '{{$event->nama_kegiatan }}')">⬇ Unduh Gambar (PNG)</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    Belum ada tiket yang dibuat untuk kegiatan ini. Silakan generate di atas.
                </div>
            @endif
        </div>
    </div>

    <script>
        // MESIN PENGUBAH SVG KE PNG SECARA OTOMATIS
        document.addEventListener("DOMContentLoaded", function() {
            const qrItems = document.querySelectorAll('.qr-item');
            
            qrItems.forEach(item => {
                const svgElement = item.querySelector('.hidden-svg svg');
                const imgElement = item.querySelector('.qr-png-result');

                if(svgElement && imgElement) {
                    // Pastikan format SVG valid
                    if(!svgElement.getAttribute('xmlns')) {
                        svgElement.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                    }
                    
                    const svgData = new XMLSerializer().serializeToString(svgElement);
                    const svgBlob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                    const DOMURL = window.URL || window.webkitURL || window;
                    const url = DOMURL.createObjectURL(svgBlob);

                    const img = new Image();
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        canvas.width = 300; 
                        canvas.height = 300;
                        const ctx = canvas.getContext('2d');
                        
                        // Beri background putih solid (wajib untuk PNG agar tidak transparan)
                        ctx.fillStyle = '#ffffff';
                        ctx.fillRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        
                        // Tampilkan hasil PNG ke layar
                        imgElement.src = canvas.toDataURL('image/png');
                        DOMURL.revokeObjectURL(url);
                    };
                    img.src = url;
                }
            });
        });

        // FUNGSI 1: CETAK KE THERMAL PRINTER
        function cetakThermal(elementId, eventName, tokenId) {
            const imgSrc = document.getElementById(elementId).src;
            const printWindow = window.open('', '_blank', 'width=400,height=600');
            
            printWindow.document.write(`
                <html>
                <head>
                    <title>Tiket Photobooth</title>
                    <style>
                        body { width: 58mm; margin: 0 auto; padding: 10px 0; text-align: center; font-family: monospace; color: #000; }
                        h3 { font-size: 16px; margin: 0 0 5px 0; text-transform: uppercase; }
                        p { font-size: 12px; margin: 0; }
                        .qr-container { margin: 15px 0; display: flex; justify-content: center; }
                        .qr-container img { width: 45mm; height: 45mm; } 
                        .footer { font-size: 10px; margin-top: 10px; border-top: 1px dashed #000; padding-top: 5px; }
                        @page { margin: 0; }
                    </style>
                </head>
                <body>
                    <h3>DigiBAR Photobooth</h3>
                    <p>${eventName}</p>
                    <div class="qr-container"><img src="${imgSrc}"></div>
                    <p style="font-weight:bold; margin-bottom: 3px;">SCAN UNTUK AKSES</p>
                    <p style="font-size: 10px; color: #333;">TKN: ${tokenId}</p>
                    <div class="footer">Tiket ini hanya berlaku 1 kali scan.</div>
                    
                    <script>
                        window.onload = function() { 
                            window.print(); 
                            setTimeout(function(){ window.close(); }, 500);
                        }
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // FUNGSI 2: UNDUH GAMBAR (Format PNG)
        function unduhQR(elementId, eventName) {
            const imgSrc = document.getElementById(elementId).src;
            const link = document.createElement("a");
            link.href = imgSrc;
            link.download = "Tiket_" + eventName.replace(/\s+/g, '_') + "_" + Date.now() + ".png";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>