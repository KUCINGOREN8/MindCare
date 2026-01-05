@php
    $steps = [
        1 => __('steps.basic_info'),
        2 => __('steps.professional_info'),
        3 => __('steps.education'),
        4 => __('steps.experience'),
        5 => __('steps.availability'),
    ];
    $currentRoute = request()->route()->getName();
    $currentStep = match (true) {
        str_contains($currentRoute, 'step1') => 1,
        str_contains($currentRoute, 'step2') => 2,
        str_contains($currentRoute, 'step3') => 3,
        str_contains($currentRoute, 'step4') => 4,
        str_contains($currentRoute, 'step5') => 5,
        default => 1,
    };
@endphp

<div class="w-full bg-white py-6 shadow-sm">
    <div class="max-w-6xl mx-auto px-4">
        {{-- Mobile --}}
        <div class="md:hidden text-center mb-6">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Step {{ $currentStep }} of {{ count($steps) }}</p>
            <h2 class="text-lg font-bold text-[#009C8F]">{{ $steps[$currentStep] }}</h2>
        </div>

        <div class="flex items-center justify-between">
            @foreach ($steps as $step => $label)
                <div class="flex items-center {{ $step < count($steps) ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center relative z-10">
                        <div
                            class="w-8 h-8 text-xs md:text-base md:w-12 md:h-12 rounded-full flex items-center justify-center transition-all duration-300 flex-shrink-0 md:mb-2 relative
                            {{ $step < $currentStep
                                ? 'bg-[#009C8F] text-white'
                                : ($step == $currentStep
                                    ? 'bg-[#009C8F] text-white ring-2 md:ring-4 ring-[#009C8F] ring-opacity-30 shadow-md'
                                    : 'bg-gray-100 text-gray-400 border border-gray-300') }}">

                            @if ($step < $currentStep)
                                <i class="fas fa-check"></i>
                            @else
                                <span class="font-semibold">{{ $step }}</span>
                            @endif

                            @if ($step == $currentStep)
                                <div class="absolute -inset-1 rounded-full bg-[#009C8F] opacity-20 animate-ping"></div>
                            @endif
                        </div>

                        <span
                            class="hidden md:block text-sm font-medium text-center mt-2 w-max max-w[120px] {{ $step <= $currentStep ? 'text-[#009C8F]' : 'text-gray-500' }}">
                            {{ $label }}
                        </span>
                    </div>

                    @if ($step < count($steps))
                        <div class="flex-1 relative mx-2 md:mx-4">
                            <div
                                class="absolute top-1/2 left-0 right-0 h-0.5 md:h-1 bg-gray-200 transform -translate-y-1/2 rounded">
                            </div>
                            @if ($step < $currentStep)
                                <div
                                    class="absolute top-1/2 left-0 right-0 h-0.5 md:h-1 bg-[#009C8F] transform -translate-y-1/2 rounded">
                                </div>
                            @elseif($step == $currentStep)
                                <div class="absolute top-1/2 left-0 h-0.5 md:h-1 bg-[#009C8F] transform -translate-y-1/2 rounded transition-all duration-500"
                                    style="width: 50%"></div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
