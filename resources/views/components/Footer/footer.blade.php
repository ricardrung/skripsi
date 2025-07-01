<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  @vite('resources/css/app.css')
  <title>Footer</title>
  <link rel="stylesheet" href="/fontawesome-/css/all.css">
</head>
<body class="flex flex-col min-h-screen">

  
  <!-- Konten Utama -->
  <main class="flex-grow">
      <!-- Konten lainnya -->
  </main>

  <!-- Footer -->
  <footer class="bg-[#2c1f11] text-white py-10 mt-auto">
      <div class="container mx-auto px-6 lg:px-10 grid grid-cols-1 md:grid-cols-3 gap-8">
          
          <!-- Logo & Deskripsi -->
          <div>
              <img src="/images/RRS_Background.png" alt="Logo" class="w-24 h-24">
              <p class="mt-3 text-gray-300">
                  Roemah Rempah Spa Manado <br>
                  Nikmati pengalaman spa terbaik dengan bahan alami.
              </p>
          </div>

          <!-- Navigasi Footer -->
          <div class="grid grid-cols-2 gap-4 md:gap-6">
              <div>
                  <h3 class="text-lg font-semibold">Layanan</h3>
                  <ul class="mt-2 space-y-2">
                      <li><a href="#" class="text-gray-300 hover:text-white">Massage</a></li>
                      <li><a href="#" class="text-gray-300 hover:text-white">Facial</a></li>
                      <li><a href="#" class="text-gray-300 hover:text-white">Body Scrub</a></li>
                      <li><a href="#" class="text-gray-300 hover:text-white">Hair Treatment</a></li>
                  </ul>
              </div>
              <div>
                  <h3 class="text-lg font-semibold">Tentang Kami</h3>
                  <ul class="mt-2 space-y-2">
                      <li><a href="#" class="text-gray-300 hover:text-white">Profil</a></li>
                      <li><a href="#" class="text-gray-300 hover:text-white">Kontak</a></li>
                      <li><a href="#" class="text-gray-300 hover:text-white">Karir</a></li>
                  </ul>
              </div>
          </div>

          <!-- Sosial Media & Hak Cipta -->
          <div class="text-center md:text-right">
              <h3 class="text-lg font-semibold">Ikuti Kami</h3>
              <div class="flex justify-center md:justify-end space-x-4 mt-3">
                  <a href="#" class="text-gray-300 hover:text-white text-xl">📘</a>
                  <a href="#" class="text-gray-300 hover:text-white text-xl">📷</a>
                  <a href="#" class="text-gray-300 hover:text-white text-xl">🐦</a>
              </div>
              <p class="text-gray-400 mt-4">© 2025 Roemah Rempah Spa. All Rights Reserved.</p>
          </div>

      </div>
  </footer>
</body>

</html>