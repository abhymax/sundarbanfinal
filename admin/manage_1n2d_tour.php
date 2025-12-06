<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$PAGE_KEY = '1n2d_tour';
$message = '';
$messageType = '';

// --- DELETE HANDLERS ---
if (isset($_GET['delete_timeline'])) {
    $pdo->prepare("DELETE FROM page_timeline WHERE id = ?")->execute([$_GET['delete_timeline']]);
    header("Location: manage_1n2d_tour.php?tab=itinerary&msg=deleted"); exit;
}
// (Keep food/pricing delete handlers same as before...)
if (isset($_GET['delete_food'])) {
    $pdo->prepare("DELETE FROM page_food_menu WHERE id = ?")->execute([$_GET['delete_food']]);
    header("Location: manage_1n2d_tour.php?tab=food&msg=deleted"); exit;
}
if (isset($_GET['delete_pricing'])) {
    $pdo->prepare("DELETE FROM page_pricing WHERE id = ?")->execute([$_GET['delete_pricing']]);
    header("Location: manage_1n2d_tour.php?tab=pricing&msg=deleted"); exit;
}

// --- POST HANDLERS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. Hero, 2. Cards, 3. Experience (Same as before - code collapsed for brevity)
    // ... [Paste the exact Hero, Cards, Experience logic from previous response here] ...
    if (isset($_POST['update_hero'])) {
        try {
            $image_url = $_POST['current_image_url'];
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], '../uploads/1n2d_hero_' . time() . '.' . $ext)) {
                    $image_url = 'uploads/1n2d_hero_' . time() . '.' . $ext;
                }
            }
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=?, cta_text=?, cta_link=?, image_url=?, overlay_opacity=? WHERE section_key=?")->execute([$_POST['hero_title'], $_POST['hero_subtitle'], $_POST['cta_text'], $_POST['cta_link'], $image_url, $_POST['overlay_opacity'], "{$PAGE_KEY}_hero"]);
            $message = "Hero updated!"; $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }

    if (isset($_POST['update_cards'])) {
        try {
           foreach ($_POST['cards'] as $id => $card) {
               $pdo->prepare("UPDATE page_cards SET icon=?, title=?, subtitle=?, color_theme=? WHERE id=?")->execute([$card['icon'], $card['title'], $card['subtitle'], $card['theme'], $id]);
           }
           $message = "Cards updated!"; $messageType = "success";
       } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
   }

   if (isset($_POST['update_experience'])) {
       try {
           $pdo->prepare("UPDATE site_sections SET title=?, subtitle=? WHERE section_key=?")->execute([$_POST['exp_header_title'], $_POST['exp_header_subtitle'], "{$PAGE_KEY}_exp_header"]);
           foreach ($_POST['highlights'] as $id => $h) {
               $image_url = $h['current_image'];
               if (isset($_FILES['highlight_image_'.$id]) && $_FILES['highlight_image_'.$id]['error'] === UPLOAD_ERR_OK) {
                   $ext = strtolower(pathinfo($_FILES['highlight_image_'.$id]['name'], PATHINFO_EXTENSION));
                   if (move_uploaded_file($_FILES['highlight_image_'.$id]['tmp_name'], '../uploads/1n2d_hl_' . $id . '_' . time() . '.' . $ext)) {
                       $image_url = 'uploads/1n2d_hl_' . $id . '_' . time() . '.' . $ext;
                   }
               }
               $bullets = array_filter(array_map('trim', explode("\n", $h['bullets'])));
               $pdo->prepare("UPDATE page_highlights SET title=?, description=?, badge_text=?, list_data=?, image_url=? WHERE id=?")->execute([$h['title'], $h['description'], $h['badge'], json_encode(array_values($bullets)), $image_url, $id]);
           }
           $message = "Experience updated!"; $messageType = "success";
       } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
   }

    // 4. ITINERARY (UPDATED FOR DAY NUMBER)
    if (isset($_POST['update_itinerary_header'])) {
        try {
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=? WHERE section_key=?")->execute([$_POST['itin_title'], $_POST['itin_subtitle'], "{$PAGE_KEY}_itin_header"]);
            $message = "Header updated!"; $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }

    if (isset($_POST['save_event'])) {
         try {
            $image_url = $_POST['current_event_image'] ?? '';
            if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION));
                if (move_uploaded_file($_FILES['event_image']['tmp_name'], '../uploads/1n2d_ev_' . time() . '.' . $ext)) {
                    $image_url = 'uploads/1n2d_ev_' . time() . '.' . $ext;
                }
            }
            
            $day_num = (int)$_POST['day_number']; // Get Day Number

            if (!empty($_POST['event_id'])) {
                $pdo->prepare("UPDATE page_timeline SET title=?, time_range=?, description=?, icon=?, color_theme=?, tags=?, image_url=?, sort_order=?, day_number=? WHERE id=?")
                    ->execute([$_POST['title'], $_POST['time'], $_POST['desc'], $_POST['icon'], $_POST['color'], $_POST['tags'], $image_url, $_POST['sort'], $day_num, $_POST['event_id']]);
                $message = "Event updated!";
            } else {
                $pdo->prepare("INSERT INTO page_timeline (page_key, title, time_range, description, icon, color_theme, tags, image_url, sort_order, day_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
                    ->execute([$PAGE_KEY, $_POST['title'], $_POST['time'], $_POST['desc'], $_POST['icon'], $_POST['color'], $_POST['tags'], $image_url, $_POST['sort'], $day_num]);
                $message = "Event added!";
            }
            $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }

    // 5. FOOD & 6. PRICING (Same as before)
    // ... [Paste previous Food & Pricing logic here] ...
    if (isset($_POST['update_food_header'])) {
        try {
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=? WHERE section_key=?")->execute([$_POST['food_title'], $_POST['food_subtitle'], "{$PAGE_KEY}_food_header"]);
            $message = "Header updated!"; $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }
    if (isset($_POST['save_food'])) {
        try {
            $image_url = $_POST['current_food_image'] ?? '';
            if (isset($_FILES['food_image']) && $_FILES['food_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['food_image']['name'], PATHINFO_EXTENSION));
                if (move_uploaded_file($_FILES['food_image']['tmp_name'], '../uploads/1n2d_food_' . time() . '.' . $ext)) {
                    $image_url = 'uploads/1n2d_food_' . time() . '.' . $ext;
                }
            }
            $items = array_filter(array_map('trim', explode("\n", $_POST['items'])));
            $is_highlighted = isset($_POST['is_highlighted']) ? 1 : 0;
            if (!empty($_POST['food_id'])) {
                $pdo->prepare("UPDATE page_food_menu SET title=?, items=?, image_url=?, is_highlighted=?, sort_order=? WHERE id=?")->execute([$_POST['title'], json_encode(array_values($items)), $image_url, $is_highlighted, $_POST['sort'], $_POST['food_id']]);
                $message = "Menu updated!";
            } else {
                $pdo->prepare("INSERT INTO page_food_menu (page_key, title, items, image_url, is_highlighted, sort_order) VALUES (?, ?, ?, ?, ?, ?)")->execute([$PAGE_KEY, $_POST['title'], json_encode(array_values($items)), $image_url, $is_highlighted, $_POST['sort']]);
                $message = "Menu added!";
            }
            $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }
    
    if (isset($_POST['update_price_header'])) {
        try {
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=? WHERE section_key=?")->execute([$_POST['price_title'], $_POST['price_subtitle'], "{$PAGE_KEY}_price_header"]);
            $message = "Header updated!"; $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }
    if (isset($_POST['save_pricing'])) {
        try {
            $features = array_filter(array_map('trim', explode("\n", $_POST['features'])));
            if (!empty($_POST['price_id'])) {
                $pdo->prepare("UPDATE page_pricing SET title=?, subtitle=?, price=?, price_unit=?, features=?, btn_text=?, style_mode=?, badge_text=?, sort_order=? WHERE id=?")
                    ->execute([$_POST['title'], $_POST['subtitle'], $_POST['price'], $_POST['unit'], json_encode(array_values($features)), $_POST['btn_text'], $_POST['mode'], $_POST['badge'], $_POST['sort'], $_POST['price_id']]);
                $message = "Plan updated!";
            } else {
                $pdo->prepare("INSERT INTO page_pricing (page_key, title, subtitle, price, price_unit, features, btn_text, style_mode, badge_text, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")
                    ->execute([$PAGE_KEY, $_POST['title'], $_POST['subtitle'], $_POST['price'], $_POST['unit'], json_encode(array_values($features)), $_POST['btn_text'], $_POST['mode'], $_POST['badge'], $_POST['sort']]);
                $message = "Plan added!";
            }
            $messageType = "success";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); $messageType = "error"; }
    }
}

// --- FETCH DATA ---
// (Fetch code same as before...)
$hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_hero'")->fetch(PDO::FETCH_ASSOC);
$cards = $pdo->query("SELECT * FROM page_cards WHERE page_key = '$PAGE_KEY' AND section_key = 'quick_info' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$expHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_exp_header'")->fetch(PDO::FETCH_ASSOC);
$highlights = $pdo->query("SELECT * FROM page_highlights WHERE page_key = '$PAGE_KEY' AND section_key = 'experience' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$itinHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_itin_header'")->fetch(PDO::FETCH_ASSOC);
// NEW: Order by day_number, then sort_order
$timeline = $pdo->query("SELECT * FROM page_timeline WHERE page_key = '$PAGE_KEY' ORDER BY day_number ASC, sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$foodHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_food_header'")->fetch(PDO::FETCH_ASSOC);
$foodMenus = $pdo->query("SELECT * FROM page_food_menu WHERE page_key = '$PAGE_KEY' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$priceHeader = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_price_header'")->fetch(PDO::FETCH_ASSOC);
$pricingPlans = $pdo->query("SELECT * FROM page_pricing WHERE page_key = '$PAGE_KEY' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-6xl mx-auto pb-20">
    <div class="flex justify-between items-end mb-8">
        <div><h1 class="text-4xl font-bold text-safari-dark font-serif">1-Night 2-Days Manager</h1></div>
        <a href="../1-night-2-days-tour.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">Preview <span class="material-symbols-outlined text-sm">visibility</span></a>
    </div>
    <?php if ($message || isset($_GET['msg'])): ?>
        <div class="p-4 mb-6 rounded-xl border bg-green-100 text-green-700 border-green-400 flex items-center gap-2"><span class="material-symbols-outlined">check_circle</span><?php echo $message ?: "Action completed successfully."; ?></div>
    <?php endif; ?>
    <div class="flex gap-4 mb-8 border-b border-gray-200 overflow-x-auto">
        <?php 
        $tabs = ['hero'=>'Hero', 'cards'=>'Quick Info', 'experience'=>'Experience', 'itinerary'=>'Itinerary', 'food'=>'Food Menu', 'pricing'=>'Pricing'];
        foreach($tabs as $key => $label) {
            echo "<button onclick=\"switchTab('$key')\" id=\"tab-$key\" class=\"tab-btn pb-3 px-4 font-bold text-gray-500 whitespace-nowrap\">$label</button>";
        }
        ?>
    </div>

    <div id="content-hero" class="tab-content hidden">
         <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="update_hero" value="1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block font-bold text-sm mb-2">Title</label><input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero['title']); ?>" class="w-full border rounded p-3 font-serif"></div>
                    <div><label class="block font-bold text-sm mb-2">Button</label><input type="text" name="cta_text" value="<?php echo htmlspecialchars($hero['cta_text']); ?>" class="w-full border rounded p-3"></div>
                    <div class="md:col-span-2"><label class="block font-bold text-sm mb-2">Subtitle</label><textarea name="hero_subtitle" class="w-full border rounded p-3"><?php echo htmlspecialchars($hero['subtitle']); ?></textarea></div>
                    <div><label class="block font-bold text-sm mb-2">Image</label><input type="file" name="hero_image" class="w-full text-sm"><input type="hidden" name="current_image_url" value="<?php echo $hero['image_url']; ?>"></div>
                    <div><label class="block font-bold text-sm mb-2">Opacity</label><input type="range" name="overlay_opacity" min="0" max="1" step="0.1" value="<?php echo $hero['overlay_opacity']; ?>" class="w-full accent-safari-green"></div>
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save</button></div>
            </form>
        </div>
    </div>
    
    <div id="content-cards" class="tab-content hidden">
         <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" class="space-y-6">
                <input type="hidden" name="update_cards" value="1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php foreach ($cards as $card): ?>
                        <div class="p-4 bg-gray-50 rounded-lg border">
                            <h4 class="font-bold text-xs text-gray-400 mb-2">Card <?php echo $card['sort_order']; ?></h4>
                            <input type="text" name="cards[<?php echo $card['id']; ?>][title]" value="<?php echo htmlspecialchars($card['title']); ?>" class="w-full border rounded p-2 mb-2 font-bold">
                            <input type="text" name="cards[<?php echo $card['id']; ?>][subtitle]" value="<?php echo htmlspecialchars($card['subtitle']); ?>" class="w-full border rounded p-2 mb-2 text-sm">
                            <div class="flex gap-2">
                                <input type="text" name="cards[<?php echo $card['id']; ?>][icon]" value="<?php echo htmlspecialchars($card['icon']); ?>" class="w-1/2 border rounded p-2 text-sm">
                                <select name="cards[<?php echo $card['id']; ?>][theme]" class="w-1/2 border rounded p-2 text-sm">
                                    <?php foreach(['orange','green','blue','purple'] as $c) echo "<option value='$c' ".($card['color_theme']==$c?'selected':'').">$c</option>"; ?>
                                </select>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Cards</button></div>
            </form>
        </div>
    </div>

    <div id="content-experience" class="tab-content hidden">
         <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="update_experience" value="1">
                <div class="mb-6 border-b pb-4">
                    <input type="text" name="exp_header_title" value="<?php echo htmlspecialchars($expHeader['title']); ?>" class="w-full border rounded p-2 mb-2 font-bold text-lg">
                    <textarea name="exp_header_subtitle" class="w-full border rounded p-2"><?php echo htmlspecialchars($expHeader['subtitle']); ?></textarea>
                </div>
                <?php foreach ($highlights as $h): ?>
                    <div class="bg-gray-50 p-4 rounded-lg border mb-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="highlights[<?php echo $h['id']; ?>][title]" value="<?php echo htmlspecialchars($h['title']); ?>" class="w-full border rounded p-2 mb-2 font-bold">
                                <textarea name="highlights[<?php echo $h['id']; ?>][description]" class="w-full border rounded p-2 text-sm" rows="3"><?php echo htmlspecialchars($h['description']); ?></textarea>
                                <textarea name="highlights[<?php echo $h['id']; ?>][bullets]" class="w-full border rounded p-2 text-sm mt-2 font-mono" rows="3"><?php echo implode("\n", json_decode($h['list_data'], true) ?? []); ?></textarea>
                            </div>
                            <div>
                                <input type="text" name="highlights[<?php echo $h['id']; ?>][badge]" value="<?php echo htmlspecialchars($h['badge_text'] ?? ''); ?>" class="w-full border rounded p-2 mb-2 text-sm" placeholder="Badge Text">
                                <div class="flex items-center gap-2">
                                    <img src="<?php echo strpos($h['image_url'], 'http')===0?$h['image_url']:'../'.$h['image_url']; ?>" class="w-16 h-16 object-cover rounded">
                                    <input type="file" name="highlight_image_<?php echo $h['id']; ?>" class="text-xs w-full">
                                    <input type="hidden" name="highlights[<?php echo $h['id']; ?>][current_image]" value="<?php echo $h['image_url']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Experience</button></div>
            </form>
        </div>
    </div>

    <div id="content-itinerary" class="tab-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="POST" class="flex gap-4 items-end">
                <input type="hidden" name="update_itinerary_header" value="1">
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Section Subtitle</label>
                    <input type="text" name="itin_subtitle" value="<?php echo htmlspecialchars($itinHeader['subtitle']); ?>" class="w-full border rounded p-2 text-sm">
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-500 mb-1">Section Title</label>
                    <input type="text" name="itin_title" value="<?php echo htmlspecialchars($itinHeader['title']); ?>" class="w-full border rounded p-2 font-bold text-lg font-serif">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-bold text-sm">Update Header</button>
            </form>
        </div>

        <div class="flex justify-end mb-6">
            <button onclick="openEventModal()" class="bg-safari-green text-white px-6 py-2 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:bg-green-800 transition">
                <span class="material-symbols-outlined">add</span> Add Time Slot
            </button>
        </div>

        <div class="space-y-4">
            <?php foreach ($timeline as $event): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 flex flex-col md:flex-row gap-4 items-center">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white shrink-0 bg-<?php echo $event['color_theme']; ?>-500" style="background-color: var(--color-<?php echo $event['color_theme']; ?>, gray);">
                        <span class="material-symbols-outlined"><?php echo $event['icon']; ?></span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-bold uppercase text-white bg-gray-800 px-2 py-0.5 rounded">Day <?php echo $event['day_number']; ?></span>
                            
                            <span class="text-xs font-bold uppercase text-<?php echo $event['color_theme']; ?>-600 bg-<?php echo $event['color_theme']; ?>-50 px-2 py-0.5 rounded"><?php echo $event['time_range']; ?></span>
                            <span class="text-xs text-gray-400">Order: <?php echo $event['sort_order']; ?></span>
                        </div>
                        <h3 class="font-bold text-gray-800"><?php echo htmlspecialchars($event['title']); ?></h3>
                    </div>
                    <div class="flex gap-2">
                        <button onclick='editEvent(<?php echo json_encode($event); ?>)' class="p-2 text-blue-600 hover:bg-blue-50 rounded"><span class="material-symbols-outlined">edit</span></button>
                        <a href="?delete_timeline=<?php echo $event['id']; ?>" onclick="return confirm('Delete?')" class="p-2 text-red-600 hover:bg-red-50 rounded"><span class="material-symbols-outlined">delete</span></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div id="content-food" class="tab-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="POST" class="flex gap-4 items-end">
                <input type="hidden" name="update_food_header" value="1">
                <div class="flex-1"><label class="block text-xs font-bold text-gray-500 mb-1">Subtitle</label><input type="text" name="food_subtitle" value="<?php echo htmlspecialchars($foodHeader['subtitle']); ?>" class="w-full border rounded p-2 text-sm"></div>
                <div class="flex-1"><label class="block text-xs font-bold text-gray-500 mb-1">Title</label><input type="text" name="food_title" value="<?php echo htmlspecialchars($foodHeader['title']); ?>" class="w-full border rounded p-2 font-bold text-lg font-serif"></div>
                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg font-bold text-sm">Update Header</button>
            </form>
        </div>
        <div class="flex justify-end mb-6"><button onclick="openFoodModal()" class="bg-safari-green text-white px-6 py-2 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:bg-green-800 transition"><span class="material-symbols-outlined">restaurant_menu</span> Add Category</button></div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($foodMenus as $menu): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden relative group">
                    <div class="h-32 bg-gray-200 relative"><img src="<?php echo strpos($menu['image_url'], 'http')===0?$menu['image_url']:'../'.$menu['image_url']; ?>" class="w-full h-full object-cover"></div>
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($menu['title']); ?></h3>
                        <div class="text-xs text-gray-500 mb-4 line-clamp-3"><?php echo implode(", ", json_decode($menu['items'], true) ?? []); ?></div>
                        <div class="flex gap-2 border-t pt-3">
                            <button onclick='editFood(<?php echo json_encode($menu); ?>)' class="flex-1 py-1 text-blue-600 font-bold text-xs">Edit</button>
                            <a href="?delete_food=<?php echo $menu['id']; ?>" onclick="return confirm('Delete?')" class="flex-1 py-1 text-red-600 font-bold text-xs text-center">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div id="content-pricing" class="tab-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <form method="POST" class="flex gap-4 items-end">
                <input type="hidden" name="update_price_header" value="1">
                <div class="flex-1"><label class="block text-xs font-bold text-gray-500 mb-1">Subtitle</label><input type="text" name="price_subtitle" value="<?php echo htmlspecialchars($priceHeader['subtitle']); ?>" class="w-full border rounded p-2 text-sm"></div>
                <div class="flex-1"><label class="block text-xs font-bold text-gray-500 mb-1">Title</label><input type="text" name="price_title" value="<?php echo htmlspecialchars($priceHeader['title']); ?>" class="w-full border rounded p-2 font-bold text-lg font-serif"></div>
                <button type="submit" class="bg-safari-green hover:bg-green-800 text-white px-4 py-2 rounded-lg font-bold text-sm">Update Header</button>
            </form>
        </div>
        <div class="flex justify-end mb-6"><button onclick="openPriceModal()" class="bg-safari-green text-white px-6 py-2 rounded-xl font-bold flex items-center gap-2 shadow-lg hover:bg-green-800 transition"><span class="material-symbols-outlined">payments</span> Add Plan</button></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($pricingPlans as $plan): ?>
                <div class="rounded-xl border border-gray-200 p-6 <?php echo $plan['style_mode'] == 'dark' ? 'bg-gray-900 text-white' : 'bg-white text-gray-800'; ?>">
                    <div class="flex justify-between"><h3 class="font-bold text-xl"><?php echo htmlspecialchars($plan['title']); ?></h3><?php if($plan['badge_text']): ?><span class="text-xs bg-green-500 text-white px-2 py-1 rounded font-bold"><?php echo htmlspecialchars($plan['badge_text']); ?></span><?php endif; ?></div>
                    <div class="text-3xl font-bold mt-2 text-safari-green">₹<?php echo htmlspecialchars($plan['price']); ?></div>
                    <div class="mt-4 flex gap-2">
                         <button onclick='editPrice(<?php echo json_encode($plan); ?>)' class="flex-1 py-2 text-blue-600 bg-blue-50 rounded font-bold text-xs">Edit</button>
                         <a href="?delete_pricing=<?php echo $plan['id']; ?>" onclick="return confirm('Delete?')" class="flex-1 py-2 text-red-600 bg-red-50 rounded font-bold text-xs text-center">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="eventModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-y-auto max-h-[90vh]">
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="save_event" value="1"><input type="hidden" name="event_id" id="event_id">
                <h3 class="text-xl font-bold text-safari-dark mb-6" id="modalTitle">Timeline Event</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Select Day</label>
                        <select name="day_number" id="event_day" class="w-full border rounded p-2 font-bold bg-gray-50">
                            <option value="1">Day 1</option>
                            <option value="2">Day 2</option>
                            <option value="3">Day 3</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Time</label><input type="text" name="time" id="event_time" placeholder="e.g. 5:00 AM" class="w-full border rounded p-2" required></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Order</label><input type="number" name="sort" id="event_sort" value="10" class="w-full border rounded p-2"></div>
                    </div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Title</label><input type="text" name="title" id="event_title" class="w-full border rounded p-2 font-bold" required></div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Description</label><textarea name="desc" id="event_desc" rows="3" class="w-full border rounded p-2"></textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Icon</label><input type="text" name="icon" id="event_icon" value="schedule" class="w-full border rounded p-2"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Color</label><select name="color" id="event_color" class="w-full border rounded p-2"><option value="orange">Orange</option><option value="green">Green</option><option value="blue">Blue</option><option value="yellow">Yellow</option><option value="gray">Gray</option></select></div>
                    </div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Tags</label><input type="text" name="tags" id="event_tags" class="w-full border rounded p-2"></div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Image</label><input type="file" name="event_image" class="w-full text-sm"><input type="hidden" name="current_event_image" id="current_event_image"></div>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="document.getElementById('eventModal').classList.add('hidden')" class="px-4 py-2 font-bold text-gray-500">Cancel</button>
                    <button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Event</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="foodModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-y-auto max-h-[90vh]">
            <form method="POST" enctype="multipart/form-data" class="p-6">
                <input type="hidden" name="save_food" value="1"><input type="hidden" name="food_id" id="food_id">
                <h3 class="text-xl font-bold text-safari-dark mb-6" id="foodModalTitle">Menu Category</h3>
                <div class="space-y-4">
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Title</label><input type="text" name="title" id="food_title" class="w-full border rounded p-2 font-bold" required></div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Items (One per line)</label><textarea name="items" id="food_items" rows="5" class="w-full border rounded p-2 font-mono text-sm"></textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Image</label><input type="file" name="food_image" class="w-full text-sm"><input type="hidden" name="current_food_image" id="current_food_image"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Order</label><input type="number" name="sort" id="food_sort" value="0" class="w-full border rounded p-2"></div>
                    </div>
                    <div><label><input type="checkbox" name="is_highlighted" id="food_highlight"> Highlighted</label></div>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="document.getElementById('foodModal').classList.add('hidden')" class="px-4 py-2 font-bold text-gray-500">Cancel</button>
                    <button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="priceModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-y-auto max-h-[90vh]">
            <form method="POST" class="p-6">
                <input type="hidden" name="save_pricing" value="1"><input type="hidden" name="price_id" id="price_id">
                <h3 class="text-xl font-bold text-safari-dark mb-6" id="priceModalTitle">Pricing Plan</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Style</label><select name="mode" id="price_mode" class="w-full border rounded p-2"><option value="light">Light (White)</option><option value="dark">Dark (Black)</option></select></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Order</label><input type="number" name="sort" id="price_sort" value="0" class="w-full border rounded p-2"></div>
                    </div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Plan Title</label><input type="text" name="title" id="price_title" class="w-full border rounded p-2 font-bold" required></div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Subtitle</label><input type="text" name="subtitle" id="price_subtitle" class="w-full border rounded p-2 text-sm"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Price</label><input type="text" name="price" id="price_amt" class="w-full border rounded p-2 font-bold" required></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Unit</label><input type="text" name="unit" id="price_unit" placeholder="/person" class="w-full border rounded p-2"></div>
                    </div>
                    <div><label class="block text-xs font-bold text-gray-600 mb-1">Features (One per line)</label><textarea name="features" id="price_features" rows="4" class="w-full border rounded p-2 font-mono text-sm"></textarea></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Button Text</label><input type="text" name="btn_text" id="price_btn" value="Book Now" class="w-full border rounded p-2"></div>
                        <div><label class="block text-xs font-bold text-gray-600 mb-1">Badge</label><input type="text" name="badge" id="price_badge" class="w-full border rounded p-2"></div>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 border-t pt-4">
                    <button type="button" onclick="document.getElementById('priceModal').classList.add('hidden')" class="px-4 py-2 font-bold text-gray-500">Cancel</button>
                    <button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => { el.classList.remove('text-safari-green', 'border-b-4', 'border-safari-green'); el.classList.add('text-gray-500'); });
            document.getElementById('content-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.remove('text-gray-500');
            document.getElementById('tab-' + tab).classList.add('text-safari-green', 'border-b-4', 'border-safari-green');
        }
        const urlParams = new URLSearchParams(window.location.search);
        switchTab(urlParams.get('tab') || 'hero');

        function openEventModal() { document.getElementById('eventModal').classList.remove('hidden'); document.getElementById('event_id').value = ''; document.getElementById('event_day').value = '1'; }
        
        // Updated editEvent to load day_number
        function editEvent(d) { 
            document.getElementById('eventModal').classList.remove('hidden'); 
            document.getElementById('event_id').value = d.id; 
            document.getElementById('event_title').value = d.title; 
            document.getElementById('event_time').value = d.time_range; 
            document.getElementById('event_desc').value = d.description; 
            document.getElementById('event_icon').value = d.icon; 
            document.getElementById('event_color').value = d.color_theme; 
            document.getElementById('event_tags').value = d.tags; 
            document.getElementById('current_event_image').value = d.image_url; 
            document.getElementById('event_sort').value = d.sort_order;
            
            // Set Day if available, else default 1
            document.getElementById('event_day').value = d.day_number || 1; 
        }
        // ... (keep other JS functions) ...
        function openFoodModal() { document.getElementById('foodModal').classList.remove('hidden'); document.getElementById('food_id').value = ''; }
        function editFood(d) { document.getElementById('foodModal').classList.remove('hidden'); document.getElementById('food_id').value = d.id; document.getElementById('food_title').value = d.title; try{document.getElementById('food_items').value=JSON.parse(d.items).join('\n');}catch(e){} document.getElementById('food_sort').value = d.sort_order; document.getElementById('current_food_image').value = d.image_url; document.getElementById('food_highlight').checked = (d.is_highlighted == 1); }

        function openPriceModal() { document.getElementById('priceModal').classList.remove('hidden'); document.getElementById('price_id').value = ''; }
        function editPrice(d) { document.getElementById('priceModal').classList.remove('hidden'); document.getElementById('price_id').value = d.id; document.getElementById('price_title').value = d.title; document.getElementById('price_subtitle').value = d.subtitle; document.getElementById('price_amt').value = d.price; document.getElementById('price_unit').value = d.price_unit; try{document.getElementById('price_features').value=JSON.parse(d.features).join('\n');}catch(e){} document.getElementById('price_btn').value = d.btn_text; document.getElementById('price_mode').value = d.style_mode; document.getElementById('price_badge').value = d.badge_text; document.getElementById('price_sort').value = d.sort_order; }
    </script>
</div>
<?php echo "</main></div></body></html>"; ?>