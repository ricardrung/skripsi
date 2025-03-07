<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-b from-[#2c1f11] to-[#573d22]">
    <div class="mb-[-20px]">
        <img src="/images/RRS_Background.png" alt="Logo" class="w-50 h-50 mx-auto">
    </div>
    <div class="w-full max-w-sm bg-white p-6 rounded-3xl shadow-md mb-16">
        <h2 class="text-3xl font-bold text-center drop-shadow-lg italic font-[Lucida_Calligraphy] text-[#000]"><b>
                Roemah
                Rempah Spa <br> Manado </b> </h2>
        <hr class="border-t border-gray-300 my-3">
        <h2 class="text-2xl font-bold text-center text-[#000]">Reset Password</h2>

        <!-- Pesan Kesalahan -->
        <div id="error-message" class="hidden text-red-600 text-sm mt-2 bg-red-100 p-2 rounded">
            <!-- Pesan kesalahan akan muncul di sini -->
        </div>

        <form action="#" class="mt-4" onsubmit="return validateForm(event)">
            <!-- Input Email -->
            <div>
                <label class="block text-gray-700">Email</label>
                <input id="email" type="email" placeholder="Enter Email"
                    class="w-full p-2 mt-1 border rounded-lg focus:ring focus:ring-[#8B5E3B]" required>
            </div>

            <!-- Input Password Baru -->
            <div class="mt-3">
                <label class="block text-gray-700">New Password</label>
                <input id="new-password" type="password" placeholder="Enter New Password"
                    class="w-full p-2 mt-1 border rounded-lg focus:ring focus:ring-[#8B5E3B]" required>
            </div>

            <!-- Input Konfirmasi Password -->
            <div class="mt-3">
                <label class="block text-gray-700">Confirm New Password</label>
                <input id="confirm-password" type="password" placeholder="Enter New Password"
                    class="w-full p-2 mt-1 border rounded-lg focus:ring focus:ring-[#8B5E3B]" required>
            </div>

            <!-- Tombol Reset Password -->
            <button class="w-full mt-4 bg-[#7a5b2a] text-white p-2 rounded-lg hover:bg-[#6D422A] transition">
                Reset Password
            </button>
        </form>

        <!-- Link Kembali ke Login -->
        <p class="text-center text-gray-700 mt-4">
            <a href="login" class="text-[#8B5E3B] hover:text-[#6D422A] hover:underline">Back to Login</a>
        </p>
    </div>

    <!-- Validasi Form -->
    <script>
        function validateForm(event) {
            event.preventDefault();
            const email = document.getElementById("email").value;
            const newPassword = document.getElementById("new-password").value;
            const confirmPassword = document.getElementById("confirm-password").value;
            const errorMessage = document.getElementById("error-message");

            if (!email.includes("@")) {
                errorMessage.innerText = "Masukkan email yang valid.";
                errorMessage.classList.remove("hidden");
                return false;
            }

            if (newPassword.length < 6) {
                errorMessage.innerText = "Password baru harus minimal 6 karakter.";
                errorMessage.classList.remove("hidden");
                return false;
            }

            if (newPassword !== confirmPassword) {
                errorMessage.innerText = "Password baru dan konfirmasi password tidak cocok.";
                errorMessage.classList.remove("hidden");
                return false;
            }

            errorMessage.classList.add("hidden");
            alert("Password berhasil direset!");
            return true;
        }
    </script>
</body>

</html>
