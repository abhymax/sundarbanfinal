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

// Fetch Settings for Phone Number
try {
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $settings = ['phone' => '+91 0000000000'];
}

include 'header.php';
?>

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

    <div class="absolute bottom-0 left-0 w-full leading-none z-20">
        <svg class="relative block w-full h-[60px] md:h-[100px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 1440 120" preserveAspectRatio="none">
            <path
                d="M0,64L48,69.3C96,75,192,85,288,80C384,75,480,53,576,48C672,43,768,53,864,64C960,75,1056,85,1152,80C1248,75,1344,53,1392,42.7L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"
                class="fill-white"></path>
        </svg>
    </div>
</section>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <div class="lg:col-span-2 space-y-16">
                
                <div>
                    <h2 class="text-3xl font-serif font-bold text-safari-green mb-6 flex items-center gap-3">
                        <span class="material-symbols-outlined text-tiger-orange">description</span> Overview
                    </h2>
                    <div class="prose prose-lg text-gray-600 leading-relaxed text-justify">
                        <?php echo nl2br(htmlspecialchars($package['description'])); ?>
                    </div>
                </div>

                <div>
                    <h3 class="text-2xl font-serif font-bold text-safari-green mb-6">Package Highlights</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php
                        $features = explode(',', $package['features']);
                        foreach ($features as $feature):
                            ?>
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:shadow-md transition">
                                <span class="material-symbols-outlined text-tiger-orange">star</span>
                                <span class="text-gray-700 font-bold"><?php echo htmlspecialchars(trim($feature)); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2">
                        <div class="p-8 bg-[#f2f9f0]">
                            <h3 class="text-xl font-bold text-safari-green mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined bg-green-200 p-1 rounded-full text-green-800">check</span> 
                                Inclusions
                            </h3>
                            <ul class="space-y-4">
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-lg shrink-0">directions_car</span>
                                    <span>Pick up & Drop from Canning To Godhkhali Ferrighat by Auto / Tata Magic.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-lg shrink-0">hotel</span>
                                    <span>Accommodation Standard Room Ac / Non Ac.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-lg shrink-0">houseboat</span>
                                    <span>Mechanised well maintained Boat with beds and European Toilet.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-lg shrink-0">restaurant</span>
                                    <span>All major meals: Breakfast, Lunch, Evening tea Snacks, Dinner, Bed tea.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-lg shrink-0">theater_comedy</span>
                                    <span>Evening Cultural Programme.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-700">
                                    <span class="material-symbols-outlined text-green-600 text-lg shrink-0">local_police</span>
                                    <span>Jungle entry fees, guide charges, boat permits & camera permissions.</span>
                                </li>
                            </ul>
                        </div>

                        <div class="p-8 bg-gray-50 border-t md:border-t-0 md:border-l border-gray-200">
                            <h3 class="text-xl font-bold text-gray-500 mb-6 flex items-center gap-2">
                                <span class="material-symbols-outlined bg-red-100 p-1 rounded-full text-red-500">close</span> 
                                Exclusions
                            </h3>
                            <ul class="space-y-4">
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <span class="material-symbols-outlined text-red-400 text-lg shrink-0">no_drinks</span>
                                    <span>Any Hard or Aerated Drinks.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <span class="material-symbols-outlined text-red-400 text-lg shrink-0">videocam_off</span>
                                    <span>Video camera charges.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <span class="material-symbols-outlined text-red-400 text-lg shrink-0">attach_money</span>
                                    <span>Miscellaneous expenses incurred by guests.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <span class="material-symbols-outlined text-red-400 text-lg shrink-0">luggage</span>
                                    <span>Coolie Charge.</span>
                                </li>
                                <li class="flex gap-3 text-sm text-gray-600">
                                    <span class="material-symbols-outlined text-red-400 text-lg shrink-0">public</span>
                                    <span><strong>Non-Indian guests:</strong> Permit fees inside Tiger Reserve payable separately.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-2xl font-serif font-bold text-safari-green mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-tiger-orange">backpack</span> Things to Carry
                    </h3>
                    <div class="bg-safari-green rounded-3xl p-6 md:p-8 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">medication</span>
                                <p class="text-white text-xs font-bold leading-tight">Specific Medicines (Cough, Cold, etc)</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">badge</span>
                                <p class="text-white text-xs font-bold leading-tight">Valid Photo ID Proof</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">flight_takeoff</span>
                                <p class="text-white text-xs font-bold leading-tight">Original Passport (Foreigners)</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">photo_camera</span>
                                <p class="text-white text-xs font-bold leading-tight">Camera / Binoculars</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">shopping_bag</span>
                                <p class="text-white text-xs font-bold leading-tight">Light Baggage</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">do_not_step</span>
                                <p class="text-white text-xs font-bold leading-tight">Comfortable Footwear</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">wb_sunny</span>
                                <p class="text-white text-xs font-bold leading-tight">Sunglasses / Hat / Sun Tan Lotion</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition">
                                <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2">payments</span>
                                <p class="text-white text-xs font-bold leading-tight">Cash (ATM Remote)</p>
                            </div>
                        </div>
                        <p class="text-center text-green-200/80 text-xs mt-4 italic">*Only SBI ATM available at Gosaba</p>
                    </div>
                </div>

                <div>
                     <h3 class="text-2xl font-serif font-bold text-safari-green mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-tiger-orange">child_care</span> Child Policy
                    </h3>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 p-6 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md transition">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm">👶</div>
                            <div>
                                <h4 class="font-bold text-gray-800">1 to 4 Years</h4>
                                <span class="inline-block bg-green-500 text-white text-xs font-bold px-2 py-1 rounded mt-1">FREE</span>
                            </div>
                        </div>
                         <div class="flex-1 bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 p-6 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md transition">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm">👦</div>
                            <div>
                                <h4 class="font-bold text-gray-800">4 to 8 Years</h4>
                                <span class="inline-block bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded mt-1">50% Charge</span>
                            </div>
                        </div>
                         <div class="flex-1 bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 p-6 rounded-2xl flex items-center gap-4 shadow-sm hover:shadow-md transition">
                            <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm">🧑</div>
                            <div>
                                <h4 class="font-bold text-gray-800">Above 8 Years</h4>
                                <span class="inline-block bg-gray-600 text-white text-xs font-bold px-2 py-1 rounded mt-1">Full Charge</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-gray-200 p-8 rounded-3xl sticky top-32 shadow-xl">
                    <h3 class="text-2xl font-serif font-bold text-safari-green mb-6">Book This Tour</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center pb-6 border-b border-gray-100">
                            <span class="text-gray-500 text-sm font-bold uppercase tracking-wide">Starting From</span>
                            <span class="text-3xl font-bold text-tiger-orange font-serif">₹<?php echo number_format($package['price']); ?></span>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-600 mb-1"><strong>Duration:</strong> <?php echo htmlspecialchars($package['duration']); ?></p>
                            <p class="text-sm text-gray-600"><strong>Availability:</strong> Daily</p>
                        </div>

                        <button onclick="openBooking('<?php echo htmlspecialchars($package['title']); ?>')"
                            class="w-full bg-[#2E4622] text-white font-bold py-4 rounded-xl hover:bg-[#1a2e1a] transition shadow-lg flex items-center justify-center gap-2 group">
                            <span>Book Now</span>
                            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </button>

                        <div class="text-center pt-4">
                            <p class="text-xs text-gray-400 mb-2 uppercase tracking-widest font-bold">Have Questions?</p>
                            <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>"
                                class="text-lg font-bold text-gray-800 hover:text-tiger-orange transition flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-tiger-orange">call</span>
                                <?php echo htmlspecialchars($settings['phone']); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'footer.php'; ?>