@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')

@section('content')
    <div class="flex flex-1 gap-6" x-data="{ showDeleteModal: false, deleteUrl: '' }">
        <div class="flex flex-col flex-1 gap-6 min-w-0">
            <div class="flex flex-col bg-white p-6 gap-4 rounded-md border-grey-border border">
                <div class="flex flex-col">
                    <h1 class="text-primary font-bold text-lg">Good Day, {{ $user->full_name }}!</h1>
                    <h5 class="text-captiondark text-sm">How are you feeling today?</h5>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="bg-white p-6 flex flex-col gap-6 rounded-md border-grey-border border">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-primary">User Management</h1>
                        <p class="text-sm text-caption-dark">Manage all registered users and admins.</p>
                    </div>
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-primary hover:bg-primary-dark text-white text-sm font-medium py-2 px-4 rounded-md transition-colors shadow-sm flex items-center gap-2">
                        <span>+ Add New User</span>
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Tabel --}}
                <div class="flex flex-col bg-white rounded-md border border-grey-border shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Joined Date</th>
                                    <th
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $u)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                        src="{{ $u->photo_url ? asset($u->photo_url) : ($u->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $u->full_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $u->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $u->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                                {{ ucfirst($u->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $u->created_at->format('d M Y') }}
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end gap-3">
                                                <a href="{{ route('admin.users.edit', $u->id) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                                                <button
                                                    @click="deleteUrl = '{{ route('admin.users.destroy', $u->id) }}'; showDeleteModal = true"
                                                    class="text-red-600 hover:text-red-900 font-medium">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div>{{ $users->links('vendor.pagination.custom') }}</div>
                </div>
            </div>
        </div>

        {{-- Sidebar Profile (Tetap sama) --}}
        <div class="hidden lg:flex flex-col w-[300px] gap-6 sticky top-4 h-fit">
            <div class="flex flex-col p-6 gap-6 bg-white rounded-md border-grey-border border">
                <div class="flex flex-col gap-4 justify-start">
                    <div class="flex flex-col gap-4 items-center text-center">
                        <img src="{{ auth()->user()->photo_url ? asset(auth()->user()->photo_url) : (auth()->user()->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                            class="rounded-full w-20 h-20" alt="pfp">
                        <div class="flex flex-col">
                            <h4 class="user-name font-semibold text-lg">{{ auth()->user()->full_name }}</h4>
                            <p class="text-caption text-sm text-gray-500">Administrator</p>
                            <div class="flex gap-2 items-center justify-center mt-2">
                                <div class="rounded-full w-2 h-2 bg-primary"></div>
                                <p class="text-primary text-sm">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 mt-2">
                        <x-rounded-button text="Settings" active="true"
                            route="{{ route('profile.edit') }}"></x-rounded-button>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf <button type="submit"
                                class="w-full bg-white hover:bg-gray-50 text-gray-700 border border-grey-border rounded-full px-4 py-2 text-sm font-medium transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
            @include('components.notifications')
        </div>

        {{-- 3. MODAL POPUP (Letakkan di paling bawah, sebelum penutup div utama) --}}

        {{-- Backdrop (Background Gelap) --}}
        <div x-show="showDeleteModal" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

            {{-- Modal Content --}}
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden transform transition-all"
                @click.away="showDeleteModal = false" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">

                {{-- Header Modal --}}
                <div class="p-6 text-center">
                    {{-- Icon Sampah Besar --}}
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                        <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900">Delete User?</h3>
                    <p class="text-sm text-gray-500 mt-2">
                        Are you sure you want to delete this user? This action cannot be undone.
                    </p>
                </div>

                {{-- Footer / Tombol Action --}}
                <div
                    class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row gap-3 justify-center sm:justify-end border-t border-gray-100">

                    {{-- Tombol Cancel --}}
                    <button @click="showDeleteModal = false" type="button"
                        class="w-full sm:w-auto px-4 py-2 bg-white border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Cancel
                    </button>

                    {{-- Form Delete (Action-nya dinamis sesuai deleteUrl) --}}
                    <form :action="deleteUrl" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Yes, Delete It
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
