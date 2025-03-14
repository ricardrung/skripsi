@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Paket Treatment</h1>

        <!-- Filter dan Tambah -->
        <div class="flex justify-between mb-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="openModal()">+ Tambah
                Paket</button>
        </div>

        <!-- Tabel Paket Treatment -->
        <div class="overflow-x-auto p-6 bg-white shadow-md rounded-lg">
            <table class="w-full border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-200 whitespace-nowrap">
                        <th class="p-4 text-left">Nama Paket</th>
                        <th class="p-4 text-left">Harga Normal</th>
                        <th class="p-4 text-left">Happy Hour</th>
                        <th class="p-4 text-left">Durasi</th>
                        <th class="p-4 text-left">Deskripsi</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Foto</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- contoh data --}}
                    <tr class="border-b whitespace-nowrap">
                        <td class="p-3">Gold Spa</td>
                        <td class="p-3 ">Rp 500.000</td>
                        <td class="p-3 ">Rp 400.000</td>
                        <td class="p-3 ">90 menit</td>
                        <td class="p-3 ">Diskon 20% untuk spa relaksasi</td>
                        <td class="p-3 ">
                            <span class="bg-green-500 text-white px-2 py-1 rounded">Aktif</span>
                        </td>
                        <td class="p-3 ">
                            <img src="/images/1.jpg" alt="Gold Spa" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="p-3 flex flex-col md:flex-row gap-2 ">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600"
                                onclick="openModal()">Edit</button>
                            <button class="bg-gray-600 text-white px-3 py-1 rounded-lg hover:bg-gray-700"
                                onclick="openUnavailableModal()">Tidak Tersedia</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 "
                                onclick="confirmDelete()">Hapus</button>
                        </td>
                    </tr>
                    <tr class="border-b whitespace-nowrap">
                        <td class="p-3">Gold Spa</td>
                        <td class="p-3 ">Rp 500.000</td>
                        <td class="p-3 ">Rp 400.000</td>
                        <td class="p-3 ">90 menit</td>
                        <td class="p-3 ">Diskon 20% untuk spa relaksasi</td>
                        <td class="p-3 ">
                            <span class="bg-green-500 text-white px-2 py-1 rounded">Aktif</span>
                        </td>
                        <td class="p-3 ">
                            <img src="gold-spa.jpg" alt="Gold Spa" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="p-3 flex flex-col md:flex-row gap-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600 "
                                onclick="openModal()">Edit</button>
                            <button class="bg-gray-600 text-white px-3 py-1 rounded-lg hover:bg-gray-700 "
                                onclick="openUnavailableModal()">Tidak Tersedia</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700 "
                                onclick="confirmDelete()">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah/Edit Paket -->
        <div id="modal"
            class="fixed inset-0 flex items-center justify-center  bg-opacity-50 hidden z-50 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
                <h3 class="text-lg font-semibold mb-4">Tambah/Edit Paket</h3>
                <form>
                    @csrf
                    <input type="text" placeholder="Nama Paket" class="border rounded p-2 w-full mb-2">
                    <input type="number" placeholder="Harga Normal" class="border rounded p-2 w-full mb-2">
                    <input type="number" placeholder="Harga Happy Hour" class="border rounded p-2 w-full mb-2">

                    <!-- Pilih Durasi -->
                    <select class="border rounded p-2 w-full mb-2">
                        <option value="30">30 Menit</option>
                        <option value="60">60 Menit</option>
                        <option value="90">90 Menit</option>
                        <option value="120">120 Menit</option>
                    </select>

                    {{-- deskripsi --}}
                    <textarea placeholder="Deskripsi Promo" class="border rounded p-2 w-full mb-2"></textarea>

                    <!-- Pilih Status Paket -->
                    <select class="border rounded p-2 w-full mb-2">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                        <option value="disembunyikan">Disembunyikan</option>
                    </select>

                    <!-- Upload Foto Paket -->
                    <label class="block text-gray-700 font-semibold mb-1">Upload Foto Paket</label>
                    <input type="file" class="border rounded p-2 w-full mb-4">

                    <!-- Pilihan Promo Happy Hour -->
                    <div class="flex items-center mb-4">
                        <input type="checkbox" id="happyHour" class="mr-2">
                        <label for="happyHour" class="text-gray-700">Masukkan ke dalam promo Happy Hour</label>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="mr-2 bg-gray-400 px-4 py-2 rounded"
                            onclick="closeModal()">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Modal -->
    <script>
        function openModal() {
            document.getElementById("modal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        function openUnavailableModal() {
            alert("Fitur ini belum diimplementasikan.");
        }

        function confirmDelete() {
            if (confirm("Apakah Anda yakin ingin menghapus Paket Treatment ini?")) {
                alert("Paket Treatment berhasil dihapus (simulasi).");
            }
        }
    </script>
@endsection
