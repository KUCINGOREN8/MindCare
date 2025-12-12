document.addEventListener('DOMContentLoaded', function() {
    let educationCount = 1;
    const container = document.getElementById('educationContainer');
    const addBtn = document.getElementById('addEducationBtn');

    if (!container || !addBtn) return;

    addBtn.addEventListener('click', function() {
        const newEntry = document.createElement('div');
        newEntry.className = 'education-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200 relative';
        newEntry.innerHTML = `
            <button type="button" class="remove-education-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Degree <span class="text-red-500">*</span></label>
                    <input type="text" name="educations[${educationCount}][degree]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="Doctor of Psychology">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                    <input type="text" name="educations[${educationCount}][institution]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="BINUS University">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Year <span class="text-red-500">*</span></label>
                    <input type="text" name="educations[${educationCount}][year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="2022">
                </div>
            </div>
        `;

        container.appendChild(newEntry);
        educationCount++;

        if (educationCount === 2) {
            const firstRemoveBtn = container.querySelector('.education-entry:first-child .remove-education-btn');
            if (firstRemoveBtn) {
                firstRemoveBtn.style.display = 'flex';
            }
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-education-btn')) {
            const entry = e.target.closest('.education-entry');
            entry.remove();
            educationCount--;

            if (educationCount === 1) {
                const firstRemoveBtn = container.querySelector('.education-entry:first-child .remove-education-btn');
                if (firstRemoveBtn) {
                    firstRemoveBtn.style.display = 'none';
                }
            }
        }
    });
});
