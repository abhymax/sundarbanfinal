<?php 
require_once 'db_connect.php';
$pageKey = '2n3d_tour';

// --- SAFE DATA FETCHING ---
try { $hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_hero'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $hero=[]; }
try { $cards = $pdo->query("SELECT * FROM page_cards WHERE page_key = '$pageKey' AND section_key = 'quick_info' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $cards=[]; }
try { $expHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_exp_header'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $expHeader=[]; }
try { $highlights = $pdo->query("SELECT * FROM page_highlights WHERE page_key = '$pageKey' AND section_key = 'experience' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $highlights=[]; }
try { $itinHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_itin_header'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $itinHeader=[]; }
try { $timeline = $pdo->query("SELECT * FROM page_timeline WHERE page_key = '$pageKey' ORDER BY day_number ASC, sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $timeline=[]; }
try { $foodHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_food_header'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $foodHeader=[]; }
try { $foodMenus = $pdo->query("SELECT * FROM page_food_menu WHERE page_key = '$pageKey' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $foodMenus=[]; }
try { $priceHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$pageKey}_price_header'")->fetch(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $priceHeader=[]; }
try { $pricingPlans = $pdo->query("SELECT * FROM page_pricing WHERE page_key = '$pageKey' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC) ?: []; } catch(Exception $e) { $pricingPlans=[]; }

// Group Timeline Events by Day
$day1_events = array_filter($timeline, function($e) { return ($e['day_number'] ?? 1) == 1; });
$day2_events = array_filter($timeline, function($e) { return ($e['day_number'] ?? 1) == 2; });
$day3_events = array_filter($timeline, function($e) { return ($e['day_number'] ?? 1) == 3; });

// Helpers
function getThemeClasses($color) {
    switch ($color) {
        case 'orange': return ['border-orange-500', 'bg-orange-100', 'text-orange-600'];
        case 'green':  return ['border-green-600',  'bg-green-100',  'text-green-600'];
        case 'blue':   return ['border-blue-500',   'bg-blue-100',   'text-blue-600'];
        case 'purple': return ['border-purple-500', 'bg-purple-100', 'text-purple-600'];
        default:       return ['border-gray-500',   'bg-gray-100',   'text-gray-600'];
    }
}
function getItinColorClasses($color) {
    switch ($color) {
        case 'orange': return ['border-orange-500', 'text-orange-600', 'bg-orange-500', 'bg-orange-100'];
        case 'green':  return ['border-green-600',  'text-green-600',  'bg-green-600',  'bg-green-100'];
        case 'blue':   return ['border-blue-600',   'text-blue-600',   'bg-blue-600',   'bg-blue-100'];
        case 'purple': return ['border-purple-600', 'text-purple-600', 'bg-purple-600', 'bg-purple-100'];
        case 'yellow': return ['border-yellow-500', 'text-yellow-600', 'bg-yellow-500', 'bg-yellow-100'];
        default:       return ['border-gray-500',   'text-gray-600',   'bg-gray-500',   'bg-gray-100'];
    }
}

include 'header.php'; 
?>

<header class="relative h-[85vh] min-h-[600px] md:h-screen flex items-center justify-center overflow-hidden mb-8 md:mb-12">
    <div class="absolute inset-0 z-0">
        <?php if(!empty($hero['image_url'])): ?>
            <img src="<?php echo htmlspecialchars($hero['image_url']); ?>" class="w-full h-full object-cover">
        <?php else: ?>
            <div class="w-full h-full bg-gray-900"></div> 
        <?php endif; ?>
        <div class="absolute inset-0 bg-black" style="opacity: <?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.5'); ?>;"></div>
    </div>
    
    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto mt-16 md:mt-0 w-full">
        <span class="inline-block py-1 px-3 rounded-full bg-green-700/40 border border-green-500 text-green-100 text-xs md:text-sm font-bold tracking-widest uppercase mb-4 md:mb-6 backdrop-blur-sm animate-pulse">
            The Ultimate Experience
        </span>
        <h1 class="text-4xl md:text-7xl text-white font-bold mb-4 md:mb-6 hero-text leading-tight drop-shadow-lg">
            <?php echo htmlspecialchars($hero['title'] ?? '2 Nights 3 Days Tour'); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-100 mb-8 md:mb-10 max-w-2xl mx-auto font-light leading-relaxed">
            <?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?>
        </p>
        
        <div class="flex flex-row gap-3 justify-center w-full max-w-md mx-auto">
            <a href="<?php echo htmlspecialchars($hero['cta_link'] ?? '#'); ?>" class="flex-1 bg-white text-green-900 px-2 py-3 md:py-4 rounded-full font-bold hover:bg-gray-100 transition transform hover:-translate-y-1 shadow-xl flex items-center justify-center gap-1 text-xs md:text-base whitespace-nowrap">
                <?php echo htmlspecialchars($hero['cta_text'] ?? 'View Plan'); ?> <span class="material-symbols-outlined text-sm md:text-lg">arrow_downward</span>
            </a>
            <a href="#pricing" class="flex-1 bg-transparent border-2 border-white text-white px-2 py-3 md:py-4 rounded-full font-bold hover:bg-white/10 transition transform hover:-translate-y-1 flex items-center justify-center gap-1 text-xs md:text-base whitespace-nowrap">
                Get Quote <span class="material-symbols-outlined text-sm md:text-lg">currency_rupee</span>
            </a>
        </div>
    </div>
    
    <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce text-white/80 hidden md:block">
        <span class="material-symbols-outlined text-4xl">keyboard_double_arrow_down</span>
    </div>
</header>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 md:-mt-32 relative z-20 mb-12 md:mb-20">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
        <?php if(!empty($cards)): foreach ($cards as $card): $colors = getThemeClasses($card['color_theme']); ?>
            <div class="bg-white p-6 rounded-xl shadow-xl border-b-4 <?php echo $colors[0]; ?> flex flex-row md:flex-col items-center md:text-center gap-4 md:gap-0 transform hover:-translate-y-1 transition duration-300">
                <div class="<?php echo $colors[1]; ?> p-3 rounded-full md:mb-4 shrink-0">
                    <span class="material-symbols-outlined <?php echo $colors[2]; ?> text-2xl md:text-3xl"><?php echo htmlspecialchars($card['icon']); ?></span>
                </div>
                <div>
                    <h3 class="font-bold text-base md:text-lg text-gray-800"><?php echo htmlspecialchars($card['title']); ?></h3>
                    <p class="text-xs md:text-sm text-gray-500 mt-0.5"><?php echo htmlspecialchars($card['subtitle']); ?></p>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<section id="experience" class="py-12 md:py-24 leaf-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 md:mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-green-900 mb-2"><?php echo htmlspecialchars($expHeader['title'] ?? ''); ?></h2>
            <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto"><?php echo htmlspecialchars($expHeader['subtitle'] ?? ''); ?></p>
        </div>
        <?php foreach ($highlights as $index => $h): $isEven = ($index % 2 == 0); $bullets = json_decode($h['list_data'], true); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center mb-16 last:mb-0">
                <div class="<?php echo $isEven ? 'order-2 md:order-1' : 'order-1 md:order-2'; ?> relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r <?php echo $isEven ? 'from-orange-600 to-red-600' : 'from-blue-600 to-cyan-600'; ?> rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                    <img src="<?php echo htmlspecialchars($h['image_url']); ?>" class="relative rounded-2xl shadow-2xl w-full object-cover h-64 md:h-96 transform transition duration-500 hover:scale-[1.01]">
                    <?php if (!empty($h['badge_text'])): ?>
                        <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded text-xs backdrop-blur-md">
                            <?php echo htmlspecialchars($h['badge_text']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="<?php echo $isEven ? 'order-1 md:order-2' : 'order-1'; ?> space-y-4 md:space-y-6">
                    <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                        <span class="<?php echo $isEven ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600'; ?> p-2 rounded-lg material-symbols-outlined text-xl">
                            <?php echo $isEven ? 'campfire' : 'travel_explore'; ?>
                        </span>
                        <?php echo htmlspecialchars($h['title']); ?>
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base"><?php echo htmlspecialchars($h['description']); ?></p>
                    <ul class="space-y-2 md:space-y-3">
                        <?php if($bullets) foreach($bullets as $b): ?>
                            <li class="flex items-center text-gray-700 text-sm md:text-base"><span class="material-symbols-outlined text-green-500 mr-2 text-lg">check_circle</span><?php echo htmlspecialchars($b); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="itinerary" class="py-12 md:py-20 bg-white relative overflow-hidden">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 md:mb-16">
            <span class="text-green-600 font-bold tracking-widest uppercase text-xs md:text-sm"><?php echo htmlspecialchars($itinHeader['subtitle'] ?? ''); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2"><?php echo htmlspecialchars($itinHeader['title'] ?? ''); ?></h2>
        </div>

        <div class="flex justify-center mb-8 md:mb-12 flex-wrap gap-2">
            <button onclick="switchDay(1)" id="tab-day1" class="px-4 md:px-8 py-2 md:py-3 rounded-full font-bold text-xs md:text-base transition-all duration-300 bg-green-900 text-white shadow-md">Day 1: Arrival</button>
            <button onclick="switchDay(2)" id="tab-day2" class="px-4 md:px-8 py-2 md:py-3 rounded-full font-bold text-xs md:text-base transition-all duration-300 text-gray-600 hover:bg-white hover:shadow-sm">Day 2: Deep Jungle</button>
            <button onclick="switchDay(3)" id="tab-day3" class="px-4 md:px-8 py-2 md:py-3 rounded-full font-bold text-xs md:text-base transition-all duration-300 text-gray-600 hover:bg-white hover:shadow-sm">Day 3: Return</button>
        </div>

        <div class="relative min-h-[400px]">
            <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-1 bg-gray-200 transform md:-translate-x-1/2"></div>
            
            <div id="day1-content" class="transition-opacity duration-500 opacity-100">
                <?php foreach ($day1_events as $index => $event): renderEvent($event, $index); endforeach; ?>
            </div>
            <div id="day2-content" class="hidden opacity-0 transition-opacity duration-500">
                <?php foreach ($day2_events as $index => $event): renderEvent($event, $index); endforeach; ?>
            </div>
            <div id="day3-content" class="hidden opacity-0 transition-opacity duration-500">
                <?php foreach ($day3_events as $index => $event): renderEvent($event, $index); endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php 
function renderEvent($event, $index) {
    $isEven = ($index % 2 == 0); 
    $colors = getItinColorClasses($event['color_theme']);
    $imgSrc = !empty($event['image_url']) ? $event['image_url'] : null;
?>
    <div class="relative z-10 flex flex-col md:flex-row gap-4 md:gap-8 mb-10 md:mb-16 items-start md:items-center">
        <div class="w-full md:w-1/2 pl-12 md:pl-0 flex <?php echo $isEven ? 'justify-end md:pr-8 text-left md:text-right' : 'justify-end md:pr-8 hidden md:block'; ?>">
            <?php if ($isEven): ?>
                <div class="bg-gray-50 p-5 md:p-6 rounded-xl shadow-md border-l-4 <?php echo $colors[0]; ?> w-full md:w-4/5 transform hover:-translate-x-1 transition relative">
                    <div class="text-lg md:text-2xl font-bold <?php echo $colors[1]; ?> mb-1 font-heading"><?php echo htmlspecialchars($event['time_range']); ?></div>
                    <h4 class="text-lg md:text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($event['title']); ?></h4>
                    <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($event['description']); ?></p>
                </div>
            <?php elseif ($imgSrc): ?>
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="rounded-xl shadow-lg w-3/4 ml-auto opacity-80 grayscale hover:grayscale-0 transition object-cover h-48 md:h-64 hidden md:block">
            <?php endif; ?>
        </div>
        <div class="absolute left-0 md:left-1/2 transform md:-translate-x-1/2 flex items-center justify-center w-9 h-9 md:w-12 md:h-12 rounded-full <?php echo $colors[2]; ?> text-white shadow-lg z-20 border-4 border-white mt-1 md:mt-0">
            <span class="material-symbols-outlined text-sm md:text-base"><?php echo htmlspecialchars($event['icon']); ?></span>
        </div>
        <div class="w-full md:w-1/2 pl-12 md:pl-8 flex <?php echo !$isEven ? 'justify-start text-left' : 'justify-start hidden md:block'; ?>">
            <?php if (!$isEven): ?>
                <div class="bg-gray-50 p-5 md:p-6 rounded-xl shadow-md border-l-4 <?php echo $colors[0]; ?> w-full md:w-4/5 transform hover:translate-x-1 transition relative">
                    <div class="text-lg md:text-2xl font-bold <?php echo $colors[1]; ?> mb-1 font-heading"><?php echo htmlspecialchars($event['time_range']); ?></div>
                    <h4 class="text-lg md:text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($event['title']); ?></h4>
                    <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($event['description']); ?></p>
                </div>
            <?php elseif ($imgSrc): ?>
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="rounded-xl shadow-lg w-3/4 opacity-80 grayscale hover:grayscale-0 transition object-cover h-48 md:h-64 hidden md:block">
            <?php endif; ?>
        </div>
    </div>
<?php } ?>

<script>
    function switchDay(day) {
        ['day1', 'day2', 'day3'].forEach(id => {
            const content = document.getElementById(id + '-content');
            const btn = document.getElementById('tab-' + id);
            if (id === 'day' + day) {
                content.classList.remove('hidden');
                setTimeout(() => content.classList.remove('opacity-0'), 10);
                btn.classList.add('bg-green-900', 'text-white');
                btn.classList.remove('text-gray-600', 'hover:bg-white', 'hover:shadow-sm');
            } else {
                content.classList.add('opacity-0');
                setTimeout(() => content.classList.add('hidden'), 300);
                btn.classList.remove('bg-green-900', 'text-white');
                btn.classList.add('text-gray-600', 'hover:bg-white', 'hover:shadow-sm');
            }
        });
    }
</script>

<section id="map-section" class="h-[300px] md:h-[500px] w-full relative">
    <div id="map" class="w-full h-full bg-gray-200 flex items-center justify-center"><p class="text-gray-500 animate-pulse">Loading Google Maps...</p></div>
    <div class="absolute bottom-4 left-4 z-10 bg-white p-4 rounded-lg shadow-lg max-w-xs hidden md:block">
        <h4 class="font-bold text-gray-800 mb-2">Route Map</h4>
        <div class="flex items-center gap-2 mb-1"><div class="w-3 h-3 rounded-full bg-red-500"></div><span class="text-xs text-gray-600">Pickup Points</span></div>
        <div class="flex items-center gap-2 mb-1"><div class="w-3 h-3 rounded-full bg-blue-500"></div><span class="text-xs text-gray-600">Boat Journey</span></div>
        <div class="flex items-center gap-2"><span class="material-symbols-outlined text-green-600 text-sm">visibility</span><span class="text-xs text-gray-600">Watch Towers</span></div>
    </div>
</section>

<section id="food" class="py-12 md:py-20 bg-stone-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8 md:mb-12">
            <span class="material-symbols-outlined text-4xl text-orange-500 mb-2">ramen_dining</span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900"><?php echo htmlspecialchars($foodHeader['title'] ?? ''); ?></h2>
            <p class="text-gray-600 mt-2 text-sm md:text-base"><?php echo htmlspecialchars($foodHeader['subtitle'] ?? ''); ?></p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
            <?php if(!empty($foodMenus)) foreach ($foodMenus as $menu): $items = json_decode($menu['items'], true) ?? []; ?>
                <div class="bg-white rounded-xl shadow-md p-5 md:p-6 hover:shadow-xl transition group <?php echo $menu['is_highlighted'] ? 'border-t-4 border-orange-500' : ''; ?>">
                    <h3 class="font-bold text-gray-800 mb-2 border-b pb-2"><?php echo htmlspecialchars($menu['title']); ?></h3>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <?php foreach ($items as $item): ?>
                            <li class="border-b border-dashed pb-1 last:border-0"><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="pricing" class="py-12 md:py-20 leaf-pattern text-white relative">
    <div class="absolute inset-0 bg-green-900/90 z-0"></div>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 md:mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-2"><?php echo htmlspecialchars($priceHeader['title'] ?? ''); ?></h2>
            <p class="text-green-100 text-sm md:text-base"><?php echo htmlspecialchars($priceHeader['subtitle'] ?? ''); ?></p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-4xl mx-auto">
            <?php if(!empty($pricingPlans)) foreach ($pricingPlans as $plan): 
                $isDark = ($plan['style_mode'] === 'dark');
                $features = json_decode($plan['features'], true) ?? [];
                $cardClass = $isDark ? 'bg-gray-900 text-white border border-gray-700' : 'bg-white text-gray-800';
                $priceColor = $isDark ? 'text-orange-500' : 'text-green-700';
                $btnClass = $isDark ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-700 hover:bg-green-800';
            ?>
                <div class="<?php echo $cardClass; ?> rounded-2xl p-6 md:p-8 shadow-2xl transform transition hover:scale-105 relative overflow-hidden">
                    <?php if (!empty($plan['badge_text'])): ?>
                        <div class="absolute top-0 right-0 bg-green-600 text-white px-4 py-1 rounded-bl-lg font-bold text-xs md:text-sm"><?php echo htmlspecialchars($plan['badge_text']); ?></div>
                    <?php endif; ?>
                    <h3 class="text-xl md:text-2xl font-bold mb-2"><?php echo htmlspecialchars($plan['title']); ?></h3>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-xs md:text-sm mb-4 md:mb-6"><?php echo htmlspecialchars($plan['subtitle']); ?></p>
                    <div class="text-4xl md:text-5xl font-bold <?php echo $priceColor; ?> mb-4 md:mb-6">₹<?php echo htmlspecialchars($plan['price']); ?></div>
                    <ul class="space-y-2 md:space-y-3 mb-6 md:mb-8 text-sm md:text-base">
                        <?php foreach ($features as $f): ?>
                            <li class="flex items-center"><span class="material-symbols-outlined <?php echo $isDark ? 'text-orange-500' : 'text-green-500'; ?> mr-2 text-lg">check</span> <?php echo htmlspecialchars($f); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button onclick="openBooking()" class="w-full py-3 md:py-4 <?php echo $btnClass; ?> text-white rounded-xl font-bold transition shadow-lg text-sm md:text-base"><?php echo htmlspecialchars($plan['btn_text']); ?></button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="pt-12 pb-32 md:pb-56 bg-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h3 class="text-lg md:text-xl font-bold text-gray-700 mb-6 md:mb-8">Wildlife Spotting Probability (This Season)</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <div class="flex flex-col items-center"><div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-orange-500 flex items-center justify-center text-lg md:text-xl font-bold text-orange-600 mb-2">25%</div><span class="text-xs md:text-sm font-bold">Royal Bengal Tiger</span></div>
            <div class="flex flex-col items-center"><div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-green-600 flex items-center justify-center text-lg md:text-xl font-bold text-green-700 mb-2">90%</div><span class="text-xs md:text-sm font-bold">Spotted Deer</span></div>
            <div class="flex flex-col items-center"><div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-blue-600 flex items-center justify-center text-lg md:text-xl font-bold text-blue-700 mb-2">85%</div><span class="text-xs md:text-sm font-bold">Estuarine Croc</span></div>
            <div class="flex flex-col items-center"><div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-yellow-500 flex items-center justify-center text-lg md:text-xl font-bold text-yellow-600 mb-2">70%</div><span class="text-xs md:text-sm font-bold">Monitor Lizard</span></div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
    (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
        key: "YOUR_API_KEY",
        v: "weekly",
    });
</script>