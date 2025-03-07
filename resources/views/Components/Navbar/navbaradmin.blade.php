<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="/fontawesome-/css/all.css">
</head>

<body class="flex bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar"
        class="pt-12 w-64 bg-[#2c1a0f] text-white h-screen fixed top-0 left-0 transition-all duration-300">

        <ul class="mt-4">
            <li class="p-4 hover:bg-[#3a2416] flex items-center space-x-3">
                <i class="fas fa-home"></i> <span class="sidebar-text">Dashboard</span>
            </li>
            <li class="p-4 hover:bg-[#3a2416] flex items-center space-x-3">
                <i class="fas fa-calendar"></i> <span class="sidebar-text">Manajemen Booking</span>
            </li>
            <li class="p-4 hover:bg-[#3a2416] flex items-center space-x-3">
                <i class="fas fa-user"></i> <span class="sidebar-text">Manajemen Therapist</span>
            </li>
            <li class="p-4 hover:bg-[#3a2416] flex items-center space-x-3">
                <i class="fas fa-cog"></i> <span class="sidebar-text">Pengaturan</span>
            </li>
        </ul>
    </div>

    <!-- Navbar -->
    <div id="navbar"
        class="fixed top-0 left-0 w-full bg-[#2c1a0f] text-white h-16 flex items-center px-6 shadow-md z-40 transition-all duration-300 pl-64">
        <button id="toggle-sidebar" class="text-white text-xl focus:outline-none">
            <i class="fas fa-bars"></i>
        </button>
        <div class="flex-grow"></div>

        <!-- Admin Profile -->
        <div class="flex items-center space-x-4">
            <a href="#" class="flex items-center space-x-2 border rounded-lg px-4 py-2 hover:bg-gray-800">
                <img src="/images/1.jpg" alt="Admin" class="h-10 w-10 rounded-full">
                <span class="hidden md:inline">John Doe</span>
            </a>
            <a href="/logout"
                class="text-white bg-red-600 px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-200">
                Logout
            </a>
        </div>
    </div>

    <!-- Main Content -->
    {{-- <div id="main-content" class="flex flex-col min-h-screen pt-16 ml-64 transition-all"> --}}
    <div id="main-content" class="ml-64 p-6 transition-all duration-300 w-full min-h-screen pt-16 overflow-auto">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p>Selamat datang di panel admin!</p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleSidebar = document.getElementById('toggle-sidebar');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const navbar = document.getElementById('navbar');
            const sidebarText = document.querySelectorAll('.sidebar-text');

            toggleSidebar.addEventListener('click', function() {
                if (sidebar.classList.contains('w-64')) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-20');
                    navbar.classList.remove('pl-64');
                    navbar.classList.add('pl-20');

                    sidebarText.forEach(text => text.classList.add('hidden'));
                } else {
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                    mainContent.classList.remove('ml-20');
                    mainContent.classList.add('ml-64');
                    navbar.classList.remove('pl-20');
                    navbar.classList.add('pl-64');

                    sidebarText.forEach(text => text.classList.remove('hidden'));
                }
            });
        });
    </script>

</body>

</html>
