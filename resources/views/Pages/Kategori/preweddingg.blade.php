@extends('Components.Layout.layout')
@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold tracking-tight text-[#2c1a0f]">Pre Wedding</h1>
        </div>
    </header>
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @php
                    $paketSpa = [
                        [
                            'nama' => 'For Bride',
                            'deskripsi' =>
                                'Paket Gold Spa + Paket Putih Ayu + Paket Kendedes + Rikmo Ayu + Body Bleaching + Ear Candling + Ratus Intimacy',
                            'harga' => 1150000,
                            'gambar' => '/images/massage.jpg',
                            'paketList' => [
                                'Paket Gold Spa',
                                'Paket Putih Ayu',
                                'Paket Kendedes',
                                'Rikmo Ayu',
                                'Body Bleaching',
                                'Ear Candling',
                                'Ratus Intimacy',
                            ],
                        ],
                        [
                            'nama' => 'For Groom',
                            'deskripsi' =>
                                'Paket Gold Spa + Paket Putih Ayu + Paket Rhama + Rikmo Ayu + Reflexology + Ear Candling',
                            'harga' => 1050000,
                            'gambar' => '/images/massage.jpg',
                            'paketList' => [
                                'Paket Gold Spa',
                                'Paket Putih Ayu',
                                'Paket Rhama',
                                'Rikmo Ayu',
                                'Reflexology',
                                'Ear Candling',
                            ],
                        ],
                    ];
                @endphp

                @foreach ($paketSpa as $paket)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ $paket['gambar'] }}" alt="{{ $paket['nama'] }}" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold text-[#2c1a0f]">{{ $paket['nama'] }}</h3>
                            <p class="text-gray-700 my-2">{{ $paket['deskripsi'] }}</p>
                            <span class="block text-lg font-bold text-[#8b5a2b]">Rp
                                {{ number_format($paket['harga'], 0, ',', '.') }}</span>
                            <button onclick="openModal('{{ $paket['nama'] }}', {{ json_encode($paket['paketList']) }})"
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
                        <!-- Tombol Close -->
                        <button onclick="closeModal()"
                            class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl">&times;</button>

                        <h2 class="text-2xl font-semibold text-[#2c1a0f] mb-4 text-center">Form Pemesanan</h2>

                        <!-- Form -->
                        <form>
                            <!-- Nama -->
                            <div>
                                <label for="nama" class="block font-semibold">Nama:</label>
                                <input type="text" id="nama" name="nama" class="w-full p-2 border rounded"
                                    required>
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label for="telepon" class="block font-semibold">Nomor Telepon:</label>
                                <input type="tel" id="telepon" name="telepon" class="w-full p-2 border rounded"
                                    required>
                            </div>

                            <!-- Paket Spa yang Dipilih -->
                            <div>
                                <label class="block font-semibold">Paket Spa yang Dipilih:</label>
                                <input type="text" id="selectedPaket"
                                    class="w-full p-2 border rounded bg-gray-100 text-gray-700" readonly>
                            </div>

                            <!-- Input untuk setiap paket -->
                            <div id="paketOptions"></div>

                            <!-- Harga -->
                            <div>
                                <p class="text-lg font-semibold">Total Harga: <span id="harga"
                                        class="text-green-600">Rp1.050.000</span></p>
                            </div>

                            <!-- Tombol Submit -->
                            <button type="submit"
                                class="w-full bg-[#8b5a2b] text-white py-2 px-4 rounded-lg hover:bg-[#6b4223] transition">
                                Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>

                <!-- JavaScript -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setMinDate();
                    });

                    function openModal(paketNama, paketList) {
                        document.getElementById("bookingModal").classList.remove("hidden");

                        // Set nama paket di input readonly
                        document.getElementById("selectedPaket").value = paketNama + " - " + paketList.join(" + ");

                        // Bersihkan dan tampilkan paket yang sesuai
                        let paketOptionsDiv = document.getElementById("paketOptions");
                        paketOptionsDiv.innerHTML = "";

                        paketList.forEach(paket => {
                            let paketId = paket.replace(/\s+/g, '-').toLowerCase();

                            let paketHtml = `
                                <div class="border p-4 rounded-lg my-2">
                                    <label class="block font-semibold">${paket}</label>

                                    <!-- Tanggal -->
                                    <div>
                                        <label for="tanggal_${paketId}" class="block font-semibold">Tanggal:</label>
                                        <input type="date" id="tanggal_${paketId}" name="tanggal[${paket}]" class="w-full p-2 border rounded"
                                            onchange="updateJam('${paketId}', 90)">
                                    </div>

                                    <!-- Jam -->
                                    <div>
                                        <label for="jam_${paketId}" class="block font-semibold">Pilih Jam:</label>
                                        <select id="jam_${paketId}" name="jam[${paket}]" class="w-full p-2 border rounded">
                                        </select>
                                    </div>
                                </div>
                            `;

                            paketOptionsDiv.innerHTML += paketHtml;
                        });

                        setMinDate();
                    }

                    function closeModal() {
                        document.getElementById("bookingModal").classList.add("hidden");
                    }

                    function setMinDate() {
                        const today = new Date();
                        const minDate = today.toISOString().split("T")[0];
                        document.querySelectorAll("input[type='date']").forEach(input => {
                            input.setAttribute("min", minDate);
                        });
                    }

                    function updateJam(paketId, durasi) {
                        const tanggal = document.getElementById("tanggal_" + paketId).value;
                        const jamSelect = document.getElementById("jam_" + paketId);

                        if (!tanggal) return;

                        jamSelect.innerHTML = "";

                        const jamMulaiOptions = [
                            "10:00", "10:30", "11:00", "11:30",
                            "12:00", "12:30", "13:00", "13:30",
                            "14:00", "14:30", "15:00", "15:30",
                            "16:00", "16:30", "17:00", "17:30",
                            "18:00", "18:30", "19:00", "19:30",
                            "20:00", "20:30"
                        ];

                        jamMulaiOptions.forEach(jamMulai => {
                            let option = document.createElement("option");
                            option.value = jamMulai;
                            option.textContent = jamMulai;
                            jamSelect.appendChild(option);
                        });
                    }
                </script>

            </div>
        </div>
    </section>
@endsection
