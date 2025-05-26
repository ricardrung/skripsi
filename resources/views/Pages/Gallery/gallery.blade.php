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

                @forelse ($rooms as $room)
                    <div
                        class="group relative overflow-hidden rounded-lg shadow-lg transition-transform duration-300 hover:scale-105">
                        <a href="{{ asset('storage/' . $room->image_path) }}" data-lightbox="gallery"
                            data-title="{{ $room->room_name }}: {{ $room->description }}">
                            <img src="{{ asset('storage/' . $room->image_path) }}" alt="{{ $room->room_name }}"
                                class="w-full h-64 object-cover">
                        </a>
                        <div
                            class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                            <h2 class="text-white text-lg font-semibold">{{ $room->room_name }}</h2>
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold mt-2">{{ $room->room_name }}</h3>
                            <p class="text-gray-600">{{ $room->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 col-span-4">Belum ada ruangan yang tersedia di galeri.</p>
                @endforelse

            </div>
        </div>
    </section>
@endsection
