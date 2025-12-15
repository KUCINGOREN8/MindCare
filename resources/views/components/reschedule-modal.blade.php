<div id="rescheduleModal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
    <div class="bg-white rounded-md p-6 w-full max-w-md">
        <h3 class="font-bold mb-4">Reschedule Appointment</h3>

        <form id="rescheduleForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="text-sm">New Date</label>
                <input type="date" name="reschedule_date" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="text-sm">New Time</label>
                <input type="time" name="reschedule_time" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeRescheduleModal()" class="px-4 py-2 border rounded">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-teal-500 text-white rounded">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
