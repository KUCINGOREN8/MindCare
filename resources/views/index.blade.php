@extends('layouts.app')
@section('title', 'Homepage')

@section('content')
    {{-- Home --}}
    <div id="home"
        class="flex flex-col lg:flex-row w-full min-h-[550px] lg:h-screen lg:max-h-[600px] bg-gradient-to-r from-cyan-100 to-white overflow-hidden relative">
        <div class="flex-1 flex flex-col justify-center px-6 py-20 lg:py-0 lg:pl-24 xl:pl-32 z-20">
            <div class="max-w-lg mx-auto lg:mx-0 text-center lg:text-left pb-5">
                <h1 class="text-4xl lg:text-5xl font-bold mb-2">Be Okay</h1>
                <h1 class="text-4xl lg:text-5xl font-bold text-[#2E6F6D] mb-3">You're Not Alone</h1>
                <p class="text-[#4D4D4E] text-base lg:text-md text-center lg:text-justify leading-relaxed">
                    {{ __('messages.description') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-5">
                @auth
                    <a href="{{ route('patient.dashboard') }}"
                        class="bg-[#00C3B3] hover:bg-[#33D1C2] active:bg-[#66DED0] text-white font-semibold w-full sm:w-40 py-3 rounded-xl transition flex items-center justify-center shadow-lg shadow-teal-500/30">
                        {{ __('messages.button1') }}
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-[#00C3B3] hover:bg-[#33D1C2] active:bg-[#66DED0] text-white font-semibold w-full sm:w-40 py-3 rounded-xl transition flex items-center justify-center shadow-lg shadow-teal-500/30">
                        {{ __('messages.button1') }}
                    </a>
                @endauth

                <a href="{{ route('psychologist.signup.step1') }}"
                    class="bg-white hover:bg-gray-50 text-[#2E6F6D] font-semibold w-full sm:w-40 py-3 rounded-xl transition border border-[#2E6F6D] flex items-center justify-center">
                    {{ __('messages.button2') }}
                </a>
            </div>
            <div
                class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start text-sm sm:text-base font-medium text-[#4D4D4E]">
                <div class="flex items-center justify-center">
                    <img src="/img/healthcare.svg" alt="" class="w-auto h-5 mr-1">
                    <p class="text-[#4D4D4E] text-sm sm:text-base font-medium">100% Confidental</p>
                </div>
                <div class="flex items-center justify-center lg:justify-start">
                    <img src="/img/ticker.svg" alt="" class="w-auto h-5 mr-2">
                    <p class="text-[#4D4D4E] text-sm sm:text-base font-medium">24/7 Support</p>
                </div>
            </div>
        </div>
        <div class="hidden lg:flex flex-1 items-end justify-center xl:justify-end relative z-10">
            <img src="{{ asset('img/welcome.png') }}" alt="Consultation"
                class="h-[85%] xl:h-[90%] w-auto max-w-full object-contain object-bottom">
        </div>
    </div>

    {{-- About --}}
    <div id="about" class="flex flex-col items-center justify-center bg-white h-auto min-h-[700px] py-20 px-6">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h1 class="font-semibold text-3xl">{{ __('messages.reason') }} <span class="text-[#00C3B3]">Be
                    Okay</span><span>?</span></h1>
            <p class="text-[#4B5563] mt-2">{{ __('messages.answer') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 gap-y-12 max-w-7xl mx-auto">
            <div
                class="p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible border border-gray-50 mt-6 lg:mt-0">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/doctor.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-8 text-lg font-semibold text-gray-800">{{ __('messages.title1') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc1') }}
                </p>
            </div>
            <div
                class="p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible border border-gray-50 mt-6 lg:mt-0">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/calender.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-8 text-lg font-semibold text-gray-800">{{ __('messages.title2') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc2') }}
                </p>
            </div>
            <div
                class="p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible border border-gray-50 mt-6 lg:mt-0">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/shield.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-8 text-lg font-semibold text-gray-800">{{ __('messages.title3') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc3') }}
                </p>
            </div>
            <div
                class="p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible border border-gray-50 mt-6 lg:mt-0">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/device.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-8 text-lg font-semibold text-gray-800">{{ __('messages.title4') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc4') }}
                </p>
            </div>
            <div
                class="p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible border border-gray-50 mt-6 lg:mt-0">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/chat.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-8 text-lg font-semibold text-gray-800">{{ __('messages.title5') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc5') }}
                </p>
            </div>
            <div
                class="p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible border border-gray-50 mt-6 lg:mt-0">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/web.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-8 text-lg font-semibold text-gray-800">{{ __('messages.title6') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc6') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Testimonials --}}
    <div id="testimonials" class="flex flex-col items-center justify-center bg-gray-200 h-auto min-h-[550px] py-20 px-6">
        <div class="text-center mb-10">
            <h1 class="font-semibold text-3xl">{{ __('messages.usersay') }}</h1>
            <p class="pt-3 text-[#4B5563]">{{ __('messages.userdesc') }}</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full max-w-7xl">
            @foreach ($testimonials as $t)
                <div class="bg-white shadow-md rounded-2xl p-6 border border-gray-100 h-full flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('img/ibu2.png') }}" alt="User"
                                class="w-10 h-10 rounded-full object-cover">
                            <div>
                                <h3 class="text-gray-900 font-semibold text-sm sm:text-base">{{ $t->name }}</h3>
                                <p class="text-xs sm:text-sm text-gray-500">{{ $t->position }}</p>
                            </div>
                        </div>

                        <p class="text-gray-600 mt-4 italic text-sm sm:text-base">
                            {{ $t->message }}
                        </p>

                        <div class="flex mt-4 text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.19 3.674h3.862c.97 0 1.371 1.24.588 1.81l-3.125 2.27 1.19 3.674c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.116 2.126c-.785.57-1.84-.197-1.54-1.118l1.19-3.674-3.125-2.27c-.783-.57-.382-1.81.588-1.81h3.862l1.19-3.674z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.19 3.674h3.862c.97 0 1.371 1.24.588 1.81l-3.125 2.27 1.19 3.674c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.116 2.126c-.785.57-1.84-.197-1.54-1.118l1.19-3.674-3.125-2.27c-.783-.57-.382-1.81.588-1.81h3.862l1.19-3.674z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.19 3.674h3.862c.97 0 1.371 1.24.588 1.81l-3.125 2.27 1.19 3.674c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.116 2.126c-.785.57-1.84-.197-1.54-1.118l1.19-3.674-3.125-2.27c-.783-.57-.382-1.81.588-1.81h3.862l1.19-3.674z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.19 3.674h3.862c.97 0 1.371 1.24.588 1.81l-3.125 2.27 1.19 3.674c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.116 2.126c-.785.57-1.84-.197-1.54-1.118l1.19-3.674-3.125-2.27c-.783-.57-.382-1.81.588-1.81h3.862l1.19-3.674z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.19 3.674h3.862c.97 0 1.371 1.24.588 1.81l-3.125 2.27 1.19 3.674c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.116 2.126c-.785.57-1.84-.197-1.54-1.118l1.19-3.674-3.125-2.27c-.783-.57-.382-1.81.588-1.81h3.862l1.19-3.674z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
