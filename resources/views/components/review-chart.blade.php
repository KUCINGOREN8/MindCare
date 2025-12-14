<div class="bg-white p-6 rounded-md border-grey-border border">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-lg">Rating Distribution</h3>
            <p class="text-sm text-gray-500">Based on patient reviews</p>
        </div>
        <div class="text-right">
            <div class="text-2xl font-bold text-primary">
                {{ number_format($averageRating, 1) }}<span class="text-lg">/5</span>
            </div>
            <div class="text-sm text-gray-500">Average Rating</div>
        </div>
    </div>
    
    <div class="flex flex-col xl:flex-row items-center gap-8">
        {{-- Chart --}}
        <div class="w-full lg:w-1/2">
            <div class="chart-container" style="position: relative; height: 250px;">
                <canvas id="ratingDistributionChart"></canvas>
            </div>
        </div>
        
        {{-- Legend --}}
        <div class="w-full lg:w-1/2">
            <div class="space-y-4">
                @foreach($labels as $index => $label)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-blue-50 transition-colors">
                    <div class="flex items-center">
                        <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $colors[$index] }}"></div>
                        <div>
                            <div class="font-medium">{{ $label }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $totalReviews > 0 ? round(($data[$index] / $totalReviews) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="font-bold">{{ $data[$index] }}</div>
                        <div class="text-xs text-gray-500">reviews</div>
                    </div>
                </div>
                @endforeach
                
                {{-- Summary --}}
                <div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $data[4] }}</div>
                            <div class="text-sm text-gray-600">5★ Reviews</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $totalReviews }}</div>
                            <div class="text-sm text-gray-600">Total Reviews</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((context.raw / total) * 100) : 0;
                                    return `${context.label}: ${context.raw} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</div>