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
                placeholder="Full Name" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('name', $user->full_name) }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $user->full_name }}"
            >
        </div>
        @error('name')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Email Address</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('email', $user->email) }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $user->email }}"
            >
        </div>
        @error('email')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block  text-[#4D4D4E]">Date of Birth</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input 
                type="date" 
                name="date_of_birth" 
                placeholder="Date of Birth" 
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                readonly
                data-readonly="true"
                data-original-value="{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '' }}"
                max="{{ date('Y-m-d') }}"
            >
        </div>
        @error('date_of_birth')
            <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid grid-cols-[20%_80%]">
        <label class="block text-[#4D4D4E]">Gender</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <select 
                name="gender"
                class="w-full outline-none text-black bg-transparent appearance-none disabled:text-[#A1AAB2] disabled:opacity-100"
                disabled
                data-disabled="true"
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
            data-cancel-form="profileForm"
            class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
            onclick="cancelEdit('profileForm')"
        >
            Cancel
        </button>

        <button 
            type="button" 
            data-edit-form="profileForm"
            class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
            onclick="toggleEdit('profileForm')"
        >
            Edit
        </button>
    </div>
</form>