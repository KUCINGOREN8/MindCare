@extends('layouts.dashboard')

@section('title')
    {{ __('book_appointment.page_title') }}
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <div class="flex flex-1 min-w-0 gap-6" x-data="{
        selectedDate: null,
        selectedTime: null,
        selectedPsychologist: null,
    }">

        <div class="flex flex-col flex-1 gap-6">

            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
                <h1 class="text-primary font-bold text-lg">{{ __('book_appointment.header_title') }}</h1>
                <p class="text-captiondark text-sm">
                    {{ __('book_appointment.header_subtitle') }}
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="font-semibold mb-4 text-lg">{{ __('book_appointment.step_1') }}</h2>

                <input type="date"
                    class="border border-gray-300 focus:ring-primary focus:border-primary rounded-lg px-4 py-2 w-56"
                    x-model="selectedDate">
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="font-semibold mb-4 text-lg">{{ __('book_appointment.step_2') }}</h2>

                <div class="grid grid-cols-4 gap-3">
                    <template
                        x-for="time in ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','13:00','14:00','15:00']">
                        <button class="px-4 py-2 rounded-lg border text-sm transition-all"
                            :class="selectedTime === time ?
                                'bg-primary text-white border-primary shadow' :
                                'bg-white border-gray-300 hover:border-primary hover:bg-gray-50'"
                            @click="selectedTime = time" x-text="time">
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="font-semibold mb-4 text-lg">{{ __('book_appointment.step_3') }}</h2>

                <div class="grid grid-cols-3 gap-6">
                    @foreach ($psychologists as $p)
                        <div class="border rounded-xl p-5 text-center cursor-pointer transition-all hover:shadow-md"
                            @click="selectedPsychologist = {{ $p->id }}"
                            :class="selectedPsychologist == {{ $p->id }} ? 'border-primary shadow-md' : 'border-gray-300'">

                            <img class="mx-auto mb-3 w-20 h-20 rounded-full object-cover shadow"
                                src="{{ $p->user->photo_url ? asset($p->user->photo_url) : ($p->user->gender == 'female' ? asset('assets/icons/user_female.svg') : asset('assets/icons/user_male.svg')) }}" />

                            <div class="font-semibold text-gray-800">{{ $p->user->full_name }}</div>
                            <div class="text-xs text-gray-500">{{ $p->title }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="w-80 bg-white border border-gray-200 rounded-xl p-6 h-fit sticky top-6 shadow-sm">
            <h3 class="font-semibold text-lg mb-4">{{ __('book_appointment.summary_title') }}</h3>

            <div class="text-sm space-y-4">
                {{-- Date --}}
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('book_appointment.label_date') }}</span>
                    <span class="font-medium" x-text="selectedDate || '-'"></span>
                </div>

                {{-- Time --}}
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('book_appointment.label_time') }}</span>
                    <span class="font-medium"
                        x-text="selectedTime ? selectedTime + ' - ' + calculateEndTime(selectedTime) : '-'"></span>
                </div>

                {{-- Duration --}}
                <div class="flex justify-between">
                    <span class="text-gray-500">{{ __('book_appointment.label_duration') }}</span>
                    <span class="font-medium">{{ __('book_appointment.duration_val') }}</span>
                </div>

                {{-- Psychologist --}}
                <div>
                    <span class="text-gray-500">{{ __('book_appointment.label_psychologist') }}</span>
                    <div class="mt-1 font-medium text-right">
                        @foreach ($psychologists as $p)
                            <span class="block" x-show="selectedPsychologist == {{ $p->id }}"
                                x-text="'{{ $p->user->full_name }} - {{ $p->title }}'">
                            </span>
                        @endforeach
                        <span x-show="!selectedPsychologist">-</span>
                    </div>
                </div>

                {{-- Fee --}}
                <div class="flex justify-between border-t pt-4">
                    <span class="text-gray-500 font-semibold">{{ __('book_appointment.label_fee') }}</span>
                    <span class="font-bold text-lg"
                        x-text="selectedPsychologist ? 'Rp ' + getConsultationFee(selectedPsychologist).toLocaleString('id-ID') : '-'">
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('appointments.store') }}" class="mt-6" id="appointmentForm">
                @csrf

                <input type="hidden" name="date" x-model="selectedDate">
                <input type="hidden" name="start_time" x-model="selectedTime">
                <input type="hidden" name="psychologist_id" x-model="selectedPsychologist">
                <input type="hidden" name="end_time" x-bind:value="calculateEndTime(selectedTime)">
                <input type="hidden" name="consultation_fee" x-bind:value="getConsultationFee(selectedPsychologist)">
                <input type="hidden" name="with" x-bind:value="getPsychologistName(selectedPsychologist)">
                <input type="hidden" name="job_title" x-bind:value="getPsychologistTitle(selectedPsychologist)">

                <button type="submit"
                    class="w-full bg-primary text-white py-3 rounded-lg font-medium transition disabled:opacity-40 hover:bg-primary/90"
                    :disabled="!selectedDate || !selectedTime || !selectedPsychologist">
                    {{ __('book_appointment.btn_confirm') }}
                </button>
            </form>

        </div>


    </div>

    </div>
@endsection

@section('scripts')
    <script>
        // variabel psychologists yang didefinisikan di PHP dan diakses di JS
        const psychologists = @json($psychologists->keyBy('id'));

        function calculateEndTime(startTime) {
            if (!startTime) return '';
            const [hours, minutes] = startTime.split(':');

            const totalMinutes = parseInt(hours) * 60 + parseInt(minutes) + 90; // 90 menit durasi
            const endHour = Math.floor(totalMinutes / 60);
            const endMinute = totalMinutes % 60;
            return `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
        }

        function getConsultationFee(psychologistId) {
            // Menggunakan operator optional chaining (?) untuk keamanan
            return psychologists[psychologistId]?.consultation_fee || 0;
        }

        function getPsychologistName(psychologistId) {
            return psychologists[psychologistId]?.user?.full_name || '';
        }

        function getPsychologistTitle(psychologistId) {
            return psychologists[psychologistId]?.title || '';
        }
    </script>
@endsection
