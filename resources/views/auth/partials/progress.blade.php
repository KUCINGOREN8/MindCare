@php
    $steps = [
        1 => 'Basic Info',
        2 => 'Professional Info',
        3 => 'Education',
        4 => 'Experience',
        5 => 'Availability'
    ];
    $currentRoute = request()->route()->getName();
    $currentStep = match(true) {
        str_contains($currentRoute, 'step1') => 1,
        str_contains($currentRoute, 'step2') => 2,
        str_contains($currentRoute, 'step3') => 3,
        str_contains($currentRoute, 'step4') => 4,
        str_contains($currentRoute, 'step5') => 5,
        default => 1
    };
@endphp

<div class="w-full bg-white py-6 shadow-sm">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center">
            @foreach($steps as $step => $label)
                <div class="flex items-center {{ $step < count($steps) ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center relative z-10">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2 relative
                            {{ $step < $currentStep ? 'bg-[#009C8F] text-white' :
                               ($step == $currentStep ? 'bg-[#009C8F] text-white ring-4 ring-[#009C8F] ring-opacity-30 shadow-md' :
                               'bg-gray-100 text-gray-400 border border-gray-300') }}">

                            @if($step < $currentStep)
                                <i class="fas fa-check text-sm"></i>
                            @else
                                <span class="font-semibold">{{ $step }}</span>
                            @endif

                            @if($step == $currentStep)
                                <div class="absolute -inset-1 rounded-full bg-[#009C8F] opacity-20 animate-ping"></div>
                            @endif
                        </div>

                        <span class="text-sm font-medium {{ $step <= $currentStep ? 'text-[#009C8F]' : 'text-gray-500' }}">
                            {{ $label }}
                        </span>
                    </div>

                    @if($step < count($steps))
                        <div class="flex-1 relative">
                            <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 transform -translate-y-1/2 rounded"></div>
                            @if($step < $currentStep)
                                <div class="absolute top-1/2 left-0 right-0 h-1 bg-[#009C8F] transform -translate-y-1/2 rounded"></div>
                            @elseif($step == $currentStep)
                                <div class="absolute top-1/2 left-0 h-1 bg-[#009C8F] transform -translate-y-1/2 rounded transition-all duration-500"
                                     style="width: 50%"></div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
