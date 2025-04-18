    <!-- Navbar -->
    <header class="bg-[#2c1a0f] text-white shadow-md fixed  w-full z-50">
        <div class="container mx-auto flex justify-between items-center px-4 md:px-6 lg:px-8 py-4">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2">
                <img src="/images/RRS_Background.png" alt="Logo" class="h-16 w-auto">
                <span class="font-bold text-xl text-center">Roemah Rempah Spa <br> Manado</span>
            </a>

            <!-- Desktop Menu (Hanya muncul di layar 1024px ke atas) -->
            <nav class="hidden lg:flex space-x-6">
                <div class="relative group">
                    <button id="categoryBtn" class="focus:outline-none flex items-center">
                        Treatments <i class="fa-solid fa-caret-down ml-2"></i>
                    </button>
                    <div id="categoryDropdown"
                        class="absolute left-0 mt-2 w-48 bg-white text-black rounded-lg shadow-lg opacity-0 invisible transition-all duration-300 group-hover:opacity-100 group-hover:visible">
                        <a href="/kategori/facetreatment" class="block px-4 py-2 hover:bg-gray-200">Face Treatment</a>
                        <a href="/kategori/bodytreatment" class="block px-4 py-2 hover:bg-gray-200">Body Treatment</a>
                        <a href="/kategori/hairtreatment" class="block px-4 py-2 hover:bg-gray-200">Hair
                            Treatment</a>
                        <a href="/kategori/reflexology" class="block px-4 py-2 hover:bg-gray-200">Reflexology</a>
                        <a href="/kategori/treatmentpackages" class="block px-4 py-2 hover:bg-gray-200">Treatment
                            Packages</a>
                        <a href="/kategori/alacarte" class="block px-4 py-2 hover:bg-gray-200">Ala Carte</a>
                        <a href="/kategori/prewedding" class="block px-4 py-2 hover:bg-gray-200">Prewedding</a>
                    </div>
                </div>
                {{-- <a href="/alltreatment" class="hover:text-gray-300">All Treatment</a> --}}
                <a href="/gallery" class="hover:text-gray-300">Gallery</a>
                <a href="/contact" class="hover:text-gray-300">Contact Us</a>
            </nav>

            <!-- Search & Auth Buttons (Hanya muncul di layar 1024px ke atas) -->
            <div class="hidden lg:flex items-center space-x-4">
                {{-- <div class="relative">
                  <i
                      class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                  <input type="text" placeholder="Search..."
                      class="pl-10 px-4 py-1 rounded bg-white text-black w-40">
              </div> --}}
                <a href="/login" class="bg-[#8b5a2b] px-4 py-2 rounded hover:bg-[#6b4223]">Login</a>
                <a href="/register" class="border border-white px-4 py-2 rounded hover:bg-white hover:text-black">Sign
                    Up</a>
            </div>

            <!-- Mobile Menu Button (Muncul di layar < 1024px) -->
            <button id="menuBtn" class="lg:hidden text-white text-2xl focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Menu (Muncul di layar < 1024px) -->
        <div id="mobileMenu"
            class="hidden lg:hidden bg-[#2c1a0f] text-white flex-col transition-all duration-300 opacity-0">

            <!-- Mobile Dropdown -->
            <div class="border-b border-gray-600">
                <button id="categoryMobileBtn" class="w-full text-left px-4 py-2 flex justify-between">
                    Treatments <i class="fa-solid fa-caret-down transition-transform duration-300"></i>
                </button>
                <div id="categoryMobileMenu" class="hidden bg-white text-black rounded-lg shadow-lg">
                    <a href="/kategori/facetreatment" class="block px-4 py-2 hover:bg-gray-200">Face Treatment</a>
                    <a href="/kategori/bodytreatment" class="block px-4 py-2 hover:bg-gray-200">Body Treatment</a>
                    <a href="/kategori/hairtreatment" class="block px-4 py-2 hover:bg-gray-200">Hair Treatment</a>
                    <a href="/kategori/reflexology" class="block px-4 py-2 hover:bg-gray-200">Reflexology</a>
                    <a href="/kategori/treatmentpackages" class="block px-4 py-2 hover:bg-gray-200">Treatment
                        Packages</a>
                    <a href="/kategori/alacarte" class="block px-4 py-2 hover:bg-gray-200">Ala Carte</a>
                    <a href="/kategori/prewedding" class="block px-4 py-2 hover:bg-gray-200">Prewedding</a>
                </div>
            </div>

            {{-- <a href="#layanan" class="block px-4 py-2 border-b border-gray-600">Layanan</a> --}}
            <a href="/gallery" class="block px-4 py-2 border-b border-gray-600">Gallery</a>
            <a href="/contact" class="block px-4 py-2 border-b border-gray-600">Contact Us</a>

            <!-- Mobile Search -->
            {{-- <div class="px-4 py-2">
              <input type="text" placeholder="Search..." class="w-full px-4 py-2 rounded bg-white text-black">
          </div> --}}

            <!-- Mobile Auth Buttons -->
            <div class="px-4 py-2">
                <a href="/login" class="block text-center bg-[#8b5a2b] px-4 py-2 rounded hover:bg-[#6b4223]">Login</a>
                <a href="/register"
                    class="block text-center border border-white px-4 py-2 mt-2 rounded hover:bg-white hover:text-black">Sign
                    Up</a>
            </div>
        </div>
    </header>

    <script>
        document.getElementById("menuBtn").addEventListener("click", function() {
            let mobileMenu = document.getElementById("mobileMenu");
            mobileMenu.classList.toggle("hidden");
            setTimeout(() => {
                mobileMenu.classList.toggle("opacity-0");
            }, 10);
        });

        document.getElementById("categoryMobileBtn").addEventListener("click", function() {
            let categoryMenu = document.getElementById("categoryMobileMenu");
            categoryMenu.classList.toggle("hidden");

            let icon = this.querySelector("i");
            icon.classList.toggle("rotate-180");
        });

        // Handle dropdown click on touch devices
        document.getElementById("categoryBtn").addEventListener("click", function() {
            let dropdown = document.getElementById("categoryDropdown");
            dropdown.classList.toggle("opacity-100");
            dropdown.classList.toggle("visible");
        });

        document.addEventListener("click", function(event) {
            let dropdown = document.getElementById("categoryDropdown");
            let btn = document.getElementById("categoryBtn");

            if (!btn.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove("opacity-100", "visible");
            }
        });
    </script>
