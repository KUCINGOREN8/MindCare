<form id="scheduleForm" method="POST" action="{{ route('profile.schedule.update') }}" class="space-y-6">
    @csrf
    @method('PUT')
    
    @php
        $days = [
            'mon' => ['day' => 'Monday', 'abbr' => 'Mon'],
            'tue' => ['day' => 'Tuesday', 'abbr' => 'Tue'],
            'wed' => ['day' => 'Wednesday', 'abbr' => 'Wed'],
            'thu' => ['day' => 'Thursday', 'abbr' => 'Thu'],
            'fri' => ['day' => 'Friday', 'abbr' => 'Fri'],
            'sat' => ['day' => 'Saturday', 'abbr' => 'Sat'],
            'sun' => ['day' => 'Sunday', 'abbr' => 'Sun']
        ];
        
        $existingSchedules = $psychologist->schedules->keyBy('day_of_week');
        $oldSchedules = old('schedules', []);
    @endphp
    
    <div class="space-y-4">
        @foreach($days as $key => $day)
            @php
                $schedule = $existingSchedules[$key] ?? null;
                $startTime = $schedule ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '';
                $endTime = $schedule ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '';
                $isAvailable = $schedule !== null;
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-[20%_80%] gap-4 schedule-day" data-day="{{ $key }}">
                <div class="flex items-center">
                    <label class="block text-[#4D4D4E]">{{ $day['day'] }}</label>
                </div>
                
                <div class="space-y-2">
                    <div class="flex items-center mb-2">
                        <input type="hidden" name="schedules[{{ $key }}][day_of_week]" value="{{ $key }}">
                        
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="hidden" name="schedules[{{ $key }}][is_available]" value="0" data-hidden-for="{{ $key }}">
                            <input type="checkbox" 
                                   name="schedules[{{ $key }}][is_available]"
                                   value="1"
                                   class="sr-only peer availability-toggle"
                                   data-day="{{ $key }}"
                                   {{ $isAvailable ? 'checked' : '' }}
                                   disabled
                                   data-disabled="true"
                                   data-original-value="{{ $isAvailable ? 'true' : 'false' }}">
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer 
                                        peer-checked:after:translate-x-full peer-checked:after:border-white 
                                        after:content-[''] after:absolute after:top-[2px] after:left-[2px] 
                                        after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                        peer-checked:bg-[#00C3B3]"></div>
                            <span class="ml-3 text-sm text-[#4D4D4E]" id="availability-label-{{ $key }}">
                                {{ $isAvailable ? 'Available' : 'Not Available' }}
                            </span>
                        </label>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 time-inputs {{ !$isAvailable ? 'hidden' : '' }}" 
                         data-day="{{ $key }}">
                        <!-- Start Time -->
                        <div>
                            <label class="block text-sm text-[#4D4D4E] mb-1">Start Time</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input 
                                    type="time" 
                                    name="schedules[{{ $key }}][start_time]"
                                    value="{{ $startTime }}"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                    readonly
                                    data-readonly="true"
                                    data-original-value="{{ $startTime }}"
                                >
                            </div>
                        </div>
                        
                        <!-- End Time -->
                        <div>
                            <label class="block text-sm text-[#4D4D4E] mb-1">End Time</label>
                            <div class="flex items-center rounded-lg px-4 py-3 shadow-sm" style="background-color: #FAFAFA;">
                                <input 
                                    type="time" 
                                    name="schedules[{{ $key }}][end_time]"
                                    value="{{ $endTime }}"
                                    class="w-full outline-none text-black placeholder-gray-400 bg-transparent read-only:text-[#A1AAB2]"
                                    readonly
                                    data-readonly="true"
                                    data-original-value="{{ $endTime }}"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(!$loop->last)
                <div class="border-b border-gray-100 my-4"></div>
            @endif
        @endforeach

        @error('schedules')
            <div class="mt-4">
                <p class="text-red-500 mt-2 ml-1">{{ $message }}</p>
            </div>
        @enderror
    </div>
    
    <div class="pt-6 flex space-x-4 justify-end">
        <button 
            type="button" 
            data-cancel-form="scheduleForm"
            class="px-4 py-2 bg-[#FF383C] hover:bg-[#C9282B] text-white rounded-md hidden"
            onclick="cancelEdit('scheduleForm')"
        >
            Cancel
        </button>

        <button 
            type="button" 
            data-edit-form="scheduleForm"
            class="px-4 py-2 bg-[#00C3B3] hover:bg-[#179990] text-white rounded-md"
            onclick="toggleEdit('scheduleForm')"
        >
            Edit
        </button>
    </div>
</form>

<style>
/* Custom styling untuk schedule form */
.availability-toggle:disabled + div {
    background-color: #e5e7eb !important;
    cursor: not-allowed;
}

.availability-toggle:disabled:checked + div {
    background-color: #a7f3d0 !important;
}

.time-inputs input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
}

.time-inputs input[readonly]::-webkit-calendar-picker-indicator {
    display: none;
}

.schedule-day:not(:last-child) {
    padding-bottom: 1rem;
}
</style>