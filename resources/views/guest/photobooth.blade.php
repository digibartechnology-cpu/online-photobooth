<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photobooth</title>
    <style>
        body { 
            margin: 0; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            height: 100vh; 
            background-color: #1a1a1a; /* Background gelap agar fokus ke tombol */
        }
        .camera-btn { 
            display: block; 
            padding: 40px 60px; 
            background: #ffffff; 
            color: #000000; 
            font-size: 28px; 
            font-weight: bold; 
            border-radius: 20px; 
            cursor: pointer; 
            text-align: center;
        }
    </style>
</head>
<body>

    <form id="photoForm" action="{{ route('photo.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="cameraInput" class="camera-btn">
            📸 AMBIL FOTO
        </label>
        
        <!-- Input disembunyikan, langsung submit saat foto dipilih/dijepret -->
        <input type="file" id="cameraInput" name="guest_photo" accept="image/*" capture="environment" style="display:none;" onchange="document.getElementById('photoForm').submit();">
    </form>

</body>
</html>