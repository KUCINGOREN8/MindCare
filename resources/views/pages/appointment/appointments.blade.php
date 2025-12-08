@extends('layouts.dashboard')
@section('title', 'Appointments')

@section('content')

    <div class="flex-1 flex flex-col h-full overflow-y-auto pr-2 pb-20 scroll-smooth">

        {{-- Header --}}
        <div class="mb-8 mt-2">
            <h2 class="text-primary font-bold text-3xl">Appointments</h2>
            <p class="text-captiondark text-base">Manage your sessions and history.</p>
        </div>

        {{-- A. UPCOMING SESSION --}}
        <section class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <h3 class="font-bold text-lg text-gray-800">Upcoming Session</h3>

                {{-- Indikator Ping (Hanya muncul jika ada jadwal) --}}
                @if(isset($ongoing) && $ongoing)
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                    </span>
                @endif
            </div>

            @if(isset($ongoing) && $ongoing)


                @include('components.upcoming-appointment', ['appointment' => $ongoing])

            @else
                {{-- State Kosong --}}
                <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                    <p class="text-gray-400 text-sm">No upcoming session right now.</p>
                </div>
            @endif
        </section>

        {{-- B. HISTORY (Tetap Sama) --}}
        <section class="mb-8">
            <h3 class="font-bold text-l mb-4">History</h3>
            <div class="space-y-3">
                @forelse($history ?? [] as $item)
                    <div class="bg-white p-4 rounded-xl border border-gray-100 hover:shadow-md transition flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $item->with ?? 'Doctor' }}</p>
                                <p class="text-xs text-gray-500">{{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d F Y') : '-' }}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wide">Completed</span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm italic">No history found.</p>
                @endforelse
            </div>
        </section>

        {{-- C. RESCHEDULE (Tetap Sama) --}}
        <section>
            <h3 class="font-bold text-lg mb-4">Reschedule Requests</h3>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 bg-yellow-100 text-yellow-700 text-[10px] font-bold px-3 py-1 rounded-bl-xl">PENDING</div>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold">Dr. Jacob Jones</h4>
                        <p class="text-sm text-gray-500">Requested: <strong>Friday, 10:00 AM</strong></p>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection
