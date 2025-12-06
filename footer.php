<?php
// Ensure Settings are available
if (!isset($settings)) {
    require_once 'db_connect.php';
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch Footer Sections
try {
    $stmt = $pdo->query("SELECT * FROM footer_sections ORDER BY sort_order ASC");
    $footer_sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $footer_sections = [];
}
?>

<footer class="bg-[#051105] text-white relative pb-10 pt-64 md:pt-48 border-t-4 border-[#2E4622] font-sans">
    <div class="max-w-7xl mx-auto px-6 relative z-20">
        
        <div class="bg-[#0F1F15] backdrop-blur-md border border-green-900/50 rounded-2xl p-8 mb-16 absolute left-4 right-4 top-0 -mt-48 md:-mt-32 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl overflow-hidden group z-30">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#FFD700]/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            <div class="text-center md:text-left relative z-10">
                <h3 class="text-2xl md:text-3xl font-bold text-white mb-2 font-serif tracking-wide">Ready for the Jungle?</h3>
                <p class="text-gray-400 text-sm md:text-base">Secure your spot in the heart of the delta today.</p>
            </div>
            <button onclick="openBooking()" class="relative z-10 bg-[#FFD700] hover:bg-yellow-400 text-black font-bold px-8 py-3 rounded-lg transition transform hover:-translate-y-1 shadow-lg flex items-center gap-2 uppercase tracking-wider text-sm">
                Start Journey <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </button>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-8 gap-y-12 mb-12 border-b border-white/10 pb-12 pt-10">
            
            <?php if (!empty($footer_sections)): ?>
                <?php foreach ($footer_sections as $section): ?>
                    
                    <?php if ($section['type'] == 'text'): ?>
                        <div class="space-y-6 col-span-2 lg:col-span-1">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($settings['logo_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>" class="h-12 md:h-16 w-auto object-contain">
                                <?php else: ?>
                                    <span class="font-bold text-2xl text-white tracking-tighter font-serif">
                                        <?php echo htmlspecialchars($settings['logo_text']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-gray-400 text-[15px] leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($section['content'])); ?>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <?php if (!empty($settings['facebook_url']) && $settings['facebook_url'] != '#'): ?>
                                    <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank" class="group">
                                        <svg class="w-5 h-5 fill-gray-400 group-hover:fill-[#FFD700] transition-colors" viewBox="0 0 24 24"><path d="M12 2.04C6.5 2.04 2 6.53 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85C10.44 7.34 11.93 5.96 14.22 5.96C15.31 5.96 16.45 6.15 16.45 6.15V8.62H15.19C13.95 8.62 13.56 9.39 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96A10 10 0 0 0 22 12.06C22 6.53 17.5 2.04 12 2.04Z"/></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($settings['instagram_url']) && $settings['instagram_url'] != '#'): ?>
                                    <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" class="group">
                                        <svg class="w-5 h-5 fill-gray-400 group-hover:fill-[#FFD700] transition-colors" viewBox="0 0 24 24"><path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"/></svg>
                                    </a>
                                <?php endif; ?>
                                <?php if (!empty($settings['youtube_url']) && $settings['youtube_url'] != '#'): ?>
                                    <a href="<?php echo htmlspecialchars($settings['youtube_url']); ?>" target="_blank" class="group">
                                        <svg class="w-6 h-6 fill-gray-400 group-hover:fill-[#FFD700] transition-colors" viewBox="0 0 24 24"><path d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.5,18.78 17.18,18.84C15.88,18.91 14.69,18.94 13.59,18.94L12,19C7.81,19 5.2,18.84 4.17,18.56C3.27,18.31 2.69,17.73 2.44,16.83C2.31,16.36 2.22,15.73 2.16,14.93C2.09,14.13 2.06,13.44 2.06,12.84L2,12C2,9.81 2.16,8.2 2.44,7.17C2.69,6.27 3.27,5.69 4.17,5.44C4.64,5.31 5.5,5.22 6.82,5.16C8.12,5.09 9.31,5.06 10.41,5.06L12,5C16.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z"/></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php elseif ($section['type'] == 'links'): ?>
                        <div>
                            <h4 class="text-white font-bold text-sm uppercase tracking-[0.15em] mb-6 border-l-4 border-[#FFD700] pl-3">
                                <?php echo htmlspecialchars($section['title']); ?>
                            </h4>
                            <div class="footer-links text-gray-400 text-[15px] space-y-3.5 [&_li]:flex [&_li]:items-center [&_a]:flex [&_a]:items-center [&_a]:gap-2 [&_a]:transition-colors [&_a:hover]:text-[#FFD700]">
                                <?php 
                                $content = $section['content'];
                                $icon = '<span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span>';
                                echo str_replace('<a href', $icon . '<a href', $content); 
                                ?>
                            </div>
                        </div>

                    <?php elseif ($section['type'] == 'contact'): ?>
                        <div class="col-span-2 lg:col-span-1">
                            <h4 class="text-white font-bold text-sm uppercase tracking-[0.15em] mb-6 border-l-4 border-[#FFD700] pl-3">
                                <?php echo htmlspecialchars($section['title']); ?>
                            </h4>
                            
                            <div class="space-y-5 text-[15px] text-gray-400">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[#FFD700] mt-1 shrink-0">location_on</span>
                                    <p class="leading-snug"><?php echo nl2br(htmlspecialchars($settings['address'])); ?></p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[#FFD700] shrink-0">call</span>
                                    <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>" class="hover:text-white transition"><?php echo htmlspecialchars($settings['phone']); ?></a>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[#FFD700] shrink-0">mail</span>
                                    <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="hover:text-white transition"><?php echo htmlspecialchars($settings['email']); ?></a>
                                </div>
                            </div>

                            <div class="mt-6 rounded-lg overflow-hidden h-32 w-full border border-white/10 grayscale hover:grayscale-0 transition duration-500 opacity-90 hover:opacity-100 shadow-md">
                                <?php echo $section['content']; ?>
                            </div>
                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>
            
            <?php else: ?>
                <div class="col-span-4 text-center text-gray-500 py-10">Footer content not loaded.</div>
            <?php endif; ?>

        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-2">
            <p class="text-gray-500 text-sm font-medium">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>. All rights reserved.</p>
            
            <div class="flex gap-3 opacity-60 grayscale hover:grayscale-0 transition duration-500">
                 <div class="h-8 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" class="h-4"></div>
                 <div class="h-8 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-4"></div>
                 <div class="h-8 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" class="h-4"></div>
                 <div class="h-8 px-3 bg-white rounded flex items-center text-xs font-bold text-gray-800 border border-gray-300">UPI</div>
            </div>
        </div>
    </div>
</footer>

<div class="fixed bottom-6 left-6 z-[90] flex flex-col gap-3">
    <a href="tel:<?php echo htmlspecialchars($settings['call_button_number'] ?? $settings['phone']); ?>" class="w-12 h-12 bg-[#FFD700] rounded-full shadow-lg flex items-center justify-center text-black hover:scale-110 transition-transform border-2 border-white group relative">
        <span class="material-symbols-outlined text-2xl">call</span>
        <span class="absolute left-full ml-3 bg-black text-white text-xs font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
            <?php echo htmlspecialchars($settings['call_label'] ?? 'Call Now'); ?>
        </span>
    </a>
</div>

<div class="fixed bottom-6 right-6 z-[90]">
    <a href="https://wa.me/<?php echo str_replace(['+', ' '], '', $settings['whatsapp_number']); ?>" target="_blank" class="w-14 h-14 bg-[#25D366] rounded-full shadow-xl flex items-center justify-center text-white hover:scale-110 transition-transform border-2 border-white group relative">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="w-8 h-8">
        <span class="absolute right-full mr-3 bg-black text-white text-xs font-bold px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
            <?php echo htmlspecialchars($settings['whatsapp_label'] ?? 'Chat'); ?>
        </span>
    </a>
</div>

<button id="backToTopBtn" onclick="scrollToTop()" title="Go to top" class="fixed bottom-24 right-7 z-[89] w-12 h-12 bg-white text-[#2E4622] rounded-full shadow-xl flex items-center justify-center border-2 border-[#FFD700] hover:scale-110 transition-transform hidden opacity-0 hover:bg-[#FFD700] hover:text-black">
     <span class="material-symbols-outlined text-2xl">arrow_upward</span>
</button>

<div id="booking-modal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeBooking()"></div>
    <div id="bookingSidebar" class="absolute right-0 top-0 h-full w-full md:w-[450px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <div class="p-6 border-b flex justify-between items-center bg-[#051105] text-white">
            <div>
                <h3 class="text-xl font-bold font-sans tracking-wide uppercase text-[#FFD700]">Plan Your Trip</h3>
                <p class="text-xs text-gray-400">Begin your adventure</p>
            </div>
            <button onclick="closeBooking()" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white"><span class="material-symbols-outlined text-sm">close</span></button>
        </div>
        <div class="flex-1 p-8 overflow-y-auto bg-gray-50">
            <div id="form-status" class="hidden p-4 mb-4 rounded-lg border text-sm"></div>
            
            <form id="booking-form" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Select Package</label>
                    <select name="package" id="modal-package" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 focus:border-green-900 transition bg-white">
                        <option value="Custom">Custom Package</option>
                        <option>1 Day Tour</option>
                        <option>1 Night 2 Days</option>
                        <option>2 Nights 3 Days</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Date</label><input type="date" name="travel_date" id="modal-date" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition"></div>
                    <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Travelers</label><select name="adults" id="modal-travelers" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition"><option>2</option><option>3</option><option>4</option><option>5+</option></select></div>
                </div>
                <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Full Name</label><input type="text" name="name" required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition" placeholder="John Doe"></div>
                <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Phone</label><input type="tel" name="phone" required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition" placeholder="+91..."></div>
                <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Email</label><input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition" placeholder="john@example.com"></div>
                
                <?php $n1=rand(2,8); $n2=rand(2,8); $_SESSION['captcha_result']=$n1+$n2; ?>
                <div class="bg-white border border-gray-200 p-3 rounded-lg flex justify-between items-center shadow-sm">
                    <span class="text-sm font-bold text-gray-600 flex items-center gap-2"><span class="material-symbols-outlined text-green-700 text-lg">security</span> Solve: <?php echo "$n1 + $n2 = ?"; ?></span>
                    <input type="number" name="captcha" required class="w-20 text-center border rounded p-1 bg-gray-50 font-bold focus:ring-1 focus:ring-green-900">
                </div>

                <button type="submit" id="submit-btn" class="w-full bg-[#2E4622] text-white font-bold py-4 rounded-lg hover:bg-[#1a2e1a] transition shadow-lg uppercase tracking-wider text-sm flex justify-center items-center gap-2">
                    Confirm Inquiry <span class="material-symbols-outlined text-sm">send</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Functions for opening/closing modal with new ID
    function openBooking() { const m=document.getElementById('booking-modal'), s=document.getElementById('bookingSidebar'); if(m&&s){ m.classList.remove('hidden'); m.style.display='block'; setTimeout(()=>{s.classList.remove('translate-x-full')},10); } }
    function closeBooking() { const m=document.getElementById('booking-modal'), s=document.getElementById('bookingSidebar'); if(m&&s){ s.classList.add('translate-x-full'); setTimeout(()=>{m.classList.add('hidden');m.style.display='none'},300); } }
    
    // Back to Top Logic
    const backToTopBtn = document.getElementById("backToTopBtn");
    window.onscroll = function() {
        if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
            backToTopBtn.classList.remove("hidden");
            setTimeout(() => {
                backToTopBtn.classList.remove("opacity-0");
            }, 10);
        } else {
            backToTopBtn.classList.add("opacity-0");
            setTimeout(() => {
                backToTopBtn.classList.add("hidden");
            }, 300);
        }
    };
    function scrollToTop() {
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
</script>

<script src="script.js"></script>
</body>
</html>