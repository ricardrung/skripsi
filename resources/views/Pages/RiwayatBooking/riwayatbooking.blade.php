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
                <option value="sedang" {{ request('status') == 'sedang' ? 'selected' : '' }}>Sedang</option>
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
            <div class="grid grid-cols-1 gap-6">
                @foreach ($bookings as $booking)
                    <div class="bg-white shadow-md rounded-lg p-6 space-y-2 relative">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-[#2c1a0f]">{{ $booking->treatment->name }}</h2>
                                <p class="text-sm text-gray-500 capitalize">
                                    {{ $booking->treatment->category->name ?? '-' }}</p>
                            </div>
                            <div>
                                @if ($booking->status == 'selesai')
                                    <div
                                        class="flex items-start gap-2 bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded-md text-sm mt-2">
                                        <i class="fas fa-check-circle mt-1 text-green-500"></i>
                                        <div class="leading-snug">
                                            <strong>Treatment selesai</strong><br>
                                            Terima kasih!
                                        </div>
                                    </div>
                                @elseif ($booking->status == 'menunggu')
                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm">Harap datang
                                        tepat Waktu!</span>
                                @elseif ($booking->status == 'batal')
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">Dibatalkan</span>
                                @elseif ($booking->status == 'sedang')
                                    <div
                                        class="flex items-start gap-2 bg-blue-50 border border-blue-200 text-blue-800 px-3 py-2 rounded-md text-sm mt-2">
                                        <i class="fas fa-spinner fa-spin mt-1 text-blue-500"></i>
                                        <div class="leading-snug">
                                            <strong>Treatment berlangsung</strong><br>
                                        </div>
                                    </div>
                                @endif
                            </div>

                        </div>
                        <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                        </p>
                        <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</p>
                        <p><strong>Harga:</strong> Rp {{ number_format($booking->final_price, 0, ',', '.') }}</p>
                        <p><strong>Therapist:</strong> {{ $booking->therapist->name ?? '-' }}</p>
                        <p><strong>Durasi:</strong> {{ $booking->treatment->duration_minutes ?? '-' }} menit</p>
                        <p><strong>Status Bayar:</strong>
                            @if ($booking->payment_status === 'sudah_bayar')
                                <span class="text-green-600 font-semibold">Lunas</span>
                            @else
                                <span class="text-red-600 font-semibold">Belum Bayar</span>
                            @endif
                        </p>
                        <p><strong>Metode Pembayaran:</strong>
                            @if ($booking->payment_method === 'cash')
                                Tunai
                            @elseif ($booking->payment_method === 'gateway')
                                Payment Gateway (Midtrans)
                            @else
                                -
                            @endif
                        </p>

                        <p><strong>Dipesan Pada:</strong>
                            {{ \Carbon\Carbon::parse($booking->created_at)->format('d M Y H:i') }}</p>

                        @if (
                            $booking->payment_status === 'belum_bayar' &&
                                $booking->status !== 'batal' &&
                                $booking->payment_method === 'gateway')
                            <p><strong>Belum Bayar ? :</strong>
                                <a href="{{ route('booking.payAgain', $booking->id) }}"
                                    class="text-blue-500 btn btn-primary">
                                    Bayar Sekarang
                                </a>
                            </p>
                        @endif

                        @if ($booking->status === 'selesai' && !$booking->feedback)
                            <button onclick="openFeedbackModal({{ $booking->id }})"
                                class="bg-yellow-500 text-white px-4 py-1 rounded hover:bg-yellow-600 text-sm">
                                Beri Feedback
                            </button>
                        @elseif ($booking->status === 'selesai' && $booking->feedback)
                            <span class="text-green-600 text-sm">Feedback Terkirim</span>
                        @endif




                        <div class="flex flex-wrap gap-2 mt-4">
                            <button onclick="openDetailModal({{ $booking->id }})"
                                class="bg-blue-100 text-blue-700 px-4 py-2 rounded text-sm hover:bg-blue-200 transition">
                                <i class="fas fa-eye"></i> Detail
                            </button>

                            @if ($booking->status == 'menunggu')
                                <form id="cancelForm-{{ $booking->id }}" method="POST"
                                    action="{{ route('booking.cancel.customer', $booking->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmCancel({{ $booking->id }})"
                                        class="bg-red-100 text-red-700 px-4 py-2 rounded text-sm hover:bg-red-200 transition">
                                        <i class="fas fa-times"></i> Batalkan
                                    </button>
                                </form>
                            @endif
                            {{-- MINIMAL CANCEL  --}}
                            @if ($booking->status == 'menunggu')
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $bookingDateTime = \Carbon\Carbon::parse(
                                        $booking->booking_date . ' ' . $booking->booking_time,
                                    );
                                    $bisaDibatalkan = $now->diffInMinutes($bookingDateTime, false) >= 60;
                                @endphp

                                @if ($bisaDibatalkan)
                                    <p class="text-sm text-yellow-700 mt-2">
                                        <i class="fas fa-info-circle"></i> Booking hanya bisa dibatalkan maksimal <strong>1
                                            jam sebelum</strong> waktu treatment.
                                    </p>
                                @else
                                    <p class="text-sm text-red-600 mt-2">
                                        <i class="fas fa-ban"></i> Waktu pembatalan telah lewat. Anda tidak bisa membatalkan
                                        booking ini.
                                    </p>
                                @endif
                            @endif

                        </div>
                    </div>
                @endforeach
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
                        <p><strong>Tipe Ruangan:</strong> <span id="detailRoomType"></span></p>
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


    {{-- //feedback --}}
    <!-- Tailwind Feedback Modal -->
    <div id="feedbackModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
            <button onclick="closeFeedbackModal()"
                class="absolute top-2 right-3 text-gray-500 hover:text-black text-lg">✕</button>

            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="booking_id" id="modal-booking-id">

                <h2 class="text-xl font-semibold text-[#2c1a0f]">📝 Feedback Anda</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Rating</label>
                    <select name="rating"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-[#2c1a0f] focus:border-[#2c1a0f]"
                        required>
                        <option value="">Pilih Rating</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} ⭐</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Komentar</label>
                    <textarea name="comment" rows="3"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-[#2c1a0f] focus:border-[#2c1a0f]"
                        placeholder="Ceritakan pengalaman Anda..."></textarea>
                </div>

                <div class="text-right">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded">
                        Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openFeedbackModal(bookingId) {
            document.getElementById('modal-booking-id').value = bookingId;
            document.getElementById('feedbackModal').classList.remove('hidden');
            document.getElementById('feedbackModal').classList.add('flex');
        }

        function closeFeedbackModal() {
            document.getElementById('feedbackModal').classList.add('hidden');
            document.getElementById('feedbackModal').classList.remove('flex');
        }
    </script>


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
            document.getElementById("detailRoomType").textContent = booking.room_type ? booking.room_type.charAt(0)
                .toUpperCase() + booking.room_type.slice(1) : '-';

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
