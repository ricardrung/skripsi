@extends('Components.Layout.layoutadmin')
@section('content')
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Manajemen Pembayaran</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="bg-gray-100">
        <div class="container mx-auto p-6">
            <h2 class="text-2xl font-bold mb-4">Manajemen Pembayaran</h2>

            <!-- Filter & Actions -->
            <div class="flex justify-between mb-4">
                <select class="border rounded p-2">
                    <option value="all">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
                <div>
                    <button class="bg-green-500 text-white px-4 py-2 rounded">Download Laporan</button>
                </div>
            </div>

            <!-- Payment Table -->
            <div class="bg-white shadow rounded-lg p-4">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-2">#</th>
                            <th class="p-2">Pelanggan</th>
                            <th class="p-2">Total</th>
                            <th class="p-2">Metode</th>
                            <th class="p-2">Status</th>
                            <th class="p-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="p-2">1</td>
                            <td class="p-2">John Doe</td>
                            <td class="p-2">Rp 500.000</td>
                            <td class="p-2">Midtrans</td>
                            <td class="p-2 text-green-500">Paid</td>
                            <td class="p-2">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded">Detail</button>
                            </td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-2">2</td>
                            <td class="p-2">Jane Doe</td>
                            <td class="p-2">Rp 700.000</td>
                            <td class="p-2">Xendit</td>
                            <td class="p-2 text-yellow-500">Pending</td>
                            <td class="p-2">
                                <button class="bg-blue-500 text-white px-2 py-1 rounded">Detail</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>

    </html>
@endsection
