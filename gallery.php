<?php 
require_once 'db_connect.php'; 
include 'header.php';

// Fetch Images (Sorted by Order first, then newest)
try {
    $images = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { $images = []; }
?>

<header class="relative h-[50vh] min-h-[400px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?q=80&w=2574&auto=format&fit=crop"
             class="w-full h-full object-cover animate-kenburns"> <div class="absolute inset-0 bg-gradient-to-b from-black/30 to-black/60"></div>
    </div>
    <div class="relative z-10 text-center text-white px-4 mt-10 md:mt-0">
        <span class="text-[#FFD700] font-bold tracking-[0.2em] uppercase text-xs md:text-sm mb-2 block">Visual Journey</span>
        <h1 class="text-4xl md:text-7xl font-serif font-bold mb-4 drop-shadow-lg">Captured Moments</h1>
        <p class="text-base md:text-lg font-light text-gray-200 max-w-2xl mx-auto">From the roar of the tiger to the silence of the creeks.</p>
    </div>
</header>

<section class="py-12 md:py-20 bg-[#f8faf9] min-h-screen">
    <div class="max-w-7xl mx-auto px-3 md:px-6">
        
        <div class="flex overflow-x-auto pb-4 md:pb-0 md:justify-center gap-3 mb-8 md:mb-12 no-scrollbar px-1">
            <button onclick="filterGallery('all')" class="filter-btn active px-5 py-2 md:px-6 rounded-full border border-[#2E4622] bg-[#2E4622] text-white font-medium text-sm whitespace-nowrap transition-all duration-300 shadow-md">All Moments</button>
            <button onclick="filterGallery('wildlife')" class="filter-btn px-5 py-2 md:px-6 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm whitespace-nowrap hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Wildlife</button>
            <button onclick="filterGallery('nature')" class="filter-btn px-5 py-2 md:px-6 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm whitespace-nowrap hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Nature</button>
            <button onclick="filterGallery('boat')" class="filter-btn px-5 py-2 md:px-6 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm whitespace-nowrap hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Boat & Resort</button>
            <button onclick="filterGallery('tourists')" class="filter-btn px-5 py-2 md:px-6 rounded-full border border-gray-300 bg-white text-gray-600 font-medium text-sm whitespace-nowrap hover:border-[#2E4622] hover:text-[#2E4622] transition-all duration-300">Experience</button>
        </div>

        <div class="columns-2 md:columns-3 lg:columns-4 gap-3 md:gap-4 space-y-3 md:space-y-4" id="gallery-grid">
            <?php foreach ($images as $img): 
                $src = (strpos($img['image_url'], 'http') === 0) ? $img['image_url'] : $img['image_url'];
            ?>
                <div class="gallery-item break-inside-avoid relative group rounded-lg md:rounded-xl overflow-hidden cursor-zoom-in shadow-sm hover:shadow-xl transition-all duration-300" 
                     data-category="<?php echo htmlspecialchars($img['category']); ?>"
                     onclick="openLightbox('<?php echo $src; ?>', '<?php echo htmlspecialchars($img['title'] ?? ''); ?>')">
                    
                    <img src="<?php echo $src; ?>" alt="<?php echo htmlspecialchars($img['title']); ?>" 
                         class="w-full h-auto transform transition duration-700 group-hover:scale-110 loading='lazy'">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 md:group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 hidden md:flex">
                        <span class="text-[#FFD700] text-[10px] font-bold uppercase tracking-wider mb-1"><?php echo ucfirst($img['category']); ?></span>
                        <?php if(!empty($img['title'])): ?>
                            <h3 class="text-white font-serif text-lg font-bold leading-tight"><?php echo htmlspecialchars($img['title']); ?></h3>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($images)): ?>
            <div class="text-center text-gray-400 py-20">
                <span class="material-symbols-outlined text-6xl mb-2 opacity-50">perm_media</span>
                <p>Gallery is being curated. Check back soon!</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<div id="lightbox" class="fixed inset-0 z-[200] bg-black/95 hidden flex items-center justify-center opacity-0 transition-opacity duration-300 p-4" onclick="if(event.target===this) closeLightbox()">
    
    <button onclick="closeLightbox()" class="absolute top-4 right-4 w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition z-[210]">
        <span class="material-symbols-outlined text-2xl">close</span>
    </button>
    
    <div class="relative w-full max-w-5xl max-h-full flex flex-col items-center justify-center">
        <img id="lightbox-img" src="" class="max-h-[80vh] max-w-full rounded shadow-2xl object-contain">
        <p id="lightbox-caption" class="text-center text-white/90 mt-4 font-serif text-lg tracking-wide"></p>
    </div>
</div>

<script>
    // Filter Logic
    function filterGallery(category) {
        const items = document.querySelectorAll('.gallery-item');
        const buttons = document.querySelectorAll('.filter-btn');
        
        // Update buttons state
        buttons.forEach(btn => {
            if (btn.innerText.toLowerCase().includes(category === 'all' ? 'all' : category.split(' ')[0])) {
                btn.classList.add('bg-[#2E4622]', 'text-white', 'border-[#2E4622]', 'shadow-md');
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-300');
            } else {
                btn.classList.remove('bg-[#2E4622]', 'text-white', 'border-[#2E4622]', 'shadow-md');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-300');
            }
        });

        // Filter Items
        items.forEach(item => {
            if (category === 'all' || item.dataset.category === category) {
                item.style.display = 'inline-block'; // Required for masonry
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
        requestAnimationFrame(() => {
            lightbox.classList.remove('opacity-0');
        });
        document.body.style.overflow = 'hidden'; // Disable page scroll
    }

    function closeLightbox() {
        lightbox.classList.add('opacity-0');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            lightboxImg.src = '';
        }, 300);
        document.body.style.overflow = 'auto'; // Enable page scroll
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });
</script>

<style>
    /* Hide Scrollbar for Filters */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Kenburns Animation */
    @keyframes kenburns {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    .animate-kenburns {
        animation: kenburns 20s infinite alternate ease-in-out;
    }
</style>

<?php include 'footer.php'; ?>