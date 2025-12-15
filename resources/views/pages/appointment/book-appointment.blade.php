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
        availablePsychologists: [],
        isLoading: false,
        errorMessage: null,

        init() {
            this.availablePsychologists = @json($psychologists->map(function($p) {
                return [
                    'id' => $p->id,
                    'user' => [
                        'full_name' => $p->user->full_name,
                        'gender' => $p->user->gender,
                        'photo_url' => $p->user->photo_url
                    ],
                    'title' => $p->title,
                    'consultation_fee' => $p->consultation_fee
                ];
            }));
        },

        async fetchAvailablePsychologists() {
            if (!this.selectedDate || !this.selectedTime) {
                this.availablePsychologists = [];
                this.selectedPsychologist = null;
                return;
            }

            this.isLoading = true;
            this.errorMessage = null;

            try {
                const response = await fetch('{{ route('patient.psychologists.available') }}?' + new URLSearchParams({
                    date: this.selectedDate,
                    time: this.selectedTime
                }));

                if (!response.ok) throw new Error('Network response was not ok');

                const data = await response.json();
                this.availablePsychologists = data;

                if (this.selectedPsychologist && !data.find(p => p.id == this.selectedPsychologist)) {
                    this.selectedPsychologist = null;
                }
            } catch (err) {
                console.error('Error fetching psychologists:', err);
                this.errorMessage = 'Failed to load available psychologists';
                this.availablePsychologists = [];
            } finally {
                this.isLoading = false;
            }
        },

        calculateEndTime(startTime) {
            if (!startTime) return '';
            const [hours, minutes] = startTime.split(':');
            const totalMinutes = parseInt(hours) * 60 + parseInt(minutes) + 90;
            const endHour = Math.floor(totalMinutes / 60);
            const endMinute = totalMinutes % 60;
            return `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
        },

        getPsychologistById(id) {
            return this.availablePsychologists.find(p => p.id == id);
        }
    }"
    x-init="
        $watch('selectedDate', () => {
            if (this.selectedDate && this.selectedTime) this.fetchAvailablePsychologists();
        });
        $watch('selectedTime', () => {
            if (this.selectedDate && this.selectedTime) this.fetchAvailablePsychologists();
        });
    ">

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
                    x-model="selectedDate"
                    min="{{ date('Y-m-d') }}"
                    max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                    @change="selectedTime = null; selectedPsychologist = null; availablePsychologists = []">
            </div>

            <div x-show="selectedDate" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="font-semibold mb-4 text-lg">{{ __('book_appointment.step_2') }}</h2>

                <div x-show="isLoading" class="text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                </div>

                <div class="grid grid-cols-4 gap-3">
                    <template x-for="time in ['09:00','09:30','10:00','10:30','11:00','11:30','12:00','13:00','14:00','15:00']">
                        <button class="px-4 py-2 rounded-lg border text-sm transition-all"
                            :class="selectedTime === time ?
                                'bg-primary text-white border-primary shadow' :
                                'bg-white border-gray-300 hover:border-primary hover:bg-gray-50'"
                            @click="selectedTime = time; selectedPsychologist = null"
                            x-text="time"
                            :disabled="isLoading">
                        </button>
                    </template>
                </div>
            </div>

            <div x-show="selectedDate && selectedTime" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h2 class="font-semibold mb-4 text-lg">{{ __('book_appointment.step_3') }}</h2>

                <div x-show="isLoading" class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                    <p class="mt-2 text-gray-600">Loading available psychologists...</p>
                </div>

                <div x-show="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
                    <p x-text="errorMessage"></p>
                </div>

                <div x-show="!isLoading && availablePsychologists.length === 0" class="text-center py-8">
                    <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded inline-block">
                        <p>No psychologists available for selected date and time.</p>
                    </div>
                </div>

                <div x-show="!isLoading && availablePsychologists.length > 0" class="grid grid-cols-3 gap-6">
                    <template x-for="p in availablePsychologists" :key="p.id">
                        <div class="border rounded-xl p-5 text-center cursor-pointer transition-all hover:shadow-md"
                            @click="selectedPsychologist = p.id"
                            :class="selectedPsychologist == p.id ? 'border-primary shadow-md' : 'border-gray-300'">

                            <img class="mx-auto mb-3 w-20 h-20 rounded-full object-cover shadow"
                                :src="p.user.photo_url ? '{{ asset('') }}' + p.user.photo_url :
                                      (p.user.gender == 'female' ? '{{ asset('assets/icons/user_female.svg') }}' :
                                      '{{ asset('assets/icons/user_male.svg') }}')" />

                            <div class="font-semibold text-gray-800" x-text="p.user.full_name"></div>
                            <div class="text-xs text-gray-500" x-text="p.title"></div>
                        </div>
                    </template>
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
                        <template x-for="p in availablePsychologists">
                            <span class="block" x-show="selectedPsychologist == p.id"
                                x-text="p.user.full_name + ' - ' + p.title">
                            </span>
                        </template>
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
@endsection

@section('scripts')
<script>
    function getConsultationFee(psychologistId) {
        const alpineData = Alpine.$data(document.querySelector('[x-data]'));
        if (!alpineData || !alpineData.availablePsychologists) return 0;

        const psychologist = alpineData.availablePsychologists.find(p => p.id == psychologistId);
        return psychologist ? psychologist.consultation_fee : 0;
    }

    function getPsychologistName(psychologistId) {
        const alpineData = Alpine.$data(document.querySelector('[x-data]'));
        if (!alpineData || !alpineData.availablePsychologists) return '';

        const psychologist = alpineData.availablePsychologists.find(p => p.id == psychologistId);
        return psychologist ? psychologist.user.full_name : '';
    }

    function getPsychologistTitle(psychologistId) {
        const alpineData = Alpine.$data(document.querySelector('[x-data]'));
        if (!alpineData || !alpineData.availablePsychologists) return '';

        const psychologist = alpineData.availablePsychologists.find(p => p.id == psychologistId);
        return psychologist ? psychologist.title : '';
    }
</script>
@endsection
