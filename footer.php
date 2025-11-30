<?php
// Fetch Footer Sections
try {
    $stmt = $pdo->query("SELECT * FROM footer_sections ORDER BY sort_order ASC");
    $footer_sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $footer_sections = [];
}
?>
<footer class="bg-[#051105] text-white pt-20 pb-10 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <?php if (!empty($footer_sections)): ?>
                <?php foreach ($footer_sections as $section): ?>
                    <div class="<?php echo ($section['type'] == 'text') ? 'col-span-1 md:col-span-2' : ''; ?>">
                        <?php if ($section['type'] == 'text'): ?>
                            <div class="flex items-center gap-3 mb-6">
                                <?php if (!empty($settings['logo_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>"
                                        alt="<?php echo htmlspecialchars($settings['site_name']); ?>"
                                        class="h-12 w-auto object-contain">
                                <?php else: ?>
                                    <div class="w-10 h-10 bg-tiger-orange rounded-full flex items-center justify-center text-black">
                                        <span class="material-symbols-outlined">directions_boat</span>
                                    </div>
                                    <span
                                        class="font-serif text-2xl font-bold"><?php echo htmlspecialchars($settings['logo_text']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="text-gray-400 max-w-sm mb-6 leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($section['content'])); ?>
                            </div>
                            <div class="flex space-x-4">
                                <?php if (!empty($settings['facebook_url']) && $settings['facebook_url'] != '#'): ?>
                                    <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-tiger-orange transition"
                                        href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank"><span
                                            class="material-symbols-outlined">public</span></a>
                                <?php endif; ?>
                                <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-tiger-orange transition"
                                    href="mailto:<?php echo htmlspecialchars($settings['email']); ?>"><span
                                        class="material-symbols-outlined">mail</span></a>
                                <a class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-tiger-orange transition"
                                    href="tel:<?php echo htmlspecialchars($settings['phone']); ?>"><span
                                        class="material-symbols-outlined">call</span></a>
                            </div>
                        <?php else: ?>
                            <h4 class="font-bold text-lg mb-6 text-safari-green-light">
                                <?php echo htmlspecialchars($section['title']); ?></h4>
                            <div class="text-gray-400 space-y-4 footer-links">
                                <?php echo $section['content']; // Assuming HTML content for links ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback Static Content -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-tiger-orange rounded-full flex items-center justify-center text-white">
                            <span class="material-symbols-outlined">directions_boat</span>
                        </div>
                        <span class="font-serif text-3xl font-bold">Sundarban Boat Safari</span>
                    </div>
                    <p class="text-gray-400 max-w-sm mb-6">
                        We are dedicated to sustainable tourism in the Sundarban Biosphere Reserve. Experience nature in its
                        purest form with the locals.
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-sm">© <?php echo date('Y'); ?>
                <?php echo htmlspecialchars($settings['site_name']); ?>. All rights reserved.</p>
            <div class="flex gap-6 text-sm text-gray-500">
                <a class="hover:text-white transition" href="#">Privacy Policy</a>
                <a class="hover:text-white transition" href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<!-- Floating Action Buttons -->
<!-- WhatsApp Button (Bottom Right) -->
<div class="fixed bottom-6 right-6 z-50">
    <a href="https://wa.me/<?php echo str_replace(['+', ' '], '', $settings['whatsapp_number']); ?>" target="_blank"
        class="w-14 h-14 bg-[#25D366] rounded-full shadow-lg flex items-center justify-center text-white hover:scale-110 transition-transform duration-300 group relative">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp" class="w-8 h-8">
        <span
            class="absolute right-full mr-4 bg-white text-gray-800 px-3 py-1 rounded-lg text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap shadow-md">
            <?php echo htmlspecialchars($settings['whatsapp_label'] ?? 'Chat with us'); ?>
        </span>
    </a>
</div>

<!-- Call Button (Bottom Left) -->
<div class="fixed bottom-6 left-6 z-50">
    <a href="tel:<?php echo htmlspecialchars($settings['call_button_number'] ?? $settings['phone']); ?>"
        class="w-14 h-14 bg-tiger-orange rounded-full shadow-lg flex items-center justify-center text-black hover:scale-110 transition-transform duration-300 group relative">
        <span class="material-symbols-outlined text-3xl">call</span>
        <span
            class="absolute left-full ml-4 bg-white text-gray-800 px-3 py-1 rounded-lg text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity duration-300 whitespace-nowrap shadow-md">
            <?php echo htmlspecialchars($settings['call_label'] ?? 'Call Now'); ?>
        </span>
    </a>
</div>

<!-- Booking Modal -->
<div id="bookingModal" class="fixed inset-0 z-[100] hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBooking()"></div>
    <div class="absolute right-0 top-0 h-full w-full md:w-[500px] bg-white shadow-2xl transform transition-transform duration-300 translate-x-full"
        id="bookingSidebar">
        <div class="p-6 h-full overflow-y-auto">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-bold font-serif text-safari-green">Plan Your Trip</h3>
                <button onclick="closeBooking()"
                    class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 transition">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form class="space-y-6" id="bookingForm" onsubmit="submitBooking(event)">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Select Package</label>
                    <select name="package"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange transition">
                        <option value="1 Day Tour">1 Day Sundarban Tour</option>
                        <option value="1 Night 2 Days">1 Night 2 Days Adventure</option>
                        <option value="2 Nights 3 Days">2 Nights 3 Days Safari</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Travel Date</label>
                        <input type="date" name="date" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange transition">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Travelers</label>
                        <select name="travelers"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange transition">
                            <option>2 Adults</option>
                            <option>3 Adults</option>
                            <option>4+ Adults</option>
                            <option>Large Group</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" placeholder="John Doe" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" name="phone" placeholder="+91 98765 43210" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange transition">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" placeholder="john@example.com" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange transition">
                </div>

                <button type="submit"
                    class="w-full bg-tiger-orange text-black font-bold py-4 rounded-xl hover:bg-orange-400 transition shadow-lg flex items-center justify-center gap-2">
                    <span>Confirm Booking Request</span>
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>

                <p class="text-xs text-center text-gray-400">
                    No payment required now. We'll contact you to confirm availability.
                </p>
            </form>
        </div>
    </div>
</div>

<script src="script.js"></script>
<script src="booking.js"></script>
</body>

</html>