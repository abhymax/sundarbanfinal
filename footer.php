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
                                    <span class="font-serif text-2xl font-bold"><?php echo htmlspecialchars($settings['logo_text']); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="text-gray-400 max-w-sm mb-6 leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($section['content'])); ?>
                            </div>
                        <?php else: ?>
                            <h4 class="font-bold text-lg mb-6 text-safari-green-light">
                                <?php echo htmlspecialchars($section['title']); ?></h4>
                            <div class="text-gray-400 space-y-4 footer-links">
                                <?php echo $section['content']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-1 md:col-span-2">
                    <h3 class="font-serif text-3xl font-bold text-white">Sundarban Boat Safari</h3>
                    <p class="text-gray-400 mt-4">Experience the wild with the locals.</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="border-t border-white/10 pt-8 flex justify-between items-center">
            <p class="text-gray-500 text-sm">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>.</p>
        </div>
    </div>
</footer>

<div class="fixed bottom-6 right-6 z-50">
    <a href="https://wa.me/<?php echo str_replace(['+', ' '], '', $settings['whatsapp_number']); ?>" target="_blank"
        class="w-14 h-14 bg-[#25D366] rounded-full shadow-lg flex items-center justify-center text-white hover:scale-110 transition-transform duration-300">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="w-8 h-8">
    </a>
</div>

<div id="bookingModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBooking()"></div>
    
    <div id="bookingSidebar" class="absolute right-0 top-0 h-full w-full md:w-[500px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-2xl font-bold font-serif text-[#2E4622]">Plan Your Trip</h3>
            <button onclick="closeBooking()" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center hover:bg-red-50 text-gray-500 hover:text-red-500">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="flex-1 p-6 overflow-y-auto">
            <div id="form-status" class="hidden p-4 mb-6 rounded-xl text-sm font-medium border"></div>

            <form class="space-y-6" id="booking-form">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Select Package</label>
                    <select name="package" id="modal-package" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                        <option value="Custom Tour">Select a Package</option>
                        <?php foreach ($packages as $pkg): ?>
                            <option value="<?php echo htmlspecialchars($pkg['title']); ?>"><?php echo htmlspecialchars($pkg['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Date</label>
                        <input type="date" name="date" id="modal-date" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Travelers</label>
                        <select name="travelers" id="modal-travelers" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                            <option value="2">2 Adults</option>
                            <option value="3">3 Adults</option>
                            <option value="4">4+ Adults</option>
                            <option value="6">Large Group</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Your Name</label>
                    <input type="text" name="name" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Phone Number</label>
                    <input type="tel" name="phone" required class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1 uppercase">Email</label>
                    <input type="email" name="email" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>

                <?php 
                    $n1 = rand(2, 8); $n2 = rand(2, 8);
                    $_SESSION['captcha_result'] = $n1 + $n2;
                ?>
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200 flex items-center justify-between">
                    <span class="font-bold text-gray-700 text-sm">Solve: <?php echo "$n1 + $n2 = ?"; ?></span>
                    <input type="number" name="captcha" required class="w-20 text-center font-bold border border-gray-300 rounded p-2">
                </div>

                <button type="submit" id="submit-btn" class="w-full bg-[#FFD700] text-black font-bold py-4 rounded-xl hover:bg-yellow-400 transition shadow-lg">
                    Confirm Inquiry
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    console.log("Footer Script Loaded: Initializing Modal Logic...");

    // 1. Define Global Open Function
    function openBooking() {
        console.log("openBooking() called!");
        const modal = document.getElementById('bookingModal');
        const sidebar = document.getElementById('bookingSidebar');
        
        if(modal && sidebar) {
            modal.classList.remove('hidden');
            modal.style.display = 'block'; // Force CSS display
            
            // Slide in animation
            setTimeout(() => {
                sidebar.classList.remove('translate-x-full');
            }, 10);
        } else {
            alert("Error: Booking form not found. Please reload the page.");
        }
    }

    // 2. Define Global Close Function
    function closeBooking() {
        const modal = document.getElementById('bookingModal');
        const sidebar = document.getElementById('bookingSidebar');
        
        if(modal && sidebar) {
            sidebar.classList.add('translate-x-full');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }, 300);
        }
    }

    // 3. Auto-Connect Hero Button on Load
    document.addEventListener('DOMContentLoaded', function() {
        const heroBtn = document.getElementById('hero-check-btn');
        
        if(heroBtn) {
            console.log("Hero Button Found. Attaching Event...");
            heroBtn.onclick = function(e) {
                e.preventDefault(); // Stop any default link behavior
                console.log("Hero Button Clicked!");
                
                // Transfer Data
                const hDate = document.getElementById('hero-date')?.value;
                const hGuests = document.getElementById('hero-guests')?.value;
                const hPackage = document.getElementById('hero-package')?.value;

                if(hDate) document.getElementById('modal-date').value = hDate;
                if(hGuests) document.getElementById('modal-travelers').value = hGuests;
                if(hPackage && hPackage !== 'All Packages') document.getElementById('modal-package').value = hPackage;

                openBooking(); // Trigger Modal
            };
        } else {
            console.warn("Hero Button (hero-check-btn) NOT found in DOM.");
        }

        // Form Submit Logic
        const form = document.getElementById('booking-form');
        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('submit-btn');
                const statusBox = document.getElementById('form-status');
                btn.disabled = true;
                btn.innerText = "Sending...";

                const formData = new FormData(form);
                fetch('submit_booking.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    statusBox.classList.remove('hidden');
                    statusBox.style.display = 'block';
                    if(data.status === 'success') {
                        statusBox.innerHTML = '<span class="text-green-600 font-bold">✓ ' + data.message + '</span>';
                        form.reset();
                        setTimeout(() => { closeBooking(); statusBox.style.display='none'; }, 3000);
                    } else {
                        statusBox.innerHTML = '<span class="text-red-600 font-bold">⚠️ ' + data.message + '</span>';
                    }
                })
                .catch(err => alert("Network Error. Try again."))
                .finally(() => { btn.disabled = false; btn.innerText = "Confirm Inquiry"; });
            });
        }
    });
</script>

<script src="script.js"></script> </body>
</html>