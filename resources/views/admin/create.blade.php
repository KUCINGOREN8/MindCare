@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')

@section('content')
    <div class="flex flex-col lg:flex-row flex-1 gap-4 lg:gap-6">
        <div class="flex flex-col flex-1 gap-6 min-w-0">
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">Good Day, {{ $user->full_name }}!</h1>
                    <h5 class="text-captiondark text-sm">{{ __('messages.mood') }}</h5>
                </div>
            </div>

            {{-- Flash Message (Notifikasi Sukses) --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif


            <div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
                {{-- Judul Form --}}
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-primary">{{ __('messages.adminadd') }}</h1>
                        <p class="text-sm text-caption-dark">{{ __('messages.adminadddesc') }}</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-primary">
                        &larr; {{ __('messages.backdashboard') }}
                    </a>
                </div>

                {{-- FORM START --}}
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Full Name --}}
                        <div class="col-span-2">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.fullname') }}</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('full_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date of Birth --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.dob') }}</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" name="password"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-span-1">
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.confirmpassword') }}</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-md border-gray-300 shadow-sm p-2 border focus:ring-primary focus:border-primary">
                        </div>

                        {{-- Gender --}}
                        <div class="col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('messages.gender') }}</label>
                            <select name="gender" class="w-full rounded-md border-gray-300 shadow-sm p-2 border bg-white">
                                <option value="" disabled selected>{{ __('messages.selectgender') }}</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>
                                    {{ __('messages.male') }}</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>
                                    {{ __('messages.female') }}
                                </option>
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
                                <option value="en" {{ old('language') == 'en' ? 'selected' : '' }}>
                                    {{ __('messages.english') }}</option>
                                <option value="id" {{ old('language') == 'id' ? 'selected' : '' }}>
                                    {{ __('messages.indonesian') }}
                                </option>
                            </select>
                            @error('language')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Terms --}}
                        <div class="col-span-2 mt-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="terms" id="terms"
                                    class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                                    {{ old('terms') ? 'checked' : '' }}>
                                <label for="terms" class="text-sm text-gray-600">
                                    {{ __('messages.terms') }}
                                </label>
                            </div>
                            @error('terms')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-100">
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-6 py-2 rounded-md border border-gray-300 text-gray-600 hover:bg-gray-50">{{ __('messages.cancel') }}</a>
                        <button type="submit"
                            class="px-6 py-2 rounded-md bg-primary text-white hover:bg-primary-dark shadow-sm">{{ __('messages.saveuser') }}</button>
                    </div>
                </form>
                {{-- FORM END --}}
            </div>
        </div>

        <div class="w-full lg:w-auto lg:shrink-0 self-start">
            <x-user-profile-card :user="$user" :notifications="$notifications" />
        </div>
    </div>
@endsection
