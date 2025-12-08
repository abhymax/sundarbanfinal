// ==========================================
// 1. NAVBAR SCROLL EFFECT
// ==========================================
const navbar = document.getElementById('navbar');
const logoText = document.getElementById('nav-logo-text');
const subText = document.getElementById('nav-sub-text');
const navLinks = document.querySelectorAll('.nav-link');

window.addEventListener('scroll', () => {
    if (!navbar) return;

    if (window.scrollY > 50) {
        navbar.classList.add('bg-safari-green', 'shadow-md', 'top-0');
        navbar.classList.remove('bg-white/10', 'backdrop-blur-md', 'border-white/10', 'bg-white', 'md:top-[40px]');

        if (logoText) {
            logoText.classList.add('text-white');
            logoText.classList.remove('text-safari-green');
        }
        if (subText) {
            subText.classList.remove('text-gray-200');
            subText.classList.add('text-gray-300');
        }

        navLinks.forEach(link => {
            link.classList.add('text-white');
            link.classList.remove('text-gray-700', 'text-safari-green');
        });

        const mobileBtn = document.querySelector('nav .md\\:hidden button');
        if (mobileBtn) {
            mobileBtn.classList.add('text-white');
            mobileBtn.classList.remove('text-gray-700', 'text-safari-green');
        }

    } else {
        navbar.classList.remove('bg-safari-green', 'shadow-md', 'top-0');
        navbar.classList.add('bg-white/10', 'backdrop-blur-md', 'border-white/10', 'md:top-[40px]');

        if (logoText) {
            logoText.classList.add('text-white');
            logoText.classList.remove('text-safari-green');
        }
        if (subText) {
            subText.classList.add('text-gray-200');
            subText.classList.remove('text-gray-300');
        }

        navLinks.forEach(link => {
            link.classList.add('text-white');
            link.classList.remove('text-gray-700');
        });

        const mobileBtn = document.querySelector('nav .md\\:hidden button');
        if (mobileBtn) {
            mobileBtn.classList.add('text-white');
            mobileBtn.classList.remove('text-gray-700');
        }
    }
});

// ==========================================
// 2. FAQ TOGGLE FUNCTION
// ==========================================
function toggleFaq(element) {
    const item = element.parentElement;
    const wasActive = item.classList.contains('active');

    document.querySelectorAll('.faq-item').forEach(i => {
        i.classList.remove('active');
    });

    if (!wasActive) {
        item.classList.add('active');
    }
}

// ==========================================
// 3. PAGE INITIALIZATION (Map & GSAP)
// ==========================================
window.addEventListener('load', () => {
    // GSAP Animations
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
        ScrollTrigger.refresh();

        if (document.querySelector("header h1")) {
            gsap.to("header h1", {
                scrollTrigger: { trigger: "header", start: "top top", scrub: true },
                y: 50, opacity: 0.8
            });
        }

        const cards = document.querySelectorAll(".package-card");
        if (cards.length > 0) {
            gsap.from(cards, {
                scrollTrigger: { trigger: "#packages", start: "top 85%" },
                y: 50, opacity: 0, duration: 0.8, stagger: 0.2, ease: "power2.out", clearProps: "all"
            });
        }

        if (document.getElementById("testimonials")) {
            gsap.from("#testimonials", {
                scrollTrigger: { trigger: "#testimonials", start: "top 85%" },
                y: 50
            });
        }
    }

    // Google Map Logic
    async function initMap() {
        const mapElement = document.getElementById("map");
        if (!mapElement) return;
        if (typeof google === 'undefined') return;

        try {
            const { Map, InfoWindow } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
            const { Polyline } = await google.maps.importLibrary("maps");

            const map = new Map(mapElement, {
                center: { lat: 22.35, lng: 88.60 },
                zoom: 9,
                mapId: '7409f494961b2d4e9e4bff1f',
                gestureHandling: 'greedy',
                disableDefaultUI: true,
                zoomControl: true
            });

            const locations = [
                { pos: { lat: 22.565, lng: 88.352 }, title: "Pickup: Esplanade", type: "pickup" },
                { pos: { lat: 22.544, lng: 88.399 }, title: "Pickup: Science City", type: "pickup" },
                { pos: { lat: 22.166, lng: 88.797 }, title: "Godkhali Ferry Ghat", type: "boat" },
                { pos: { lat: 22.120, lng: 88.830 }, title: "Sajnekhali Watch Tower", type: "wildlife" },
                { pos: { lat: 22.100, lng: 88.800 }, title: "Sudhanyakhali Watch Tower", type: "wildlife" }
            ];

            const infoWindow = new InfoWindow();

            locations.forEach(loc => {
                let bgColor = loc.type === "boat" ? "#4285F4" : loc.type === "wildlife" ? "#34A853" : "#EA4335";
                let glyphIcon = loc.type === "boat" ? "sailing" : loc.type === "wildlife" ? "visibility" : "directions_car";

                const glyph = document.createElement("span");
                glyph.className = "material-symbols-outlined";
                glyph.textContent = glyphIcon;
                glyph.style.color = "white";
                glyph.style.fontSize = "16px";

                const pin = new PinElement({ glyph: glyph, background: bgColor, borderColor: "white", scale: 1.2 });
                const marker = new AdvancedMarkerElement({ map, position: loc.pos, content: pin.element, title: loc.title });

                marker.addListener('click', () => {
                    infoWindow.setContent(`<div style="padding:5px"><strong>${loc.title}</strong></div>`);
                    infoWindow.open(map, marker);
                });
            });
            
             // Draw Routes
            const roadPath = new Polyline({
                path: [
                    { lat: 22.565, lng: 88.352 }, // Esplanade
                    { lat: 22.544, lng: 88.399 }, // Science City
                    { lat: 22.45, lng: 88.50 }, 
                    { lat: 22.30, lng: 88.65 }, 
                    { lat: 22.166, lng: 88.797 }  // Godkhali
                ],
                geodesic: true,
                strokeColor: "#FF5722",
                strokeOpacity: 0.8,
                strokeWeight: 4,
                map: map
            });

            const boatPath = new Polyline({
                path: [
                    { lat: 22.166, lng: 88.797 }, // Godkhali
                    { lat: 22.14, lng: 88.81 }, 
                    { lat: 22.120, lng: 88.830 }, // Sajnekhali
                    { lat: 22.100, lng: 88.800 }  // Sudhanyakhali
                ],
                geodesic: true,
                strokeColor: "#0288D1",
                strokeOpacity: 0.8,
                strokeWeight: 4,
                map: map
            });

        } catch (e) {
            console.warn("Map init failed or library missing:", e);
        }
    }
    initMap();
});

// ==========================================
// 4. BOOKING LOGIC (Connects Index.php Button to Footer Modal)
// ==========================================
document.addEventListener('DOMContentLoaded', function () {

    // --- A. Booking Form Submission Logic ---
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

    // --- B. "Check Availability" Button Logic (INDEX.PHP) ---
    // This connects the button in the Hero section to the Modal in the Footer
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
            const modalAdults = document.querySelector('#booking-modal select[name="adults"]'); 
            const modalPackage = document.querySelector('#booking-modal select[name="package"]');

            if (modalDate && date) modalDate.value = date;

            if (modalAdults && guests) {
                // Try to match the select option
                const options = Array.from(modalAdults.options);
                const matchingOption = options.find(opt => opt.value === guests);
                if (matchingOption) {
                    modalAdults.value = guests;
                }
            }

            if (modalPackage && packageVal && packageVal !== 'All Packages') {
                let targetValue = packageVal;
                if (packageVal === '1 Day') targetValue = '1 Day Tour';

                const options = Array.from(modalPackage.options);
                const matchingOption = options.find(opt => opt.value === targetValue || opt.text === targetValue);
                if (matchingOption) {
                    modalPackage.value = matchingOption.value;
                }
            }
        });
    }

    // --- C. Date Input Click Logic ---
    const heroDateInput = document.getElementById('hero-date');
    if (heroDateInput) {
        heroDateInput.addEventListener('click', function () {
            if (typeof this.showPicker === 'function') {
                this.showPicker();
            } else {
                this.focus(); 
            }
        });
    }
});