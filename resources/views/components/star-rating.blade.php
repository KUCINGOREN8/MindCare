@props([
    'name' => 'rating',
    'value' => 0,
    'size' => 'lg', // sm, md, lg
    'editable' => true,
    'showLabels' => true,
])

@php
    $sizes = [
        'sm' => 'w-6 h-6',
        'md' => 'w-8 h-8',
        'lg' => 'w-10 h-10',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['lg'];
    $currentRating = (int) old($name, $value);
@endphp

<div {{ $attributes->merge(['class' => 'star-rating-input']) }} 
     x-data="{
         rating: {{ $currentRating }},
         hoverRating: 0,
         setRating(value) {
             this.rating = value;
             this.$refs.ratingInput.value = value;
         },
         setHover(value) {
             this.hoverRating = value;
         },
         clearHover() {
             this.hoverRating = 0;
         },
         getStarColor(starIndex) {
             if (this.hoverRating > 0) {
                 return starIndex <= this.hoverRating ? 'text-yellow-400' : 'text-gray-300';
             }
             return starIndex <= this.rating ? 'text-yellow-400' : 'text-gray-300';
         }
     }">
    
    <input type="hidden" name="{{ $name }}" x-ref="ratingInput" x-model="rating" value="{{ $currentRating }}">
    
    <div class="grid grid-cols-5 gap-1 sm:gap-2 mb-2">
        @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center justify-center">
                @if($editable)
                    <button type="button"
                            @click="setRating({{ $i }})"
                            @mouseenter="setHover({{ $i }})"
                            @mouseleave="clearHover()"
                            class="p-1 focus:outline-none transition-transform hover:scale-110 active:scale-95"
                            aria-label="Rate {{ $i }} star{{ $i > 1 ? 's' : '' }}">
                        <svg class="{{ $sizeClass }} transition-colors duration-150"
                             :class="getStarColor({{ $i }})"
                             fill="currentColor"
                             viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                @else
                    <div class="p-1">
                        <svg class="{{ $sizeClass }} {{ $i <= $currentRating ? 'text-yellow-400' : 'text-gray-300' }}"
                             fill="currentColor"
                             viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                @endif
            </div>
        @endfor
    </div>

    @if($showLabels)
        <div class="grid grid-cols-5 gap-1 sm:gap-2 text-xs text-gray-500 text-center mt-1">
            <span class="truncate px-1">Poor</span>
            <span class="truncate px-1">Fair</span>
            <span class="truncate px-1">Good</span>
            <span class="truncate px-1">Very Good</span>
            <span class="truncate px-1">Excellent</span>
        </div>
    @endif
    
    @error($name)
        <p class="mt-2 text-sm text-red-600 text-center">{{ $message }}</p>
    @enderror
</div>