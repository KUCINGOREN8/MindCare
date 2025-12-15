@extends('layouts.dashboard')

@section('title')
    {{ __('appointment.title') }}
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- SUNTIKKAN TRANSLATION KE X-DATA --}}
    <div class="flex flex-1 min-w-0 gap-6" x-data="appointmentFlow({
        alertComplete: '{{ __('appointment.alert_complete_fields') }}',
        notSelected: '{{ __('appointment.not_selected') }}'
    })">

        <div class="flex flex-col flex-1 gap-6">
            <div class="flex flex-col bg-white p-6 rounded-md border-grey-border border">
                <h1 class="text-primary font-bold text-lg">{{ __('appointment.header_title') }}</h1>
                <p class="text-captiondark text-sm">
                    {{ __('appointment.header_desc') }}
                </p>
            </div>

            @if ($isSpecific)
                <div class="bg-white p-6 rounded-xl border border-primary shadow-sm">
                    <h2 class="font-semibold mb-4 text-lg">{{ __('appointment.psychologist_selected') }}</h2>
                    <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                        <img class="w-16 h-16 rounded-full object-cover shadow"
                            src="{{ $psychologists[0]->user->photo_url }}" />
                        <div>
                            <div class="font-semibold text-gray-800">{{ $psychologists[0]->user->full_name }}</div>
                            <div class="text-sm text-gray-600">{{ $psychologists[0]->title }}</div>
                            <div class="text-xs text-gray-500">{{ __('appointment.fee') }}: Rp
                                {{ number_format($psychologists[0]->consultation_fee, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="font-semibold mb-4 text-lg">{{ __('appointment.step_1') }}</h2>

                    <div x-show="loadingDates" class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <p class="text-sm text-gray-500 mt-2">{{ __('appointment.loading_dates') }}</p>
                    </div>

                    <div x-show="!loadingDates">
                        <div class="mb-4">
                            <input type="date"
                                class="border border-gray-300 focus:ring-primary focus:border-primary rounded-lg px-4 py-2 w-56"
                                x-model="selectedDate" :min="getToday()" @change="fetchTimes()">
                        </div>

                        <div x-show="availableDays.length > 0" class="mt-3">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">{{ __('appointment.available_on') }}</span>
                                <span x-text="formatDaysForInfo(availableDays)"></span>
                            </p>
                        </div>

                        <div x-show="availableDates.length > 0" class="mt-3">
                            <p class="text-sm text-gray-600 mb-2">{{ __('appointment.quick_select') }}</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="date in availableDates.slice(0, 5)" :key="date">
                                    <button @click="selectedDate = date; fetchTimes()"
                                        :class="selectedDate === date ?
                                            'bg-primary text-white' :
                                            'bg-gray-100 hover:bg-gray-200 text-gray-800'"
                                        class="px-3 py-1 rounded-md text-sm transition">
                                        <span x-text="formatDate(date)"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <p x-show="availableDays.length === 0" class="text-gray-500">
                            {{ __('appointment.no_days_found') }}
                        </p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="font-semibold mb-4 text-lg">{{ __('appointment.step_2') }}</h2>

                    <div x-show="selectedDate && loadingTimes" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-10 w-10 border-b-2 border-primary mb-3"></div>
                        <p class="text-sm text-gray-500">{{ __('appointment.loading_times') }}</p>
                    </div>

                    <div x-show="!loadingTimes">
                        <div x-show="!selectedDate" class="text-center py-6">
                            <p class="text-gray-500">{{ __('appointment.no_time_slots') }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ __('appointment.select_date_first') }}</p>
                        </div>

                        <div x-show="selectedDate && availableTimes.length > 0" class="grid grid-cols-4 gap-3">
                            <template x-for="time in availableTimes" :key="time">
                                <button @click="selectedTime = time"
                                    :class="selectedTime === time ?
                                        'bg-primary text-white border-primary shadow' :
                                        'bg-white border-gray-300 hover:border-primary hover:bg-gray-50'"
                                    class="px-4 py-2 rounded-lg border text-sm transition-all" x-text="time">
                                </button>
                            </template>
                        </div>

                        <div x-show="selectedDate && availableTimes.length === 0" class="text-center py-6">
                            <p class="text-gray-500">{{ __('appointment.no_slots_for_date') }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ __('appointment.select_another_date') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="font-semibold mb-4 text-lg">{{ __('appointment.step_1') }}</h2>
                    <div class="mb-4">
                        <input type="date"
                            class="border border-gray-300 focus:ring-primary focus:border-primary rounded-lg px-4 py-2 w-56"
                            x-model="selectedDate" :min="getToday()"
                            @change="selectedTime = null; availablePsychologists = [];">
                    </div>
                    <p class="text-sm text-gray-500">{{ __('appointment.select_date_continue') }}</p>
                </div>

        <!-- TIME CARD -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <h2 class="font-semibold mb-4 text-lg">2. Choose Time</h2>
            <div class="grid grid-cols-4 gap-3">
                <template x-for="time in ['09:00','10:00','11:00', '12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00']" :key="time">
                    <button @click="selectedTime = time; fetchAvailablePsychologists()"
                            :class="selectedTime === time
                                    ? 'bg-primary text-white border-primary shadow'
                                    : 'bg-white border-gray-300 hover:border-primary hover:bg-gray-50'"
                            class="md:px-4 md:py-2 rounded-lg border text-sm transition-all"
                            x-text="time">
                    </button>
                </template>
            </div>
            <p x-show="!selectedDate" class="text-sm text-gray-500 mt-2">Please select a date first</p>
        </div>

                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <h2 class="font-semibold mb-4 text-lg">{{ __('appointment.step_3') }}</h2>

                    <div x-show="loadingPsychologists" class="text-center py-4">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        <p class="text-sm text-gray-500 mt-2">{{ __('appointment.loading_psychologists') }}</p>
                    </div>

                    <div x-show="!loadingPsychologists">
                        <div x-show="availablePsychologists.length > 0" class="grid grid-cols-3 gap-6">
                            <template x-for="psych in availablePsychologists" :key="psych.id">
                                <div class="border rounded-xl p-5 text-center cursor-pointer transition-all hover:shadow-md"
                                    @click="selectedPsychologist = psych.id"
                                    :class="selectedPsychologist == psych.id ?
                                        'bg-primary border-primary text-white shadow' :
                                        'border-gray-300 bg-white text-gray-800'">

                                    <img class="mx-auto mb-3 w-20 h-20 rounded-full object-cover shadow"
                                        :src="psych.user.photo_url" />

                                    <div x-text="psych.user.full_name"
                                        :class="selectedPsychologist == psych.id ? 'text-white' : 'text-gray-800'">
                                    </div>

                                    <div class="text-xs" x-text="psych.title"
                                        :class="selectedPsychologist == psych.id ? 'text-white/80' : 'text-gray-500'">
                                    </div>

                                    <div class="text-xs font-medium mt-2"
                                        x-text="'Rp ' + formatCurrency(psych.consultation_fee)"
                                        :class="selectedPsychologist == psych.id ? 'text-white' : 'text-primary'">
                                    </div>
                                </div>
                            </template>
                        </div>
                        <p x-show="selectedDate && selectedTime && availablePsychologists.length === 0"
                            class="text-gray-500">
                            {{ __('appointment.no_psychologists_found') }}
                        </p>
                        <p x-show="!selectedDate || !selectedTime" class="text-gray-500">
                            {{ __('appointment.select_datetime_first') }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

    <!-- POPUP OVERLAY (MOBILE ONLY) -->
<div x-show="showSummary"
     x-cloak
     @click="showSummary = false"
     class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity">
</div>

    <!-- RIGHT SIDEBAR / POPUP -->
<div :class="showSummary ? 'translate-y-0' : 'translate-y-full'"
     class="lg:translate-y-0 fixed lg:static bottom-0 right-0 lg:block w-full lg:w-80 bg-white border border-gray-200 lg:rounded-xl rounded-t-2xl lg:rounded-t-xl p-6 lg:h-fit lg:sticky lg:top-6 shadow-lg lg:shadow-sm z-50 ease-in-out transition-transform duration-300 max-h-[90vh] overflow-y-auto">

    <!-- Close Button (Mobile Only) -->
    <button @click="showSummary = false"
            class="lg:hidden absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>

    <h3 class="font-semibold text-lg mb-4">Appointment Summary</h3>
    <div class="text-sm space-y-4">
        <div class="flex justify-between">
            <span class="text-gray-500">Date</span>
            <span class="font-medium" x-text="selectedDate ? formatDate(selectedDate) : '-'"></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Time</span>
            <span class="font-medium" x-text="selectedTime ? selectedTime + ' - ' + calculateEndTime(selectedTime) : '-'"></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Psychologist</span>
            <span class="font-medium text-right">
                @if($isSpecific)
                <span>{{ $psychologists[0]->user->full_name }} - {{ $psychologists[0]->title }}</span>
                @else
                <template x-for="psych in availablePsychologists" :key="psych.id">
                    <span x-show="selectedPsychologist == psych.id" x-text="psych.user.full_name + ' - ' + psych.title"></span>
                </template>
                <span x-show="!selectedPsychologist" class="text-gray-400">Not selected</span>
                @endif
            </span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Duration</span>
            <span class="font-medium">90 minutes</span>
        </div>
        <div class="flex justify-between border-t pt-4">
            <span class="text-gray-500 font-semibold">Fee</span>
            <span class="font-bold text-lg">
                @if($isSpecific)
                <span>Rp {{ number_format($psychologists[0]->consultation_fee, 0, ',', '.') }}</span>
                @else
                <template x-for="psych in availablePsychologists" :key="psych.id">
                    <span x-show="selectedPsychologist == psych.id" x-text="'Rp ' + formatCurrency(psych.consultation_fee)"></span>
                </template>
                <span x-show="!selectedPsychologist">-</span>
                @endif
            </span>
        </div>
    </div>

    <!-- FORM -->
    <form method="POST" action="{{ route('patient.appointments.store') }}" class="mt-6" id="appointmentForm">
    @csrf
    <input type="hidden" name="date" id="hiddenDate">
    <input type="hidden" name="start_time" id="hiddenStartTime">
    <input type="hidden" name="psychologist_id" id="hiddenPsychologistId">
    <input type="hidden" name="end_time" id="hiddenEndTime">
    <input type="hidden" name="consultation_fee" id="hiddenConsultationFee">
    <input type="hidden" name="with" id="hiddenWith">
    <input type="hidden" name="job_title" id="hiddenJobTitle">

    <button type="button"
            @click="submitForm()"
            class="w-full bg-primary text-white py-3 rounded-lg font-medium transition disabled:opacity-40 hover:bg-primary/90"
            :disabled="!selectedDate || !selectedTime || !selectedPsychologist">
        Confirm & Pay
    </button>
    </form>
</div>

<!-- SIDEBAR POPUP BUTTON (MOBILE) -->
<button @click="showSummary = true"
        class="lg:hidden fixed z-30 shadow-lg rounded-xl bottom-4 right-4 bg-primary text-white px-5 py-3 font-bold hover:bg-primary/90 transition-all">
    View Summary
</button>

</div>

<!-- Tambah di CSS atau head -->
<style>
[x-cloak] { display: none !important; }
</style>
</div>

    <script>
        function appointmentFlow(translations) {
            return {
                selectedDate: null,
                selectedTime: null,
                trans: translations, // Simpan data translasi dari blade

                // FLOW 1
                psychologists: @json($psychologists->keyBy('id')),
                selectedPsychologist: @if ($isSpecific && count($psychologists) > 0)
                    {{ $psychologists[0]->id }}
                @else
                    null
                @endif ,
                availableDays: [],
                availableDates: [],
                availableTimes: [],
                loadingDates: false,
                loadingTimes: false,

                // FLOW 2
                availablePsychologists: [],
                loadingPsychologists: false,

                init() {
                    if (this.selectedPsychologist) {
                        this.fetchDates();
                    }
                },

                // Methods (Tidak ada yang perlu diubah di sini kecuali alert)
                async fetchDates() {
                    if (!this.selectedPsychologist) return;

                    this.loadingDates = true;
                    this.availableDates = [];
                    this.availableDays = [];
                    this.selectedDate = null;
                    this.availableTimes = [];
                    this.selectedTime = null;

                    try {
                        const daysResponse = await fetch(
                            `/patient/psychologist/${this.selectedPsychologist}/available-days`);
                        this.availableDays = await daysResponse.json();

                        const datesResponse = await fetch(
                            `/patient/psychologist/${this.selectedPsychologist}/available-dates`);
                        this.availableDates = await datesResponse.json();
                    } catch (err) {
                        console.error('Error fetching dates:', err);
                    } finally {
                        this.loadingDates = false;
                    }
                },

                async fetchTimes() {
                    if (!this.selectedPsychologist || !this.selectedDate) return;

                    this.loadingTimes = true;
                    this.availableTimes = [];
                    this.selectedTime = null;

                    try {
                        const response = await fetch(
                            `/patient/psychologist/${this.selectedPsychologist}/available-times?date=${this.selectedDate}`
                            );
                        this.availableTimes = await response.json();
                    } catch (err) {
                        console.error('Error fetching times:', err);
                    } finally {
                        this.loadingTimes = false;
                    }
                },

                async fetchAvailablePsychologists() {
                    if (!this.selectedDate || !this.selectedTime) return;

                    this.loadingPsychologists = true;
                    this.availablePsychologists = [];
                    this.selectedPsychologist = null;

                    try {
                        const response = await fetch(
                            `/patient/available-psychologists?date=${this.selectedDate}&time=${this.selectedTime}`);
                        this.availablePsychologists = await response.json();
                    } catch (err) {
                        console.error('Error fetching psychologists:', err);
                    } finally {
                        this.loadingPsychologists = false;
                    }
                },

                submitForm() {
                    if (!this.selectedDate || !this.selectedTime || !this.selectedPsychologist) {
                        alert(this.trans.alertComplete); // Gunakan pesan alert yang sudah ditranslate
                        return;
                    }

                    document.getElementById('hiddenDate').value = this.selectedDate;
                    document.getElementById('hiddenStartTime').value = this.selectedTime;
                    document.getElementById('hiddenPsychologistId').value = this.selectedPsychologist;
                    document.getElementById('hiddenEndTime').value = this.calculateEndTime(this.selectedTime);
                    document.getElementById('hiddenConsultationFee').value = this.getConsultationFee(this
                        .selectedPsychologist);
                    document.getElementById('hiddenWith').value = this.getPsychologistName(this.selectedPsychologist);
                    document.getElementById('hiddenJobTitle').value = this.getPsychologistTitle(this.selectedPsychologist);

                    const form = document.getElementById('appointmentForm');
                    const formData = new FormData(form);
                    form.submit();
                },

                getPsychologistFee(psychologistId) {
                    const psych = this.availablePsychologists.find(p => p.id == psychologistId);
                    return psych ? psych.consultation_fee : 0;
                },

                getPsychologistName(psychologistId) {
                    if (this.psychologists[psychologistId]) {
                        return this.psychologists[psychologistId].user.full_name;
                    }

                    const psych = this.availablePsychologists.find(p => p.id == psychologistId);
                    return psych ? psych.user.full_name : '';
                },

                getPsychologistTitle(psychologistId) {
                    if (this.psychologists[psychologistId]) {
                        return this.psychologists[psychologistId].title;
                    }

                    const psych = this.availablePsychologists.find(p => p.id == psychologistId);
                    return psych ? psych.title : '';
                },

                getConsultationFee(psychologistId) {
                    if (this.psychologists[psychologistId]) {
                        return this.psychologists[psychologistId].consultation_fee || 0;
                    }

                    return this.getPsychologistFee(psychologistId);
                },

                calculateEndTime(startTime) {
                    if (!startTime) return '';
                    const [hours, minutes] = startTime.split(':');
                    const totalMinutes = parseInt(hours) * 60 + parseInt(minutes) + 90;
                    const endHour = Math.floor(totalMinutes / 60);
                    const endMinute = totalMinutes % 60;
                    return `${endHour.toString().padStart(2, '0')}:${endMinute.toString().padStart(2, '0')}`;
                },

                formatDate(dateStr) {
                    const date = new Date(dateStr);
                    // Pakai locale dari PHP agar format tanggal sesuai bahasa
                    const locale = '{{ app()->getLocale() == 'id' ? 'id-ID' : 'en-US' }}';
                    return date.toLocaleDateString(locale, {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric'
                    });
                },

                formatDateShort(dateStr) {
                    const date = new Date(dateStr);
                    const locale = '{{ app()->getLocale() == 'id' ? 'id-ID' : 'en-US' }}';
                    return date.toLocaleDateString(locale, {
                        month: 'short',
                        day: 'numeric'
                    });
                },

                formatCurrency(amount) {
                    return new Intl.NumberFormat('id-ID').format(amount);
                },

                getToday() {
                    return new Date().toISOString().split('T')[0];
                },

                formatDaysForInfo(days) {
                    // Mapping hari manual jika perlu, atau biarkan JS handle jika API sudah kirim format benar
                    return days.map(day =>
                        day.charAt(0).toUpperCase() + day.slice(1)
                    ).join(', ');
                }
            }
        }
    </script>
@endsection
