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
                    <div class="experience-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200 relative">
                        <button type="button" class="remove-experience-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors" style="display: none;">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                                <input type="text" name="experiences[0][position]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="Clinical Psychologist">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">Organization <span class="text-red-500">*</span></label>
                                <input type="text" name="experiences[0][organization]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="Serenity Wellness Clinic">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Start Year <span class="text-red-500">*</span></label>
                                <input type="text" name="experiences[0][start_year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="2022">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">End Year</label>
                                <input type="text" name="experiences[0][end_year]" maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="2023">
                                <p class="text-sm text-gray-500 mt-1">Leave blank if currently working here</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <button type="button" id="addExperienceBtn" class="flex items-center text-[#009C8F] hover:opacity-80">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                        </svg>
                        Add Another Experience
                    </button>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('psychologist.signup.step3', $user) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md">
                        Next →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/step4.js') }}"></script>
@endsection
