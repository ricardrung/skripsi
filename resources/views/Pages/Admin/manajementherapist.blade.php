@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <!-- Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Therapist</h1>

        <!-- Tombol Tambah Therapist -->
        <div class="mb-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" onclick="openAddModal()">
                + Tambah Therapist
            </button>
        </div>

        <!-- Tabel Therapist -->
        <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
            <table class="w-full border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-left">Jadwal Kerja</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Contoh Data -->
                    <tr class="border-b">
                        <td class="p-3">Therapist A</td>
                        <td class="p-3">Senin - Jumat</td>
                        <td class="p-3 text-green-600">Sedang Menangani Pelanggan</td>
                        <td class="p-3 flex flex-col md:flex-row gap-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600"
                                onclick="openEditModal('Therapist A', 'Senin - Jumat')">Edit</button>
                            <button class="bg-gray-600 text-white px-3 py-1 rounded-lg hover:bg-gray-700"
                                onclick="openUnavailableModal()">Tidak Tersedia</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700"
                                onclick="confirmDelete()">Hapus</button>
                        </td>
                    </tr>
                    <tr class="border-b">
                        <td class="p-3">Therapist B</td>
                        <td class="p-3">Selasa - Sabtu</td>
                        <td class="p-3 text-gray-500">Tersedia</td>
                        <td class="p-3 flex flex-col md:flex-row gap-2">
                            <button class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600"
                                onclick="openEditModal('Therapist B', 'Selasa - Sabtu')">Edit</button>
                            <button class="bg-gray-600 text-white px-3 py-1 rounded-lg hover:bg-gray-700"
                                onclick="openUnavailableModal()">Tidak Tersedia</button>
                            <button class="bg-red-600 text-white px-3 py-1 rounded-lg hover:bg-red-700"
                                onclick="confirmDelete()">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Therapist -->
    <div id="addModal" class="fixed inset-0 flex items-center justify-center  bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
            <h2 class="text-xl font-semibold mb-4">Tambah Therapist</h2>
            <form>
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold">Nama Therapist</label>
                    <input type="text" name="name" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Jadwal Kerja</label>
                    <input type="text" name="schedule" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-400 text-white px-3 py-1 rounded-lg"
                        onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Therapist -->
    <div id="editModal" class="fixed inset-0 flex items-center justify-center  bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
            <h2 class="text-xl font-semibold mb-4">Edit Therapist</h2>
            <form id="editForm">
                @csrf
                <div class="mb-4">
                    <label class="block font-semibold">Nama Therapist</label>
                    <input type="text" id="editName" name="name" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block font-semibold">Jadwal Kerja</label>
                    <input type="text" id="editSchedule" name="schedule" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" class="bg-gray-400 text-white px-3 py-1 rounded-lg"
                        onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Modal -->
    <script>
        function openAddModal() {
            document.getElementById("addModal").classList.remove("hidden");
        }

        function closeAddModal() {
            document.getElementById("addModal").classList.add("hidden");
        }

        function openEditModal(therapistName, therapistSchedule) {
            document.getElementById("editName").value = therapistName;
            document.getElementById("editSchedule").value = therapistSchedule;
            document.getElementById("editModal").classList.remove("hidden");
        }

        function closeEditModal() {
            document.getElementById("editModal").classList.add("hidden");
        }

        function confirmDelete() {
            if (confirm("Apakah Anda yakin ingin menghapus therapist ini?")) {
                alert("Therapist berhasil dihapus (simulasi).");
            }
        }

        function openUnavailableModal() {
            alert("Fitur ini belum diimplementasikan.");
        }

        // Event listener untuk form edit
        document.getElementById("editForm").addEventListener("submit", function(event) {
            event.preventDefault();
            alert("Data berhasil diperbarui (simulasi).");
            closeEditModal();
        });
    </script>
@endsection
