@extends('Components.Layout.layoutadmin')
@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>

        <!-- Ringkasan Total Booking -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h2 class="text-lg font-semibold text-gray-700">Booking Hari Ini</h2>
                <p class="text-2xl font-bold text-blue-600">35</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h2 class="text-lg font-semibold text-gray-700">Booking Minggu Ini</h2>
                <p class="text-2xl font-bold text-green-600">210</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h2 class="text-lg font-semibold text-gray-700">Booking Bulan Ini</h2>
                <p class="text-2xl font-bold text-red-600">920</p>
            </div>
        </div>

        <!-- Notifikasi Booking -->
        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Notifikasi Booking</h2>
            <ul class="mt-2">
                <li class="border-b py-2">🟢 Booking #1234 - Selesai</li>
                <li class="border-b py-2">🟡 Booking #1235 - Dalam Proses</li>
                <li class="py-2">🔴 Booking #1236 - Dibatalkan</li>
            </ul>
        </div>

        <!-- Statistik Therapist -->
        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Therapist yang Bertugas</h2>
            <p class="text-2xl font-bold text-purple-600">5 dari 10</p>
        </div>

        <!-- Grafik Pelanggan & Pendapatan -->
        <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
            <h2 class="text-lg font-semibold text-gray-700">Statistik Pelanggan & Pendapatan</h2>
            <canvas id="chart"></canvas>
        </div>
    </div>

    <!-- Kalender Booking -->
    <div class="mt-6 bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-lg font-semibold text-gray-700">Kalender Booking</h2>
        <div id="calendar" class="mt-4"></div>
    </div>

    <style>
        /* Perbaikan layout FullCalendar */
        .fc-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
        }

        .fc-toolbar .fc-button {
            padding: 8px 12px !important;
            font-size: 14px;
        }

        .fc-daygrid-event {
            white-space: normal !important;
            font-size: 12px;
            padding: 2px 4px;
            line-height: 1.2;
        }
    </style>

    <!-- Tambahkan Chart.js & FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.0/index.global.min.js"></script>
    <script>
        // Inisialisasi FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [{
                        title: 'Booking A',
                        start: '2025-03-10'
                    },
                    {
                        title: 'Booking B',
                        start: '2025-03-15'
                    }
                ]
            });
            calendar.render();
        });

        // Inisialisasi Chart.js
        var ctx = document.getElementById('chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                        label: 'Pelanggan',
                        data: [100, 200, 150, 300, 400, 350],
                        borderColor: 'blue',
                        fill: false
                    },
                    {
                        label: 'Pendapatan',
                        data: [10, 15, 12, 20, 25, 22],
                        borderColor: 'green',
                        fill: false
                    }
                ]
            }
        });
    </script>
@endsection
