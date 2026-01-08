@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
    <div class="min-h-screen bg-gray-50 w-full overflow-x-hidden">
        @include('auth.partials.progress')

        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-2" style="color: #009C8F;">
                    {{ __('messages.experience') }}</h2>
                <p class="text-gray-600 text-center mb-8 text-sm md:text-base">Step 4: {{ __('messages.experiencedesc') }}
                </p>

                <form method="POST" action="{{ route('psychologist.signup.storeStep4', $user) }}" id="experienceForm">
                    @csrf

                    <div id="experienceContainer">
                        <div
                            class="experience-entry bg-gray-50 p-4 md:p-6 rounded-lg mb-4 border border-gray-200 relative mt-4">
                            <button type="button"
                                class="remove-experience-btn absolute -top-3 -right-3 md:-top-2 md:-right-2 bg-red-500 text-white rounded-full w-8 h-8 md:w-6 md:h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors"
                                style="{{ $experiences->count() > 1 ? 'display: flex;' : 'display: none;' }}">
                                <svg class="w-4 h-4 md:w-3 md:h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label
                                        class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.position') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="experiences[0][position]" required
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                        placeholder="Clinical Psychologist"
                                        value="{{ old('experiences.0.position', $experiences[0]->position ?? '') }}">
                                    @error('experiences.0.position')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.organization') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="experiences[0][organization]" required
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300"
                                        placeholder="Serenity Wellness Clinic"
                                        value="{{ old('experiences.0.organization', $experiences[0]->organization ?? '') }}">
                                    @error('experiences.0.organization')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label
                                        class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.startyear') }}
                                        <span class="text-red-500">*</span></label>
                                    <input type="text" name="experiences[0][start_year]" required maxlength="4"
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                        placeholder="2022"
                                        value="{{ old('experiences.0.start_year', $experiences[0]->start_year ?? '') }}">
                                    @error('experiences.0.start_year')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label
                                        class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.endyear') }}</label>
                                    <input type="text" name="experiences[0][end_year]" maxlength="4"
                                        class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                        placeholder="2023"
                                        value="{{ old('experiences.0.end_year', $experiences[0]->end_year ?? '') }}">
                                    <p class="text-sm text-gray-500 mt-1">{{ __('messages.leaveblankendyear') }}</p>
                                    @error('experiences.0.end_year')
                                        <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @foreach ($experiences as $index => $experience)
                            @if ($index > 0)
                                <div
                                    class="experience-entry bg-gray-50 p-4 md:p-6 rounded-lg mb-4 border border-gray-200 relative mt-4">
                                    <button type="button"
                                        class="remove-experience-btn absolute -top-3 -right-3 md:-top-2 md:-right-2 bg-red-500 text-white rounded-full w-8 h-8 md:w-6 md:h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors">
                                        <svg class="w-4 h-4 md:w-3 md:h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.position') }}
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="experiences[{{ $index }}][position]"
                                                required
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="Clinical Psychologist"
                                                value="{{ old('experiences.' . $index . '.position', $experience->position) }}">
                                            @error('experiences.' . $index . '.position')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.organization') }}
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="experiences[{{ $index }}][organization]"
                                                required
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="Serenity Wellness Clinic"
                                                value="{{ old('experiences.' . $index . '.organization', $experience->organization) }}">
                                            @error('experiences.' . $index . '.organization')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.startyear') }}
                                                <span class="text-red-500">*</span></label>
                                            <input type="text" name="experiences[{{ $index }}][start_year]"
                                                required maxlength="4"
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="2022"
                                                value="{{ old('experiences.' . $index . '.start_year', $experience->start_year) }}">
                                            @error('experiences.' . $index . '.start_year')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            <label
                                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.endyear') }}</label>
                                            <input type="text" name="experiences[{{ $index }}][end_year]"
                                                maxlength="4"
                                                class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 text-sm md:text-base"
                                                placeholder="2023"
                                                value="{{ old('experiences.' . $index . '.end_year', $experience->end_year) }}">
                                            <p class="text-sm text-gray-500 mt-1">{{ __('messages.leaveblankendyear') }}
                                            </p>
                                            @error('experiences.' . $index . '.end_year')
                                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="mb-8">
                        <button type="button" id="addExperienceBtn"
                            class="flex items-center text-[#009C8F] hover:opacity-80 text-sm md:text-base font-medium">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ __('messages.addexperience') }}
                        </button>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ route('psychologist.signup.step3', $user) }}"
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
