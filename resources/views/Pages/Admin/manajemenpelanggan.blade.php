@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Pelanggan</h1>

        {{-- Tombol Tambah --}}
        <div class="flex justify-between mb-4">
            <button onclick="openTambahModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Pelanggan
            </button>
        </div>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari nama/email..." value="{{ request('search') }}"
                class="p-2 border rounded w-full sm:w-64">

            <select name="gender" class="p-2 border rounded bg-white w-full sm:w-48">
                <option value="">Semua Gender</option>
                <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
            </select>


            <button type="submit"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full sm:w-auto">Filter</button>

            <a href="{{ route('customers.index') }}"
                class="text-gray-600 underline hover:text-gray-800 w-full sm:w-auto text-center">Reset</a>
        </form>

        {{-- Tabel Data --}}
        @if ($customers->isEmpty())
            <p class="text-center text-gray-500">Belum ada data pelanggan.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Nama</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Gender</th>
                            <th class="py-3 px-4 text-left">Tanggal Lahir</th>
                            <th class="py-3 px-4 text-left">Kontak</th>
                            <th class="py-3 px-4 text-left">Booking</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $index => $cust)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">{{ $cust->name }}</td>
                                <td class="py-3 px-4">{{ $cust->email }}</td>
                                <td class="py-3 px-4">{{ ucfirst($cust->gender ?? '-') }}</td>
                                <td class="py-3 px-4">
                                    {{ $cust->birthdate ? \Carbon\Carbon::parse($cust->birthdate)->format('d-m-Y') : '-' }}
                                </td>
                                <td class="py-3 px-4">{{ $cust->phone ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $cust->bookings_as_customer_count ?? 0 }}</td>
                                <td class="py-3 px-4 space-y-2">
                                    <button onclick="openEditModal(this)" data-id="{{ $cust->id }}"
                                        data-name="{{ $cust->name }}" data-email="{{ $cust->email }}"
                                        data-phone="{{ $cust->phone }}" data-gender="{{ $cust->gender }}"
                                        data-birthdate="{{ $cust->birthdate }}"
                                        class="block w-full text-center bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Edit
                                    </button>

                                    <form id="deleteForm-{{ $cust->id }}"
                                        action="{{ route('customers.destroy', $cust->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $cust->id }})"
                                            class="block w-full bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $customers->appends(request()->except('page'))->links() }}
            </div>
        @endif

        {{-- Modal Tambah --}}
        <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
                <button onclick="closeTambahModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>

                <h2 class="text-xl font-bold mb-4 text-center">Tambah Pelanggan Baru</h2>

                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Nama</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">No HP</label>
                        <input type="text" name="phone" class="w-full border rounded px-3 py-2">
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
                        <label class="block text-sm text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birthdate" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
                <button onclick="closeEditModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>

                <h2 class="text-xl font-bold mb-4 text-center">Edit Pelanggan</h2>

                <form id="editForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Nama</label>
                        <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Email</label>
                        <input type="email" name="email" id="editEmail" class="w-full border rounded px-3 py-2"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">No HP</label>
                        <input type="text" name="phone" id="editPhone" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Gender</label>
                        <select name="gender" id="editGender" class="w-full border rounded px-3 py-2">
                            <option value="">-- Pilih --</option>
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birthdate" id="editBirthdate"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Ganti Password (opsional)</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- SweetAlert dan Modal Script --}}
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
            function openTambahModal() {
                document.getElementById('modalTambah').classList.remove('hidden');
            }

            function closeTambahModal() {
                document.getElementById('modalTambah').classList.add('hidden');
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data pelanggan akan dihapus permanen.",
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

            function openEditModal(button) {
                const id = button.dataset.id;
                const name = button.dataset.name;
                const email = button.dataset.email;
                const phone = button.dataset.phone;
                const gender = button.dataset.gender;
                const birthdate = button.dataset.birthdate;

                document.getElementById('editForm').action = `/customers/${id}`;
                document.getElementById('editName').value = name;
                document.getElementById('editEmail').value = email;
                document.getElementById('editPhone').value = phone;
                document.getElementById('editGender').value = gender;
                document.getElementById('editBirthdate').value = birthdate;

                document.getElementById('modalEdit').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('modalEdit').classList.add('hidden');
            }
        </script>
    </div>
@endsection
