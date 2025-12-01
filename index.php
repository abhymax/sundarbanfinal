<?php
require_once 'db_connect.php';

// Fetch Packages
try {
    $stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
    $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $packages = [];
}

// Fetch "Who We Are" Content
try {
    $stmt = $pdo->query("SELECT * FROM home_about WHERE id = 1");
    $about = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$about) {
        throw new Exception("No about content found");
    }
} catch (Exception $e) {
    // Fallback content if DB fails
    $about = [
        'tagline' => 'Who We Are',
        'title' => 'Sundarban Boat Safari',
        'description' => 'We are not just a tour agency; we are locals who grew up listening to the whispers of the mangroves. Our mission is to show you the raw, unfiltered beauty of the Sundarbans while ensuring the conservation of this fragile ecosystem.',
        'image_url' => 'https://images.unsplash.com/photo-1596895111956-bf1cf0599ce5?q=80&w=1000&auto=format&fit=crop',
        'feature_1_icon' => 'safety_check',
        'feature_1_text' => '100% Safety Record',
        'feature_2_icon' => 'local_library',
        'feature_2_text' => 'Expert Local Guides',
        'feature_3_icon' => 'restaurant',
        'feature_3_text' => 'Hygienic Local Cuisine'
    ];
}

// Fetch Hero Content
try {
    $stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = 'home_hero'");
    $stmt->execute();
    $hero_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$hero_data) {
        throw new Exception("No hero content found");
    }

    // Map database columns to template variables
    $hero = [
        'title' => $hero_data['title'],
        'subtitle' => $hero_data['subtitle'],
        'bg_image_url' => $hero_data['image_url'],
        'video_url' => $hero_data['video_url']
    ];

} catch (Exception $e) {
    $hero = [
        'title' => 'Into the Wild',
        'subtitle' => 'Explore the Mystic Mangroves',
        'bg_image_url' => 'https://images.unsplash.com/photo-1519055548599-6d4d129508c4?q=80&w=2070&auto=format&fit=crop',
        'video_url' => ''
    ];
}

// Fetch Testimonials
try {
    $stmt = $pdo->query("SELECT * FROM testimonials ORDER BY created_at DESC");
    $testimonials = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $testimonials = [];
}

// Fetch Featured Species
try {
    $stmt = $pdo->query("SELECT * FROM species WHERE is_featured_on_home = 1 ORDER BY sort_order ASC LIMIT 6");
    $featured_species = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $featured_species = [];
}

// Fetch FAQs
try {
    $stmt = $pdo->query("SELECT * FROM faqs ORDER BY sort_order ASC");
    $faqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $faqs = [];
}
?>
<?php include 'header.php'; ?>
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <?php if (!empty($hero['video_url'])): ?>
            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="<?php echo htmlspecialchars($hero['video_url']); ?>" type="video/mp4">
            </video>
        <?php else: ?>
            <img src="<?php echo htmlspecialchars($hero['bg_image_url']); ?>" alt="Sundarban"
                class="w-full h-full object-cover">
        <?php endif; ?>
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <div class="relative z-10 text-center text-white px-4 max-w-6xl mx-auto mt-16">
        <h1 class="font-serif text-4xl md:text-6xl font-bold mb-4 leading-tight drop-shadow-lg">
            <?php echo htmlspecialchars($hero['title']); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-100 mb-10 max-w-2xl mx-auto font-light tracking-wide drop-shadow-md">
            <?php echo htmlspecialchars($hero['subtitle']); ?>
        </p>

        <div class="glass-card rounded-2xl p-4 md:p-6 max-w-5xl mx-auto shadow-2xl border-t border-white/40 hidden md:block mb-8 bg-white/90 backdrop-blur-xl">
            <div class="grid grid-cols-4 gap-4 items-center">
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Date</label>
                    <input id="hero-date" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer placeholder-gray-500" type="date">
                </div>
                
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Guests</label>
                    <select id="hero-guests" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer">
                        <option value="2">2 Travelers</option>
                        <option value="3">3 Travelers</option>
                        <option value="4">4 Travelers</option>
                        <option value="6">6+ Group</option>
                    </select>
                </div>

                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Package</label>
                    <select id="hero-package" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer">
                        <option value="All Packages">All Packages</option>
                        <?php foreach ($packages as $pkg): ?>
                            <option value="<?php echo htmlspecialchars($pkg['title']); ?>">
                                <?php echo htmlspecialchars($pkg['title']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button id="hero-check-btn" class="bg-[#2E4622] text-white h-12 rounded-xl font-bold hover:bg-[#1a2e1a] transition flex items-center justify-center gap-2 shadow-lg transform hover:-translate-y-0.5 text-sm uppercase tracking-wide">
    Check Availability
</button>
            </div>
        </div>

        <div class="mt-8">
            <button onclick="openBooking()" class="bg-[#FFD700] text-black px-10 py-4 rounded-full font-bold text-lg hover:bg-yellow-400 transition inline-flex items-center gap-2 group shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                Plan Your Trip
                <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </button>
        </div>

    </div>

    
</section>

<!-- Who We Are Section -->
<section class="py-24 bg-white relative overflow-hidden" id="about">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div class="relative">
                <div
                    class="absolute -top-10 -left-10 w-40 h-40 bg-orange-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                </div>
                <div
                    class="absolute -bottom-10 -right-10 w-40 h-40 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                </div>

                <div class="relative rounded-2xl overflow-hidden shadow-2xl group">
                    <?php if (strpos($about['image_url'], 'http') === 0): ?>
                        <img src="<?php echo htmlspecialchars($about['image_url']); ?>" alt="About Us"
                            class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                    <?php else: ?>
                        <img src="<?php echo htmlspecialchars($about['image_url']); ?>" alt="About Us"
                            class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-105">
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-tiger-orange">verified</span>
                            <span class="font-bold tracking-wider uppercase text-sm">Govt. Registered</span>
                        </div>
                        <p class="text-sm opacity-90">Serving travelers since 2010</p>
                    </div>
                </div>
            </div>

            <div>
                
                    <div class="inline-flex p-[1px] rounded-full bg-gradient-to-r from-yellow-300 via-orange-400 to-yellow-300 mb-4 shadow-sm">
                <span class="block px-6 py-1.5 rounded-full bg-[#2E4622] text-tiger-orange font-bold tracking-widest uppercase text-xs">
                    <?php echo htmlspecialchars($about['tagline']); ?>
                </span>
            </div>
                    
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-safari-green mt-3 mb-6">
                    <?php echo htmlspecialchars($about['title']); ?>
                </h2>
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    <?php echo nl2br(htmlspecialchars($about['description'])); ?>
                </p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center shrink-0 text-safari-green">
                            <span
                                class="material-symbols-outlined"><?php echo htmlspecialchars($about['feature_1_icon']); ?></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">
                                <?php echo htmlspecialchars($about['feature_1_text']); ?>
                            </h4>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center shrink-0 text-tiger-orange">
                            <span
                                class="material-symbols-outlined"><?php echo htmlspecialchars($about['feature_2_icon']); ?></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">
                                <?php echo htmlspecialchars($about['feature_2_text']); ?>
                            </h4>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0 text-blue-600">
                            <span
                                class="material-symbols-outlined"><?php echo htmlspecialchars($about['feature_3_icon']); ?></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">
                                <?php echo htmlspecialchars($about['feature_3_text']); ?>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <a href="#contact"
                        class="inline-flex items-center gap-2 text-safari-green font-bold hover:text-tiger-orange transition-colors group">
                        <span>Learn More About Us</span>
                        <span
                            class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Packages Section -->
<section class="py-24 bg-green-50" id="packages">
    <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            
            <div class="inline-flex p-[1px] rounded-full bg-gradient-to-r from-yellow-300 via-orange-400 to-yellow-300 mb-4 shadow-sm">
                <span class="block px-6 py-1.5 rounded-full bg-[#2E4622] text-tiger-orange font-bold tracking-widest uppercase text-xs">
                    Curated Itineraries
                </span>
            </div>

            <h2 class="text-4xl md:text-5xl font-serif font-bold text-safari-green mt-2">Choose Your Expedition</h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">From quick day escapes to deep jungle immersions, we have designed the perfect route for every traveler.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if (!empty($packages)): ?>
                <?php foreach ($packages as $pkg): ?>
                    <div class="group package-card relative h-[500px] rounded-3xl overflow-hidden cursor-pointer bg-white shadow-xl hover:shadow-2xl transition duration-300 border border-green-100"
                        onclick="window.location.href='<?php echo htmlspecialchars($pkg['slug']); ?>.php'">
                        
                        <img alt="<?php echo htmlspecialchars($pkg['title']); ?>"
                            class="absolute inset-0 w-full h-full object-cover opacity-90 transition-transform duration-700 group-hover:scale-110"
                            src="<?php echo htmlspecialchars($pkg['image_url']); ?>">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/30 to-transparent"></div>

                        <?php if ($pkg['is_popular']): ?>
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full border border-white/20 z-10">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Most Popular</span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($pkg['is_bestseller']): ?>
                            <div class="absolute top-4 right-4 bg-tiger-orange px-3 py-1 rounded-full shadow-lg z-10">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Best Seller</span>
                            </div>
                        <?php endif; ?>

                        <div class="absolute bottom-0 p-6 w-full z-10">
                            <div class="transform transition-transform duration-500 translate-y-4 group-hover:translate-y-0">
                                <span class="text-tiger-orange font-bold text-lg mb-1 block">₹<?php echo number_format($pkg['price']); ?> / Person</span>
                                <h3 class="text-2xl font-serif font-bold text-white mb-3 leading-tight">
                                    <?php echo htmlspecialchars($pkg['title']); ?>
                                </h3>
                                <ul class="text-gray-300 text-xs mb-6 space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-75">
                                    <?php
                                    $features = explode(',', $pkg['features']);
                                    $features = array_slice($features, 0, 2); 
                                    foreach ($features as $feature):
                                        ?>
                                        <li class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-tiger-orange text-xs">check_circle</span>
                                            <?php echo htmlspecialchars(trim($feature)); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button class="block w-full text-center bg-white text-safari-green font-bold py-3 rounded-xl hover:bg-tiger-orange hover:text-white transition shadow-lg">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-4 text-center py-10 text-gray-500">
                    No packages available at the moment.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Wildlife Section -->
<section class="py-24 bg-white overflow-hidden relative" id="wildlife">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 text-center">
        
        <div class="inline-flex p-[1px] rounded-full bg-gradient-to-r from-yellow-300 via-orange-400 to-yellow-300 mb-4 shadow-sm">
            <span class="block px-6 py-1.5 rounded-full bg-[#2E4622] text-tiger-orange font-bold tracking-widest uppercase text-xs">
                Meet the Locals
            </span>
        </div>

        <h2 class="text-4xl md:text-5xl font-serif font-bold text-safari-green mt-2">Wildlife of Sundarbans</h2>
        <p class="text-gray-500 mt-4 max-w-2xl mx-auto">The mangrove forests are home to a diverse range of species.</p>
    </div>

    <div class="relative w-full">
        <div class="absolute left-0 top-0 h-full w-20 bg-gradient-to-r from-white to-transparent z-10 pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-full w-20 bg-gradient-to-l from-white to-transparent z-10 pointer-events-none"></div>

        <div class="wildlife-track flex gap-6 w-max hover:pause-scroll">
            <?php 
            // Fallback Data (Matches your screenshot)
            if (empty($featured_species)) {
                $featured_species = [
                    ['name' => 'Royal Bengal Tiger', 'description' => 'The apex predator and soul of the forest.', 'image_url' => 'https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=1000&auto=format&fit=crop'],
                    ['name' => 'Estuarine Crocodile', 'description' => 'The largest reptile on the planet.', 'image_url' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=1000&auto=format&fit=crop'],
                    ['name' => 'Spotted Deer', 'description' => 'The graceful beauty of the mangroves.', 'image_url' => 'https://images.unsplash.com/photo-1484406566174-9da00092ee7b?q=80&w=1000&auto=format&fit=crop'],
                    ['name' => 'Kingfisher', 'description' => 'A flash of blue in the green canopy.', 'image_url' => 'https://images.unsplash.com/photo-1544552866-d3ed42536cfd?q=80&w=1000&auto=format&fit=crop']
                ];
            }

            // Duplicate data 4 times to ensure infinite seamless loop
            $display_species = array_merge($featured_species, $featured_species, $featured_species, $featured_species);
            
            foreach ($display_species as $species): 
            ?>
                <div class="relative w-[300px] md:w-[350px] h-[450px] rounded-3xl overflow-hidden shrink-0 group cursor-pointer shadow-lg border border-gray-100">
                    <img src="<?php echo htmlspecialchars($species['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($species['name']); ?>"
                         class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                    
                    <div class="absolute bottom-0 p-8 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-2xl font-serif font-bold text-white mb-1"><?php echo htmlspecialchars($species['name']); ?></h3>
                        <p class="text-gray-300 text-sm font-medium opacity-90"><?php echo htmlspecialchars($species['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="mt-12 text-center">
        <a href="species_checklist.php" class="inline-flex items-center gap-2 bg-safari-green text-white px-8 py-3 rounded-full font-bold hover:bg-tiger-orange hover:text-black transition-colors duration-300 shadow-lg">
           <span>View Complete Checklist</span>
           <span class="material-symbols-outlined">arrow_forward</span>
       </a>
   </div>
</section>

<!-- Testimonials Section -->
<section class="py-24 bg-[#0F1F15] overflow-hidden relative" id="testimonials"> <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 text-center relative z-10">
        <span class="text-[#FFD700] font-bold tracking-widest uppercase text-xs mb-2 block">Guest Diaries</span>
        <h2 class="text-4xl md:text-5xl font-serif font-bold text-white">Stories from the River</h2>
        <p class="text-gray-400 mt-4">Join 5000+ happy travelers who explored with us.</p>
    </div>
    
    <div class="relative w-full">
        <div class="absolute left-0 top-0 h-full w-20 md:w-40 bg-gradient-to-r from-[#0F1F15] to-transparent z-10 pointer-events-none"></div>
        <div class="absolute right-0 top-0 h-full w-20 md:w-40 bg-gradient-to-l from-[#0F1F15] to-transparent z-10 pointer-events-none"></div>

        <div class="testimonial-track flex gap-8 w-max">
            <?php 
            // If no testimonials in DB, create placeholders
            if (empty($testimonials)) {
                $testimonials = [
                    ['name' => 'Rahul Sen', 'location' => 'Kolkata', 'rating' => 5, 'text' => 'The 2-night package was incredible. The boat was clean and the food was better than most Kolkata restaurants.', 'image_url' => 'https://images.unsplash.com/photo-1531891437562-4301cf35b7e4?q=80&w=200&auto=format&fit=crop'],
                    ['name' => 'Priya Das', 'location' => 'Mumbai', 'rating' => 5, 'text' => 'A magical experience. The sunset from the boat deck is something I will never forget. Staff was very polite.', 'image_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=200&auto=format&fit=crop'],
                    ['name' => 'Amit Roy', 'location' => 'Bangalore', 'rating' => 5, 'text' => 'Saw the Royal Bengal Tiger! The guide was extremely knowledgeable and kept us safe. Highly recommended.', 'image_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=200&auto=format&fit=crop'],
                    ['name' => 'Sarah Jenkins', 'location' => 'UK', 'rating' => 5, 'text' => 'The silence of the mangroves is healing. This tour agency organized everything perfectly.', 'image_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=200&auto=format&fit=crop']
                ];
            }
            
            // Duplicate array 4 times to ensure infinite scroll fills the screen
            $display_testimonials = array_merge($testimonials, $testimonials, $testimonials, $testimonials);
            ?>

            <?php foreach ($display_testimonials as $t): ?>
                <div class="w-[400px] bg-white/5 backdrop-blur-sm border border-white/10 p-8 rounded-2xl shrink-0 hover:bg-white/10 transition duration-300 select-none">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-[#FFD700]">
                            <?php 
                                $img = !empty($t['image_url']) ? htmlspecialchars($t['image_url']) : 'https://ui-avatars.com/api/?name=' . urlencode($t['name']) . '&background=random';
                            ?>
                            <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($t['name']); ?>" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-lg font-serif"><?php echo htmlspecialchars($t['name']); ?></h4>
                            <div class="flex text-[#FFD700] text-xs gap-0.5">
                                <?php for($i=0; $i<$t['rating']; $i++) echo '★'; ?>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic font-light leading-relaxed">"<?php echo htmlspecialchars($t['text'] ?? $t['content']); ?>"</p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-24 bg-safari-green text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-serif font-bold mt-3">Common Questions</h2>
        </div>

        <div class="space-y-4">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $faq): ?>
                    <div
                        class="faq-item border border-white/20 rounded-xl bg-white/5 overflow-hidden hover:bg-white/10 transition-colors duration-300">
                        <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none"
                            onclick="toggleFaq(this)">
                            <span class="font-bold text-lg text-white"><?php echo htmlspecialchars($faq['question']); ?></span>
                            <span
                                class="material-symbols-outlined faq-icon text-tiger-orange transition-transform duration-300">expand_more</span>
                        </button>
                        <div class="faq-content max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="p-6 pt-0 text-gray-200 leading-relaxed border-t border-white/10 mt-2">
                                <?php echo nl2br(htmlspecialchars($faq['answer'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center text-gray-300">No FAQs available at the moment.</div>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
    function toggleFaq(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('.faq-icon');

        // Close other open FAQs
        document.querySelectorAll('.faq-content').forEach(item => {
            if (item !== content && item.style.maxHeight) {
                item.style.maxHeight = null;
                item.previousElementSibling.querySelector('.faq-icon').style.transform = 'rotate(0deg)';
            }
        });

        if (content.style.maxHeight) {
            content.style.maxHeight = null;
            icon.style.transform = 'rotate(0deg)';
        } else {
            content.style.maxHeight = content.scrollHeight + "px";
            icon.style.transform = 'rotate(180deg)';
        }
    }
</script>

<section id="contact"></section>

<?php include 'footer.php'; ?>