@extends('Components.Layout.layout')
@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6 ">
            <h1 class="text-3xl font-bold tracking-tight text-[#2c1a0f]">Treatment Packages</h1>
        </div>
    </header>
    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Card Paket Spa Signature Package -->
                @foreach ($treatments as $treatment)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        @if (!empty($treatment->demo_video_url))
                            @php
                                $url = $treatment->demo_video_url;

                                if (Str::contains($url, 'youtu.be')) {
                                    // Untuk link short youtu.be
                                    preg_match('/youtu\.be\/([^\?]+)/', $url, $matches);
                                    $videoId = $matches[1] ?? '';
                                } elseif (Str::contains($url, 'watch?v=')) {
                                    // Untuk link youtube.com/watch?v=
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
                            <p class="text-gray-700 my-2">{{ $treatment->description }}</p>

                            <span class="block text-lg font-bold text-[#8b5a2b]">Rp
                                {{ number_format($treatment->price, 0, ',', '.') }}</span>

                            @if ($treatment->happy_hour_price)
                                <div class="mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md">
                                    <p class="text-sm font-medium">
                                        <i class="fa-regular fa-clock"></i> <strong>Happy Hour :</strong> Senin - Jumat,
                                        10:00 - 13:00
                                    </p>
                                    <p class="text-sm font-bold text-[#8b5a2b]">Rp
                                        {{ number_format($treatment->happy_hour_price, 0, ',', '.') }}</p>
                                </div>
                            @endif

                            @auth
                                <button
                                    onclick="openModal('{{ $treatment->name }}', '{{ $treatment->description }}', {{ $treatment->price }}, {{ $treatment->happy_hour_price ?? 0 }}, {{ $treatment->id }})"
                                    class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                    Booking
                                </button>
                            @else
                                <a href="{{ route('register') }}"
                                    class="mt-4 inline-block bg-[#8b5a2b] text-white px-4 py-2 rounded-lg hover:bg-[#6b4223] transition">
                                    Booking
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforeach

                @auth
                    <!-- Form Booking Modal -->
                    <div id="bookingModal"
                        class="fixed inset-0 flex items-center justify-center  bg-opacity-50 hidden z-50 overflow-y-auto">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
                            <!-- Tombol Close -->
                            <button onclick="closeModal()"
                                class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-2xl">&times;</button>
                            <!--Judul-->
                            <h2 class="text-2xl font-semibold text-[#2c1a0f] mb-4 text-center">Form Pemesanan</h2>

                            @if ($errors->any())
                                <div class="bg-red-100 border border-red-400 text-red-700 p-2 rounded mb-4">
                                    <ul class="list-disc list-inside text-left">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- Form -->
                            <form method="POST" action="{{ route('booking.store.customer') }}">
                                @csrf

                                <!-- Hidden Treatment ID -->
                                <input type="hidden" id="treatment_id" name="treatment_id">
                                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                                <!-- Nama -->
                                <div>
                                    <label for="nama" class="block font-semibold">Nama:</label>
                                    <input type="text" id="nama" name="nama" class="w-full p-2 border rounded"
                                        value="{{ auth()->user()->name ?? '' }}" readonly>
                                </div>
                                <!-- Nomor Telepon -->
                                <div>
                                    <label for="telepon" class="block font-semibold">Nomor Telepon:</label>
                                    <input type="tel" id="telepon" name="telepon" class="w-full p-2 border rounded"
                                        value="{{ auth()->user()->phone ?? '' }}" readonly>
                                </div>
                                <!-- Paket Spa -->
                                <div>
                                    <label class="block font-semibold">Paket Spa yang Dipilih:</label>
                                    <input type="text" id="treatment_name_display"
                                        class="w-full p-2 border rounded bg-gray-100 text-gray-700" readonly>
                                </div>
                                <!-- Tanggal -->
                                <div>
                                    <label for="booking_date" class="block font-semibold">Tanggal:</label>
                                    <input type="date" id="booking_date" name="booking_date"
                                        class="w-full p-2 border rounded" required>
                                </div>
                                <!-- Jam -->
                                <div>
                                    <label for="booking_time" class="block font-semibold">Pilih Jam:</label>
                                    <select id="booking_time" name="booking_time" class="w-full p-2 border rounded" required
                                        onchange="updateHarga()">
                                        <option value="10:00:00">10:00 - 12:30</option>
                                        <option value="10:30:00">10:30 - 13:00</option>
                                        <option value="11:00:00">11:00 - 13:30</option>
                                        <option value="11:30:00">11:30 - 14:00</option>
                                        <option value="12:00:00">12:00 - 14:30</option>
                                        <option value="12:30:00">12:30 - 15:00</option>
                                        <option value="13:00:00">13:00 - 15:30</option>
                                        <option value="13:30:00">13:30 - 16:00</option>
                                        <option value="14:00:00">14:00 - 16:30</option>
                                        <option value="14:30:00">14:30 - 17:00</option>
                                        <option value="15:00:00">15:00 - 17:30</option>
                                        <option value="15:30:00">15:30 - 18:00</option>
                                        <option value="16:00:00">16:00 - 18:30</option>
                                        <option value="16:30:00">16:30 - 19:00</option>
                                        <option value="17:00:00">17:00 - 19:30</option>
                                        <option value="17:30:00">17:30 - 20:00</option>
                                        <option value="18:00:00">18:00 - 20:30</option>
                                        <option value="18:30:00">18:30 - 21:00</option>
                                        <option value="19:00:00">19:00 - 21:30</option>
                                        <option value="19:30:00">19:30 - 22:00</option>
                                    </select>

                                </div>
                                <!-- Pemilihan Therapist -->
                                <div>
                                    <label for="therapist_id" class="block font-semibold">Pilih Therapist (Opsional):</label>
                                    <select id="therapist_id" name="therapist_id" class="w-full p-2 border rounded">
                                        <option value="auto">Pilihkan untuk saya</option>
                                        @foreach ($therapists as $therapist)
                                            <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Metode Pembayaran -->
                                <div>
                                    <label for="payment_method" class="block font-semibold">Metode Pembayaran:</label>
                                    <select id="payment_method" name="payment_method" class="w-full p-2 border rounded"
                                        required>
                                        <option value="">-- Pilih Metode Pembayaran --</option>
                                        <option value="cash">Bayar di Tempat (Cash)</option>
                                        <option value="gateway">Bayar Sekarang (Payment Gateway)</option>
                                    </select>
                                </div>

                                {{-- note --}}
                                <div>
                                    <label for="note" class="block font-semibold">Catatan (opsional):</label>
                                    <textarea name="note" id="note" rows="3" class="w-full p-2 border rounded resize-none"></textarea>
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

                @endauth


                <!-- JavaScript untuk Modal dan Happy Hour -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setMinDate();
                        updateJam();
                    });

                    function openModal(namaPaket, deskripsiPaket, hargaNormal, hargaHappyHour, treatmentId) {
                        document.getElementById("bookingModal").classList.remove("hidden");

                        // Set paket spa yang dipilih di dalam form
                        document.getElementById("treatment_name_display").value = namaPaket + " - " + deskripsiPaket;
                        document.getElementById("treatment_id").value = treatmentId;

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
                        const year = today.getFullYear();
                        const month = String(today.getMonth() + 1).padStart(2, '0');
                        const day = String(today.getDate()).padStart(2, '0');

                        const localDate = `${year}-${month}-${day}`; // Format YYYY-MM-DD
                        document.getElementById("booking_date").setAttribute("min", localDate);
                    }

                    // Perbarui pilihan jam sesuai tanggal yang dipilih
                    function updateJam() {
                        const tanggal = document.getElementById("booking_date").value;
                        const jamSelect = document.getElementById("booking_time");

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
                        const currentMinutes = today.getMinutes();

                        jamOptions.forEach(jam => {
                            const jamMulai = jam.split("-")[0];
                            const [jamHour, jamMinute] = jamMulai.split(":").map(Number); // Pisahkan jam dan menit

                            // Jika hari ini, hanya tampilkan jam yang belum lewat
                            if (isToday && (jamHour < currentHour || (jamHour === currentHour && jamMinute < currentMinutes))) {
                                return;
                            }

                            const option = document.createElement("option");
                            option.value = jamMulai + ":00";
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

                        const tanggal = document.getElementById("booking_date").value;
                        const jamSelect = document.getElementById("booking_time").value;

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
                            "10:00:00", "10:30:00", "11:00:00", "11:30:00",
                            "12:00:00", "12:30:00", "13:00:00"
                        ];

                        return (day >= 1 && day <= 5) && happyHourTimes.includes(jamSelect);
                    }

                    document.getElementById("booking_date").addEventListener("change", function() {
                        updateJam();
                        updateHarga();
                    });

                    document.getElementById("booking_time").addEventListener("change", updateHarga);


                    function fetchAvailableTherapists() {
                        const tanggal = document.getElementById('booking_date').value;
                        const jam = document.getElementById('booking_time').value;
                        const treatmentId = document.getElementById('treatment_id').value;

                        if (!tanggal || !jam || !treatmentId) return;

                        fetch(`/api/available-therapists?tanggal=${tanggal}&jam=${jam}&treatment_id=${treatmentId}`)
                            .then(response => response.json())
                            .then(data => {
                                const therapistSelect = document.getElementById('therapist_id');
                                therapistSelect.innerHTML = '<option value="">Pilihkan untuk saya</option>';

                                if (data.therapists.length === 0) {
                                    const opt = document.createElement('option');
                                    opt.disabled = true;
                                    opt.textContent = 'Tidak ada therapist tersedia';
                                    therapistSelect.appendChild(opt);
                                } else {
                                    data.therapists.forEach(therapist => {
                                        const opt = document.createElement('option');
                                        opt.value = therapist.id;
                                        opt.textContent = therapist.name;
                                        therapistSelect.appendChild(opt);
                                    });
                                }
                            })
                            .catch(err => console.error('Gagal memuat therapist:', err));
                    }

                    // Panggil saat user ganti tanggal / jam / treatment
                    document.getElementById('booking_date').addEventListener('change', () => {
                        updateJam(); // tetap panggil ini
                        setTimeout(fetchAvailableTherapists, 100); // tunggu 0.1 detik agar booking_time sudah update
                    });

                    document.getElementById('booking_time').addEventListener('change', fetchAvailableTherapists);
                    document.getElementById('treatment_id').addEventListener('change', fetchAvailableTherapists);
                </script>
            </div>
        </div>
    </section>
@endsection
