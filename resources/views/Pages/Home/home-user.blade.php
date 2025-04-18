@extends('Components.Layout.layout-customer')
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


    <!-- Daftar Layanan -->
    <section id="layanan" class="py-16 bg-gray-100 scroll-mt-16">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-8 text-[#2c1a0f]">Best Selling</h2>
            {{-- <div class="flex justify-center"> --}}
            <div class="grid grid-cols-1 md:grid-cols-2  gap-8">
                @php
                    $paketSpa = [
                        [
                            'nama' => 'Signature Roemah Rempah',
                            'deskripsi' => 'Massage + The Javanese Lulur + Totok Wajah + Masker Wajah + Ear Candling',
                            'harga_normal' => 275000,
                            'harga_happy_hour' => 240000,
                            'gambar' => '/images/massage.jpg',
                        ],
                        [
                            'nama' => 'Awet Enom',
                            'deskripsi' =>
                                'Gold Spa Treatment Massage + Lulur Gold + Totok Wajah + Serum Gold + Masker Wajah',
                            'harga_normal' => 295000,
                            'harga_happy_hour' => 275000,
                            'gambar' => '/images/massage.jpg',
                        ],
                    ];
                @endphp
                <!-- Card 1 -->
                @foreach ($paketSpa as $paket)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <img src="{{ $paket['gambar'] }}" alt="{{ $paket['nama'] }}" class="w-full h-64 object-cover">
                        <div class="p-6">
                            <h3 class="text-2xl font-semibold text-[#2c1a0f]">{{ $paket['nama'] }}</h3>
                            <p class="text-gray-700 my-2">{{ $paket['deskripsi'] }}</p>
                            <!-- Harga Normal -->
                            <span class="block text-lg font-bold text-[#8b5a2b]">Rp
                                {{ number_format($paket['harga_normal'], 0, ',', '.') }}</span>
                            <!-- Info Happy Hour -->
                            <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md">
                                <p class="text-sm font-medium">
                                    <i class="fa-regular fa-clock"></i> <strong>Happy Hour :</strong> Senin - Jumat,
                                    10:00 -
                                    13:00
                                </p>
                                <p class="text-sm font-bold text-[#8b5a2b]">Harga spesial : Rp
                                    {{ number_format($paket['harga_happy_hour'], 0, ',', '.') }}</p>
                            </div>
                            <!-- Tombol Booking -->
                            <button
                                onclick="openModal('{{ $paket['nama'] }}', '{{ $paket['deskripsi'] }}', {{ $paket['harga_normal'] }}, {{ $paket['harga_happy_hour'] }})"
                                class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                Booking
                            </button>
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
                        <!--Judul-->
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
                                <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded"
                                    required onchange="updateHarga()">
                            </div>
                            <!-- Jam -->
                            <div>
                                <label for="jam" class="block font-semibold">Pilih Jam:</label>
                                <select id="jam" name="jam" class="w-full p-2 border rounded" required
                                    onchange="updateHarga()">
                                    <option value="10:00-12:30">10:00 - 12:30</option>
                                    <option value="10:30-13:00">10:30 - 13:00</option>
                                    <option value="11:00-13:30">11:00 - 13:30</option>
                                    <option value="11:30-14:00">11:30 - 14:00</option>
                                    <option value="12:00-14:30">12:00 - 14:30</option>
                                    <option value="12:30-15:00">12:30 - 15:00</option>
                                    <option value="13:00-15:30">13:00 - 15:30</option>
                                    <option value="13:30-16:00">13:30 - 16:00</option>
                                    <option value="14:00-16:30">14:00 - 16:30</option>
                                    <option value="14:30-17:00">14:30 - 17:00</option>
                                    <option value="15:00-17:30">15:00 - 17:30</option>
                                    <option value="15:30-18:00">15:30 - 18:00</option>
                                    <option value="16:00-18:30">16:00 - 18:30</option>
                                    <option value="16:30-19:00">16:30 - 19:00</option>
                                    <option value="17:00-19:30">17:00 - 19:30</option>
                                    <option value="17:30-20:00">17:30 - 20:00</option>
                                    <option value="18:00-20:30">18:00 - 20:30</option>
                                    <option value="18:30-21:00">18:30 - 21:00</option>
                                    <option value="19:00-21:30">19:00 - 21:30</option>
                                    <option value="19:30-22:00">19:30 - 22:00</option>
                                </select>
                            </div>
                            <!-- Harga -->
                            <div>
                                <p class="text-lg font-semibold">Total Harga: <span id="harga"
                                        class="text-green-600">Rp150.000</span></p>
                            </div>
                            <!-- Tombol Submit -->
                            <button type="submit"
                                class="w-full bg-[#8b5a2b] text-white py-2 px-4 rounded-lg hover:bg-[#6b4223] transition">
                                Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
                <!-- JavaScript untuk Modal dan Happy Hour -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setMinDate();
                        updateJam();
                    });

                    function openModal(namaPaket, deskripsiPaket, hargaNormal, hargaHappyHour) {
                        document.getElementById("bookingModal").classList.remove("hidden");

                        // Set paket spa yang dipilih di dalam form
                        document.querySelector("#bookingModal input[name='paket']").value = namaPaket;
                        document.querySelector("#bookingModal input[readonly]").value = namaPaket + " - " + deskripsiPaket;

                        // Simpan harga dalam elemen modal
                        document.getElementById("bookingModal").setAttribute("data-harga-normal", hargaNormal);
                        document.getElementById("bookingModal").setAttribute("data-harga-happy-hour", hargaHappyHour);

                        setMinDate();
                        updateJam();
                        updateHarga(); // Perbarui harga saat modal dibuka
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

                        jamSelect.innerHTML = ""; // Kosongkan opsi jam sebelum diisi ulang

                        const jamOptions = [
                            "10:00-12:30", "10:30-13:00", "11:00-13:30", "11:30-14:00",
                            "12:00-14:30", "12:30-15:00", "13:00-15:30", "13:30-16:00",
                            "14:00-16:30", "14:30-17:00", "15:00-17:30", "15:30-18:00",
                            "16:00-18:30", "16:30-19:00", "17:00-19:30", "17:30-20:00",
                            "18:00-20:30", "18:30-21:00", "19:00-21:30", "19:30-22:00"
                        ];

                        const currentHour = today.getHours();

                        jamOptions.forEach(jam => {
                            const jamMulai = parseInt(jam.split(":")[0]);

                            // Jika hari ini, hanya tampilkan jam yang belum lewat
                            if (isToday && jamMulai <= currentHour) {
                                return;
                            }

                            const option = document.createElement("option");
                            option.value = jam;
                            option.textContent = jam.replace("-", " - ");
                            jamSelect.appendChild(option);
                        });

                        updateHarga();
                    }

                    // Perbarui harga berdasarkan tanggal dan jam yang dipilih
                    function updateHarga() {
                        const hargaText = document.getElementById("harga");

                        // Ambil harga normal dan happy hour dari modal
                        const modal = document.getElementById("bookingModal");
                        const hargaNormal = parseInt(modal.getAttribute("data-harga-normal"));
                        const hargaHappyHour = parseInt(modal.getAttribute("data-harga-happy-hour"));

                        const tanggal = document.getElementById("tanggal").value;
                        const jamSelect = document.getElementById("jam").value;

                        let hargaFinal = hargaNormal;

                        if (isHappyHour(tanggal, jamSelect)) {
                            hargaFinal = hargaHappyHour;
                        }

                        hargaText.textContent = "Rp" + hargaFinal.toLocaleString();
                    }


                    // Cek apakah Happy Hour berlaku (hanya berlaku di jam 13:00 - 14:30)
                    function isHappyHour(tanggal, jamSelect) {
                        if (!tanggal || !jamSelect) return false;

                        const date = new Date(tanggal);
                        const day = date.getDay(); // 1 = Senin, 2 = Selasa, ..., 5 = Jumat

                        const happyHourTimes = [
                            "10:00-12:30", "10:30-13:00", "11:00-13:30", "11:30-14:00",
                            "12:00-14:30", "12:30-15:00", "13:00-15:30"
                        ];

                        return (day >= 1 && day <= 5) && happyHourTimes.includes(jamSelect);
                    }

                    document.getElementById("tanggal").addEventListener("change", function() {
                        updateJam();
                        updateHarga();
                    });

                    document.getElementById("jam").addEventListener("change", updateHarga);
                </script>
            </div>
        </div>
    </section>

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
                    @php
                        $teamMembers = [
                            [
                                'name' => 'Pimpinan',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Kasir / Admin',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 1',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 2',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 3',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 4',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 5',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 6',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 7',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 8',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 9',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 10',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 11',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 12',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 13',
                                'image' => '/images/contoh.png',
                            ],
                            [
                                'name' => 'Therapist 14',
                                'image' => '/images/contoh.png',
                            ],
                        ];
                    @endphp
                    @foreach ($teamMembers as $member)
                        <div class="text-center">
                            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
                                class="w-40 h-40 object-cover rounded-full mx-auto">
                            <h4 class="mt-4 text-xl font-semibold">{{ $member['name'] }}</h4>
                        </div>
                    @endforeach

                </div>
            </div>
    </section>

    <!-- Peta Lokasi -->
    <section id="layanan" class="py-16 bg-gray-100 scroll-mt-16">
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
