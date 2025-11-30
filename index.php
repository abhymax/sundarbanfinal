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

    <!-- Content -->
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto mt-20">
        <div class="mb-6 flex justify-center">
            <span
                class="px-4 py-1.5 rounded-full border border-white/30 bg-white/10 backdrop-blur-md text-sm font-medium tracking-wider uppercase">
                Explore Our Tour Packages
            </span>
        </div>
        <h1 class="font-serif text-5xl md:text-7xl lg:text-8xl font-bold mb-6 leading-tight">
            <?php echo htmlspecialchars($hero['title']); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-200 mb-10 max-w-2xl mx-auto font-light tracking-wide">
            <?php echo htmlspecialchars($hero['subtitle']); ?>
        </p>

        <!-- Enquiry Form -->
        <div class="bg-white/10 backdrop-blur-md p-6 rounded-2xl max-w-2xl mx-auto mt-8 border border-white/20">
            <h3 class="text-xl font-bold mb-4 text-white">Quick Enquiry</h3>
            <form action="submit_inquiry.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="name" placeholder="Your Name" required
                    class="bg-white/80 border-0 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-tiger-orange focus:bg-white transition">
                <input type="tel" name="phone" placeholder="Phone Number" required
                    class="bg-white/80 border-0 rounded-lg px-4 py-3 text-gray-900 placeholder-gray-500 focus:ring-2 focus:ring-tiger-orange focus:bg-white transition">
                <button type="submit"
                    class="bg-tiger-orange text-black font-bold py-3 rounded-lg hover:bg-white hover:text-tiger-orange transition duration-300 flex items-center justify-center gap-2">
                    <span>Send Request</span>
                    <span class="material-symbols-outlined text-sm">send</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce text-white">
        <span class="material-symbols-outlined text-4xl">keyboard_arrow_down</span>
    </div>

    <!-- Bottom Wave -->
    <div class="absolute bottom-0 left-0 w-full leading-none z-20">
        <svg class="relative block w-full h-[60px] md:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path
                d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,64C960,75,1056,85,1152,80C1248,75,1344,53,1392,42.7L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"
                class="fill-white"></path>
        </svg>
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
                <span
                    class="text-tiger-orange font-bold tracking-widest uppercase text-sm"><?php echo htmlspecialchars($about['tagline']); ?></span>
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
<section class="py-24 bg-gray-50" id="packages">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm">Curated Itineraries</span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-safari-green mt-3">Choose Your Expedition</h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">From quick day escapes to deep jungle immersions, we have
                designed the perfect route for every traveler.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (!empty($packages)): ?>
                <?php foreach ($packages as $pkg): ?>
                    <div class="group package-card relative h-[500px] rounded-3xl overflow-hidden cursor-pointer bg-gray-900 shadow-2xl"
                        onclick="window.location.href='<?php echo htmlspecialchars($pkg['slug']); ?>.php'">
                        <img alt="<?php echo htmlspecialchars($pkg['title']); ?>"
                            class="absolute inset-0 w-full h-full object-cover opacity-90 transition-transform duration-700 group-hover:scale-110"
                            src="<?php echo htmlspecialchars($pkg['image_url']); ?>">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/30 to-transparent"></div>

                        <?php if ($pkg['is_popular']): ?>
                            <div
                                class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full border border-white/20 z-10">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Most Popular</span>
                            </div>
                        <?php endif; ?>
                        <?php if ($pkg['is_bestseller']): ?>
                            <div class="absolute top-4 right-4 bg-tiger-orange px-3 py-1 rounded-full shadow-lg z-10">
                                <span class="text-xs font-bold text-white uppercase tracking-wider">Best Seller</span>
                            </div>
                        <?php endif; ?>

                        <div class="absolute bottom-0 p-8 w-full z-10">
                            <div class="transform transition-transform duration-500 translate-y-4 group-hover:translate-y-0">
                                <span
                                    class="text-tiger-orange font-bold text-lg mb-1 block">₹<?php echo number_format($pkg['price']); ?>
                                    / Person</span>
                                <h3 class="text-3xl font-serif font-bold text-white mb-3">
                                    <?php echo htmlspecialchars($pkg['title']); ?>
                                </h3>
                                <ul
                                    class="text-gray-300 text-sm mb-6 space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-75">
                                    <?php
                                    $features = explode(',', $pkg['features']);
                                    foreach ($features as $feature):
                                        ?>
                                        <li class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-tiger-orange text-xs">check_circle</span>
                                            <?php echo htmlspecialchars(trim($feature)); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                                <button
                                    class="block w-full text-center bg-white text-safari-green font-bold py-3 rounded-xl hover:bg-tiger-orange hover:text-white transition">View
                                    Details</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-10 text-gray-500">
                    No packages available at the moment.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Wildlife Section -->
<section class="py-24 bg-white" id="wildlife">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm">Meet the Locals</span>
            <h2 class="text-4xl md:text-5xl font-serif font-bold text-safari-green mt-3">Wildlife of Sundarbans</h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto">The mangrove forests are home to a diverse range of species.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (!empty($featured_species)): ?>
                <?php foreach ($featured_species as $species): ?>
                    <div class="group relative overflow-hidden rounded-2xl shadow-lg aspect-[4/3]">
                        <img src="<?php echo htmlspecialchars($species['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($species['name']); ?>"
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 p-6 w-full">
                            <h3 class="text-2xl font-serif font-bold text-white mb-1"><?php echo htmlspecialchars($species['name']); ?></h3>
                            <p class="text-gray-300 text-sm line-clamp-2"><?php echo htmlspecialchars($species['description']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center text-gray-500">No species featured at the moment.</div>
            <?php endif; ?>
        </div>
        
        <div class="mt-12 text-center">
             <a href="species_checklist.php" class="inline-flex items-center gap-2 bg-safari-green text-white px-8 py-3 rounded-full font-bold hover:bg-tiger-orange hover:text-black transition-colors duration-300">
                <span>View Complete Checklist</span>
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-24 bg-gray-50 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12 text-center">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm">Guest Reviews</span>
        <h2 class="text-4xl md:text-5xl font-serif font-bold text-safari-green mt-3">Stories from the Wild</h2>
    </div>
    
    <div class="relative w-full overflow-hidden">
        <div class="flex gap-8 animate-marquee whitespace-nowrap">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $testimonial): ?>
                    <div class="inline-block w-[400px] bg-white p-8 rounded-2xl shadow-sm whitespace-normal">
                        <div class="flex items-center gap-1 text-tiger-orange mb-4">
                            <?php for($i=0; $i<5; $i++): ?>
                                <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-600 mb-6 italic">"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-xl">
                                <?php echo substr($testimonial['name'], 0, 1); ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($testimonial['location']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                 <!-- Duplicate for seamless loop -->
                 <?php foreach ($testimonials as $testimonial): ?>
                    <div class="inline-block w-[400px] bg-white p-8 rounded-2xl shadow-sm whitespace-normal">
                        <div class="flex items-center gap-1 text-tiger-orange mb-4">
                            <?php for($i=0; $i<5; $i++): ?>
                                <span class="material-symbols-outlined text-sm fill-current">star</span>
                            <?php endfor; ?>
                        </div>
                        <p class="text-gray-600 mb-6 italic">"<?php echo htmlspecialchars($testimonial['content']); ?>"</p>
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-bold text-xl">
                                <?php echo substr($testimonial['name'], 0, 1); ?>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900"><?php echo htmlspecialchars($testimonial['name']); ?></h4>
                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($testimonial['location']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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