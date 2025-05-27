@extends('components.layout.layout-therapist')
@section('content')
    <div class="p-6 space-y-6">
        <h1 class="text-3xl font-semibold text-gray-800">Selamat Datang, {{ Auth::user()->name }}</h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Status -->
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h2 class="text-sm text-gray-500">Status Anda</h2>
                <p class="text-lg font-semibold text-green-600">{{ $status }}</p>
            </div>

            <!-- Total Jadwal Hari Ini -->
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h2 class="text-sm text-gray-500">Jadwal Hari Ini</h2>
                <a href="{{ route('therapist.schedule.today') }}"
                    class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                    📋 Lihat semua jadwal hari ini
                </a>
                <p class="text-2xl font-bold text-rose-600">{{ count($todayBookings) }}</p>
            </div>

            <!-- Booking Minggu Ini -->
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h2 class="text-sm text-gray-500">Booking Minggu Ini</h2>
                <a href="{{ route('therapist.schedule.week') }}" class="text-blue-600 text-sm hover:underline">
                    📆 Lihat semua booking minggu ini
                </a>
                <p class="text-2xl font-bold text-indigo-600">{{ $weeklyCount }}</p>
            </div>

            <!-- Booking Bulan Ini -->
            <div class="bg-white shadow rounded-lg p-4 text-center">
                <h2 class="text-sm text-gray-500">Booking Bulan Ini</h2>
                <a href="{{ route('therapist.schedule.month') }}" class="text-blue-600 text-sm hover:underline">
                    📊 Lihat semua booking bulan ini
                </a>
                <p class="text-2xl font-bold text-blue-600">{{ $monthlyCount }}</p>
            </div>

        </div>

        <!-- List Jadwal Hari Ini -->
        <div class="bg-white shadow rounded-lg p-4">
            <h2 class="text-lg font-bold text-gray-700 mb-3">Daftar Jadwal Hari Ini</h2>
            @forelse($todayBookings as $booking)
                <div class="border-b py-2 text-sm">
                    🕒 <strong>{{ \Carbon\Carbon::parse($booking->booking_time)->format('H:i') }}</strong> -
                    <span class="font-medium">{{ $booking->treatment->name }}</span>
                    <br>
                    👤 <span class="text-gray-700">Guest:</span> {{ $booking->guest_name ?? '-' }}<br>
                    🔐 <span class="text-gray-700">Customer:</span> {{ $booking->user->name ?? '-' }}
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada jadwal hari ini.</p>
            @endforelse
        </div>



        <canvas id="bookingChart" height="100"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('bookingChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Booking per Hari',
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: '#4f46e5',
                    }]
                }
            });
        </script>


    </div>
@endsection
