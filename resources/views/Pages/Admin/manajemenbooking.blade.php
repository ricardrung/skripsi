@extends('Components.Layout.layoutadmin')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Booking Treatment</h1>

        {{-- Filter --}}
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" placeholder="Cari pelanggan..." value="{{ request('search') }}"
                class="p-2 border rounded w-full sm:w-64">

            <select name="status" class="p-2 border rounded bg-white w-full sm:w-48">
                <option value="">Semua Status</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="sedang" {{ request('status') == 'sedang' ? 'selected' : '' }}>Sedang</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Dibatalkan</option>
            </select>
            <select name="order" class="p-2 border rounded bg-white w-full sm:w-48">
                <option value="">Urutkan</option>
                <option value="id_desc" {{ request('order') == 'id_desc' ? 'selected' : '' }}>ID Terbaru</option>
                <option value="id_asc" {{ request('order') == 'id_asc' ? 'selected' : '' }}>ID Terlama</option>
            </select>
            <select name="min_rating" class="p-2 border rounded bg-white w-full sm:w-48">
                <option value="">Semua Rating</option>
                <option value="5" {{ request('min_rating') == '5' ? 'selected' : '' }}>⭐ 5</option>
                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>⭐ 4</option>
                <option value="3" {{ request('min_rating') == '3' ? 'selected' : '' }}>⭐ 3</option>
                <option value="2" {{ request('min_rating') == '2' ? 'selected' : '' }}>⭐ 2</option>
                <option value="1" {{ request('min_rating') == '1' ? 'selected' : '' }}>⭐ 1</option>
            </select>


            <!-- Filter Creator -->
            <select name="creator_id" class="p-2 border rounded bg-white w-full sm:w-64">
                <option value="">Semua Creator</option>
                @foreach ($creators as $creator)
                    <option value="{{ $creator->id }}" {{ request('creator_id') == $creator->id ? 'selected' : '' }}>
                        {{ $creator->name }}
                    </option>
                @endforeach
            </select>


            <input type="date" name="start_date" value="{{ request('start_date') }}"
                class="p-2 border rounded w-full sm:w-44">
            <input type="date" name="end_date" value="{{ request('end_date') }}"
                class="p-2 border rounded w-full sm:w-44">

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 w-full sm:w-auto">
                Filter
            </button>

            <a href="{{ route('booking.admin') }}"
                class="text-gray-600 underline hover:text-gray-800 w-full sm:w-auto text-center">
                Reset
            </a>
        </form>


        @if ($bookings->isEmpty())
            <p class="text-center text-gray-500">Belum ada data booking.</p>
        @else
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full table-auto">
                    <thead class="bg-yellow-500 text-white whitespace-nowrap">
                        <tr>
                            <th class="py-3 px-4 text-left">No</th>
                            <th class="py-3 px-4 text-left">Booking ID</th>
                            <th class="py-3 px-4 text-left">Pelanggan</th>
                            <th class="py-3 px-4 text-left">Kontak</th>
                            <th class="py-3 px-4 text-left">Layanan</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-left">Tanggal</th>
                            <th class="py-3 px-4 text-left">JamMulai</th>
                            <th class="py-3 px-4 text-left">Keterangan Waktu</th>
                            <th class="py-3 px-4 text-left">JamSelesai</th>
                            <th class="py-3 px-4 text-left">Durasi</th>
                            <th class="py-3 px-4 text-left">Therapist</th>
                            <th class="py-3 px-4 text-left">Harga</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Dibuat Oleh</th>
                            <th class="py-3 px-4 text-left">Feedback</th>
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
                                <td class="py-3 px-4">{{ $booking->user->name ?? ($booking->guest_name ?? '-') }}</td>
                                <td class="py-3 px-4">{{ $booking->user->phone ?? ($booking->guest_phone ?? '-') }}</td>
                                <td class="py-3 px-4">{{ $booking->treatment->name }}</td>
                                <td class="py-3 px-4">{{ $booking->treatment->category->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                </td>
                                <td class="py-3 px-4">{{ $booking->booking_time }}</td>
                                @php
                                    $start = \Carbon\Carbon::parse($booking->booking_time);
                                    $end = $start->copy()->addMinutes($booking->treatment->duration_minutes);
                                @endphp
                                @php
                                    $bookingDateTime = \Carbon\Carbon::parse(
                                        $booking->booking_date . ' ' . $booking->booking_time,
                                    );
                                    $now = \Carbon\Carbon::now();
                                    $diffMinutes = $now->diffInMinutes($bookingDateTime, false);

                                    if ($diffMinutes > 0) {
                                        $jam = floor($diffMinutes / 60);
                                        $menit = $diffMinutes % 60;
                                        $relativeTime = 'Mulai dalam ';
                                        if ($jam > 0) {
                                            $relativeTime .= $jam . ' jam ';
                                        }
                                        if ($menit > 0) {
                                            $relativeTime .= $menit . ' menit';
                                        }
                                    } elseif ($diffMinutes === 0) {
                                        $relativeTime = 'Sedang dimulai sekarang';
                                    } else {
                                        $diffMinutes = abs($diffMinutes);
                                        $jam = floor($diffMinutes / 60);
                                        $menit = $diffMinutes % 60;
                                        $relativeTime = 'Sudah lewat ';
                                        if ($jam > 0) {
                                            $relativeTime .= $jam . ' jam ';
                                        }
                                        if ($menit > 0) {
                                            $relativeTime .= $menit . ' menit';
                                        }
                                    }
                                @endphp

                                <td class="py-3 px-4 text-sm italic text-gray-700">
                                    {{ trim($relativeTime) }}
                                </td>


                                <td class="py-3 px-4">{{ $end->format('H:i:s') }}</td>
                                <td class="py-3 px-4">{{ $booking->treatment->duration_minutes }} menit</td>
                                <td class="py-3 px-4">{{ $booking->therapist->name ?? '-' }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="px-3 py-1 text-sm rounded-full
                                        @if ($booking->status === 'selesai') bg-green-100 text-green-700
                                        @elseif ($booking->status === 'menunggu')
                                            bg-yellow-100 text-yellow-700
                                        @elseif ($booking->status === 'sedang')
                                            bg-blue-100 text-blue-700
                                        @elseif ($booking->status === 'batal')
                                            bg-red-100 text-red-700
                                        @else
                                            bg-gray-200 text-gray-600 @endif">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">{{ $booking->creator->name ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    @if ($booking->feedback)
                                        <div class="flex flex-col gap-1">
                                            <div class="text-yellow-500">
                                                @for ($i = 1; $i <= $booking->feedback->rating; $i++)
                                                    ⭐
                                                @endfor
                                            </div>
                                            <div class="text-gray-600 text-sm italic">
                                                "{{ $booking->feedback->comment ?: 'Tidak ada komentar.' }}"
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Belum ada</span>
                                    @endif
                                </td>

                                <td class="py-3 px-4 space-y-2">
                                    {{-- Tombol Detail --}}
                                    <a href="#" onclick="showDetail({{ $booking->toJson() }})"
                                        class="block text-center bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                        Detail
                                    </a>

                                    {{-- Tombol Batalkan (hanya saat status menunggu) --}}
                                    @if ($booking->status === 'menunggu')
                                        <form id="cancelForm-{{ $booking->id }}" method="POST"
                                            action="{{ route('booking.cancel', $booking->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="block w-full bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                                onclick="confirmCancelBooking({{ $booking->id }})">
                                                Batalkan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- ❗ Hanya tampil jika status BUKAN batal --}}
                                    @if ($booking->status !== 'batal')
                                        {{-- Tandai Sedang --}}
                                        @if ($booking->status === 'menunggu')
                                            <form method="POST"
                                                action="{{ route('booking.updateStatus', ['id' => $booking->id, 'status' => 'sedang']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="block w-full bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                                    Tandai Sedang
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tandai Selesai --}}
                                        @if ($booking->status === 'sedang')
                                            <form method="POST"
                                                action="{{ route('booking.updateStatus', ['id' => $booking->id, 'status' => 'selesai']) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="block w-full bg-emerald-600 text-white px-3 py-1 rounded hover:bg-emerald-700 text-sm">
                                                    Tandai Selesai
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Tandai Lunas (jika belum lunas) --}}
                                        @if ($booking->payment_status !== 'sudah_bayar')
                                            <form method="POST"
                                                action="{{ route('booking.markAsPaid', $booking->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="block w-full bg-sky-700 text-white px-3 py-1 rounded hover:bg-sky-800 text-sm">
                                                    Tandai Lunas
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    {{-- //kirim wa --}}
                                    @php
                                        $nomor =
                                            '62' . ltrim($booking->user->phone ?? ($booking->guest_phone ?? ''), '0');
                                    @endphp

                                    <a href="https://wa.me/{{ $nomor }}" target="_blank"
                                        class="block w-full bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm text-center">
                                        WhatsApp
                                    </a>

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $bookings->appends(request()->except('page'))->links() }}
            </div>
        @endif
    </div>

    {{-- ✅ Alert sukses --}}
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

    {{--  Fungsi konfirmasi pembatalan --}}
    <script>
        function confirmCancelBooking(id) {
            event.preventDefault(); // agar tidak langsung submit
            Swal.fire({
                title: 'Yakin ingin membatalkan booking?',
                text: "Tindakan ini tidak dapat dibatalkan.",
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




        function showDetail(booking) {
            // Isi data
            // document.getElementById('detailTreatment').textContent = booking.treatment?.name ?? '-';
            // document.getElementById('detailKategori').textContent = booking.treatment?.category?.name ?? '-';
            // document.getElementById('detailTanggal').textContent = booking.booking_date ?? '-';
            // document.getElementById('detailJam').textContent = booking.booking_time ?? '-';
            // document.getElementById('detailDurasi').textContent = (booking.treatment?.duration_minutes ?? '-') + ' menit';
            // document.getElementById('detailHarga').textContent = 'Rp ' + parseInt(booking.final_price).toLocaleString();
            // document.getElementById('detailStatus').textContent = booking.status ?? '-';
            document.getElementById('detailPelanggan').textContent = booking.user?.name ?? booking.guest_name ?? '-';
            // document.getElementById('detailTherapist').textContent = booking.therapist?.name ?? '-';
            // document.getElementById('detailCreator').textContent = booking.creator?.name ?? '-';
            document.getElementById('detailPaymentMethod').textContent = (booking.payment_method === 'cash') ? 'Cash' :
                'Payment Gateway';
            document.getElementById('detailPaymentStatus').textContent = (booking.payment_status === 'sudah_bayar') ?
                'Sudah Bayar' : 'Belum Bayar';
            document.getElementById("detailRoomType").textContent = booking.room_type ? booking.room_type.charAt(0)
                .toUpperCase() + booking.room_type.slice(1) : '-';
            // Format tanggal pesan dibuat (created_at)
            const createdAt = new Date(booking.created_at).toLocaleString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('detailCreatedAt').textContent = createdAt;
            document.getElementById('detailNote').textContent = booking.note ?? '-';
            document.getElementById('detailPhone').textContent = booking.user?.phone ?? booking.guest_phone ?? '-';
            const endTime = new Date(`1970-01-01T${booking.booking_time}`);
            endTime.setMinutes(endTime.getMinutes() + (booking.treatment?.duration_minutes ?? 0));
            // document.getElementById('detailEndTime').textContent = endTime.toTimeString().slice(0, 5);


            // Tampilkan modal
            document.getElementById('detailModal').classList.remove('hidden');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>

    {{-- Modal Detail Booking --}}
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
            <button onclick="closeDetailModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
            <h2 class="text-2xl font-semibold mb-4 text-center text-[#2c1a0f]">Detail Booking</h2>

            <div class="space-y-2 text-sm text-gray-800">
                <p><strong>Pelanggan:</strong> <span id="detailPelanggan"></span></p>
                <hr class="my-2 border-gray-200">
                <p><strong>Kontak Pelanggan:</strong> <span id="detailPhone"></span></p>
                <hr class="my-2 border-gray-200">
                <p><strong>Metode Pembayaran:</strong> <span id="detailPaymentMethod"></span></p>
                <hr class="my-2 border-gray-200">
                <p><strong>Status Pembayaran:</strong> <span id="detailPaymentStatus"></span></p>
                <hr class="my-2 border-gray-200">
                <p><strong>Tipe Ruangan:</strong> <span id="detailRoomType"></span></p>
                <hr class="my-2 border-gray-200">
                <p><strong>Dipesan Pada:</strong> <span id="detailCreatedAt"></span></p>
                <hr class="my-2 border-gray-200">
                <p><strong>Catatan:</strong> <span id="detailNote"></span></p>
                <hr class="my-2 border-gray-200">
            </div>
        </div>
    </div>


@endsection
