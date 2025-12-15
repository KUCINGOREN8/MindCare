@extends('layouts.dashboard')

@section('title')
    {{ __('payment.page_title') }}
@endsection

@section('content')
    <div class="flex flex-1 min-h-screen items-center justify-center">
        <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-lg max-w-md w-full text-center">

            {{-- KONDISI SUKSES --}}
            @if ($status === 'success')
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('payment.success_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ $message }}</p>
                <a href="{{ route('patient.appointments.index') }}"
                    class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition inline-block">
                    {{ __('payment.btn_view_appointments') }}
                </a>

                {{-- KONDISI PENDING --}}
            @elseif($status === 'pending')
                <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('payment.pending_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ $message }}</p>
                <div class="space-y-3">
                    <a href="{{ route('patient.appointments.index') }}"
                        class="block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition">
                        {{ __('payment.btn_check_status') }}
                    </a>
                    <a href="{{ route('patient.book.appointment') }}"
                        class="block border border-primary text-primary px-6 py-3 rounded-lg hover:bg-primary/10 transition">
                        {{ __('payment.btn_book_again') }}
                    </a>
                </div>

                {{-- KONDISI GAGAL --}}
            @else
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('payment.failed_title') }}</h2>
                <p class="text-gray-600 mb-6">{{ $message }}</p>
                <div class="space-y-3">
                    <a href="{{ route('patient.book.appointment') }}"
                        class="block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition">
                        {{ __('payment.btn_try_again') }}
                    </a>
                    <a href="{{ route('patient.appointments.index') }}"
                        class="block border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition">
                        {{ __('payment.btn_back_dashboard') }}
                    </a>
                </div>
            @endif

            {{-- DETAIL PEMBAYARAN --}}
            @isset($payment)
                <div class="mt-8 pt-6 border-t border-gray-200 text-left text-sm text-gray-500">
                    <p><strong>{{ __('payment.order_id') }}</strong> {{ $payment->order_id }}</p>
                    <p><strong>{{ __('payment.amount') }}</strong> Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <p><strong>{{ __('payment.status') }}</strong> <span class="capitalize">{{ $payment->status }}</span></p>
                </div>
            @endisset
        </div>
    </div>
@endsection
