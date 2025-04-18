<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Layout</title>
    <link rel="stylesheet" href="/fontawesome-/css/all.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="flex flex-col min-h-screen ">
    @auth
        @include('Components.Navbar.nav-customer')
    @endauth
    @guest
        @include('Components.Navbar.nav-guest')
    @endguest
    <main>
        @yield('content')
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
                        <li><a href="/kategori/facetreatment" class="text-gray-300 hover:text-white">Face
                                Treatment</a>
                        </li>
                        <li><a href="/kategori/bodytreatment" class="text-gray-300 hover:text-white">Body
                                Treatment</a></li>
                        <li><a href="/kategori/hairtreatment" class="text-gray-300 hover:text-white">Hair
                                Treatment</a></li>
                        <li><a href="/kategori/reflexology" class="text-gray-300 hover:text-white">Reflexology</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold">Layanan</h3>
                    <ul class="mt-2 space-y-2">
                        <li><a href="/kategori/treatmentpackages" class="text-gray-300 hover:text-white">Treatment
                                Packages</a></li>
                        <li><a href="/kategori/alacarte" class="text-gray-300 hover:text-white">Ala Carte</a></li>
                        <li><a href="/kategori/prewedding" class="text-gray-300 hover:text-white">Prewedding</a></li>
                    </ul>
                </div>
            </div>

            <!-- Sosial Media & Hak Cipta -->
            <div class="text-center md:text-right">
                <h3 class="text-lg font-semibold">Ikuti Kami</h3>
                <div class="flex justify-center md:justify-end space-x-4 mt-3">
                    <a href="https://www.instagram.com/roemahrempahspa_manado?igsh=OWN5dzZoYm4wZmN5"
                        class="text-gray-300 hover:text-white text-xl"><i class="fa-brands fa-instagram"></i> </a>
                    <a href="https://wa.me/+628114321986" class="text-gray-300 hover:text-white text-xl"><i
                            class="fa-brands fa-whatsapp"></i></a>
                    <a href="https://www.facebook.com/share/1Bnq97Cerr/"
                        class="text-gray-300 hover:text-white text-xl"><i class="fa-brands fa-facebook"></i></a>
                </div>
                <p class="text-gray-400 mt-4">© 2025 Roemah Rempah Spa. All Rights Reserved.</p>
            </div>

        </div>
    </footer>

</body>

</html>
