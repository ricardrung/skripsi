@extends('components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Membership</h1>

        {{-- Tombol Tambah Membership --}}
        <div class="flex justify-between mb-4">
            <button onclick="openTambahModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Membership
            </button>
        </div>

        @if ($memberships->isEmpty())
            <p class="text-center text-gray-500">Belum ada data membership.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Nama</th>
                            <th class="py-3 px-4 text-left">Syarat Belanja</th>
                            <th class="py-3 px-4 text-left">Diskon</th>
                            <th class="py-3 px-4 text-left">Untuk</th>
                            <th class="py-3 px-4 text-left">Catatan</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memberships as $index => $membership)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-4">{{ $membership->name }}</td>
                                <td class="py-3 px-4">Rp{{ number_format($membership->min_annual_spending) }}</td>
                                <td class="py-3 px-4">{{ $membership->discount_percent }}%</td>
                                <td class="py-3 px-4">
                                    @if ($membership->applies_to === 'all')
                                        Semua Treatment
                                    @else
                                        {{ $membership->applies_to }}
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $membership->benefit_note ?? '-' }}</td>
                                <td class="py-3 px-4 space-y-2">
                                    <button
                                        onclick="openEditModal({{ $membership->id }}, '{{ addslashes($membership->name) }}', {{ $membership->min_annual_spending }}, {{ $membership->discount_percent }}, '{{ $membership->applies_to }}', '{{ addslashes($membership->benefit_note) }}')"
                                        class="block bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 w-full">
                                        Edit
                                    </button>

                                    <form id="deleteForm-{{ $membership->id }}"
                                        action="{{ route('memberships.destroy', $membership->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete({{ $membership->id }})"
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

        {{-- Modal Tambah --}}
        {{-- Modal Tambah Membership --}}
        <div id="modalTambah" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
                <button onclick="closeTambahModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
                <h2 class="text-xl font-bold mb-4 text-center">Tambah Membership</h2>
                <form action="{{ route('memberships.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Nama Membership</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Syarat Belanja</label>
                        <input type="number" name="min_annual_spending" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Diskon (%)</label>
                        <input type="number" name="discount_percent" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Berlaku Untuk</label>
                        <select name="applies_to" class="w-full border rounded px-3 py-2" required>
                            <option value="all">Semua Treatment</option>
                            <option value="Body Treatment">Body Treatment</option>
                            <option value="Face Treatment">Face Treatment</option>
                            <option value="Reflexology">Reflexology</option>
                            <option value="Hair Treatment">Hair Treatment</option>
                            <option value="Treatment Packages">Treatment Packages</option>
                            <option value="A La Carte">A La Carte</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Catatan Benefit</label>
                        <input type="text" name="benefit_note" class="w-full border rounded px-3 py-2">
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
        {{-- Modal Edit Membership --}}
        <div id="modalEdit" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
                <button onclick="closeEditModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
                <h2 class="text-xl font-bold mb-4 text-center">Edit Membership</h2>

                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Nama Membership</label>
                        <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Syarat Belanja</label>
                        <input type="number" name="min_annual_spending" id="editSpending"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Diskon (%)</label>
                        <input type="number" name="discount_percent" id="editDiscount"
                            class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Berlaku Untuk</label>
                        <select name="applies_to"id="editAppliesTo" class="w-full border rounded px-3 py-2" required>
                            <option value="all">Semua Treatment</option>
                            <option value="Body Treatment">Body Treatment</option>
                            <option value="Face Treatment">Face Treatment</option>
                            <option value="Reflexology">Reflexology</option>
                            <option value="Hair Treatment">Hair Treatment</option>
                            <option value="Treatment Packages">Treatment Packages</option>
                            <option value="A La Carte">A La Carte</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block text-sm text-gray-700">Catatan Benefit</label>
                        <input type="text" name="benefit_note" id="editNote"
                            class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>


        {{-- SweetAlert --}}
        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: `{!! session('success') !!}`,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
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


            function openEditModal(id, name, minBelanja, diskon, appliesTo, note) {
                document.getElementById('editForm').action = '/memberships/' + id;
                document.getElementById('editName').value = name;
                document.getElementById('editSpending').value = minBelanja;
                document.getElementById('editDiscount').value = diskon;
                document.getElementById('editAppliesTo').value = appliesTo;
                document.getElementById('editNote').value = note;
                document.getElementById('modalEdit').classList.remove('hidden');
            }


            function closeEditModal() {
                document.getElementById('modalEdit').classList.add('hidden');
            }

            function confirmDelete(id) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data membership akan dihapus permanen.",
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
    </div>
@endsection
