@extends('layouts.auth')
@section('title', 'Sign Up as Psychologist')

@section('content')
<div class="min-h-screen bg-gray-50">
    @include('auth.partials.progress')

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-center mb-2" style="color: #009C8F;">Availability Schedule</h2>
            <p class="text-gray-600 text-center mb-8">Step 5: Set your consultation hours</p>

            <form method="POST" action="{{ route('psychologist.signup.storeStep5', $user) }}" id="scheduleForm">
                @csrf
                <div id="scheduleContainer">
                    @php $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']; @endphp

                    @foreach($days as $index => $day)
                    <div class="schedule-day bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800 capitalize">{{ $day }}</h3>
                            <label class="flex items-center">
                                <input type="checkbox" name="schedules[{{ $index }}][not_available]"
                                       class="not-available-toggle mr-2"
                                       style="accent-color: #dc2626;"
                                       data-index="{{ $index }}">
                                <span class="text-sm text-gray-600">Not available</span>
                            </label>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 schedule-fields" id="schedule-fields-{{ $index }}">
                            <input type="hidden" name="schedules[{{ $index }}][day_of_week]" value="{{ $day }}">

                            <div>
                                <label class="block text-gray-700 mb-2">Start Time</label>
                                <input type="time" name="schedules[{{ $index }}][start_time]"
                                       class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 schedule-time"
                                       value="09:00">
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-2">End Time</label>
                                <input type="time" name="schedules[{{ $index }}][end_time]"
                                       class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300 schedule-time"
                                       value="17:00">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-medium text-blue-800 mb-1">Important Notes</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Set start and end time for each day you're available</li>
                                <li>• Check "Not available" for days you don't work</li>
                                <li>• You can update your availability later from your profile</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-between">
                    <a href="{{ route('psychologist.signup.step4', $user) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        ← Back
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#009C8F] text-white rounded-lg font-medium hover:opacity-90 transition shadow-md">
                        Complete Registration →
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/step5.js') }}"></script>
@endsection
