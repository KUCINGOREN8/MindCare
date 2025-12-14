<div class="grid grid-cols-[20%_80%]">
    <p class="text-[#4D4D4E]">Photo Profile</p>
    <div class="flex justify-between">
        <img id="profileImage" src="{{ $user->photo_url }}" class="object-cover rounded-full w-16 h-16 lg:mx-0 mx-auto" alt="Profile Picture"> 
        
        <div class="flex flex-col items-end justify-end">
            <input type="file" id="photoInput" accept="image/*" class="hidden" onchange="uploadPhoto()">
            <div class="flex gap-3 flex-col sm:flex-row">
                <button id="deletePhotoBtn" type="button" onclick="deletePhoto()" class="text-red-600 hover:text-red-800 {{ !$user->photo_url ? 'disabled:cursor-not-allowed disabled:opacity-100' : '' }}" @disabled(!$user->photo_url) >
                    Delete
                </button>
                <button id="uploadPhotoBtn" type="button" onclick="document.getElementById('photoInput').click()" class="text-primary hover:text-primary-dark">
                    Upload
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Max 2MB (JPEG, PNG, GIF)</p>
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

<script>
    const defaultPhotoUrl = "{{ $user->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg') }}";

    async function uploadPhoto() {
        const input = document.getElementById('photoInput');
        const file = input.files[0];
        
        if (!file) return;
        
        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPEG, PNG, GIF, WebP)');
            input.value = '';
            return;
        }
        
        // Validasi ukuran file (maks 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Image size must be less than 2MB');
            input.value = '';
            return;
        }
        
        // Set loading state
        const uploadBtn = document.getElementById('uploadPhotoBtn');
        const originalText = uploadBtn.textContent;
        uploadBtn.textContent = 'Uploading...';
        uploadBtn.disabled = true;
        
        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', '{{ csrf_token() }}');
        
        try {
            const response = await fetch('{{ route("profile.upload-photo") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                const profileImage = document.getElementById('profileImage');
                profileImage.src = result.photo_url + '?t=' + new Date().getTime();
                
                const deleteBtn = document.getElementById('deletePhotoBtn');
                deleteBtn.disabled = false;
                
                showNotification('Profile photo updated successfully!', 'success');
            } else {
                showNotification(result.message || 'Failed uploading photo', 'error');
            }
        } catch (error) {
            showNotification('Failed uploading photo. Please try again.', 'error');
        } finally {
            uploadBtn.textContent = originalText;
            uploadBtn.disabled = false;
            input.value = '';
        }
    }

    async function deletePhoto() {
        if (!confirm('Are you sure to delete your photo profile?')) {
            return;
        }
        
        const deleteBtn = document.getElementById('deletePhotoBtn');
        const originalText = deleteBtn.textContent;
        deleteBtn.textContent = 'Deleting...';
        deleteBtn.disabled = true;
        
        try {
            const response = await fetch('{{ route("profile.delete-photo") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                const profileImage = document.getElementById('profileImage');
                profileImage.src = defaultPhotoUrl;
                
                deleteBtn.disabled = true;
                deleteBtn.textContent = 'Delete';
                
                showNotification('Photo profile deleted successfully!', 'success');
            } else {
                showNotification(result.message || 'Failed deleting photo', 'error');
                deleteBtn.disabled = false;
                deleteBtn.textContent = originalText;
            }
        } catch (error) {
            showNotification('Failed deleting photo. Please try again.', 'error');
            deleteBtn.disabled = false;
            deleteBtn.textContent = originalText;
        }
    }

    function showNotification(message, type = 'success') {
        window.dispatchEvent(new CustomEvent('open-snackbar', {
            detail: { message, type }
        }));
    }
</script>