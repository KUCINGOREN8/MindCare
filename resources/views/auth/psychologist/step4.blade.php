@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('auth.partials.progress')

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-2" style="color: #009C8F;">Professional Experience</h2>
            <p class="text-gray-600 text-center mb-8">Step 4: Add your work experience</p>

            <form method="POST" action="{{ route('psychologist.signup.storeStep4', $user) }}" id="experienceForm">
                @csrf

                <div id="experienceContainer">
                    <!-- Experience fields will be added here -->
                    <div class="experience-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                                <input type="text" name="experiences[0][position]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., Clinical Psychologist">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Organization <span class="text-red-500">*</span></label>
                                <input type="text" name="experiences[0][organization]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., Serenity Wellness Clinic">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Start Year <span class="text-red-500">*</span></label>
                                <input type="text" name="experiences[0][start_year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., 2014">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">End Year</label>
                                <input type="text" name="experiences[0][end_year]" maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., 2017 (or leave blank for current)">
                                <p class="text-sm text-gray-500 mt-1">Leave blank if currently working here</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-8">
                    <button type="button" onclick="addExperienceField()" class="flex items-center text-[#009C8F] hover:opacity-80">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Add Another Experience
                    </button>

                    <button type="button" onclick="removeExperienceField()" class="flex items-center text-red-500 hover:opacity-80" id="removeExperienceBtn" style="display: none;">
                        <i class="fas fa-minus-circle mr-2"></i>
                        Remove Last
                    </button>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('psychologist.signup.step3', $user) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md">
                        Next: Availability →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let experienceCount = 1;

function addExperienceField() {
    const container = document.getElementById('experienceContainer');
    const newEntry = document.createElement('div');
    newEntry.className = 'experience-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200';
    newEntry.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                <input type="text" name="experiences[${experienceCount}][position]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., Clinical Psychologist">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Organization <span class="text-red-500">*</span></label>
                <input type="text" name="experiences[${experienceCount}][organization]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., Serenity Wellness Clinic">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 mb-2">Start Year <span class="text-red-500">*</span></label>
                <input type="text" name="experiences[${experienceCount}][start_year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., 2014">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">End Year</label>
                <input type="text" name="experiences[${experienceCount}][end_year]" maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., 2017 (or leave blank for current)">
                <p class="text-sm text-gray-500 mt-1">Leave blank if currently working here</p>
            </div>
        </div>
    `;
    container.appendChild(newEntry);
    experienceCount++;

    if (experienceCount > 1) {
        document.getElementById('removeExperienceBtn').style.display = 'flex';
    }
}

function removeExperienceField() {
    if (experienceCount > 1) {
        const container = document.getElementById('experienceContainer');
        container.removeChild(container.lastChild);
        experienceCount--;

        if (experienceCount === 1) {
            document.getElementById('removeExperienceBtn').style.display = 'none';
        }
    }
}
</script>
@endpush
@endsection
