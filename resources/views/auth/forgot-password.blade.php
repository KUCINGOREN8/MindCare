<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="overflow-hidden">

    <div class="flex" style="height: 100vh;">
        <!-- Left Container-->
        <div class="w-full lg:w-[70%] flex items-center justify-center bg-white overflow-y-auto" style="padding-top: 12rem; padding-bottom: 4rem; padding-left: 2rem; padding-right: 2rem;">
            <div class="w-full max-w-lg">
                <h2 class="text-5xl font-bold mb-2 text-center" style="color: #009C8F;">FORGOT PASSWORD</h2>
                <p class="text-gray-600 text-center mb-8">Enter your email to reset your password</p>

                <form class="space-y-6" method="POST" action="{{ route('password.email') }}">
                    @csrf

                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <img src="{{ asset('assets/signup/email.svg') }}" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                            <input type="email" name="email" placeholder="Email" class="w-full outline-none text-black placeholder-gray-400 bg-transparent" value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full text-white py-3 rounded-lg font-medium hover:opacity-90 transition text-base shadow-md" style="background-color: #009C8F;">
                            Send Reset Link
                        </button>
                    </div>

                    <div class="text-center pt-3">
                        <p class="text-gray-600 text-sm">
                            Remember your password?
                            <a href="{{ route('login') }}" class="font-medium underline" style="color: #009C8F;">Sign in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Container -->
        <div class="hidden lg:block lg:w-[30%] relative">
            <img src="{{ asset('assets/signup/right-image.jpg') }}" alt="Person writing" class="w-full h-full object-cover">
        </div>
    </div>

</body>
</html>
