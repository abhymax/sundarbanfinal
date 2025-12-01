<?php
// Fetch Footer Sections
try {
    $stmt = $pdo->query("SELECT * FROM footer_sections ORDER BY sort_order ASC");
    $footer_sections = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $footer_sections = [];
}
?>

<footer class="bg-[#051105] text-white relative pb-10 pt-16  border-t-4 border-[#2E4622]">
    <div class="max-w-7xl mx-auto px-4 relative z-20">
        
        <div class="bg-[#0F1F15] backdrop-blur-md border border-green-900/50 rounded-2xl p-8 mb-16 -mt-32 flex flex-col md:flex-row items-center justify-between gap-6 shadow-[0_10px_40px_-10px_rgba(255,215,0,0.3)] relative overflow-hidden group transform hover:-translate-y-1 transition duration-500">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#FFD700]/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="text-center md:text-left relative z-10">
                <h3 class="text-2xl md:text-3xl font-serif font-bold text-white mb-2">Ready for the Jungle?</h3>
                <p class="text-gray-300 text-sm">Book your seat in the heart of the delta.</p>
            </div>
            <button onclick="openBooking()" class="relative z-10 bg-gradient-to-r from-[#FFD700] to-[#FFA500] text-black font-bold px-8 py-3 rounded-full hover:shadow-[0_0_20px_rgba(255,215,0,0.5)] transition transform flex items-center gap-2">
                Start Your Journey <span class="material-symbols-outlined">arrow_forward</span>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-12 border-b border-white/10 pb-10">
            <div class="space-y-5">
                <?php if (!empty($settings['logo_url'])): ?>
                    <img src="<?php echo htmlspecialchars($settings['logo_url']); ?>" class="h-16 w-auto object-contain">
                <?php else: ?>
                    <span class="font-serif text-2xl font-bold text-white">Sundarban<br><span class="text-[#FFD700]">Safari</span></span>
                <?php endif; ?>
                <p class="text-gray-400 text-sm leading-relaxed">Govt. certified eco-tourism. Experience the wild responsibly.</p>
                
                <div class="flex gap-4">
                    <a href="<?php echo $settings['facebook_url']; ?>" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#1877F2] transition hover:scale-110"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <a href="<?php echo $settings['instagram_url']; ?>" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-pink-600 transition hover:scale-110"><svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>

            <div>
                <h4 class="text-white font-bold text-lg mb-6 border-b-2 border-[#FFD700] inline-block pb-1 font-serif">Explore</h4>
                <ul class="space-y-3 text-gray-300 text-sm">
                    <li><a href="index.php" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> Home</a></li>
                    <li><a href="about.php" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> About Us</a></li>
                    <li><a href="#packages" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> Packages</a></li>
                    <li><a href="gallery.php" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> Gallery</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-bold text-lg mb-6 border-b-2 border-[#FFD700] inline-block pb-1 font-serif">Legal</h4>
                <ul class="space-y-3 text-gray-300 text-sm">
                    <li><a href="#" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> Terms of Service</a></li>
                    <li><a href="contact.php" class="hover:text-[#FFD700] transition flex items-center gap-2"><span class="material-symbols-outlined text-[#FFD700] text-sm">chevron_right</span> Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold text-lg mb-6 border-b-2 border-[#FFD700] inline-block pb-1 font-serif">Locate Us</h4>
                <div class="rounded-lg overflow-hidden h-32 w-full border border-white/20 shadow-lg relative group">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3684.090762455585!2d88.36389531534905!3d22.575708338548665!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a02770000000001%3A0x0!2zMjLCsDM0JzMyLjYiTiA4OMKwMjEnNTcuOSJF!5e0!3m2!1sen!2sin!4v1633023222534!5m2!1sen!2sin" 
                        width="100%" height="100%" style="border:0; filter: grayscale(100%) invert(92%) contrast(83%);" 
                        allowfullscreen="" loading="lazy" class="group-hover:filter-none transition duration-700">
                    </iframe>
                </div>
                <div class="mt-4 flex items-start gap-3 text-sm text-gray-300">
                    <span class="material-symbols-outlined text-[#FFD700] text-sm mt-1">location_on</span>
                    <p><?php echo nl2br(htmlspecialchars($settings['address'])); ?></p>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-2 border-t border-white/5">
            <p class="text-gray-300 text-sm font-medium tracking-wide">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($settings['site_name']); ?>. All rights reserved.</p>
            
            <div class="flex gap-3 opacity-80 hover:opacity-100 transition">
                 <div class="h-8 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/0/04/Visa.svg" class="h-4"></div>
                 <div class="h-8 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-4"></div>
                 <div class="h-8 bg-white rounded px-2 flex items-center"><img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" class="h-4"></div>
                 <div class="h-8 px-3 bg-white rounded flex items-center text-xs font-bold text-gray-800 tracking-wider border border-gray-300">UPI</div>
            </div>
        </div>
    </div>
</footer>

<div class="fixed bottom-6 left-6 z-[90]">
    <a href="tel:<?php echo htmlspecialchars($settings['call_button_number'] ?? $settings['phone']); ?>" class="w-14 h-14 bg-[#FFD700] rounded-full shadow-[0_0_20px_rgba(255,215,0,0.5)] flex items-center justify-center text-black hover:scale-110 transition-transform border-2 border-white">
        <span class="material-symbols-outlined text-3xl">call</span>
    </a>
</div>
<div class="fixed bottom-6 right-6 z-[90]">
    <a href="https://wa.me/<?php echo str_replace(['+', ' '], '', $settings['whatsapp_number']); ?>" target="_blank" class="w-14 h-14 bg-[#25D366] rounded-full shadow-[0_0_20px_rgba(37,211,102,0.5)] flex items-center justify-center text-white hover:scale-110 transition-transform border-2 border-white">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="w-8 h-8">
    </a>
</div>

<div id="bookingModal" class="fixed inset-0 z-[100] hidden" style="display: none;">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeBooking()"></div>
    <div id="bookingSidebar" class="absolute right-0 top-0 h-full w-full md:w-[500px] bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
        <div class="p-6 border-b flex justify-between items-center bg-gray-50"><h3 class="text-2xl font-bold font-serif text-[#2E4622]">Plan Your Trip</h3><button onclick="closeBooking()" class="w-10 h-10 rounded-full bg-white border flex items-center justify-center hover:text-red-500"><span class="material-symbols-outlined">close</span></button></div>
        <div class="flex-1 p-6 overflow-y-auto"><div id="form-status" class="hidden p-4 mb-4 rounded-lg border"></div><form id="booking-form" class="space-y-5"><div><label class="block text-xs font-bold uppercase text-gray-600 mb-1">Package</label><select name="package" id="modal-package" class="w-full border rounded-lg px-4 py-3"><option value="Custom">Custom</option><?php foreach($packages as $p) echo "<option>{$p['title']}</option>"; ?></select></div><div class="grid grid-cols-2 gap-4"><div><label class="block text-xs font-bold uppercase text-gray-600 mb-1">Date</label><input type="date" name="date" id="modal-date" class="w-full border rounded-lg px-4 py-3"></div><div><label class="block text-xs font-bold uppercase text-gray-600 mb-1">Travelers</label><select name="travelers" id="modal-travelers" class="w-full border rounded-lg px-4 py-3"><option>2</option><option>3</option><option>4+</option></select></div></div><div><label class="block text-xs font-bold uppercase text-gray-600 mb-1">Name</label><input type="text" name="name" required class="w-full border rounded-lg px-4 py-3"></div><div><label class="block text-xs font-bold uppercase text-gray-600 mb-1">Phone</label><input type="tel" name="phone" required class="w-full border rounded-lg px-4 py-3"></div><div><label class="block text-xs font-bold uppercase text-gray-600 mb-1">Email</label><input type="email" name="email" class="w-full border rounded-lg px-4 py-3"></div><?php $n1=rand(2,8); $n2=rand(2,8); $_SESSION['captcha_result']=$n1+$n2; ?><div class="bg-yellow-50 p-3 rounded flex justify-between border border-yellow-200"><span class="font-bold text-sm">Solve: <?php echo "$n1 + $n2 = ?"; ?></span><input type="number" name="captcha" required class="w-16 text-center border rounded p-1"></div><button type="submit" id="submit-btn" class="w-full bg-[#FFD700] font-bold py-4 rounded-xl hover:bg-yellow-400">Confirm Inquiry</button></form></div>
    </div>
</div>

<script>
    function openBooking() { const m=document.getElementById('bookingModal'), s=document.getElementById('bookingSidebar'); if(m&&s){ m.classList.remove('hidden'); m.style.display='block'; setTimeout(()=>{s.classList.remove('translate-x-full')},10); } }
    function closeBooking() { const m=document.getElementById('bookingModal'), s=document.getElementById('bookingSidebar'); if(m&&s){ s.classList.add('translate-x-full'); setTimeout(()=>{m.classList.add('hidden');m.style.display='none'},300); } }
    document.addEventListener('DOMContentLoaded', ()=>{
        const b=document.getElementById('hero-check-btn');
        if(b) b.onclick=(e)=>{e.preventDefault(); 
            ['date','guests','package'].forEach(k=>{
                const v=document.getElementById(`hero-${k}`)?.value;
                const t=document.getElementById(k==='guests'?'modal-travelers':`modal-${k}`);
                if(v&&t)t.value=v;
            });
            openBooking();
        };
        const f=document.getElementById('booking-form');
        if(f) f.addEventListener('submit', e=>{
            e.preventDefault(); const btn=document.getElementById('submit-btn'), st=document.getElementById('form-status');
            btn.disabled=true; btn.innerText="Sending...";
            fetch('submit_booking.php',{method:'POST',body:new FormData(f)}).then(r=>r.json()).then(d=>{
                st.classList.remove('hidden'); st.style.display='block';
                st.innerHTML=d.status==='success'?`<span class="text-green-600">✓ ${d.message}</span>`:`<span class="text-red-600">⚠️ ${d.message}</span>`;
                if(d.status==='success'){f.reset();setTimeout(()=>{closeBooking();st.style.display='none'},3000);}
            }).catch(()=>alert("Error")).finally(()=>{btn.disabled=false;btn.innerText="Confirm Inquiry"});
        });
    });
</script>
<script src="script.js"></script>
</body>
</html>