@extends('Components.Layout.layout-therapist')
@section('content')
    <div class="p-6 bg-gray-50 min-h-screen space-y-6">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard Therapist</h1>

        <!-- 1. Jadwal Booking Hari Ini -->
        <section class="bg-white p-4 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold mb-2">Jadwal Booking Hari Ini</h2>
            <ul class="divide-y">
                <li class="py-2 flex justify-between">
                    <div>
                        <p class="font-medium">Ibu Sari</p>
                        <p class="text-sm text-gray-500">Signature Massage | 14:00 - 15:00</p>
                    </div>
                    <span class="text-yellow-500 font-semibold">Belum dimulai</span>
                </li>
                <li class="py-2 flex justify-between">
                    <div>
                        <p class="font-medium">Pak Budi</p>
                        <p class="text-sm text-gray-500">Body Scrub | 15:00 - 16:00</p>
                    </div>
                    <span class="text-green-500 font-semibold">Sedang berlangsung</span>
                </li>
            </ul>
        </section>

        <!-- 2. Status Therapist -->
        <section class="bg-white p-4 rounded-xl shadow-md">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold">Status Therapist</h2>
                <select class="border rounded-md p-1 text-sm">
                    <option class="text-green-600">✅ Tersedia</option>
                    <option class="text-yellow-500">🔄 Sedang menangani</option>
                    <option class="text-red-500">❌ Tidak tersedia</option>
                </select>
            </div>
        </section>

        <!-- 3. Booking Mendatang -->
        <section class="bg-white p-4 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold mb-2">Booking Mendatang (7 Hari ke Depan)</h2>
            <ul class="text-sm text-gray-700 space-y-1">
                <li>26 Apr – Mbak Lani – Reflexology – 13:00</li>
                <li>27 Apr – Pak Dedi – Body Scrub – 10:00</li>
            </ul>
        </section>

        <!-- 4. Riwayat Booking -->
        <section class="bg-white p-4 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold mb-2">Riwayat Booking</h2>
            <ul class="text-sm text-gray-700 space-y-1">
                <li>20 Apr – Ibu Sari – Signature Massage</li>
                <li>18 Apr – Pak Budi – Body Scrub</li>
            </ul>
        </section>

        <!-- 5. Notifikasi Penting -->
        <section class="bg-white p-4 rounded-xl shadow-md">
            <h2 class="text-xl font-semibold mb-2">Notifikasi Penting</h2>
            <ul class="list-disc ml-5 text-sm text-red-600 space-y-1">
                <li>Ada booking baru untuk besok jam 10:00</li>
                <li>Customer membatalkan booking jam 15:00</li>
            </ul>
        </section>

        <!-- 6. Tombol Update Status -->
        <section class="flex space-x-4">
            <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">Mulai Sesi</button>
            <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Selesai</button>
        </section>
    </div>
@endsection
