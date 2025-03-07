@extends('Components.Layout.layout')

@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-5xl font-bold tracking-tight text-[#2c1a0f] text-center">Gallery</h1>
            <p class="mt-2 text-lg text-gray-600 text-center">Jelajahi keindahan dan kenyamanan ruangan kami.</p>
        </div>
    </header>

    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @php
                    $rooms = [
                        [
                            'image' => '/images/massage.jpg',
                            'title' => 'Ruang Terapi Aromaterapi',
                            'description' => 'Ruang nyaman dengan aroma terapi untuk relaksasi maksimal.',
                        ],
                        [
                            'image' => '/images/massage.jpg',
                            'title' => 'Ruang Terapi Pijat',
                            'description' => 'Ruang yang dirancang khusus untuk pengalaman pijat yang menyegarkan.',
                        ],
                        [
                            'image' => '/images/massage.jpg',
                            'title' => 'Ruang Mandi Rempah',
                            'description' => 'Ruang mandi dengan rempah alami untuk pengalaman spa yang menyegarkan.',
                        ],
                        [
                            'image' => '/images/massage.jpg',
                            'title' => 'Ruang Relaksasi',
                            'description' => 'Ruang tenang untuk bersantai setelah perawatan.',
                        ],
                        // Tambahkan lebih banyak ruangan sesuai kebutuhan
                    ];
                @endphp

                @foreach ($rooms as $room)
                    <div
                        class="group relative overflow-hidden rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                        <a href="{{ $room['image'] }}" data-lightbox="gallery"
                            data-title="{{ $room['title'] }}: {{ $room['description'] }}">
                            <img src="{{ $room['image'] }}" alt="{{ $room['title'] }}" class="w-full h-64 object-cover">
                        </a>
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <h2 class="text-white text-lg font-semibold">{{ $room['title'] }}</h2>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mt-2">{{ $room['title'] }}</h3>
                            <p class="text-gray-600">{{ $room['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-[#2c1a0f] mb-6">Testimoni Pelanggan</h2>
            <div class="space-y-4">
                <blockquote class="border-l-4 border-[#8b5a2b] pl-4 italic">
                    🌟🌟🌟🌟🌟 "Pengalaman yang sangat memuaskan! Ruangannya nyaman dan bersih." – Anna
                </blockquote>
                <blockquote class="border-l-4 border-[#8b5a2b] pl-4 italic">
                    🌟🌟🌟🌟🌟 "Sangat merekomendasikan! Layanan yang luar biasa." – Budi
                </blockquote>
                <blockquote class="border-l-4 border-[#8b5a2b] pl-4 italic">
                    🌟🌟🌟🌟🌟 "Ruangannya sangat menenangkan, saya merasa segar setelah perawatan." – Clara
                </blockquote>
            </div>
        </div>
    </section> --}}
@endsection
