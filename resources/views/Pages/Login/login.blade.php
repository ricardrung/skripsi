<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Login</title>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-b from-[#2c1f11] to-[#573d22]">
    {{-- <div class="mb-[-20px]">
        <img src="/images/RRS_Background.png" alt="Logo" class="w-50 h-50 mx-auto">
    </div> --}}
    <div class="w-full max-w-sm bg-white p-5 rounded-3xl shadow-2xl mt-16 mb-16">
        <h2 class="text-3xl font-bold text-center drop-shadow-lg italic font-[Lucida_Calligraphy] text-[#000]"><b>
                Roemah Rempah Spa <br> Manado </b> </h2>
        <hr class="border-t border-gray-300 my-3">
        <h2 class="text-2xl font-bold text-center text-[#000]">Log In</h2>
        <form action="navbar" class="mt-4">
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" placeholder="Enter Email"
                    class="w-full p-2 mt-1 border rounded-lg focus:ring focus:ring-[#8B5E3B]" required>
            </div>
            <div class="mt-3">
                <label class="block text-gray-700">Password</label>
                <input type="password" placeholder="Enter Password"
                    class="w-full p-2 mt-1 border rounded-lg focus:ring focus:ring-[#8B5E3B]" required>
            </div>
            <div class="flex justify-between items-center mt-3">
                <a href="resetpassword" class="text-[#8B5E3B] text-sm hover:text-[#8B5E3B] hover:underline">Reset
                    Password?</a>
            </div>
            <button
                class="w-full mt-4 bg-[#2c1f11] text-white p-2 rounded-lg hover:bg-[#b8925c] transition">Login</button>
        </form>
        <p class="text-center text-gray-700 mt-4">Don't have an account?
            <a href="signup" class="text-[#8B5E3B] hover:text-[#8B5E3B] hover:underline">Sign Up</a>
        </p>
    </div>
</body>

</html>
