<form id="professionalForm" method="POST" action="{{ route('profile.professional.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Professional Title</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="text" 
                name="title" 
                placeholder="e.g., Clinical Psychologist" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('title', $psychologist->title ?? '') }}"
                readonly
                data-original-value="{{ $psychologist->title ?? '' }}"
            >
        </div>
        @error('title')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Specialization</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="text" 
                name="specialization"
                placeholder="e.g., Anxiety, Depression, Trauma" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('specialization', $psychologist->specialization ?? '') }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $psychologist->specialization ?? '' }}"
            >
        </div>
        @error('specialization')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>
    
    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">License Number</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="text" 
                name="license_number" 
                placeholder="Your professional license number" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('license_number', $psychologist->license_number ?? '') }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $psychologist->license_number ?? '' }}"
            >
        </div>
        @error('license_number')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Years of Experience</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="number" 
                name="years_experience" 
                placeholder="e.g., 5" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('years_experience', $psychologist->years_experience ?? 0) }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $psychologist->years_experience ?? 0 }}"
                min="0"
            >
        </div>
        @error('years_experience')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Consultation Fee (Rp)</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="number" 
                name="consultation_fee" 
                placeholder="e.g., 300000" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('consultation_fee', $psychologist->consultation_fee ?? 0) }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $psychologist->consultation_fee ?? 0 }}"
                min="0"
                step="10000"
            >
        </div>
        @error('consultation_fee')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Languages</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <select 
                name="languages[]" 
                multiple
                class="w-full outline-none text-black bg-transparent appearance-none disabled:text-[#A1AAB2] disabled:opacity-50"
                disabled
                data-disabled="true"
                data-original-value="{{ json_encode($psychologist->languages ?? []) }}"
            >
                <option value="indonesian" {{ in_array('indonesian', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>Indonesian</option>
                <option value="english" {{ in_array('english', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>English</option>
                <option value="javanese" {{ in_array('japanese', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>Japanese</option>
                <option value="sundanese" {{ in_array('mandarin', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>Mandarin</option>
            </select>
            <svg id="languagesArrow" class="w-5 h-5 text-black opacity-0 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
        @error('languages')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Short Bio -->
    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Short Bio</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <textarea 
                name="short_bio" 
                id="shortBioInput"
                placeholder="Brief professional introduction (max 500 characters)" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] resize-none h-32"
                readonly
                data-original-value="{{ $psychologist->short_bio ?? '' }}"
                maxlength="500"
            >{{ old('short_bio', $psychologist->short_bio ?? '') }}</textarea>
        </div>
        @error('short_bio')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">About Me</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <textarea 
                name="about_me" 
                id="aboutMeInput"
                placeholder="Detailed professional background and experience" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] resize-none h-64"
                readonly
                data-original-value="{{ $psychologist->about_me ?? '' }}"
            >{{ old('about_me', $psychologist->about_me ?? '') }}</textarea>
        </div>
        @error('about_me')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-6 flex space-x-4 justify-end">
        <button 
            type="button" 
            data-cancel-form="professionalForm"
            class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
            onclick="cancelEdit('professionalForm')"
        >
            Cancel
        </button>

        <button 
            type="button" 
            data-edit-form="professionalForm"
            class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
            onclick="toggleEdit('professionalForm')"
        >
            Edit
        </button>
    </div>
</form>