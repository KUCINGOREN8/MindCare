<div 
    x-data="{
        show: false,
        message: '',
        type: 'success',
        timeout: null,
        init() {
            @if(session('success') || session('error'))
                this.show = true;
                this.message = '{{ session('success') ?? session('error') }}';
                this.type = '{{ session('error') ? 'error' : 'success' }}';
                this.startTimer();
            @endif
        },
        startTimer() {
            this.timeout = setTimeout(() => this.show = false, 5000);
        },
        clearTimer() {
            if (this.timeout) clearTimeout(this.timeout);
        },
        close() {
            this.clearTimer();
            this.show = false;
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform translate-y-2"
    @open-snackbar.window="
        show = true;
        message = $event.detail.message;
        type = $event.detail.type || 'success';
        startTimer();
    "
    @close-snackbar.window="close()"
    class="fixed bottom-4 left-1/2 transform -translate-x-1/2 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-md w-auto"
    :class="{
        'bg-green-500': type === 'success',
        'bg-red-500': type === 'error',
        'bg-yellow-500': type === 'warning',
        'bg-blue-500': type === 'info'
    }"
    x-cloak
>
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <template x-if="type === 'success'">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </template>
            <template x-if="type === 'error'">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </template>
            <span x-text="message" class="text-sm font-medium"></span>
        </div>
        <button @click="close()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>