    <!-- Navbar -->
    <header class="bg-[#2c1a0f] text-white shadow-md fixed  w-full z-50">
        <div class="container mx-auto flex justify-between items-center px-4 md:px-6 lg:px-8 py-4">
            <!-- Logo -->
            <a href="/" class="flex items-center space-x-2">
                <img src="/Images/RRS_Background.png" alt="Logo" class="h-16 w-auto">
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

                    </div>
                </div>
                {{-- <a href="/alltreatment" class="hover:text-gray-300">All Treatment</a> --}}
                <a href="/gallery" class="hover:text-gray-300">Room</a>
                <a href="/contact" class="hover:text-gray-300">Contact Us</a>
                @if (auth()->user()->role === 'admin')
                    <a href="/riwayatbooking-preview" class="hover:text-gray-300">Bookings History</a>
                    <a href="/promo-preview" class="hover:text-gray-300">Promo</a>
                    <a href="/membership-preview" class="hover:text-gray-300">Membership</a>
                @else
                    <a href="/riwayatbooking" class="hover:text-gray-300">Bookings History</a>
                    <a href="/promo" class="hover:text-gray-300">Promo</a>
                    <a href="/membership" class="hover:text-gray-300">Membership</a>
                @endif

            </nav>

            <!-- Search & Auth Buttons (Hanya muncul di layar 1024px ke atas) -->
            <div class="hidden lg:flex items-center space-x-4">

                {{-- <div class="relative">
                  <i
                      class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                  <input type="text" placeholder="Search..."
                      class="pl-10 px-4 py-1 rounded bg-white text-black w-40">
              </div> --}}

                {{-- <a href="/login" class="bg-[#8b5a2b] px-4 py-2 rounded hover:bg-[#6b4223]">Login</a>
              <a href="/register" class="border border-white px-4 py-2 rounded hover:bg-white hover:text-black">Sign
                  Up</a> --}}
                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                                {{-- Foto Profil --}}
                                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                                    class="h-8 w-8 rounded-full object-cover mr-2">

                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                              this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Hamburger -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Responsive Navigation Menu -->
                <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                                class="h-8 w-8 rounded-full object-cover mr-2">

                            <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                      this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                </div>
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

                </div>
            </div>

            {{-- <a href="#layanan" class="block px-4 py-2 border-b border-gray-600">Layanan</a> --}}
            <a href="/gallery" class="block px-4 py-2 border-b border-gray-600">Room</a>
            <a href="/contact" class="block px-4 py-2 border-b border-gray-600">Contact Us</a>
            @if (auth()->user()->role === 'admin')
                <a href="/riwayatbooking-preview" class="block px-4 py-2 border-b border-gray-600">History
                    Bookings</a>
                <a href="/promo-preview" class="block px-4 py-2 border-b border-gray-600">Promo</a>
                <a href="/membership-preview" class="block px-4 py-2 border-b border-gray-600">Membership</a>
            @else
                <a href="/riwayatbooking" class="block px-4 py-2 border-b border-gray-600">History Bookings</a>
                <a href="/promo" class="block px-4 py-2 border-b border-gray-600">Promo</a>
                <a href="/membership" class="block px-4 py-2 border-b border-gray-600">Membership</a>
            @endif


            <!-- Mobile Search -->
            {{-- <div class="px-4 py-2">
              <input type="text" placeholder="Search..." class="w-full px-4 py-2 rounded bg-white text-black">
          </div> --}}

            <!-- Mobile Auth Buttons -->
            {{-- <div class="px-4 py-2">
              <a href="/login" class="block text-center bg-[#8b5a2b] px-4 py-2 rounded hover:bg-[#6b4223]">Login</a>
              <a href="/register"
                  class="block text-center border border-white px-4 py-2 mt-2 rounded hover:bg-white hover:text-black">Sign
                  Up</a>
          </div> --}}
            <!-- Settings Dropdown -->
            <div class="flex flex-col px-4 py-2 space-y-2 border-t border-gray-600 text-sm">
                {{-- Foto Profil --}}
                <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                    class="h-8 w-8 rounded-full object-cover mr-2">

                <div class="text-white font-semibold">{{ Auth::user()->name }}</div>
                <div class="text-gray-300">{{ Auth::user()->email }}</div>

                <a href="{{ route('profile.edit') }}" class="text-gray-300 hover:text-white">Profile</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-left text-gray-300 hover:text-white w-full">
                        Log Out
                    </button>
                </form>
            </div>

            <!-- Hamburger -->
            {{-- <div class="-me-2 flex items-center sm:hidden">
              <button @click="open = ! open"
                  class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                  <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                      <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                      <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                          stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
              </button>
          </div> --}}
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
