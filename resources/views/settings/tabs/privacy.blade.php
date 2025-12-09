<form id="privacyForm" method="POST" action="{{ route('profile.privacy.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Old Password</label>
        <div class="flex flex-1 items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="password" 
                name="old_password" 
                placeholder="Old Password" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                readonly
                data-readonly="true"
                data-password-field="true"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50" id="password-eye"
                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
            </button>
        </div>
        @error('old_password')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">New Password</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="password" 
                name="new_password" 
                placeholder="New Password" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                readonly
                data-readonly="true"
                data-password-field="true"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50" id="password-eye"
                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
            </button>
        </div>
        @error('new_password')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Confirm Password</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="password" 
                name="confirm_password" 
                placeholder="Confirm Password" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                readonly
                data-readonly="true"
                data-password-field="true"
            >
            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password" class="w-5 h-5 opacity-50" id="password-eye"
                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}"
                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}">
            </button>
            </div>
        @error('confirm_password')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-6 flex space-x-4 justify-end">
        <button 
            type="button" 
            data-cancel-form="privacyForm"
            class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
            onclick="cancelEdit('privacyForm')"
        >
            Cancel
        </button>

        <button 
            type="button" 
            data-edit-form="privacyForm"
            class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
            onclick="toggleEdit('privacyForm')"
        >
            Edit
        </button>
    </div>
</form>