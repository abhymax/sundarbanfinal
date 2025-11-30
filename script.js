// Navbar Scroll Effect
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

// Modal Functions
function openBooking() {
    const modal = document.getElementById('booking-modal');
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100', 'visible');
}

function closeBooking() {
    const modal = document.getElementById('booking-modal');
    modal.classList.remove('opacity-100', 'visible');
    modal.classList.add('opacity-0', 'invisible');
}

// FAQ Toggle
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

// GSAP Animations - FIXED
// Using window.load to ensure images are ready before calculating positions
window.addEventListener('load', () => {
    gsap.registerPlugin(ScrollTrigger);

    // Refresh ScrollTrigger to ensure positions are correct after image load
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

    // Reveal Packages - Fixed opacity issue
    gsap.from(".package-card", {
        scrollTrigger: {
            trigger: "#packages",
            start: "top 85%" // Trigger slightly earlier
        },
        y: 50,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power2.out",
        clearProps: "all" // Ensures styles are removed after animation prevents stuck states
    });

    // Reveal Testimonials
    gsap.from("#testimonials", {
        scrollTrigger: { trigger: "#testimonials", start: "top 85%" },
        y: 50,

    });

    // --- Logic for 1-Day Tour Page ---

    // Google Map Initialization
    async function initMap() {
        // Check if the map container exists before running logic
        const mapElement = document.getElementById("map");
        if (!mapElement) return;

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
        const roadPathCoordinates = [
            esplanade,
            scienceCity,
            { lat: 22.45, lng: 88.50 }, // Waypoint
            { lat: 22.30, lng: 88.65 }, // Waypoint
            godkhali
        ];

        const boatPathCoordinates = [
            godkhali,
            { lat: 22.14, lng: 88.81 }, // River bend
            sajnekhali,
            sudhanyakhali
        ];

        const roadPath = new Polyline({
            path: roadPathCoordinates,
            geodesic: true,
            strokeColor: "#FF5722",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });

        const boatPath = new Polyline({
            path: boatPathCoordinates,
            geodesic: true,
            strokeColor: "#0288D1",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });
    }
});



// Modal Functions
function openBooking() {
    const modal = document.getElementById('booking-modal');
    modal.classList.remove('opacity-0', 'invisible');
    modal.classList.add('opacity-100', 'visible');
}

function closeBooking() {
    const modal = document.getElementById('booking-modal');
    modal.classList.remove('opacity-100', 'visible');
    modal.classList.add('opacity-0', 'invisible');
}

// FAQ Toggle
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

// GSAP Animations - FIXED
// Using window.load to ensure images are ready before calculating positions
window.addEventListener('load', () => {
    gsap.registerPlugin(ScrollTrigger);

    // Refresh ScrollTrigger to ensure positions are correct after image load
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

    // Reveal Packages - Fixed opacity issue
    gsap.from(".package-card", {
        scrollTrigger: {
            trigger: "#packages",
            start: "top 85%" // Trigger slightly earlier
        },
        y: 50,
        opacity: 0,
        duration: 0.8,
        stagger: 0.2,
        ease: "power2.out",
        clearProps: "all" // Ensures styles are removed after animation prevents stuck states
    });

    // Reveal Testimonials
    gsap.from("#testimonials", {
        scrollTrigger: { trigger: "#testimonials", start: "top 85%" },
        y: 50,

    });

    // --- Logic for 1-Day Tour Page ---

    // Google Map Initialization
    async function initMap() {
        // Check if the map container exists before running logic
        const mapElement = document.getElementById("map");
        if (!mapElement) return;

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
        const roadPathCoordinates = [
            esplanade,
            scienceCity,
            { lat: 22.45, lng: 88.50 }, // Waypoint
            { lat: 22.30, lng: 88.65 }, // Waypoint
            godkhali
        ];

        const boatPathCoordinates = [
            godkhali,
            { lat: 22.14, lng: 88.81 }, // River bend
            sajnekhali,
            sudhanyakhali
        ];

        const roadPath = new Polyline({
            path: roadPathCoordinates,
            geodesic: true,
            strokeColor: "#FF5722",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });

        const boatPath = new Polyline({
            path: boatPathCoordinates,
            geodesic: true,
            strokeColor: "#0288D1",
            strokeOpacity: 0.8,
            strokeWeight: 4,
            map: map
        });
    }

    // Initialize Map
    initMap();

    // GSAP Animations for Timeline
    gsap.registerPlugin(ScrollTrigger);

    // Animate Timeline Items
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
});
