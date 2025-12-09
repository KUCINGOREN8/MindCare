@extends('layouts.dashboard')

@section('title')
Profile Settings
@endsection

@section('content')
@php
    $tab = request('tab', 'profile');
    
    $tabs = [
        'profile' => [
            'name' => 'Profile',
            'title' => 'Profile Settings',
            'subtitle' => 'Update your photo and personal details here.',
            'icon' => 'user.svg',
            'roles' => ['patient', 'psychologist']
        ],
        'professional' => [
            'name' => 'Professional',
            'title' => 'Professional Settings',
            'subtitle' => 'Update your professional details and qualifications.',
            'icon' => 'work.svg',
            'roles' => ['psychologist']
        ],
        'schedule' => [
            'name' => 'Work Schedule',
            'title' => 'Schedule Settings',
            'subtitle' => 'Set your availability for appointments.',
            'icon' => 'calendar.svg',
            'roles' => ['psychologist']
        ],
        'privacy' => [
            'name' => 'Privacy',
            'title' => 'Privacy Settings',
            'subtitle' => 'Update your password to keep your account safe.',
            'icon' => 'password.svg',
            'roles' => ['patient', 'psychologist', 'admin']
        ],
        'preferences' => [
            'name' => 'Preferences',
            'title' => 'Preferences',
            'subtitle' => 'Adjust website language settings.',
            'icon' => 'language.svg',
            'roles' => ['patient', 'psychologist', 'admin']
        ],
    ];
    
    $availableTabs = array_filter($tabs, function($tabConfig) use ($user) {
        return in_array($user->role, $tabConfig['roles']);
    });
    
    if (!isset($availableTabs[$tab])) {
        $tab = 'profile';
    }
    
    $title = $tabs[$tab]['title'];
    $subtitle = $tabs[$tab]['subtitle'];
@endphp

<div class="flex flex-1 flex-col gap-6">
    <div class="flex flex-col gap-6">
        <div class="flex bg-white p-6 rounded-md border-grey-border border justify-between">
            <div class="flex flex-col">
                <h1 class="font-bold text-[#00C3B3] text-lg">{{ $title }}</h1>
                <h5 class="text-captiondark ">{{ $subtitle }}</h5>
            </div>
            <a href="{{ route($user->role . '.dashboard') }}" class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md flex items-center justify-center">Back</a>
        </div>

        <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border justify-between gap-6">
            <nav class="tabs overflow-x-auto space-x-1">
                @foreach($availableTabs as $tabKey => $tabConfig)
                    <a href="?tab={{ $tabKey }}"
                        class="group px-4 py-2 rounded-md inline-flex items-center gap-2
                        {{ $tab === $tabKey ? 'bg-[#00C3B3] text-white' : 'hover:bg-[#00C3B3]/10 hover:text-[#00C3B3] text-[#4D4D4E]' }}">
                        
                        @if($tabConfig['icon'] === 'user.svg')
                            {!! str_replace(
                                '<svg ',
                                '<svg class="w-5 h-5 '.($tab === $tabKey ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                                file_get_contents(public_path('assets/signup/user.svg'))
                            ) !!}
                        @elseif($tabConfig['icon'] === 'password.svg')
                            {!! str_replace(
                                '<svg ',
                                '<svg class="w-5 h-5 '.($tab === $tabKey ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                                file_get_contents(public_path('assets/signup/password.svg'))
                            ) !!}
                        @elseif($tabConfig['icon'] === 'language.svg')
                            {!! str_replace(
                                '<svg ',
                                '<svg class="w-5 h-5 '.($tab === $tabKey ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                                file_get_contents(public_path('assets/signup/language.svg'))
                            ) !!}
                        @elseif($tabConfig['icon'] === 'work.svg')
                            {!! str_replace(
                                '<svg ',
                                '<svg class="w-5 h-5 '.($tab === $tabKey ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                                file_get_contents(public_path('assets/icons/work.svg'))
                            ) !!}
                        @elseif($tabConfig['icon'] === 'calendar.svg')
                            {!! str_replace(
                                '<svg ',
                                '<svg class="w-5 h-5 '.($tab === $tabKey ? 'text-white' : 'text-[#4D4D4E] group-hover:text-[#00C3B3]').'" fill="currentColor" ',
                                file_get_contents(public_path('assets/icons/calendar.svg'))
                            ) !!}
                        @endif
                        
                        <span class="hidden sm:inline">{{ $tabConfig['name'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="mt-3">
                @if ($tab === 'profile')
                    @include('settings.tabs.profile', ['user' => $user])
                @endif

                @if ($tab === 'professional' && $user->isPsychologist())
                    @php
                        $psychologist = $user->psychologist;
                    @endphp
                    @include('settings.tabs.professional', ['user' => $user, 'psychologist' => $psychologist])
                @endif

                @if ($tab === 'schedule' && $user->isPsychologist())
                    @php
                        $psychologist = $user->psychologist;
                    @endphp
                    @include('settings.tabs.schedule', ['user' => $user, 'psychologist' => $psychologist])
                @endif

                @if ($tab === 'privacy')
                    @include('settings.tabs.privacy', ['user' => $user])
                @endif

                @if ($tab === 'preferences')
                    @include('settings.tabs.preferences', ['user' => $user])
                @endif
            </div>
        </div>
    </div>
</div>

<script>
const EditModeManager = {
    states: {},
    
    init() {
        this.initializeAllForms();
        this.setupNotifications();
        this.setupPasswordToggles();
        this.setupScheduleToggles();
    },
    
    initializeAllForms() {
        document.querySelectorAll('form[id]').forEach(form => {
            const formId = form.id;
            const hasPasswordFields = form.querySelector('[data-password-field]') !== null;
            const isScheduleForm = form.querySelector('.availability-toggle') !== null;

            this.states[formId] = {
                isEditMode: false,
                originalValues: this.getFormValues(form),
                isPasswordForm: hasPasswordFields,
                isScheduleForm: isScheduleForm
            };
            
            const inputs = form.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.hasAttribute('readonly')) {
                    input.setAttribute('data-readonly', 'true');
                }

                if (input.hasAttribute('disabled')) {
                    input.setAttribute('data-disabled', 'true');
                }
                
                if (input.name && input.type !== 'password') {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        input.setAttribute('data-original-value', input.checked);
                    } else {
                        input.setAttribute('data-original-value', input.value);
                    }
                }
            });
        });
    },

    setupPasswordToggles() {
        document.querySelectorAll('.password-toggle').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const input = button.previousElementSibling;
                const eyeIcon = button.querySelector('img');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.src = eyeIcon.getAttribute('data-open-icon');
                    eyeIcon.alt = 'Hide password';
                } else {
                    input.type = 'password';
                    eyeIcon.src = eyeIcon.getAttribute('data-closed-icon');
                    eyeIcon.alt = 'Show password';
                }
            });
        });
    },

    setupScheduleToggles() {
        document.querySelectorAll('.availability-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                if (this.hasAttribute('disabled')) return;
                
                const day = this.getAttribute('data-day');
                const timeInputs = document.querySelector(`.time-inputs[data-day="${day}"]`);
                const label = document.getElementById(`availability-label-${day}`);
                
                const hiddenInput = document.querySelector(`input[type="hidden"][data-hidden-for="${day}"]`);
                
                if (this.checked) {
                    timeInputs.classList.remove('hidden');
                    if (label) label.textContent = 'Available';
                    
                    if (hiddenInput) {
                        hiddenInput.disabled = true;
                    }
                    
                    const startInput = timeInputs.querySelector('input[name*="start_time"]');
                    const endInput = timeInputs.querySelector('input[name*="end_time"]');
                    
                    if (startInput && !startInput.value) {
                        startInput.value = '09:00';
                    }
                    if (endInput && !endInput.value) {
                        endInput.value = '17:00';
                    }
                } else {
                    timeInputs.classList.add('hidden');
                    if (label) label.textContent = 'Not Available';
                    
                    if (hiddenInput) {
                        hiddenInput.disabled = false;
                    }
                }
            });
            
            toggle.dispatchEvent(new Event('change'));
        });
    },
    
    getFormValues(form) {
        const values = {};
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.name) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    values[input.name] = input.checked;
                } else if (input.multiple && input.tagName === 'SELECT') {
                    values[input.name] = Array.from(input.selectedOptions).map(opt => opt.value);
                } else if (input.type === 'password') {
                    values[input.name] = '';
                } else {
                    values[input.name] = input.value;
                }
            }
        });
        
        return values;
    },
    
    toggleEditMode(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const state = this.states[formId];
        const editButton = form.querySelector(`[data-edit-form="${formId}"]`);
        const cancelButton = form.querySelector(`[data-cancel-form="${formId}"]`);
        
        if (!state.isEditMode) {
            state.isEditMode = true;
            state.originalValues = this.getFormValues(form);
            this.enableFormInputs(form);
            
            if (editButton) {
                editButton.textContent = 'Confirm';
                editButton.onclick = () => this.submitForm(formId);
                editButton.classList.add('confirm-mode');
            }
            
            if (cancelButton) {
                cancelButton.classList.remove('hidden');
            }
        } else {
            this.submitForm(formId);
        }
    },
    
    enableFormInputs(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.hasAttribute('data-readonly')) {
                input.removeAttribute('readonly');
                input.classList.remove('read-only:text-[#A1AAB2]');
            }
            if (input.hasAttribute('data-disabled')) {
                input.removeAttribute('disabled');
                input.classList.remove('disabled:text-[#A1AAB2]', 'disabled:opacity-100');
            }
            if (input.hasAttribute('data-password-field') && input.type === 'text') {
                input.type = 'password';
            }
        });
        
        const selectArrows = form.querySelectorAll('select + svg');
        selectArrows.forEach(arrow => {
            arrow.classList.remove('opacity-0');
            arrow.classList.add('opacity-100');
        });

        if (this.states[form.id]?.isScheduleForm) {
            const toggles = form.querySelectorAll('.availability-toggle');
            toggles.forEach(toggle => {
                toggle.removeAttribute('disabled');
            });
        }
    },
    
    disableFormInputs(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.hasAttribute('data-readonly')) {
                input.setAttribute('readonly', 'true');
                input.classList.add('read-only:text-[#A1AAB2]');
            }
            if (input.hasAttribute('data-disabled')) {
                input.setAttribute('disabled', 'true');
                input.classList.add('disabled:text-[#A1AAB2]', 'disabled:opacity-100');
            }
        });
        
        const selectArrows = form.querySelectorAll('select + svg');
        selectArrows.forEach(arrow => {
            arrow.classList.remove('opacity-100');
            arrow.classList.add('opacity-0');
        });

        if (this.states[form.id]?.isScheduleForm) {
            const toggles = form.querySelectorAll('.availability-toggle');
            toggles.forEach(toggle => {
                toggle.removeAttribute('disabled');
            });
        }
    },
    
    submitForm(formId) {
        const form = document.getElementById(formId);
        if (form) {
            if (this.validateRequiredFields(form)) {
                form.submit();
            }
        }
    },

    validateScheduleForm(form) {
        let isValid = true;
        
        const checkedToggles = form.querySelectorAll('.availability-toggle:checked');
        
        checkedToggles.forEach(toggle => {
            const day = toggle.getAttribute('data-day');
            const startTime = form.querySelector(`[name="schedules[${day}][start_time]"]`);
            const endTime = form.querySelector(`[name="schedules[${day}][end_time]"]`);
            
            if (startTime && !startTime.value.trim()) {
                isValid = false;
                this.showValidationError(startTime, 'Start time is required');
            }
            
            if (endTime && !endTime.value.trim()) {
                isValid = false;
                this.showValidationError(endTime, 'End time is required');
            }
            
            if (startTime && endTime && startTime.value && endTime.value) {
                if (startTime.value >= endTime.value) {
                    isValid = false;
                    this.showValidationError(endTime, 'End time must be after start time');
                }
            }
        });
        
        return isValid;
    },
    
    validateRequiredFields(form) {
        let isValid = true;
        const requiredInputs = form.querySelectorAll('[required]');
        
        if (this.states[form.id]?.isScheduleForm) {
            return this.validateScheduleForm(form);
        }

        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                this.showValidationError(input, 'This field is required');
            }
        });
        
        return isValid;
    },
    
    showValidationError(input, message) {
        const existingError = input.parentElement.nextElementSibling;
        if (existingError && existingError.classList.contains('text-red-500')) {
            existingError.remove();
        }
        
        const errorElement = document.createElement('p');
        errorElement.className = 'text-red-500 mt-2 ml-1';
        errorElement.textContent = message;
        
        input.parentElement.parentElement.appendChild(errorElement);
        
        input.classList.add('border-red-500', 'border');
        setTimeout(() => {
            input.classList.remove('border-red-500', 'border');
        }, 3000);
    },
    
    cancelEdit(formId) {
        const form = document.getElementById(formId);
        if (!form) return;
        
        const state = this.states[formId];
        const editButton = form.querySelector(`[data-edit-form="${formId}"]`);
        const cancelButton = form.querySelector(`[data-cancel-form="${formId}"]`);
        
        if (state.isPasswordForm) {
            const passwordInputs = form.querySelectorAll('[type="password"], [type="text"][data-password-field]');
            passwordInputs.forEach(input => {
                input.value = '';
                if (input.type === 'text' && input.hasAttribute('data-password-field')) {
                    input.type = 'password';
                }
            });
            
            const eyeIcons = form.querySelectorAll('.password-toggle img');
            eyeIcons.forEach(icon => {
                icon.src = icon.getAttribute('data-closed-icon');
                icon.alt = 'Show password';
            });
        } else {
            this.restoreOriginalValues(form);
        }

        this.disableFormInputs(form);
        
        if (editButton) {
            editButton.textContent = 'Edit';
            editButton.onclick = () => this.toggleEditMode(formId);
            editButton.classList.remove('confirm-mode');
        }
        
        if (cancelButton) {
            cancelButton.classList.add('hidden');
        }
        
        state.isEditMode = false;
        
        this.clearFormErrors(form);
    },
    
    restoreOriginalValues(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        
        inputs.forEach(input => {
            if (input.name && input.hasAttribute('data-original-value')) {
                const originalValue = input.getAttribute('data-original-value');
                
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = originalValue === 'true';

                    if (input.classList.contains('availability-toggle')) {
                        const day = input.getAttribute('data-day');
                        const timeInputs = document.querySelector(`.time-inputs[data-day="${day}"]`);
                        const label = document.getElementById(`availability-label-${day}`);
                        
                        if (originalValue === 'true') {
                            if (timeInputs) timeInputs.classList.remove('hidden');
                            if (label) label.textContent = 'Available';
                        } else {
                            if (timeInputs) timeInputs.classList.add('hidden');
                            if (label) label.textContent = 'Not Available';
                        }
                    }

                } else if (input.multiple && input.tagName === 'SELECT') {
                    try {
                        const values = JSON.parse(originalValue);
                        Array.from(input.options).forEach(option => {
                            option.selected = values.includes(option.value);
                        });
                    } catch (e) {
                        console.error('Error parsing multi-select values:', e);
                    }
                } else {
                    input.value = originalValue;
                }
            }
        });
    },
    
    clearFormErrors(form) {
        const errorElements = form.querySelectorAll('.text-red-500');
        errorElements.forEach(error => error.remove());
    },
    
    setupNotifications() {
        @if(session('success'))
            this.showNotification('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            this.showNotification('{{ session('error') }}', 'error');
        @endif

        @if($errors->any() && request('tab') == 'schedule')
            const scheduleForm = document.getElementById('scheduleForm');
            if (scheduleForm) {
                const state = this.states['scheduleForm'];
                if (state && !state.isEditMode) {
                    this.toggleEditMode('scheduleForm');
                }
            }
        @endif
    },
    
    showNotification(message, type = 'success') {
        window.dispatchEvent(new CustomEvent('open-snackbar', {
            detail: { message, type }
        }));
    }

    
};

document.addEventListener('DOMContentLoaded', () => {
    EditModeManager.init();
});

function toggleEdit(formId) {
    EditModeManager.toggleEditMode(formId);
}

function cancelEdit(formId) {
    EditModeManager.cancelEdit(formId);
}
</script>
@endsection