@if(session('success') || session('error'))
<div 
    x-data="{ open: true }"
    x-init="setTimeout(() => open = false, 5000)"
    x-show="open"
    x-transition
    class="fixed bottom-4 right-4 bg-white shadow-lg border border-gray-200 rounded-lg p-4 flex items-start space-x-3 w-80"
>
    <div class="{{ session('error') ? 'text-red-500' : 'text-green-500' }}">
        {{ session('error') ? '❌' : '✔️' }}
    </div>
    <div class="flex-1 text-sm text-gray-700">
        {{ session('success') ?? session('error') }}
    </div>
    @if(session('undo_id'))
    <form action="{{ route('mood.undo') }}" method="POST" class="mr-2">
        @csrf
        <input type="hidden" name="undo_id" value="{{ session('undo_id') }}">
        <button class="text-blue-600 text-xs underline">Undo</button>
    </form>
    @endif
    <button @click="open = false" class="text-gray-400 hover:text-gray-600">✖</button>
</div>
@endif
