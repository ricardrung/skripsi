@extends('Components.Layout.layout')
@section('content')
    <!-- Hero Section -->
    <section class="relative w-full min-h-screen flex items-center justify-center bg-cover bg-center pt-20 sm:pt-0">
        <div class="absolute inset-0 bg-gradient-to-br from-[#2c1a0f] via-[#5a3e2b] to-[#1a1a1a] "></div>
        <div class="relative text-center text-white px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 mt-16">ROEMAH REMPAH SPA MANADO</h1>
            <p class="text-lg md:text-xl mb-6">Spa Tradisional Berkualitas, Dengan Harga Terjangkau.</p>
            <button onclick="location.href='#layanan'"
                class="mb-2 bg-[#8b5a2b] text-white px-6 py-3 rounded-lg text-lg hover:bg-[#6b4223] transition">
                Best Selling
            </button>
        </div>
    </section>

    {{-- best selling --}}
    @include('Pages.Kategori.bestselling')

    <!-- Tentang Kami -->
    <section id="tentang" class="py-16 bg-white scroll-mt-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8 text-[#2c1a0f]">About Us</h2>
            <p class="text-lg text-gray-700 max-w-2xl mx-auto mb-6">
                Roemah Rempah Spa adalah tempat terbaik untuk menikmati berbagai perawatan spa dengan bahan alami.
                Kami berkomitmen memberikan pengalaman relaksasi yang tak terlupakan.
            </p>

            <!-- Visi & Misi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <h3 class="text-2xl text-center font-semibold text-[#2c1a0f]">Visi</h3>
                    <p class="text-gray-700 mt-2">Menjadi spa terbaik dengan layanan berkualitas yang mengutamakan
                        kesehatan dan kenyamanan pelanggan.</p>
                </div>
                <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                    <h3 class="text-2xl text-center font-semibold text-[#2c1a0f]">Misi</h3>
                    <ul class="list-disc pl-6 mt-2 text-gray-700">
                        <li>Menggunakan bahan alami untuk setiap perawatan.</li>
                        <li>Memberikan layanan profesional dengan therapist berpengalaman.</li>
                        <li>Mengutamakan kenyamanan dan kepuasan pelanggan.</li>
                    </ul>
                </div>
            </div>


            <!-- Jadwal Operasional -->
            <div class="mt-8 bg-gray-100 text-gray-700 text-center py-4 rounded-lg shadow-md">
                <h3 class="text-2xl text-[#2c1a0f] font-semibold">Jadwal Operasional</h3>
                <p class="text-lg mt-2">Buka Setiap Hari</p>
                <p class="text-lg"><i class="fa-regular fa-clock"></i> 10:00 - 22:00 </p>
            </div>


            <!-- Gambar Tim -->
            <div class="mt-12">
                <h3 class="text-2xl font-bold mb-6 text-[#2c1a0f]">Our Team</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach ($therapists as $therapist)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $therapist->photo) }}" alt="{{ $therapist->name }}"
                                class="w-40 h-40 object-cover rounded-full mx-auto">
                            <h4 class="mt-4 text-xl font-semibold">{{ $therapist->name }}</h4>
                        </div>
                    @endforeach
                </div>
            </div>


    </section>

    <!-- Peta Lokasi -->
    <section id="peta" class="py-16 bg-gray-100 scroll-mt-16">
        <div>
            <h3 class="text-3xl font-bold mb-8 text-center text-[#2c1a0f]">Our Location</h3>
            <div class="w-full h-64 md:h-80">
                <iframe class="w-full h-full rounded-lg shadow-md"
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6863.692753629921!2d124.9118368143122!3d1.5321754505143814!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3287a129b52714c3%3A0xc681ab54f7562c6!2sRoemah%20Rempah!5e0!3m2!1sen!2sus!4v1739799474362!5m2!1sen!2sus"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        </div>
    </section>
@endsection
