@extends('layouts.app')
@section('title', 'Homepage')

@section('content')
    {{-- Home --}}
    <div id="home" class="flex items-center justify-start h-[600px] bg-cover bg-fit w-full pl-52"
        style="background-image: url('{{ asset('img/welcome.png') }}')">
        <div class="justify-center mr-12 pr-20">
            <div class="flex-col max-w-md">
                <h1 class="text-5xl font-bold mb-2">Be Okay</h1>
                <h1 class="text-5xl font-bold text-[#2E6F6D] mb-3">You're Not Alone</h1>
                <p class="text-[#4D4D4E] text-md text-justify">{{ __('messages.description') }}</p>
            </div>
            <div class="flex pt-7 space-x-6">
                @auth
                    <a href="{{ route('patient.dashboard') }}"
                        class="bg-[#00C3B3] hover:bg-[#33D1C2] active:bg-[#66DED0] text-white font-semibold w-40 py-3 rounded-[1vw] transition flex items-center justify-center">
                        {{ __('messages.button1') }}
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-[#00C3B3] hover:bg-[#33D1C2] active:bg-[#66DED0] text-white font-semibold w-40 py-3 rounded-[1vw] transition flex items-center justify-center">
                        {{ __('messages.button1') }}
                    </a>
                @endauth

                <button
                    class="bg-white hover:bg-[#f9f9f9] active:bg-[#66DED0] text-[#2E6F6D] font-semibold w-40 py-3 rounded-[1vw] transition border border-[#2E6F6D]">
                    {{ __('messages.button2') }}
                </button>
            </div>
            <div class="flex space-x-9 pt-2">
                <div class="flex items-center justify-center">
                    <img src="/img/healthcare.svg" alt="" class="w-auto h-5 mr-1">
                    <p class="text-[#4D4D4E]">100% Confidental</p>
                </div>
                <div class="flex items-center justify-center">
                    <img src="/img/ticker.svg" alt="" class="w-auto h-5 mr-2">
                    <p class="text-[#4D4D4E]">24/7 Support</p>
                </div>
            </div>
        </div>
    </div>

    {{-- About --}}
    <div  id="about" class="flex flex-col items-center justify-center bg-white h-[700px]">
        <div class="text-center">
            <h1 class="font-semibold text-3xl">{{ __('messages.reason') }} <span class="text-[#00C3B3]">Be
                    Okay</span><span>?</span></h1>
            <p class="text-[#4B5563]">{{ __('messages.answer') }}</p>
        </div>
        <div class="m-10 pt-5 flex space-x-4 h-[180px]">
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/doctor.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">{{ __('messages.title1') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc1') }}
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/calender.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">{{ __('messages.title2') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc2') }}
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/shield.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">{{ __('messages.title3') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc3') }}
                </p>
            </div>
        </div>
        <div class="pt-5 flex space-x-4 h-[180px]">
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/device.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">{{ __('messages.title4') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc4') }}
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/chat.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">{{ __('messages.title5') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc5') }}
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/web.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">{{ __('messages.title6') }}</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    {{ __('messages.desc6') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Testimonials --}}
    <div id="testimonials" class="flex flex-col items-center justify-center bg-gray-200 h-[550px]">
        <div class="text-center">
            <h1 class="font-semibold text-3xl">{{ __('messages.usersay') }}</h1>
            <p class="pt-3 text-[#4B5563]">{{ __('messages.userdesc') }}</p>
        </div>
        <div class="justify-between flex space-x-4 pt-10">
            @foreach ($testimonials as $t)
                <div class="max-w-sm bg-white shadow-md rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/ibu2.png') }}" alt="User" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h3 class="text-gray-900 font-semibold">{{ $t->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $t->position }}</p>
                        </div>
                    </div>

                    <p class="text-gray-600 mt-4 italic">
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
            @endforeach
        </div>
    </div>
@endsection
