@extends('layouts.auth')
@section('title', 'Signup Psychologist')

@section('content')
<div class="flex" style="min-height: 100vh;">
    
    
    <div class="w-full lg:w-[70%] flex items-center justify-center bg-white overflow-y-auto px-6 md:px-12 py-16">
        <div class="w-full max-w-lg">

            <h2 class="text-5xl font-bold mb-8 text-center" style="color:#009C8F;">SIGN UP PSYCHOLOGIST</h2>

            <form class="space-y-6" action="{{ route('register.psychologist') }}" method="POST" enctype="multipart/form-data">
                @csrf

                
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/user.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="full_name" placeholder="Full Name" 
                               class="w-full bg-transparent outline-none text-black placeholder-gray-400" 
                               value="{{ old('full_name') }}">
                    </div>
                    @error('full_name') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/email.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="email" name="email" placeholder="Email" 
                               class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                               value="{{ old('email') }}">
                    </div>
                    @error('email') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                
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

                
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/calender.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="date" name="date_of_birth"
                               class="w-full bg-transparent outline-none text-gray-500"
                               onchange="this.style.color='#000000'" value="{{ old('date_of_birth') }}">
                    </div>
                    @error('date_of_birth') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/gender.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <select name="gender" class="w-full outline-none bg-transparent text-gray-400"
                                onchange="this.style.color=this.value?'#000000':'#9CA3AF'">
                            <option value="" disabled selected class="text-gray-400">Gender</option>
                            <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                            <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                            <option value="other" {{ old('gender')=='other'?'selected':'' }}>Other</option>
                        </select>
                    </div>
                    @error('gender') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/language.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <select name="language" class="w-full outline-none bg-transparent text-gray-400"
                                onchange="this.style.color=this.value?'#000000':'#9CA3AF'">
                            <option value="" disabled selected>Preferred Language</option>
                            <option value="en" {{ old('language')=='en'?'selected':'' }}>English</option>
                            <option value="id" {{ old('language')=='id'?'selected':'' }}>Indonesian</option>
                        </select>
                    </div>
                    @error('language') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/card.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="license_number" placeholder="License (STR/Practice ID)" 
                               class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                               value="{{ old('license_number') }}">
                    </div>
                    @error('license_number') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

               
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/book.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="text" name="specialization" placeholder="Specialization (Clinical, Child, etc.)" 
                               class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                               value="{{ old('specialization') }}">
                    </div>
                    @error('specialization') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

               
                <div>
                    <div class="flex items-center rounded-lg px-4 py-3 shadow-sm bg-[#FAFAFA]">
                        <img src="{{ asset('assets/signup/briefcase.svg') }}" class="w-5 h-5 opacity-50 mr-3">
                        <input type="number" name="years_experience" placeholder="Years of Experience" 
                               class="w-full bg-transparent outline-none text-black placeholder-gray-400"
                               value="{{ old('years_experience') }}">
                    </div>
                    @error('experience_years') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                
               

                
                <div class="flex items-start pt-2">
                    <input type="checkbox" name="terms" class="w-4 h-4 mt-1 mr-2" style="accent-color:#009C8F;">
                    <label class="text-sm text-gray-500">Agree to Terms & Privacy Policy</label>
                </div>
                @error('terms') <p class="text-red-500 text-sm mt-1 ml-1">{{ $message }}</p> @enderror

                
                <div class="pt-3">
                    <button class="w-full bg-[#009C8F] text-white py-3 rounded-lg font-medium hover:opacity-90 transition text-base shadow-md">
                        Sign Up
                    </button>
                </div>

                
                <div class="text-center pt-3">
                    <p class="text-gray-600 text-sm">Already have an account?
                        <a href="{{ route('login') }}" class="font-medium underline" style="color:#009C8F;">Sign in</a>
                    </p>
                </div>

            </form>
        </div>
    </div>

    
    <div class="hidden lg:block lg:w-[30%]">
        <img src="{{ asset('assets/signup/right-image.jpg') }}" class="w-full h-full object-cover">
    </div>

</div>
@endsection
