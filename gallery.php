<?php include 'header.php'; ?>

<header class="relative h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=2574&auto=format&fit=crop"
            class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
    <div class="relative z-10 text-center text-white px-4">
        <h1 class="text-5xl md:text-7xl font-bold font-serif mb-4">Through the Lens</h1>
        <p class="text-xl font-light tracking-wide">Moments captured in the mystic mangroves.</p>
    </div>
</header>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-center gap-4 mb-12 flex-wrap">
            <button class="filter-btn active px-6 py-2 rounded-full border border-green-900 bg-green-900 text-white"
                data-filter="all">All</button>
            <button
                class="filter-btn px-6 py-2 rounded-full border border-gray-300 hover:border-green-900 hover:text-green-900 text-gray-500"
                data-filter="wildlife">Wildlife</button>
            <button
                class="filter-btn px-6 py-2 rounded-full border border-gray-300 hover:border-green-900 hover:text-green-900 text-gray-500"
                data-filter="nature">Nature</button>
            <button
                class="filter-btn px-6 py-2 rounded-full border border-gray-300 hover:border-green-900 hover:text-green-900 text-gray-500"
                data-filter="experience">Experience</button>
        </div>

        <div class="masonry-grid" id="gallery-grid">
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="wildlife">
                <img src="https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">The Royal Bengal</p>
                </div>
            </div>
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="nature">
                <img src="https://images.unsplash.com/photo-1615656783693-793dbd239c04?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">Golden Sunrise</p>
                </div>
            </div>
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="experience">
                <img src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">River Cruise</p>
                </div>
            </div>
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="wildlife">
                <img src="https://images.unsplash.com/photo-1599407337675-927696660cc6?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">Spotted Deer</p>
                </div>
            </div>
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="experience">
                <img src="https://images.unsplash.com/photo-1623164962299-0c679b329244?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">Village Life</p>
                </div>
            </div>
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="nature">
                <img src="https://images.unsplash.com/photo-1628062967667-1755da909249?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">Mangrove Roots</p>
                </div>
            </div>
            <div class="mb-6 break-inside relative group overflow-hidden rounded-xl cursor-zoom-in gallery-item"
                data-category="experience">
                <img src="https://images.unsplash.com/photo-1631452180519-c014fe946bc7?q=80&w=1000&auto=format&fit=crop"
                    class="w-full object-cover transition-transform duration-700 group-hover:scale-110">
                <div
                    class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                    <p class="text-white font-bold font-serif">Local Cuisine</p>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="lightbox" class="fixed inset-0 z-[60] bg-black/95 hidden items-center justify-center p-4"
    onclick="this.classList.add('hidden')">
    <img id="lightbox-img" class="max-h-[90vh] max-w-full rounded-lg shadow-2xl" src="">
</div>

<script>
    // Filtering Logic
    const buttons = document.querySelectorAll('.filter-btn');
    const items = document.querySelectorAll('.gallery-item');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Style Buttons
            buttons.forEach(b => {
                b.classList.remove('bg-green-900', 'text-white');
                b.classList.add('text-gray-500', 'border-gray-300');
            });
            btn.classList.add('bg-green-900', 'text-white');
            btn.classList.remove('text-gray-500', 'border-gray-300');

            // Filter Items
            const filter = btn.getAttribute('data-filter');
            items.forEach(item => {
                if (filter === 'all' || item.getAttribute('data-category') === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Lightbox Logic
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');

    items.forEach(item => {
        item.addEventListener('click', () => {
            const src = item.querySelector('img').src;
            lightboxImg.src = src;
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
        });
    });

    // GSAP Entry
    gsap.from(".gallery-item", {
        y: 50, opacity: 0, duration: 0.8, stagger: 0.1, ease: "power2.out"
    });
</script>

<?php include 'footer.php'; ?>