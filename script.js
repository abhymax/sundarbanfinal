// --- 1. Navbar Scroll Effect ---
const navbar = document.getElementById('navbar');
const logoText = document.getElementById('nav-logo-text');
const subText = document.getElementById('nav-sub-text');
const navLinks = document.querySelectorAll('.nav-link');

window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('bg-safari-green', 'shadow-md', 'top-0');
        navbar.classList.remove('bg-white/10', 'backdrop-blur-md', 'border-white/10', 'bg-white', 'md:top-[40px]');

        // Logo Text stays white or becomes white
        logoText.classList.add('text-white');
        logoText.classList.remove('text-safari-green');

        subText.classList.remove('text-gray-200');
        subText.classList.add('text-gray-300');

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

        logoText.classList.add('text-white');
        logoText.classList.remove('text-safari-green');

        subText.classList.add('text-gray-200');
        subText.classList.remove('text-gray-300');

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

// --- 2. Global Modal Functions (New Logic) ---
// These are attached to 'window' so they can be called from HTML onclick="" events
window.openBooking = function() {
    const modal = document.getElementById('bookingModal');
    const sidebar = document.getElementById('bookingSidebar');
    
    if(modal && sidebar) {
        modal.classList.remove('hidden');
        // Small delay ensures display:block applies before the slide-in animation starts
        setTimeout(() => {
            sidebar.classList.remove('translate-x-full');
        }, 10);
    }
};

window.closeBooking = function() {
    const modal = document.getElementById('bookingModal');
    const sidebar = document.getElementById('bookingSidebar');
    
    if(modal && sidebar) {
        sidebar.classList.add('translate-x-full');
        // Wait for the slide-out animation (300ms) before hiding the element
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
};

// --- 3. FAQ Toggle ---
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

// --- 4. Main Page Initialization (Map & GSAP) ---
window.addEventListener('load', () => {
    // --- GSAP Animations ---
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
        ScrollTrigger.refresh();

        // Hero Parallax
        gsap.to("header h1", {
            scrollTrigger: {
                trigger: "header",
                start: "top top",
                scrub: true
            },
            y: 50,
            opacity: 0.8
        });

        // Reveal Packages
        gsap.from(".package-card", {
            scrollTrigger: {
                trigger: "#packages",
                start: "top 85%"
            },
            y: 50,
            opacity: 0,
            duration: 0.8,
            stagger: 0.2,
            ease: "power2.out",
            clearProps: "all"
        });

        // Reveal Testimonials
        gsap.from("#testimonials", {
            scrollTrigger: { trigger: "#testimonials", start: "top 85%" },
            y: 50,
        });

        // Animate Timeline Items (for Itinerary)
        const items = document.querySelectorAll('.timeline-item');
        if (items.length > 0) {
            gsap.utils.toArray('.timeline-item').forEach((item, i) => {
                gsap.from(item, {
                    scrollTrigger: {
                        trigger: item,
                        start: "top 80%",
                    },
                    opacity: 0,
                    y: 50,
                    duration: 0.8,
                    ease: "power2.out"
                });
            });
        }
    }

    // --- Google Map Initialization ---
    async function initMap() {
        const mapElement = document.getElementById("map");
        if (!mapElement) return;

        // Ensure Google Maps is loaded
        if (typeof google === 'undefined' || !google.maps) return;

        const { Map, InfoWindow } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
        const { Polyline } = await google.maps.importLibrary("maps");

        const map = new Map(mapElement, {
            center: { lat: 22.35, lng: 88.60 }, // Centered between Kolkata and Sundarbans
            zoom: 9,
            mapId: 'SUNDARBAN_MAP_ID', // Required for Advanced Markers
            gestureHandling: 'greedy',
            disableDefaultUI: true,
            zoomControl: true
        });

        // Coordinates
        const esplanade = { lat: 22.565, lng: 88.352 };
        const scienceCity = { lat: 22.544, lng: 88.399 };
        const godkhali = { lat: 22.166, lng: 88.797 };
        const sajnekhali = { lat: 22.120, lng: 88.830 };
        const sudhanyakhali = { lat: 22.100, lng: 88.800 };

        // Markers Data
        const locations = [
            { pos: esplanade, title: "Pickup: Esplanade", type: "pickup" },
            { pos: scienceCity, title: "Pickup: Science City", type: "pickup" },
            { pos: godkhali, title: "Godkhali Ferry Ghat (Boat Start)", type: "boat" },
            { pos: sajnekhali, title: "Sajnekhali Watch Tower", type: "wildlife" },
            { pos: sudhanyakhali, title: "Sudhanyakhali Watch Tower", type: "wildlife" }
        ];

        const infoWindow = new InfoWindow();

        locations.forEach(loc => {
            let bgColor = "#EA4335"; // Red for pickup
            let glyphIcon = "directions_car";

            if (loc.type === "boat") {
                bgColor = "#4285F4"; // Blue for boat
                glyphIcon = "sailing";
            } else if (loc.type === "wildlife") {
                bgColor = "#34A853"; // Green for nature
                glyphIcon = "visibility";
            }

            const glyph = document.createElement("span");
            glyph.className = "material-symbols-outlined";
            glyph.textContent = glyphIcon;
            glyph.style.color = "white";
            glyph.style.fontSize = "16px";

            const pin = new PinElement({
                glyph: glyph,
                background: bgColor,
                borderColor: "white",
                scale: 1.2
            });

            const marker = new AdvancedMarkerElement({
                map,
                position: loc.pos,
                content: pin.element,
                title: loc.title
            });

            marker.addListener('click', () => {
                const header = document.createElement('div');
                header.innerHTML = `<strong style="font-size:16px">${loc.title}</strong>`;
                const content = document.createElement('div');
                content.innerHTML = `<p style="color:#555; margin-top:4px;">${loc.type === 'wildlife' ? 'Key spot for wildlife viewing.' : 'Transit point.'}</p>`;
                infoWindow.setHeaderContent(header);
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        });

        // Draw Routes
        const roadPath = new Polyline({
            path: [esplanade, scienceCity, { lat: 22.45, lng: 88.50 }, { lat: 22.30, lng: 88.65 }, godkhali],
            geodesic: true,
            strokeColor: "#FF5722",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });

        const boatPath = new Polyline({
            path: [godkhali, { lat: 22.14, lng: 88.81 }, sajnekhali, sudhanyakhali],
            geodesic: true,
            strokeColor: "#0288D1",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });
    }

    initMap();
});

// --- 5. Booking Logic (Connect Hero & Form) ---
document.addEventListener('DOMContentLoaded', function () {
    
    // Connect Hero "Check Availability" Button to Modal
    const heroBtn = document.getElementById('hero-check-btn');
    if(heroBtn) {
        heroBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get values from Hero Bar inputs
            const hDate = document.getElementById('hero-date')?.value;
            const hGuests = document.getElementById('hero-guests')?.value; 
            const hPackage = document.getElementById('hero-package')?.value;

            // Inject them into the Popup Modal fields
            if(hDate) {
                const dateInput = document.getElementById('modal-date');
                if(dateInput) dateInput.value = hDate;
            }
            if(hGuests) {
                const guestsInput = document.getElementById('modal-travelers');
                if(guestsInput) guestsInput.value = hGuests;
            }
            if(hPackage && hPackage !== 'All Packages') {
                const pkgInput = document.getElementById('modal-package');
                if(pkgInput) pkgInput.value = hPackage;
            }

            window.openBooking();
        });
    }

    // Handle Form Submission (AJAX)
    const form = document.getElementById('booking-form');
    if(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submit-btn');
            const statusBox = document.getElementById('form-status');
            const originalText = btn.innerHTML;

            // Loading State
            btn.disabled = true;
            btn.innerHTML = 'Sending...';
            btn.classList.add('opacity-75');

            const formData = new FormData(form);

            fetch('submit_booking.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                statusBox.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
                statusBox.style.display = 'block';

                if(data.status === 'success') {
                    statusBox.classList.add('bg-green-100', 'text-green-700');
                    statusBox.innerHTML = '✓ ' + data.message;
                    form.reset();
                    setTimeout(() => { window.closeBooking(); statusBox.style.display = 'none'; }, 3000);
                } else {
                    statusBox.classList.add('bg-red-100', 'text-red-700');
                    statusBox.innerHTML = '⚠️ ' + data.message;
                }
            })
            .catch(err => {
                statusBox.classList.add('bg-red-100', 'text-red-700');
                statusBox.style.display = 'block';
                statusBox.innerHTML = '⚠️ Network error. Please try again.';
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                btn.classList.remove('opacity-75');
            });
        });
    }
});