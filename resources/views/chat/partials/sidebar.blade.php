<!-- Sidebar Kiri -->
<div class="p-6 pb-4 border-b border-gray-100">
    <div class="relative mb-6">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        {{-- Search --}}
        <input
            type="text"
            placeholder="Search {{ $userType == 'patient' ? 'psychologist' : 'patient' }}..."
            class="w-full pl-10 pr-4 py-3 border-0 bg-gray-50 rounded-xl focus:ring-2 focus:ring-primary focus:bg-white transition placeholder-gray-500"
            id="sidebar-search"
        >
    </div>

    <div class="flex space-x-6 border-b border-gray-200">
        <button class="pb-3 px-1 font-semibold text-primary border-b-2 border-primary text-sm">
            All
        </button>
        <button class="pb-3 px-1 font-medium text-gray-500 hover:text-gray-700 text-sm transition">
            Unread
        </button>
        <button class="pb-3 px-1 font-medium text-gray-500 hover:text-gray-700 text-sm transition">
            Archived
        </button>
    </div>
</div>

<!-- Chat List -->
<div class="overflow-y-auto flex-1 divide-y divide-gray-100">
    @forelse($conversations as $conv)
        @php
            if ($userType == 'patient') {
                $otherUser = $conv->psychologist;
                $userTitle = $otherUser->psychologist->specialization ?? 'Psychologist';
            } elseif ($userType == 'psychologist') {
                $otherUser = $conv->patient;
                $userTitle = 'Patient';
            }

            $isActive = isset($conversation) && $conversation->id == $conv->id;
            $unreadCount = $conv->messages->where('receiver_id', auth()->id())->where('is_read', false)->count();
        @endphp
        <a
            href="{{ route('chat.show', $conv) }}"
            class="flex items-center gap-3 p-4 hover:bg-gray-50 transition duration-200 {{ $isActive ? 'bg-blue-50 border-r-2 border-primary' : '' }}"
        >
            <!-- User Profile -->
            <div class="relative flex-shrink-0">
                <img
                    src="{{ $otherUser->photo_url ? asset($otherUser->photo_url) : ($otherUser->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm"
                    alt="{{ $otherUser->full_name }}"
                >
                <!-- Online -->
                <div class="absolute bottom-0 right-0 w-3 h-3 {{ optional($conv->last_message_at)->diffInMinutes(now()) < 5 ? 'bg-green-500' : 'bg-gray-400' }} rounded-full border-2 border-white"></div>
            </div>

            <!-- Details -->
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2 mb-1">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-gray-900 text-sm truncate">
                            {{ $otherUser->full_name }}
                        </h4>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $userTitle }}
                        </p>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                        {{ $conv->latestMessage ? $conv->latestMessage->created_at->format('H:i') : '' }}
                    </span>
                </div>

                <!-- Last Message & Unread -->
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm text-gray-600 truncate">
                        @if($conv->latestMessage)
                            {{ Illuminate\Support\Str::limit($conv->latestMessage->message, 25) }}
                        @else
                            <span class="text-gray-400">Start conversation</span>
                        @endif
                    </p>

                    @if($unreadCount > 0)
                        <span class="bg-primary text-white text-xs font-semibold rounded-full w-5 h-5 flex items-center justify-center flex-shrink-0">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </div>
            </div>
        </a>
    @empty
        <div class="flex flex-col items-center justify-center h-full p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No conversations yet</h3>
            <p class="text-gray-500 text-sm">
                @if($userType == 'patient')
                    Start a conversation with a psychologist
                @else
                    Your patients will appear here
                @endif
            </p>
        </div>
    @endforelse
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('sidebar-search');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            const conversationItems = document.querySelectorAll('a[href*="chat"]');

            conversationItems.forEach(item => {
                const userName = item.querySelector('h4').textContent.toLowerCase();
                const userTitle = item.querySelector('p.text-gray-500').textContent.toLowerCase();
                const lastMessage = item.querySelector('p.text-gray-600').textContent.toLowerCase();

                if (userName.includes(searchTerm) || userTitle.includes(searchTerm) || lastMessage.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    const tabs = document.querySelectorAll('.flex.space-x-6 button');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => {
                t.classList.remove('text-primary', 'border-primary', 'font-semibold');
                t.classList.add('text-gray-500', 'font-medium');
            });

            this.classList.remove('text-gray-500', 'font-medium');
            this.classList.add('text-primary', 'border-primary', 'font-semibold');
        });
    });
});
</script>
@endpush
