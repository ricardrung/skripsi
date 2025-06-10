@extends('Components.Layout.layoutadmin')
@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard Admin</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <a href="{{ route('booking.admin') }}">
                <div class="bg-white p-4 shadow-md rounded-lg hover:bg-blue-50 transition">
                    <h2 class="text-lg font-semibold text-gray-700">Booking Hari Ini</h2>
                    <p class="text-3xl font-bold text-blue-600">{{ $todayBookings }}</p>
                </div>
            </a>
            <a href="{{ route('booking.admin') }}">
                <div class="bg-white p-4 shadow-md rounded-lg hover:bg-blue-50 transition">
                    <h2 class="text-lg font-semibold text-gray-700">Booking Minggu Ini</h2>
                    <p class="text-3xl font-bold text-green-600">{{ $weeklyBookings }}</p>
                </div>
            </a>
            <a href="{{ route('booking.admin') }}">
                <div class="bg-white p-4 shadow-md rounded-lg hover:bg-blue-50 transition">
                    <h2 class="text-lg font-semibold text-gray-700">Booking Bulan Ini</h2>
                    <p class="text-3xl font-bold text-red-600">{{ $monthlyBookings }}</p>
                </div>
            </a>
        </div>
        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Notifikasi Terbaru</h2>
            <ul class="space-y-3 text-sm sm:text-base">
                @foreach ($notifications as $notif)
                    <li class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center gap-x-2">
                        <div class="flex items-center gap-1">
                            <span class="text-gray-600">📌</span>
                            <a href="{{ route('booking.admin') }}" class="text-blue-700 hover:underline break-all">
                                @if ($notif->user)
                                    ({{ $notif->user->name }})
                                @else
                                    ({{ $notif->guest_name }})
                                @endif
                            </a>
                        </div>

                        <div class="flex items-center gap-1">
                            <span>- Booking <span class="text-blue-500">#{{ $notif->id }}</span> -</span>
                            <span
                                class="capitalize font-medium
                        {{ $notif->status === 'selesai'
                            ? 'text-green-600'
                            : ($notif->status === 'sedang'
                                ? 'text-yellow-500'
                                : ($notif->status === 'batal'
                                    ? 'text-red-600'
                                    : 'text-gray-500')) }}">
                                {{ $notif->status }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>


        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Therapist</h2>
            <p class="text-sm font-medium text-green-700 mb-1">Tersedia ({{ $therapistList['tersedia']->count() }})</p>
            <ul class="mb-2 pl-4 text-sm text-gray-700">
                @foreach ($therapistList['tersedia'] as $t)
                    <li>• {{ $t->name }}</li>
                @endforeach
            </ul>

            <p class="text-sm font-medium text-yellow-600 mb-1">Sedang Menangani ({{ $therapistList['sedang']->count() }})
            </p>
            <ul class="mb-2 pl-4 text-sm text-gray-700">
                @foreach ($therapistList['sedang'] as $t)
                    <li>• {{ $t->name }}</li>
                @endforeach
            </ul>

            <p class="text-sm font-medium text-red-600 mb-1">Tidak Tersedia ({{ $therapistList['tidak']->count() }})</p>
            <ul class="pl-4 text-sm text-gray-700">
                @foreach ($therapistList['tidak'] as $t)
                    <li>• {{ $t->name }}</li>
                @endforeach
            </ul>
        </div>


        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Grafik Pendapatan 12 Bulan Terakhir</h2>
            <div class="overflow-x-auto">
                <div class="min-w-[600px]">
                    <canvas id="incomeChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>


        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Kalender Booking</h2>
            <div class="overflow-x-auto">
                <div class="min-w-[600px]">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>

        <!-- Modal Detail Booking -->
        <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-lg relative">
                <button onclick="closeBookingModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-xl">&times;</button>
                <h2 class="text-2xl font-semibold mb-4 text-center text-[#2c1a0f]">Detail Booking</h2>

                <div class="space-y-2 text-sm text-gray-800">
                    <p><strong>Pelanggan:</strong> <span id="modalCustomer"></span></p>
                    <hr>
                    <p><strong>Therapist:</strong> <span id="modalTherapist"></span></p>
                    <hr>
                    <p><strong>Layanan:</strong> <span id="modalTreatment"></span></p>
                    <hr>

                    <p><strong>Jam Mulai:</strong> <span id="modalStartTime"></span></p>
                    <hr>
                    <p><strong>Jam Selesai:</strong> <span id="modalEndTime"></span></p>
                    <hr>
                    <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                    <hr>
                    <p><strong>Catatan:</strong> <span id="modalNote"></span></p>
                </div>
            </div>
        </div>

    </div>
    <style>
        /* Responsif FullCalendar */
        @media (max-width: 640px) {
            #calendar {
                min-width: 600px;
            }

            .fc .fc-toolbar-title {
                font-size: 1.1rem;
            }

            .fc-toolbar {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }

            .fc .fc-button-group {
                flex-wrap: wrap;
            }

            .fc-daygrid-event {
                font-size: 0.7rem;
                padding: 2px 3px;
            }
        }

        /* Pastikan cursor pointer di semua event */
        .fc-event {
            cursor: pointer !important;
        }
    </style>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.0/index.global.min.js"></script>
    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($monthlyIncome),
                    backgroundColor: 'rgba(34,197,94,0.5)',
                    borderColor: 'green',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 45,
                            minRotation: 0
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Kalender
        document.addEventListener('DOMContentLoaded', function() {
            const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'dayGridMonth',
                eventDisplay: 'block',
                eventTimeFormat: {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    @foreach ($calendarBookings as $b)
                        {
                            title: 'Booking',
                            start: '{{ $b->booking_date }}T{{ $b->booking_time }}',
                            extendedProps: {
                                customer: '{{ $b->user->name ?? ($b->guest_name ?? 'Tanpa Nama') }}',
                                therapist: '{{ $b->therapist->name ?? '-' }}',
                                status: '{{ $b->status }}',
                                treatment: '{{ $b->treatment->name ?? '-' }}',
                                startTime: '{{ $b->booking_time }}',
                                duration: '{{ $b->duration_minutes }}',
                                note: `{{ $b->note ?? '-' }}`
                            },
                            color: '{{ $b->status === 'selesai'
                                ? '#22c55e' // hijau
                                : ($b->status === 'sedang'
                                    ? '#60a5fa' // kuning
                                    : ($b->status === 'batal'
                                        ? '#ef4444' // merah
                                        : '#facc15')) }}'
                        },
                    @endforeach
                ],
                eventClick: function(info) {
                    const props = info.event.extendedProps;

                    document.getElementById('modalCustomer').textContent = props.customer;
                    document.getElementById('modalTherapist').textContent = props.therapist;
                    document.getElementById('modalStatus').textContent = props.status;
                    document.getElementById('modalNote').textContent = props.note;
                    document.getElementById('modalStartTime').textContent = props.startTime;
                    document.getElementById('modalTreatment').textContent = props.treatment;



                    // Hitung jam selesai format HH:mm:ss
                    const [hh, mm, ss] = props.startTime.split(":").map(Number);
                    const totalMinutes = hh * 60 + mm + parseInt(props.duration);
                    const endHours = Math.floor(totalMinutes / 60) % 24;
                    const endMinutes = totalMinutes % 60;

                    const endTimeFormatted = [
                        endHours.toString().padStart(2, '0'),
                        endMinutes.toString().padStart(2, '0'),
                        ss.toString().padStart(2, '0') // tetap pakai detik dari jam mulai
                    ].join(":");

                    document.getElementById('modalEndTime').textContent = endTimeFormatted;

                    document.getElementById('bookingModal').classList.remove('hidden');
                },
                eventDidMount: function(info) {
                    info.el.setAttribute('title', `Booking: ${info.event.extendedProps.customer}`);
                    info.el.style.cursor = 'pointer';

                }
            });

            calendar.render();
        });

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }
    </script>
@endsection
