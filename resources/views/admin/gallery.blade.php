<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galeri Mentahan - {{ $event->nama_kegiatan }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #0f172a; color: white; }
    </style>
</head>
<body class="p-6 md:p-10">

    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <a href="/admin-home" class="text-slate-400 hover:text-white text-sm flex items-center gap-2 mb-2 transition-colors">
                    <span>←</span> Kembali ke Dashboard
                </a>
                <h1 class="text-3xl font-black tracking-tight">Galeri Mentahan</h1>
                <p class="text-amber-500 font-bold mt-1">{{ $event->nama_kegiatan }}</p>
            </div>
            
            <div class="bg-slate-800 border border-slate-700 px-6 py-3 rounded-2xl text-center">
                <span class="block text-slate-400 text-xs uppercase tracking-wider font-bold mb-1">Total Jepretan</span>
                <span class="text-2xl font-black text-white">{{ $photos->count() }} Foto</span>
            </div>
        </div>

        <!-- Grid Foto -->
        @if($photos->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
                @foreach($photos as $photo)
                    <div class="group relative bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all hover:-translate-y-1">
                        
                        <!-- Gambar Foto -->
                        <div class="aspect-[4/3] bg-black overflow-hidden relative">
                            <!-- PERBAIKAN: Menambahkan path 'storage/' agar sesuai dengan lokasi simpan di Controller -->
                            <img src="{{ asset('storage/' . $photo->file_name) }}" onerror="this.src='{{ asset($photo->file_name) }}'" alt="Mentahan" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity">
                        </div>

                        <!-- Info & Tombol Download -->
                        <div class="p-4 flex justify-between items-center bg-slate-900 relative z-10">
                            <div class="text-[10px] text-slate-400 font-medium">
                                {{ $photo->created_at->format('H:i:s') }} WIB
                            </div>
                            
                            <!-- PERBAIKAN: Menambahkan path 'storage/' pada link download -->
                            <a href="{{ asset('storage/' . $photo->file_name) }}" download="DigiBAR_{{ $event->nama_kegiatan }}_{{ $photo->id }}.jpg" class="bg-blue-600 hover:bg-blue-500 text-white p-2 rounded-lg transition-colors cursor-pointer" title="Download Foto Ini">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <!-- Tampilan Jika Kosong -->
            <div class="w-full py-20 bg-slate-900/50 border border-dashed border-slate-700 rounded-3xl text-center flex flex-col items-center justify-center">
                <div class="text-6xl mb-4 opacity-50">📷</div>
                <h3 class="text-xl font-bold text-slate-300">Belum Ada Jepretan</h3>
                <p class="text-slate-500 text-sm mt-2">Tamu belum melakukan sesi foto di acara ini.</p>
            </div>
        @endif

    </div>

</body>
</html>