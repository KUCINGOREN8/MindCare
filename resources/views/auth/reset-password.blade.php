<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="overflow-hidden">

    <div class="flex" style="height: 100vh;">
        <!-- Left Container-->
        <div class="hidden lg:block lg:w-[30%] relative">
            <img src="{{ asset('assets/signup/right-image.jpg') }}" alt="Person writing" class="w-full h-full object-cover">
        </div>

        <!-- Right Container -->
        <div class="w-full lg:w-[70%] flex items-center justify-center bg-white overflow-y-auto" style="padding-top: 12rem; padding-bottom: 4rem; padding-left: 2rem; padding-right: 2rem;">
            <div class="w-full max-w-lg">
                <h2 class="text-5xl font-bold mb-2 text-center" style="color: #009C8F;">RESET PASSWORD</h2>
                <p class="text-gray-600 text-center mb-8">Enter your new password</p>

                <form class="space-y-6" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #F0F0F0;">
                            <img src="{{ asset('assets/signup/email.svg') }}" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                            <input type="email" name="email" placeholder="Email"
                                class="w-full outline-none text-gray-500 placeholder-gray-400 bg-transparent"
                                value="{{ $email ?? old('email') }}"
                                readonly
                                style="background-color: #F0F0F0;">
                            <img src="{{ asset('assets/forgot-password/lock.svg') }}" alt="locked" class="w-5 h-5 ml-2 opacity-50">
                        </div>
                        @error('email')
                            <p class="text-red-500 text-sm mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <img src="{{ asset('assets/signup/password.svg') }}" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                            <input type="password" name="password" placeholder="New Password" class="w-full outline-none text-black placeholder-gray-400 bg-transparent" id="password" required>
                            <button type="button" class="ml-2 focus:outline-none" onclick="togglePassword('password')">
                                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50" id="password-eye"
                                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-sm mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <img src="{{ asset('assets/signup/password.svg') }}" alt="icon" class="w-5 h-5 mr-4 opacity-50">
                            <input type="password" name="password_confirmation" placeholder="Confirm New Password" class="w-full outline-none text-black placeholder-gray-400 bg-transparent" id="confirm-password" required>
                            <button type="button" class="ml-2 focus:outline-none" onclick="togglePassword('confirm-password')">
                                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50" id="confirm-password-eye"
                                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
                            </button>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full text-white py-3 rounded-lg font-medium hover:opacity-90 transition text-base shadow-md" style="background-color: #009C8F;">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/auth.js') }}"></script>
</body>
</html>
