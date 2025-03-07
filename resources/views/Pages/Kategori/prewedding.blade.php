@extends('Components.Layout.layout')
@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6 ">
            <h1 class="text-3xl font-bold tracking-tight text-[#2c1a0f]">Pre Wedding</h1>
        </div>
    </header>
    <section class="py-16 ">
        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 ">
                @php
                    $paketSpa = [
                        [
                            'nama' => 'For Bride',
                            'deskripsi' =>
                                'Paket Gold Spa + Paket Putih Ayu + Paket Kendedes + Rikmo Ayu + Body Bleaching + Ear Candling + Ratus Intimacy',
                            'harga' => 1150000,
                            'gambar' => '/images/massage.jpg',
                        ],
                        [
                            'nama' => 'For Groom',
                            'deskripsi' =>
                                'Paket Gold Spa + Paket Putih Ayu + Paket Rhama + Rikmo Ayu + Reflexology + Ear Candling',
                            'harga' => 1050000,
                            'gambar' => '/images/massage.jpg',
                        ],
                    ];
                    $therapists = ['Therapist A', 'Therapist B', 'Therapist C', 'Therapist D', 'Therapist E'];
                @endphp
                <!-- Card Paket Spa -->
                @foreach ($paketSpa as $paket)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ $paket['gambar'] }}" alt="{{ $paket['nama'] }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold text-[#2c1a0f]">{{ $paket['nama'] }}</h3>
                            <p class="text-gray-700 my-2">{{ $paket['deskripsi'] }}</p>
                            <span class="block text-lg font-bold text-[#8b5a2b]">Rp
                                {{ number_format($paket['harga'], 0, ',', '.') }}</span>
                            <button
                                onclick="openModal('{{ $paket['nama'] }}', '{{ $paket['deskripsi'] }}', {{ $paket['harga'] }})"
                                class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                Booking
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Form Booking Modal -->
                <div id="bookingModal"
                    class="fixed inset-0 flex items-center justify-center bg-opacity-50 hidden z-50 overflow-y-auto">
                    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
                        <button onclick="closeModal()"
                            class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl">&times;</button>

                        <h2 class="text-2xl font-semibold text-[#2c1a0f] mb-4 text-center">Form Pemesanan</h2>

                        <form>
                            <div>
                                <label for="nama" class="block font-semibold">Nama:</label>
                                <input type="text" id="nama" name="nama" class="w-full p-2 border rounded"
                                    required>
                            </div>

                            <div>
                                <label for="telepon" class="block font-semibold">Nomor Telepon:</label>
                                <input type="tel" id="telepon" name="telepon" class="w-full p-2 border rounded"
                                    required>
                            </div>

                            <div>
                                <label class="block font-semibold">Paket Spa yang Dipilih:</label>
                                <input type="text" id="selectedPaket"
                                    class="w-full p-2 border rounded bg-gray-100 text-gray-700" readonly>
                            </div>

                            @php
                                $paketList = [
                                    'Paket Gold Spa' => 150, // 2.5 jam
                                    'Paket Putih Ayu' => 150, // 2.5 jam
                                    'Paket Kendedes' => 90, // 1.5 jam
                                    'Paket Rhama' => 90, // 1.5 jam
                                    'Paket Rikmo Ayu' => 90, // 1.5 jam
                                    'Body Bleaching' => 45, // 45 menit
                                    'Reflexology' => 90, // 1.5 jam
                                    'Ear Candling' => 15, // 15 menit
                                ];
                            @endphp



                            @foreach ($paketList as $paket => $durasi)
                                <div class="border p-4 rounded-lg my-2 paket-item hidden" data-paket="{{ $paket }}">
                                    <label class="block font-semibold">{{ $paket }}</label>

                                    <div>
                                        <label for="tanggal_{{ Str::slug($paket) }}"
                                            class="block font-semibold">Tanggal:</label>
                                        <input type="date" id="tanggal_{{ Str::slug($paket) }}"
                                            name="tanggal[{{ $paket }}]" class="w-full p-2 border rounded"
                                            onchange="updateJam('{{ Str::slug($paket) }}', {{ $durasi }})"required>
                                    </div>

                                    <div>
                                        <label for="jam_{{ Str::slug($paket) }}" class="block font-semibold">Pilih
                                            Jam:</label>
                                        <select id="jam_{{ Str::slug($paket) }}" name="jam[{{ $paket }}]"
                                            class="w-full p-2 border rounded">
                                            <!-- Opsi jam akan diisi oleh JavaScript -->
                                        </select>
                                    </div>

                                    <!-- Pemilihan Therapist Per Paket -->
                                    <div>
                                        <label for="therapist_{{ Str::slug($paket) }}" class="block font-semibold">Pilih
                                            Therapist:</label>
                                        <select id="therapist_{{ Str::slug($paket) }}"
                                            name="therapist[{{ $paket }}]" class="w-full p-2 border rounded">
                                            <option value="auto">Pilihkan untuk saya</option>
                                            @foreach ($therapists as $therapist)
                                                <option value="{{ $therapist }}">{{ $therapist }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Ratus Intimacy hanya untuk For Bride -->
                            <div id="ratusIntimacy" class="border p-4 rounded-lg my-2 bg-gray-100 hidden">
                                <label class="block font-semibold">Ratus Intimacy</label>
                            </div>

                            <div>
                                <p class="text-lg font-semibold">Total Harga: <span id="harga"
                                        class="text-green-600">Rp1.050.000</span></p>
                            </div>

                            <button type="submit"
                                class="w-full bg-[#8b5a2b] text-white py-2 px-4 rounded-lg hover:bg-[#6b4223] transition">
                                Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setMinDate();
                    });

                    function openModal(nama, deskripsi, harga) {
                        document.getElementById("selectedPaket").value = nama + ' - ' + deskripsi;

                        // Tampilkan paket yang sesuai
                        const paketItems = document.querySelectorAll('.paket-item');
                        paketItems.forEach(item => {
                            item.classList.add('hidden'); // Sembunyikan semua paket
                        });

                        // Tampilkan paket yang relevan
                        if (nama === 'For Bride') {
                            const bridePackages = ['Paket Gold Spa', 'Paket Putih Ayu', 'Paket Kendedes', 'Rikmo Ayu', 'Body Bleaching',
                                'Ear Candling'
                            ];
                            bridePackages.forEach(paket => {
                                const paketItem = document.querySelector(`.paket-item[data-paket="${paket}"]`);
                                if (paketItem) {
                                    paketItem.classList.remove('hidden'); // Tampilkan paket yang sesuai
                                }
                            });
                            document.getElementById("ratusIntimacy").classList.remove('hidden'); // Tampilkan Ratus Intimacy
                        } else if (nama === 'For Groom') {
                            const groomPackages = ['Paket Gold Spa', 'Paket Putih Ayu', 'Paket Rhama', 'Rikmo Ayu', 'Reflexology',
                                'Ear Candling'
                            ];
                            groomPackages.forEach(paket => {
                                const paketItem = document.querySelector(`.paket-item[data-paket="${paket}"]`);
                                if (paketItem) {
                                    paketItem.classList.remove('hidden'); // Tampilkan paket yang sesuai
                                }
                            });
                            document.getElementById("ratusIntimacy").classList.add('hidden'); // Sembunyikan Ratus Intimacy
                        }

                        document.getElementById("bookingModal").classList.remove("hidden");
                        setMinDate();
                        document.getElementById("harga").textContent = 'Rp' + harga.toLocaleString();
                    }

                    function closeModal() {
                        document.getElementById("bookingModal").classList.add("hidden");
                    }

                    function setMinDate() {
                        const today = new Date();
                        const minDate = today.toISOString().split("T")[0]; // Format YYYY-MM-DD
                        document.querySelectorAll("input[type='date']").forEach(input => {
                            input.setAttribute("min", minDate);
                        });
                    }

                    function updateJam(paketId, durasi) {
                        const tanggal = document.getElementById("tanggal_" + paketId).value;
                        const jamSelect = document.getElementById("jam_" + paketId);

                        if (!tanggal) return;

                        const dateSelected = new Date(tanggal);
                        const today = new Date();
                        const isToday = dateSelected.toDateString() === today.toDateString();

                        jamSelect.innerHTML = ""; // Kosongkan opsi jam sebelum diisi ulang

                        const jamOptions = [];

                        // Menentukan jam mulai berdasarkan durasi
                        if (durasi === 150) { // 2.5 jam
                            jamOptions.push("10:00-12:30", "10:30-13:00", "11:00-13:30", "11:30-14:00",
                                "12:00-14:30", "12:30-15:00", "13:00-15:30", "13:30-16:00",
                                "14:00-16:30", "14:30-17:00", "15:00-17:30", "15:30-18:00",
                                "16:00-18:30", "16:30-19:00", "17:00-19:30", "17:30-20:00",
                                "18:00-20:30", "18:30-21:00", "19:00-21:30", "19:30-22:00");
                        } else if (durasi === 90) { // 1.5 jam
                            jamOptions.push("10:00-11:30", "10:30-12:00", "11:00-12:30", "11:30-13:00",
                                "12:00-13:30", "12:30-14:00", "13:00-14:30", "13:30-15:00",
                                "14:00-15:30", "14:30-16:00", "15:00-16:30", "15:30-17:00",
                                "16:00-17:30", "16:30-18:00", "17:00-18:30", "17:30-19:00",
                                "18:00-19:30", "18:30-20:00", "19:00-20:30", "19:30-21:00",
                                "20:00-21:30", "20:30-22:00");
                        } else if (durasi === 45) { // 45 menit
                            jamOptions.push("10:00-10:45", "10:30-11:15", "11:00-11:45", "11:30-12:15",
                                "12:00-12:45", "12:30-13:15", "13:00-13:45", "13:30-14:15",
                                "14:00-14:45", "14:30-15:15", "15:00-15:45", "15:30-16:15",
                                "16:00-16:45", "16:30-17:15", "17:00-17:45", "17:30-18:15",
                                "18:00-18:45", "18:30-19:15", "19:00-19:45", "19:30-20:15",
                                "20:00-20:45", "20:30-21:15", "21:00-21:45");
                        } else if (durasi === 15) { // 15 menit
                            jamOptions.push("10:00-10:15", "10:30-10:45", "11:00-11:15", "11:30-11:45",
                                "12:00-12:15", "12:30-12:45", "13:00-13:15", "13:30-13:45",
                                "14:00-14:15", "14:30-14:45", "15:00-15:15", "15:30-15:45",
                                "16:00-16:15", "16:30-16:45", "17:00-17:15", "17:30-17:45",
                                "18:00-18:15", "18:30-18:45", "19:00-19:15", "19:30-19:45",
                                "20:00-20:15", "20:30-20:45", "21:00-21:15");
                        }

                        const currentHour = today.getHours();
                        const currentMinutes = today.getMinutes();

                        jamOptions.forEach(jam => {
                            const jamMulai = parseInt(jam.split(":")[0]);
                            const jamSelesai = parseInt(jam.split("-")[1].split(":")[0]);

                            // Jika hari ini, hanya tampilkan jam yang belum lewat
                            if (isToday && (jamMulai < currentHour || (jamMulai === currentHour && jamSelesai <=
                                    currentMinutes))) {
                                return;
                            }

                            const option = document.createElement("option");
                            option.value = jam;
                            option.textContent = jam.replace("-", " - ");
                            jamSelect.appendChild(option);
                        });
                    }
                </script>

            </div>
        </div>
    </section>
@endsection
