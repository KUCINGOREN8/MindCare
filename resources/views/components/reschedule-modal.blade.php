<div id="rescheduleModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
    <div class="bg-white rounded-md p-6 w-full max-w-md">
        {{-- TRANSLATE TITLE --}}
        <h3 class="font-bold mb-4">{{ __('reschedule.modal_title') }}</h3>

        <form id="rescheduleForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="text-sm">New Date</label>
                <select name="reschedule_date" id="reschedule_date_select" 
                        class="w-full border rounded px-3 py-2" required 
                        onchange="updateTimeSlots(this.value)">
                    <option value="">Select New Date</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="text-sm">New Time</label>
                <select name="reschedule_time" id="reschedule_time_select" 
                        class="w-full border rounded px-3 py-2" required>
                    <option value="">Select New Time</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                {{-- TRANSLATE BUTTONS --}}
                <button type="button" onclick="closeRescheduleModal()"
                    class="px-4 py-2 border rounded hover:bg-gray-50 transition">
                    {{ __('reschedule.cancel') }}
                </button>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded hover:bg-teal-600 transition">
                    {{ __('reschedule.submit') }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Javascript tetap sama, tidak ada yang perlu ditranslate di sini
    function openRescheduleModal(id, date, time) {
        const modal = document.getElementById('rescheduleModal');
        const form = document.getElementById('rescheduleForm');

        form.action = `/patient/appointments/${id}/reschedule`;

        form.querySelector('input[name="reschedule_date"]').value = date;
        form.querySelector('input[name="reschedule_time"]').value = time;

        modal.classList.remove('hidden');
    }

    function closeRescheduleModal() {
        document.getElementById('rescheduleModal').classList.add('hidden');
    }
</script>
