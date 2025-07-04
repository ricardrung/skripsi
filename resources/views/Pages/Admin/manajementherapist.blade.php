@extends('components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Therapist</h1>
        {{-- Tombol Tambah Therapist --}}
        <div class="flex justify-between mb-4">
            <button onclick="openTambahModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Therapist
            </button>
        </div>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari nama therapist..." value="{{ request('search') }}"
                class="p-2 border rounded w-full sm:w-64">

            <select name="availability" class="p-2 border rounded bg-white w-full sm:w-60">
                <option value="">Semua Ketersediaan</option>
                <option value="tersedia" {{ request('availability') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="sedang menangani" {{ request('availability') == 'sedang menangani' ? 'selected' : '' }}>
                    Sedang Menangani</option>
                <option value="tidak tersedia" {{ request('availability') == 'tidak tersedia' ? 'selected' : '' }}>Tidak
                    Tersedia</option>
            </select>


            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full sm:w-auto">
                Filter
            </button>

            <a href="{{ route('therapist.index') }}"
                class="text-gray-600 underline hover:text-gray-800 w-full sm:w-auto text-center">
                Reset
            </a>
        </form>

        @if ($therapists->isEmpty())
            <p class="text-center text-gray-500">Belum ada data therapist.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white whitespace-nowrap">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Nama</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Gender</th>
                            <th class="py-3 px-4 text-left">Kontak</th>
                            <th class="py-3 px-4 text-left">Ketersediaan</th>
                            <th class="py-3 px-4 text-left">Booking Aktif</th>
                            <th class="py-3 px-4 text-left">Hari Ini</th>
                            <th class="py-3 px-4 text-left">Bulan Ini</th>
                            <th class="py-3 px-4 text-left">Foto</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($therapists as $index => $therapist)
                            <tr class="border-b hover:bg-gray-50 whitespace-nowrap">
                                <td class="py-3 px-4">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-3 px-4">{{ $therapist->name }}</td>
                                <td class="py-3 px-4">{{ $therapist->email }}</td>
                                <td class="py-3 px-4">{{ ucfirst($therapist->gender) }}</td>
                                <td class="py-3 px-4">{{ $therapist->phone }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full
                                        @if ($therapist->availability == 'tersedia') bg-green-100 text-green-700
                                        @elseif ($therapist->availability == 'sedang menangani') bg-yellow-100 text-yellow-700
                                        @elseif ($therapist->availability == 'tidak tersedia') bg-red-100 text-red-700
                                        @else bg-gray-200 text-gray-700 @endif">
                                        {{ ucfirst($therapist->availability) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">{{ $therapist->bookings_count ?? 0 }}</td>
                                <td class="py-3 px-4">{{ $therapist->bookings_today_count ?? 0 }}</td>
                                <td class="py-3 px-4">{{ $therapist->bookings_selesai_bulan_ini_count ?? 0 }}</td>
                                <td class="py-3 px-4">
                                    @if ($therapist->photo)
                                        <img src="{{ asset('storage/' . $therapist->photo) }}" alt="foto"
                                            class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <span class="text-gray-400 italic">-</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 space-y-2">
                                    <a href="#"
                                        onclick="openEditModal({{ $therapist->id }}, '{{ $therapist->name }}', '{{ $therapist->phone }}', '{{ $therapist->gender }}', '{{ $therapist->availability }}', '{{ $therapist->photo }}')"
                                        class="block text-center bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Edit
                                    </a>


                                    <form id="deleteForm-{{ $therapist->id }}"
                                        action="{{ route('therapist.destroy', $therapist->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $therapist->id }})"
                                            class="block w-full bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="mt-6">
            {{ $therapists->appends(request()->except('page'))->links() }}
        </div>


        {{-- Alert sukses --}}
        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: `{!! session('success') !!}`,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data therapist akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('deleteForm-' + id).submit();
                    }
                });
            }
        </script>

        {{-- Tambah fungsi dummy jika diperlukan --}}
        <script>
            function editTherapist(id) {
                alert('Edit therapist ID: ' + id);
            }
        </script>
    </div>

    {{-- Modal Tambah Therapist --}}
    <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
            <button onclick="closeTambahModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>

            <h2 class="text-xl font-bold mb-4 text-center">Tambah Therapist Baru</h2>

            <form action="{{ route('therapist.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Nama</label>
                    <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Email</label>
                    <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Password</label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Jenis Kelamin</label>
                    <select name="gender" class="w-full border rounded px-3 py-2" required>
                        <option value="">-- Pilih --</option>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Ketersediaan</label>
                    <select name="availability" class="w-full border rounded px-3 py-2" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="sedang menangani">Sedang Menangani</option>
                        <option value="tidak tersedia">Tidak Tersedia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Foto Profil</label>
                    <input type="file" name="photo" accept="image/*" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Therapist --}}
    <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
            <button onclick="closeEditModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>

            <h2 class="text-xl font-bold mb-4 text-center">Edit Therapist</h2>

            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Nama</label>
                    <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Email</label>
                    <input type="email" name="email" id="editEmail" class="w-full border rounded px-3 py-2" required>
                </div>


                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Nomor Telepon</label>
                    <input type="text" name="phone" id="editPhone" class="w-full border rounded px-3 py-2"
                        required>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Jenis Kelamin</label>
                    <select name="gender" id="editGender" class="w-full border rounded px-3 py-2" required>
                        <option value="male">Laki-laki</option>
                        <option value="female">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Ketersediaan</label>
                    <select name="availability" id="editAvailability" class="w-full border rounded px-3 py-2" required>
                        <option value="tersedia">Tersedia</option>
                        <option value="sedang menangani">Sedang Menangani</option>
                        <option value="tidak tersedia">Tidak Tersedia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Foto Saat Ini</label>
                    <img id="editPhotoPreview" src="" alt="Foto"
                        class="w-20 h-20 rounded-full object-cover mb-2">
                </div>

                <div class="mb-3">
                    <label class="block text-sm text-gray-700">Ganti Foto (Opsional)</label>
                    <input type="file" name="photo" class="w-full border rounded px-3 py-2" accept="image/*">
                </div>


                <div class="mt-4">
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openEditModal(id, name, phone, gender, availability, photoPath = null) {
            document.getElementById('editForm').action = '/manajemen-therapist/' + id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editPhone').value = phone;
            document.getElementById('editGender').value = gender;
            document.getElementById('editAvailability').value = availability;
            const photoPreview = document.getElementById('editPhotoPreview');
            if (photoPath) {
                photoPreview.src = '/storage/' + photoPath;
                photoPreview.classList.remove('hidden');
            } else {
                photoPreview.src = '';
                photoPreview.classList.add('hidden');
            }
            document.getElementById('modalEdit').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('modalEdit').classList.add('hidden');
        }
    </script>

    {{-- Script untuk modal --}}
    <script>
        function openTambahModal() {
            document.getElementById('modalTambah').classList.remove('hidden');
        }

        function closeTambahModal() {
            document.getElementById('modalTambah').classList.add('hidden');
        }
    </script>

@endsection
