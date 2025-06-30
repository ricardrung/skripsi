@extends('Components.Layout.layout')
@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6 ">
            <h1 class="text-3xl font-bold tracking-tight text-[#2c1a0f]">Body Treatment</h1>
        </div>
    </header>


    <section class="py-16">
        <div class="container mx-auto px-4 text-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @php
                    use App\Services\MembershipService;

                    $membershipService = app(MembershipService::class);
                @endphp

                @foreach ($treatments as $treatment)
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        {{-- Video --}}
                        @if (!empty($treatment->demo_video_url))
                            @php
                                $url = $treatment->demo_video_url;
                                $videoId = '';

                                if (Str::contains($url, 'youtu.be')) {
                                    preg_match('/youtu\.be\/([^\?]+)/', $url, $matches);
                                    $videoId = $matches[1] ?? '';
                                } elseif (Str::contains($url, 'watch?v=')) {
                                    parse_str(parse_url($url, PHP_URL_QUERY), $query);
                                    $videoId = $query['v'] ?? '';
                                }

                                $embedUrl = $videoId ? 'https://www.youtube.com/embed/' . $videoId : '';
                            @endphp

                            @if ($embedUrl)
                                <div class="my-4">
                                    <iframe width="100%" height="200" src="{{ $embedUrl }}" frameborder="0"
                                        allowfullscreen class="rounded-lg shadow"></iframe>
                                </div>
                            @endif
                        @endif

                        <div class="p-6">

                            @php
                                $userMembership = Auth::user()?->userMembership;
                                $membership = $userMembership?->membership;

                                $originalPrice = $treatment->price;
                                $discount = Auth::check()
                                    ? $membershipService->getUserDiscount(
                                        Auth::user(),
                                        $treatment->category->name ?? '',
                                    )
                                    : 0;
                                $finalPrice =
                                    $discount > 0 ? floor($originalPrice * (1 - $discount / 100)) : $originalPrice;
                            @endphp


                            <h3 class="text-2xl font-semibold text-[#2c1a0f]">{{ $treatment->name }}</h3>
                            <p class="text-gray-700 my-2">{{ $treatment->description }}</p>

                            @if ($finalPrice < $originalPrice)
                                <span class="text-sm line-through text-gray-500">Rp
                                    {{ number_format($originalPrice, 0, ',', '.') }}</span>
                                <span class="block text-lg font-bold text-[#8b5a2b]">Rp
                                    {{ number_format($finalPrice, 0, ',', '.') }}</span>
                                <p class="text-green-600 text-sm font-semibold">Diskon Member: {{ $discount }}%</p>
                            @else
                                <span class="block text-lg font-bold text-[#8b5a2b]">Rp
                                    {{ number_format($originalPrice, 0, ',', '.') }}</span>
                            @endif

                            {{-- Happy Hour --}}
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

                            {{-- Tombol Booking --}}
                            @auth
                                <button
                                    onclick="openModal('{{ $treatment->name }}', '{{ $treatment->description }}', {{ $originalPrice }}, {{ $treatment->happy_hour_price ?? 0 }}, {{ $treatment->id }}, '{{ $treatment->category->name ?? '' }}', {{ $discount }})"
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


                <!-- Form Booking Modal -->
                @auth
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
                                <input type="hidden" id="user_membership_level"
                                    value="{{ auth()->user()->membership->level ?? 'classic' }}">
                                <input type="hidden" id="treatment_category" value="">
                                <input type="hidden" id="membership_scope"
                                    value="{{ auth()->user()->userMembership?->membership?->applies_to ?? '' }}">

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
                                        <option value="10:00:00">10:00 - 11:30</option>
                                        <option value="10:30:00">10:30 - 12:00</option>
                                        <option value="11:00:00">11:00 - 12:30</option>
                                        <option value="11:30:00">11:30 - 13:00</option>
                                        <option value="12:00:00">12:00 - 13:30</option>
                                        <option value="12:30:00">12:30 - 14:00</option>
                                        <option value="13:00:00">13:00 - 14:30</option>
                                        <option value="13:30:00">13:30 - 15:00</option>
                                        <option value="14:00:00">14:00 - 15:30</option>
                                        <option value="14:30:00">14:30 - 16:00</option>
                                        <option value="15:00:00">15:00 - 16:30</option>
                                        <option value="15:30:00">15:30 - 17:00</option>
                                        <option value="16:00:00">16:00 - 17:30</option>
                                        <option value="16:30:00">16:30 - 18:00</option>
                                        <option value="17:00:00">17:00 - 18:30</option>
                                        <option value="17:30:00">17:30 - 19:00</option>
                                        <option value="18:00:00">18:00 - 19:30</option>
                                        <option value="18:30:00">18:30 - 20:00</option>
                                        <option value="19:00:00">19:00 - 20:30</option>
                                        <option value="19:30:00">19:30 - 21:00</option>
                                        <option value="20:00:00">20:00 - 21:30</option>
                                        <option value="20:30:00">20:30 - 22:00</option>
                                    </select>
                                </div>

                                {{-- Room Typw --}}
                                <div>
                                    <label for="room_type" class="block font-semibold">Pilih Tipe Ruangan:</label>
                                    <select id="room_type" name="room_type" class="w-full p-2 border rounded" required>
                                        <option value="">-- Pilih Tipe Ruangan --</option>
                                        <option value="single">Single</option>
                                        <option value="double">Double</option>
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

                                <!-- Tambahkan di bawah dropdown room_type -->
                                <div id="second-treatment-container" class="hidden mt-2">
                                    <label for="second_treatment_id" class="block font-semibold">Pilih Treatment
                                        Kedua:</label>
                                    <select name="second_treatment_id" id="second_treatment_id"
                                        class="w-full p-2 border rounded">
                                        <option value="">-- Pilih Treatment Kedua --</option>
                                        @foreach ($allTreatments as $t)
                                            @if ($t->category_id != 7)
                                                <option value="{{ $t->id }}" data-harga="{{ $t->price }}"
                                                    data-happyhour-price="{{ $t->happy_hour_price ?? $t->price }}"
                                                    data-room-type="{{ $t->room_type }}"
                                                    data-category="{{ $t->category->name ?? '' }}">
                                                    {{ $t->name }} ({{ $t->category->name ?? '-' }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                {{-- therapist_kedua --}}
                                <div id="second-therapist-container" class="hidden">
                                    <label for="second_therapist_id" class="block font-semibold">Pilih Therapist Kedua
                                        (Opsional)
                                        :</label>
                                    <select id="second_therapist_id" name="second_therapist_id"
                                        class="w-full p-2 border rounded">
                                        <option value="">Pilihkan untuk saya</option>
                                        @foreach ($therapists as $therapist)
                                            <option value="{{ $therapist->id }}">
                                                {{ $therapist->name }}</option>
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


                {{-- tampilan kedua --}}
                <script>
                    document.getElementById('room_type').addEventListener('change', function() {
                        const roomType = this.value;
                        const secondTreatmentSelect = document.getElementById('second_treatment_id');

                        // Tampilkan/hide treatment kedua
                        const secondTreatmentContainer = document.getElementById('second-treatment-container');
                        const secondTherapistContainer = document.getElementById('second-therapist-container');

                        if (roomType === 'double') {
                            secondTreatmentContainer.classList.remove('hidden');
                            secondTherapistContainer.classList.remove('hidden');

                            // Filter options treatment kedua yang hanya support room_type 'double' atau 'both'
                            Array.from(secondTreatmentSelect.options).forEach(option => {
                                if (option.value === '') {
                                    // selalu tampilkan opsi default
                                    option.style.display = '';
                                    return;
                                }
                                // Ambil atribut data-room-type, harus kamu tambahkan dulu di blade (lihat bawah)
                                const roomTypeOption = option.getAttribute('data-room-type');
                                if (roomTypeOption === 'double' || roomTypeOption === 'both' || roomTypeOption ===
                                    'triple' || roomTypeOption === 'reflexology') {
                                    option.style.display = '';
                                } else {
                                    option.style.display = 'none';
                                }
                            });

                            // Reset value jika saat ini tidak valid (hidden)
                            if (secondTreatmentSelect.selectedOptions.length > 0) {
                                const selectedOption = secondTreatmentSelect.selectedOptions[0];
                                if (selectedOption.style.display === 'none') {
                                    secondTreatmentSelect.value = '';
                                    updateHarga(); // update harga jika treatment kedua berubah
                                }
                            }
                        } else {
                            secondTreatmentContainer.classList.add('hidden');
                            secondTherapistContainer.classList.add('hidden');
                            secondTreatmentSelect.value = '';
                            document.getElementById('second_therapist_id').value = '';
                            updateHarga();
                        }
                    });
                </script>

                <!-- JavaScript untuk Modal dan Happy Hour -->
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        setMinDate();
                        updateJam();
                    });

                    function openModal(namaPaket, deskripsiPaket, hargaNormal, hargaHappyHour, treatmentId, category, diskonMember) {
                        document.getElementById("bookingModal").classList.remove("hidden");

                        // Set paket spa yang dipilih di dalam form
                        document.getElementById("treatment_name_display").value = namaPaket + " - " + deskripsiPaket;
                        document.getElementById("treatment_id").value = treatmentId;

                        // Simpan harga dalam elemen modal
                        document.getElementById("bookingModal").setAttribute("data-harga-normal", hargaNormal);
                        document.getElementById("bookingModal").setAttribute("data-harga-happy-hour", hargaHappyHour);
                        document.getElementById("bookingModal").setAttribute("data-diskon-member", diskonMember);
                        document.getElementById("bookingModal").setAttribute("data-category", category);

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
                            "10:00-11:30", "10:30-12:00", "11:00-12:30", "11:30-13:00",
                            "12:00-13:30", "12:30-14:00", "13:00-14:30", "13:30-15:00",
                            "14:00-15:30", "14:30-16:00", "15:00-16:30", "15:30-17:00",
                            "16:00-17:30", "16:30-18:00", "17:00-18:30", "17:30-19:00",
                            "18:00-19:30", "18:30-20:00", "19:00-20:30", "19:30-21:00",
                            "20:00-21:30", "20:30-22:00"
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

                        const modal = document.getElementById("bookingModal");
                        const hargaNormal = parseInt(modal.getAttribute("data-harga-normal")) || 0;
                        const hargaHappyHour = parseInt(modal.getAttribute("data-harga-happy-hour")) || 0;
                        const diskonMember = parseInt(modal.getAttribute("data-diskon-member")) || 0;

                        const tanggal = document.getElementById("booking_date").value;
                        const jamSelect = document.getElementById("booking_time").value;

                        // Hitung harga treatment pertama
                        let hargaTreatmentPertama = isHappyHour(tanggal, jamSelect) ? hargaHappyHour : hargaNormal;

                        // Terapkan diskon untuk treatment pertama (karena sudah dipastikan sesuai scope)
                        const hargaTreatmentPertamaSetelahDiskon = hargaTreatmentPertama - (hargaTreatmentPertama * diskonMember / 100);

                        let totalHarga = hargaTreatmentPertamaSetelahDiskon;

                        // Tambahkan harga treatment kedua jika dipilih
                        const secondTreatmentSelect = document.getElementById("second_treatment_id");
                        const secondTreatmentOption = secondTreatmentSelect.options[secondTreatmentSelect.selectedIndex];

                        if (secondTreatmentOption && secondTreatmentOption.value !== '') {
                            const secondHargaNormal = parseInt(secondTreatmentOption.getAttribute('data-harga')) || 0;
                            const secondHargaHappy = parseInt(secondTreatmentOption.getAttribute('data-happyhour-price')) ||
                                secondHargaNormal;

                            // Hitung harga treatment kedua
                            let hargaTreatmentKedua = isHappyHour(tanggal, jamSelect) ? secondHargaHappy : secondHargaNormal;

                            // Cek apakah treatment kedua mendapat diskon
                            // Anda perlu menambahkan data-category pada option treatment kedua
                            const secondTreatmentCategory = secondTreatmentOption.getAttribute('data-category');

                            // Dapatkan scope membership dari user (perlu ditambahkan sebagai hidden input atau data attribute)
                            const membershipScope = document.getElementById('membership_scope')?.value || '';

                            // Terapkan diskon hanya jika kategori treatment kedua sesuai dengan scope membership
                            if (membershipScope && secondTreatmentCategory &&
                                (membershipScope.toLowerCase() === 'all' ||
                                    membershipScope.toLowerCase().includes(secondTreatmentCategory.toLowerCase()))) {
                                hargaTreatmentKedua = hargaTreatmentKedua - (hargaTreatmentKedua * diskonMember / 100);
                            }

                            totalHarga += hargaTreatmentKedua;
                        }

                        hargaText.textContent = "Rp" + Math.round(totalHarga).toLocaleString();
                    }

                    // Perbarui harga berdasarkan tanggal dan jam yang dipilih
                    // function updateHarga() {
                    //     const hargaText = document.getElementById("harga");

                    //     const modal = document.getElementById("bookingModal");
                    //     const hargaNormal = parseInt(modal.getAttribute("data-harga-normal")) || 0;
                    //     const hargaHappyHour = parseInt(modal.getAttribute("data-harga-happy-hour")) || 0;
                    //     const diskonMember = parseInt(modal.getAttribute("data-diskon-member")) || 0;

                    //     const tanggal = document.getElementById("booking_date").value;
                    //     const jamSelect = document.getElementById("booking_time").value;

                    //     let hargaFinal = isHappyHour(tanggal, jamSelect) ? hargaHappyHour : hargaNormal;

                    //     // Tambahkan harga treatment kedua jika dipilih
                    //     const secondTreatmentSelect = document.getElementById("second_treatment_id");
                    //     const secondTreatmentOption = secondTreatmentSelect.options[secondTreatmentSelect.selectedIndex];

                    //     if (secondTreatmentOption && secondTreatmentOption.value !== '') {
                    //         const secondHargaNormal = parseInt(secondTreatmentOption.getAttribute('data-harga')) || 0;
                    //         const secondHargaHappy = parseInt(secondTreatmentOption.getAttribute('data-happyhour-price')) ||
                    //             secondHargaNormal;

                    //         if (isHappyHour(tanggal, jamSelect)) {
                    //             hargaFinal += secondHargaHappy;
                    //         } else {
                    //             hargaFinal += secondHargaNormal;
                    //         }
                    //     }

                    //     // Hitung diskon dari membership
                    //     const hargaSetelahDiskon = hargaFinal - (hargaFinal * diskonMember / 100);

                    //     hargaText.textContent = "Rp" + Math.round(hargaSetelahDiskon).toLocaleString();
                    // }
                    document.getElementById('second_treatment_id').addEventListener('change', updateHarga);

                    // Cek apakah Happy Hour berlaku (hanya berlaku di jam 13:00 - 14:30)
                    function isHappyHour(tanggal, jamSelect) {
                        if (!tanggal || !jamSelect) return false;

                        const date = new Date(tanggal);
                        const day = date.getDay(); // 1 = Senin, 2 = Selasa, ..., 5 = Jumat

                        const happyHourTimes = ["10:00:00", "10:30:00", "11:00:00", "11:30:00",
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

                        //CEK KETERSEDIAAN THERAPIST PANGGIL API KE AVAILABLE THERAPIST

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

                        //tampilkan daftar therapist dari database
                        // @foreach ($therapists as $therapist)
                        //     const opt{{ $therapist->id }} = document.createElement('option');
                        //     opt{{ $therapist->id }}.value = '{{ $therapist->id }}';
                        //     opt{{ $therapist->id }}.textContent = '{{ $therapist->name }}';
                        //     therapistSelect.appendChild(opt{{ $therapist->id }});
                        // @endforeach

                    }

                    // Panggil saat user ganti tanggal / jam / treatment
                    document.getElementById('booking_date').addEventListener('change', () => {
                        updateJam(); // tetap panggil ini
                        setTimeout(fetchAvailableTherapists, 100); // tunggu 0.1 detik agar booking_time sudah update
                    });

                    document.getElementById('booking_time').addEventListener('change', fetchAvailableTherapists);
                    document.getElementById('treatment_id').addEventListener('change', fetchAvailableTherapists);

                    // therapist kedua 
                    function fetchAvailableTherapistsSecondTreatment() {
                        const tanggal = document.getElementById('booking_date').value;
                        const jam = document.getElementById('booking_time').value;
                        const treatmentId = document.getElementById('second_treatment_id').value;
                        const firstTherapistId = document.getElementById('therapist_id').value;


                        if (!tanggal || !jam || !treatmentId) return;

                        fetch(
                                `/api/available-therapists-second?tanggal=${tanggal}&jam=${jam}&treatment_id=${treatmentId}&first_therapist_id=${firstTherapistId}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                const therapistSelect = document.getElementById('second_therapist_id');
                                therapistSelect.innerHTML = '<option value="">Pilihkan untuk saya</option>';

                                if (!data.therapists || data.therapists.length === 0) {
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
                            .catch(err => console.error('Gagal memuat therapist kedua:', err));
                    }


                    // Event listener supaya dropdown therapist kedua update saat:
                    document.getElementById('second_treatment_id').addEventListener('change',
                        fetchAvailableTherapistsSecondTreatment);
                    document.getElementById('therapist_id').addEventListener('change', fetchAvailableTherapistsSecondTreatment);
                    document.getElementById('booking_date').addEventListener('change', fetchAvailableTherapistsSecondTreatment);
                    document.getElementById('booking_time').addEventListener('change', fetchAvailableTherapistsSecondTreatment);

                    // Saat therapist kedua dipilih, sembunyikan dia dari dropdown therapist pertama
                    document.getElementById('second_therapist_id').addEventListener('change', function() {
                        const secondTherapistId = this.value;
                        const therapist1Select = document.getElementById('therapist_id');

                        Array.from(therapist1Select.options).forEach(option => {
                            // Tampilkan semua dulu
                            option.hidden = false;

                            // Jangan hide opsi default
                            if (option.value === '') return;

                            // Sembunyikan therapist kedua dari pilihan pertama
                            option.hidden = (option.value === secondTherapistId);
                        });

                        // Reset pilihan jika therapist pertama sekarang hidden
                        if (therapist1Select.value === secondTherapistId) {
                            therapist1Select.value = '';
                        }
                    });

                    // saat therapist pertama berubah, reset pilihan therapist kedua jika sama
                    document.getElementById('therapist_id').addEventListener('change', function() {
                        const firstTherapistId = this.value;
                        const therapist2Select = document.getElementById('second_therapist_id');

                        // Reset pilihan kedua jika sama
                        if (therapist2Select.value === firstTherapistId) {
                            therapist2Select.value = '';
                        }
                    });

                    function updateRoomTypeOptions() {
                        const date = document.getElementById('booking_date').value;
                        const time = document.getElementById('booking_time').value;
                        const treatmentId1 = document.getElementById('treatment_id').value;
                        const treatmentId2 = document.getElementById('second_treatment_id').value;

                        if (!date || !time || !treatmentId1) return;

                        fetch(
                                `/api/all-room-capacities?date=${date}&time=${time}&treatment_id1=${treatmentId1}&treatment_id2=${treatmentId2}`
                            )
                            .then(response => response.json())
                            .then(data => {
                                const select = document.getElementById('room_type');
                                Array.from(select.options).forEach(option => {
                                    const roomType = option.value;
                                    if (roomType && data[roomType]) {
                                        const available = data[roomType].available;
                                        const max = data[roomType].max_capacity;
                                        option.textContent =
                                            `${roomType.charAt(0).toUpperCase() + roomType.slice(1)} (tersedia ${available} dari ${max})`;
                                    }
                                });
                            })
                            .catch(err => {
                                console.error('Gagal memuat kapasitas semua ruangan:', err);
                            });
                    }

                    // Panggil saat tanggal, jam, treatment pertama, atau kedua berubah
                    document.getElementById('booking_date').addEventListener('change', updateRoomTypeOptions);
                    document.getElementById('booking_time').addEventListener('change', updateRoomTypeOptions);
                    document.getElementById('treatment_id').addEventListener('change', updateRoomTypeOptions);
                    document.getElementById('second_treatment_id').addEventListener('change', updateRoomTypeOptions);
                </script>
            </div>
        </div>
    </section>
@endsection
