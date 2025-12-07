@extends('layouts.dashboard')

@section('title')
Payment Status
@endsection

@section('content')
<div class="flex flex-1 min-h-screen items-center justify-center">
    <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-lg max-w-md w-full text-center">

        @if($status === 'success')
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Successful!</h2>
            <p class="text-gray-600 mb-6">{{ $message }}</p>
            <a href="{{ route('appointments.index') }}"
               class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition inline-block">
                View My Appointments
            </a>

        @elseif($status === 'pending')
            <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Pending</h2>
            <p class="text-gray-600 mb-6">{{ $message }}</p>
            <div class="space-y-3">
                <a href="{{ route('appointments.index') }}"
                   class="block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition">
                    Check Appointment Status
                </a>
                <a href="{{ route('book.appointment') }}"
                   class="block border border-primary text-primary px-6 py-3 rounded-lg hover:bg-primary/10 transition">
                    Book Another Session
                </a>
            </div>

        @else
            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Payment Failed</h2>
            <p class="text-gray-600 mb-6">{{ $message }}</p>
            <div class="space-y-3">
                <a href="{{ route('book.appointment') }}"
                   class="block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition">
                    Try Again
                </a>
                <a href="{{ route('appointments.index') }}"
                   class="block border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition">
                    Back to Dashboard
                </a>
            </div>
        @endif

        @isset($payment)
        <div class="mt-8 pt-6 border-t border-gray-200 text-left text-sm text-gray-500">
            <p><strong>Order ID:</strong> {{ $payment->order_id }}</p>
            <p><strong>Amount:</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> <span class="capitalize">{{ $payment->status }}</span></p>
        </div>
        @endisset
    </div>
</div>
@endsection
