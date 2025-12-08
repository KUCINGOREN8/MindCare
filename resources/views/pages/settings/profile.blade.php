@extends('layouts.dashboard')

@section('title')
Profile Settings
@endsection

@section('content')
@php
    $tab = request('tab', 'profile');
    $titles = [
        'profile' => [
            'title' => 'Profile Settings',
            'subtitle' => 'Update your photo and personal details here.'
        ],
        'privacy' => [
            'title' => 'Privacy Settings',
            'subtitle' => 'Update your password to keep your account safe.'
        ],
        'preferences' => [
            'title' => 'Preferences',
            'subtitle' => 'Adjust website language settings.'
        ],
    ];
    $title = $titles[$tab]['title'];
    $subtitle = $titles[$tab]['subtitle'];
@endphp

    <div class="flex flex-1 flex-col gap-6">
        <div class="flex flex-col gap-6">
            <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
                <div class="flex flex-col">
                    <h1 class="font-bold text-[#00C3B3] text-lg">{{ $title }}</h1>
                    <h5 class="text-captiondark ">{{ $subtitle }}</h5>
                </div>
                <a href="{{ route('dashboard.index') }}" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md flex items-center justify-center">Back</a>
            </div>

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
                <nav class="tabs overflow-x-auto space-x-1">
                    <a href="?tab=profile"
                        class="group px-4 py-2 rounded-md inline-flex items-center gap-2
                        {{ $tab === 'profile' ? 'bg-[#00C3B3] text-white' : 'hover:bg-[#00C3B3]/10 hover:text-[#00C3B3] text-[#4D4D4E]' }}">
                        {!! str_replace(
                            '<svg ',
                            '<svg class="w-5 h-5 '.($tab === 'profile' ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                            file_get_contents(public_path('assets/signup/user.svg'))
                        ) !!}
                        <span class="hidden sm:inline">Profile</span>
                    </a>

                    <a href="?tab=privacy"
                        class="group px-4 py-2 rounded-md inline-flex items-center gap-1
                        {{ $tab === 'privacy' ? 'bg-[#00C3B3] text-white' : 'hover:bg-[#00C3B3]/10 hover:text-[#00C3B3] text-[#4D4D4E]' }}">
                        {!! str_replace(
                            '<svg ',
                            '<svg class="w-5 h-5 '.($tab === 'privacy' ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                            file_get_contents(public_path('assets/signup/password.svg'))
                        ) !!}
                        <span class="hidden sm:inline">Privacy</span>
                    </a>

                    <a href="?tab=preferences"
                        class="group px-4 py-2 rounded-md inline-flex items-center gap-1
                        {{ $tab === 'preferences' ? 'bg-[#00C3B3] text-white' : 'hover:bg-[#00C3B3]/10 hover:text-[#00C3B3] text-[#4D4D4E]' }}">
                        {!! str_replace(
                            '<svg ',
                            '<svg class="w-5 h-5 '.($tab === 'preferences' ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                            file_get_contents(public_path('assets/signup/language.svg'))
                        ) !!}
                        <span class="hidden sm:inline">Preferences</span>
                    </a>
                </nav>

                <div class="mt-3">
                    @if ($tab === 'profile')
                        <div class="grid grid-cols-[20%_80%]">
                            <p class="text-[#4D4D4E]">Photo Profile</p>
                            <div class="flex justify-between">
                                <img src="{{ $user->photo_url ? asset($user->photo_url) : ($user->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" class="rounded-full w-16 h-16 lg:mx-0 mx-auto" alt="pfp"> 
                                <div class="flex gap-3">
                                    <a href="" class="text-red-600">Delete</a>
                                    <a href="" class="text-primary">Upload</a>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-1 h-[1px] mt-5 mb-5 bg-[#ECECEC]"></div>
                    
                        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">Full Name</label>
                                <div class="flex flex-1 items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <input 
                                        type="text" 
                                        name="name" 
                                        id="nameInput"
                                        placeholder="Full Name" 
                                        class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                        value="{{ old('name', $user->full_name) }}"
                                        readonly
                                        data-original-value="{{ $user->full_name }}"
                                    >
                                </div>
                                @error('name')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">Email Address</label>
                                <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="emailInput"
                                        placeholder="Email" 
                                        class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                        value="{{ old('email', $user->email) }}"
                                        readonly
                                        data-original-value="{{ $user->email }}"
                                    >
                                </div>
                                @error('email')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">Date of Birth</label>
                                <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <input 
                                        type="date" 
                                        name="date_of_birth" 
                                        id="dobInput"
                                        placeholder="Date of Birth" 
                                        class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                        value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                        readonly
                                        data-original-value="{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '' }}"
                                        max="{{ date('Y-m-d') }}"
                                    >
                                </div>
                                @error('date_of_birth')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">Gender</label>
                                <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <select 
                                        name="gender" 
                                        id="genderSelect"
                                        class="w-full outline-none text-black bg-transparent appearance-none disabled:text-[#A1AAB2] disabled:opacity-100"
                                        disabled
                                        data-original-value="{{ $user->gender }}"
                                    >
                                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <svg id="genderArrow" class="w-5 h-5 text-black opacity-0 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                                @error('gender')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-6 flex space-x-4 justify-end">
                                <button 
                                    type="button" 
                                    id="cancelButton"
                                    class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
                                    style="background-color: #FF383C;"
                                    onclick="cancelEdit()"
                                >
                                    Cancel
                                </button>

                                <button 
                                    type="button" 
                                    id="editButton"
                                    class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
                                    style="background-color: #00C3B3;"
                                    onclick="toggleEditMode(true)"
                                >
                                    Edit
                                </button>
                            </div>
                        </form>
                    @endif

                    @if ($tab === 'privacy')
                        <form id="profileForm" method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">Old Password</label>
                                <div class="flex flex-1 items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <input 
                                        type="password" 
                                        name="old_password" 
                                        id="oldPasswordInput"
                                        placeholder="Old Password" 
                                        class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                        readonly
                                    >
                                </div>
                                @error('old_password')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">New Password</label>
                                <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <input 
                                        type="password" 
                                        name="new_password" 
                                        id="passwordInput"
                                        placeholder="New Password" 
                                        class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                        readonly
                                    >
                                </div>
                                @error('new_password')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-[20%_80%]">
                                <label class="block  text-[#4D4D4E]">Confirm Password</label>
                                <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                    <input 
                                        type="password" 
                                        name="confirm_password" 
                                        id="confirmPasswordInput"
                                        placeholder="Confirm Password" 
                                        class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                        readonly
                                    >
                                </div>
                                @error('confirm_password')
                                    <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-6 flex space-x-4 justify-end">
                                <button 
                                    type="button" 
                                    id="cancelButton"
                                    class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
                                    style="background-color: #FF383C;"
                                    onclick="cancelEdit()"
                                >
                                    Cancel
                                </button>

                                <button 
                                    type="button" 
                                    id="editButton"
                                    class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
                                    style="background-color: #00C3B3;"
                                    onclick="toggleEditMode(true)"
                                >
                                    Edit
                                </button>
                            </div>
                        </form>
                    @endif

                    @if ($tab === 'preferences')
                        <div class="grid grid-cols-[20%_80%]">
                            <label class="block  text-[#4D4D4E]">Preferred Language</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                {{-- <input 
                                    type="password" 
                                    name="confirm_password" 
                                    id="confirmPasswordInput"
                                    placeholder="Confirm Password" 
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                    readonly
                                > --}}
                                <select
                                    name="language"
                                    id="languageInput"
                                    disabled
                                    class="w-full outline-none bg-transparent text-gray-400"
                                    onchange="this.style.color = this.value ? '#000000' : '#9CA3AF'">
                                    <option value="" disabled selected class="text-gray-400">Preferred Language</option>
                                    <option value="en" {{ old('language', $user->preferred_language) == 'en' ? 'selected' : '' }} class="text-black" >English</option>
                                    <option value="id" {{ old('language') == 'id' ? 'selected' : '' }} class="text-black">Indonesian</option>
                                </select>
                            </div>
                            @error('language')
                                <p class="text-red-500  mt-2 ml-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="pt-6 flex space-x-4 justify-end">
                            <button 
                                type="button" 
                                id="cancelButton"
                                class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
                                style="background-color: #FF383C;"
                                onclick="cancelEdit()"
                            >
                                Cancel
                            </button>

                            <button 
                                type="button" 
                                id="editButton"
                                class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
                                style="background-color: #00C3B3;"
                                onclick="toggleEditMode(true)"
                            >
                                Edit
                            </button>
                        </div>
                    @endif

                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let isEditMode = false;
        let originalValues = {};
        function toggleEditMode(showCancel = true) {
            const nameInput = document.getElementById('nameInput');
            const emailInput = document.getElementById('emailInput');
            const genderSelect = document.getElementById('genderSelect');
            const genderArrow = document.getElementById('genderArrow');
            const editButton = document.getElementById('editButton');
            const cancelButton = document.getElementById('cancelButton');
            if (!isEditMode) {
                isEditMode = true;
                
                originalValues = {
                    name: nameInput.value,
                    email: emailInput.value,
                    dob: dobInput.value,
                    gender: genderSelect.value
                };
                nameInput.readOnly = false;
                emailInput.readOnly = false;
                dobInput.readOnly = false;
                genderSelect.disabled = false;
                genderArrow.classList.remove('opacity-0');
                genderArrow.classList.add('opacity-100');
                
                editButton.textContent = 'Confirm';
                editButton.onclick = () => submitForm();
                
                if (showCancel) {
                    cancelButton.classList.remove('hidden');
                }
            } else {
                document.getElementById('profileForm').submit();
            }
        }
        function submitForm() {
            document.getElementById('profileForm').submit();
        }
        function cancelEdit() {
            const nameInput = document.getElementById('nameInput');
            const emailInput = document.getElementById('emailInput');
            const dobInput = document.getElementById('dobInput');
            const genderSelect = document.getElementById('genderSelect');
            const genderArrow = document.getElementById('genderArrow');
            const editButton = document.getElementById('editButton');
            const cancelButton = document.getElementById('cancelButton');
            nameInput.value = originalValues.name;
            emailInput.value = originalValues.email;
            dobInput.value = originalValues.dob;
            genderSelect.value = originalValues.gender;
            nameInput.readOnly = true;
            emailInput.readOnly = true;
            dobInput.readOnly = true;
            genderSelect.disabled = true;
            genderArrow.classList.remove('opacity-100');
            genderArrow.classList.add('opacity-0');
            editButton.textContent = 'Edit';
            editButton.onclick = () => toggleEditMode(true);
            
            cancelButton.classList.add('hidden');
            
            isEditMode = false;
            
            document.querySelectorAll('.text-red-500.').forEach(el => el.remove());
        }
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('nameInput');
            const emailInput = document.getElementById('emailInput');
            const dobInput = document.getElementById('dobInput');
            const genderSelect = document.getElementById('genderSelect');
            
            if (nameInput && emailInput && dobInput && genderSelect) {
                originalValues = {
                    name: nameInput.value,
                    email: emailInput.value,
                    dob: dobInput.value,
                    gender: genderSelect.value
                };
            }
            @if(session('success'))
                if (typeof window.showSnackbar !== 'undefined') {
                    window.showSnackbar("{{ session('success') }}", 'success');
                }
            @endif
            @if(session('error'))
                if (typeof window.showSnackbar !== 'undefined') {
                    window.showSnackbar("{{ session('error') }}", 'error');
                }
            @endif
        });
    </script>
@endsection