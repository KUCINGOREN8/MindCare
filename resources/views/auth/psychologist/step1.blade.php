@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
    <div class="min-h-screen bg-gray-50 w-full overflow-x-hidden">
        @include('auth.partials.progress')

        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-xl shadow-lg p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-center mb-2" style="color: #009C8F;">
                    {{ __('messages.registitle') }}</h2>
                <p class="text-gray-600 text-center mb-8 text-sm md:text-base">Step 1: {{ __('messages.basicinfo') }}</p>

                <form method="POST" action="{{ route('psychologist.signup.storeStep1') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="col-span-2">
                            <label
                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.fullname') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                {!! str_replace(
                                    '<svg ',
                                    '<svg class="w-5 h-5 md:w-6 h-6 text-[#A1AAB2] opacity-50 mr-3 md:mr-4 flex-shrink-0" fill="currentColor" ',
                                    file_get_contents(public_path('assets/signup/user.svg')),
                                ) !!}
                                <input type="text" name="full_name" required
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent text-sm md:text-base"
                                    placeholder="{{ __('messages.fullname') }}"
                                    value="{{ old('full_name', $user->full_name ?? '') }}">
                            </div>
                            @error('full_name')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-span-2">
                            <label class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.email') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <img src="{{ asset('assets/signup/email.svg') }}" alt="icon"
                                    class="w-5 h-5 md:w-6 md:h-6 mr-3 md:mr-4 opacity-50 flex-shrink-0">
                                <input type="email" name="email" required
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent text-sm md:text-base"
                                    placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-gray-700 mb-2 text-sm md:text-base">Password</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                {!! str_replace(
                                    '<svg ',
                                    '<svg class="w-5 h-5 md:w-6 md:h-6 text-[#A1AAB2] opacity-50 mr-3 md:mr-4 flex-shrink-0" fill="currentColor" ',
                                    file_get_contents(public_path('assets/signup/password.svg')),
                                ) !!}
                                <input type="password" name="password" required id="password"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent text-sm md:text-base"
                                    placeholder="Password" {{ $user ? '' : 'required' }}>
                                <button type="button" class="password-toggle flex-shrink-0"
                                    onclick="togglePassword('password')">
                                    <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password"
                                        class="w-5 h-5 opacity-50 eye-icon" data-field="password">
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.confirmpassword') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                {!! str_replace(
                                    '<svg ',
                                    '<svg class="w-5 h-5 md:w-6 md:h-6 text-[#A1AAB2] opacity-50 mr-3 md:mr-4 flex-shrink-0" fill="currentColor" ',
                                    file_get_contents(public_path('assets/signup/password.svg')),
                                ) !!}
                                <input type="password" name="password_confirmation" required id="confirm-password"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent text-sm md:text-base"
                                    placeholder="{{ __('messages.confirmpassword') }}" {{ $user ? '' : 'required' }}>
                                <button type="button" class="password-toggle flex-shrink-0"
                                    onclick="togglePassword('confirm-password')">
                                    <img src="{{ asset('assets/signup/eye-closed.svg') }}" alt="Show password"
                                        class="w-5 h-5 opacity-50 eye-icon" data-field="confirm-password">
                                </button>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.dob') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <img src="{{ asset('assets/signup/calender.svg') }}" alt="icon"
                                    class="w-5 h-5 mr-3 md:mr-4 opacity-50 flex-shrink-0">
                                <input type="date" name="date_of_birth" required
                                    class="w-full outline-none bg-transparent text-sm md:text-base" style="color: #9CA3AF;"
                                    onchange="this.style.color='#000000'"
                                    value="{{ old(
                                        'date_of_birth',
                                        isset($user->date_of_birth) ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '',
                                    ) }}">
                            </div>
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gender -->
                        <div class="col-span-2 md:col-span-1">
                            <label
                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.gender') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                <img src="{{ asset('assets/signup/gender.svg') }}" alt="icon"
                                    class="w-5 h-5 mr-3 md:mr-4 opacity-50 flex-shrink-0">
                                <select name="gender" required
                                    class="w-full outline-none bg-transparent text-gray-400 text-sm md:text-base"
                                    onchange="this.style.color='#000000'">
                                    <option value="" disabled
                                        {{ old('gender', $user->gender ?? '') ? '' : 'selected' }}>
                                        {{ __('messages.gender') }}</option>
                                    <option value="male"
                                        {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>
                                        {{ __('messages.male') }}</option>
                                    <option value="female"
                                        {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>
                                        {{ __('messages.female') }}
                                    </option>
                                    <option value="other"
                                        {{ old('gender', $user->gender ?? '') == 'other' ? 'selected' : '' }}>
                                        {{ __('messages.other') }}
                                    </option>
                                </select>
                            </div>
                            @error('gender')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preferred Language -->
                        <div class="col-span-1 md:col-span-2">
                            <label
                                class="block text-gray-700 mb-2 text-sm md:text-base">{{ __('messages.preferlang') }}</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm"
                                style="background-color: #FAFAFA;">
                                {!! str_replace(
                                    '<svg ',
                                    '<svg class="w-5 h-5 md:w-6 md:h-6 text-[#A1AAB2] opacity-50 mr-3 md:mr-4 flex-shrink-0" fill="currentColor" ',
                                    file_get_contents(public_path('assets/signup/language.svg')),
                                ) !!}
                                <select name="preferred_language" required
                                    class="w-full outline-none bg-transparent text-gray-400 text-sm md:text-base"
                                    onchange="this.style.color='#000000'">
                                    <option value="" disabled
                                        {{ old('preferred_language', $user->preferred_language ?? '') ? '' : 'selected' }}>
                                        {{ __('messages.preferlang') }}</option>
                                    <option value="en"
                                        {{ old('preferred_language', $user->preferred_language ?? '') == 'en' ? 'selected' : '' }}>
                                        {{ __('messages.english') }}</option>
                                    <option value="id"
                                        {{ old('preferred_language', $user->preferred_language ?? '') == 'id' ? 'selected' : '' }}>
                                        {{ __('messages.indonesian') }}</option>
                                </select>
                            </div>
                            @error('preferred_language')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Terms -->
                        <div class="col-span-2">
                            <div class="flex items-start pt-2">
                                <input type="checkbox" name="terms" id="terms" required
                                    class="w-4 h-4 mt-0.5 mr-3 flex-shrink-0" style="accent-color: #009C8F;"
                                    {{ old('terms', $user->agree_to_terms ?? false) ? 'checked' : '' }}>
                                <label for="terms" class="text-sm text-gray-600">{{ __('messages.agree') }} <a
                                        href="#"
                                        class="!text-[#009C8F] hover:underline bg-transparent">{{ __('messages.term') }}</a>
                                    and <a href="#"
                                        class="!text-[#009C8F] hover:underline bg-transparent">{{ __('messages.policy') }}</a></label>
                            </div>
                            @error('terms')
                                <p class="text-red-500 text-xs md:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between">
                        <a href="{{ url('/') }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition text-sm md:text-base text-center">
                            {{ __('messages.cancel') }}
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
