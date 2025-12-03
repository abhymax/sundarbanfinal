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

<footer class="bg-[#051105] text-white relative pb-10 pt-40 border-t-4 border-[#2E4622] font-sans">
    <div class="max-w-7xl mx-auto px-6 relative z-20">
        
        <div class="bg-[#0F1F15] backdrop-blur-md border border-green-900/50 rounded-none md:rounded-2xl p-8 mb-20 -mt-52 flex flex-col md:flex-row items-center justify-between gap-6 shadow-2xl relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#FFD700]/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            <div class="text-center md:text-left relative z-10">
                <h3 class="text-3xl font-bold text-white mb-2 tracking-tight">Ready for the Jungle?</h3>
                <p class="text-gray-400 text-base">Secure your spot in the heart of the delta today.</p>
            </div>
            <button onclick="openBooking()" class="relative z-10 bg-[#FFD700] hover:bg-yellow-400 text-black font-bold px-8 py-4 rounded-lg transition transform hover:-translate-y-1 shadow-lg flex items-center gap-2 uppercase tracking-wider text-sm">
                Start Journey <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16 border-b border-white/10 pb-12">
            
            <?php if (!empty($footer_sections)): ?>
                <?php foreach ($footer_sections as $section): ?>
                    
                    <?php if ($section['type'] == 'text'): ?>
                        <div class="space-y-6">
                            <div class="flex items-center gap-3">
                                <?php if (!empty($settings['logo_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>" class="h-14 w-auto object-contain opacity-90">
                                <?php else: ?>
                                    <span class="font-bold text-2xl text-white tracking-tighter">
                                        <?php echo htmlspecialchars($settings['logo_text']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="text-gray-400 text-base leading-relaxed">
                                <?php echo nl2br(htmlspecialchars($section['content'])); ?>
                            </div>
                            
                            <div class="flex gap-3">
                                <?php 
                                $socials = [
                                    ['url' => $settings['facebook_url'], 'icon' => 'public', 'color' => 'hover:bg-blue-600'],
                                    ['url' => $settings['instagram_url'], 'icon' => 'photo_camera', 'color' => 'hover:bg-pink-600'],
                                    ['url' => $settings['youtube_url'], 'icon' => 'play_arrow', 'color' => 'hover:bg-red-600']
                                ];
                                foreach($socials as $soc): if(!empty($soc['url'])): ?>
                                    <a href="<?php echo htmlspecialchars($soc['url']); ?>" target="_blank" 
                                       class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:text-white <?php echo $soc['color']; ?> transition-all duration-300">
                                        <span class="material-symbols-outlined text-lg"><?php echo $soc['icon']; ?></span>
                                    </a>
                                <?php endif; endforeach; ?>
                            </div>
                        </div>

                    <?php elseif ($section['type'] == 'links'): ?>
                        <div>
                            <h4 class="text-white font-bold text-sm uppercase tracking-[0.15em] mb-6 border-l-4 border-[#FFD700] pl-3">
                                <?php echo htmlspecialchars($section['title']); ?>
                            </h4>
                            <div class="footer-links text-gray-400 text-base space-y-3 [&_li]:flex [&_li]:items-center [&_a]:flex [&_a]:items-center [&_a]:gap-2 [&_a]:transition-colors [&_a:hover]:text-[#FFD700]">
                                <?php 
                                // Add chevron icon to links automatically
                                $content = $section['content'];
                                $icon = '<span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span>';
                                echo str_replace('<a href', $icon . '<a href', $content); 
                                ?>
                            </div>
                        </div>

                    <?php elseif ($section['type'] == 'contact'): ?>
                        <div>
                            <h4 class="text-white font-bold text-sm uppercase tracking-[0.15em] mb-6 border-l-4 border-[#FFD700] pl-3">
                                <?php echo htmlspecialchars($section['title']); ?>
                            </h4>
                            
                            <div class="space-y-5 text-base text-gray-400">
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[#FFD700] mt-1">location_on</span>
                                    <p class="leading-snug"><?php echo nl2br(htmlspecialchars($settings['address'])); ?></p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[#FFD700]">call</span>
                                    <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>" class="hover:text-white transition"><?php echo htmlspecialchars($settings['phone']); ?></a>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-[#FFD700]">mail</span>
                                    <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="hover:text-white transition"><?php echo htmlspecialchars($settings['email']); ?></a>
                                </div>
                            </div>

                            <div class="mt-6 rounded-lg overflow-hidden h-32 w-full border border-white/10 grayscale hover:grayscale-0 transition duration-500 opacity-80 hover:opacity-100">
                                <?php echo $section['content']; ?>
                            </div>
                        </div>

                    <?php endif; ?>
                <?php endforeach; ?>
            
            <?php else: ?>
                <div class="col-span-4 text-center text-gray-500 py-10">Footer content not loaded. Check database.</div>
            <?php endif; ?>

        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-500 text-sm">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>. All rights reserved.</p>
            
            <div class="flex gap-2 opacity-50 grayscale hover:grayscale-0 transition duration-500">
                 <div class="h-6 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" class="h-3"></div>
                 <div class="h-6 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-3"></div>
                 <div class="h-6 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" class="h-3"></div>
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

<div id="bookingModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
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
                    <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Date</label><input type="date" name="date" id="modal-date" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition"></div>
                    <div><label class="block text-xs font-bold uppercase text-gray-500 mb-1 tracking-wider">Travelers</label><select name="travelers" id="modal-travelers" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:ring-2 focus:ring-green-900 transition"><option>2</option><option>3</option><option>4</option><option>5+</option></select></div>
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
    function openBooking() { const m=document.getElementById('bookingModal'), s=document.getElementById('bookingSidebar'); if(m&&s){ m.classList.remove('hidden'); m.style.display='block'; setTimeout(()=>{s.classList.remove('translate-x-full')},10); } }
    function closeBooking() { const m=document.getElementById('bookingModal'), s=document.getElementById('bookingSidebar'); if(m&&s){ s.classList.add('translate-x-full'); setTimeout(()=>{m.classList.add('hidden');m.style.display='none'},300); } }
    document.addEventListener('DOMContentLoaded', ()=>{
        const f=document.getElementById('booking-form');
        if(f) f.addEventListener('submit', e=>{
            e.preventDefault(); const btn=document.getElementById('submit-btn'), st=document.getElementById('form-status');
            btn.disabled=true; btn.innerHTML='<span class="material-symbols-outlined animate-spin">refresh</span> Sending...';
            fetch('submit_booking.php',{method:'POST',body:new FormData(f)}).then(r=>r.json()).then(d=>{
                st.classList.remove('hidden','bg-green-100','text-green-800','bg-red-100','text-red-800'); st.style.display='block';
                if(d.status==='success'){
                    st.classList.add('bg-green-100','text-green-800'); st.innerHTML=`<div class="flex items-center gap-2"><span class="material-symbols-outlined">check_circle</span> ${d.message}</div>`;
                    f.reset(); setTimeout(()=>{closeBooking();st.style.display='none'},3000);
                } else {
                    st.classList.add('bg-red-100','text-red-800'); st.innerHTML=`<div class="flex items-center gap-2"><span class="material-symbols-outlined">error</span> ${d.message}</div>`;
                }
            }).catch(()=>alert("Error")).finally(()=>{btn.disabled=false;btn.innerHTML='Confirm Inquiry <span class="material-symbols-outlined text-sm">send</span>'});
        });
    });
</script>
<script src="script.js"></script>
</body>
</html>