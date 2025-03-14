@extends('Components.Layout.layout')

@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#2c1a0f] text-center">Promo</h1>
            <p class="mt-2 text-base md:text-lg text-gray-600 text-center">5x booking dapat 1 voucher gratis treatment
                dibawah
            </p>
        </div>
    </header>

    <section class="py-16 ">
        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 ">
                @php
                    // Misalkan kita mendapatkan jumlah transaksi dari session atau database
                    $jumlahTransaksi = session('jumlah_transaksi', 5); // Ganti dengan logika yang sesuai
                    $paketSpa = [
                        [
                            'nama' => 'Ear Candle',
                            'deskripsi' => 'Ear Candle 15 Menit',
                            'harga' => 75000,
                            'durasi' => 15,
                            'gambar' => '/images/massage.jpg',
                        ],
                        [
                            'nama' => 'Totok Wajah',
                            'deskripsi' => 'Totok Wajah 30 Menit',
                            'harga' => 55000,
                            'durasi' => 30,
                            'gambar' => '/images/massage.jpg',
                        ],
                        [
                            'nama' => 'Reflexology',
                            'deskripsi' => 'Reflexology 60 Menit',
                            'harga' => 130000,
                            'durasi' => 60,
                            'gambar' => '/images/massage.jpg',
                        ],
                        [
                            'nama' => 'Full Body Massage',
                            'deskripsi' => 'Full Body Massage 60 Menit',
                            'harga' => 130000,
                            'durasi' => 60,
                            'gambar' => '/images/massage.jpg',
                        ],
                        [
                            'nama' => 'Full Body Massage + face mask',
                            'deskripsi' => 'Full Body Massage 60 Menit + face mask',
                            'harga' => 130000,
                            'durasi' => 60,
                            'gambar' => '/images/massage.jpg',
                        ],
                    ];
                @endphp

                @if ($jumlahTransaksi >= 5 && $jumlahTransaksi % 5 == 0)
                    <!-- Card Paket Spa -->
                    @foreach ($paketSpa as $paket)
                        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                            <img src="{{ $paket['gambar'] }}" alt="{{ $paket['nama'] }}" class="w-full h-48 object-cover">
                            <div class="p-6">
                                <h3 class="text-2xl font-semibold text-[#2c1a0f]">{{ $paket['nama'] }}</h3>
                                <p class="text-gray-700 my-2">{{ $paket['deskripsi'] }}</p>
                                <span class="block text-lg font-bold text-[#8b5a2b]">Gratis</span>
                                <button
                                    onclick="openModal('{{ $paket['nama'] }}', '{{ $paket['deskripsi'] }}', 0, {{ $paket['durasi'] }})"
                                    class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                    Pilih Treatment Gratis
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 flex items-center justify-center">
                        <p class="text-gray-700 text-center">Anda belum memenuhi syarat untuk mendapatkan treatment gratis.
                            Lakukan booking
                            sebanyak 5 kali untuk mendapatkan voucher gratis.</p>
                    </div>
                @endif

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

                            <!-- Paket Spa -->
                            <div>
                                <label class="block font-semibold">Paket Spa yang Dipilih:</label>
                                <input type="text" class="w-full p-2 border rounded bg-gray-100 text-gray-700" readonly>
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
                                    <!-- Opsi jam akan diisi oleh JavaScript -->
                                </select>
                            </div>

                            <!-- Harga -->
                            <div>
                                <p class="text-lg font-semibold">Total Harga: <span id="harga" class="text-green-600">Rp
                                        0</span></p>
                            </div>

                            <!-- Tombol Submit -->
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

                    function openModal(namaPaket, deskripsiPaket, harga, durasi) {
                        document.getElementById("bookingModal").classList.remove("hidden");

                        // Set paket spa yang dipilih di dalam form
                        document.querySelector("#bookingModal input[name='paket']").value = namaPaket;
                        document.querySelector("#bookingModal input[readonly]").value = namaPaket + " - " + deskripsiPaket;

                        // Simpan harga dan durasi dalam elemen modal
                        document.getElementById("bookingModal").setAttribute("data-harga", harga);
                        document.getElementById("bookingModal").setAttribute("data-durasi", durasi); // Menyimpan durasi

                        setMinDate();
                        updateJam(durasi); // Pastikan durasi sudah terisi
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
                    function updateJam(durasi) {
                        const tanggal = document.getElementById("tanggal").value;
                        const jamSelect = document.getElementById("jam");

                        if (!tanggal) return;

                        const dateSelected = new Date(tanggal);
                        const today = new Date();
                        const isToday = dateSelected.toDateString() === today.toDateString();

                        const currentHour = today.getHours();
                        const currentMinutes = today.getMinutes();

                        jamSelect.innerHTML = ""; // Kosongkan opsi jam sebelum diisi ulang

                        // Menentukan jam mulai berdasarkan durasi
                        const jamMulaiOptions = [
                            "10:00", "10:30", "11:00", "11:30",
                            "12:00", "12:30", "13:00", "13:30",
                            "14:00", "14:30", "15:00", "15:30",
                            "16:00", "16:30", "17:00", "17:30",
                            "18:00", "18:30", "19:00", "19:30",
                            "20:00", "20:30", "21:00", "21:30",
                            "22:00" // Tambahkan opsi hingga 22:00
                        ];

                        jamMulaiOptions.forEach(jamMulai => {
                            const [jamHour, jamMinute] = jamMulai.split(":").map(Number); // Pisahkan jam dan menit

                            // Hitung jam selesai
                            let jamSelesai = new Date();
                            jamSelesai.setHours(jamHour);
                            jamSelesai.setMinutes(jamMinute + durasi);

                            // Cek apakah jam selesai melebihi 22:00
                            if (jamSelesai.getHours() > 22 || (jamSelesai.getHours() === 22 && jamSelesai.getMinutes() > 0)) {
                                return; // Jangan tambahkan opsi jika jam selesai melebihi 22:00
                            }

                            // Jika hari ini, sembunyikan waktu yang sudah lewat
                            if (isToday && (jamHour < currentHour || (jamHour === currentHour && jamMinute < currentMinutes))) {
                                return;
                            }

                            const jamSelesaiFormatted = jamSelesai.getHours().toString().padStart(2, "0") + ":" +
                                jamSelesai.getMinutes().toString().padStart(2, "0");

                            // Tambahkan opsi ke dalam select
                            const option = document.createElement("option");
                            option.value = jamMulai + "-" + jamSelesaiFormatted;
                            option.textContent = jamMulai + " - " + jamSelesaiFormatted;
                            jamSelect.appendChild(option);
                        });
                    }

                    function updateHarga() {
                        const hargaText = document.getElementById("harga");

                        const modal = document.getElementById("bookingModal");
                        const hargaNormal = parseInt(modal.getAttribute("data-harga"));
                        // Jika harga adalah 0, tampilkan "Gratis"
                        if (hargaNormal === 0) {
                            hargaText.textContent = "Gratis";
                        } else {
                            hargaText.textContent = "Rp " + hargaNormal.toLocaleString();
                        }


                        hargaText.textContent = "Rp" + hargaNormal.toLocaleString();
                    }

                    document.getElementById("tanggal").addEventListener("change", function() {
                        const durasi = parseInt(document.getElementById("bookingModal").getAttribute("data-durasi"));
                        updateJam(durasi);
                    });
                </script>
            </div>
        </div>
    </section>
@endsection
