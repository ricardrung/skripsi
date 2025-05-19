<section id="layanan" class="py-16 bg-gray-100 scroll-mt-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-8 text-[#2c1a0f]">Best Selling</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($bestSellingTreatments as $treatment)
                <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                    @if (!empty($treatment->demo_video_url))
                        @php
                            $url = $treatment->demo_video_url;
                            if (Str::contains($url, 'youtu.be')) {
                                preg_match('/youtu\.be\/([^\?]+)/', $url, $matches);
                                $videoId = $matches[1] ?? '';
                            } elseif (Str::contains($url, 'watch?v=')) {
                                parse_str(parse_url($url, PHP_URL_QUERY), $query);
                                $videoId = $query['v'] ?? '';
                            } else {
                                $videoId = '';
                            }
                            $embedUrl = $videoId ? 'https://www.youtube.com/embed/' . $videoId : '';
                        @endphp

                        @if ($embedUrl)
                            <div class="my-4">
                                <iframe width="100%" height="200" src="{{ $embedUrl }}" frameborder="0"
                                    allowfullscreen class="rounded-lg shadow">
                                </iframe>
                            </div>
                        @endif
                    @endif

                    <div class="p-6">
                        <h3 class="text-2xl font-semibold text-[#2c1a0f]">{{ $treatment->name }}</h3>
                        <p class="text-gray-700 my-2">{{ Str::limit($treatment->description, 60) }}</p>
                        <span class="block text-lg font-bold text-[#8b5a2b]">
                            Rp {{ number_format($treatment->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ url('/kategori/' . $treatment->category->slug) }}"
                            class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">Belum ada treatment best selling.</p>
            @endforelse
        </div>
    </div>
</section>
