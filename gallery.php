<?php 
require_once 'db_connect.php'; 
include 'header.php';

// Fetch Images (Sorted by Order first, then newest)
try {
    $images = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $images = []; }
?>

<header class="relative h-[50vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=2574&auto=format&fit=crop"
             class="w-full h-full object-cover animate-kenburns"> <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/60"></div>
    </div>
    <div class="relative z-10 text-center text-white px-4">
        <span class="text-[#FFD700] font-bold tracking-[0.2em] uppercase text-sm mb-2 block">Visual Journey</span>
        <h1 class="text-5xl md:text-7xl font-serif font-bold mb-4 drop-shadow-lg">Captured Moments</h1>
        <p class="text-lg font-light text-gray-200 max-w-2xl mx-auto">From the roar of the tiger to the silence of the creeks.</p>
    </div>
</header>

<section class="py-16 bg-[#f8faf9] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex justify-center gap-3 mb-12 flex-wrap">
            <button onclick="filterGallery('all')" class="filter-btn active px-6 py-2 rounded-full border border-[#2E4622] bg-[#2E4622] text-white font-medium transition-all duration-300 hover:shadow-lg">All</button>
            <button onclick="filterGallery('wildlife')" class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 font-medium hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Wildlife</button>
            <button onclick="filterGallery('nature')" class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 font-medium hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Nature</button>
            <button onclick="filterGallery('boat')" class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 font-medium hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Boat & Resort</button>
            <button onclick="filterGallery('tourists')" class="filter-btn px-6 py-2 rounded-full border border-gray-300 text-gray-600 font-medium hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Experience</button>
        </div>

        <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6" id="gallery-grid">
            <?php foreach ($images as $img): 
                $src = (strpos($img['image_url'], 'http') === 0) ? $img['image_url'] : $img['image_url'];
                // Use 'break-inside-avoid' to prevent images splitting across columns
            ?>
                <div class="gallery-item relative group break-inside-avoid rounded-2xl overflow-hidden cursor-zoom-in shadow-md hover:shadow-xl transition-all duration-500" 
                     data-category="<?php echo htmlspecialchars($img['category']); ?>"
                     onclick="openLightbox('<?php echo $src; ?>', '<?php echo htmlspecialchars($img['title'] ?? ''); ?>')">
                    
                    <img src="<?php echo $src; ?>" alt="<?php echo htmlspecialchars($img['title']); ?>" class="w-full h-auto transform transition duration-700 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <span class="text-[#FFD700] text-xs font-bold uppercase tracking-wider mb-1"><?php echo ucfirst($img['category']); ?></span>
                        <?php if(!empty($img['title'])): ?>
                            <h3 class="text-white font-serif text-xl font-bold"><?php echo htmlspecialchars($img['title']); ?></h3>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($images)): ?>
            <div class="text-center text-gray-400 py-20">
                <span class="material-symbols-outlined text-6xl mb-2">perm_media</span>
                <p>Gallery is being curated. Check back soon!</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<div id="lightbox" class="fixed inset-0 z-[100] bg-black/95 hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white hover:text-[#FFD700] transition transform hover:rotate-90">
        <span class="material-symbols-outlined text-4xl">close</span>
    </button>
    
    <div class="relative max-w-7xl max-h-[90vh] px-4">
        <img id="lightbox-img" src="" class="max-h-[85vh] max-w-full rounded shadow-2xl border border-white/10">
        <p id="lightbox-caption" class="text-center text-white mt-4 font-serif text-xl tracking-wide"></p>
    </div>
</div>

<script>
    // Filter Logic
    function filterGallery(category) {
        const items = document.querySelectorAll('.gallery-item');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update buttons
        buttons.forEach(btn => {
            if (btn.innerText.toLowerCase().includes(category === 'all' ? 'all' : category.split(' ')[0])) {
                btn.classList.add('bg-[#2E4622]', 'text-white', 'border-[#2E4622]');
                btn.classList.remove('text-gray-600', 'border-gray-300');
            } else {
                btn.classList.remove('bg-[#2E4622]', 'text-white', 'border-[#2E4622]');
                btn.classList.add('text-gray-600', 'border-gray-300');
            }
        });

        // Animate items
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.classList.remove('opacity-0', 'scale-95');
                    item.classList.add('opacity-100', 'scale-100');
                }, 50);
            } else {
                item.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }

    // Lightbox Logic
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxCaption = document.getElementById('lightbox-caption');

    function openLightbox(src, caption) {
        lightboxImg.src = src;
        lightboxCaption.innerText = caption;
        lightbox.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        requestAnimationFrame(() => {
            lightbox.classList.remove('opacity-0');
        });
        document.body.style.overflow = 'hidden'; // Disable scrolling
    }

    function closeLightbox() {
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightboxImg.src = '';
        }, 300);
        document.body.style.overflow = 'auto'; // Enable scrolling
    }

    // Close on background click
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) closeLightbox();
    });
    
    // Escape key close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>

<style>
    /* Kenburns Animation for Hero */
    @keyframes kenburns {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-kenburns {
        animation: kenburns 20s infinite alternate ease-in-out;
    }
</style>

<?php include 'footer.php'; ?>