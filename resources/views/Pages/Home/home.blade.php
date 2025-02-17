<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>
  <link rel="stylesheet" href="/fontawesome-/css/all.css">
  @vite('resources/css/app.css')
  
</head>
<body class="bg-gray-100">
  @extends('Components.Layout.layout')
  @section('content' ) 
 <!-- Hero Section -->
<!-- Hero Section -->
<section class="relative w-full min-h-screen flex items-center justify-center bg-cover bg-center pt-20 sm:pt-0" style="background-image: url('/images/spa-hero.jpg');">
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    <div class="relative text-center text-white px-4">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Nikmati Relaksasi Sempurna di Roemah Rempah Spa</h1>
        <p class="text-lg md:text-xl mb-6">Temukan berbagai layanan spa yang menenangkan untuk tubuh dan jiwa Anda.</p>
        <a href="#layanan" class="bg-[#8b5a2b] text-white px-6 py-3 rounded-lg text-lg hover:bg-[#6b4223] transition">
            Lihat Layanan
        </a>
    </div>
</section>


<!-- Daftar Layanan -->
<section id="layanan" class="py-16 bg-gray-100 scroll-mt-16">
  <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl font-bold mb-8 text-[#2c1a0f]">Layanan Spa Kami</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Card 1 -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <img src="/images/massage.jpg" alt="Massage" class="w-full h-48 object-cover">
              <div class="p-6">
                  <h3 class="text-2xl font-semibold text-[#2c1a0f]">Massage</h3>
                  <p class="text-gray-700 my-2">Nikmati pijatan relaksasi yang menyegarkan tubuh dan pikiran.</p>
                  <span class="block text-lg font-bold text-[#8b5a2b]">Rp 150.000</span>
                  <a href="#" class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                      Lihat Detail
                  </a>
              </div>
          </div>
          
          <!-- Card 2 -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <img src="/images/facial.jpg" alt="Facial" class="w-full h-48 object-cover">
              <div class="p-6">
                  <h3 class="text-2xl font-semibold text-[#2c1a0f]">Facial</h3>
                  <p class="text-gray-700 my-2">Perawatan wajah yang menyegarkan dan meremajakan kulit Anda.</p>
                  <span class="block text-lg font-bold text-[#8b5a2b]">Rp 120.000</span>
                  <a href="#" class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                      Lihat Detail
                  </a>
              </div>
          </div>

          <!-- Card 3 -->
          <div class="bg-white shadow-lg rounded-lg overflow-hidden">
              <img src="/images/body-scrub.jpg" alt="Body Scrub" class="w-full h-48 object-cover">
              <div class="p-6">
                  <h3 class="text-2xl font-semibold text-[#2c1a0f]">Body Scrub</h3>
                  <p class="text-gray-700 my-2">Mengangkat sel kulit mati untuk kulit yang lebih halus dan cerah.</p>
                  <span class="block text-lg font-bold text-[#8b5a2b]">Rp 130.000</span>
                  <a href="#" class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                      Lihat Detail
                  </a>
              </div>
          </div>
      </div>
  </div>
</section>

<!-- Tentang Kami -->
<section id="tentang" class="py-16 bg-white scroll-mt-16">
  <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl font-bold mb-8 text-[#2c1a0f]">Tentang Kami</h2>
      <p class="text-lg text-gray-700 max-w-2xl mx-auto mb-6">
          Roemah Rempah Spa adalah tempat terbaik untuk menikmati berbagai perawatan spa dengan bahan alami.
          Kami berkomitmen memberikan pengalaman relaksasi yang tak terlupakan.
      </p>

      <!-- Visi & Misi -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
          <div class="bg-gray-100 p-6 rounded-lg shadow-md">
              <h3 class="text-2xl font-semibold text-[#2c1a0f]">Visi</h3>
              <p class="text-gray-700 mt-2">Menjadi spa terbaik dengan layanan berkualitas yang mengutamakan kesehatan dan kenyamanan pelanggan.</p>
          </div>
          <div class="bg-gray-100 p-6 rounded-lg shadow-md">
              <h3 class="text-2xl font-semibold text-[#2c1a0f]">Misi</h3>
              <ul class="list-disc pl-6 mt-2 text-gray-700">
                  <li>Menggunakan bahan alami untuk setiap perawatan.</li>
                  <li>Memberikan layanan profesional dengan therapist berpengalaman.</li>
                  <li>Mengutamakan kenyamanan dan kepuasan pelanggan.</li>
              </ul>
          </div>
      </div>

      <!-- Gambar Tim -->
      <div class="mt-12">
          <h3 class="text-2xl font-bold mb-6 text-[#2c1a0f]">Tim Kami</h3>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
              <div class="text-center">
                  <img src="/images/pimpinan.jpg" alt="Pimpinan" class="w-40 h-40 object-cover rounded-full mx-auto">
                  <h4 class="mt-4 text-xl font-semibold">Pimpinan</h4>
              </div>
              <div class="text-center">
                  <img src="/images/admin.jpg" alt="Kasir/Admin" class="w-40 h-40 object-cover rounded-full mx-auto">
                  <h4 class="mt-4 text-xl font-semibold">Kasir / Admin</h4>
              </div>
              <div class="text-center">
                  <img src="/images/therapist.jpg" alt="Therapist" class="w-40 h-40 object-cover rounded-full mx-auto">
                  <h4 class="mt-4 text-xl font-semibold">Therapist</h4>
              </div>
          </div>
      </div>
    </section>
      <!-- Peta Lokasi -->
      <section id="layanan" class="py-16 bg-gray-100 scroll-mt-16">
      <div class="mt-12">
          <h3 class="text-2xl font-bold mb-6 text-center text-[#2c1a0f]">Lokasi Kami</h3>
          <div class="w-full h-64 md:h-80">
              <iframe class="w-full h-full rounded-lg shadow-md" 
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8110171170225!2d124.83171111435013!3d1.457562498759256!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7e8c21a7b2933f5b!2sRoemah%20Rempah%20Spa!5e0!3m2!1sen!2sid!4v1649123456789"
                  allowfullscreen="" loading="lazy">
              </iframe>
          </div>
      </div>
  </div>
</section>
  @endsection
</body>
</html>