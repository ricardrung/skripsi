@extends('Components.Layout.layoutadmin')
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

            {{-- Pilih User atau Guest --}}
            <div class="mb-4">
                <label class="block text-gray-700">Pilih Customer Terdaftar</label>
                <select name="user_id" id="user_id" class="w-full p-2 border rounded">
                    <option value="">-- Pilih Customer --</option>
                    @foreach ($customers as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 text-center">
                <p class="text-gray-500 text-sm">Atau jika tamu belum terdaftar:</p>
                <button type="button" id="toggleGuest"
                    class="mt-2 px-4 py-2 bg-yellow-500 rounded hover:bg-yellow-600 text-white">
                    Isi Manual Data Tamu
                </button>
            </div>

            {{-- Input Guest (disembunyikan dulu) --}}
            <div id="guestSection" class="hidden">
                <div class="mb-4">
                    <label class="block text-gray-700">Nama Tamu (Guest)</label>
                    <input type="text" name="guest_name" class="w-full p-2 border rounded" placeholder="Nama tamu">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Nomor HP</label>
                    <input type="text" name="guest_phone" class="w-full p-2 border rounded" placeholder="08xxxxxx">
                </div>
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
                    <option value="gateway">Payment Gateway</option>
                </select>
            </div>

            {{-- Note --}}
            <div class="mb-4">
                <label class="block text-gray-700">Catatan (Opsional)</label>
                <textarea name="note" class="w-full p-2 border rounded" rows="2"></textarea>
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

            paketSelect.addEventListener("change", function() {
                const selected = this.options[this.selectedIndex];
                const durasi = parseInt(selected.dataset.duration);
                const harga = selected.dataset.price;
                hargaInput.value = harga;
                generateJamOptions(durasi);
            });

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

            toggleGuest.addEventListener("click", function() {
                guestSection.classList.toggle("hidden");
            });
        });
    </script>
@endsection
