<?php 
require_once 'db_connect.php';
$pageKey = 'about';

// --- SAFE DATA FETCHING ---
try { $hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_hero'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $hero=[]; }
try { $intro = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_intro'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $intro=[]; }
try { $stats = $pdo->query("SELECT * FROM page_cards WHERE page_key = '$pageKey' AND section_key = 'stats' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $stats=[]; }
try { $missionVision = $pdo->query("SELECT * FROM page_highlights WHERE page_key = '$pageKey' AND section_key = 'mission_vision' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $missionVision=[]; }

include 'header.php'; 
?>

<header class="relative h-[50vh] min-h-[450px] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <?php 
        $videoUrl = $hero['video_url'] ?? '';
        $imageUrl = $hero['image_url'] ?? '';
        
        if (!empty($videoUrl)): 
            // 1. YouTube Link Check
            if (strpos($videoUrl, 'youtube.com') !== false || strpos($videoUrl, 'youtu.be') !== false): 
                $ytId = preg_replace('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/', '$2', $videoUrl);
        ?>
            <iframe class="w-full h-full object-cover pointer-events-none" 
                src="https://www.youtube.com/embed/<?php echo $ytId; ?>?autoplay=1&mute=1&loop=1&playlist=<?php echo $ytId; ?>&controls=0&showinfo=0&rel=0&modestbranding=1" 
                frameborder="0" allow="autoplay; encrypted-media" allowfullscreen>
            </iframe>
        
        <?php else: 
            // 2. Uploaded Video File
            $vidSrc = strpos($videoUrl, 'http') === 0 ? $videoUrl : $videoUrl;
        ?>
            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="<?php echo htmlspecialchars($vidSrc); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
        
        <?php elseif (!empty($imageUrl)): 
            // 3. Image Fallback
            $imgSrc = strpos($imageUrl, 'http') === 0 ? $imageUrl : $imageUrl;
        ?>
            <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="w-full h-full object-cover opacity-90">
        
        <?php else: ?>
            <div class="w-full h-full bg-safari-green"></div>
        <?php endif; ?>
        
        <div class="absolute inset-0 bg-gradient-to-r from-green-900/90 to-green-900/40" style="opacity: <?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.5'); ?>;"></div>
    </div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto mt-10 md:mt-0">
        <span class="text-tiger-orange font-bold tracking-widest uppercase text-xs md:text-sm mb-2 block"><?php echo htmlspecialchars($hero['cta_text'] ?? 'Since 2015'); ?></span>
        <h1 class="text-4xl md:text-6xl font-bold font-serif mb-4 leading-tight"><?php echo htmlspecialchars($hero['title'] ?? 'Guardians of the Delta'); ?></h1>
        <p class="text-lg md:text-xl font-light max-w-2xl mx-auto opacity-90"><?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?></p>
    </div>
</header>

<section class="py-12 md:py-24">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-10 md:gap-16 items-center">
            <div class="w-full md:w-1/2 relative">
                <div class="absolute top-4 left-4 w-full h-full border-2 border-tiger-orange rounded-2xl -z-10 hidden md:block"></div>
                <?php if(!empty($intro['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($intro['image_url']); ?>" class="rounded-2xl shadow-2xl w-full object-cover h-[300px] md:h-[400px]">
                <?php endif; ?>
            </div>
            
            <div class="w-full md:w-1/2 space-y-6">
                <h2 class="text-3xl md:text-4xl font-serif font-bold text-green-900"><?php echo htmlspecialchars($intro['title'] ?? 'Born from the River'); ?></h2>
                <div class="text-gray-600 leading-relaxed space-y-4 text-base md:text-lg">
                    <?php echo nl2br(htmlspecialchars($intro['subtitle'] ?? '')); ?>
                </div>
                
                <?php if(!empty($stats)): ?>
                    <div class="grid grid-cols-2 gap-4 pt-4 md:pt-6">
                        <?php foreach($stats as $stat): 
                            $borderColor = ($stat['color_theme'] ?? '') == 'orange' ? 'border-tiger-orange' : 'border-green-900';
                        ?>
                            <div class="bg-white p-4 rounded-lg shadow-md border-l-4 <?php echo $borderColor; ?>">
                                <span class="text-2xl md:text-3xl font-bold text-green-900 block"><?php echo htmlspecialchars($stat['title'] ?? ''); ?></span>
                                <p class="text-xs md:text-sm text-gray-500"><?php echo htmlspecialchars($stat['subtitle'] ?? ''); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="py-12 md:py-24 bg-[#fcfcfc] relative overflow-hidden">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-green-50/50 -skew-x-12 pointer-events-none hidden md:block"></div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <?php if(!empty($missionVision)): foreach ($missionVision as $index => $item): 
            $isEven = ($index % 2 == 0); // Mission (0) = Image Left, Vision (1) = Image Right
            $imgSrc = !empty($item['image_url']) ? (strpos($item['image_url'], 'http')===0 ? $item['image_url'] : $item['image_url']) : null;
        ?>
            <div class="flex flex-col <?php echo $isEven ? 'md:flex-row' : 'md:flex-row-reverse'; ?> items-center gap-8 md:gap-12 mb-16 md:mb-24 last:mb-0">
                
                <div class="w-full md:w-1/2">
                    <div class="relative group">
                        <div class="absolute inset-0 bg-safari-green rounded-2xl transform <?php echo $isEven ? 'rotate-3' : '-rotate-3'; ?> opacity-20 transition-transform group-hover:rotate-0 hidden md:block"></div>
                        <?php if($imgSrc): ?>
                            <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="<?php echo htmlspecialchars($item['title'] ?? ''); ?>" 
                                class="relative w-full h-[250px] md:h-[400px] object-cover rounded-2xl shadow-xl transform transition duration-500 group-hover:-translate-y-2">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full md:w-1/2 space-y-4 md:space-y-6 text-center <?php echo $isEven ? 'md:text-left' : 'md:text-left'; ?>">
                    
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 md:py-2 rounded-full bg-safari-green border border-tiger-orange text-tiger-orange font-bold uppercase tracking-widest text-[10px] md:text-xs mb-2 md:mb-4 shadow-sm">
                        <span class="material-symbols-outlined text-sm">
                            <?php echo $isEven ? 'verified' : 'rocket_launch'; ?>
                        </span>
                        <?php echo $isEven ? 'Our Purpose' : 'The Future'; ?>
                    </div>

                    <h2 class="text-3xl md:text-5xl font-serif font-bold text-safari-dark"><?php echo htmlspecialchars($item['title'] ?? ''); ?></h2>
                    <p class="text-gray-600 text-base md:text-lg leading-relaxed">
                        <?php echo nl2br(htmlspecialchars($item['description'] ?? '')); ?>
                    </p>
                </div>

            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<?php include 'footer.php'; ?>