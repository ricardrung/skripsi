<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="/fontawesome-/css/all.css">
</head>

<body class="bg-gray-100">
    <div id="sidebar"
        class="fixed inset-y-0 left-0 h-screen bg-[#2c1a0f] text-white flex flex-col z-50 w-64 transition-all duration-300 md:relative">

        <!-- Logo -->
        <div class="flex items-center justify-center h-16 border-b border-white">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-12">
        </div>

        <!-- Menu -->
        <nav class="flex-1 px-2 py-4 space-y-2 overflow-y-auto">
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-home w-5"></i>
                <span class="ml-3 sidebar-text">Dashboard</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-calendar-check w-5"></i>
                <span class="ml-3 sidebar-text">Manajemen Booking</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-edit w-5"></i>
                <span class="ml-3 sidebar-text">Input Booking Manual</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-user-md w-5"></i>
                <span class="ml-3 sidebar-text">Manajemen Therapist</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-spa w-5"></i>
                <span class="ml-3 sidebar-text">Manajemen Paket Treatment</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-box w-5"></i>
                <span class="ml-3 sidebar-text">Manajemen Ketersediaan Bahan</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-money-check-alt w-5"></i>
                <span class="ml-3 sidebar-text">Manajemen Pembayaran</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-users w-5"></i>
                <span class="ml-3 sidebar-text">Manajemen Pelanggan</span>
            </a>
            <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-white hover:text-[#2c1a0f]">
                <i class="fas fa-chart-line w-5"></i>
                <span class="ml-3 sidebar-text">Laporan & Analitik</span>
            </a>
        </nav>
    </div>



</body>

</html>
