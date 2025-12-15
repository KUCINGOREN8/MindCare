@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
    <div class="min-h-screen bg-gray-50">
        @include('auth.partials.progress')

        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-2" style="color: #009C8F;">{{ __('messages.personalinfo') }}</h2>
                <p class="text-gray-600 text-center mb-8">Step 2: {{ __('messages.personalinfodesc') }}</p>

                <form method="POST" action="{{ route('psychologist.signup.storeStep2', $user) }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label class="block text-gray-700 mb-2">{{ __('messages.profesionaltitle') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <i class="fas fa-user-md text-[#A1AAB2] opacity-50 mr-4"></i>
                                <input type="text" name="title" required
                                    class="w-full outline-none text-black bg-transparent"
                                    placeholder="Clinical Psychologist"
                                    value="{{ old('title', $psychologist->title ?? '') }}">
                            </div>
                            @error('title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Specialization -->
                        <div>
                            <label class="block text-gray-700 mb-2">{{ __('messages.specialization') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <i class="fas fa-stethoscope text-[#A1AAB2] opacity-50 mr-4"></i>
                                <input type="text" name="specialization" required
                                    class="w-full outline-none text-black bg-transparent"
                                    placeholder="Anxiety, CBT, Relationship"
                                    value="{{ old('specialization', $psychologist->specialization ?? '') }}">
                            </div>
                            @error('specialization')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- License Number -->
                        <div>
                            <label class="block text-gray-700 mb-2">{{ __('messages.licensenumber') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <i class="fas fa-id-card text-[#A1AAB2] opacity-50 mr-4"></i>
                                <input type="text" name="license_number" required
                                    class="w-full outline-none text-black bg-transparent" placeholder="PSY-198432"
                                    value="{{ old('license_number', $psychologist->license_number ?? '') }}">
                            </div>
                            @error('license_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Years of Experience -->
                        <div>
                            <label class="block text-gray-700 mb-2">{{ __('messages.yearspractice') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <i class="fas fa-briefcase text-[#A1AAB2] opacity-50 mr-4"></i>
                                <input type="number" name="years_experience" required min="0"
                                    class="w-full outline-none text-black bg-transparent" placeholder="0"
                                    value="{{ old('years_experience', $psychologist->years_experience ?? 0) }}">
                            </div>
                            @error('years_experience')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Consultation Fee -->
                        <div>
                            <label class="block text-gray-700 mb-2">{{ __('messages.consultationfee') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <i class="fas fa-money-bill-wave text-[#A1AAB2] opacity-50 mr-4"></i>
                                <input type="number" name="consultation_fee" required min="10000" step="10000"
                                    class="w-full outline-none text-black bg-transparent" placeholder="200000"
                                    value="{{ old('consultation_fee', $psychologist->consultation_fee ?? '') }}">
                                <span class="text-gray-500 ml-2">IDR</span>
                            </div>
                            @error('consultation_fee')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Languages -->
                        <div>
                            <label class="block text-gray-700 mb-2">{{ __('messages.languagespoken') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-language text-[#A1AAB2] opacity-50 mr-4"></i>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $selectedLanguages = old('languages', $psychologist->languages ?? []);
                                        @endphp
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="languages[]" value="English" class="mr-2"
                                                style="accent-color: #009C8F;"
                                                {{ in_array('English', $selectedLanguages) ? 'checked' : '' }}>
                                            <span>{{ __('messages.english') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="languages[]" value="Indonesia" class="mr-2"
                                                style="accent-color: #009C8F;"
                                                {{ in_array('Indonesia', $selectedLanguages) ? 'checked' : '' }}>
                                            <span>{{ __('messages.indonesian') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="languages[]" value="Mandarin" class="mr-2"
                                                style="accent-color: #009C8F;"
                                                {{ in_array('Mandarin', $selectedLanguages) ? 'checked' : '' }}>
                                            <span>{{ __('messages.mandarin') }}</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="languages[]" value="Japanese" class="mr-2"
                                                style="accent-color: #009C8F;"
                                                {{ in_array('Japanese', $selectedLanguages) ? 'checked' : '' }}>
                                            <span>{{ __('messages.japanese') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('languages')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Short Bio -->
                        <div class="col-span-2">
                            <label class="block text-gray-700 mb-2">{{ __('messages.shortbio') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <textarea name="short_bio" required rows="3" class="w-full outline-none text-black bg-transparent resize-none"
                                    placeholder="Brief introduction about yourself and your approach..." maxlength="500">{{ old('short_bio', $psychologist->short_bio ?? '') }}</textarea>
                            </div>
                            @error('short_bio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- About Me -->
                        <div class="col-span-2">
                            <label class="block text-gray-700 mb-2">{{ __('messages.aboutme') }} <span
                                    class="text-red-500">*</span></label>
                            <div class="rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <textarea name="about_me" required rows="4" class="w-full outline-none text-black bg-transparent resize-none"
                                    placeholder="Detailed description of your experience, approach, philosophy...">{{ old('about_me', $psychologist->about_me ?? '') }}</textarea>
                            </div>
                            @error('about_me')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="javascript:history.back()"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            ← {{ __('messages.back') }}
                        </a>
                        <button type="submit"
                            class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md">
                            {{ __('messages.next') }} →
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
