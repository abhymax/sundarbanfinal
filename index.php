<?php
require_once 'db_connect.php';

// --- SAFE DATA FETCHING ---
try {
    $stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (Exception $e) { $packages = []; }

try {
    $stmt = $pdo->query("SELECT * FROM home_about WHERE id = 1");
    $about = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (Exception $e) { $about = []; }

try {
    $stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = 'home_hero'");
    $stmt->execute();
    $hero = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
} catch (Exception $e) { $hero = []; }

try {
    $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (Exception $e) { $testimonials = []; }

try {
    $stmt = $pdo->query("SELECT * FROM species WHERE is_featured_on_home = 1 ORDER BY sort_order ASC LIMIT 6");
    $featured_species = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (Exception $e) { $featured_species = []; }

try {
    $stmt = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC");
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
} catch (Exception $e) { $faqs = []; }
?>
<?php include 'header.php'; ?>

<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <?php if (!empty($hero['video_url'])): ?>
            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="<?php echo htmlspecialchars($hero['video_url']); ?>" type="video/mp4">
            </video>
        <?php else: ?>
            <img src="<?php echo htmlspecialchars($hero['image_url'] ?? ''); ?>" alt="Sundarban" class="w-full h-full object-cover">
        <?php endif; ?>
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <div class="relative z-10 text-center text-white px-4 max-w-6xl mx-auto mt-16">
        <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold mb-4 leading-tight drop-shadow-lg">
            <?php echo htmlspecialchars($hero['title'] ?? 'Welcome to Sundarban'); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-100 mb-10 max-w-2xl mx-auto font-light tracking-wide drop-shadow-md">
            <?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?>
        </p>

        <div class="glass-card rounded-2xl p-4 md:p-6 max-w-5xl mx-auto shadow-2xl border-t border-white/40 hidden md:block mb-8 bg-white/90 backdrop-blur-xl">
              <div class="grid grid-cols-4 gap-4 items-center">
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Date</label>
                    <input id="hero-date" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer placeholder-gray-500" type="date">
                </div>
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Guests</label>
                    <select id="hero-guests" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer"><option value="2">2 Travelers</option><option value="4">4 Travelers</option><option value="6">6+ Group</option></select>
                </div>
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-1">Package</label>
                    <select id="hero-package" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer"><option value="All Packages">All Packages</option><?php foreach ($packages as $pkg) echo "<option value='{$pkg['title']}'>{$pkg['title']}</option>"; ?></select>
                </div>
                <button id="hero-check-btn" class="bg-safari-green text-white h-12 rounded-xl font-bold hover:bg-green-900 transition flex items-center justify-center gap-2 shadow-lg">Check Availability</button>
            </div>
        </div>

        <div class="mt-8 md:hidden">
            <button onclick="openBooking()" class="bg-[#FFD700] text-black px-8 py-4 rounded-full font-bold text-base hover:bg-yellow-400 transition inline-flex items-center gap-2 group shadow-xl">
                Plan Your Trip
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </div>
    </div>
</section>

<section class="py-16 md:py-24 bg-white" id="who-we-are">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 md:gap-16 items-center">
            <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                <img src="<?php echo htmlspecialchars($about['image_url'] ?? ''); ?>" alt="Sundarban" class="w-full h-[300px] md:h-[500px] object-cover hover:scale-105 transition duration-700">
            </div>
            <div>
                <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm"><?php echo htmlspecialchars($about['tagline'] ?? ''); ?></span>
                <h2 class="text-3xl md:text-5xl font-serif font-bold text-safari-green mt-2 mb-6"><?php echo htmlspecialchars($about['title'] ?? ''); ?></h2>
                <p class="text-gray-600 mb-8 leading-relaxed text-base md:text-lg"><?php echo nl2br(htmlspecialchars($about['description'] ?? '')); ?></p>
                
                <div class="space-y-6">
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-orange-100 flex items-center justify-center text-tiger-orange flex-shrink-0">
                            <span class="material-symbols-outlined text-xl md:text-2xl"><?php echo htmlspecialchars($about['feature_1_icon'] ?? ''); ?></span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-base md:text-lg"><?php echo htmlspecialchars($about['feature_1_text'] ?? ''); ?></h4>
                    </div>
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-green-100 flex items-center justify-center text-safari-green flex-shrink-0">
                            <span class="material-symbols-outlined text-xl md:text-2xl"><?php echo htmlspecialchars($about['feature_2_icon'] ?? ''); ?></span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-base md:text-lg"><?php echo htmlspecialchars($about['feature_2_text'] ?? ''); ?></h4>
                    </div>
                    <div class="flex gap-4 items-center">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                            <span class="material-symbols-outlined text-xl md:text-2xl"><?php echo htmlspecialchars($about['feature_3_icon'] ?? ''); ?></span>
                        </div>
                        <h4 class="font-bold text-gray-800 text-base md:text-lg"><?php echo htmlspecialchars($about['feature_3_text'] ?? ''); ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-16 md:py-24 bg-gray-50" id="packages">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 md:mb-16">
            <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm">Curated Itineraries</span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-safari-green mt-2">Choose Your Expedition</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($packages as $pkg): ?>
                <div class="group package-card relative h-[450px] md:h-[500px] rounded-3xl overflow-hidden cursor-pointer bg-gray-900 shadow-lg" onclick="window.location.href='<?php echo htmlspecialchars($pkg['slug']); ?>.php'">
                    <img src="<?php echo htmlspecialchars($pkg['image_url']); ?>" class="absolute inset-0 w-full h-full object-cover opacity-90 transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/30 to-transparent"></div>
                    
                    <?php if ($pkg['is_popular']): ?>
                        <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full border border-white/20 z-10"><span class="text-xs font-bold text-white uppercase">Most Popular</span></div>
                    <?php endif; ?>

                    <div class="absolute bottom-0 p-6 md:p-8 w-full z-10">
                        <div class="transform transition-transform duration-500 translate-y-4 group-hover:translate-y-0">
                            <span class="text-tiger-orange font-bold text-lg mb-1 block">₹<?php echo number_format($pkg['price']); ?> / Person</span>
                            <h3 class="text-2xl md:text-3xl font-serif font-bold text-white mb-3"><?php echo htmlspecialchars($pkg['title']); ?></h3>
                            <button class="block w-full text-center bg-white text-safari-green font-bold py-3 rounded-xl mt-4 hover:bg-tiger-orange hover:text-white transition">View Details</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-16 md:py-24 bg-[#0a260a] relative overflow-hidden" id="wildlife">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12 md:mb-16">
            <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm">The Residents</span>
            <h2 class="text-3xl md:text-5xl font-serif font-bold text-white mt-2">Meet the Locals</h2>
        </div>

        <div class="relative w-full overflow-hidden">
             <div class="wildlife-track flex gap-6 w-max hover:pause-scroll">
                <?php 
                $display_species = array_merge($featured_species, $featured_species, $featured_species); // Loop effect
                foreach ($display_species as $species): 
                ?>
                    <div class="relative w-[280px] md:w-[350px] h-[400px] md:h-[450px] rounded-3xl overflow-hidden shrink-0 group cursor-pointer shadow-lg border border-white/10">
                        <img src="<?php echo htmlspecialchars($species['image_url']); ?>" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 p-6 md:p-8 w-full">
                            <h3 class="text-xl md:text-2xl font-serif font-bold text-white mb-1"><?php echo htmlspecialchars($species['name']); ?></h3>
                            <p class="text-gray-400 text-xs md:text-sm tracking-wide uppercase font-medium truncate"><?php echo htmlspecialchars($species['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="mt-12 text-center">
            <a href="species_checklist.php" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-white/20 text-white hover:bg-tiger-orange hover:border-tiger-orange hover:text-black transition-all duration-300">
                <span>View Species Checklist</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

<section class="py-16 md:py-24 bg-[#1a2e1a] overflow-hidden relative" id="testimonials">
    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center mb-12">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm">Guest Diaries</span>
        <h2 class="text-3xl md:text-5xl font-serif font-bold text-white mt-2">Stories from the River</h2>
    </div>
    
    <div class="relative w-full overflow-hidden">
        <div class="testimonial-track flex gap-8 w-max">
            <?php 
            $display_testimonials = array_merge($testimonials, $testimonials);
            foreach ($display_testimonials as $t): 
            ?>
                <div class="w-[300px] md:w-[400px] bg-white/5 backdrop-blur-md p-6 md:p-8 rounded-2xl border border-white/10 shrink-0">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-tiger-orange flex items-center justify-center text-black font-bold text-lg">
                            <?php echo substr($t['name'], 0, 1); ?>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-base md:text-lg"><?php echo htmlspecialchars($t['name']); ?></h4>
                            <div class="flex text-yellow-400 text-xs gap-0.5">★★★★★</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic text-sm md:text-base leading-relaxed">"<?php echo htmlspecialchars($t['content'] ?? ''); ?>"</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10 text-center mt-12">
        <a href="https://share.google/pPARQ1fOGvqUFHZtr" target="_blank" 
           class="inline-flex items-center gap-3 px-8 py-4 rounded-full bg-white text-[#1a2e1a] font-bold hover:bg-tiger-orange hover:text-black transition-all duration-300 shadow-xl transform hover:-translate-y-1 group">
            
            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0OCA0OCI+PHBhdGggZmlsbD0iI0VBNDMzNSIgZD0iTTI0IDkuNWMzLjU0IDAgNi43MSAxLjIyIDkuMjEgMy42bDYuODUtNi44NUMzNS45IDIuMzggMzAuNDcgMCAyNCAwIDE0LjYyIDAgNi41MSA1LjM4IDIuNTYgMTMuMjJsNy45OCA2LjE5QzEyLjQzIDEzLjcyIDE3Ljc0IDkuNSAyNCA5LjV6Ii8+PHBhdGggZmlsbD0iIzQyODVGNCIgZD0iTTQ2Ljk4IDI0LjU1YzAgLTEuNTctLjE1LTMuMDktLjQ2LTQuNTVIMjR2OS4wOWgxMi45MmMtLjU1IDIuOTMtMi4yNiA1LjQxLTQuODIgNy4xOGw3LjgyIDYuMDRjNC41Ny00LjIzIDcuMjEtMTAuNDkgNy4yMS0xNy43NnoiLz48cGF0aCBmaWxsPSIjRkJCQzA1IiBkPSJNMTAuNTMgMjguNTljLS40OC0xLjQ1LS43Ni0yLjk5LS43Ni00LjU5cy4yNy0zLjE0Ljc2LTQuNTlsLTcuOTgtNi4xOUMuOTIgMTYuNDYgMCAyMC4xMiAwIDI0YzAgMy44OC45MiA3LjU0IDIuNTYgMTAuNzhsNy45Ny02LjE5eiIvPjxwYXRoIGZpbGw9IiMzNEE4NTMiIGQ9Ik0yNCA0OGM2LjQ4IDAgMTEuOTMtMi4xMyAxNS44OS01LjgxbC03LjgyLTYuMDRjLTIuMTQgMS40NC00Ljg4IDIuMzUtOC4wNyAyLjM1LTYuMjYgMC0xMS41Ny00LjIyLTEzLjQ3LTkuOTFsLTcuOTggNi4xOUM2LjUxIDQyLjYyIDE0LjYyIDQ4IDI0IDQ4eiIvPjwvc3ZnPg==" 
                 alt="Google" class="w-6 h-6">
                 
            <span>Review us on Google</span>
        </a>
    </div>
    ```
    </section>

<section class="py-16 md:py-24 bg-safari-green text-white">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-serif font-bold">Common Questions</h2>
        </div>
        <div class="space-y-4">
            <?php foreach ($faqs as $faq): ?>
                <div class="faq-item border border-white/20 rounded-xl bg-white/5 overflow-hidden">
                    <button class="w-full flex justify-between items-center p-5 md:p-6 text-left hover:bg-white/10 transition" onclick="toggleFaq(this)">
                        <span class="font-bold text-base md:text-lg"><?php echo htmlspecialchars($faq['question']); ?></span>
                        <span class="material-symbols-outlined faq-icon">expand_more</span>
                    </button>
                    <div class="faq-content">
                        <div class="p-5 md:p-6 pt-0 text-gray-300 text-sm md:text-base leading-relaxed border-t border-white/10 mt-2">
                            <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>