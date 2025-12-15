<div class="bg-white p-6 rounded-md border-grey-border border flex flex-1 flex-col">
    <div class="flex justify-between mb-6">
        <div>
            {{-- TRANSLATE TITLE --}}
            <h3 class="font-bold">{{ __('psychologist_dashboard.rating_title') }}</h3>

            {{-- TRANSLATE SUBTITLE WITH PARAMETER --}}
            <p class="text-sm text-gray-500">
                {{ __('psychologist_dashboard.rating_based_on', ['count' => $totalReviews]) }}
            </p>
        </div>
        <div class="text-right flex items-center align-middle gap-2">
            <img src="{{ asset('assets/icons/star.png') }}" class="w-5 h-5" alt="star">
            <div class="text-2xl font-bold">
                {{ number_format($averageRating, 1) }}<span class="text-sm">/5</span>
            </div>
        </div>
    </div>

    <div class="flex justify-center items-center">
        <div class="flex flex-col items-center gap-8">
            {{-- Chart --}}
            <div class="w-full md:w-1/2">
                <div class="chart-container" style="position: relative; height: 250px;">
                    <canvas id="ratingDistributionChart"></canvas>
                </div>
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap justify-center gap-6">
                @foreach ($colors as $index => $color)
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full" style="background-color: {{ $color }}"></div>
                        {{-- Pastikan $labels[$index] isinya sudah diterjemahkan dari Controller jika mengandung kata (misal: "5 Bintang") --}}
                        <p class="text-xs">{{ $labels[$index] ?? 'Label' }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('ratingDistributionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($labels),
                datasets: [{
                    data: @json($data),
                    backgroundColor: @json($colors),
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((context.raw / total) *
                                    100) : 0;
                                return `${context.label}: ${context.raw} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
