<form id="privacyForm" method="POST" action="{{ route('profile.privacy.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- OLD PASSWORD --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_old_pass') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="password" name="old_password" placeholder="{{ __('settings.placeholder_old_pass') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                readonly data-readonly="true" data-password-field="true" required>
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50"
                    id="password-eye" data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
            </button>
        </div>
        @error('old_password')
            <p class="text-red-500 text-xs mt-1 ml-1 sm:col-start-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- NEW PASSWORD --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_new_pass') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="password" name="new_password" placeholder="{{ __('settings.placeholder_new_pass') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                readonly data-readonly="true" data-password-field="true" required>
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50"
                    id="password-eye" data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
            </button>
        </div>
        @error('new_password')
            <p class="text-red-500 text-xs mt-1 ml-1 sm:col-start-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- CONFIRM PASSWORD --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_confirm_pass') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="password" name="new_password_confirmation"
                placeholder="{{ __('settings.placeholder_confirm_pass') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                readonly data-readonly="true" data-password-field="true">
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50"
                    id="password-eye" data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
            </button>
        </div>
        @error('new_password_confirmation')
            <p class="text-red-500 text-xs mt-1 ml-1 sm:col-start-2">{{ $message }}</p>
        @enderror
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="pt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
        <button type="button" data-cancel-form="privacyForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md text-sm font-medium transition-colors hidden"
            onclick="cancelEdit('privacyForm')">
            {{ __('settings.btn_cancel') }}
        </button>

        <button type="button" data-edit-form="privacyForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md text-sm font-medium transition-colors"
            onclick="toggleEdit('privacyForm')">
            {{ __('settings.btn_edit') }}
        </button>
    </div>
</form>
