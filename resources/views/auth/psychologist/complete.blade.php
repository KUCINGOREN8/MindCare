@extends('layouts.auth')
@section('title', 'Registration Complete')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-800 mb-2" style="color: #009C8F;">Registration Complete!</h2>
            <p class="text-gray-600 mb-6">Your psychologist profile has been created successfully.</p>
        </div>

        <div class="mb-8 p-6 bg-gray-50 rounded-lg">
            <p class="text-gray-700 mb-4">
                <i class="fas fa-envelope text-[#009C8F] mr-2"></i>
                We've sent a verification OTP to your email address.
            </p>
            <p class="text-gray-600 text-sm">
                Please check your inbox and verify your email to activate your account and access your dashboard.
            </p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('otp.verify') }}" class="block w-full py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md">
                Verify OTP Now
            </a>

            <a href="{{ route('login') }}" class="block w-full py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition">
                Back to Login
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <p class="text-sm text-gray-500">
                Need help? <a href="#" class="text-[#009C8F] hover:underline">Contact our support team</a>
            </p>
        </div>
    </div>
</div>
@endsection
