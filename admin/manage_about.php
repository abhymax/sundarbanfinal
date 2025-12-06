<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$PAGE_KEY = 'about';
$message = '';

// --- POST HANDLER ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. HERO UPDATE
    if (isset($_POST['update_hero'])) {
        try {
            $image_url = $_POST['current_image_url'] ?? '';
            $video_url = $_POST['video_url'] ?? ''; // Current text value

            // --- HANDLE IMAGE UPLOAD ---
            if (!empty($_FILES['hero_image']['name'])) {
                if ($_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                    $valid_img = ['jpg', 'jpeg', 'png', 'webp'];
                    if (in_array($ext, $valid_img)) {
                        $newFile = 'about_hero_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['hero_image']['tmp_name'], '../uploads/' . $newFile)) {
                            $image_url = 'uploads/' . $newFile;
                            // **CRITICAL FIX**: If new image uploaded, CLEAR video to ensure image shows
                            if (empty($_FILES['hero_video']['name'])) {
                                $video_url = ''; 
                            }
                        }
                    } else { throw new Exception("Invalid image format."); }
                } else { throw new Exception("Image upload error code: " . $_FILES['hero_image']['error']); }
            }

            // --- HANDLE VIDEO UPLOAD ---
            if (!empty($_FILES['hero_video']['name'])) {
                if ($_FILES['hero_video']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['hero_video']['name'], PATHINFO_EXTENSION));
                    $valid_vid = ['mp4', 'webm'];
                    if (in_array($ext, $valid_vid)) {
                        $newFile = 'about_hero_vid_' . time() . '.' . $ext;
                        if (move_uploaded_file($_FILES['hero_video']['tmp_name'], '../uploads/' . $newFile)) {
                            $video_url = 'uploads/' . $newFile; // Override text link
                        }
                    } else { throw new Exception("Invalid video format (MP4/WebM only)."); }
                } elseif ($_FILES['hero_video']['error'] === UPLOAD_ERR_INI_SIZE) {
                    throw new Exception("Video file is too large. Check PHP upload_max_filesize.");
                } else { 
                    throw new Exception("Video upload error code: " . $_FILES['hero_video']['error']); 
                }
            }

            // Check & Update
            $check = $pdo->query("SELECT count(*) FROM site_sections WHERE section_key='{$PAGE_KEY}_hero'")->fetchColumn();
            if ($check > 0) {
                $pdo->prepare("UPDATE site_sections SET title=?, subtitle=?, cta_text=?, image_url=?, video_url=?, overlay_opacity=? WHERE section_key='{$PAGE_KEY}_hero'")
                    ->execute([$_POST['hero_title'], $_POST['hero_subtitle'], $_POST['badge_text'], $image_url, $video_url, $_POST['overlay_opacity']]);
            } else {
                $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle, cta_text, image_url, video_url, overlay_opacity) VALUES (?, ?, ?, ?, ?, ?, ?)")
                    ->execute(["{$PAGE_KEY}_hero", $_POST['hero_title'], $_POST['hero_subtitle'], $_POST['badge_text'], $image_url, $video_url, $_POST['overlay_opacity']]);
            }
            
            $message = "Hero Updated Successfully!";
        } catch (Exception $e) { $message = "Error: ".$e->getMessage(); }
    }

    // 2. STORY & STATS
    if (isset($_POST['update_story'])) {
        try {
            $image_url = $_POST['current_story_image'] ?? '';
            if (!empty($_FILES['story_image']['name'])) {
                $ext = strtolower(pathinfo($_FILES['story_image']['name'], PATHINFO_EXTENSION));
                $newFile = 'about_story_'.time().'.'.$ext;
                if(move_uploaded_file($_FILES['story_image']['tmp_name'], '../uploads/'.$newFile)) $image_url = 'uploads/'.$newFile;
            }
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=?, image_url=? WHERE section_key='{$PAGE_KEY}_intro'")
                ->execute([$_POST['story_title'], $_POST['story_desc'], $image_url]);

            if(isset($_POST['stats'])) {
                foreach ($_POST['stats'] as $id => $stat) {
                    $pdo->prepare("UPDATE page_cards SET title=?, subtitle=?, color_theme=? WHERE id=?")
                        ->execute([$stat['number'], $stat['label'], $stat['color'], $id]);
                }
            }
            $message = "Story & Stats Updated!";
        } catch (Exception $e) { $message = "Error: ".$e->getMessage(); }
    }

    // 3. MISSION & VISION
    if (isset($_POST['update_mission'])) {
        try {
            if(isset($_POST['mv'])) {
                foreach ($_POST['mv'] as $id => $item) {
                    $image_url = $item['current_image'];
                    if (!empty($_FILES['mv_image_'.$id]['name'])) {
                        $ext = strtolower(pathinfo($_FILES['mv_image_'.$id]['name'], PATHINFO_EXTENSION));
                        $newFile = 'about_mv_'.$id.'_'.time().'.'.$ext;
                        if(move_uploaded_file($_FILES['mv_image_'.$id]['tmp_name'], '../uploads/'.$newFile)) $image_url = 'uploads/'.$newFile;
                    }
                    $pdo->prepare("UPDATE page_highlights SET title=?, description=?, image_url=? WHERE id=?")
                        ->execute([$item['title'], $item['description'], $image_url, $id]);
                }
            }
            $message = "Mission & Vision Updated!";
        } catch (Exception $e) { $message = "Error: ".$e->getMessage(); }
    }
}

// --- FETCH DATA ---
$hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_hero'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'', 'subtitle'=>'', 'image_url'=>'', 'video_url'=>'', 'cta_text'=>'', 'overlay_opacity'=>'0.5'];
$intro = $pdo->query("SELECT * FROM site_sections WHERE section_key = '{$PAGE_KEY}_intro'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'', 'subtitle'=>'', 'image_url'=>''];
$stats = $pdo->query("SELECT * FROM page_cards WHERE page_key = '$PAGE_KEY' AND section_key = 'stats' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
$missionVision = $pdo->query("SELECT * FROM page_highlights WHERE page_key = '$PAGE_KEY' AND section_key = 'mission_vision' ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-5xl mx-auto pb-20">
    <div class="flex justify-between items-end mb-8">
        <div><h1 class="text-4xl font-bold text-safari-dark font-serif">About Page Manager</h1></div>
        <a href="../about.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">Preview <span class="material-symbols-outlined text-sm">visibility</span></a>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border <?php echo strpos($message, 'Error') !== false ? 'bg-red-100 text-red-700 border-red-400' : 'bg-green-100 text-green-700 border-green-400'; ?> flex items-center gap-2">
            <span class="material-symbols-outlined">info</span> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="flex gap-4 mb-8 border-b border-gray-200">
        <button onclick="switchTab('hero')" id="tab-hero" class="tab-btn pb-3 px-4 font-bold text-safari-green border-b-4 border-safari-green">Hero</button>
        <button onclick="switchTab('story')" id="tab-story" class="tab-btn pb-3 px-4 font-bold text-gray-500">Story & Stats</button>
        <button onclick="switchTab('mission')" id="tab-mission" class="tab-btn pb-3 px-4 font-bold text-gray-500">Mission & Vision</button>
    </div>

    <div id="content-hero" class="tab-content">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="update_hero" value="1">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-sm mb-2">Title</label>
                        <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero['title'] ?? ''); ?>" class="w-full border rounded p-3 font-serif">
                    </div>
                    <div>
                        <label class="block font-bold text-sm mb-2">Badge Text</label>
                        <input type="text" name="badge_text" value="<?php echo htmlspecialchars($hero['cta_text'] ?? ''); ?>" class="w-full border rounded p-3" placeholder="e.g. Since 2015">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm mb-2">Subtitle</label>
                        <textarea name="hero_subtitle" class="w-full border rounded p-3"><?php echo htmlspecialchars($hero['subtitle'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 md:col-span-2">
                        <label class="block font-bold text-sm mb-2">Background Video</label>
                        <input type="text" name="video_url" value="<?php echo htmlspecialchars($hero['video_url'] ?? ''); ?>" placeholder="YouTube Link or clear this to use image" class="w-full border rounded p-2 mb-2 text-sm">
                        <label class="block text-xs font-bold text-gray-500 mb-1">OR Upload File (MP4)</label>
                        <input type="file" name="hero_video" accept="video/mp4,video/webm" class="w-full text-sm">
                        <p class="text-xs text-orange-600 mt-1">Note: Uploading a video will override the link.</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300 md:col-span-2">
                        <label class="block font-bold text-sm mb-2">Image (Mobile Fallback / Primary)</label>
                        <div class="flex items-center gap-4">
                            <?php if(!empty($hero['image_url'])): ?>
                                <img src="../<?php echo htmlspecialchars($hero['image_url']); ?>" class="h-16 w-16 object-cover rounded border">
                            <?php endif; ?>
                            <input type="file" name="hero_image" accept="image/*" class="w-full text-sm">
                            <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($hero['image_url'] ?? ''); ?>">
                        </div>
                        <p class="text-xs text-green-600 mt-2"><strong>Tip:</strong> Uploading a new image will clear the Video URL to ensure the image displays.</p>
                    </div>

                    <div>
                        <label class="block font-bold text-sm mb-2">Overlay Opacity</label>
                        <input type="range" name="overlay_opacity" min="0" max="1" step="0.1" value="<?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.5'); ?>" class="w-full accent-safari-green">
                    </div>
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Hero</button></div>
            </form>
        </div>
    </div>

    <div id="content-story" class="tab-content hidden"><div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8"><form method="POST" enctype="multipart/form-data" class="space-y-8"><input type="hidden" name="update_story" value="1"><div><h3 class="text-lg font-bold text-safari-dark mb-4">Our Story</h3><div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="md:col-span-2"><label class="block font-bold text-sm mb-2">Title</label><input type="text" name="story_title" value="<?php echo htmlspecialchars($intro['title'] ?? ''); ?>" class="w-full border rounded p-3 font-serif"></div><div class="md:col-span-2"><label class="block font-bold text-sm mb-2">Description</label><textarea name="story_desc" rows="6" class="w-full border rounded p-3"><?php echo htmlspecialchars($intro['subtitle'] ?? ''); ?></textarea></div><div><label class="block font-bold text-sm mb-2">Side Image</label><input type="file" name="story_image" class="w-full text-sm"><input type="hidden" name="current_story_image" value="<?php echo $intro['image_url'] ?? ''; ?>"></div></div></div><div class="pt-6 border-t border-gray-100"><h3 class="text-lg font-bold text-safari-dark mb-4">Key Statistics</h3><div class="grid grid-cols-2 gap-6"><?php foreach ($stats as $stat): ?><div class="bg-gray-50 p-4 rounded-lg border"><div class="space-y-3"><div><label class="text-xs font-bold text-gray-500">Number</label><input type="text" name="stats[<?php echo $stat['id']; ?>][number]" value="<?php echo htmlspecialchars($stat['title']); ?>" class="w-full border rounded p-2 font-bold"></div><div><label class="text-xs font-bold text-gray-500">Label</label><input type="text" name="stats[<?php echo $stat['id']; ?>][label]" value="<?php echo htmlspecialchars($stat['subtitle']); ?>" class="w-full border rounded p-2"></div><div><label class="text-xs font-bold text-gray-500">Color</label><select name="stats[<?php echo $stat['id']; ?>][color]" class="w-full border rounded p-2"><option value="orange" <?php echo $stat['color_theme']=='orange'?'selected':''; ?>>Orange</option><option value="green" <?php echo $stat['color_theme']=='green'?'selected':''; ?>>Green</option></select></div></div></div><?php endforeach; ?></div></div><div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Story</button></div></form></div></div>
    <div id="content-mission" class="tab-content hidden"><div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8"><form method="POST" enctype="multipart/form-data" class="space-y-8"><input type="hidden" name="update_mission" value="1"><?php foreach ($missionVision as $item): ?><div class="bg-gray-50 p-6 rounded-xl border border-gray-200"><h3 class="text-lg font-bold text-safari-dark mb-4 border-b pb-2"><?php echo htmlspecialchars($item['title']); ?></h3><div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="space-y-4"><input type="hidden" name="mv[<?php echo $item['id']; ?>][title]" value="<?php echo htmlspecialchars($item['title']); ?>"><div><label class="block font-bold text-sm mb-2">Description</label><textarea name="mv[<?php echo $item['id']; ?>][description]" rows="4" class="w-full border rounded p-3"><?php echo htmlspecialchars($item['description']); ?></textarea></div></div><div><label class="block font-bold text-sm mb-2">Image</label><div class="flex gap-4 items-start"><?php if($item['image_url']): ?><img src="<?php echo strpos($item['image_url'], 'http')===0 ? $item['image_url'] : '../'.$item['image_url']; ?>" class="w-24 h-24 object-cover rounded-lg border"><?php endif; ?><div class="flex-1"><input type="file" name="mv_image_<?php echo $item['id']; ?>" class="w-full text-sm"><input type="hidden" name="mv[<?php echo $item['id']; ?>][current_image]" value="<?php echo $item['image_url']; ?>"><p class="text-xs text-gray-500 mt-1">Upload to replace.</p></div></div></div></div></div><?php endforeach; ?><div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Mission & Vision</button></div></form></div></div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('text-safari-green', 'border-b-4', 'border-safari-green');
                el.classList.add('text-gray-500');
            });
            document.getElementById('content-' + tab).classList.remove('hidden');
            const btn = document.getElementById('tab-' + tab);
            btn.classList.remove('text-gray-500');
            btn.classList.add('text-safari-green', 'border-b-4', 'border-safari-green');
        }
    </script>
</div>
<?php echo "</main></div></body></html>"; ?>