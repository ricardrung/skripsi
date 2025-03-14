@extends('Components.Layout.layoutadmin')
@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-gray-700 mb-4">Input Booking Manual</h2>

        <form>
            @csrf

            <!-- Nama Pelanggan -->
            <div class="mb-4">
                <label class="block text-gray-700">Nama Pelanggan</label>
                <input type="text" name="nama" placeholder="Enter Full Name" class="w-full p-2 border rounded">
            </div>

            {{-- Gender --}}
            <div class="mb-4">
                <label class="block text-gray-700">Gender</label>
                <select type="text" placeholder="Enter Gender" class="w-full p-2 border rounded" required>
                    <option value="">Select Gender</option>
                    <option value="Laki-laki">Laki-laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" placeholder="Enter Email" class="w-full p-2 border rounded" required>
            </div>

            <!-- Nomor WA/Telepon -->
            <div class="mb-4">
                <label class="block text-gray-700">Nomor WhatsApp/Telepon</label>
                <input type="text" name="nomor" placeholder="Enter Phone Number" class="w-full p-2 border rounded">
            </div>

            {{-- Birhtday --}}
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Lahir</label>
                <input type="date" placeholder="Enter Birth Date" class="w-full p-2  border rounded" required>
            </div>

            <!-- Paket Treatment -->
            <div class="mb-4">
                <label class="block text-gray-700">Paket Treatment</label>
                <select name="paket" id="paket" required class="w-full p-2 border rounded">
                    <option value="" disabled selected>Pilih Paket</option>
                    <option value="30">Reflexology (30 Menit)</option>
                    <option value="60">Full Body Massage (60 Menit)</option>
                    <option value="90">Aromatherapy (90 Menit)</option>
                </select>
            </div>

            <!-- Harga Treatment -->
            <div class="mb-4">
                <label class="block text-gray-700">Harga Treatment</label>
                <input type="number" name="harga" placeholder="Enter Price" required class="w-full p-2 border rounded">
            </div>

            <!-- Tanggal Treatment -->
            <div class="mb-4">
                <label class="block text-gray-700">Tanggal Treatment</label>
                <input type="date" name="tanggal" required class="w-full p-2 border rounded">
            </div>

            <!-- Jam Treatment -->
            <div class="mb-4">
                <label class="block text-gray-700">Jam Treatment</label>
                <select name="jam" id="jam" required class="w-full p-2 border rounded">
                    <option value="" disabled selected>Pilih Jam</option>
                </select>
            </div>

            <!-- Therapist -->
            <div class="mb-4">
                <label class="block text-gray-700">Therapist</label>
                <select name="therapist" required class="w-full p-2 border rounded">
                    <option value="Auto">Pilih Otomatis</option>
                    <option value="Therapist A">Therapist A</option>
                    <option value="Therapist B">Therapist B</option>
                </select>
            </div>

            <!-- Metode Pembayaran -->
            <div class="mb-4">
                <label class="block text-gray-700">Metode Pembayaran</label>
                <select name="metode_pembayaran" required class="w-full p-2 border rounded">
                    <option value="Cash">Cash</option>
                    <option value="Transfer">Transfer</option>
                    <option value="Payment Gateway">Payment Gateway</option>
                </select>
            </div>

            <!-- Status Pembayaran -->
            <div class="mb-4">
                <label class="block text-gray-700">Status Pembayaran</label>
                <select name="status_pembayaran" required class="w-full p-2 border rounded">
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                    <option value="Processing">Processing</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-4">
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Simpan
                    Booking</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const paketSelect = document.getElementById("paket");
            const jamSelect = document.getElementById("jam");

            paketSelect.addEventListener("change", function() {
                const durasi = parseInt(this.value); // Durasi treatment dalam menit
                generateJamOptions(durasi);
            });

            function generateJamOptions(durasi) {
                jamSelect.innerHTML = '<option value="" disabled selected>Pilih Jam</option>'; // Reset dropdown

                const jamBuka = 10 * 60; // 10:00 dalam menit
                const jamTutup = 22 * 60; // 22:00 dalam menit
                const interval = 30; // Interval 30 menit

                for (let waktuMulai = jamBuka; waktuMulai + durasi <= jamTutup; waktuMulai += interval) {
                    const jamMulai = formatWaktu(waktuMulai);
                    const jamSelesai = formatWaktu(waktuMulai + durasi);
                    const option = document.createElement("option");
                    option.value = jamMulai;
                    option.textContent = `${jamMulai} - ${jamSelesai}`;
                    jamSelect.appendChild(option);
                }
            }

            function formatWaktu(totalMenit) {
                const jam = Math.floor(totalMenit / 60);
                const menit = totalMenit % 60;
                return `${jam.toString().padStart(2, "0")}:${menit.toString().padStart(2, "0")}`;
            }
        });
    </script>
@endsection
