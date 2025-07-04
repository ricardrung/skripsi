@extends('components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Pembayaran</h1>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari nama customer..." value="{{ request('search') }}"
                class="p-2 border rounded w-full sm:w-64 text-sm">

            <select name="payment_status" class="p-2 border rounded bg-white w-full sm:w-48 text-sm">
                <option value="">Semua Status</option>
                <option value="belum_bayar" {{ request('payment_status') == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar
                </option>
                <option value="sudah_bayar" {{ request('payment_status') == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar
                </option>
            </select>

            <select name="therapist_id" class="p-2 border rounded bg-white w-full sm:w-48 text-sm">
                <option value="">Semua Therapist</option>
                @foreach ($therapists as $therapist)
                    <option value="{{ $therapist->id }}" {{ request('therapist_id') == $therapist->id ? 'selected' : '' }}>
                        {{ $therapist->name }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="start_date" class="p-2 border rounded text-sm" value="{{ request('start_date') }}">
            <input type="date" name="end_date" class="p-2 border rounded text-sm" value="{{ request('end_date') }}">

            <button type="submit"
                class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full sm:w-auto text-sm">
                Filter
            </button>

            <a href="{{ route('booking.manajemen.pembayaran') }}"
                class="text-gray-600 underline hover:text-gray-800 w-full sm:w-auto text-center text-sm">
                Reset
            </a>
        </form>
        {{-- download --}}
        <form method="GET" action="{{ route('laporan.pemasukan.download') }}"
            class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="hidden" name="search" value="{{ request('search') }}">
            <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
            <input type="hidden" name="therapist_id" value="{{ request('therapist_id') }}">
            <input type="hidden" name="start_date" class="p-2 border rounded text-sm" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" class="p-2 border rounded text-sm" value="{{ request('end_date') }}">

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">
                Download Laporan (CSV)
            </button>
        </form>



        {{-- Tabel Pembayaran --}}
        @if ($bookings->isEmpty())
            <p class="text-center text-gray-500">Belum ada data pembayaran.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white whitespace-nowrap">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Booking ID</th>
                            <th class="py-3 px-4 text-left">Customer</th>
                            <th class="py-3 px-4 text-left">Therapist</th>
                            <th class="py-3 px-4 text-left">Kontak</th>
                            <th class="py-3 px-4 text-left">Treatment</th>
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Total Bayar</th>
                            <th class="py-3 px-4 text-left">Metode</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Status Booking</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $index => $booking)
                            <tr class="border-b hover:bg-gray-50 whitespace-nowrap">
                                <td class="py-3 px-4">
                                    {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $index + 1 }}
                                </td>
                                <td class="py-3 px-4">#{{ $booking->id }}</td>
                                <td class="py-3 px-4">
                                    {{ $booking->user->name ?? ($booking->guest_name ?? '-') }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $booking->therapist->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $booking->user->phone ?? ($booking->guest_phone ?? '-') }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $booking->treatment->name ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ $booking->booking_date }} ({{ $booking->booking_time }})
                                </td>
                                <td class="py-3 px-4 font-semibold text-green-700">
                                    Rp{{ number_format($booking->final_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 capitalize">
                                    {{ $booking->payment_method ?? '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full
                                    @if ($booking->payment_status === 'sudah_bayar') bg-green-100 text-green-700
                                    @else bg-red-100 text-red-700 @endif">
                                        {{ ucfirst($booking->payment_status) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full
                                        @if ($booking->status === 'selesai') bg-green-100 text-green-700
                                        @elseif ($booking->status === 'menunggu') bg-yellow-100 text-yellow-700
                                        @elseif ($booking->status === 'sedang') bg-blue-100 text-blue-700
                                        @elseif ($booking->status === 'batal') bg-red-100 text-red-700
                                        @else bg-gray-200 text-gray-600 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>

                                <td class="py-3 px-4">
                                    @if ($booking->payment_status === 'belum_bayar' && $booking->status !== 'batal')
                                        <form id="formLunas-{{ $booking->id }}"
                                            action="{{ route('booking.update.status.bayar', $booking->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button"
                                                class="block w-full bg-green-500 text-white px-3 py-1 text-sm rounded hover:bg-green-600"
                                                onclick="konfirmasiLunas({{ $booking->id }})">
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-sm text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $bookings->appends(request()->except('page'))->links() }}
            </div>
        @endif

        {{-- SweetAlert --}}
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

        @if (session('error'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: `{!! session('error') !!}`,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

    </div>
    <script>
        function konfirmasiLunas(id) {
            Swal.fire({
                title: 'Tandai sudah lunas?',
                text: "Pastikan pembayaran telah diterima.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, tandai lunas',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formLunas-' + id).submit();
                }
            });
        }
    </script>

@endsection
