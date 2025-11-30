<?php
require_once 'db_connect.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: index.php");
    exit;
}

// Fetch Package Details
try {
    $stmt = $pdo->prepare("SELECT * FROM packages WHERE slug = ?");
    $stmt->execute([$slug]);
    $package = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$package) {
        throw new Exception("Package not found");
    }
} catch (Exception $e) {
    header("Location: index.php");
    exit;
}

include 'header.php';
?>

<!-- Hero Section -->
<section class="relative h-[60vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($package['image_url']); ?>"
            alt="<?php echo htmlspecialchars($package['title']); ?>" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
    </div>

    <div class="relative z-10 text-center text-white px-4">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-sm mb-4 block">Tour Package</span>
        <h1 class="font-serif text-5xl md:text-7xl font-bold mb-6"><?php echo htmlspecialchars($package['title']); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light">
            ₹<?php echo number_format($package['price']); ?> / Person
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

<!-- Package Details -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <h2 class="text-3xl font-serif font-bold text-safari-green mb-6">Overview</h2>
                <div class="prose prose-lg text-gray-600 mb-12">
                    <?php echo nl2br(htmlspecialchars($package['description'])); ?>
                </div>

                <h3 class="text-2xl font-serif font-bold text-safari-green mb-6">What's Included</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-12">
                    <?php
                    $features = explode(',', $package['features']);
                    foreach ($features as $feature):
                        ?>
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                            <span class="material-symbols-outlined text-tiger-orange">check_circle</span>
                            <span class="text-gray-700 font-medium"><?php echo htmlspecialchars(trim($feature)); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-gray-50 p-8 rounded-3xl sticky top-32">
                    <h3 class="text-2xl font-serif font-bold text-safari-green mb-6">Book This Tour</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center pb-6 border-b border-gray-200">
                            <span class="text-gray-600">Price per person</span>
                            <span
                                class="text-2xl font-bold text-tiger-orange">₹<?php echo number_format($package['price']); ?></span>
                        </div>

                        <button onclick="openBooking('<?php echo htmlspecialchars($package['title']); ?>')"
                            class="w-full bg-tiger-orange text-black font-bold py-4 rounded-xl hover:bg-orange-400 transition shadow-lg flex items-center justify-center gap-2">
                            <span>Book Now</span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </button>

                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-2">Need help?</p>
                            <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>"
                                class="text-safari-green font-bold hover:text-tiger-orange transition">
                                Call <?php echo htmlspecialchars($settings['phone']); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>