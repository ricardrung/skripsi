@extends('Components.Layout.layout')

@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-5xl font-bold tracking-tight text-[#2c1a0f] text-center">Contact Us</h1>
            <p class="mt-2 text-lg text-gray-600 text-center">Kami siap membantu Anda!</p>
        </div>
    </header>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- WhatsApp Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                    <i class="fab fa-whatsapp text-[#25D366] text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-[#2c1a0f] mb-2">WhatsApp</h2>
                    <p class="text-gray-600 text-center mb-4">Hubungi kami melalui WhatsApp untuk pertanyaan cepat.</p>
                    <a href="https://wa.me/628114321986"
                        class="bg-[#25D366] text-white py-2 px-4 rounded-md hover:bg-[#1DAF5A] transition">Hubungi via
                        WhatsApp</a>
                </div>

                <!-- Instagram Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                    <i class="fab fa-instagram text-[#E1306C] text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-[#2c1a0f] mb-2">Instagram</h2>
                    <p class="text-gray-600 text-center mb-4">Ikuti kami di Instagram untuk update terbaru.</p>
                    <a href="https://www.instagram.com/roemahrempahspa_manado?igsh=OWN5dzZoYm4wZmN5"
                        class="bg-[#E1306C] text-white py-2 px-4 rounded-md hover:bg-[#C13584] transition">DM Kami di
                        Instagram</a>
                </div>

                <!-- Facebook Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                    <i class="fab fa-facebook text-[#3b5998] text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-[#2c1a0f] mb-2">Facebook</h2>
                    <p class="text-gray-600 text-center mb-4">Temukan kami di Facebook untuk informasi lebih lanjut.</p>
                    <a href="https://www.facebook.com/share/1Bnq97Cerr/"
                        class="bg-[#3b5998] text-white py-2 px-4 rounded-md hover:bg-[#2d4373] transition">Kunjungi
                        Facebook</a>
                </div>

                <!-- Email Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                    <i class="fas fa-envelope text-[#FF5733] text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-[#2c1a0f] mb-2">Email</h2>
                    <p class="text-gray-600 text-center mb-4">Kirimkan pertanyaan Anda melalui email.</p>
                    <a href="mailto:info@roemahrempahspamanado@gmail.com"
                        class="bg-[#FF5733] text-white py-2 px-4 rounded-md hover:bg-[#C70039] transition">Kirim Email</a>
                </div>

                <!-- Google Maps Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                    <i class="fas fa-map-marker-alt text-[#8b5a2b] text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-[#2c1a0f] mb-2">Lokasi Kami</h2>
                    <p class="text-gray-600 text-center mb-4">Temukan lokasi kami di Google Maps.</p>
                    <a href="https://maps.app.goo.gl/zqqgesUuV4mHyoTw6"
                        class="bg-[#8b5a2b] text-white py-2 px-4 rounded-md hover:bg-[#6b4223] transition"
                        target="_blank">Dapatkan Petunjuk Arah</a>
                </div>

                <!-- Phone Card -->
                <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
                    <i class="fas fa-phone-alt text-[#007BFF] text-6xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-[#2c1a0f] mb-2">Telepon</h2>
                    <p class="text-gray-600 text-center mb-4">Hubungi kami langsung melalui telepon.</p>
                    <a href="tel:+628114321986"
                        class="bg-[#007BFF] text-white py-2 px-4 rounded-md hover:bg-[#0056b3] transition">Telepon
                        Sekarang</a>
                </div>
            </div>
        </div>
    </section>
@endsection
