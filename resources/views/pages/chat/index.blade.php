@extends('layouts.dashboard')

@php
use Illuminate\Support\Str;
@endphp

@section('title', 'Messages')

@section('content')
<div class="absolute inset-0 flex bg-gray-50">
   {{-- List SideBar --}}
    <div class="w-full md:w-1/3 lg:w-1/4 border-r border-gray-200 bg-white flex flex-col h-full">
        <!-- Header -->
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-gray-800">My Messages</h1>

            <!-- Search Bar -->
            <div class="mt-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    placeholder="Search psych"
                    class="w-full pl-10 pr-4 py-3 border-0 bg-gray-50 rounded-xl focus:ring-2 focus:ring-primary focus:bg-white transition placeholder-gray-500"
                >
            </div>

            <!-- Filter - BLM WORK -->
            <div class="mt-6 flex space-x-6 border-b">
                <button class="pb-3 font-semibold text-primary border-b-2 border-primary text-sm">
                    All
                </button>
                <button class="pb-3 font-medium text-gray-500 hover:text-gray-700 text-sm transition">
                    Unread
                </button>
                <button class="pb-3 font-medium text-gray-500 hover:text-gray-700 text-sm transition">
                    Archived
                </button>
            </div>
        </div>

        <!-- Conversations Chat List -->
        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $conversation)
                @php
                    if ($isPatient ?? false) {
                        $otherUser = $conversation->psychologist;
                        $userType = 'psychologist';
                    } elseif ($isPsychologist ?? false) {
                        $otherUser = $conversation->patient;
                        $userType = 'patient';
                    } else {
                        $otherUser = null;
                        $userType = '';
                    }

                    $latestMessage = $conversation->latestMessage;
                    $unreadCount = $conversation->messages->where('receiver_id', auth()->id())->where('is_read', false)->count();
                @endphp

                <a
                    href="{{ route('chat.show', $conversation) }}"
                    class="flex items-center gap-4 p-4 border-b border-gray-100 hover:bg-gray-50 transition duration-200"
                >
                    <div class="relative flex-shrink-0">
                        <img
                            src="{{ $otherUser->photo_url ? asset($otherUser->photo_url) : ($otherUser->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                            class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm"
                            alt="{{ $otherUser->full_name }}"
                        >
                        <!-- Online  -->
                        <div class="absolute bottom-0 right-0 w-3 h-3 {{ rand(0,1) ? 'bg-green-500' : 'bg-gray-400' }} rounded-full border-2 border-white"></div>
                    </div>

                    <!-- Details Nama, Last Message -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm truncate">
                                    {{ $otherUser->full_name }}
                                </h3>
                                <p class="text-xs text-gray-500 truncate">
                                    @if($userType == 'psychologist' && $otherUser->psychologist && $otherUser->psychologist->title)
                                        {{ $otherUser->psychologist->title }}
                                    @elseif($userType == 'psychologist')
                                        Psychologist
                                    @else
                                        Patient
                                    @endif
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                                @if($latestMessage)
                                    {{ $latestMessage->created_at->format('H:i') }}
                                @endif
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm text-gray-600 truncate">
                                @if($latestMessage)
                                    {{ Str::limit($latestMessage->message, 25) }}
                                @else
                                    <span class="text-gray-400">Start conversation</span>
                                @endif
                            </p>

                            {{-- Unread --}}
                            @if($unreadCount > 0)
                                <span class="bg-primary text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <!-- Right Bar -->
                <div class="flex flex-col items-center justify-center h-full min-h-[400px] p-8">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No conversations yet</h3>
                    <p class="text-gray-500 text-center text-sm mb-6">
                        @if($isPatient)
                            Find a psychologist to start your mental health journey
                        @elseif($isPsychologist)
                            You don't have any patient conversations yet
                        @endif
                    </p>

                    @if($isPatient)
                        <a
                            href="{{ route('find.psychologist') }}"
                            class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg hover:bg-primary-dark transition text-sm font-medium"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Find Psychologist
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <div class="hidden md:flex flex-1 flex-col items-center justify-center p-8 bg-gradient-to-br from-blue-50/50 to-indigo-50/50">
        <div class="max-w-md text-center">
            <div class="w-32 h-32 mx-auto bg-gradient-to-br from-white to-gray-50 rounded-2xl flex items-center justify-center mb-8 shadow-sm border border-gray-100">
                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Select a conversation to start chatting</h2>
            <p class="text-gray-600 text-lg mb-2">
                Choose a psychologist from your message list to view your conversation history
            </p>
            <p class="text-gray-500">
                and continue your mental health journey together.
            </p>
        </div>
    </div>
</div>
@endsection

{{-- @push('styles')
<style>
    main {
        padding: 0 !important;
        position: relative;
        min-height: calc(100vh - 4rem);
    }

    .overflow-y-auto::-webkit-scrollbar {
        width: 6px;
    }

    .overflow-y-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 20px;
    }

    .overflow-y-auto::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[placeholder="Search psych"]');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase().trim();
                const conversations = document.querySelectorAll('a[href*="chat"]');

                conversations.forEach(conv => {
                    const name = conv.querySelector('h3').textContent.toLowerCase();
                    const title = conv.querySelector('p.text-gray-500')?.textContent.toLowerCase() || '';
                    const message = conv.querySelector('p.text-gray-600').textContent.toLowerCase();

                    if (name.includes(searchTerm) || title.includes(searchTerm) || message.includes(searchTerm)) {
                        conv.style.display = 'flex';
                    } else {
                        conv.style.display = 'none';
                    }
                });
            });
        }

        const tabs = document.querySelectorAll('button.pb-3');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => {
                    t.classList.remove('text-primary', 'border-primary');
                    t.classList.add('text-gray-500', 'hover:text-gray-700');
                });

                this.classList.remove('text-gray-500', 'hover:text-gray-700');
                this.classList.add('text-primary', 'border-primary');
            });
        });
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 100);
        }
    });
</script>
@endpush --}}
