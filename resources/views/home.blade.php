@extends('layouts.app')
@section('title', 'Homepage')

@section('content')
    <div class="flex items-center justify-center h-[600px] bg-cover bg-fit w-full"
        style="background-image: url('{{ asset('img/welcome.png') }}')">
        <div class="justify-center mr-12 pr-20">
            <div class="flex-col w-1/2">
                <h1 class="text-5xl font-bold mb-2">Be Okay</h1>
                <h1 class="text-5xl font-bold text-[#2E6F6D] mb-3">You're Not Alone</h1>
                <p class="text-[#4D4D4E] text-md text-justify">Connect with licensed mental health professionals from the
                    comfort of
                    your home.
                    Take the first step
                    towards
                    better mental well-being today.</p>
            </div>
            <div class="flex pt-7 space-x-6">
                <button
                    class="bg-[#00C3B3] hover:bg-[#33D1C2] active:bg-[#66DED0] text-white font-semibold px-10 py-3 rounded-[1vw] transition">Start
                    Now</button>
                <button
                    class="bg-white hover:bg-[#f9f9f9] active:bg-[#66DED0] text-[#2E6F6D] font-semibold px-10 py-3 rounded-[1vw] transition border border-[#2E6F6D]">Book
                    a Session</button>
            </div>
            <div class="flex space-x-7 pt-2">
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
    <div class="flex flex-col items-center justify-center bg-white h-[700px]">
        <div class="text-center">
            <h1 class="font-semibold text-3xl">Why Choose <span class="text-[#00C3B3]">Be Okay</span><span>?</span></h1>
            <p class="text-[#4B5563]">We provide comprehensive mental health support with qualified professionals</p>
        </div>
        <div class="m-10 pt-5 flex space-x-4 h-[180px]">
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/doctor.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">Licensed Professionals</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    Connect with certified psychologists
                    and counselors.
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/calender.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">24/7 Support</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    Get help whenever you need it with our round-the-clock support system.
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/shield.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">Secure & Private</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    Your privacy is our priority with end-to- end encrypted sessions.
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
                <h3 class="mt-6 text-lg font-semibold text-gray-800">Access Anywhere</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    Our responsive web app lets you access Be Okay from any device.
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/chat.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">Real-time Secure Chat</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    Connect instantly with your psychologist in a safe, private chat environment designed for comfort and
                    trust.
                </p>
            </div>
            <div class="max-w-sm mx-auto p-6 bg-white rounded-2xl shadow-md text-center relative overflow-visible">
                <!-- Icon Circle -->
                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-[#00C3B3] p-4 rounded-full shadow-md">
                    <img src="/img/web.svg" alt="Icon" class="h-8 w-8">
                </div>

                <!-- Card Content -->
                <h3 class="mt-6 text-lg font-semibold text-gray-800">Multi-language Support</h3>
                <p class="mt-2 text-gray-500 text-sm">
                    Switch easily between English and Bahasa Indonesia for a more natural and personalized experience.
                </p>
            </div>
        </div>
    </div>
    <div class="flex flex-col items-center justify-center bg-gray-200 h-[550px]">
        <div class="text-center">
            <h1 class="font-semibold text-3xl">What Our Users Say</h1>
            <p class="pt-3 text-[#4B5563]">Real stories from people who found their way to being okay</p>
        </div>
        <div class="justify-between flex space-x-4 pt-10">
            <div>
                <div class="max-w-sm bg-white shadow-md rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/ibu2.png') }}" alt="User" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h3 class="text-gray-900 font-semibold">Sarah M.</h3>
                            <p class="text-sm text-gray-500">Marketing Professional</p>
                        </div>
                    </div>

                    <p class="text-gray-600 mt-4 italic">
                        "Be Okay helped me through my anxiety. The counselors are incredibly understanding and professional.
                        I feel so much better now."
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
            <div>
                <div class="max-w-sm bg-white shadow-md rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/om2.png') }}" alt="User" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h3 class="text-gray-900 font-semibold">David R.</h3>
                            <p class="text-sm text-gray-500">Student</p>
                        </div>
                    </div>

                    <p class="text-gray-600 mt-4 italic">
                        "The convenience of online sessions made it possible for me to get help. The platform is easy to use
                        and very supportive."
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
            <div>
                <div class="max-w-sm bg-white shadow-md rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('img/guru.png') }}" alt="User"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <h3 class="text-gray-900 font-semibold">Emily K.</h3>
                            <p class="text-sm text-gray-500">Teacher</p>
                        </div>
                    </div>

                    <p class="text-gray-600 mt-4 italic">
                        "I was hesitant at first, but Be Okay provided exactly what I needed. The counselors are caring and
                        the process is seamless."
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
        </div>
    </div>
@endsection
