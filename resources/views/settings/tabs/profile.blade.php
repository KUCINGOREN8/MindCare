{{-- @php
    dd($user->hasCustomPhoto());
@endphp --}}

<div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-4 sm:gap-0">
    <p class="text-[#4D4D4E] font-medium sm:font-normal mb-2 sm:mb-0">{{ __('settings.label_photo') }}</p>
    <div class="flex items-center justify-between sm:gap-6">
        <img id="profileImage" src="{{ $user->photo_url }}"
            class="object-cover rounded-full w-16 h-16 border border-gray-200" alt="Profile Picture">

        <div class="flex flex-col items-end sm:items-start justify-end gap-2">
            <input type="file" id="photoInput" accept="image/*" class="hidden" onchange="uploadPhoto()">
            <div class="flex gap-3">
                <button id="deletePhotoBtn" type="button" onclick="deletePhoto()"
                    class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors {{ !$user->hasCustomPhoto() ? 'disabled:cursor-not-allowed disabled:opacity-100' : '' }}"
                    {{ !$user->hasCustomPhoto() ? 'disabled' : '' }}>
                    {{ __('settings.btn_delete') }}
                </button>
                <button id="uploadPhotoBtn" type="button" onclick="document.getElementById('photoInput').click()"
                    class="text-primary hover:text-primary-dark text-sm font-medium transition-colors">
                    {{ __('settings.btn_upload') }}
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ __('settings.photo_hint') }}</p>
        </div>
    </div>
</div>

<div class="flex flex-1 h-[1px] mt-6 mb-6 bg-[#ECECEC]"></div>

{{-- PROFILE FORM --}}
<form id="profileForm" method="POST" action="{{ route('profile.update') }}" class="space-y-6">
    @csrf
    @method('PUT')

    {{-- FULL NAME --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_fullname') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="text" name="name" placeholder="{{ __('settings.placeholder_fullname') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('name', $user->full_name) }}" readonly data-readonly="true"
                data-original-value="{{ $user->full_name }}">
        </div>
        @error('name')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- EMAIL --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_email') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm border border-transparent focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/50 transition-all"
            style="background-color: #FAFAFA;">
            <input type="email" name="email" placeholder="{{ __('settings.placeholder_email') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('email', $user->email) }}" readonly data-readonly="true"
                data-original-value="{{ $user->email }}">
        </div>
        @error('email')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- DATE OF BIRTH --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_dob') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <input type="date" name="date_of_birth" placeholder="{{ __('settings.placeholder_dob') }}"
                class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2] text-sm sm:text-base"
                value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                readonly data-readonly="true"
                data-original-value="{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '' }}"
                max="{{ date('Y-m-d') }}">
        </div>
        @error('date_of_birth')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- GENDER --}}
    <div class="flex flex-col sm:grid sm:grid-cols-[20%_80%] gap-2 sm:gap-0 sm:items-center">
        <label class="text-[#4D4D4E] font-medium sm:font-normal">{{ __('settings.label_gender') }}</label>
        <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
            <select name="gender"
                class="w-full outline-none text-black bg-transparent appearance-none disabled:text-[#A1AAB2] disabled:opacity-100 text-sm sm:text-base"
                disabled data-disabled="true" data-original-value="{{ $user->gender }}">
                <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>
                    {{ __('settings.gender_male') }}</option>
                <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>
                    {{ __('settings.gender_female') }}</option>
                <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>
                    {{ __('settings.gender_other') }}</option>
            </select>
            <svg id="genderArrow" class="w-5 h-5 text-black opacity-0 transition-opacity duration-200 flex-shrink-0"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        @error('gender')
            <p class="text-red-500 text-xs mt-1 ml-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="pt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 sm:gap-4">
        <button type="button" data-cancel-form="profileForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md text-sm font-medium transition-colors hidden"
            onclick="cancelEdit('profileForm')">
            {{ __('settings.btn_cancel') }}
        </button>

        <button type="button" data-edit-form="profileForm"
            class="w-full sm:w-auto px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md text-sm font-medium transition-colors"
            onclick="toggleEdit('profileForm')">
            {{ __('settings.btn_edit') }}
        </button>
    </div>
</form>

<script>
    const defaultPhotoUrl =
        "{{ $user->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg') }}";

    // DEFINE TRANSLATION OBJECT
    const LANG_PROFILE = {
        uploading: "{{ __('settings.js_uploading') }}",
        deleting: "{{ __('settings.js_deleting') }}",
        btnUpload: "{{ __('settings.btn_upload') }}",
        btnDelete: "{{ __('settings.btn_delete') }}",
        confirmDelete: "{{ __('settings.js_confirm_delete_photo') }}",
        successUpload: "{{ __('settings.js_photo_success') }}",
        successDelete: "{{ __('settings.js_photo_delete_success') }}",
        errSize: "{{ __('settings.js_photo_size_error') }}",
        errType: "{{ __('settings.js_photo_type_error') }}",
        errFail: "{{ __('settings.js_photo_fail') }}"
    };

    async function uploadPhoto() {
        const input = document.getElementById('photoInput');
        const file = input.files[0];

        if (!file) return;

        // Validasi tipe file
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            alert(LANG_PROFILE.errType);
            input.value = '';
            return;
        }

        // Validasi ukuran file (maks 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert(LANG_PROFILE.errSize);
            input.value = '';
            return;
        }

        // Set loading state
        const uploadBtn = document.getElementById('uploadPhotoBtn');
        // const originalText = uploadBtn.textContent; // Tidak perlu, kita pakai LANG
        uploadBtn.textContent = LANG_PROFILE.uploading;
        uploadBtn.disabled = true;

        const formData = new FormData();
        formData.append('photo', file);
        formData.append('_token', '{{ csrf_token() }}');

        try {
            const response = await fetch('{{ route('profile.upload-photo') }}', {
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

                showNotification(LANG_PROFILE.successUpload, 'success');
            } else {
                showNotification(result.message || LANG_PROFILE.errFail, 'error');
            }
        } catch (error) {
            showNotification(LANG_PROFILE.errFail, 'error');
        } finally {
            uploadBtn.textContent = LANG_PROFILE.btnUpload;
            uploadBtn.disabled = false;
            input.value = '';
        }
    }

    async function deletePhoto() {
        if (!confirm(LANG_PROFILE.confirmDelete)) {
            return;
        }

        const deleteBtn = document.getElementById('deletePhotoBtn');
        deleteBtn.textContent = LANG_PROFILE.deleting;
        deleteBtn.disabled = true;

        try {
            const response = await fetch('{{ route('profile.delete-photo') }}', {
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
                deleteBtn.textContent = LANG_PROFILE.btnDelete;

                showNotification(LANG_PROFILE.successDelete, 'success');
            } else {
                showNotification(result.message || 'Failed deleting photo', 'error');
                deleteBtn.disabled = false;
                deleteBtn.textContent = LANG_PROFILE.btnDelete;
            }
        } catch (error) {
            showNotification('Failed deleting photo. Please try again.', 'error');
            deleteBtn.disabled = false;
            deleteBtn.textContent = LANG_PROFILE.btnDelete;
        }
    }

    function showNotification(message, type = 'success') {
        window.dispatchEvent(new CustomEvent('open-snackbar', {
            detail: {
                message,
                type
            }
        }));
    }
</script>
