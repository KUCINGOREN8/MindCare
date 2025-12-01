@extends('layouts.dashboard')

@section('title')
Find Psychologist
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <div class="flex flex-1 min-w-0">
        <div x-data="liveSearch()" class="flex flex-col flex-1 gap-6 min-w-0">
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
                <h1 class="text-primary font-bold text-lg">Find Psychologist!</h1>
                <h5 class="text-captiondark text-sm">Explore psychologists and pick the one you feel comfortable with.</h5>
            </div>

            <div class="">
                <form class="flex gap-4 items-center" onsubmit="return false;">
                    <div class="flex-1 bg-white rounded-md border border-grey-border px-4 py-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-caption mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                        <input name="q" type="search" placeholder="Type to search..." class="w-full outline-none text-sm text-captiondark placeholder-gray-400 px-4 py-3" x-model="query" @input.debounce.500ms="search()"  />
                    </div>
                </form>
            </div>

            {{-- Default Layout --}}
            <div x-show="query.length === 0" class="grid grid-cols-3 gap-6">
                @foreach($psychologists as $psychologist)
                <div class="flex flex-col bg-white rounded-md border border-grey-border p-6 items-center text-center gap-3">
                    <div class="justify-items-center">
                        <img src="{{ $psychologist->photo_url ? asset($psychologist->photo_url) : ($psychologist->gender=="female" ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" alt="" style='width:100px;'>
                    </div>
                    
                    <div class="mb-2">
                        <div class="font-semibold">{{ $psychologist->full_name }}</div>
                        <div class="text-xs text-gray-400">{{ $psychologist->title }}</div>
                    </div>
                    
                    
                    <div class="flex gap-4 flex-col lg:flex-row">
                        <x-rounded-button text="Book" active="true" route="#"></x-rounded-button>
                        <x-rounded-button text="Details" secondary="true" route="{{ Route('psychologist.profile', $psychologist->id) }}"></x-rounded-button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Search Results Layout --}}
            <div class="grid grid-cols-3 gap-6" x-show="results.length > 0">
                <template x-for="item in results" :key="item.id">
                    <div class="flex flex-col bg-white rounded-md border border-grey-border p-6 items-center text-center gap-3" >
                        <div class="justify-items-center">
                            <img 
                                :src="item.photo_url 
                                    ? item.photo_url 
                                    : (item.gender === 'female' 
                                        ? '{{ asset('assets/icons/user_female.svg') }}' 
                                        : '{{ asset('assets/icons/user_male.svg') }}')"
                                style="width:100px;"
                                alt=""
                            >
                        </div>
    
                        <div class="mb-2">
                            <div class="font-semibold" x-text="item.full_name"></div>
                            <div class="text-xs text-gray-400" x-text="item.title"></div>
                        </div>
    
                        <div class="flex gap-4 flex-col lg:flex-row">
                            <x-rounded-button text="Book" active="true" route="#"></x-rounded-button>
                            <a :href="`${'{{ url('/psychologist') }}'}/${item.id}`" class="bg-white hover:bg-caption/2 text-caption-dark border border-grey-border flex rounded-md px-2 md:px-4 py-2 md:py-2 text-center flex-1 items-center justify-center text-xs sm:text-sm lg:text-base">Details</a>
                        </div>
                    </div>
                </template>

            </div>
            
            
            {{-- No Search Result --}}
            <p x-show="query.length > 0 && results.length === 0" class="text-captiondark">
                No results found.
            </p>

        </div>
    </div>

<script>
function liveSearch() {
    return {
        query: '',
        results: [],

        search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            
            fetch(`{{ route('psychologist.search') }}?q=` + this.query)
                .then(res => res.json())
                .then(data => {
                    this.results = data;
                });
        }
    }
}
</script>
@endsection
