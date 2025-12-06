<?php 
require_once 'db_connect.php';

// Fetch Settings (Address, Phone, etc.)
try {
    $stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
    $settings = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $settings = ['phone'=>'', 'email'=>'', 'address'=>''];
}

// Fetch Dynamic Page Content
try {
    $hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = 'contact_hero'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'Contact Us', 'subtitle'=>'Get in touch', 'image_url'=>'', 'cta_text'=>'Get in Touch', 'overlay_opacity'=>'0.5'];
    $intro = $pdo->query("SELECT * FROM site_sections WHERE section_key = 'contact_intro'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'Contact Information', 'subtitle'=>'Fill up the form...'];
} catch (Exception $e) {
    $hero = []; $intro = [];
}

include 'header.php'; 

// Generate Math Captcha
$n1 = rand(2, 9);
$n2 = rand(2, 9);
$_SESSION['contact_captcha'] = $n1 + $n2;
?>

<header class="relative h-[50vh] min-h-[450px] bg-[#051105] flex flex-col items-center justify-center text-center text-white overflow-hidden">
    <div class="absolute inset-0 z-0">
        <?php if(!empty($hero['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($hero['image_url']); ?>" class="w-full h-full object-cover opacity-40">
        <?php endif; ?>
        <div class="absolute inset-0 bg-black" style="opacity: <?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.5'); ?>;"></div>
    </div>

    <div class="absolute top-0 left-0 w-64 h-64 bg-tiger-orange/10 rounded-full blur-[80px] -translate-x-1/2 -translate-y-1/2 z-0"></div>
    <div class="absolute bottom-0 right-0 w-64 h-64 bg-safari-green/20 rounded-full blur-[80px] translate-x-1/2 translate-y-1/2 z-0"></div>
    
    <div class="relative z-10 max-w-2xl mx-auto px-4 mt-10 md:mt-0">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-xs md:text-sm mb-3 block"><?php echo htmlspecialchars($hero['cta_text'] ?? 'Get in Touch'); ?></span>
        <h1 class="text-4xl md:text-7xl font-serif font-bold mb-4 md:mb-6"><?php echo htmlspecialchars($hero['title'] ?? 'Contact Us'); ?></h1>
        <p class="text-gray-300 text-base md:text-lg font-light max-w-xl mx-auto leading-relaxed px-4"><?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?></p>
    </div>
</header>

<section class="relative -mt-16 md:-mt-20 pb-12 md:pb-24 px-4 z-20">
    <div class="max-w-6xl mx-auto bg-white rounded-2xl md:rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="w-full md:w-2/5 bg-safari-green text-white p-8 md:p-12 relative overflow-hidden flex flex-col justify-between">
            
            <div class="relative z-10 space-y-10 md:space-y-12">
                <div>
                    <h3 class="text-2xl md:text-3xl font-bold font-serif mb-3 md:mb-4 text-white"><?php echo htmlspecialchars($intro['title'] ?? 'Contact Info'); ?></h3>
                    <p class="text-green-100/80 font-light leading-relaxed text-sm md:text-base"><?php echo htmlspecialchars($intro['subtitle'] ?? ''); ?></p>
                </div>
                    
                <div class="space-y-6 md:space-y-8">
                    <div class="flex items-start gap-4 md:gap-5 group">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-tiger-orange group-hover:bg-tiger-orange group-hover:text-safari-green transition-colors duration-300"><span class="material-symbols-outlined text-lg md:text-xl">call</span></div>
                        <div>
                            <p class="text-[10px] md:text-xs text-green-300/70 uppercase tracking-widest font-bold mb-1">Phone</p>
                            <a href="tel:<?php echo htmlspecialchars($settings['phone']); ?>" class="text-base md:text-lg font-medium hover:text-tiger-orange transition"><?php echo htmlspecialchars($settings['phone']); ?></a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 md:gap-5 group">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-tiger-orange group-hover:bg-tiger-orange group-hover:text-safari-green transition-colors duration-300"><span class="material-symbols-outlined text-lg md:text-xl">mail</span></div>
                        <div>
                            <p class="text-[10px] md:text-xs text-green-300/70 uppercase tracking-widest font-bold mb-1">Email</p>
                            <a href="mailto:<?php echo htmlspecialchars($settings['email']); ?>" class="text-base md:text-lg font-medium hover:text-tiger-orange transition break-all"><?php echo htmlspecialchars($settings['email']); ?></a>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 md:gap-5 group">
                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-full bg-white/10 flex items-center justify-center shrink-0 text-tiger-orange group-hover:bg-tiger-orange group-hover:text-safari-green transition-colors duration-300"><span class="material-symbols-outlined text-lg md:text-xl">location_on</span></div>
                        <div>
                            <p class="text-[10px] md:text-xs text-green-300/70 uppercase tracking-widest font-bold mb-1">Office</p>
                            <p class="text-base md:text-lg font-medium leading-snug"><?php echo nl2br(htmlspecialchars($settings['address'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 md:mt-12 relative z-10">
                <div class="flex gap-4">
                    <?php if(!empty($settings['facebook_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['facebook_url']); ?>" target="_blank" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center hover:bg-tiger-orange hover:border-tiger-orange hover:text-safari-green transition-all duration-300">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(!empty($settings['instagram_url'])): ?>
                    <a href="<?php echo htmlspecialchars($settings['instagram_url']); ?>" target="_blank" class="w-10 h-10 rounded-full border border-white/20 flex items-center justify-center hover:bg-tiger-orange hover:border-tiger-orange hover:text-safari-green transition-all duration-300">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="w-full md:w-3/5 p-6 md:p-16 bg-white">
            <div class="mb-6 md:mb-8">
                <h2 class="text-2xl md:text-3xl font-bold font-serif text-gray-800">Send us a Message</h2>
            </div>

            <div id="form-alert" class="hidden mb-6 p-4 rounded-lg text-sm font-bold border"></div>

            <form id="contact-form" class="space-y-4 md:space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition-all" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" name="phone" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition-all" placeholder="+91...">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Subject</label>
                    <select name="subject" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition-all appearance-none">
                        <option>General Inquiry</option>
                        <option>Booking Request</option>
                        <option>Custom Package Plan</option>
                        <option>Feedback</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Message</label>
                    <textarea name="message" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 h-32 focus:outline-none focus:ring-2 focus:ring-tiger-orange focus:bg-white transition-all resize-none" required></textarea>
                </div>

                <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 flex items-center justify-between">
                    <div class="flex items-center gap-2 text-gray-700 font-bold text-sm">
                        <span class="material-symbols-outlined text-tiger-orange text-lg">security</span>
                        <span>Check: <?php echo "$n1 + $n2 = ?"; ?></span>
                    </div>
                    <input type="number" name="captcha" class="w-20 text-center bg-white border border-gray-300 rounded-lg px-3 py-2 font-bold focus:ring-2 focus:ring-tiger-orange outline-none" placeholder="?" required>
                </div>

                <button type="submit" id="send-btn" class="w-full bg-tiger-orange text-black font-bold py-3 md:py-4 rounded-xl hover:bg-orange-400 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2 text-sm md:text-base">
                    <span>Send Message</span>
                    <span class="material-symbols-outlined text-sm md:text-base">send</span>
                </button>
            </form>
        </div>
    </div>
</section>

<section class="h-[300px] md:h-[450px] w-full grayscale hover:grayscale-0 transition duration-700 border-t border-gray-200">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3684.0693765968293!2d88.3576!3d22.5726!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a027767b19943b3%3A0x7e1d1c1d1d1d1d1d!2sEsplanade%2C%20Kolkata!5e0!3m2!1sen!2sin!4v1620000000000!5m2!1sen!2sin" 
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
    </iframe>
</section>

<script>
document.getElementById('contact-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('send-btn');
    const alertBox = document.getElementById('form-alert');
    const originalText = btn.innerHTML;

    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin mr-2">refresh</span> Sending...';
    btn.classList.add('opacity-75', 'cursor-not-allowed');

    fetch('submit_contact_form.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => response.json())
    .then(data => {
        alertBox.classList.remove('hidden');
        if(data.status === 'success') {
            alertBox.classList.remove('bg-red-50', 'text-red-800', 'border-red-200');
            alertBox.classList.add('bg-green-50', 'text-green-800', 'border-green-200');
            alertBox.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined">check_circle</span> ' + data.message + '</div>';
            this.reset();
            setTimeout(() => window.location.reload(), 2000);
        } else {
            alertBox.classList.remove('bg-green-50', 'text-green-800', 'border-green-200');
            alertBox.classList.add('bg-red-50', 'text-red-800', 'border-red-200');
            alertBox.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined">error</span> ' + data.message + '</div>';
        }
    })
    .catch(err => {
        alertBox.classList.remove('hidden', 'bg-green-50', 'text-green-800');
        alertBox.classList.add('bg-red-50', 'text-red-800', 'border-red-200');
        alertBox.innerHTML = '<div class="flex items-center gap-2"><span class="material-symbols-outlined">wifi_off</span> Connection Error. Please try again.</div>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
    });
});
</script>

<?php include 'footer.php'; ?>