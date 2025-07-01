<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Navbar</title>
    <link rel="stylesheet" href="/fontawesome-/css/all.css">
</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-[#2c1f11] shadow-md fixed top-0 w-full z-50">
        <div class="container mx-auto px-4 lg:px-10 py-3 flex flex-wrap items-center justify-between">
            <!-- Logo -->
            <a href="#" class="flex items-center space-x-2">
                <img src="/images/RRS_Background.png" alt="Logo" class="w-16 h-16">
                <span class="text-lg font-bold text-center font-[Lucida_Calligraphy] text-white">Roemah Rempah Spa<br>
                    Manado</span>
            </a>

            <!-- Hamburger Menu (Mobile) -->
            <button id="menuButton" class="md:hidden text-white text-2xl focus:outline-none">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Menu (Desktop & Mobile) -->
            <div id="navMenu"
                class="hidden md:flex flex-col md:flex-row md:items-center w-full md:w-auto bg-[#2c1f11] md:bg-transparent p-5 md:p-0 shadow-md md:shadow-none">

                <!-- Dropdown Category -->
                <div class="relative mb-3 md:mb-0 w-full md:w-auto">
                    <button id="categoryButton"
                        class="w-full md:w-auto px-3 py-2 text-xl text-white hover:bg-black rounded-md flex items-center justify-between">
                        Category <i class="fa-solid fa-caret-down ml-6"></i>
                    </button>
                    <!-- Submenu -->
                    <div id="categoryMenu"
                        class="absolute left-0 w-full md:w-48 bg-white shadow-lg rounded-md hidden overflow-y-auto max-h-60 z-50">
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Massage</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Facial</a>
                        <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Body Scrub</a>
                    </div>
                </div>

                <a href="#"
                    class="block md:inline px-3 py-2 text-xl text-white hover:bg-black rounded-md">Layanan</a>
                <a href="#" class="block md:inline px-3 py-2 text-xl text-white hover:bg-black rounded-md">Tentang
                    Kami</a>

                <!-- Search Box -->
                <div class="relative w-full md:w-80 mt-3 md:mt-0 md:mr-6">
                    <i
                        class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                    <input type="text" placeholder="Search..."
                        class="w-full pl-10 px-4 py-2 bg-white border border-gray-300 text-black rounded-lg focus:ring-2 focus:ring-black">
                </div>

                <!-- Menu Kanan (Login / Profile) -->
                <div
                    class="flex flex-wrap justify-center md:flex-row items-center w-full md:w-auto space-y-2 md:space-y-0 md:space-x-4 mt-3 md:mt-0 gap-1">
                    <a href="/login"
                        class="w-full md:w-auto px-4 py-2 bg-[#8B5E3B] text-white rounded-lg hover:bg-[#6D422A] transition text-center">
                        Login
                    </a>
                    <a href="/signup"
                        class="w-full md:w-auto px-4 py-2 border border-[#8B5E3B] text-[#8B5E3B] rounded-lg hover:bg-[#8B5E3B] hover:text-white transition text-center">
                        Sign Up
                    </a>
                </div>
            </div>

            <!-- Menu Kanan (Login / Profile) -->

        </div>
    </nav>

    <!-- JavaScript untuk Hamburger Menu -->
    <script>
        document.getElementById('menuButton').addEventListener('click', function() {
            document.getElementById('navMenu').classList.toggle('hidden');
            document.getElementById('authMenu').classList.toggle('hidden');
        });

        document.getElementById('categoryButton').addEventListener('click', function() {
            document.getElementById('categoryMenu').classList.toggle('hidden');
        });
        // Menutup dropdown saat klik di luar
        document.addEventListener('click', function(event) {
            const categoryMenu = document.getElementById('categoryMenu');
            const categoryButton = document.getElementById('categoryButton');

            if (!categoryMenu.contains(event.target) && !categoryButton.contains(event.target)) {
                categoryMenu.classList.add('hidden');
            }
        });
    </script>

</body>

</html>
