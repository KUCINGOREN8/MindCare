@extends('layouts.auth')
@section('title', 'OTP Code')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div
                    class="mx-auto w-16 h-16 bg-gradient-to-br from-[#00C3B3] to-[#33D1C2] rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                    <img src="{{ asset('assets/signup/lock.png') }}" alt="Secure Verification"
                        class="w-8 h-8 filter brightness-0 invert">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    {{ __('messages.otpverify') }}
                </h2>
                <p class="text-gray-600">
                    {{ __('messages.otpdescverify') }}
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form method="POST" action="{{ route('otp.verify') }}" class="p-8" id="otpForm">
                    @csrf
                    <input type="hidden" name="otp_code" id="otp_code">

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-4 text-center">
                            {{ __('messages.otpentercode') }}
                        </label>

                        <!-- OTP  -->
                        <div class="flex justify-center gap-3 mb-4">
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/20 focus:border-[#00C3B3] transition-all duration-200"
                                inputmode="numeric" pattern="[0-9]" autofocus>
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/20 focus:border-[#00C3B3] transition-all duration-200"
                                inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/20 focus:border-[#00C3B3] transition-all duration-200"
                                inputmode="numeric" pattern="[0-9]">

                            <div class="w-4 flex items-center justify-center">
                                <span class="text-gray-400 font-bold">-</span>
                            </div>

                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/20 focus:border-[#00C3B3] transition-all duration-200"
                                inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/20 focus:border-[#00C3B3] transition-all duration-200"
                                inputmode="numeric" pattern="[0-9]">
                            <input type="text" maxlength="1"
                                class="otp-input w-14 h-14 text-center text-2xl font-bold border-2 {{ $errors->has('otp_code') ? 'border-red-300' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/20 focus:border-[#00C3B3] transition-all duration-200"
                                inputmode="numeric" pattern="[0-9]">
                        </div>

                        @error('otp_code')
                            <div class="text-center">
                                <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <!-- Submit  -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#00C3B3] to-[#33D1C2] hover:from-[#00ADA0] hover:to-[#00C3B3] text-white font-semibold py-4 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-[#00C3B3]/50">
                        {{ __('messages.otpverifybtn') }}
                    </button>
                </form>

                {{-- Resend OTP --}}
                <div class="px-8 pb-8">
                    <div class="text-center text-sm pt-4 border-t border-gray-100">
                        <span class="text-gray-600">{{ __('messages.otperror') }}</span>

                        <form method="POST" action="{{ route('otp.resend') }}" class="inline" id="resendForm">
                            @csrf
                            <button type="submit"
                                class="font-semibold text-[#00C3B3] hover:text-[#33D1C2] ml-1 transition-colors duration-200">
                                {{ __('messages.otpresend') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
