{{-- resources/views/components/mood-chart-js.blade.php --}}
@props([
    'chartData' => [],
    'averageMood' => 0,
    'note' => [],
    'moodValueMap' => [
        'sad' => 1,
        'flat' => 2,
        'good' => 3,
        'happy' => 4,
        'blissful' => 5
    ],
    'weekDays' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    'height' => '250px',
    'chartId' => null
])

@php
    $chartId = $chartId ?? 'moodChart-' . Str::random(8);
    
    $colorClasses = [
        'green' => 'bg-green-50 border-green-200 text-green-800',
        'yellow' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'red' => 'bg-red-50 border-red-200 text-red-800',
        'gray' => 'bg-gray-50 border-gray-200 text-gray-800',
    ];
    
    $hasData = count(array_filter($chartData)) > 0;
    
    $chartValues = [];
    $chartLabels = [];
    $pointBackgroundColors = [];
    $pointBorderColors = [];
    
    foreach ($weekDays as $day) {
        $chartLabels[] = $day;
        $value = $chartData[$day] ?? null;
        $chartValues[] = $value;
        
        if ($day === now()->format('D')) {
            $pointBackgroundColors[] = $value !== null ? '#00C3B3' : '#E5E7EB';
            $pointBorderColors[] = $value !== null ? '#FFFFFF' : '#D1D5DB';
        } else {
            $pointBackgroundColors[] = $value !== null ? '#00C3B3' : '#E5E7EB';
            $pointBorderColors[] = $value !== null ? '#FFFFFF' : '#D1D5DB';
        }
    }
    
    // Mood mapping tooltip
    $moodLabels = [
        1 => ['emoji' => '😢', 'label' => 'Sad'],
        2 => ['emoji' => '😕', 'label' => 'Flat'],
        3 => ['emoji' => '😐', 'label' => 'Good'],
        4 => ['emoji' => '🙂', 'label' => 'Happy'],
        5 => ['emoji' => '😊', 'label' => 'Blissful'],
    ];
    
    $jsChartData = json_encode([
        'labels' => $chartLabels,
        'values' => $chartValues,
        'backgroundColors' => $pointBackgroundColors,
        'borderColors' => $pointBorderColors,
        'moodLabels' => $moodLabels,
    ]);
@endphp

<div {{ $attributes->merge(['class' => 'bg-white p-6 rounded-md border-grey-border border']) }}>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="font-bold text-lg">Weekly Mood Overview</h2>
            <p class="text-gray-500 text-sm">Track how you've been feeling this week</p>
        </div>
        <div class="text-sm text-gray-500 text-left md:text-right">
            <span class="block">Period</span>
            <span class="font-medium">{{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d') }}</span>
        </div>
    </div>

    <!-- Chart -->
    <div class="mb-8">
        <div class="relative w-full" style="height: {{ $height }}; min-height: 200px;">
            <canvas id="{{ $chartId }}" class="w-full h-full"></canvas>
                
            @if(!$hasData)
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center text-gray-400 px-4">
                        <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <p class="text-sm font-medium">No mood data this week</p>
                        <p class="text-xs mt-1">Log your mood to see the chart</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Mood Note -->
    @if($hasData)
        <div class="{{ $colorClasses[$note['color'] ?? 'gray'] }} p-4 rounded-lg border mb-6">
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-800">{{ $note['title'] ?? 'No Data' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $note['subtitle'] ?? 'Log your mood to see insights' }}</p>
                    
                    @if(($note['suggest_appointment'] ?? false))
                        <div class="mt-3">
                            <a href="{{ route('patient.find.psychologist') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-700 hover:bg-red-200 rounded-md text-sm font-medium transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Book a Session
                            </a>
                        </div>
                    @endif
                </div>
                
                <!-- Average Mood Display -->
                <div class="text-center md:text-right">
                    <div class="text-2xl font-bold text-gray-800">{{ number_format($averageMood, 1) }}</div>
                    <div class="text-xs text-gray-600">Avg. Mood</div>
                    @if($averageMood > 0)
                        <div class="text-xs mt-1">
                            @if($averageMood >= 4)
                                <span class="text-green-600 font-medium">↑ High</span>
                            @elseif($averageMood >= 2.5)
                                <span class="text-yellow-600 font-medium">— Moderate</span>
                            @else
                                <span class="text-red-600 font-medium">↓ Low</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    
    <!-- Legend -->
    <div class="pt-6 border-t border-gray-100">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between text-sm gap-4 sm:gap-0">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-primary"></div>
                    <span class="text-gray-600">Your Mood</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                    <span class="text-gray-600">No Data</span>
                </div>
            </div>
            <div class="text-gray-500 font-medium">
                {{ count(array_filter($chartData)) }}/{{ count($chartData) }} days logged
            </div>
        </div>
    </div>
</div>

@if($hasData)
@push('scripts')
<script>
document.addEventListener('alpine:init', function() {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }
    
    const chartElement = document.getElementById('{{ $chartId }}');
    if (!chartElement) {
        console.error('Chart element not found: {{ $chartId }}');
        return;
    }
    
    const chartData = {!! $jsChartData !!};
    
    if (chartElement.chart) {
        chartElement.chart.destroy();
    }
    
    const ctx = chartElement.getContext('2d');
    
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(139, 92, 246, 0.15)');
    gradient.addColorStop(1, 'rgba(139, 92, 246, 0.01)');
    
    function getSegmentColor(ctx) {
        if (ctx.p0.parsed.y === ctx.p1.parsed.y) {
            const value = ctx.p0.parsed.y;
            if (value === 5) return '#10B981';
            if (value === 4) return '#34D399';
            if (value === 3) return '#FBBF24';
            if (value === 2) return '#F59E0B';
            if (value === 1) return '#  ';
        }
        return '#8B5CF6';
    }
    
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Mood',
                data: chartData.values,
                backgroundColor: chartData.backgroundColors,
                borderColor: chartData.borderColors,
                borderWidth: 2,
                pointRadius: function(context) {
                    const index = context.dataIndex;
                    const day = chartData.labels[index];
                    const today = new Date().toLocaleDateString('en-US', { weekday: 'short' });
                    return day === today ? 8 : 6;
                },
                pointHoverRadius: 10,
                fill: true,
                tension: 0.3,
                segment: {
                    borderColor: getSegmentColor,
                    backgroundColor: gradient
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 10,
                    bottom: 10
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 0.5,
                    max: 5.5,
                    offset: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return chartData.moodLabels[value]?.emoji || '';
                        },
                        font: {
                            size: 14,
                            family: "'Inter', sans-serif"
                        },
                        padding: 8
                    },
                    grid: {
                        drawBorder: false,
                        color: 'rgba(0, 0, 0, 0.04)',
                        drawTicks: false
                    },
                    title: {
                        display: false
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif",
                            weight: '500'
                        },
                        color: '#6B7280',
                        maxRotation: 0
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleFont: {
                        size: 12,
                        family: "'Inter', sans-serif",
                        weight: '600'
                    },
                    bodyFont: {
                        size: 11,
                        family: "'Inter', sans-serif"
                    },
                    padding: {
                        top: 8,
                        bottom: 8,
                        left: 12,
                        right: 12
                    },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y;
                            if (!value) return 'No data';
                            const mood = chartData.moodLabels[value];
                            return `${mood.emoji} ${mood.label}`;
                        },
                        title: function(context) {
                            const day = context[0].label;
                            const today = new Date().toLocaleDateString('en-US', { weekday: 'short' });
                            return day === today ? `Today (${day})` : day;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            elements: {
                point: {
                    hoverBackgroundColor: '#2E6F6D',
                    hoverBorderColor: '#FFFFFF',
                    hoverBorderWidth: 3
                },
                line: {
                    borderWidth: 2,
                    tension: 0.3
                }
            }
        }
    });
    
    chartElement.chart = chart;
    
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (chart) {
                chart.resize();
            }
        }, 250);
    });
});

if (typeof Alpine === 'undefined') {
    document.addEventListener('DOMContentLoaded', function() {
        const event = new Event('alpine:init');
        document.dispatchEvent(event);
    });
}
</script>
@endpush
@endif