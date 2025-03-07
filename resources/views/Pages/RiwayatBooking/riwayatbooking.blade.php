@extends('Components.Layout.layout')

@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#2c1a0f] text-center">Riwayat Booking</h1>
            <p class="mt-2 text-base md:text-lg text-gray-600 text-center">Lihat dan kelola riwayat booking layanan spa Anda.
            </p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        {{-- Pencarian --}}
        <div class="mt-8 mb-6">
            <input type="text" id="search" placeholder="Cari berdasarkan layanan..."
                class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500">
        </div>
        @php

        @endphp

        {{-- Cek jika tidak ada booking --}}
        @if (empty($bookings))
            <p class="text-center text-gray-500">Belum ada riwayat booking.</p>
        @else
            {{-- Tabel Riwayat Booking --}}
            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="min-w-full bg-white rounded-lg overflow-hidden">
                    <thead class="bg-yellow-500 text-white">
                        <tr>
                            <th class="py-3 px-2 md:px-4 text-left">#</th>
                            <th class="py-3 px-2 md:px-4 text-left">Layanan</th>
                            <th class="py-3 px-2 md:px-4 text-left">Tanggal</th>
                            <th class="py-3 px-2 md:px-4 text-left">Harga</th>
                            <th class="py-3 px-2 md:px-4 text-left">Status</th>
                            <th class="py-3 px-2 md:px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bookingTable">
                        @foreach ($bookings as $index => $booking)
                            <tr class="border-b hover:bg-gray-100 transition-all">
                                <td class="py-3 px-2 md:px-4">{{ $index + 1 }}</td>
                                <td class="py-3 px-2 md:px-4">{{ $booking['layanan'] }}</td>
                                <td class="py-3 px-2 md:px-4">
                                    {{ \Carbon\Carbon::parse($booking['tanggal'])->format('d M Y, H:i') }}</td>
                                <td class="py-3 px-2 md:px-4">Rp {{ number_format($booking['harga'], 0, ',', '.') }}</td>
                                <td class="py-3 px-2 md:px-4">
                                    @if ($booking['status'] == 'Selesai')
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Selesai</span>
                                    @elseif($booking['status'] == 'Pending')
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Pending</span>
                                    @elseif($booking['status'] == 'Dibatalkan')
                                        <span
                                            class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 md:px-4 flex space-x-2">
                                    <a href="#" class="text-blue-500 hover:underline">Detail</a>
                                    @if ($booking['status'] == 'Pending')
                                        <button onclick="confirmCancel('{{ $booking['id'] }}')"
                                            class="text-red-500 hover:underline">Batalkan</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Script Pencarian dan Batalkan --}}
    <script>
        // Fitur Pencarian
        const searchInput = document.getElementById('search');
        const bookingTable = document.getElementById('bookingTable');

        searchInput.addEventListener('keyup', function() {
            const filter = searchInput.value.toLowerCase();
            const rows = bookingTable.getElementsByTagName('tr');

            Array.from(rows).forEach(row => {
                const layanan = row.cells[1].textContent.toLowerCase();
                if (layanan.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endsection
