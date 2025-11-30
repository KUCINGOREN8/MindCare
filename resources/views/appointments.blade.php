@extends('layouts.sidebar') 

@section('content')

    {{-- ==================== KOLOM TENGAH (MAIN CONTENT) ==================== --}}
    <main class="flex-1 p-8 bg-gray-50 overflow-y-auto h-screen">
        
        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-teal-600">Appointments</h2>
            <p class="text-gray-500">Keeping track of your appointments here.</p>
        </div>

        {{-- SECTION 1: ONGOING --}}
        <section class="mb-8">
            <h3 class="font-bold text-lg mb-4 text-gray-800">Ongoing</h3>
            
            {{-- Kartu Ongoing (Contoh Data Statis/Dinamis) --}}
            @if(isset($ongoing) && $ongoing)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-start mb-4">
                <div class="flex gap-4">
                    {{-- Avatar Dokter --}}
                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                        {{-- Placeholder Image --}}
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($ongoing->with) }}&background=random" alt="Doctor" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900">{{ $ongoing->with }}</h4>
                        <p class="text-sm text-gray-500">{{ $ongoing->job_title ?? 'Specialist' }}</p>
                        <div class="flex items-center gap-2 mt-2 text-gray-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ \Carbon\Carbon::parse($ongoing->date)->format('l, d M Y') }}, {{ \Carbon\Carbon::parse($ongoing->time)->format('H:i A') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold 
                        {{ $ongoing->status == 'confirmed' ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600' }}">
                        {{ ucfirst($ongoing->status) }}
                    </span>
                </div>
            </div>
            {{-- Tombol Aksi --}}
            <div class="flex gap-3 mb-6">
                <button class="px-6 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition shadow-sm text-sm font-medium">Join Session</button>
                <button class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition text-sm font-medium">Reschedule</button>
            </div>
            @else
                <div class="bg-white p-6 rounded-2xl border border-gray-100 text-center text-gray-400">
                    No ongoing appointments.
                </div>
            @endif
        </section>

        {{-- SECTION 2: HISTORY --}}
        <section class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <h3 class="font-bold text-lg text-gray-800">History</h3>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <div id="history-container" class="space-y-4">
                {{-- Item History Awal --}}
                @foreach($history as $item)
                    <div class="bg-green-50 p-4 rounded-xl flex items-center gap-4 border border-green-100">
                        <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center text-green-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Your consultation was successful</p>
                            <p class="text-xs text-green-600">Last consultation with {{ $item->with }} on {{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Load More --}}
            <div class="mt-4">
                <button id="load-more-btn" onclick="loadMoreHistory()" class="px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg hover:bg-gray-200 transition">
                    Load More..
                </button>
            </div>
        </section>

        {{-- SECTION 3: RESCHEDULE --}}
        <section>
            <div class="flex items-center gap-2 mb-4">
                <h3 class="font-bold text-lg text-gray-800">Reschedule</h3>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold text-gray-900">Dr. Jacob Jones</h4>
                        <p class="text-sm text-gray-500">Therapist</p>
                        <div class="flex items-center gap-2 mt-2 text-gray-600 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>Friday, 10:00 AM</span>
                        </div>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-400 text-white">Requested</span>
                </div>
                <div class="mt-4">
                     <button class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition text-sm font-medium">Reschedule</button>
                </div>
            </div>
        </section>

    </main>


    {{-- ==================== KOLOM KANAN (SIDEBAR PROFIL) ==================== --}}
    <aside class="w-80 h-screen sticky top-0 bg-white border-l p-6 overflow-y-auto">
        
        {{-- Profile Section --}}
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200">
                <img src="" alt="Profile" class="w-full h-full object-cover">
            </div>
            <div>
                <p class="font-bold text-gray-900">Jane Cooper</p>
                <p class="text-gray-500 text-xs">Premium Member</p>
                <div class="flex items-center gap-1 mt-1">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <p class="text-green-600 text-xs font-medium">Active</p>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mb-8">
            <button class="flex-1 py-2 bg-teal-400 text-white text-sm font-medium rounded-lg hover:bg-teal-500 transition">Edit Profile</button>
            <button class="px-4 py-2 bg-gray-50 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-100 transition border border-gray-100">Logout</button>
        </div>

        {{-- Notifications Section --}}
        <h3 class="font-bold text-lg mb-4 text-gray-800">Notifications</h3>

        <div class="space-y-4">
            
            {{-- Blue Notification --}}
            <div class="p-4 rounded-xl bg-blue-50 border-l-4 border-blue-400">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <div>
                        <p class="font-bold text-sm text-gray-800">Session Reminder</p>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">Your session with Dr. Emily Chen starts in 2 hours</p>
                        <p class="text-xs text-gray-400 mt-2">1 hour ago</p>
                    </div>
                </div>
            </div>

            {{-- Green Notification --}}
            <div class="p-4 rounded-xl bg-green-50 border-l-4 border-green-400">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-bold text-sm text-gray-800">Mood Entry Complete</p>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">Great job logging your mood for 7 days straight!</p>
                        <p class="text-xs text-gray-400 mt-2">3 hours ago</p>
                    </div>
                </div>
            </div>

            {{-- Purple Notification --}}
            <div class="p-4 rounded-xl bg-purple-50 border-l-4 border-purple-400">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <div>
                        <p class="font-bold text-sm text-gray-800">New Message</p>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">Dr. Rodriguez sent you a follow-up message</p>
                        <p class="text-xs text-gray-400 mt-2">5 hours ago</p>
                    </div>
                </div>
            </div>

            {{-- Yellow Notification --}}
            <div class="p-4 rounded-xl bg-yellow-50 border-l-4 border-yellow-400">
                <div class="flex items-start gap-3">
                     <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <div>
                        <p class="font-bold text-sm text-gray-800">Daily Tip</p>
                        <p class="text-xs text-gray-600 mt-1 leading-relaxed">Try a 5-minute meditation to start your day</p>
                        <p class="text-xs text-gray-400 mt-2">1 day ago</p>
                    </div>
                </div>
            </div>

        </div>

    </aside>

    {{-- SCRIPT LOAD MORE --}}
    <script>
        let offset = 5; // Karena 5 data awal sudah ditampilkan
        const container = document.getElementById('history-container');
        const loadMoreBtn = document.getElementById('load-more-btn');

        async function loadMoreHistory() {
            // Ubah text tombol jadi loading
            loadMoreBtn.innerText = 'Loading...';
            loadMoreBtn.disabled = true;

            try {
                // Pastikan Anda sudah membuat Route untuk ini
                const response = await fetch(`/appointments/load-more?offset=${offset}`);
                const data = await response.json();

                if (data.length > 0) {
                    data.forEach(item => {
                        // Membuat elemen HTML baru untuk setiap data
                        const html = `
                            <div class="bg-green-50 p-4 rounded-xl flex items-center gap-4 border border-green-100 animate-fade-in">
                                <div class="w-8 h-8 rounded-full bg-green-200 flex items-center justify-center text-green-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800 text-sm">Your consultation was successful</p>
                                    <p class="text-xs text-green-600">Last consultation with ${item.with} on ${new Date(item.date).toLocaleDateString()}</p>
                                </div>
                            </div>
                        `;
                        container.insertAdjacentHTML('beforeend', html);
                    });

                    offset += 5; // Tambah offset untuk load berikutnya
                    loadMoreBtn.innerText = 'Load More..';
                    loadMoreBtn.disabled = false;
                } else {
                    loadMoreBtn.innerText = 'No more history';
                    loadMoreBtn.style.display = 'none';
                }

            } catch (error) {
                console.error('Error:', error);
                loadMoreBtn.innerText = 'Error loading data';
            }
        }
    </script>
    {{-- CSS untuk Animasi Smooth --}}
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

@endsection