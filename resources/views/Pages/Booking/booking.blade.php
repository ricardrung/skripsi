@extends('components.Layout.layout')

@section('content')
    <div class="container mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Daftar Booking</h1>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Paket</th>
                    <th class="px-4 py-2">Tanggal</th>
                    <th class="px-4 py-2">Jam</th>
                    <th class="px-4 py-2">Therapist</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookings as $booking)
                    <tr>
                        <td class="border px-4 py-2">{{ $booking->treatment->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $booking->booking_date }}</td>
                        <td class="border px-4 py-2">{{ $booking->booking_time }}</td>
                        <td class="border px-4 py-2">{{ $booking->therapist->name ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ ucfirst($booking->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
