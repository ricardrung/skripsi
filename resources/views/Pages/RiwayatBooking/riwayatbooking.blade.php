@extends('Components.Layout.layout')

@section('content')
    <header class="bg-white shadow-sm pt-24">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-[#2c1a0f] text-center">Riwayat Booking</h1>
            <p class="mt-2 text-base md:text-lg text-gray-600 text-center">
                Lihat riwayat booking treatment Anda dan kelola statusnya.
            </p>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        {{-- Notifikasi --}}
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


        {{-- Pencarian --}}


        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari layanan..." value="{{ request('search') }}"
                class="p-2 border rounded w-full">

            <select name="status" class="p-2 pr-8 border rounded appearance-none bg-white">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>

            <select name="order" class="p-2 pr-8 border rounded appearance-none bg-white">
                <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Terlama</option>
            </select>

            <input type="date" name="start_date" value="{{ request('start_date') }}" class="p-2 border rounded"
                placeholder="Dari Tanggal">

            <input type="date" name="end_date" value="{{ request('end_date') }}" class="p-2 border rounded"
                placeholder="Sampai Tanggal">


            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">
                Filter
            </button>
            <a href="{{ route('booking.riwayat') }}" class="text-gray-600 underline hover:text-gray-800">
                Reset Filter
            </a>
        </form>
        @if (request('start_date') || request('end_date'))
            <p class="text-sm text-gray-600 mb-2">
                Menampilkan booking
                @if (request('start_date'))
                    dari <strong>{{ request('start_date') }}</strong>
                @endif
                @if (request('end_date'))
                    sampai <strong>{{ request('end_date') }}</strong>
                @endif
            </p>
        @endif


        {{-- @if (request('status') || request('order') || request('search'))
            <p class="text-sm text-gray-600 mb-2">
                Menampilkan data
                @if (request('status'))
                    <strong>dengan status {{ ucfirst(request('status')) }}</strong>
                @endif
                @if (request('order'))
                    (<strong>{{ request('order') == 'asc' ? 'Terlama ke Terbaru' : 'Terbaru ke Terlama' }}</strong>)
                @endif
                @if (request('search'))
                    | Cari: "<strong>{{ request('search') }}</strong>"
                @endif
            </p>
        @endif --}}


        {{-- Tabel Booking --}}
        <p class="mb-4 text-gray-600">Total Booking Anda: <strong>{{ $bookings->total() }}</strong></p>
        @if ($bookings->isEmpty())
            <p class="text-center text-gray-500">Belum ada riwayat booking.</p>
        @else
            <div class="overflow-x-auto shadow-lg rounded-lg">
                <table class="min-w-full bg-white rounded overflow-hidden">
                    <thead class="bg-yellow-500 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Layanan</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">Harga</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Therapist</th>
                            <th class="py-3 px-4 text-left">Durasi</th>
                            <th class="py-3 px-4 text-left">Dipesan Pada</th>
                            <th class="py-3 px-4 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bookingTable">
                        @foreach ($bookings as $index => $booking)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4">
                                    {{ ($bookings->currentPage() - 1) * $bookings->perPage() + $index + 1 }}</td>
                                <td class="py-3 px-4">{{ $booking->treatment->name }}</td>
                                <td class="py-3 px-4 capitalize">{{ $booking->treatment->category->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }},
                                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}
                                </td>
                                <td class="py-3 px-4">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    @if ($booking->status == 'selesai')
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Selesai</span>
                                    @elseif ($booking->status == 'menunggu')
                                        <span
                                            class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Menunggu</span>
                                    @elseif ($booking->status == 'batal')
                                        <span
                                            class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">{{ $booking->therapist->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $booking->treatment->duration_minutes ?? '-' }} menit</td>
                                <td class="py-3 px-4">
                                    {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}
                                </td>


                                {{-- <td class="py-3 px-4">
                                    @if ($booking->payment_status === 'sudah_bayar')
                                        <span
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Lunas</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Belum
                                            Bayar</span>
                                    @endif
                                </td> --}}

                                <td class="py-3 px-4 space-y-2">
                                    <button onclick="openDetailModal({{ $booking->id }})"
                                        class="inline-block w-full text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded hover:bg-blue-200 transition">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>

                                    @if ($booking->status == 'menunggu')
                                        <form id="cancelForm-{{ $booking->id }}" method="POST"
                                            action="{{ route('booking.cancel', $booking->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmCancel({{ $booking->id }})"
                                                class="inline-flex items-center justify-center w-full text-sm bg-red-100 text-red-700 px-3 py-1 rounded hover:bg-red-200 transition whitespace-nowrap">
                                                <i class="fas fa-times mr-1"></i> Batalkan
                                            </button>
                                        </form>
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

            <!-- Modal Detail Booking -->
            <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 w-full max-w-lg relative shadow-lg">
                    <button onclick="closeDetailModal()"
                        class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
                    <h2 class="text-2xl font-semibold mb-4 text-[#2c1a0f] text-center">Detail Booking</h2>

                    <div id="detailContent" class="text-left text-sm space-y-2">
                        <p><strong>Layanan:</strong> <span id="detailTreatment"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Kategori:</strong> <span id="detailKategori"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Tanggal:</strong> <span id="detailTanggal"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Jam:</strong> <span id="detailJam"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Durasi:</strong> <span id="detailDurasi"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Dipesan Pada:</strong> <span id="detailDipesan"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Therapist:</strong> <span id="detailTherapist"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Harga:</strong> <span id="detailHarga"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Metode Pembayaran:</strong> <span id="detailPembayaran"></span></p>
                        <hr class="my-2 border-gray-200">
                        <p><strong>Catatan:</strong> <span id="detailNote"></span></p>
                        <hr class="my-2 border-gray-200">
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Script Pencarian --}}
    <script>
        function confirmCancel(id) {
            Swal.fire({
                title: 'Yakin ingin membatalkan booking ini?',
                text: "Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, batalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('cancelForm-' + id).submit();
                }
            });
        }


        const bookings = @json($bookings->items());

        function openDetailModal(id) {
            const booking = bookings.find(b => b.id === id);
            if (!booking) return;

            const tanggal = new Date(booking.booking_date).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            const jamMulai = booking.booking_time.slice(0, 5);
            const durasi = booking.treatment.duration_minutes || 90;
            const jamSelesai = calculateEndTime(jamMulai, durasi);

            document.getElementById("detailTreatment").textContent = booking.treatment.name;
            document.getElementById("detailKategori").textContent = booking.treatment.category.name || '-';
            document.getElementById("detailTanggal").textContent = tanggal;
            document.getElementById("detailJam").textContent = `${jamMulai} - ${jamSelesai}`;
            document.getElementById("detailDurasi").textContent = `${durasi} menit`;
            const createdAt = new Date(booking.created_at).toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById("detailDipesan").textContent = createdAt;

            document.getElementById("detailStatus").textContent = booking.status;
            document.getElementById("detailTherapist").textContent = booking.therapist?.name || '-';
            document.getElementById("detailHarga").textContent = "Rp " + parseInt(booking.final_price).toLocaleString();
            document.getElementById("detailPembayaran").textContent = booking.payment_method === 'cash' ? 'Cash' :
                'Payment Gateway';
            document.getElementById("detailNote").textContent = booking.note || '-';

            document.getElementById("detailModal").classList.remove("hidden");
        }

        function closeDetailModal() {
            document.getElementById("detailModal").classList.add("hidden");
        }

        function calculateEndTime(startTime, duration) {
            const [hour, minute] = startTime.split(':').map(Number);
            const start = new Date();
            start.setHours(hour);
            start.setMinutes(minute + duration);
            return start.toTimeString().slice(0, 5);
        }
    </script>
@endsection
