document.addEventListener('DOMContentLoaded', function() {
    let experienceCount = 1;
    const container = document.getElementById('experienceContainer');
    const addBtn = document.getElementById('addExperienceBtn');

    if (!container || !addBtn) return;

    addBtn.addEventListener('click', function() {
        const newEntry = document.createElement('div');
        newEntry.className = 'experience-entry bg-gray-50 p-6 rounded-lg mb-4 border border-gray-200 relative';
        newEntry.innerHTML = `
            <button type="button" class="remove-experience-btn absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-md hover:bg-red-600 z-10 transition-colors">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-2">Position <span class="text-red-500">*</span></label>
                    <input type="text" name="experiences[${experienceCount}][position]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="Head Psychologist">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Organization <span class="text-red-500">*</span></label>
                    <input type="text" name="experiences[${experienceCount}][organization]" required class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="BNCC Hospital">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Start Year <span class="text-red-500">*</span></label>
                    <input type="text" name="experiences[${experienceCount}][start_year]" required maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="2023">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">End Year</label>
                    <input type="text" name="experiences[${experienceCount}][end_year]" maxlength="4" class="w-full outline-none text-black bg-white px-4 py-3 rounded-lg border border-gray-300" placeholder="2025">
                    <p class="text-sm text-gray-500 mt-1">Leave blank if currently working here</p>
                </div>
            </div>
        `;

        container.appendChild(newEntry);
        experienceCount++;

        if (experienceCount === 2) {
            const firstRemoveBtn = container.querySelector('.experience-entry:first-child .remove-experience-btn');
            if (firstRemoveBtn) {
                firstRemoveBtn.style.display = 'flex';
            }
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-experience-btn')) {
            const entry = e.target.closest('.experience-entry');
            entry.remove();
            experienceCount--;

            if (experienceCount === 1) {
                const firstRemoveBtn = container.querySelector('.experience-entry:first-child .remove-experience-btn');
                if (firstRemoveBtn) {
                    firstRemoveBtn.style.display = 'none';
                }
            }
        }
    });
});
