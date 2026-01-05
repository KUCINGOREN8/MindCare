@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
    <div class="min-h-screen bg-gray-50 w-full overflow-x-hidden">
        @include('auth.partials.progress')

        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-2" style="color: #009C8F;">
                    {{ __('messages.education') }}</h2>
                <p class="text-gray-600 text-center mb-8 text-sm md:text-base">Step 3: {{ __('messages.educationdesc') }}</p>

                <form method="POST" action="{{ route('psychologist.signup.storeStep3', $user) }}" id="educationForm">
                    @csrf

                    <div id="educationContainer">
                        <div
                            class="education-entry bg-gray-50 p-4 md:p-6 rounded-lg mb-4 border border-gray-200 relative mt-4">
                            <button type="button"
                                class="remove-education-btn absolute -top-3 -right-3 md:-top-2 md:-right-2 bg-red-500 text-white rounded-full w-8 h-8 md:w-6 md:h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors"
                                style="{{ $educations->count() > 1 ? 'display: flex;' : 'display: none;' }}">
                                <svg class="w-4 h-4 md:w-3 md:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.degree') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="educations[0][degree]" required
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                        placeholder="Master of Psychology"
                                        value="{{ old('educations.0.degree', $educations[0]->degree ?? '') }}">
                                    @error('educations.0.degree')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.institution') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="educations[0][institution]" required
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                        placeholder="BINUS University"
                                        value="{{ old('educations.0.institution', $educations[0]->institution ?? '') }}">
                                    @error('educations.0.institution')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-gray-700 mb-2 text-sm md:text-base">Year <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="educations[0][year]" required maxlength="4"
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                        placeholder="2020"
                                        value="{{ old('educations.0.year', $educations[0]->year ?? '') }}">
                                    @error('educations.0.year')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @foreach ($educations as $index => $education)
                            @if ($index > 0)
                                <div
                                    class="education-entry bg-gray-50 p-4 md:p-6 rounded-lg mb-4 border border-gray-200 relative mt-6 md:mt-4">
                                    <button type="button"
                                        class="remove-education-btn absolute -top-3 -right-3 md:-top-2 md:-right-2 bg-red-500 text-white rounded-full w-8 h-8 md:w-6 md:h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors">
                                        <svg class="w-4 h-4 md:w-3 md:h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.degree') }}
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="educations[{{ $index }}][degree]" required
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="Master of Psychology"
                                                value="{{ old('educations.' . $index . '.degree', $education->degree) }}">
                                            @error('educations.' . $index . '.degree')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.institution') }}
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="educations[{{ $index }}][institution]"
                                                required
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="BINUS University"
                                                value="{{ old('educations.' . $index . '.institution', $education->institution) }}">
                                            @error('educations.' . $index . '.institution')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.year') }}
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="educations[{{ $index }}][year]" required
                                                maxlength="4"
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="2020"
                                                value="{{ old('educations.' . $index . '.year', $education->year) }}">
                                            @error('educations.' . $index . '.year')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mb-8">
                        <button type="button" id="addEducationBtn"
                            class="flex items-center text-[#009C8F] hover:opacity-80 text-sm md:text-base font-medium">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ __('messages.addeducation') }}
                        </button>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('psychologist.signup.step2', $user) }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-center text-sm md:text-base">
                            ← {{ __('messages.back') }}
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md text-sm md:text-base">
                            {{ __('messages.next') }} →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
