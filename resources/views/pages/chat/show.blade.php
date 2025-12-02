@extends('layouts.dashboard')

@section('title', 'Chat with ' . ($otherUser->full_name ?? 'User'))

@section('content')
<div class="absolute inset-0 flex bg-gray-50">
    <div class="hidden md:block w-96 border-r border-gray-200 bg-white h-full overflow-hidden">
        @include('pages.chat.partials.sidebar', [
            'conversations' => $conversations,
            'userType' => $userType,
            'conversation' => $conversation
        ])
    </div>

    <div class="flex-1 flex flex-col h-full">
        <div class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center">
                {{-- Responsive - Mobile --}}
                <a href="{{ route('messages') }}" class="md:hidden mr-4 text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                {{-- Header --}}
                <div class="flex items-center">
                    <div class="relative">
                        <img
                            src="{{ $otherUser->photo_url ? asset($otherUser->photo_url) : ($otherUser->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                            class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm"
                            alt="{{ $otherUser->full_name }}"
                        >
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="ml-3">
                        <h2 class="font-bold text-gray-900 text-sm">{{ $otherUser->full_name }}</h2>
                        <p class="text-xs text-gray-600 flex items-center">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                            Online -
                            @if($userType == 'patient')
                                {{ $otherUser->psychologist->specialization ?? 'Psychologist' }}
                            @elseif($userType == 'psychologist')
                                Patient
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Buttons Header --}}
            <div class="flex items-center gap-4">
                <!-- Search -->
                <button class="text-gray-500 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <!-- More  -->
                <button class="text-gray-500 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Messages -->
        <div
            id="messages-container"
            class="flex-1 overflow-y-auto p-4 md:p-6 min-h-0"
            style="background-color: #f8fafc;"
        >
            <div class="max-w-3xl mx-auto">
                @foreach($messages as $message)
                    <div class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }} mb-4 last:mb-0">
                        <div class="max-w-[70%] lg:max-w-[60%]">
                            @if($message->sender_id != auth()->id())
                                <p class="text-xs text-gray-500 mb-1 ml-3 font-medium">
                                    {{ $message->sender->full_name }}
                                </p>
                            @endif

                            <div class="flex items-start gap-2">
                                @if($message->sender_id != auth()->id())
                                    <img
                                        src="{{ $message->sender->photo_url ? asset($message->sender->photo_url) : ($message->sender->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                                        class="w-8 h-8 rounded-full flex-shrink-0 border border-gray-200 mt-1"
                                    />
                                @endif

                                <div class="flex-1">
                                    <div class="{{ $message->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-white text-gray-900' }} rounded-2xl px-4 py-3 shadow-sm border border-gray-100">
                                        <p class="text-sm break-words leading-relaxed">{{ $message->message }}</p>
                                    </div>

                                    <div class="flex items-center gap-2 mt-1 {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                        <span class="text-xs {{ $message->sender_id == auth()->id() ? 'text-gray-400' : 'text-gray-500' }}">
                                            {{ $message->created_at->format('H:i') }}
                                        </span>

                                        @if($message->sender_id == auth()->id())
                                            @if($message->is_read)
                                                <span class="text-xs text-primary font-medium flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                    Read
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">
                                                    Sent
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                                @if($message->sender_id == auth()->id())
                                    <div class="w-8 flex-shrink-0"></div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-white border-t border-gray-100 p-4 flex-shrink-0">
            <form id="message-form" method="POST" action="{{ route('chat.send', $conversation) }}" class="max-w-3xl mx-auto">
                @csrf
                <div class="flex items-center gap-3">
                    <!-- Attachment Button -->
                    <button type="button" class="text-gray-500 hover:text-gray-700 p-2.5 rounded-xl hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>

                    <!-- Message Input -->
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            name="message"
                            id="message-input"
                            placeholder="Type your message..."
                            class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3.5 pr-12 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white transition placeholder-gray-500 text-sm"
                            autocomplete="off"
                            autofocus
                        >

                        <!-- Emoji Button -->
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Send Button -->
                    <button
                        type="submit"
                        id="send-button"
                        class="bg-primary text-white rounded-xl p-3.5 hover:bg-primary-dark transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    main {
        padding: 0 !important;
        position: relative;
    }

    #messages-container::-webkit-scrollbar {
        width: 6px;
    }

    #messages-container::-webkit-scrollbar-track {
        background: transparent;
    }

    #messages-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
    }

    #messages-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    #messages-container {
        scroll-behavior: smooth;
    }

    .message-bubble-sent {
        border-bottom-right-radius: 0.5rem !important;
    }

    .message-bubble-received {
        border-bottom-left-radius: 0.5rem !important;
    }
</style>
@endpush

@push('scripts')
<script>

// Blm Implement Attachment Button + Icons
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const messagesContainer = document.getElementById('messages-container');

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function scrollToBottom() {
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    }

    scrollToBottom();

    if (messageInput) {
        messageInput.focus();
    }
    if (messageForm) {
        messageForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const message = messageInput.value.trim();

            if (!message) {
                alert('Please type a message');
                return;
            }

            sendButton.disabled = true;
            const originalHTML = sendButton.innerHTML;
            sendButton.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            `;

            try {
                const formData = new FormData(this);

                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    messageInput.value = '';
                    addNewMessageToUI(data.message);

                    updateSidebarConversation(data.message);

                } else {
                    throw new Error(data.message || 'Failed to send message');
                }

            } catch (error) {
                console.error('Error:', error);
                alert(error.message || 'Failed to send message. Please try again.');
            } finally {
                sendButton.disabled = false;
                sendButton.innerHTML = originalHTML;
            }
        });
    }
    if (messageInput) {
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                messageForm.dispatchEvent(new Event('submit'));
            }
        });
    }

    // Waktu
    function addNewMessageToUI(messageData) {
        const isSent = {{ auth()->id() }} === messageData.sender_id;
        const time = new Date(messageData.created_at).toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        });

        const messageHtml = `
            <div class="flex ${isSent ? 'justify-end' : 'justify-start'}">
                <div class="flex items-end gap-2 max-w-[70%] lg:max-w-[60%]">
                    ${!isSent ? `
                        <img src="${messageData.sender.photo_url ? messageData.sender.photo_url : (messageData.sender.gender == 'female' ? '/assets/icons/user_female.svg' : '/assets/icons/user_male.svg')}"
                            class="w-8 h-8 rounded-full flex-shrink-0 border border-gray-200" />
                    ` : ''}

                    <div class="flex flex-col ${isSent ? 'items-end' : 'items-start'}">
                        ${!isSent ? `
                            <p class="text-xs text-gray-500 mb-1 font-medium">
                                ${messageData.sender.full_name}
                            </p>
                        ` : ''}

                        <div class="${isSent ? 'bg-primary text-white' : 'bg-white text-gray-900'} rounded-2xl px-4 py-3 shadow-sm border border-gray-100">
                            <p class="text-sm break-words leading-relaxed">${messageData.message}</p>
                        </div>

                        <div class="flex items-center gap-2 mt-1.5">
                            <span class="text-xs ${isSent ? 'text-gray-400' : 'text-gray-500'}">
                                ${time}
                            </span>

                            ${isSent ? `
                                <span class="text-xs text-gray-400">
                                    Sent
                                </span>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;

        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        scrollToBottom();
    }

    function updateSidebarConversation(messageData) {
        const conversationItem = document.querySelector(`a[href*="{{ route('chat.show', $conversation) }}"]`);
        if (conversationItem) {
            const lastMessageEl = conversationItem.querySelector('.text-gray-600');
            const timeEl = conversationItem.querySelector('.text-gray-400');

            if (lastMessageEl) {
                lastMessageEl.textContent = messageData.message.length > 25
                    ? messageData.message.substring(0, 25) + '...'
                    : messageData.message;
            }

            if (timeEl) {
                const now = new Date();
                timeEl.textContent = now.getHours().toString().padStart(2, '0') + ':' +
                                   now.getMinutes().toString().padStart(2, '0');
            }
        }
    }

    let lastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};

    function pollForNewMessages() {
        setTimeout(async () => {
            try {
                const response = await fetch('{{ route("chat.messages", $conversation) }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (response.ok) {
                    const messages = await response.json();

                    const newMessages = messages.filter(msg => msg.id > lastMessageId && msg.sender_id !== {{ auth()->id() }});

                    if (newMessages.length > 0) {
                        newMessages.forEach(msg => {
                            addNewMessageToUI(msg);
                        });
                        lastMessageId = Math.max(...messages.map(msg => msg.id));
                    }
                }
            } catch (error) {
                console.error('Polling error:', error);
            }

            pollForNewMessages();
        }, 1000);
    }

    @if($messages->isNotEmpty() && $messages->last()->sender_id != auth()->id())
        pollForNewMessages();
    @endif
});
</script>
@endpush
@endsection
