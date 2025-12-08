<?php 
require_once 'db_connect.php';
$pageKey = '1n2d_tour';

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

// Render Function
function renderEvent($event, $index) {
    $isEven = ($index % 2 == 0); 
    $colors = getItinColorClasses($event['color_theme']);
    $imgSrc = !empty($event['image_url']) ? $event['image_url'] : null;
    $tags = !empty($event['tags']) ? array_filter(explode(',', $event['tags'])) : [];
    ?>
    <div class="relative z-10 flex flex-col md:flex-row gap-4 md:gap-8 mb-10 md:mb-16 items-start md:items-center">
        <div class="w-full md:w-1/2 pl-12 md:pl-0 flex <?php echo $isEven ? 'justify-end md:pr-8 text-left md:text-right' : 'justify-end md:pr-8 hidden md:block'; ?>">
            <?php if ($isEven): ?>
                <div class="bg-gray-50 p-5 md:p-6 rounded-xl shadow-md border-l-4 <?php echo $colors[0]; ?> w-full md:w-4/5 transform hover:-translate-x-1 transition relative">
                    <div class="text-lg md:text-2xl font-bold <?php echo $colors[1]; ?> mb-1 font-heading"><?php echo htmlspecialchars($event['time_range']); ?></div>
                    <h4 class="text-lg md:text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($event['title']); ?></h4>
                    <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($event['description']); ?></p>
                    <?php if (!empty($tags)): ?>
                        <div class="mt-3 inline-flex flex-wrap gap-2 md:justify-end">
                            <?php foreach($tags as $tag): ?>
                                <span class="text-[10px] md:text-xs font-bold <?php echo $colors[1]; ?> bg-white border border-current px-2 py-1 rounded"><?php echo htmlspecialchars(trim($tag)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
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
                    <?php if (!empty($tags)): ?>
                        <div class="mt-3 inline-flex flex-wrap gap-2">
                            <?php foreach($tags as $tag): ?>
                                <span class="text-[10px] md:text-xs font-bold <?php echo $colors[1]; ?> bg-white border border-current px-2 py-1 rounded"><?php echo htmlspecialchars(trim($tag)); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif ($imgSrc): ?>
                <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="rounded-xl shadow-lg w-3/4 opacity-80 grayscale hover:grayscale-0 transition object-cover h-48 md:h-64 hidden md:block">
            <?php endif; ?>
        </div>
    </div>
    <?php
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
        <span class="inline-block py-1 px-3 rounded-full bg-orange-500/20 border border-orange-400 text-orange-100 text-xs md:text-sm font-bold tracking-widest uppercase mb-4 md:mb-6 backdrop-blur-sm animate-pulse">
            Most Popular Choice
        </span>
        <h1 class="text-4xl md:text-7xl text-white font-bold mb-4 md:mb-6 hero-text leading-tight drop-shadow-lg">
            <?php echo htmlspecialchars($hero['title'] ?? 'Jungle Nights'); ?>
        </h1>
        <p class="text-lg md:text-xl text-gray-100 mb-8 md:mb-10 max-w-2xl mx-auto font-light leading-relaxed">
            <?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?>
        </p>
        
        <div class="flex flex-row gap-3 justify-center w-full max-w-md mx-auto">
            <a href="#itinerary"
                class="flex-1 bg-white text-green-900 px-2 py-3 rounded-full font-bold hover:bg-gray-100 transition transform hover:-translate-y-1 shadow-xl flex items-center justify-center gap-1 text-xs md:text-base whitespace-nowrap">
                <?php echo htmlspecialchars($hero['cta_text'] ?? 'View Plan'); ?> <span class="material-symbols-outlined text-sm md:text-lg">arrow_downward</span>
            </a>
            <a href="#pricing"
                class="flex-1 bg-transparent border-2 border-white text-white px-2 py-3 rounded-full font-bold hover:bg-white/10 transition transform hover:-translate-y-1 flex items-center justify-center gap-1 text-xs md:text-base whitespace-nowrap">
                Get Quote <span class="material-symbols-outlined text-sm md:text-lg">currency_rupee</span>
            </a>
        </div>

        <div class="glass-card rounded-2xl p-4 md:p-6 max-w-4xl mx-auto shadow-2xl border-t border-white/40 hidden md:block mt-10 bg-white/90 backdrop-blur-xl text-left">
            <div class="grid grid-cols-4 gap-4 items-center">
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Date</label>
                    <input id="hero-date" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer placeholder-gray-500" type="date">
                </div>
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-[10px] uppercase text-gray-500 font-bold mb-1 tracking-wider">Guests</label>
                    <select id="hero-guests" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer">
                        <option value="2">2 Travelers</option>
                        <option value="4">4 Travelers</option>
                        <option value="6">6+ Group</option>
                    </select>
                </div>
                <div class="text-left border-r border-gray-300 px-4">
                    <label class="block text-xs uppercase text-gray-500 font-bold mb-1">Package</label>
                    <select id="hero-package" class="bg-transparent text-gray-900 font-bold w-full outline-none cursor-pointer">
                        <option value="1 Day Tour">1 Day Tour</option>
                        <option value="1 Night 2 Days" selected>1 Night 2 Days</option>
                        <option value="2 Nights 3 Days">2 Nights 3 Days</option>
                    </select>
                </div>
                <button id="hero-check-btn" class="bg-safari-green text-white h-12 rounded-xl font-bold hover:bg-green-900 transition flex items-center justify-center gap-2 shadow-lg text-sm">
                    Check Availability
                </button>
            </div>
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

        <?php if(!empty($highlights)): foreach ($highlights as $index => $h): 
            $isEven = ($index % 2 == 0);
            $bullets = json_decode($h['list_data'], true);
            $imgSrc = !empty($h['image_url']) ? $h['image_url'] : '';
        ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center mb-16 last:mb-0">
                <div class="<?php echo $isEven ? 'order-2 md:order-1' : 'order-1 md:order-2'; ?> relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r <?php echo $isEven ? 'from-green-600 to-teal-600' : 'from-blue-600 to-cyan-600'; ?> rounded-2xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                    <img src="<?php echo htmlspecialchars($imgSrc); ?>" 
                         class="relative rounded-2xl shadow-2xl w-full object-cover h-64 md:h-96 transform transition duration-500 hover:scale-[1.01]">
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
                    
                    <?php if (!empty($bullets)): ?>
                        <ul class="space-y-2 md:space-y-3">
                            <?php foreach($bullets as $b): ?>
                                <li class="flex items-center text-gray-700 text-sm md:text-base">
                                    <span class="material-symbols-outlined text-green-500 mr-2 text-lg">check_circle</span>
                                    <?php echo htmlspecialchars($b); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<section id="itinerary" class="py-12 md:py-20 bg-white relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10 md:mb-16">
            <span class="text-green-600 font-bold tracking-widest uppercase text-xs md:text-sm"><?php echo htmlspecialchars($itinHeader['subtitle'] ?? ''); ?></span>
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2"><?php echo htmlspecialchars($itinHeader['title'] ?? ''); ?></h2>
        </div>

        <div class="flex justify-center mb-8 md:mb-12">
            <div class="bg-gray-100 p-1 rounded-full shadow-inner inline-flex">
                <button onclick="switchDay(1)" id="tab-day1" class="px-6 md:px-8 py-2 md:py-3 rounded-full font-bold text-sm md:text-base transition-all duration-300 bg-green-900 text-white shadow-md">Day 1: Arrival</button>
                <button onclick="switchDay(2)" id="tab-day2" class="px-6 md:px-8 py-2 md:py-3 rounded-full font-bold text-sm md:text-base transition-all duration-300 text-gray-600 hover:bg-white hover:shadow-sm">Day 2: Safari</button>
            </div>
        </div>

        <div class="relative min-h-[400px]">
            <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-1 bg-gray-200 transform md:-translate-x-1/2"></div>
            
            <div id="day1-content" class="transition-opacity duration-500 opacity-100">
                <?php foreach ($day1_events as $index => $event): renderEvent($event, $index); endforeach; ?>
            </div>

            <div id="day2-content" class="hidden opacity-0 transition-opacity duration-500">
                <?php foreach ($day2_events as $index => $event): renderEvent($event, $index); endforeach; ?>
            </div>
        </div>
    </div>
</section>

<script>
    function switchDay(day) {
        const day1 = document.getElementById('day1-content');
        const day2 = document.getElementById('day2-content');
        const tab1 = document.getElementById('tab-day1');
        const tab2 = document.getElementById('tab-day2');

        if(day === 1) {
            day1.classList.remove('hidden');
            setTimeout(() => day1.classList.remove('opacity-0'), 10);
            day2.classList.add('opacity-0');
            setTimeout(() => day2.classList.add('hidden'), 300);
            
            tab1.classList.add('bg-green-900', 'text-white', 'shadow-md');
            tab1.classList.remove('text-gray-600', 'hover:bg-white', 'hover:shadow-sm');
            tab2.classList.remove('bg-green-900', 'text-white', 'shadow-md');
            tab2.classList.add('text-gray-600', 'hover:bg-white', 'hover:shadow-sm');
        } else {
            day2.classList.remove('hidden');
            setTimeout(() => day2.classList.remove('opacity-0'), 10);
            day1.classList.add('opacity-0');
            setTimeout(() => day1.classList.add('hidden'), 300);

            tab2.classList.add('bg-green-900', 'text-white', 'shadow-md');
            tab2.classList.remove('text-gray-600', 'hover:bg-white', 'hover:shadow-sm');
            tab1.classList.remove('bg-green-900', 'text-white', 'shadow-md');
            tab1.classList.add('text-gray-600', 'hover:bg-white', 'hover:shadow-sm');
        }
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if(!empty($foodMenus)): foreach ($foodMenus as $menu): 
                $isHighlight = $menu['is_highlighted'];
                $items = json_decode($menu['items'], true) ?? [];
                $imgSrc = !empty($menu['image_url']) ? $menu['image_url'] : '';
            ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition duration-300 group <?php echo $isHighlight ? 'border-t-4 border-orange-500 transform md:-translate-y-2' : ''; ?>">
                    <div class="h-40 md:h-48 overflow-hidden">
                        <img src="<?php echo htmlspecialchars($imgSrc); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-5 md:p-6">
                        <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($menu['title']); ?></h3>
                        <ul class="text-gray-600 space-y-2 text-sm">
                            <?php foreach ($items as $item): ?>
                                <li class="border-b border-dashed pb-1 <?php echo $isHighlight && (stripos($item, 'Fish')!==false || stripos($item, 'Prawn')!==false) ? 'font-bold text-orange-600' : ''; ?>">
                                    <?php echo htmlspecialchars($item); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<section id="guidelines" class="py-16 md:py-24 bg-white border-t border-gray-100">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <div>
            <div class="text-center mb-10">
                <span class="text-safari-green font-bold tracking-widest uppercase text-xs md:text-sm">Essentials</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2 font-serif">Inclusions & Exclusions</h2>
            </div>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200 flex flex-col md:flex-row">
                <div class="p-8 md:p-10 bg-[#f2f9f0] w-full md:w-1/2">
                    <h3 class="text-xl font-bold text-safari-green mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined bg-green-200 p-1.5 rounded-full text-green-800 text-sm">check</span> 
                        What's Included
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex gap-3 text-sm text-gray-700 items-start">
                            <span class="material-symbols-outlined text-green-600 text-lg shrink-0 mt-0.5">directions_car</span>
                            <span>Pick up & Drop from Canning To Godhkhali Ferrighat by Auto / Tata Magic.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-700 items-start">
                            <span class="material-symbols-outlined text-green-600 text-lg shrink-0 mt-0.5">hotel</span>
                            <span>Accommodation Standard Room Ac / Non Ac.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-700 items-start">
                            <span class="material-symbols-outlined text-green-600 text-lg shrink-0 mt-0.5">houseboat</span>
                            <span>Mechanised well maintained Boat with beds and European Toilet.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-700 items-start">
                            <span class="material-symbols-outlined text-green-600 text-lg shrink-0 mt-0.5">restaurant</span>
                            <span>All major meals: Breakfast, Lunch, Evening tea Snacks, Dinner, Bed tea.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-700 items-start">
                            <span class="material-symbols-outlined text-green-600 text-lg shrink-0 mt-0.5">theater_comedy</span>
                            <span>Evening Cultural Programme.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-700 items-start">
                            <span class="material-symbols-outlined text-green-600 text-lg shrink-0 mt-0.5">local_police</span>
                            <span>Jungle entry fees, guide charges, boat permits & camera permissions.</span>
                        </li>
                    </ul>
                </div>

                <div class="p-8 md:p-10 bg-white w-full md:w-1/2 border-t md:border-t-0 md:border-l border-gray-100">
                    <h3 class="text-xl font-bold text-gray-500 mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined bg-red-100 p-1.5 rounded-full text-red-500 text-sm">close</span> 
                        What's Excluded
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex gap-3 text-sm text-gray-600 items-start">
                            <span class="material-symbols-outlined text-red-400 text-lg shrink-0 mt-0.5">no_drinks</span>
                            <span>Any Hard or Aerated Drinks.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-600 items-start">
                            <span class="material-symbols-outlined text-red-400 text-lg shrink-0 mt-0.5">videocam_off</span>
                            <span>Video camera charges.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-600 items-start">
                            <span class="material-symbols-outlined text-red-400 text-lg shrink-0 mt-0.5">attach_money</span>
                            <span>Any miscellaneous expenses incurred by the guests.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-600 items-start">
                            <span class="material-symbols-outlined text-red-400 text-lg shrink-0 mt-0.5">luggage</span>
                            <span>Coolie Charge.</span>
                        </li>
                        <li class="flex gap-3 text-sm text-gray-600 items-start">
                            <span class="material-symbols-outlined text-red-400 text-lg shrink-0 mt-0.5">public</span>
                            <span><strong>Non-Indian guests:</strong> Permit fees inside Tiger Reserve payable separately.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-serif font-bold text-safari-green mb-6 flex items-center gap-3">
                <span class="material-symbols-outlined text-tiger-orange text-3xl">backpack</span> Things to Carry
            </h3>
            <div class="bg-safari-green rounded-3xl p-6 md:p-8 relative overflow-hidden shadow-2xl">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 relative z-10">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">medication</span>
                        <p class="text-white text-xs font-bold leading-tight">Specific Medicines</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">badge</span>
                        <p class="text-white text-xs font-bold leading-tight">Valid Photo ID</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">flight_takeoff</span>
                        <p class="text-white text-xs font-bold leading-tight">Passport (Foreigners)</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">photo_camera</span>
                        <p class="text-white text-xs font-bold leading-tight">Camera / Binoculars</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">shopping_bag</span>
                        <p class="text-white text-xs font-bold leading-tight">Light Baggage</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">do_not_step</span>
                        <p class="text-white text-xs font-bold leading-tight">Comfortable Footwear</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">wb_sunny</span>
                        <p class="text-white text-xs font-bold leading-tight">Sunglasses / Hat / Sun Tan Lotion</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-xl text-center border border-white/10 hover:bg-white/20 transition group">
                        <span class="material-symbols-outlined text-3xl text-tiger-orange mb-2 group-hover:scale-110 transition-transform">payments</span>
                        <p class="text-white text-xs font-bold leading-tight">Cash (ATM Remote)</p>
                    </div>
                </div>
                <p class="text-center text-green-200/80 text-xs mt-6 italic">*Only SBI ATM available at Gosaba</p>
            </div>
        </div>

        <div>
             <h3 class="text-2xl font-serif font-bold text-safari-green mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-tiger-orange">child_care</span> Child Policy
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-orange-50 border border-orange-200 p-6 rounded-2xl flex flex-col items-center text-center shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm mb-3">👶</div>
                    <h4 class="font-bold text-gray-800">1 to 4 Years</h4>
                    <span class="inline-block bg-green-600 text-white text-xs font-bold px-3 py-1 rounded-full mt-2">FREE</span>
                </div>
                 <div class="bg-blue-50 border border-blue-200 p-6 rounded-2xl flex flex-col items-center text-center shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm mb-3">👦</div>
                    <h4 class="font-bold text-gray-800">4 to 8 Years</h4>
                    <span class="inline-block bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full mt-2">50% Charge</span>
                </div>
                 <div class="bg-gray-50 border border-gray-200 p-6 rounded-2xl flex flex-col items-center text-center shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-2xl shadow-sm mb-3">🧑</div>
                    <h4 class="font-bold text-gray-800">Above 8 Years</h4>
                    <span class="inline-block bg-gray-700 text-white text-xs font-bold px-3 py-1 rounded-full mt-2">Full Charge</span>
                </div>
            </div>
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
            <?php if(!empty($pricingPlans)): foreach ($pricingPlans as $plan): 
                $isDark = ($plan['style_mode'] === 'dark');
                $features = json_decode($plan['features'], true) ?? [];
                
                // --- COLOR LOGIC UPDATE ---
                // Dark Card: Dark Green BG + Yellow Text/Button
                // Light Card: White BG + Green Text/Button
                $cardClass = $isDark ? 'bg-[#0a260a] text-white border border-[#2E4622]' : 'bg-white text-gray-800 border border-gray-100';
                $priceColor = $isDark ? 'text-yellow-400' : 'text-green-700'; // Changed Orange to Yellow-400
                $iconColor  = $isDark ? 'text-yellow-400' : 'text-green-500'; // Icons now Yellow
                
                // Button: Yellow background needs Black text for contrast
                $btnClass   = $isDark ? 'bg-yellow-500 hover:bg-yellow-400 text-black' : 'bg-green-700 hover:bg-green-800 text-white';
            ?>
                <div class="<?php echo $cardClass; ?> rounded-2xl p-6 md:p-8 shadow-2xl transform transition hover:scale-105 relative overflow-hidden">
                    <?php if (!empty($plan['badge_text'])): ?>
                        <div class="absolute top-0 right-0 bg-green-600 text-white px-4 py-1 rounded-bl-lg font-bold text-xs md:text-sm">
                            <?php echo htmlspecialchars($plan['badge_text']); ?>
                        </div>
                    <?php endif; ?>

                    <h3 class="text-xl md:text-2xl font-bold mb-2"><?php echo htmlspecialchars($plan['title']); ?></h3>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-xs md:text-sm mb-4 md:mb-6">
                        <?php echo htmlspecialchars($plan['subtitle']); ?>
                    </p>
                    
                    <div class="text-4xl md:text-5xl font-bold <?php echo $priceColor; ?> mb-4 md:mb-6">
                        ₹<?php echo htmlspecialchars($plan['price']); ?>
                        <span class="text-base md:text-lg text-gray-400 font-normal"><?php echo htmlspecialchars($plan['price_unit']); ?></span>
                    </div>

                    <ul class="space-y-2 md:space-y-3 mb-6 md:mb-8 text-sm md:text-base">
                        <?php foreach ($features as $feature): ?>
                            <li class="flex items-center">
                                <span class="material-symbols-outlined <?php echo $iconColor; ?> mr-2 text-lg">check</span>
                                <?php echo htmlspecialchars($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <button onclick="openBooking()" class="w-full py-3 md:py-4 <?php echo $btnClass; ?> rounded-xl font-bold transition shadow-lg text-sm md:text-base">
                        <?php echo htmlspecialchars($plan['btn_text']); ?>
                    </button>
                </div>
            <?php endforeach; endif; ?>
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