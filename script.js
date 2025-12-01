// --- 1. Navbar Scroll Effect ---
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

// --- 2. Modal Functions (Global & Robust) ---
window.openBooking = function() {
    // Try finding the modal by both possible IDs (New vs Old format)
    const modal = document.getElementById('bookingModal') || document.getElementById('booking-modal');
    const sidebar = document.getElementById('bookingSidebar');
    
    if(modal) {
        // Ensure we remove ALL possible hiding classes
        modal.classList.remove('hidden', 'opacity-0', 'invisible');
        // Add visibility classes
        modal.classList.add('opacity-100', 'visible');
        
        // If sidebar exists, animate it in
        if(sidebar) {
            setTimeout(() => {
                sidebar.classList.remove('translate-x-full');
            }, 10);
        }
    } else {
        console.error("Error: Modal not found. Checked for IDs: 'bookingModal' and 'booking-modal'");
    }
};

window.closeBooking = function() {
    const modal = document.getElementById('bookingModal') || document.getElementById('booking-modal');
    const sidebar = document.getElementById('bookingSidebar');
    
    if(modal) {
        if(sidebar) {
            sidebar.classList.add('translate-x-full');
            // Wait for the slide-out animation (300ms) before hiding the element
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('opacity-100', 'visible');
                modal.classList.add('opacity-0', 'invisible');
            }, 300);
        } else {
            // No sidebar? Hide immediately
            modal.classList.add('hidden');
        }
    }
};

// --- 3. FAQ & Init Logic ---
function toggleFaq(element) {
    const item = element.parentElement;
    const wasActive = item.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));
    if (!wasActive) item.classList.add('active');
}

window.addEventListener('load', () => {
    // GSAP
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
        ScrollTrigger.refresh();
        gsap.to("header h1", { scrollTrigger: { trigger: "header", start: "top top", scrub: true }, y: 50, opacity: 0.8 });
        gsap.from(".package-card", { scrollTrigger: { trigger: "#packages", start: "top 85%" }, y: 50, opacity: 0, duration: 0.8, stagger: 0.2, ease: "power2.out", clearProps: "all" });
        gsap.from("#testimonials", { scrollTrigger: { trigger: "#testimonials", start: "top 85%" }, y: 50 });
    }

    // Google Map (Simplified for safety)
    async function initMap() {
        const mapElement = document.getElementById("map");
        if (!mapElement || typeof google === 'undefined') return;
        
        // Safety check for maps library
        try {
            const { Map } = await google.maps.importLibrary("maps");
            const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
            const { Polyline } = await google.maps.importLibrary("maps");

            const map = new Map(mapElement, { 
                center: { lat: 22.35, lng: 88.60 }, 
                zoom: 9, 
                mapId: 'SUNDARBAN_MAP_ID', 
                disableDefaultUI: true 
            });
            
            // Route drawing logic (kept brief to focus on modal fix)
            const roadPath = new Polyline({
                path: [{ lat: 22.565, lng: 88.352 }, { lat: 22.166, lng: 88.797 }],
                geodesic: true, strokeColor: "#FF5722", strokeOpacity: 0.8, strokeWeight: 4, map: map
            });
        } catch (e) {
            console.log("Map init skipped or failed: ", e);
        }
    }
    initMap();
});

// --- 4. Connect Hero Button & Submit Logic ---
document.addEventListener('DOMContentLoaded', function () {
    
    // Connect Hero Button to Modal
    const heroBtn = document.getElementById('hero-check-btn');
    if(heroBtn) {
        heroBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Transfer Data
            const hDate = document.getElementById('hero-date')?.value;
            const hGuests = document.getElementById('hero-guests')?.value;
            const hPackage = document.getElementById('hero-package')?.value;

            const mDate = document.getElementById('modal-date');
            const mTravelers = document.getElementById('modal-travelers');
            const mPackage = document.getElementById('modal-package');

            if(hDate && mDate) mDate.value = hDate;
            if(hGuests && mTravelers) mTravelers.value = hGuests;
            if(hPackage && hPackage !== 'All Packages' && mPackage) mPackage.value = hPackage;

            window.openBooking();
        });
    }

    // Form Submission with Captcha
    // Check for both ID variations just in case
    const form = document.getElementById('booking-form') || document.getElementById('bookingForm');
    
    if(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const btn = document.getElementById('submit-btn');
            const statusBox = document.getElementById('form-status');
            const originalText = btn ? btn.innerHTML : 'Send';

            if(btn) {
                btn.disabled = true;
                btn.innerHTML = 'Sending...';
                btn.classList.add('opacity-75');
            }

            const formData = new FormData(form);

            fetch('submit_booking.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(statusBox) {
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
                } else {
                    alert(data.message);
                }
            })
            .catch(err => {
                if(statusBox) {
                    statusBox.classList.add('bg-red-100', 'text-red-700');
                    statusBox.style.display = 'block';
                    statusBox.innerHTML = '⚠️ Network error. Please check connection.';
                }
            })
            .finally(() => {
                if(btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    btn.classList.remove('opacity-75');
                }
            });
        });
    }
});