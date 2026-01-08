{{-- File: resources/views/settings/tabs/professional.blade.php --}}

{{-- === 1. FORM INFORMASI UMUM === --}}
<form id="professionalForm" method="POST" action="{{ route('profile.professional.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    <h3 class="text-lg font-semibold mt-0">{{ __('settings.section_general') }}</h3>

    {{-- Professional Title --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_title') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="text" name="title" placeholder="{{ __('settings.placeholder_title') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('title', $psychologist->title ?? '') }}" readonly
                data-original-value="{{ $psychologist->title ?? '' }}">
        </div>
        @error('title')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Specialization --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_spec') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="text" name="specialization" placeholder="{{ __('settings.placeholder_spec') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('specialization', $psychologist->specialization ?? '') }}" readonly data-readonly="true"
                data-original-value="{{ $psychologist->specialization ?? '' }}">
        </div>
        @error('specialization')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- License Number --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_license') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="text" name="license_number" placeholder="{{ __('settings.placeholder_license') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('license_number', $psychologist->license_number ?? '') }}" readonly data-readonly="true"
                data-original-value="{{ $psychologist->license_number ?? '' }}">
        </div>
        @error('license_number')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Years of Experience --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_exp_years') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="number" name="years_experience" placeholder="{{ __('settings.placeholder_exp_years') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('years_experience', $psychologist->years_experience ?? 0) }}" readonly
                data-readonly="true" data-original-value="{{ $psychologist->years_experience ?? 0 }}" min="0">
        </div>
        @error('years_experience')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Consultation Fee --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_fee') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="number" name="consultation_fee" placeholder="{{ __('settings.placeholder_fee') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('consultation_fee', $psychologist->consultation_fee ?? 0) }}" readonly
                data-readonly="true" data-original-value="{{ $psychologist->consultation_fee ?? 0 }}" min="0"
                step="10000">
        </div>
        @error('consultation_fee')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Languages --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_languages') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <select name="languages[]" multiple
                class="w-full outline-none text-black bg-transparent appearance-none disabled:text-[#A1AAB2] disabled:opacity-50 text-sm sm:text-base cursor-pointer disabled:cursor-default"
                disabled data-disabled="true" data-original-value="{{ json_encode($psychologist->languages ?? []) }}">
                <option value="indonesian"
                    {{ in_array('indonesian', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>
                    {{ __('settings.lang_indonesian') }}</option>
                <option value="english"
                    {{ in_array('english', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>
                    {{ __('settings.lang_english') }}</option>
                <option value="javanese"
                    {{ in_array('javanese', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>
                    {{ __('settings.lang_javanese') }}</option>
                <option value="sundanese"
                    {{ in_array('sundanese', old('languages', $psychologist->languages ?? [])) ? 'selected' : '' }}>
                    {{ __('settings.lang_sundanese') }}</option>
            </select>
            <svg id="languagesArrow"
                class="w-5 h-5 text-black opacity-0 transition-opacity duration-200 flex-shrink-0 pointer-events-none"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        @error('languages')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Short Bio --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_short_bio') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <textarea name="short_bio" id="shortBioInput" placeholder="{{ __('settings.placeholder_short_bio') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] resize-none h-32 text-sm sm:text-base"
                readonly data-original-value="{{ $psychologist->short_bio ?? '' }}" maxlength="500">{{ old('short_bio', $psychologist->short_bio ?? '') }}</textarea>
        </div>
        @error('short_bio')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- About Me --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="block text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_about_me') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <textarea name="about_me" id="aboutMeInput" placeholder="{{ __('settings.placeholder_about_me') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] resize-none h-64 text-sm sm:text-base"
                readonly data-original-value="{{ $psychologist->about_me ?? '' }}">{{ old('about_me', $psychologist->about_me ?? '') }}</textarea>
        </div>
        @error('about_me')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="pt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
        <button type="button" data-cancel-form="professionalForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md text-sm font-medium transition-colors hidden"
            onclick="cancelEdit('professionalForm')">
            {{ __('settings.btn_cancel') }}
        </button>

        <button type="button" data-edit-form="professionalForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md text-sm font-medium transition-colors"
            onclick="toggleEdit('professionalForm')">
            {{ __('settings.btn_edit') }}
        </button>
    </div>
</form>

<hr class="my-4">

{{-- === 2. FORM PENDIDIKAN (EDUCATION) === --}}
<form id="educationForm" method="POST" action="{{ route('profile.education.store') }}" class="space-y-6 mt-8">
    @csrf
    <h3 class="text-lg font-semibold mb-4">{{ __('settings.section_education') }}</h3>

    <div id="educationContainer">
        @php
            $educations = $psychologist->educations ?? [];
        @endphp

        @if (count($educations) > 0)
            @foreach ($educations as $index => $education)
                <div class="education-entry space-y-4 p-4 border rounded-lg mb-4" data-index="{{ $index }}">
                    <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $education->id }}">

                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_degree') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="educations[{{ $index }}][degree]"
                                placeholder="{{ __('settings.placeholder_degree') }}"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('educations.' . $index . '.degree', $education->degree) }}" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_institution') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="educations[{{ $index }}][institution]"
                                placeholder="{{ __('settings.placeholder_institution') }}"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('educations.' . $index . '.institution', $education->institution) }}"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_year') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="educations[{{ $index }}][year]"
                                placeholder="{{ __('settings.placeholder_year') }}"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('educations.' . $index . '.year', $education->year) }}">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-education"
                            data-id="{{ $education->id }}">
                            {{ __('settings.btn_remove_education') }}
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Default Empty Education Form --}}
            <div class="education-entry space-y-4 p-4 border rounded-lg mb-4" data-index="0">
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">{{ __('settings.label_degree') }}</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input type="text" name="educations[0][degree]"
                            placeholder="{{ __('settings.placeholder_degree') }}"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                    </div>
                </div>

                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">{{ __('settings.label_institution') }}</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input type="text" name="educations[0][institution]"
                            placeholder="{{ __('settings.placeholder_institution') }}"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                    </div>
                </div>

                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">{{ __('settings.label_year') }}</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input type="text" name="educations[0][year]"
                            placeholder="{{ __('settings.placeholder_year') }}"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="flex justify-between">
        <button type="button" id="addEducation"
            class="px-4 py-2 hover:bg-gray-300 bg-[#FAFAFA] text-[#4D4D4E] border border-grey-border rounded-md">
            {{ __('settings.btn_add_education') }}
        </button>

        <button type="submit" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md">
            {{ __('settings.btn_save_education') }}
        </button>
    </div>
</form>

<hr class="my-4">

{{-- === 3. FORM PENGALAMAN (EXPERIENCE) === --}}
<form id="experienceForm" method="POST" action="{{ route('profile.experience.store') }}" class="space-y-6 mt-8">
    @csrf
    <h3 class="text-lg font-semibold mb-4">{{ __('settings.section_experience') }}</h3>

    <div id="experienceContainer">
        @php
            $experiences = $psychologist->experiences ?? [];
        @endphp

        @if (count($experiences) > 0)
            @foreach ($experiences as $index => $experience)
                <div class="experience-entry space-y-4 p-4 border rounded-lg mb-4" data-index="{{ $index }}">
                    <input type="hidden" name="experiences[{{ $index }}][id]"
                        value="{{ $experience->id }}">

                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_position') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="experiences[{{ $index }}][position]"
                                placeholder="{{ __('settings.placeholder_position') }}"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('experiences.' . $index . '.position', $experience->position) }}"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_org') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="experiences[{{ $index }}][organization]"
                                placeholder="{{ __('settings.placeholder_org') }}"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('experiences.' . $index . '.organization', $experience->organization) }}"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">{{ __('settings.label_start_year') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <input type="text" name="experiences[{{ $index }}][start_year]"
                                    placeholder="2018"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                    value="{{ old('experiences.' . $index . '.start_year', $experience->start_year) }}">
                            </div>
                        </div>
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">{{ __('settings.label_end_year') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <input type="text" name="experiences[{{ $index }}][end_year]"
                                    placeholder="{{ __('settings.placeholder_end_year') }}"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                    value="{{ old('experiences.' . $index . '.end_year', $experience->end_year) }}">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-experience"
                            data-id="{{ $experience->id }}">
                            {{ __('settings.btn_remove_experience') }}
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            {{-- Default Empty Experience Form --}}
            <div class="experience-entry space-y-4 p-4 border rounded-lg mb-4" data-index="0">
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">{{ __('settings.label_position') }}</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input type="text" name="experiences[0][position]"
                            placeholder="{{ __('settings.placeholder_position') }}"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                    </div>
                </div>

                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">{{ __('settings.label_org') }}</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input type="text" name="experiences[0][organization]"
                            placeholder="{{ __('settings.placeholder_org') }}"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_start_year') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="experiences[0][start_year]" placeholder="2018"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                        </div>
                    </div>
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">{{ __('settings.label_end_year') }}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                            style="background-color: #FAFAFA;">
                            <input type="text" name="experiences[0][end_year]"
                                placeholder="{{ __('settings.placeholder_end_year') }}"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="flex justify-between">
        <button type="button" id="addExperience"
            class="px-4 py-2 hover:bg-gray-300 bg-[#FAFAFA] text-[#4D4D4E] border border-grey-border rounded-md">
            {{ __('settings.btn_add_experience') }}
        </button>

        <button type="submit" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md">
            {{ __('settings.btn_save_experience') }}
        </button>
    </div>
</form>

<style>
    .education-entry,
    .experience-entry {
        background-color: #FAFAFA;
    }

    .remove-education,
    .remove-experience {
        font-size: 0.875rem;
        cursor: pointer;
    }
</style>

<script>
    // OBJECT TRANSLATION UNTUK JS DINAMIS
    const LANG_PROF = {
        // Labels
        labelDegree: "{{ __('settings.label_degree') }}",
        phDegree: "{{ __('settings.placeholder_degree') }}",
        labelInst: "{{ __('settings.label_institution') }}",
        phInst: "{{ __('settings.placeholder_institution') }}",
        labelYear: "{{ __('settings.label_year') }}",
        phYear: "{{ __('settings.placeholder_year') }}",
        btnRemoveEdu: "{{ __('settings.btn_remove_education') }}",

        labelPos: "{{ __('settings.label_position') }}",
        phPos: "{{ __('settings.placeholder_position') }}",
        labelOrg: "{{ __('settings.label_org') }}",
        phOrg: "{{ __('settings.placeholder_org') }}",
        labelStart: "{{ __('settings.label_start_year') }}",
        labelEnd: "{{ __('settings.label_end_year') }}",
        phEnd: "{{ __('settings.placeholder_end_year') }}",
        btnRemoveExp: "{{ __('settings.btn_remove_experience') }}",

        // Alerts
        alertMinEdu: "{{ __('settings.alert_min_edu') }}",
        alertMinExp: "{{ __('settings.alert_min_exp') }}",
        confirmRemoveEdu: "{{ __('settings.confirm_remove_edu') }}",
        confirmRemoveExp: "{{ __('settings.confirm_remove_exp') }}"
    };

    document.addEventListener('DOMContentLoaded', function() {
        // === LOGIC PENDIDIKAN ===
        let educationIndex = {{ count($educations) > 0 ? count($educations) : 1 }};
        const educationContainer = document.getElementById('educationContainer');
        const addEducationBtn = document.getElementById('addEducation');

        if (addEducationBtn) {
            addEducationBtn.addEventListener('click', function() {
                // Template string menggunakan variable LANG_PROF
                const template = `
                <div class="education-entry space-y-4 p-4 border rounded-lg mb-4" data-index="${educationIndex}">
                    <hr class="my-4">
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">${LANG_PROF.labelDegree}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input type="text" name="educations[${educationIndex}][degree]" 
                                   placeholder="${LANG_PROF.phDegree}" 
                                   class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">${LANG_PROF.labelInst}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input type="text" name="educations[${educationIndex}][institution]" 
                                   placeholder="${LANG_PROF.phInst}" 
                                   class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">${LANG_PROF.labelYear}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input type="text" name="educations[${educationIndex}][year]" 
                                   placeholder="${LANG_PROF.phYear}" 
                                   class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-education" onclick="this.closest('.education-entry').remove()">
                            ${LANG_PROF.btnRemoveEdu}
                        </button>
                    </div>
                </div>
            `;

                educationContainer.insertAdjacentHTML('beforeend', template);
                educationIndex++;
            });
        }

        // Handle remove buttons for existing Education entries
        educationContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-education')) {
                const totalEducation = educationContainer.querySelectorAll('.education-entry').length;
                if (totalEducation <= 1) {
                    alert(LANG_PROF.alertMinEdu);
                    return;
                }

                const educationId = e.target.dataset.id;

                if (educationId) {
                    if (confirm(LANG_PROF.confirmRemoveEdu)) {
                        fetch(`/profile/education/${educationId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    e.target.closest('.education-entry').remove();
                                }
                            });
                    }
                } else {
                    e.target.closest('.education-entry').remove();
                }
            }
        });

        // === LOGIC PENGALAMAN ===
        let experienceIndex = {{ count($experiences) > 0 ? count($experiences) : 1 }};
        const experienceContainer = document.getElementById('experienceContainer');
        const addExperienceBtn = document.getElementById('addExperience');

        if (addExperienceBtn) {
            addExperienceBtn.addEventListener('click', function() {
                const template = `
                <div class="experience-entry space-y-4 p-4 border rounded-lg mb-4" data-index="${experienceIndex}">
                    <hr class="my-4">
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">${LANG_PROF.labelPos}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input type="text" name="experiences[${experienceIndex}][position]" 
                                   placeholder="${LANG_PROF.phPos}" 
                                   class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">${LANG_PROF.labelOrg}</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input type="text" name="experiences[${experienceIndex}][organization]" 
                                   placeholder="${LANG_PROF.phOrg}" 
                                   class="w-full outline-none text-black placeholder-gray-400 bg-transparent" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">${LANG_PROF.labelStart}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input type="text" name="experiences[${experienceIndex}][start_year]" placeholder="2018" class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                            </div>
                        </div>
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">${LANG_PROF.labelEnd}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input type="text" name="experiences[${experienceIndex}][end_year]" placeholder="${LANG_PROF.phEnd}" class="w-full outline-none text-black placeholder-gray-400 bg-transparent">
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-experience" onclick="this.closest('.experience-entry').remove()">
                            ${LANG_PROF.btnRemoveExp}
                        </button>
                    </div>
                </div>
            `;

                experienceContainer.insertAdjacentHTML('beforeend', template);
                experienceIndex++;
            });
        }

        // Handle remove buttons for existing Experience entries
        experienceContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-experience')) {
                const totalExperience = experienceContainer.querySelectorAll('.experience-entry')
                    .length;
                if (totalExperience <= 1) {
                    alert(LANG_PROF.alertMinExp);
                    return;
                }

                const experienceId = e.target.dataset.id;
                if (experienceId) {
                    if (confirm(LANG_PROF.confirmRemoveExp)) {
                        fetch(`/profile/experience/${experienceId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute('content'),
                                    'Content-Type': 'application/json'
                                }
                            })
                            .then(response => {
                                if (response.ok) {
                                    e.target.closest('.experience-entry').remove();
                                }
                            });
                    }
                } else {
                    e.target.closest('.experience-entry').remove();
                }
            }
        });
    });
</script>
