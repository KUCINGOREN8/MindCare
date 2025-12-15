    @extends('layouts.dashboard')
    @section('title', 'Appointments')

    @section('content')
    <div class="flex flex-col flex-1 gap-6 min-w-0 h-full overflow-y-auto pr-2 pb-20 scroll-smooth">

        <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
            <h1 class="text-primary font-bold text-lg">Appointments</h1>
            <h5 class="text-captiondark text-sm">Manage your sessions and history.</h5>
        </div>

        {{-- Upcoming Appointment --}}
        @include('components.upcoming-appointment', [
            'upcomingAppointments' => $upcomingAppointments,
            'user' => $user,
            'showSeeAll' => false
        ])
        {{-- History --}}
        @include('components.history-appointment')

        {{-- Reschedule --}}
        

        @include('components.reschedule-modal')


    </div>
    @endsection

    <script>
    let currentPsychologistSchedules = [];
    const SESSION_DURATION_MINUTES = 60;

    function openRescheduleModal(id, date, time, schedulesJson) {
        const modal = document.getElementById('rescheduleModal');
        const form = document.getElementById('rescheduleForm');
        const dateSelect = document.getElementById('reschedule_date_select');
        
        try {
            currentPsychologistSchedules = JSON.parse(schedulesJson);
        } catch (e) {
            console.error("Failed to parse schedules JSON:", e);
            return;
        }

        form.action = `/patient/appointments/${id}/reschedule`;
        
        populateAvailableDates(dateSelect, date);
        
        dateSelect.value = date;
        updateTimeSlots(date); 

        modal.classList.remove('hidden');
    }

    function closeRescheduleModal() {
        document.getElementById('rescheduleModal').classList.add('hidden');
    }

    function populateAvailableDates(selectElement, currentDate) {
        selectElement.innerHTML = '<option value="">Select New Date</option>';

        const availableDays = currentPsychologistSchedules.map(s => s.day_of_week.toLowerCase());
        const availableDates = new Set();
        
        for (let i = 1; i <= 30; i++) {
            const nextDate = new Date();
            nextDate.setDate(nextDate.getDate() + i);
            
            const dayOfWeek = nextDate.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
            const dateString = nextDate.toISOString().split('T')[0];

            if (availableDays.includes(dayOfWeek)) {
                availableDates.add(dateString);
            }
        }

        Array.from(availableDates).sort().forEach(date => {
            const option = document.createElement('option');
            option.value = date;
            option.textContent = new Date(date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric' });
            selectElement.appendChild(option);
        });
    }

    function updateTimeSlots(selectedDate) {
        const timeSelect = document.getElementById('reschedule_time_select');
        timeSelect.innerHTML = '<option value="">Select New Time</option>';
        
        if (!selectedDate) return;

        const selectedDay = new Date(selectedDate + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();

        const slotsForDay = currentPsychologistSchedules.filter(
            schedule => schedule.day_of_week.toLowerCase() === selectedDay
        );

        if (slotsForDay.length === 0) {
            timeSelect.innerHTML = '<option value="" disabled>No slots available on this day.</option>';
            return;
        }

        const availableTimeSlots = new Set();

        slotsForDay.forEach(slot => {
            let current = parseTime(slot.start_time);
            const end = parseTime(slot.end_time);
            
            while (current.getTime() + (SESSION_DURATION_MINUTES * 60000) <= end.getTime()) {
                const timeString = formatTime(current);
                availableTimeSlots.add(timeString);
                
                current = new Date(current.getTime() + (SESSION_DURATION_MINUTES * 60000));
            }
        });

        Array.from(availableTimeSlots).sort().forEach(time => {
            const option = document.createElement('option');
            option.value = time;
            option.textContent = time; 
            timeSelect.appendChild(option);
        });
    }

    function parseTime(time) {
        const d = new Date();
        const [h, m, s] = time.split(':');
        d.setHours(h, m, s || 0, 0);
        return d;
    }

    function formatTime(date) {
        const h = date.getHours().toString().padStart(2, '0');
        const m = date.getMinutes().toString().padStart(2, '0');
        return `${h}:${m}`;
    }
</script>
