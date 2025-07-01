<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="/fontawesome-/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar"
        class="fixed top-0 left-0 h-screen bg-[#2c1a0f] text-white w-64 z-50 transition-all duration-300 overflow-y-auto  md:translate-x-0 -translate-x-full">

        <!-- Logo -->
        <div class="flex justify-center items-center py-4">
            <img id="logo" src="/Images/RRS_Background.png" alt="Logo"
                class="h-36 w-auto object-contain transition-all duration-300">
        </div>

        <!-- Menu -->
        <ul class="border-t border-gray-300 mt-4">
            <li>
                <a href="/dashboard-admin"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('dashboard-admin') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-home"></i> <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/manajemen-booking"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('manajemen-booking') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-calendar"></i> <span class="sidebar-text">Manajemen Booking</span>
                </a>
            </li>
            <li>
                <a href="/input-booking-manual"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('input-booking-manual') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-edit"></i> <span class="sidebar-text">Input Booking Manual</span>
                </a>
            </li>
            <li>
                <a href="/manajemen-therapist"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('manajemen-therapist') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-user"></i> <span class="sidebar-text">Manajemen Therapist</span>
                </a>
            </li>
            <li>
                <a href="/manajemen-paket-treatment"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('manajemen-paket-treatment') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-box"></i> <span class="sidebar-text">Manajemen Paket Treatment</span>
                </a>
            </li>
            <li>
                <a href="/manajemen-ruangan"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('manajemen-ruangan') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-flask"></i> <span class="sidebar-text">Manajemen Ruangan
                    </span>
                </a>
            </li>
            {{-- <li>
                <a href="/manajemen-promo" class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block">
                    <i class="fa-solid fa-receipt"></i><span class="sidebar-text">Manajemen promo</span>
                </a>
            </li> --}}
            <li>
                <a href="/manajemen-pembayaran"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('manajemen-pembayaran') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-credit-card"></i> <span class="sidebar-text">Manajemen Pembayaran</span>
                </a>
            </li>
            <li>
                <a href="/manajemen-pelanggan"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('manajemen-pelanggan') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-users"></i> <span class="sidebar-text">Manajemen Pelanggan</span>
                </a>
            </li>
            <li>
                <a href="/memberships"
                    class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block  {{ request()->is('memberships') ? 'bg-[#3a2416]' : '' }}">
                    <i class="fas fa-medal"></i><span class="sidebar-text">Manajemen Memberships</span>
                </a>
            </li>
            {{-- <li>
                <a href="/laporan-analitik" class="p-6 hover:bg-[#3a2416] flex items-center space-x-3 block">
                    <i class="fas fa-chart-line"></i> <span class="sidebar-text">Laporan & Analitik</span>
                </a>
            </li> --}}
        </ul>
        <div class="border-t border-gray-300 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="text-white bg-red-600 px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-200 flex items-center justify-center w-full">
                    <i class="fas fa-sign-out-alt"></i> <span class="sidebar-text ml-2">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Overlay (untuk mobile) -->
    <div id="overlay" class="fixed inset-0 bg-transparant bg-opacity-50 hidden md:hidden z-40"></div>

    <!-- Navbar -->
    <div id="navbar"
        class="fixed top-0 left-0 w-full bg-[#2c1a0f] text-white h-16 flex items-center px-6 shadow-md z-40 transition-all duration-300 md:pl-64">
        <button id="toggle-sidebar" class="text-white text-xl focus:outline-none pl-4">
            <i class="fas fa-bars"></i>
        </button>

        <div class="flex-grow"></div>

        <!-- Admin Profile Desktop-->
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">

                        {{-- Foto Profil --}}
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                            class="h-8 w-8 rounded-full object-cover mr-2">


                        {{-- Nama --}}
                        <div>{{ Auth::user()->name }}</div>

                        {{-- Icon Panah --}}
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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

        <!-- Admin Profile Mobile -->
        <div class="sm:hidden">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button
                        class="inline-flex items-center border border-transparent focus:outline-none transition ease-in-out duration-150">
                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}"
                            class="h-8 w-8 rounded-full object-cover mr-2">
                        <span class="text-white text-sm font-medium">{{ Auth::user()->name }}</span>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-dropdown-link>

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

    </div>

    <!-- Konten utama -->
    <div id="main-content" class="pt-16 transition-all duration-300 md:ml-64">
        <div class="p-6">
            @yield('content')
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Ambil semua tautan sidebar
            const sidebarLinks = document.querySelectorAll('#sidebar a');

            // Loop melalui semua tautan dan tambahkan event listener
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    // Hapus kelas 'active' dari semua tautan
                    sidebarLinks.forEach(link => link.classList.remove('bg-[#3a2416]'));

                    // Tambahkan kelas 'active' pada tautan yang diklik
                    this.classList.add('bg-[#3a2416]');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const navbar = document.getElementById('navbar');
            const mainContent = document.getElementById('main-content');
            const sidebarText = document.querySelectorAll('.sidebar-text');
            const logo = document.getElementById('logo');

            toggleSidebar.addEventListener('click', function() {
                if (window.innerWidth < 768) {
                    // Mode Mobile: Toggle Sidebar Overlay
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                } else {
                    // Mode Desktop: Toggle Sidebar Kecil/Besar
                    if (sidebar.classList.contains('w-64')) {
                        // Kecilkan Sidebar
                        sidebar.classList.remove('w-64');
                        sidebar.classList.add('w-20');
                        navbar.classList.remove('md:pl-64');
                        navbar.classList.add('md:pl-20');
                        mainContent.classList.remove('md:ml-64');
                        mainContent.classList.add('md:ml-20');

                        sidebarText.forEach(text => text.classList.add('hidden'));

                        // Perkecil logo jika ada
                        if (logo) {
                            logo.classList.remove('h-36');
                            logo.classList.add('h-12');
                        }
                    } else {
                        // Perbesar Sidebar
                        sidebar.classList.remove('w-20');
                        sidebar.classList.add('w-64');
                        navbar.classList.remove('md:pl-20');
                        navbar.classList.add('md:pl-64');
                        mainContent.classList.remove('md:ml-20');
                        mainContent.classList.add('md:ml-64');

                        sidebarText.forEach(text => text.classList.remove('hidden'));

                        // Perbesar logo jika ada
                        if (logo) {
                            logo.classList.remove('h-12');
                            logo.classList.add('h-36');
                        }
                    }
                }
            });

            // Tutup sidebar saat overlay diklik di mobile
            overlay.addEventListener('click', function() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            });
        });
    </script>

    <script>
        function confirmDeleteTreatment(id) {
            Swal.fire({
                title: 'Hapus Treatment?',
                text: "Data ini tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-treatment-' + id).submit();
                }
            });
        }
    </script>


</body>

</html>
