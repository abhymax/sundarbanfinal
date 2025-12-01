document.addEventListener('DOMContentLoaded', function () {

    // --- 1. Booking Form Submission Logic ---
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Stop page reload

            const form = this;
            const btn = document.getElementById('submit-btn');
            const statusBox = document.getElementById('form-status');
            const originalBtnText = btn.innerHTML;

            // Show Loading State
            btn.innerHTML = '<span class="material-symbols-outlined animate-spin">refresh</span> Sending...';
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');

            // Collect Data
            const formData = new FormData(form);

            // Send to Backend
            fetch('submit_booking.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    // Handle Response
                    statusBox.classList.remove('hidden', 'text-green-600', 'text-red-600', 'bg-green-100', 'bg-red-100');
                    statusBox.style.display = 'block';

                    if (data.status === 'success') {
                        statusBox.classList.add('text-green-800', 'bg-green-100');
                        statusBox.innerHTML = `<span class="material-symbols-outlined align-middle mr-1">check_circle</span> ${data.message}`;
                        form.reset(); // Clear inputs

                        // Close modal after 3 seconds
                        setTimeout(() => {
                            if (typeof closeBooking === 'function') {
                                closeBooking();
                            }
                            statusBox.style.display = 'none';
                        }, 3000);

                    } else {
                        throw new Error(data.message || 'Submission failed');
                    }
                })
                .catch(error => {
                    statusBox.classList.add('text-red-800', 'bg-red-100');
                    statusBox.innerHTML = `<span class="material-symbols-outlined align-middle mr-1">error</span> ${error.message}`;
                })
                .finally(() => {
                    // Reset Button
                    btn.innerHTML = originalBtnText;
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-not-allowed');
                });
        });
    }

    // --- 2. "Check Availability" Button Logic ---
    const heroCheckBtn = document.getElementById('hero-check-btn');

    if (heroCheckBtn) {
        heroCheckBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Get values from header inputs
            const dateInput = document.getElementById('hero-date');
            const guestsInput = document.getElementById('hero-guests');
            const packageInput = document.getElementById('hero-package');

            const date = dateInput ? dateInput.value : '';
            const guests = guestsInput ? guestsInput.value : '';
            const packageVal = packageInput ? packageInput.value : '';

            // Open Modal
            if (typeof openBooking === 'function') {
                openBooking();
            } else {
                console.error('openBooking function not found');
            }

            // Populate Modal Inputs
            const modalDate = document.querySelector('#booking-modal input[name="travel_date"]');
            const modalAdults = document.querySelector('#booking-modal input[name="adults"]');
            const modalPackage = document.querySelector('#booking-modal select[name="package"]');

            if (modalDate && date) modalDate.value = date;

            if (modalAdults && guests) {
                // Extract number from "2 Travelers" etc.
                const guestsNum = parseInt(guests);
                if (!isNaN(guestsNum)) {
                    modalAdults.value = guestsNum;
                }
            }

            if (modalPackage && packageVal && packageVal !== 'All Packages') {
                let targetValue = packageVal;
                if (packageVal === '1 Day') targetValue = '1 Day Tour';

                // Try to set the value
                const options = Array.from(modalPackage.options);
                const matchingOption = options.find(opt => opt.value === targetValue || opt.text === targetValue);
                if (matchingOption) {
                    modalPackage.value = matchingOption.value;
                }
            }
        });
    }

    // --- 3. Date Input Click Logic ---
    const heroDateInput = document.getElementById('hero-date');
    if (heroDateInput) {
        heroDateInput.addEventListener('click', function () {
            if (typeof this.showPicker === 'function') {
                this.showPicker();
            } else {
                this.focus(); // Fallback for browsers that don't support showPicker
            }
        });
    }

});