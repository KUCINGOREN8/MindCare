{{-- General Information --}}
<form id="professionalForm" method="POST" action="{{ route('profile.professional.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    <h3 class="text-lg font-semibold mt-0">General Information</h3>
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

<hr class="my-4">

<!-- Education Section -->
<form id="educationForm" method="POST" action="{{ route('profile.education.store') }}" class="space-y-6 mt-8">
    @csrf
    <h3 class="text-lg font-semibold mb-4">Education</h3>
    
    <div id="educationContainer">
        @php
            $educations = $psychologist->educations ?? [];
        @endphp
        
        @if(count($educations) > 0)
            @foreach($educations as $index => $education)
                <div class="education-entry space-y-4 p-4 border rounded-lg mb-4" data-index="{{ $index }}">
                    
                    <input type="hidden" name="educations[{{ $index }}][id]" value="{{ $education->id }}">
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Degree</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="educations[{{ $index }}][degree]"
                                placeholder="e.g., Master of Psychology"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('educations.' . $index . '.degree', $education->degree) }}"
                                required
                            >
                        </div>
                        @error('educations.' . $index . '.degree')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Institution</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="educations[{{ $index }}][institution]"
                                placeholder="e.g., University of Indonesia"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('educations.' . $index . '.institution', $education->institution) }}"
                                required
                            >
                        </div>
                        @error('educations.' . $index . '.institution')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Year</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="educations[{{ $index }}][year]"
                                placeholder="e.g., 2020"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('educations.' . $index . '.year', $education->year) }}"
                            >
                        </div>
                        @error('educations.' . $index . '.year')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-education" data-id="{{ $education->id }}">
                            Remove Education
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="education-entry space-y-4 p-4 border rounded-lg mb-4" data-index="0">
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">Degree</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input 
                            type="text" 
                            name="educations[0][degree]"
                            placeholder="e.g., Master of Psychology"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                            value="{{ old('educations.0.degree') }}"
                            required
                        >
                    </div>
                    @error('educations.0.degree')
                        <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">Institution</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input 
                            type="text" 
                            name="educations[0][institution]"
                            placeholder="e.g., University of Indonesia"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                            value="{{ old('educations.0.institution') }}"
                            required
                        >
                    </div>
                    @error('educations.0.institution')
                        <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">Year</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input 
                            type="text" 
                            name="educations[0][year]"
                            placeholder="e.g., 2020"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                            value="{{ old('educations.0.year') }}"
                        >
                    </div>
                    @error('educations.0.year')
                        <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        @endif
    </div>
    
    <div class="flex justify-between">
        <button type="button" id="addEducation" class="px-4 py-2 hover:bg-gray-300 bg-[#FAFAFA] text-[#4D4D4E] border border-grey-border rounded-md">
            + Add Another Education
        </button>
        
        <button type="submit" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md">
            Save Education
        </button>
    </div>
</form>

<hr class="my-4">

<!-- Experience Section -->
<form id="experienceForm" method="POST" action="{{ route('profile.experience.store') }}" class="space-y-6 mt-8">
    @csrf
    <h3 class="text-lg font-semibold mb-4">Professional Experience</h3>
    
    <div id="experienceContainer">
        @php
            $experiences = $psychologist->experiences ?? [];
        @endphp
        
        @if(count($experiences) > 0)
            @foreach($experiences as $index => $experience)
                <div class="experience-entry space-y-4 p-4 border rounded-lg mb-4" data-index="{{ $index }}">                    
                    <input type="hidden" name="experiences[{{ $index }}][id]" value="{{ $experience->id }}">
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Position</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="experiences[{{ $index }}][position]"
                                placeholder="e.g., Clinical Psychologist"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('experiences.' . $index . '.position', $experience->position) }}"
                                required
                            >
                        </div>
                        @error('experiences.' . $index . '.position')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Organization</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="experiences[{{ $index }}][organization]"
                                placeholder="e.g., XYZ Hospital"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('experiences.' . $index . '.organization', $experience->organization) }}"
                                required
                            >
                        </div>
                        @error('experiences.' . $index . '.organization')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">Start Year</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input 
                                    type="text" 
                                    name="experiences[{{ $index }}][start_year]"
                                    placeholder="e.g., 2018"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                    value="{{ old('experiences.' . $index . '.start_year', $experience->start_year) }}"
                                >
                            </div>
                            @error('experiences.' . $index . '.start_year')
                                <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">End Year</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input 
                                    type="text" 
                                    name="experiences[{{ $index }}][end_year]"
                                    placeholder="e.g., 2023 or Present"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                    value="{{ old('experiences.' . $index . '.end_year', $experience->end_year) }}"
                                >
                            </div>
                            @error('experiences.' . $index . '.end_year')
                                <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-experience" data-id="{{ $experience->id }}">
                            Remove Experience
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="experience-entry space-y-4 p-4 border rounded-lg mb-4" data-index="0">
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">Position</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input 
                            type="text" 
                            name="experiences[0][position]"
                            placeholder="e.g., Clinical Psychologist"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                            value="{{ old('experiences.0.position') }}"
                            required
                        >
                    </div>
                    @error('experiences.0.position')
                        <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-[20%_80%] gap-4">
                    <label class="block text-[#4D4D4E]">Organization</label>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                        <input 
                            type="text" 
                            name="experiences[0][organization]"
                            placeholder="e.g., XYZ Hospital"
                            class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                            value="{{ old('experiences.0.organization') }}"
                            required
                        >
                    </div>
                    @error('experiences.0.organization')
                        <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Start Year</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="experiences[0][start_year]"
                                placeholder="e.g., 2018"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('experiences.0.start_year') }}"
                            >
                        </div>
                        @error('experiences.0.start_year')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">End Year</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="experiences[0][end_year]"
                                placeholder="e.g., 2023 or Present"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                value="{{ old('experiences.0.end_year') }}"
                            >
                        </div>
                        @error('experiences.0.end_year')
                            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    <div class="flex justify-between">
        <button type="button" id="addExperience" class="px-4 py-2 hover:bg-gray-300 bg-[#FAFAFA] text-[#4D4D4E] border border-grey-border rounded-md">
            + Add Another Experience
        </button>
        
        <button type="submit" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md">
            Save Experience
        </button>
    </div>
</form>

<style>
.education-entry, .experience-entry {
    background-color: #FAFAFA;
}

.remove-education, .remove-experience {
    font-size: 0.875rem;
    cursor: pointer;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Education
    let educationIndex = {{ count($educations) }};
    const educationContainer = document.getElementById('educationContainer');
    const addEducationBtn = document.getElementById('addEducation');
    
    if (addEducationBtn) {
        addEducationBtn.addEventListener('click', function() {
            const template = `
                <div class="education-entry space-y-4 p-4 border rounded-lg mb-4" data-index="${educationIndex}">
                    <hr class="my-4">
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Degree</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="educations[${educationIndex}][degree]"
                                placeholder="e.g., Master of Psychology"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Institution</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="educations[${educationIndex}][institution]"
                                placeholder="e.g., University of Indonesia"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Year</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="educations[${educationIndex}][year]"
                                placeholder="e.g., 2020"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                            >
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-education" onclick="this.closest('.education-entry').remove()">
                            Remove Education
                        </button>
                    </div>
                </div>
            `;
            
            educationContainer.insertAdjacentHTML('beforeend', template);
            educationIndex++;
        });
    }
    
    let experienceIndex = {{ count($experiences) }};
    const experienceContainer = document.getElementById('experienceContainer');
    const addExperienceBtn = document.getElementById('addExperience');
    
    if (addExperienceBtn) {
        addExperienceBtn.addEventListener('click', function() {
            const template = `
                <div class="experience-entry space-y-4 p-4 border rounded-lg mb-4" data-index="${experienceIndex}">
                    <hr class="my-4">
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Position</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="experiences[${experienceIndex}][position]"
                                placeholder="e.g., Clinical Psychologist"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-[20%_80%] gap-4">
                        <label class="block text-[#4D4D4E]">Organization</label>
                        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                            <input 
                                type="text" 
                                name="experiences[${experienceIndex}][organization]"
                                placeholder="e.g., XYZ Hospital"
                                class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                required
                            >
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">Start Year</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input 
                                    type="text" 
                                    name="experiences[${experienceIndex}][start_year]"
                                    placeholder="e.g., 2018"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                >
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-[20%_80%] gap-4">
                            <label class="block text-[#4D4D4E]">End Year</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input 
                                    type="text" 
                                    name="experiences[${experienceIndex}][end_year]"
                                    placeholder="e.g., 2023 or Present"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent"
                                >
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="button" class="text-red-500 hover:text-red-700 remove-experience" onclick="this.closest('.experience-entry').remove()">
                            Remove Experience
                        </button>
                    </div>
                </div>
            `;
            
            experienceContainer.insertAdjacentHTML('beforeend', template);
            experienceIndex++;
        });
    }
    
    // Handle remove buttons for existing entries
    educationContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-education')) {
            const totalEducation = educationContainer.querySelectorAll('.education-entry').length;
            if (totalEducation <= 1) {
                alert('At least one education entry must remain.');
                return;
            }

            const educationId = e.target.dataset.id;

            if (educationId) {
                if (confirm('Are you sure you want to remove this education?')) {
                    fetch(`/profile/education/${educationId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
    
    experienceContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-experience')) {
            const experienceId = e.target.dataset.id;
            if (experienceId) {
                if (confirm('Are you sure you want to remove this experience?')) {
                    // AJAX request untuk menghapus dari database
                    fetch(`/experience/${experienceId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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