@extends('layouts.dashboard')

@section('title', __('chat_show.page_title', ['name' => $otherUser->full_name ?? 'User']))

@section('content')
    <div class="absolute inset-0 flex bg-gray-50">
        <div class="hidden md:block w-96 border-r border-gray-200 bg-white h-full overflow-hidden">
            {{-- Sidebar Component (Pastikan sidebar ini juga sudah ditranslate di file blade-nya) --}}
            @include('chat.partials.sidebar', [
                'conversations' => $conversations,
                'userType' => $userType,
                'conversation' => $conversation,
            ])
        </div>

        <div class="flex-1 flex flex-col h-full">
            {{-- HEADER --}}
            <div class="bg-white border-b border-gray-100 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center">
                    <a href="{{ route('messages') }}" class="md:hidden mr-4 text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <div class="flex items-center">
                        <div class="relative">
                            <img src="{{ $otherUser->photo_url }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm"
                                alt="{{ $otherUser->full_name }}">
                            <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white">
                            </div>
                        </div>
                        <div class="ml-3">
                            <h2 class="font-bold text-gray-900 text-sm">{{ $otherUser->full_name }}</h2>
                            <p class="text-xs text-gray-600 flex items-center">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                                {{ __('chat_show.status_online') }} -
                                @if ($userType == 'patient')
                                    {{ $otherUser->psychologist->specialization ?? __('chat_show.role_psychologist') }}
                                @elseif($userType == 'psychologist')
                                    {{ __('chat_show.role_patient') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MESSAGES AREA --}}
            <div id="messages-container" class="flex-1 overflow-y-auto p-4 md:p-6 min-h-0"
                style="background-color: #f8fafc;">
                <div class="max-w-3xl mx-auto">
                    @foreach ($messages as $message)
                        @php
                            $createdAt = is_array($message['created_at'])
                                ? \Carbon\Carbon::parse($message['created_at']['date'] ?? $message['created_at'])
                                : \Carbon\Carbon::parse($message['created_at']);
                        @endphp
                        <div
                            class="flex {{ $message['sender_id'] == auth()->id() ? 'justify-end' : 'justify-start' }} mb-4 last:mb-0">
                            <div class="max-w-[70%] lg:max-w-[60%]">
                                @if ($message['sender_id'] != auth()->id())
                                    <p class="text-xs text-gray-500 mb-1 ml-3 font-medium">
                                        {{ $message['sender']['full_name'] }}
                                    </p>
                                @endif

                                <div class="flex items-start gap-2">
                                    @if ($message['sender_id'] != auth()->id())
                                        <img src="{{ $message['sender']['photo_url'] }}"
                                            class="w-8 h-8 rounded-full flex-shrink-0 border border-gray-200 mt-1" />
                                    @endif

                                    <div class="flex-1">
                                        <div
                                            class="{{ $message['sender_id'] == auth()->id() ? 'bg-primary text-white' : 'bg-white text-gray-900' }} rounded-2xl px-4 py-3 shadow-sm border border-gray-100">
                                            @if ($message['attachment_url'] ?? false)
                                                @php
                                                    $fileName = $message['attachment_name'] ?? 'Attachment';
                                                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                    $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                                                    $docExts = ['pdf', 'doc', 'docx', 'txt', 'rtf'];
                                                @endphp

                                                @if (in_array($fileExt, $imageExts))
                                                    <div class="mb-2">
                                                        <img src="{{ $message['attachment_url'] }}"
                                                            class="max-w-full max-h-64 rounded-lg cursor-pointer hover:opacity-90 transition"
                                                            onclick="window.open('{{ $message['attachment_url'] }}', '_blank')"
                                                            alt="{{ $fileName }}"
                                                            onerror="this.onerror=null; this.src='/assets/icons/image-error.svg';">
                                                        <p
                                                            class="text-xs {{ $message['sender_id'] == auth()->id() ? 'text-white/80' : 'text-gray-500' }} mt-1">
                                                            {{ $fileName }}
                                                        </p>
                                                    </div>
                                                    {{-- Cek pesan teks selain default placeholder --}}
                                                    @if ($message['message'] && !in_array($message['message'], ['🖼️ Image', '📄 Document', '📎 Attachment']))
                                                        <p
                                                            class="text-sm break-words leading-relaxed {{ $message['sender_id'] == auth()->id() ? 'text-white' : 'text-gray-900' }} mt-2">
                                                            {{ $message['message'] }}
                                                        </p>
                                                    @endif
                                                @elseif(in_array($fileExt, $docExts))
                                                    @php
                                                        $icon =
                                                            $fileExt === 'pdf'
                                                                ? '📄'
                                                                : (in_array($fileExt, ['doc', 'docx'])
                                                                    ? '📝'
                                                                    : '📄');
                                                    @endphp
                                                    <div
                                                        class="mb-2 p-3 {{ $message['sender_id'] == auth()->id() ? 'bg-white/20' : 'bg-gray-50' }} rounded-lg border {{ $message['sender_id'] == auth()->id() ? 'border-white/30' : 'border-gray-200' }} hover:opacity-90 transition">
                                                        <div class="flex items-center gap-3">
                                                            <div class="text-2xl">{{ $icon }}</div>
                                                            <div class="flex-1 min-w-0">
                                                                <p
                                                                    class="text-sm font-medium {{ $message['sender_id'] == auth()->id() ? 'text-white' : 'text-gray-900' }} truncate">
                                                                    {{ $fileName }}
                                                                </p>
                                                                <div class="flex items-center gap-2 mt-1">
                                                                    <a href="{{ $message['attachment_url'] }}"
                                                                        class="text-xs {{ $message['sender_id'] == auth()->id() ? 'text-white hover:text-white/80' : 'text-primary hover:underline' }} font-medium"
                                                                        target="_blank" download="{{ $fileName }}">
                                                                        {{ __('chat_show.btn_download') }}
                                                                    </a>
                                                                    <span
                                                                        class="text-xs {{ $message['sender_id'] == auth()->id() ? 'text-white/60' : 'text-gray-500' }}">•</span>
                                                                    <span
                                                                        class="text-xs {{ $message['sender_id'] == auth()->id() ? 'text-white/60' : 'text-gray-500' }} uppercase">
                                                                        {{ $fileExt }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if ($message['message'] && !in_array($message['message'], ['🖼️ Image', '📄 Document', '📎 Attachment']))
                                                        <p
                                                            class="text-sm break-words leading-relaxed {{ $message['sender_id'] == auth()->id() ? 'text-white' : 'text-gray-900' }} mt-2">
                                                            {{ $message['message'] }}
                                                        </p>
                                                    @endif
                                                @endif
                                            @else
                                                <p class="text-sm break-words leading-relaxed">{{ $message['message'] }}
                                                </p>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-2 mt-1 {{ $message['sender_id'] == auth()->id() ? 'justify-end' : 'justify-start' }}">
                                            <span class="text-xs {{ $message['sender_id'] == auth()->id() ? 'text-gray-400' : 'text-gray-500' }}">
                                                {{ $createdAt->format('H:i') }}
                                            </span>

                                            @if ($message['sender_id'] === auth()->id())
                                                <span class="text-xs {{ $message['is_read'] ? 'text-gray-400' : 'text-gray-400' }}">
                                                    {{ $message['is_read'] ? __('chat_show.status_read') : __('chat_show.status_sent') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($message['sender_id'] == auth()->id())
                                        <div class="w-8 flex-shrink-0"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- INPUT FOOTER --}}
            <div class="bg-white border-t border-gray-100 p-4 flex-shrink-0">
                <form id="message-form" method="POST" action="{{ route('chat.send', $conversation) }}"
                    enctype="multipart/form-data" class="max-w-3xl mx-auto">
                    @csrf
                    <input type="file" id="file-input" name="attachment" class="hidden"
                        accept="image/*,.pdf,.doc,.docx,.txt">

                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <button type="button" id="attachment-btn"
                                class="text-gray-500 hover:text-gray-700 p-2.5 rounded-xl hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                            </button>

                            <div id="attachment-dropdown"
                                class="absolute bottom-full left-0 mb-2 hidden bg-white rounded-xl shadow-lg border border-gray-200 p-2 w-48 z-50">
                                <button type="button" onclick="document.getElementById('file-input').click()"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ __('chat_show.menu_photo') }}
                                </button>
                                <button type="button" onclick="document.getElementById('file-input').click()"
                                    class="w-full flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    {{ __('chat_show.menu_document') }}
                                </button>
                            </div>
                        </div>

                        {{-- Emoji --}}
                        <div class="relative">
                            <button type="button" id="emoji-btn"
                                class="text-gray-500 hover:text-gray-700 p-2.5 rounded-xl hover:bg-gray-100 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>

                            <div id="emoji-picker"
                                class="absolute bottom-full left-0 mb-2 hidden bg-white rounded-xl shadow-lg border border-gray-200 p-3 w-64 z-50">
                                <div class="grid grid-cols-8 gap-1 mb-2">
                                    @php
                                        $emojis = [
                                            '😀',
                                            '😂',
                                            '🥰',
                                            '😎',
                                            '😢',
                                            '😡',
                                            '👍',
                                            '👏',
                                            '❤️',
                                            '🔥',
                                            '🎉',
                                            '🤔',
                                            '👀',
                                            '💯',
                                            '🙏',
                                            '😴',
                                        ];
                                    @endphp
                                    @foreach ($emojis as $emoji)
                                        <button type="button" class="emoji-btn text-lg hover:bg-gray-100 rounded p-1"
                                            data-emoji="{{ $emoji }}">
                                            {{ $emoji }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Input Field --}}
                        <div class="flex-1 relative">
                            <textarea name="message" id="message-input" placeholder="{{ __('chat_show.placeholder_message') }}"
                                class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-primary focus:border-transparent focus:bg-white transition placeholder-gray-500 text-sm resize-none min-h-[44px] max-h-32"
                                autocomplete="off" autofocus rows="1" oninput="autoResize(this)"></textarea>

                            <div id="file-preview" class="hidden mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span id="file-name" class="text-sm text-gray-700 truncate max-w-xs"></span>
                                    </div>
                                    <button type="button" onclick="removeFile()"
                                        class="text-gray-500 hover:text-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" id="send-button"
                            class="bg-primary text-white rounded-xl p-3.5 hover:bg-primary-dark transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
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
            // DEFINE JS TRANSLATIONS
            const LANG_CHAT = {
                msgImage: "{{ __('chat_show.msg_image') }}",
                msgDoc: "{{ __('chat_show.msg_document') }}",
                msgAttach: "{{ __('chat_show.msg_attachment') }}",
                btnDownload: "{{ __('chat_show.btn_download') }}",
                btnDownloadFile: "{{ __('chat_show.btn_download_file') }}",
                alertEmpty: "{{ __('chat_show.js_empty_msg') }}",
                alertFail: "{{ __('chat_show.js_failed_send') }}",
                alertFailGeneric: "{{ __('chat_show.js_failed_generic') }}",
                statusRead: "{{ __('chat_show.status_read') }}",
                statusSent: "{{ __('chat_show.status_sent') }}"
            };

            function autoResize(textarea) {
                textarea.style.height = 'auto';
                textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
            }

            function scrollToBottom() {
                const messagesContainer = document.getElementById('messages-container');
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            document.addEventListener('click', function(e) {
                if (!e.target.closest('#attachment-btn') && !e.target.closest('#attachment-dropdown')) {
                    document.getElementById('attachment-dropdown').classList.add('hidden');
                }
                if (!e.target.closest('#emoji-btn') && !e.target.closest('#emoji-picker')) {
                    document.getElementById('emoji-picker').classList.add('hidden');
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const messageForm = document.getElementById('message-form');
                const messageInput = document.getElementById('message-input');
                const sendButton = document.getElementById('send-button');
                const messagesContainer = document.getElementById('messages-container');
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                scrollToBottom();

                if (messageInput) {
                    messageInput.focus();
                    messageInput.addEventListener('input', function() {
                        autoResize(this);
                    });
                }

                // Toggle Dropdown Logics (Attachment & Emoji)
                const attachmentBtn = document.getElementById('attachment-btn');
                if (attachmentBtn) {
                    attachmentBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const dropdown = document.getElementById('attachment-dropdown');
                        const emojiPicker = document.getElementById('emoji-picker');
                        dropdown.classList.toggle('hidden');
                        if (emojiPicker) emojiPicker.classList.add('hidden');
                    });
                }

                const emojiBtn = document.getElementById('emoji-btn');
                if (emojiBtn) {
                    emojiBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const picker = document.getElementById('emoji-picker');
                        const dropdown = document.getElementById('attachment-dropdown');
                        picker.classList.toggle('hidden');
                        if (dropdown) dropdown.classList.add('hidden');
                    });
                }

                document.querySelectorAll('.emoji-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const textarea = document.getElementById('message-input');
                        const emoji = this.getAttribute('data-emoji');
                        const cursorPos = textarea.selectionStart;
                        textarea.value = textarea.value.substring(0, cursorPos) + emoji + textarea.value
                            .substring(cursorPos);
                        textarea.focus();
                        textarea.selectionStart = textarea.selectionEnd = cursorPos + emoji.length;
                        autoResize(textarea);
                        document.getElementById('emoji-picker').classList.add('hidden');
                    });
                });

                const fileInput = document.getElementById('file-input');
                if (fileInput) {
                    fileInput.addEventListener('change', function(e) {
                        if (this.files && this.files[0]) {
                            const file = this.files[0];
                            document.getElementById('file-name').textContent = file.name;
                            document.getElementById('file-preview').classList.remove('hidden');
                        }
                    });
                }

                window.removeFile = function() {
                    document.getElementById('file-input').value = '';
                    document.getElementById('file-preview').classList.add('hidden');
                };

                if (messageForm) {
                    messageForm.addEventListener('submit', async function(e) {
                        e.preventDefault();

                        const messageInput = document.getElementById('message-input');
                        const fileInput = document.getElementById('file-input');
                        const message = messageInput.value.trim();
                        const file = fileInput.files[0];

                        if (!message && !file) {
                            alert(LANG_CHAT.alertEmpty);
                            return;
                        }

                        const sendButton = document.getElementById('send-button');
                        sendButton.disabled = true;
                        const originalHTML = sendButton.innerHTML;
                        sendButton.innerHTML =
                            `<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>`;

                        try {
                            const formData = new FormData(this);

                            if (!message && file) {
                                const fileExt = file.name.split('.').pop().toLowerCase();
                                const isImage = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt);
                                const isDoc = ['pdf', 'doc', 'docx'].includes(fileExt);

                                if (isImage) formData.set('message', LANG_CHAT.msgImage);
                                else if (isDoc) formData.set('message', LANG_CHAT.msgDoc);
                                else formData.set('message', LANG_CHAT.msgAttach);
                            }

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
                                messageInput.style.height = 'auto';
                                removeFile();

                                if (data.message) {
                                    addNewMessageToUI(data.message);
                                    updateSidebarConversation(data.message);
                                }
                            } else {
                                throw new Error(data.message || LANG_CHAT.alertFailGeneric);
                            }

                        } catch (err) {
                            console.error('Error:', err);
                            alert(LANG_CHAT.alertFail);
                        } finally {
                            sendButton.disabled = false;
                            sendButton.innerHTML = originalHTML;
                        }
                    });
                }

                // Enter to send
                if (messageInput) {
                    messageInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' && !e.shiftKey) {
                            e.preventDefault();
                            if (messageForm) {
                                messageForm.dispatchEvent(new Event('submit'));
                            }
                        }
                    });
                }

                // Function to add new message
                window.addNewMessageToUI = function(messageData) {
                    const isSent = {{ auth()->id() }} === messageData.sender_id;
                    const time = new Date(messageData.created_at).toLocaleTimeString('en-US', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    });

                    let messageContent = '';

                    if (messageData.attachment_url) {
                        const url = messageData.attachment_url;
                        const fileName = messageData.attachment_name || 'Attachment';
                        const fileExt = fileName.split('.').pop().toLowerCase();
                        const imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
                        const docExts = ['pdf', 'doc', 'docx', 'txt', 'rtf'];

                        if (imageExts.includes(fileExt)) {
                            messageContent = `
                                <div class="mb-2">
                                    <img src="${url}" class="max-w-full max-h-64 rounded-lg cursor-pointer hover:opacity-90 transition" onclick="window.open('${url}', '_blank')" alt="${fileName}" onerror="this.onerror=null; this.src='/assets/icons/image-error.svg';">
                                    <p class="text-xs ${isSent ? 'text-white/80' : 'text-gray-500'} mt-1">${fileName}</p>
                                </div>
                                ${messageData.message && ![LANG_CHAT.msgImage, LANG_CHAT.msgDoc, LANG_CHAT.msgAttach].includes(messageData.message) ? `<p class="text-sm break-words leading-relaxed ${isSent ? 'text-white' : 'text-gray-900'} mt-2">${messageData.message}</p>` : ''}
                            `;
                        } else if (docExts.includes(fileExt)) {
                            const icon = fileExt === 'pdf' ? '📄' : (['doc', 'docx'].includes(fileExt) ? '📝' : '📄');
                            messageContent = `
                                <div class="mb-2 p-3 ${isSent ? 'bg-white/20' : 'bg-gray-50'} rounded-lg border ${isSent ? 'border-white/30' : 'border-gray-200'} hover:opacity-90 transition">
                                    <div class="flex items-center gap-3">
                                        <div class="text-2xl">${icon}</div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium ${isSent ? 'text-white' : 'text-gray-900'} truncate">${fileName}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <a href="${url}" class="text-xs ${isSent ? 'text-white hover:text-white/80' : 'text-primary hover:underline'} font-medium" target="_blank" download="${fileName}">
                                                    ${LANG_CHAT.btnDownload}
                                                </a>
                                                <span class="text-xs ${isSent ? 'text-white/60' : 'text-gray-500'}">•</span>
                                                <span class="text-xs ${isSent ? 'text-white/60' : 'text-gray-500'} uppercase">
                                                    ${fileExt}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ${messageData.message && ![LANG_CHAT.msgImage, LANG_CHAT.msgDoc, LANG_CHAT.msgAttach].includes(messageData.message) ? `<p class="text-sm break-words leading-relaxed ${isSent ? 'text-white' : 'text-gray-900'} mt-2">${messageData.message}</p>` : ''}
                            `;
                        }
                    } else {
                        messageContent = `<p class="text-sm break-words leading-relaxed">${messageData.message}</p>`;
                    }

                    const statusText = messageData.is_read ? LANG_CHAT.statusRead : LANG_CHAT.statusSent;
                    const statusColor = messageData.is_read ? 'text-gray-400' : 'text-gray-400';

                    const messageHtml = `
                        <div class="flex ${isSent ? 'justify-end' : 'justify-start'} mb-4 last:mb-0">
                            <div class="max-w-[70%] lg:max-w-[60%]">
                                ${!isSent ? `<p class="text-xs text-gray-500 mb-1 ml-3 font-medium">${messageData.sender.full_name}</p>` : ''}

                                <div class="flex items-start gap-2">
                                    ${!isSent ? `<img src="${messageData.sender.photo_url}" class="w-8 h-8 rounded-full flex-shrink-0 border border-gray-200 mt-1" />` : ''}

                                    <div class="flex-1">
                                        <div class="${isSent ? 'bg-primary text-white' : 'bg-white text-gray-900'} rounded-2xl px-4 py-3 shadow-sm border border-gray-100">
                                            ${messageContent}
                                        </div>

                                        <div class="flex items-center gap-2 mt-1 ${isSent ? 'justify-end' : 'justify-start'}">
                                            <span class="text-xs ${isSent ? 'text-gray-400' : 'text-gray-500'}">
                                                ${time}
                                            </span>

                                            ${isSent ?
                                                `<span class="text-xs ${statusColor}">
                                                    ${statusText}
                                                </span>`
                                                : ''
                                            }
                                        </div>
                                    </div>

                                    ${isSent ? '<div class="w-8 flex-shrink-0"></div>' : ''}
                                </div>
                            </div>
                        </div>
                    `;

                    if (messagesContainer) {
                        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
                        const isNearBottom = messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight < 200;
                        if (isNearBottom) {
                            scrollToBottom();
                        }
                    }
                };

                let lastMessageId = {{ !empty($messages) && end($messages) ? end($messages)['id'] : 0 }};
                let isPolling = false;
                let pollingTimeout = null;

                function pollForNewMessages() {
                    if (isPolling) return;
                    isPolling = true;
                    clearTimeout(pollingTimeout);

                    pollingTimeout = setTimeout(async () => {
                        try {
                            const response = await fetch('{{ route('chat.messages', $conversation) }}', {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });

                            if (response.ok) {
                                const messages = await response.json();
                                const newMessages = messages.filter(msg => msg.id > lastMessageId && msg
                                    .sender_id !== {{ auth()->id() }});

                                if (newMessages.length > 0) {
                                    newMessages.forEach(msg => addNewMessageToUI(msg));
                                    lastMessageId = Math.max(...messages.map(msg => msg.id));
                                }
                            }
                        } catch (err) {
                            console.error('Polling error:', err);
                        } finally {
                            isPolling = false;
                            pollForNewMessages();
                        }
                    }, 3000);
                }

                @if (
                    !empty($messages) &&
                        is_array($lastMessage = end($messages)) &&
                        isset($lastMessage['sender_id']) &&
                        $lastMessage['sender_id'] != auth()->id())
                    @php $messages = array_values($messages); @endphp
                    pollForNewMessages();
                @endif
            });
        </script>
    @endpush
@endsection
