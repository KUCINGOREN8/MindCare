@extends('layouts.dashboard')
@section('title', 'Edit User')

@section('content')
    <div class="flex flex-1 gap-6">
        <div class="flex flex-col flex-1 gap-6 min-w-0">
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">Good Day, {{ auth()->user()->full_name }}!</h1>
                    <h5 class="text-captiondark text-sm">{{ __('messages.editinguser') }}: <b>{{ $user->full_name }}</b></h5>
                </div>
            </div>

            <div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-primary">{{ __('messages.edituser') }}</h1>
                        <p class="text-sm text-caption-dark">{{ __('messages.updateuserdesc') }}</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm text-gray-500 hover:text-primary transition-colors">
                        &larr; {{ __('messages.backtolist') }}
                    </a>
                </div>

                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Full Name --}}
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.fullname') }}</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('full_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.dob') }}</label>
                            <input type="date" name="date_of_birth" {{-- PERBAIKAN: Format dulu tanggalnya jadi Y-m-d --}}
                                value="{{ old('date_of_birth', $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('Y-m-d') : '') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password <span
                                    class="text-xs text-gray-400 font-normal">({{ __('messages.leaveblank') }})</span></label>
                            <input type="password" name="password"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary"
                                placeholder="New Password (Optional)">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.confirmnewpass') }}</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary"
                                placeholder="Confirm New Password">
                        </div>

                        {{-- Gender --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.gender') }}</label>
                            <select name="gender" class="w-full rounded-md border-gray-300 shadow-sm p-2 border bg-white">
                                <option value="" disabled>{{ __('messages.selectgender') }}</option>
                                <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>
                                    {{ __('messages.male') }}
                                </option>
                                <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>
                                    {{ __('messages.female') }}</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Language --}}
                        <div class="col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.preferlang') }}</label>
                            <select name="language" class="w-full rounded-md border-gray-300 shadow-sm p-2 border bg-white">
                                <option value="en"
                                    {{ old('language', $user->preferred_language) == 'en' ? 'selected' : '' }}>
                                    {{ __('messages.english') }}
                                </option>
                                <option value="id"
                                    {{ old('language', $user->preferred_language) == 'id' ? 'selected' : '' }}>
                                    {{ __('messages.indonesian') }}
                                </option>
                            </select>
                            @error('language')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Terms Checkbox --}}
                        <div class="col-span-2 mt-2">
                            <div class="flex items-center gap-2 text-gray-500">
                                <input type="checkbox" checked disabled
                                    class="rounded border-gray-300 text-gray-400 shadow-sm bg-gray-100">
                                <label class="text-sm">
                                    {{ __('messages.terms') }} ({{ __('messages.alwaysagreed') }})
                                </label>
                            </div>
                        </div>

                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-6 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50 transition-colors">{{ __('messages.cancel') }}</a>
                        <button type="submit"
                            class="px-6 py-2 rounded-md bg-primary text-white hover:bg-primary-dark shadow-sm transition-colors">{{ __('messages.updateuser') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <x-user-profile-card :user="$user" :notifications="$notifications" />
    @endsection
