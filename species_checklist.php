<?php
require_once 'db_connect.php';

// Fetch All Species
try {
    $stmt = $pdo->query("SELECT * FROM species ORDER BY sort_order ASC");
    $species_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $species_list = [];
}

include 'header.php';
?>

<!-- Hero Section -->
<section class="relative h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=2070&auto=format&fit=crop"
            alt="Sundarban Wildlife" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
    </div>

    <div class="relative z-10 text-center text-white px-4">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm mb-4 block">The Inhabitants</span>
        <h1 class="font-serif text-5xl md:text-7xl font-bold mb-6">Wildlife Checklist</h1>
        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light">
            Discover the diverse flora and fauna that call the Sundarbans home.
        </p>
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

<!-- Species Grid -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            <?php if (!empty($species_list)): ?>
                <?php foreach ($species_list as $animal): ?>
                    <div class="group">
                        <div class="relative overflow-hidden rounded-3xl h-80 mb-6 shadow-lg">
                            <img src="<?php echo htmlspecialchars($animal['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($animal['name']); ?>"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                        <h3
                            class="text-3xl font-serif font-bold text-safari-green mb-3 group-hover:text-tiger-orange transition-colors">
                            <?php echo htmlspecialchars($animal['name']); ?>
                        </h3>
                        <p class="text-gray-600 leading-relaxed">
                            <?php echo htmlspecialchars($animal['description']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-20">
                    <p class="text-gray-500 text-xl">No species listed yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>