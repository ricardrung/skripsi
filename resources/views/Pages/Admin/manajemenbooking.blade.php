@extends('Components.Layout.layoutadmin')
@section('content')
    <div class="p-6">
        <!-- Header -->
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Booking</h1>

        @php
            // Data dummy therapist
            $therapists = [
                (object) ['id' => 1, 'name' => 'Therapist A'],
                (object) ['id' => 2, 'name' => 'Therapist B'],
            ];

            // Data dummy booking
            $bookings = [
                (object) [
                    'id' => 1,
                    'customer_name' => 'John Doe',
                    'package' => 'Paket Spa Gold',
                    'date' => '2025-03-10',
                    'therapist' => (object) ['name' => 'Therapist A'],
                    'status' => 'pending',
                    'status_color' => 'bg-yellow-500',
                    'payment_status' => 'Belum Lunas',
                ],
                (object) [
                    'id' => 2,
                    'customer_name' => 'Jane Smith',
                    'package' => 'Body Massage',
                    'date' => '2025-03-11',
                    'therapist' => (object) ['name' => 'Therapist B'],
                    'status' => 'confirmed',
                    'status_color' => 'bg-green-500',
                    'payment_status' => 'Lunas',
                ],
            ];
        @endphp

        <!-- Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <input type="date" id="filter-date" class="border p-2 rounded-lg w-full md:w-auto">
            <select id="filter-status" class="border p-2 rounded-lg w-full md:w-auto">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Dikonfirmasi</option>
                <option value="completed">Selesai</option>
                <option value="canceled">Dibatalkan</option>
            </select>
            <select id="filter-therapist" class="border p-2 rounded-lg w-full md:w-auto">
                <option value="">Semua Therapist</option>
                @foreach ($therapists as $therapist)
                    <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                @endforeach
            </select>
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg w-full md:w-auto">Filter</button>
        </div>

        <!-- Tabel Daftar Booking -->
        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-3">Nama</th>
                        <th class="p-3">Paket</th>
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Therapist</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                        <tr class="border-b">
                            <td class="p-3">{{ $booking->customer_name }}</td>
                            <td class="p-3">{{ $booking->package }}</td>
                            <td class="p-3">{{ $booking->date }}</td>
                            <td class="p-3">{{ $booking->therapist->name ?? 'Belum Dipilih' }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-lg text-white {{ $booking->status_color }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="p-3 flex flex-col sm:flex-row gap-2">
                                <button onclick="showDetail({{ $booking->id }})"
                                    class="bg-gray-600 text-white px-3 py-1 rounded-lg w-full sm:w-auto">Detail</button>
                                @if ($booking->status === 'pending')
                                    <button
                                        class="bg-green-600 text-white px-3 py-1 rounded-lg w-full sm:w-auto">Konfirmasi</button>
                                    <button
                                        class="bg-red-600 text-white px-3 py-1 rounded-lg w-full sm:w-auto">Tolak</button>
                                @endif
                                @if ($booking->status === 'confirmed')
                                    <button
                                        class="bg-yellow-600 text-white px-3 py-1 rounded-lg w-full sm:w-auto">Reschedule</button>
                                    <button
                                        class="bg-red-500 text-white px-3 py-1 rounded-lg w-full sm:w-auto">Batal</button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Booking -->
    <div id="modal-detail"
        class="fixed inset-0 flex items-center justify-center  bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative max-h-screen overflow-auto">
            <h2 class="text-xl font-bold mb-4">Detail Booking</h2>
            <div id="booking-detail"></div>
            <button onclick="closeModal()" class="mt-4 bg-gray-600 text-white px-4 py-2 rounded-lg">Tutup</button>
        </div>
    </div>

    <script>
        function showDetail(id) {
            // Data dummy untuk modal
            const dataDummy = {
                1: {
                    customer_name: 'John Doe',
                    package: 'Paket Spa Gold',
                    date: '2025-03-10',
                    therapist: 'Therapist A',
                    status: 'Pending',
                    payment_status: 'Belum Lunas'
                },
                2: {
                    customer_name: 'Jane Smith',
                    package: 'Body Massage',
                    date: '2025-03-11',
                    therapist: 'Therapist B',
                    status: 'Dikonfirmasi',
                    payment_status: 'Lunas'
                }
            };

            const data = dataDummy[id];

            document.getElementById('booking-detail').innerHTML = `
            <p><strong>Nama:</strong> ${data.customer_name}</p>
            <p><strong>Paket:</strong> ${data.package}</p>
            <p><strong>Tanggal:</strong> ${data.date}</p>
            <p><strong>Therapist:</strong> ${data.therapist}</p>
            <p><strong>Status:</strong> ${data.status}</p>
            <p><strong>Pembayaran:</strong> ${data.payment_status}</p>
        `;
            document.getElementById('modal-detail').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal-detail').classList.add('hidden');
        }
    </script>
@endsection
