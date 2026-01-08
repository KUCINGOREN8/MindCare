<form id="preferencesForm" method="POST" action="{{ route('profile.preferences.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="flex flex-col sm:grid sm:grid-cols-[minmax(120px,20%)_1fr] gap-2 sm:gap-0 sm:items-center">
        {{-- TRANSLATE LABEL --}}
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_preferred_lang') }}</label>

        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <select name="language"
                class="w-full outline-none bg-transparent text-black appearance-none disabled:text-[#A1AAB2] disabled:opacity-100 text-sm sm:text-base cursor-pointer disabled:cursor-default"
                disabled data-disabled="true" data-original-value="{{ $user->preferred_language ?? '' }}">
                {{-- TRANSLATE PLACEHOLDER & OPTIONS --}}
                <option value="" disabled {{ !$user->preferred_language ? 'selected' : '' }}
                    class="text-gray-400">
                    {{ __('settings.placeholder_lang') }}
                </option>

                <option value="en" {{ old('language', $user->preferred_language) == 'en' ? 'selected' : '' }}
                    class="text-black">
                    {{ __('settings.lang_en') }}
                </option>

                <option value="id" {{ old('language', $user->preferred_language) == 'id' ? 'selected' : '' }}
                    class="text-black">
                    {{ __('settings.lang_id') }}
                </option>
            </select>

            <svg class="w-5 h-5 text-black opacity-0 transition-opacity duration-200 flex-shrink-0 pointer-events-none"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        @error('language')
            <p class="text-red-500 text-xs mt-1 ml-1 sm:col-start-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
        {{-- TRANSLATE BUTTONS --}}
        <button type="button" data-cancel-form="preferencesForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md text-sm font-medium transition-colors hidden"
            onclick="cancelEdit('preferencesForm')">
            {{ __('settings.btn_cancel') }}
        </button>

        <button type="button" data-edit-form="preferencesForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md text-sm font-medium transition-colors"
            onclick="toggleEdit('preferencesForm')">
            {{ __('settings.btn_edit') }}
        </button>
    </div>
</form>
