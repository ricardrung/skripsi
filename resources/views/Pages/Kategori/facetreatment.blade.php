    @extends('Components.Layout.layout')
    @section('content')
        <header class="bg-white shadow-sm pt-24">
            <div class="container mx-auto px-4 py-6 ">
                <h1 class="text-3xl font-bold tracking-tight text-[#2c1a0f]">Face Treatment</h1>
            </div>
        </header>
        <section class="py-16 ">
            <div class="container mx-auto px-4 text-center">
                {{-- <h2 class="text-3xl font-bold mb-8 text-[#2c1a0f]">Perawatan Wajah</h2> --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 ">
                    @php
                        $paketSpa = [
                            [
                                'nama' => 'Awet Enom',
                                'deskripsi' => 'Totok Wajah + Serum + Masker Wajah',
                                'harga' => 105000,
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
                                <span
                                    class="block text-lg font-bold text-[#8b5a2b]">{{ number_format($paket['harga'], 0, ',', '.') }}</span>
                                @auth
                                    <button
                                        onclick="openModal('{{ $paket['nama'] }}', '{{ $paket['deskripsi'] }}', {{ $paket['harga'] }})"
                                        class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                        Booking
                                    </button>
                                @else
                                    <!-- Kalau user belum login -->
                                    <a href="{{ route('register') }}"
                                        class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                        Booking
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach

                    <!-- Form Booking Modal -->
                    <div id="bookingModal"
                        class="fixed inset-0 flex items-center justify-center  bg-opacity-50 hidden z-50 overflow-y-auto">
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

                                <!-- Paket Spa -->
                                <div>
                                    <label class="block font-semibold">Paket Spa yang Dipilih:</label>
                                    <input type="text" class="w-full p-2 border rounded bg-gray-100 text-gray-700"
                                        readonly>
                                    <input type="hidden" id="paket" name="paket">
                                </div>


                                <!-- Tanggal -->
                                <div>
                                    <label for="tanggal" class="block font-semibold">Tanggal:</label>
                                    <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded">
                                </div>

                                <!-- Jam -->
                                <div>
                                    <label for="jam" class="block font-semibold">Pilih Jam:</label>
                                    <select id="jam" name="jam" class="w-full p-2 border rounded">
                                        <option value="10:00-11:00">10:00 - 11:00</option>
                                        <option value="10:30-11:30">10:30 - 11:30</option>
                                        <option value="11:00-12:00">11:00 - 12:00</option>
                                        <option value="11:30-12:30">11:30 - 12:30</option>
                                        <option value="12:00-13:00">12:00 - 13:00</option>
                                        <option value="12:30-13:30">12:30 - 13:30</option>
                                        <option value="13:00-14:00">13:00 - 14:00</option>
                                        <option value="13:30-14:30">13:30 - 14:30</option>
                                        <option value="14:00-15:00">14:00 - 15:00</option>
                                        <option value="14:30-15:30">14:30 - 15:30</option>
                                        <option value="15:00-16:00">15:00 - 16:00</option>
                                        <option value="15:30-16:30">15:30 - 16:30</option>
                                        <option value="16:00-17:00">16:00 - 17:00</option>
                                        <option value="16:30-17:30">16:30 - 17:30</option>
                                        <option value="17:00-18:00">17:00 - 18:00</option>
                                        <option value="17:30-18:30">17:30 - 18:30</option>
                                        <option value="18:00-19:00">18:00 - 19:00</option>
                                        <option value="18:30-19:30">18:30 - 19:30</option>
                                        <option value="19:00-20:00">19:00 - 20:00</option>
                                        <option value="19:30-20:30">19:30 - 20:30</option>
                                        <option value="20:00-21:00">20:00 - 21:00</option>
                                        <option value="20:30-21:30">20:30 - 21:30</option>
                                        <option value="21:00-22:00">21:00 - 22:00</option>
                                    </select>
                                </div>

                                <!-- Pemilihan Therapist -->
                                <div>
                                    <label for="therapist" class="block font-semibold">Pilih Therapist (Opsional):</label>
                                    <select id="therapist" name="therapist" class="w-full p-2 border rounded">
                                        <option value="auto">Pilihkan untuk saya</option>
                                        @foreach ($therapists as $therapist)
                                            <option value="{{ $therapist }}">{{ $therapist }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Harga -->
                                <div>
                                    <p class="text-lg font-semibold">Total Harga: <span id="harga"
                                            class="text-green-600">Rp 0</span></p>
                                </div>

                                <!-- Tombol Submit -->
                                <button type="submit"
                                    class="w-full bg-[#8b5a2b] text-white py-2 px-4 rounded-lg hover:bg-[#6b4223] transition">
                                    Pesan Sekarang
                                </button>
                            </form>
                        </div>
                    </div>


                    <!-- JavaScript untuk Modal -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            setMinDate();
                            updateJam();
                        });

                        function openModal(namaPaket, deskripsiPaket, harga) {
                            document.getElementById("bookingModal").classList.remove("hidden");

                            // Set paket spa yang dipilih di dalam form
                            document.querySelector("#bookingModal input[name='paket']").value = namaPaket;
                            document.querySelector("#bookingModal input[readonly]").value = namaPaket + " - " + deskripsiPaket;

                            // Simpan harga dalam elemen modal
                            document.getElementById("bookingModal").setAttribute("data-harga", harga);

                            setMinDate();
                            updateJam();
                            updateHarga();
                        }

                        function closeModal() {
                            document.getElementById("bookingModal").classList.add("hidden");
                        }

                        // Atur minimal tanggal agar tidak bisa memilih hari yang sudah lewat
                        function setMinDate() {
                            const today = new Date();
                            const minDate = today.toISOString().split("T")[0]; // Format YYYY-MM-DD
                            document.getElementById("tanggal").setAttribute("min", minDate);
                        }

                        // Perbarui pilihan jam sesuai tanggal yang dipilih
                        function updateJam() {
                            const tanggal = document.getElementById("tanggal").value;
                            const jamSelect = document.getElementById("jam");

                            if (!tanggal) return;

                            const dateSelected = new Date(tanggal);
                            const today = new Date();
                            const isToday = dateSelected.toDateString() === today.toDateString();

                            const currentHour = today.getHours();
                            const currentMinutes = today.getMinutes();

                            jamSelect.innerHTML = ""; // Kosongkan opsi jam sebelum diisi ulang

                            const jamOptions = [
                                "10:00-11:00", "10:30-11:30", "11:00-12:00", "11:30-12:30",
                                "12:00-13:00", "12:30-13:30", "13:00-14:00", "13:30-14:30",
                                "14:00-15:00", "14:30-15:30", "15:00-16:00", "15:30-16:30",
                                "16:00-17:00", "16:30-17:30", "17:00-18:00", "17:30-18:30",
                                "18:00-19:00", "18:30-19:30", "19:00-20:00", "19:30-20:30",
                                "20:00-21:00", "20:30-21:30", "21:00-22:00"
                            ];

                            jamOptions.forEach(jam => {
                                const jamMulai = jam.split("-")[0]; // Ambil waktu mulai, misal "10:30"
                                const [jamHour, jamMinute] = jamMulai.split(":").map(Number); // Pisahkan jam dan menit

                                // Jika hari ini, sembunyikan waktu yang sudah lewat
                                if (isToday && (jamHour < currentHour || (jamHour === currentHour && jamMinute < currentMinutes))) {
                                    return;
                                }

                                const option = document.createElement("option");
                                option.value = jam;
                                option.textContent = jam.replace("-", " - ");
                                jamSelect.appendChild(option);
                            });
                        }

                        function updateHarga() {
                            const hargaText = document.getElementById("harga");

                            const modal = document.getElementById("bookingModal");
                            const hargaNormal = parseInt(modal.getAttribute("data-harga"));

                            hargaText.textContent = "Rp" + hargaNormal.toLocaleString();
                        }

                        document.getElementById("tanggal").addEventListener("change", updateJam);
                    </script>
                </div>
            </div>
        </section>
    @endsection
