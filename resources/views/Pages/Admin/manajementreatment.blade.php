@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Paket Treatment</h1>

        <div class="flex justify-between mb-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="openModal()">+ Tambah
                Paket</button>
        </div>

        <div class="overflow-x-auto p-6 bg-white shadow-md rounded-lg">
            <table class="w-full border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-200 whitespace-nowrap">
                        <th class="p-4 text-left">Nama Paket</th>
                        <th class="p-4 text-left">Kategori</th>
                        <th class="p-4 text-left">Harga Normal</th>
                        <th class="p-4 text-left">Happy Hour</th>
                        <th class="p-4 text-left">Durasi</th>
                        <th class="p-4 text-left">Deskripsi</th>
                        <th class="p-4 text-left">Promo</th>
                        <th class="p-4 text-left">Foto</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- contoh data statis sementara --}}
                    <tr class="border-b whitespace-nowrap">
                        <td class="p-3">Gold Spa</td>
                        <td class="p-3">Body Treatment</td>
                        <td class="p-3">Rp 500.000</td>
                        <td class="p-3">Rp 400.000</td>
                        <td class="p-3">90 menit</td>
                        <td class="p-3">Diskon 20% untuk spa relaksasi</td>
                        <td class="p-3"><span class="bg-green-500 text-white px-2 py-1 rounded">Ya</span></td>
                        <td class="p-3"><img src="/images/1.jpg" alt="Gold Spa" class="w-16 h-16 object-cover rounded">
                        </td>
                        <td class="p-3 flex flex-col md:flex-row gap-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600"
                                onclick="openModal()">Edit</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700"
                                onclick="confirmDelete()">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="modal"
            class="fixed inset-0 flex items-center justify-center bg-opacity-50 hidden z-50 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
                <h3 class="text-lg font-semibold mb-4">Tambah/Edit Paket</h3>
                <form method="POST" action="{{ route('treatments.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" placeholder="Nama Paket" class="border rounded p-2 w-full mb-2"
                        required>
                    <select name="category_id" class="border rounded p-2 w-full mb-2" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="price" placeholder="Harga Normal" class="border rounded p-2 w-full mb-2"
                        required>
                    <input type="number" name="happy_hour_price" placeholder="Harga Happy Hour"
                        class="border rounded p-2 w-full mb-2">
                    <select name="duration_minutes" class="border rounded p-2 w-full mb-2" required>
                        <option value="">-- Pilih Durasi --</option>
                        <option value="30">15 Menit</option>
                        <option value="30">20 Menit</option>
                        <option value="30">30 Menit</option>
                        <option value="30">45 Menit</option>
                        <option value="60">60 Menit</option>
                        <option value="90">90 Menit</option>
                        <option value="120">150 Menit</option>
                    </select>
                    <textarea name="description" placeholder="Deskripsi" class="border rounded p-2 w-full mb-2"></textarea>
                    <input type="url" name="demo_video_url" placeholder="Link Video Demo"
                        class="border rounded p-2 w-full mb-2">
                    <input type="number" name="promo_required_bookings" placeholder="Minimal Booking untuk Promo"
                        class="border rounded p-2 w-full mb-2">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="is_promo" id="is_promo" class="mr-2">
                        <label for="is_promo">Masukkan ke dalam Promo</label>
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

    <script>
        function openModal() {
            document.getElementById("modal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        function confirmDelete() {
            if (confirm("Apakah Anda yakin ingin menghapus Paket Treatment ini?")) {
                alert("Paket Treatment berhasil dihapus (simulasi).")
            }
        }
    </script>
@endsection
