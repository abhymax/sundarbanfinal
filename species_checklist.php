<?php
require_once 'db_connect.php';

// 1. Fetch Hero Section (Dynamic)
try {
    $stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = 'species_hero'");
    $stmt->execute();
    $hero = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $hero = [];
}

// 2. Fetch Species List
try {
    $stmt = $pdo->query("SELECT * FROM species ORDER BY sort_order ASC");
    $species_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $species_list = [];
}

include 'header.php';
?>

<section class="relative h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <?php if(!empty($hero['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($hero['image_url']); ?>"
                alt="<?php echo htmlspecialchars($hero['title'] ?? 'Wildlife'); ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <img src="https://images.unsplash.com/photo-1547971718-d71680108933?q=80&w=2070&auto=format&fit=crop"
                alt="Sundarban Wildlife" class="w-full h-full object-cover">
        <?php endif; ?>
        
        <div class="absolute inset-0 bg-black" style="opacity: <?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.5'); ?>;"></div>
    </div>

    <div class="relative z-10 text-center text-white px-4">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm mb-4 block">
            <?php echo htmlspecialchars($hero['cta_text'] ?? 'The Inhabitants'); ?>
        </span>
        <h1 class="font-serif text-5xl md:text-7xl font-bold mb-6">
            <?php echo htmlspecialchars($hero['title'] ?? 'Wildlife Checklist'); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light">
            <?php echo htmlspecialchars($hero['subtitle'] ?? 'Discover the diverse flora and fauna that call the Sundarbans home.'); ?>
        </p>
    </div>
</section>

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