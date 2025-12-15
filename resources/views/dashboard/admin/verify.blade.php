@extends('layouts.dashboard')
@section('title', 'Verify Psychologist')

@section('content')
    {{-- 1. WRAPPER UTAMA: Flex Row (Kiri Konten, Kanan Sidebar) --}}
    <div class="flex flex-1 gap-6" x-data="{
        showModal: false,
        modalUrl: '',
        modalAction: 'POST',
        modalTitle: '',
        modalMessage: '',
        modalBtnClass: '',
        modalBtnText: ''
    }">

        {{-- === BAGIAN KIRI: KONTEN UTAMA === --}}
        <div class="flex flex-col flex-1 gap-6 min-w-0">

            {{-- Header Greeting --}}
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">{{ __('messages.admincenter') }}</h1>
                    <h5 class="text-captiondark text-sm">{{ __('messages.admincenterdesc') }}.</h5>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">

                {{-- Judul Halaman --}}
                <div class="flex justify-between items-center">
                    <div class="flex flex-col gap-1 justify-between items-start">
                        <h3 class="font-bold">{{ __('messages.pendingreq') }}</h3>
                        <p class="text-xs text-caption-dark">{{ __('messages.approverejectdesc') }}.</p>
                    </div>
                </div>

                {{-- Flash Message --}}
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Tabel --}}
                <div class="flex flex-col bg-white rounded-md border border-grey-border shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.psychologistname') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.licenseandspec') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.registerdate') }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __('messages.action') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pendingPsychologists as $psy)
                                    <tr class="hover:bg-gray-50 transition-colors">

                                        {{-- Nama & Email --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                        src="{{ $psy->photo_url ? asset($psy->photo_url) : ($psy->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                                                        alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $psy->full_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">{{ $psy->email }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- License & Spec --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-700">
                                                    {{ $psy->psychologist->license_number ?? '-' }}
                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    {{ $psy->psychologist->specialization ?? 'Psychologist' }}
                                                </span>
                                            </div>
                                        </td>

                                        {{-- Register Date --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $psy->created_at->format('d M Y') }}
                                            <div class="text-xs text-orange-500 mt-1 font-medium">
                                                {{ __('messages.pendingverif') }}</div>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-3 items-center">

                                                <button type="button"
                                                    @click="
                                                        showModal = true;
                                                        modalUrl = '{{ route('admin.verify.reject', $psy->id) }}';
                                                        modalAction = 'DELETE';
                                                        modalTitle = 'Reject Application?';
                                                        modalMessage = 'Are you sure you want to REJECT this application? This will remove the request permanently.';
                                                        modalBtnClass = 'bg-red-600 hover:bg-red-700 focus:ring-red-500';
                                                        modalBtnText = 'Yes, Reject It';
                                                    "
                                                    class="text-red-500 hover:text-red-700 font-medium hover:underline transition-all">
                                                    {{ __('messages.reject') }}
                                                </button>

                                                {{-- 2. TOMBOL APPROVE (Memicu Modal Hijau/Biru) --}}
                                                <button type="button"
                                                    @click="
                                                        showModal = true;
                                                        modalUrl = '{{ route('admin.verify.approve', $psy->id) }}';
                                                        modalAction = 'POST';
                                                        modalTitle = 'Approve Psychologist?';
                                                        modalMessage = 'Are you sure you want to APPROVE this user? They will be allowed to access the dashboard immediately.';
                                                        modalBtnClass = 'bg-primary hover:bg-primary-dark focus:ring-primary';
                                                        modalBtnText = 'Yes, Approve';
                                                    "
                                                    class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-xs font-bold transition-colors shadow-sm">
                                                    {{ __('messages.approve') }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center gap-3">
                                                <div class="bg-gray-100 p-4 rounded-full">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-gray-600 font-medium text-base">{{ __('messages.nopendingfound') }}.</span>
                                                    <span
                                                        class="text-sm text-gray-400">{{ __('messages.nopendingfounddesc') }}.</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div>
                        {{ $pendingPsychologists->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </div>
        </div>

        <x-user-profile-card :user="$user" :notifications="$notifications" />

        <div x-show="showModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            {{-- Modal Panel --}}
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden transform transition-all border border-gray-100"
                @click.away="showModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4">

                <div class="p-6 text-center">
                    {{-- Icon Tanda Tanya (Neutral) --}}
                    <div
                        class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-6 ring-8 ring-blue-50/50">
                        <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    {{-- Judul & Pesan Dinamis --}}
                    <h3 class="text-xl font-bold text-gray-900 mb-2" x-text="modalTitle"></h3>
                    <p class="text-sm text-gray-500 leading-relaxed" x-text="modalMessage"></p>
                </div>

                {{-- Footer Action --}}
                <div
                    class="bg-gray-50 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 justify-center sm:justify-end border-t border-gray-100">

                    {{-- Tombol Cancel --}}
                    <button @click="showModal = false" type="button"
                        class="w-full sm:w-auto px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 hover:text-gray-900 font-medium transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 shadow-sm">
                        Cancel
                    </button>

                    {{-- Form Eksekusi --}}
                    <form :action="modalUrl" method="POST" class="w-full sm:w-auto">
                        @csrf
                        {{-- Input Method Spoofing Dinamis (Bisa DELETE atau POST) --}}
                        <input type="hidden" name="_method" :value="modalAction">

                        {{-- Tombol Confirm Dinamis --}}
                        <button type="submit" :class="modalBtnClass"
                            class="w-full sm:w-auto px-5 py-2.5 text-white rounded-lg font-bold transition-all shadow-md transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 flex justify-center items-center gap-2">
                            <span x-text="modalBtnText"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
