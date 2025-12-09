@extends('layouts.dashboard')

@section('title')
Book Appointment
@endsection

@section('content')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<div class="flex flex-1 min-w-0 gap-6"
     x-data="{
         selectedDate: null,
         selectedTime: null,
         selectedPsychologist: null,
     }">

    <!-- LEFT CONTENT -->
    <div class="flex flex-col flex-1 gap-6">

        <!-- HEADER -->
        <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
            <h1 class="text-primary font-bold text-lg">Book an Appointment</h1>
            <p class="text-captiondark text-sm">
                Choose your preferred date, time, and psychologist to continue.
            </p>
        </div>

        <!-- DATE SELECTION -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="font-semibold mb-4 text-lg">1. Select Date</h2>

            <input type="date"
                   class="border border-gray-300 focus:ring-primary focus:border-primary rounded-lg px-4 py-2 w-56"
                   x-model="selectedDate">
        </div>

        <!-- TIME SELECTION -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="font-semibold mb-4 text-lg">2. Choose Time</h2>

            <div class="grid grid-cols-4 gap-3">
                <template x-for="time in ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','13:00','14:00','15:00']">
                    <button
                        class="px-4 py-2 rounded-lg border text-sm transition-all"
                        :class="selectedTime === time
                                ? 'bg-primary text-white border-primary shadow'
                                : 'bg-white border-gray-300 hover:border-primary hover:bg-gray-50'"
                        @click="selectedTime = time"
                        x-text="time">
                    </button>
                </template>
            </div>
        </div>

        <!-- PSYCHOLOGIST SELECTION -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="font-semibold mb-4 text-lg">3. Pick a Psychologist</h2>

            <div class="grid grid-cols-3 gap-6">
                @foreach($psychologists as $p)
                <div class="border rounded-xl p-5 text-center cursor-pointer transition-all hover:shadow-md"
                     @click="selectedPsychologist = {{ $p->id }}"
                     :class="selectedPsychologist == {{ $p->id }} ? 'border-primary shadow-md' : 'border-gray-300'">

                    <img class="mx-auto mb-3 w-20 h-20 rounded-full object-cover shadow"
                         src="{{ $p->user->photo_url ? asset($p->user->photo_url) : ($p->user->gender=='female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" />

                    <div class="font-semibold text-gray-800">{{ $p->user->full_name }}</div>
                    <div class="text-xs text-gray-500">{{ $p->title }}</div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- RIGHT SIDEBAR -->
        <div class="w-80 bg-white border border-gray-200 rounded-xl p-6 h-fit sticky top-6 shadow-sm">
            <h3 class="font-semibold text-lg mb-4">Appointment Summary</h3>

            <div class="text-sm space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Date</span>
                    <span class="font-medium" x-text="selectedDate || '-'"></span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Time</span>
                    <span class="font-medium" x-text="selectedTime ? selectedTime + ' - ' + calculateEndTime(selectedTime) : '-'"></span>
                </div>

                 <div class="flex justify-between">
                    <span class="text-gray-500">Duration</span>
                    <span class="font-medium">90 minutes</span>
                </div>

                <div>
                    <span class="text-gray-500">Psychologist</span>
                    <div class="mt-1 font-medium text-right">
                        @foreach($psychologists as $p)
                            <span class="block"
                                x-show="selectedPsychologist == {{ $p->id }}"
                                x-text="'{{ $p->user->full_name }} - {{ $p->title }}'">
                            </span>
                        @endforeach
                        <span x-show="!selectedPsychologist">-</span>
                    </div>
                </div>

                <div class="flex justify-between border-t pt-4">
                    <span class="text-gray-500 font-semibold">Fee</span>
                    <span class="font-bold text-lg"
                        x-text="selectedPsychologist ? 'Rp ' + getConsultationFee(selectedPsychologist).toLocaleString('id-ID') : '-'">
                    </span>
                </div>
            </div>

            <!-- FORM HERE -->
            <form method="POST" action="{{ route('patient.appointments.store') }}" class="mt-6" id="appointmentForm">
                @csrf

                <input type="hidden" name="date" x-model="selectedDate">
                <input type="hidden" name="start_time" x-model="selectedTime">
                <input type="hidden" name="psychologist_id" x-model="selectedPsychologist">
                <input type="hidden" name="end_time" x-bind:value="calculateEndTime(selectedTime)">
                <input type="hidden" name="consultation_fee" x-bind:value="getConsultationFee(selectedPsychologist)">
                <input type="hidden" name="with" x-bind:value="getPsychologistName(selectedPsychologist)">
                <input type="hidden" name="job_title" x-bind:value="getPsychologistTitle(selectedPsychologist)">

                <button
                    type="submit"
                    class="w-full bg-primary text-white py-3 rounded-lg font-medium transition disabled:opacity-40 hover:bg-primary/90"
                    :disabled="!selectedDate || !selectedTime || !selectedPsychologist">
                    Confirm & Pay
                </button>
            </form>

        </div>


    </div>

</div>

@endsection

<script>
    const psychologists = @json($psychologists->keyBy('id'));

    function calculateEndTime(startTime) {
        if (!startTime) return '';
        const [hours, minutes] = startTime.split(':');

        const totalMinutes = parseInt(hours) * 60 + parseInt(minutes) + 90;
        const endHour = Math.floor(totalMinutes / 60);
        const endMinute = totalMinutes % 60;
        return `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
    }

    function getConsultationFee(psychologistId) {
        return psychologists[psychologistId]?.consultation_fee || 0;
    }

    function getPsychologistName(psychologistId) {
        return psychologists[psychologistId]?.user?.full_name || '';
    }

    function getPsychologistTitle(psychologistId) {
        return psychologists[psychologistId]?.title || '';
    }
</script>
