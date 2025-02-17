<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Layout</title>
  <link rel="stylesheet" href="/fontawesome-/css/all.css">
  @vite('resources/css/app.css')
</head>
<body class="flex flex-col min-h-screen ">
  <header class="bg-[#2c1a0f] text-white shadow-md fixed top-0 left-0 w-full z-50">
    <div class="container mx-auto flex justify-between items-center px-4 md:px-6 lg:px-8 py-4">
        <!-- Logo -->
        <a href="#" class="flex items-center space-x-2">
            <img src="/images/RRS_Background.png" alt="Logo" class="h-10 ">
            <span class="font-bold text-xl">Roemah Rempah Spa</span>
        </a>

        <!-- Desktop Menu (Hanya muncul di layar 1024px ke atas) -->
        <nav class="hidden lg:flex space-x-6">
            <div class="relative group">
                <button id="categoryBtn" class="focus:outline-none flex items-center">
                    Category <i class="fa-solid fa-caret-down ml-2"></i>
                </button>
                <div id="categoryDropdown" class="absolute left-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 group-hover:opacity-100 group-hover:visible">
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Massage</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Facial</a>
                    <a href="#" class="block px-4 py-2 hover:bg-gray-200">Body Scrub</a>
                </div>
            </div>
            <a href="#layanan" class="hover:text-gray-300">Layanan</a>
            <a href="#tentang" class="hover:text-gray-300">Tentang Kami</a>
        </nav>

        <!-- Search & Auth Buttons (Hanya muncul di layar 1024px ke atas) -->
        <div class="hidden lg:flex items-center space-x-4">
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                <input type="text" placeholder="Search..." class="pl-10 px-4 py-1 rounded bg-white text-black w-40">
            </div>
            <a href="#" class="bg-[#8b5a2b] px-4 py-2 rounded hover:bg-[#6b4223]">Login</a>
            <a href="#" class="border border-white px-4 py-2 rounded hover:bg-white hover:text-black">Sign Up</a>
        </div>

        <!-- Mobile Menu Button (Muncul di layar < 1024px) -->
        <button id="menuBtn" class="lg:hidden text-white text-2xl focus:outline-none">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Menu (Muncul di layar < 1024px) -->
    <div id="mobileMenu" class="hidden lg:hidden bg-[#2c1a0f] text-white flex-col transition-all duration-300 opacity-0">
        <a href="#layanan" class="block px-4 py-2 border-b border-gray-600">Layanan</a>
        <a href="#tentang" class="block px-4 py-2 border-b border-gray-600">Tentang Kami</a>

        <!-- Mobile Dropdown -->
        <div class="border-b border-gray-600">
            <button id="categoryMobileBtn" class="w-full text-left px-4 py-2 flex justify-between">
                Category <i class="fa-solid fa-caret-down transition-transform duration-300"></i>
            </button>
            <div id="categoryMobileMenu" class="hidden bg-white text-black rounded-lg shadow-lg">
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Massage</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Facial</a>
                <a href="#" class="block px-4 py-2 hover:bg-gray-200">Body Scrub</a>
            </div>
        </div>

        <!-- Mobile Search -->
        <div class="px-4 py-2">
            <input type="text" placeholder="Search..." class="w-full px-4 py-2 rounded bg-white text-black">
        </div>

        <!-- Mobile Auth Buttons -->
        <div class="px-4 py-2">
            <a href="#" class="block text-center bg-[#8b5a2b] px-4 py-2 rounded hover:bg-[#6b4223]">Login</a>
            <a href="#" class="block text-center border border-white px-4 py-2 mt-2 rounded hover:bg-white hover:text-black">Sign Up</a>
        </div>
    </div>
</header>

<script>
    document.getElementById("menuBtn").addEventListener("click", function () {
        let mobileMenu = document.getElementById("mobileMenu");
        mobileMenu.classList.toggle("hidden");
        setTimeout(() => {
            mobileMenu.classList.toggle("opacity-0");
        }, 10);
    });

    document.getElementById("categoryMobileBtn").addEventListener("click", function () {
        let categoryMenu = document.getElementById("categoryMobileMenu");
        categoryMenu.classList.toggle("hidden");

        let icon = this.querySelector("i");
        icon.classList.toggle("rotate-180");
    });

    // Handle dropdown click on touch devices
    document.getElementById("categoryBtn").addEventListener("click", function () {
        let dropdown = document.getElementById("categoryDropdown");
        dropdown.classList.toggle("opacity-100");
        dropdown.classList.toggle("visible");
    });

    document.addEventListener("click", function (event) {
        let dropdown = document.getElementById("categoryDropdown");
        let btn = document.getElementById("categoryBtn");

        if (!btn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove("opacity-100", "visible");
        }
    });
</script>



<main>
  @yield('content')
</main>

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