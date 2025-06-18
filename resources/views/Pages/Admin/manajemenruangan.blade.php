@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Ruangan Spa</h1>

        <div class="flex justify-between mb-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="openModal()">+ Tambah
                Ruangan</button>
        </div>

        <form method="GET" action="{{ route('spa_rooms.index') }}" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari nama ruangan..." value="{{ request('search') }}"
                class="p-2 border rounded w-full md:w-64">
            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Filter</button>
        </form>

        @if ($rooms->isEmpty())
            <p class="text-center text-gray-500">Belum ada data ruangan.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Nama Ruangan</th>
                            <th class="py-3 px-4 text-left">Tipe</th>
                            <th class="py-3 px-4 text-left">Kapasitas</th>
                            <th class="py-3 px-4 text-left">Deskripsi</th>
                            <th class="py-3 px-4 text-left">Gambar</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $room->room_name }}</td>
                                <td class="py-3 px-4">{{ $room->room_type }}</td>
                                <td class="py-3 px-4">{{ $room->capacity }}</td>
                                <td class="py-3 px-4" title="{{ $room->description }}">
                                    {{ Str::limit($room->description, 40) }}
                                </td>
                                <td class="py-3 px-4">
                                    @if ($room->image_path)
                                        <img src="{{ asset('storage/' . $room->image_path) }}" class="h-12 rounded shadow">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full {{ $room->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $room->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 space-y-2">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 w-full"
                                        onclick="openModalEdit({{ $room->id }})">Edit</button>
                                    <form id="delete-room-{{ $room->id }}" method="POST"
                                        action="{{ route('spa_rooms.destroy', $room->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteRoom({{ $room->id }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 w-full">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $rooms->appends(request()->except('page'))->links() }}
            </div>
        @endif

        {{-- Modal Tambah/Edit --}}
        <div id="modal"
            class="fixed inset-0 flex items-center justify-center bg-opacity-50 hidden z-50 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
                <h3 class="text-lg font-semibold mb-4">Tambah/Edit Ruangan</h3>
                <form id="spaRoomForm" method="POST" action="{{ route('spa_rooms.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="spa_room_id">
                    <input type="hidden" name="_method" id="form_method" value="POST">

                    <input type="text" name="room_name" placeholder="Nama Ruangan" class="border rounded p-2 w-full mb-2"
                        required>
                    <input type="text" name="room_type" placeholder="Tipe Ruangan" class="border rounded p-2 w-full mb-2"
                        required>
                    <input type="number" name="capacity" placeholder="Kapasitas" class="border rounded p-2 w-full mb-2"
                        required>
                    <textarea name="description" placeholder="Deskripsi" class="border rounded p-2 w-full mb-2"></textarea>
                    <input type="file" name="image" accept="image/*" class="border rounded p-2 w-full mb-2">
                    <div class="flex items-center mb-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="mr-2" checked>
                        <label for="is_active">Aktifkan/Tampilkan di Galeri</label>
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
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif


    {{-- JavaScript --}}
    <script>
        function openModal() {
            const form = document.getElementById("spaRoomForm");
            form.reset();
            form.action = "{{ route('spa_rooms.store') }}";
            document.getElementById('form_method').value = "POST";
            document.getElementById("modal").classList.remove("hidden");
        }

        function openModalEdit(id) {
            fetch(`/spa_rooms/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('spaRoomForm');
                    form.room_name.value = data.room_name;
                    form.room_type.value = data.room_type;
                    form.capacity.value = data.capacity;
                    form.description.value = data.description ?? '';
                    form.is_active.checked = data.is_active;

                    form.action = `/spa_rooms/${id}`;
                    document.getElementById('form_method').value = "PUT";
                    document.getElementById("modal").classList.remove("hidden");
                })
                .catch(error => {
                    alert('Gagal mengambil data ruangan untuk diedit.');
                });
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        function confirmDeleteRoom(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-room-${id}`).submit();
                }
            });
        }
    </script>
@endsection
