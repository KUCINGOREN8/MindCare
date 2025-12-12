@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('auth.partials.progress')

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-2" style="color: #009C8F;">Education Background</h2>
            <p class="text-gray-600 text-center mb-8">Step 3: Add your educational qualifications</p>

            <form method="POST" action="{{ route('psychologist.signup.storeStep3', $user) }}" id="educationForm">
                @csrf

                <div id="educationContainer">
                    <!-- Education fields will be added here -->
                    <div class="education-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Degree <span class="text-red-500">*</span></label>
                                <input type="text" name="educations[0][degree]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., Master of Clinical Psychology">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                                <input type="text" name="educations[0][institution]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., University of Melbourne">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                                <input type="text" name="educations[0][year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., 2012">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-8">
                    <button type="button" onclick="addEducationField()" class="flex items-center text-[#009C8F] hover:opacity-80">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Add Another Education
                    </button>

                    <button type="button" onclick="removeEducationField()" class="flex items-center text-red-500 hover:opacity-80" id="removeEducationBtn" style="display: none;">
                        <i class="fas fa-minus-circle mr-2"></i>
                        Remove Last
                    </button>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('psychologist.signup.step2', $user) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md">
                        Next: Experience →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let educationCount = 1;

function addEducationField() {
    const container = document.getElementById('educationContainer');
    const newEntry = document.createElement('div');
    newEntry.className = 'education-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200';
    newEntry.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 mb-2">Degree <span class="text-red-500">*</span></label>
                <input type="text" name="educations[${educationCount}][degree]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., Master of Clinical Psychology">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                <input type="text" name="educations[${educationCount}][institution]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., University of Melbourne">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                <input type="text" name="educations[${educationCount}][year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="e.g., 2012">
            </div>
        </div>
    `;
    container.appendChild(newEntry);
    educationCount++;

    // Show remove button if more than 1 entry
    if (educationCount > 1) {
        document.getElementById('removeEducationBtn').style.display = 'flex';
    }
}

function removeEducationField() {
    if (educationCount > 1) {
        const container = document.getElementById('educationContainer');
        container.removeChild(container.lastChild);
        educationCount--;

        // Hide remove button if only 1 entry left
        if (educationCount === 1) {
            document.getElementById('removeEducationBtn').style.display = 'none';
        }
    }
}
</script>
@endpush
@endsection
