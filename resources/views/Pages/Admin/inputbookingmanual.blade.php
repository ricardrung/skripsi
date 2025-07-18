@extends('components.Layout.layoutadmin')
@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Input Booking Manual</h2>

        @if (session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="bg-red-200 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('booking.store.admin') }}" method="POST">
            @csrf



            <div class="mb-4">
                <label class="block text-gray-700">Nama Tamu (Guest)</label>
                <input type="text" name="guest_name" class="w-full p-2 border rounded" placeholder="Nama tamu" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Nomor HP</label>
                <input type="text" name="guest_phone" class="w-full p-2 border rounded" placeholder="08xxxxxx" required>
            </div>

            {{-- Treatment --}}
            <div class="mb-4">
                <label class="block text-gray-700">Paket Treatment</label>
                <select name="treatment_id" id="paket" required class="w-full p-2 border rounded">
                    <option value="" disabled selected>Pilih Paket</option>
                    @foreach ($treatments as $treatment)
                        <option value="{{ $treatment->id }}" data-duration="{{ $treatment->duration_minutes }}"
                            data-price="{{ $treatment->price }}" data-happy="{{ $treatment->happy_hour_price }}">
                            {{ $treatment->category->name ?? 'Tanpa Kategori' }} - {{ $treatment->name }}
                            ({{ $treatment->duration_minutes }} Menit)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Harga</label>
                <input type="number" id="harga" class="w-full p-2 border rounded" readonly>
            </div>

            {{-- Tanggal Booking --}}
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Treatment</label>
                <input type="date" name="booking_date" required class="w-full p-2 border rounded">
            </div>

            {{-- Jam Booking --}}
            <div class="mb-4">
                <label class="block text-gray-700">Jam Treatment</label>
                <select name="booking_time" id="jam" required class="w-full p-2 border rounded">
                    <option value="">Pilih jam</option>
                </select>
            </div>

            {{-- Therapist --}}
            <div class="mb-4">
                <label class="block text-gray-700">Pilih Therapist</label>
                <select name="therapist_id" class="w-full p-2 border rounded">
                    <option value="">Pilih Otomatis</option>
                    @foreach ($therapists as $therapist)
                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Metode Bayar --}}
            <div class="mb-4">
                <label class="block text-gray-700">Metode Pembayaran</label>
                <select name="payment_method" class="w-full p-2 border rounded" required>
                    <option value="cash">Cash</option>

                </select>
            </div>

            {{-- Note --}}
            <div class="mb-4">
                <label class="block text-gray-700">Catatan (Opsional)</label>
                <textarea name="note" class="w-full p-2 border rounded" rows="2"></textarea>
            </div>

            {{-- ruangan --}}
            <div class="mb-4">
                <label class="block text-gray-700">Tipe Ruangan</label>
                <select name="room_type" id="room_type" class="w-full p-2 border rounded" required>
                    <option value="single">Single</option>
                    <option value="double">Double</option>
                    <option value="hair">Hair</option>
                    <option value="reflexology">Reflexology</option>
                </select>
            </div>

            <!-- Tambahkan di bawah dropdown room_type -->
            <div id="second-treatment-container" class="hidden mt-2">
                <label for="second_treatment_id" class="block font-semibold">Pilih Treatment
                    Kedua:</label>
                <select name="second_treatment_id" id="second_treatment_id" class="w-full p-2 border rounded">
                    <option value="">-- Pilih Treatment Kedua --</option>
                    @foreach ($allTreatments as $t)
                        @if ($t->category_id != 7)
                            <option value="{{ $t->id }}" data-harga="{{ $t->price }}"
                                data-happyhour-price="{{ $t->happy_hour_price ?? $t->price }}"
                                data-room-type="{{ $t->room_type }}">
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
                <select id="second_therapist_id" name="second_therapist_id" class="w-full p-2 border rounded">
                    <option value="">Pilihkan untuk saya</option>
                    @foreach ($therapists as $therapist)
                        <option value="{{ $therapist->id }}">
                            {{ $therapist->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Total Harga</label>
                <input type="number" id="total_harga" class="w-full p-2 border rounded" readonly>
            </div>



            {{-- Submit --}}
            <div class="mt-4">
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded">Simpan
                    Booking</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paketSelect = document.getElementById("paket");
            const jamSelect = document.getElementById("jam");
            const hargaInput = document.getElementById("harga");
            const guestSection = document.getElementById("guestSection");
            const toggleGuest = document.getElementById("toggleGuest");
            const secondTreatmentSelect = document.getElementById("second_treatment_id");
            const totalHargaInput = document.getElementById("total_harga");
            const roomTypeSelect = document.getElementById("room_type");
            const secondTreatmentContainer = document.getElementById("second-treatment-container");
            const secondTherapistContainer = document.getElementById("second-therapist-container");

            paketSelect.addEventListener("change", function() {
                const selected = this.options[this.selectedIndex];
                const durasi = parseInt(selected.dataset.duration);
                generateJamOptions(durasi);
                updateHargaUtama();
                updateTotalHarga();
            });

            secondTreatmentSelect.addEventListener("change", updateTotalHarga);
            jamSelect.addEventListener("change", function() {
                updateHargaUtama();
                updateTotalHarga();
            });

            roomTypeSelect.addEventListener("change", function() {
                if (this.value === "double") {
                    secondTreatmentContainer.classList.remove("hidden");
                    secondTherapistContainer.classList.remove("hidden");
                } else {
                    secondTreatmentContainer.classList.add("hidden");
                    secondTherapistContainer.classList.add("hidden");
                }
                updateTotalHarga();
            });

            toggleGuest.addEventListener("click", function() {
                guestSection.classList.toggle("hidden");
            });

            function updateHargaUtama() {
                const selected = paketSelect.options[paketSelect.selectedIndex];
                const jamValue = jamSelect.value;
                const happy = selected.dataset.happy;
                const normal = selected.dataset.price;
                let harga = parseInt(normal);

                // Cek Happy Hour
                const day = new Date().getDay(); // 0: Minggu, 1: Senin, ..., 6: Sabtu
                const isWeekday = day >= 1 && day <= 5;

                if (isWeekday && jamValue) {
                    const jamParts = jamValue.split(":");
                    const jamMulai = parseInt(jamParts[0]);
                    const menitMulai = parseInt(jamParts[1]);
                    const timeInMinutes = jamMulai * 60 + menitMulai;

                    const start = 10 * 60; // 10:00
                    const end = 13 * 60; // 13:00

                    if (timeInMinutes >= start && timeInMinutes <= end && happy) {
                        harga = parseInt(happy);
                    }
                }

                hargaInput.value = harga || 0;
            }

            function updateTotalHarga() {
                const harga1 = parseInt(hargaInput.value) || 0;
                let harga2 = 0;

                const selected2 = secondTreatmentSelect.options[secondTreatmentSelect.selectedIndex];
                if (selected2 && selected2.value !== "") {
                    const normal2 = parseInt(selected2.dataset.harga) || 0;
                    const happy2 = parseInt(selected2.dataset.happyhourPrice) || normal2;

                    // Ambil jam dari booking utama
                    const jamValue = jamSelect.value;
                    const day = new Date().getDay();
                    const isWeekday = day >= 1 && day <= 5;

                    if (isWeekday && jamValue) {
                        const jamMulai = parseInt(jamValue.split(":")[0]);
                        if (jamMulai >= 10 && jamMulai < 13) {
                            harga2 = happy2;
                        } else {
                            harga2 = normal2;
                        }
                    } else {
                        harga2 = normal2;
                    }
                }

                if (totalHargaInput) {
                    totalHargaInput.value = harga1 + harga2;
                }
            }


            function generateJamOptions(durasi) {
                jamSelect.innerHTML = '<option value="">Pilih Jam</option>';
                const jamBuka = 10 * 60;
                const jamTutup = 22 * 60;
                const interval = 30;
                for (let mulai = jamBuka; mulai + durasi <= jamTutup; mulai += interval) {
                    const jamMulai = formatTime(mulai);
                    const jamSelesai = formatTime(mulai + durasi);
                    const option = document.createElement("option");
                    option.value = jamMulai;
                    option.textContent = `${jamMulai} - ${jamSelesai}`;
                    jamSelect.appendChild(option);
                }
            }

            function formatTime(totalMinutes) {
                const hours = Math.floor(totalMinutes / 60);
                const minutes = totalMinutes % 60;
                return `${hours.toString().padStart(2, "0")}:${minutes.toString().padStart(2, "0")}`;
            }
        });
    </script>
@endsection
