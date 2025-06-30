@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Paket Treatment</h1>

        <div class="flex justify-between mb-4">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="openModal()">+ Tambah
                Paket</button>
        </div>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari nama paket..." value="{{ request('search') }}"
                class="p-2 border rounded w-full md:w-64">

            <select name="category" class="p-2 border rounded w-full sm:w-64 bg-white">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>



            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Filter</button>
            <a href="{{ route('treatments.index') }}" class="text-gray-600 underline hover:text-gray-800">Reset</a>
        </form>

        @if ($treatments->isEmpty())
            <p class="text-center text-gray-500">Belum ada data treatment.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white whitespace-nowrap">
                        <tr>
                            <th class="py-3 px-4 text-left">Nama Paket</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-left">Harga Normal</th>
                            <th class="py-3 px-4 text-left">Happy Hour</th>
                            <th class="py-3 px-4 text-left">Durasi</th>
                            <th class="py-3 px-4 text-left">Tipe Ruangan</th>
                            <th class="py-3 px-4 text-left">Deskripsi</th>
                            <th class="py-3 px-4 text-left">Promo</th>
                            <th class="py-3 px-4 text-left">Best Selling</th>
                            <th class="py-3 px-4 text-left">Video</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($treatments as $treatment)
                            <tr class="border-b hover:bg-gray-50 whitespace-nowrap">
                                <td class="py-3 px-4">{{ $treatment->name }}</td>
                                <td class="py-3 px-4">{{ $treatment->category->name ?? '-' }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($treatment->price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    {{ $treatment->happy_hour_price ? 'Rp ' . number_format($treatment->happy_hour_price, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-3 px-4">{{ $treatment->duration_minutes }} menit</td>
                                <td class="py-3 px-4">{{ $treatment->room_type ?? '-' }}</td>
                                <td class="py-3 px-4" title="{{ $treatment->description }}">
                                    {{ Str::limit($treatment->description, 40) }}
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full {{ $treatment->is_promo ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                        {{ $treatment->is_promo ? 'Promo' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full {{ $treatment->is_best_selling ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-600' }}">
                                        {{ $treatment->is_best_selling ? 'Best Selling' : 'Tidak' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if ($treatment->demo_video_url)
                                        <a href="{{ $treatment->demo_video_url }}" class="text-blue-600 underline"
                                            target="_blank">Tonton</a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full {{ $treatment->is_available ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $treatment->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 space-y-2">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 w-full"
                                        onclick="openModalEdit({{ $treatment->id }})">Edit</button>
                                    <form id="delete-treatment-{{ $treatment->id }}" method="POST"
                                        action="{{ route('treatments.destroy', $treatment->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteTreatment({{ $treatment->id }})"
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
                {{ $treatments->appends(request()->except('page'))->links() }}
            </div>
        @endif


        <div id="modal"
            class="fixed inset-0 flex items-center justify-center bg-opacity-50 hidden z-50 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
                <h3 class="text-lg font-semibold mb-4">Tambah/Edit Paket</h3>

                <form id="treatmentForm" method="POST" action="{{ route('treatments.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="treatment_id">
                    <input type="hidden" name="_method" id="form_method" value="POST">

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
                        <option value="15">15 Menit</option>
                        <option value="20">20 Menit</option>
                        <option value="30">30 Menit</option>
                        <option value="45">45 Menit</option>
                        <option value="60">60 Menit</option>
                        <option value="90">90 Menit</option>
                        <option value="150">150 Menit</option>
                    </select>
                    <!-- Pilih Tipe Ruangan Treatment -->

                    <select name="room_type" id="room_type" class="w-full p-2 border rounded mb-2" required>
                        <option value="">-- Pilih Tipe --</option>
                        <option value="single">Single</option>
                        <option value="double">Double</option>
                        <option value="both">Bisa Single & Double</option>
                        <option value="hair">Hair</option>
                        <option value="triple">Bisa Single & Double & Reflexology</option>
                    </select>


                    <textarea name="description" placeholder="Deskripsi" class="border rounded p-2 w-full mb-2"></textarea>
                    <input type="url" name="demo_video_url" placeholder="Link Video Demo"
                        class="border rounded p-2 w-full mb-2">
                    <div class="flex items-center mb-2">
                        <input type="hidden" name="is_promo" value="0"> <!-- default jika tidak dicentang -->
                        <input type="checkbox" name="is_promo" id="is_promo" value="1" class="mr-2">
                        <label for="is_promo">Masukkan ke dalam Promo</label>
                    </div>
                    <div class="flex items-center mb-2">
                        <input type="hidden" name="is_best_selling" value="0">
                        <input type="checkbox" name="is_best_selling" id="is_best_selling" value="1"
                            class="mr-2">
                        <label for="is_best_selling">Tandai sebagai Best Selling</label>
                    </div>
                    <div class="flex items-center mb-2">
                        <input type="hidden" name="is_available" value="0"> {{-- default jika tidak dicentang --}}
                        <input type="checkbox" name="is_available" id="is_available" value="1" class="mr-2"
                            checked>
                        <label for="is_available">Tersedia untuk Dipesan</label>
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

    {{-- Script dan SweetAlert tetap pakai kode sebelumnya --}}
    <script>
        function openModal() {
            // Reset form
            const form = document.getElementById("treatmentForm");
            form.reset();
            form.is_promo.checked = false;
            form.is_best_selling.checked = false;
            form.is_available.checked = true;


            // Set action ke store
            form.action = "{{ route('treatments.store') }}";
            document.getElementById('form_method').value = "POST";

            // Tampilkan modal
            document.getElementById("modal").classList.remove("hidden");
        }

        function openModalEdit(id) {
            fetch(`/treatments/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    const form = document.getElementById('treatmentForm');

                    // Isi field
                    form.name.value = data.name;
                    form.category_id.value = data.category_id;
                    form.price.value = data.price;
                    form.happy_hour_price.value = data.happy_hour_price ?? '';
                    form.duration_minutes.value = data.duration_minutes;
                    form.description.value = data.description ?? '';
                    form.demo_video_url.value = data.demo_video_url ?? '';
                    form.is_promo.checked = data.is_promo;
                    form.is_best_selling.checked = data.is_best_selling;
                    form.is_available.checked = data.is_available;
                    form.room_type.value = data.room_type;



                    // Set ke mode UPDATE
                    form.action = `/treatments/${id}`;
                    document.getElementById('form_method').value = "PUT";

                    document.getElementById("modal").classList.remove("hidden");
                })
                .catch(error => {
                    console.error('Gagal fetch treatment:', error);
                    alert('Gagal mengambil data treatment untuk diedit.');
                });
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }
    </script>

    {{-- SweetAlert untuk alert sukses --}}
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
@endsection
