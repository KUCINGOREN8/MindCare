@extends('layouts.dashboard')

@section('title')
    {{ __('find_psychologist.title') }}
@endsection

@section('content')
    <div class="flex flex-1 min-w-0">
        {{-- SUNTIKKAN TRANSLATION KE X-DATA --}}
        <div x-data="liveSearch({
            btnBook: '{{ __('find_psychologist.book') }}',
            btnDetails: '{{ __('find_psychologist.details') }}',
            routeBook: '{{ route('patient.book.appointment', ':id') }}',
            routeDetails: '{{ route('patient.psychologist.profile', ':id') }}'
        })" class="flex flex-col flex-1 gap-6 min-w-0">

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
                <h1 class="text-primary font-bold text-lg">{{ __('find_psychologist.title') }}</h1>
                <h5 class="text-captiondark text-sm">{{ __('find_psychologist.subtitle') }}</h5>
            </div>

            <div class="">
                <form class="flex gap-4 items-center" onsubmit="return false;">
                    <div class="flex-1 bg-white rounded-md border border-grey-border px-4 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-caption mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" />
                        </svg>
                        <input name="q" type="search" placeholder="{{ __('find_psychologist.search_placeholder') }}"
                            class="w-full outline-none text-sm text-captiondark placeholder-gray-400 px-4 py-3"
                            x-model="query" @input.debounce.500ms="search()" />
                    </div>
                </form>
            </div>

            {{-- Layout Default (Server Side Render) --}}
            <div x-show="query.length === 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($psychologists as $psychologist)
                    <div
                        class="flex flex-col bg-white rounded-md border border-grey-border p-6 items-center text-center gap-3">
                        <div class="justify-items-center">
                            {{-- Logic Photo URL dengan fallback gender asset --}}
                            <img src="{{ $psychologist->user->photo_url ?? ($psychologist->user->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}"
                                class="rounded-full w-16 h-16 lg:mx-0 mx-auto object-cover" alt="Profile Picture">
                        </div>

                        <div class="mb-2">
                            <div class="font-semibold">{{ $psychologist->user->full_name }}</div>
                            <div class="text-xs text-gray-400">{{ $psychologist->title }}</div>
                        </div>


                        <div class="flex gap-4 flex-col lg:flex-row">
                            <x-rounded-button text="{{ __('find_psychologist.book') }}" active="true"
                                route="{{ route('patient.book.appointment', $psychologist->id) }}"></x-rounded-button>
                            <x-rounded-button text="{{ __('find_psychologist.details') }}" secondary="true"
                                route="{{ Route('patient.psychologist.profile', $psychologist->id) }}"></x-rounded-button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Search Results Layout (Client Side / Alpine JS) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" x-show="results.length > 0"
                style="display: none;">
                <template x-for="item in results" :key="item.id">
                    <div
                        class="flex flex-col bg-white rounded-md border border-grey-border p-6 items-center text-center gap-3">
                        <div class="justify-items-center">
                            <img :src="item.photo_url ?
                                item.photo_url :
                                (item.gender === 'female' ?
                                    '{{ asset('assets/icons/user_female.svg') }}' :
                                    '{{ asset('assets/icons/user_male.svg') }}')"
                                class="rounded-full w-16 h-16 lg:mx-0 mx-auto object-cover" alt="">
                        </div>

                        <div class="mb-2">
                            <div class="font-semibold" x-text="item.full_name"></div>
                            <div class="text-xs text-gray-400" x-text="item.title"></div>
                        </div>

                        <div class="flex gap-4 flex-col lg:flex-row w-full justify-center">
                            {{-- 
                                TOMBOL ALPINE JS: 
                                Kita tidak bisa pakai Component Blade <x-rounded-button> di dalam <template> Alpine 
                                karena Blade dirender di server sebelum JS jalan.
                                Jadi kita harus tulis HTML manual yang mirip dengan component tersebut.
                            --}}

                            {{-- Tombol Book --}}
                            <a :href="translations.routeBook.replace(':id', item.id)"
                                class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded-full font-medium transition-colors text-sm w-full md:w-auto text-center">
                                <span x-text="translations.btnBook"></span>
                            </a>

                            {{-- Tombol Details --}}
                            <a :href="translations.routeDetails.replace(':id', item.id)"
                                class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-2 rounded-full font-medium transition-colors text-sm w-full md:w-auto text-center">
                                <span x-text="translations.btnDetails"></span>
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            {{-- No Search Result --}}
            <p x-show="query.length > 0 && results.length === 0" class="text-captiondark text-center py-10"
                style="display: none;">
                {{ __('find_psychologist.no_results') }}
            </p>
        </div>
    </div>

    <script>
        function liveSearch(trans) {
            return {
                query: '',
                results: [],
                translations: trans, // Simpan data translasi dari blade ke variabel JS

                search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        return;
                    }

                    fetch(`{{ route('patient.psychologist.search') }}?q=` + this.query)
                        .then(res => res.json())
                        .then(data => {
                            this.results = data;
                        });
                }
            }
        }
    </script>
@endsection
