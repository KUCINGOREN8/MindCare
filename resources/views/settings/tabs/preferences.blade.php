<form id="preferencesForm" method="POST" action="{{ route('profile.preferences.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Preferred Language</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <select
                name="language"
                class="w-full outline-none bg-transparent text-black appearance-none disabled:text-[#A1AAB2] disabled:opacity-100"
                disabled
                data-disabled="true"
                data-original-value="{{ $user->preferred_language ?? '' }}"
            >
                <option value="" disabled {{ !$user->preferred_language ? 'selected' : '' }} class="text-gray-400">Preferred Language</option>
                <option value="en" {{ old('language', $user->preferred_language) == 'en' ? 'selected' : '' }} class="text-black">English</option>
                <option value="id" {{ old('language', $user->preferred_language) == 'id' ? 'selected' : '' }} class="text-black">Indonesian</option>
            </select>
            <svg class="w-5 h-5 text-black opacity-0 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        @error('language')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-6 flex space-x-4 justify-end">
        <button 
            type="button" 
            data-cancel-form="preferencesForm"
            class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
            onclick="cancelEdit('preferencesForm')"
        >
            Cancel
        </button>

        <button 
            type="button" 
            data-edit-form="preferencesForm"
            class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
            onclick="toggleEdit('preferencesForm')"
        >
            Edit
        </button>
    </div>
</form>