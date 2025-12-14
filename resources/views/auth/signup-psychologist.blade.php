@extends('layouts.auth')
@section('title', 'Signup Psychologist')

@section('content')
<div class="flex" style="min-height: 100vh;">

    {{-- LEFT FORM --}}
    <div class="w-full lg:w-[70%] flex items-center justify-center bg-white overflow-y-auto px-6 md:px-12 py-16">
        <div class="w-full max-w-lg">

            <h2 class="text-5xl font-bold mb-8 text-center" style="color:#009C8F;">SIGN UP PSYCHOLOGIST</h2>

            <form class="space-y-6" action="{{ route('register.psychologist') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- FULL NAME --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/user.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="full_name" placeholder="Full Name"
                            class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                            value="{{ old('full_name') }}">
                    </div>
                    @error('full_name') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/email.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="email" name="email" placeholder="Email"
                                class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                                value="{{ old('email') }}">
                    </div>
                    @error('email') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/password.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="password" name="password" placeholder="Password"
                                class="w-full bg-transparent outline-none text-black placeholder-gray-400" id="password">
                        <button type="button" onclick="togglePassword('password')">
                            <img src="{{ asset('assets/signup/eye-closed.svg') }}" class="w-5 h-5 opacity-50"
                                    id="password-eye"
                                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}"
                                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}">
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- CONFIRM PASSWORD --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/password.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="password" name="password_confirmation" placeholder="Confirm Password"
                                class="w-full bg-transparent outline-none text-black placeholder-gray-400" id="confirm-password">
                        <button type="button" onclick="togglePassword('confirm-password')">
                            <img src="{{ asset('assets/signup/eye-closed.svg') }}" class="w-5 h-5 opacity-50"
                                    id="confirm-password-eye"
                                    data-open-icon="{{ asset('assets/signup/eye-open.svg') }}"
                                    data-closed-icon="{{ asset('assets/signup/eye-closed.svg') }}">
                        </button>
                    </div>
                </div>

                {{-- DATE OF BIRTH --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/calender.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="date" name="date_of_birth"
                                class="w-full bg-transparent outline-none text-gray-500"
                                onchange="this.style.color='#000000'" value="{{ old('date_of_birth') }}">
                    </div>
                    @error('date_of_birth') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- GENDER --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/gender.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <select name="gender" class="w-full outline-none bg-transparent text-gray-400"
                                onchange="this.style.color=this.value?'#000000':'#9CA3AF'">
                            <option value="" disabled selected>Gender</option>
                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                            <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                        </select>
                    </div>
                    @error('gender') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- PLATFORM LANGUAGE --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/language.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <select name="language" class="w-full outline-none bg-transparent text-gray-400"
                                onchange="this.style.color=this.value?'#000000':'#9CA3AF'">
                            <option value="" disabled selected>Preferred Language (Platform)</option>
                            <option value="en" {{ old('language')=='en'?'selected':'' }}>English</option>
                            <option value="id" {{ old('language')=='id'?'selected':'' }}>Indonesian</option>
                        </select>
                    </div>
                    @error('language') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- ===== PROFESSIONAL INFO ===== --}}
                <h3 class="text-xl font-semibold pt-4" style="color:#009C8F;">Professional Information</h3>

                {{-- TITLE --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/user.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="title" placeholder="Professional Title (e.g., M.Psi, Psikolog)"
                            class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                            value="{{ old('title') }}">
                    </div>
                    @error('title') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- LICENSE --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/card.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="license_number" placeholder="License (STR/Practice ID)"
                                class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                                value="{{ old('license_number') }}">
                    </div>
                    @error('license_number') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- SPECIALIZATION --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/book.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="specialization" placeholder="Specialization (Clinical, Child, etc.)"
                                class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                                value="{{ old('specialization') }}">
                    </div>
                    @error('specialization') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- EXPERIENCE --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/briefcase.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="number" name="years_experience" placeholder="Years of Experience"
                                class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                                value="{{ old('years_experience') }}">
                    </div>
                    @error('years_experience') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- FEE --}}
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/dollar.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="number" step="1000" name="consultation_fee" placeholder="Consultation Fee (e.g., 200000)"
                            class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                            value="{{ old('consultation_fee') }}">
                    </div>
                    @error('consultation_fee') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- SHORT BIO --}}
                <div>
                    <div class="rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <textarea name="short_bio" rows="2" placeholder="Short Bio (Max 255 chars, for profile preview)"
                            class="w-full bg-transparent outline-none text-black placeholder-gray-400 resize-none">{{ old('short_bio') }}</textarea>
                    </div>
                    @error('short_bio') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- ABOUT ME --}}
                <div>
                    <div class="rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <textarea name="about_me" rows="4" placeholder="About Me / Full Profile Description"
                            class="w-full bg-transparent outline-none text-black placeholder-gray-400 resize-none">{{ old('about_me') }}</textarea>
                    </div>
                    @error('about_me') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- LANGUAGES CHECKBOX --}}
                <div class="px-4 py-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Languages Spoken (for consultation)</label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="languages[]" value="en" class="form-checkbox"
                                {{ in_array('en', old('languages', [])) ? 'checked' : '' }} style="accent-color:#009C8F;">
                            <span class="ml-2 text-gray-600">English</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="languages[]" value="id" class="form-checkbox"
                                {{ in_array('id', old('languages', [])) ? 'checked' : '' }} style="accent-color:#009C8F;">
                            <span class="ml-2 text-gray-600">Indonesian</span>
                        </label>
                    </div>
                    @error('languages') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                {{-- TERMS --}}
                <div class="flex items-start pt-2">
                    <input type="checkbox" name="terms" class="w-4 h-4 mt-1 mr-2" style="accent-color:#009C8F;"
                        {{ old('terms') ? 'checked' : '' }}>
                    <label class="text-sm text-gray-500">Agree to Terms & Privacy Policy</label>
                </div>
                @error('terms') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror

                {{-- SUBMIT --}}
                <div class="pt-3">
                    <button class="w-full bg-[#009C8F] text-white py-3 rounded-lg font-medium hover:opacity-90 transition text-base shadow-md">
                        Sign Up
                    </button>
                </div>

                {{-- LOGIN LINK --}}
                <div class="text-center pt-3">
                    <p class="text-gray-600 text-sm">Already have an account?
                        <a href="{{ route('login') }}" class="font-medium underline" style="color:#009C8F;">Login</a>
                    </p>
                </div>

            </form>
        </div>
    </div>

    {{-- RIGHT IMAGE --}}
    <div class="hidden lg:block lg:w-[30%]">
        <img src="{{ asset('assets/signup/right-image.jpg') }}" class="w-full h-full object-cover">
    </div>

</div>
@endsection
