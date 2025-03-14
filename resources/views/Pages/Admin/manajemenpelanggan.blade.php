@extends('Components.Layout.layoutadmin')
@section('content')
    <div class="p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Manajemen Pelanggan</h2>

        <!-- Filter Pencarian -->
        <div class="relative w-full md:w-80 mt-3 md:mt-0 md:mr-6 mb-4">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            <input type="text" id="search" placeholder="Cari pelanggan..."
                class="w-full pl-10 px-4 py-2 bg-white  text-black rounded-lg focus:ring-2 focus:ring-black"
                onkeyup="filterCustomers()">
        </div>
        {{-- <div class="relative w-full md:w-80 mt-3 md:mt-0 md:mr-6" >
          <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
            <input type="text" placeholder="Search..." 
                class="w-full pl-10 px-4 py-2 bg-white border border-gray-300 text-black rounded-lg focus:ring-2 focus:ring-black">
        </div> --}}

        <!-- Tabel Pelanggan -->
        <div class="overflow-x-auto p-6 bg-white shadow-md rounded-lg">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">Nama</th>
                        <th class="p-3 text-center">Gender</th>
                        <th class="p-3 text-center">Email</th>
                        <th class="p-3 text-center">No HP</th>
                        <th class="p-3 text-center">Birthday</th>
                        <th class="p-3 text-center">Total Booking</th>
                        {{-- <th class="p-3 text-center">Aksi</th> --}}
                    </tr>
                </thead>
                <tbody id="customer-list">
                    <!-- Data Dummy -->
                    <tr class="border-b">
                        <td class="p-3">Dewi Ayu</td>
                        <td class="p-3 text-center">Wanita</td>
                        <td class="p-3 text-center">dewi@example.com</td>
                        <td class="p-3 text-center">08123456789</td>
                        <td class="p-3 text-center">1995-06-21</td>
                        <td class="p-3 text-center">5</td>
                        {{-- <td class="p-3 text-center">
                            <button class="text-blue-500 hover:underline"
                                onclick="openDetail('Dewi Ayu', 'Wanita', 'dewi@example.com', '08123456789', '1995-06-21', '5')">Lihat</button>
                            <button class="text-red-500 hover:underline ml-2" onclick="deleteCustomer(this)">Hapus</button>
                        </td> --}}
                    </tr>
                    <tr class="border-b">
                        <td class="p-3">Budi Santoso</td>
                        <td class="p-3 text-center">Pria</td>
                        <td class="p-3 text-center">budi@example.com</td>
                        <td class="p-3 text-center">08234567890</td>
                        <td class="p-3 text-center">1989-02-15</td>
                        <td class="p-3 text-center">10</td>
                        {{-- <td class="p-3 text-center">
                            <button class="text-blue-500 hover:underline"
                                onclick="openDetail('Budi Santoso', 'Pria', 'budi@example.com', '08234567890', '1989-02-15', '10')">Lihat</button>
                            <button class="text-red-500 hover:underline ml-2" onclick="deleteCustomer(this)">Hapus</button>
                        </td> --}}
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- <!-- Modal Detail Pelanggan -->
        <div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg w-96">
                <h3 class="text-lg font-semibold mb-4">Detail Pelanggan</h3>
                <p><strong>Nama:</strong> <span id="detail-name"></span></p>
                <p><strong>Gender:</strong> <span id="detail-gender"></span></p>
                <p><strong>Email:</strong> <span id="detail-email"></span></p>
                <p><strong>No HP:</strong> <span id="detail-phone"></span></p>
                <p><strong>Birthday:</strong> <span id="detail-birthday"></span></p>
                <p><strong>Total Booking:</strong> <span id="detail-bookings"></span></p>
                <div class="flex justify-end mt-4">
                    <button class="bg-gray-300 px-4 py-2 rounded" onclick="closeDetail()">Tutup</button>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Script Modal & Pencarian -->
    <script>
        // function openDetail(name, gender, email, phone, birthday, bookings) {
        //     document.getElementById("detail-name").textContent = name;
        //     document.getElementById("detail-gender").textContent = gender;
        //     document.getElementById("detail-email").textContent = email;
        //     document.getElementById("detail-phone").textContent = phone;
        //     document.getElementById("detail-birthday").textContent = birthday;
        //     document.getElementById("detail-bookings").textContent = bookings;
        //     document.getElementById("modalDetail").classList.remove("hidden");
        // }

        // function closeDetail() {
        //     document.getElementById("modalDetail").classList.add("hidden");
        // }

        function deleteCustomer(element) {
            if (confirm("Apakah Anda yakin ingin menghapus pelanggan ini?")) {
                element.closest("tr").remove();
            }
        }

        function filterCustomers() {
            let input = document.getElementById("search").value.toLowerCase();
            let rows = document.querySelectorAll("#customer-list tr");

            rows.forEach(row => {
                let name = row.cells[0].textContent.toLowerCase();
                let phone = row.cells[3].textContent.toLowerCase();
                if (name.includes(input) || phone.includes(input)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    </script>
@endsection
