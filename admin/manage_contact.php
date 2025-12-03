<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. HERO
    if (isset($_POST['update_hero'])) {
        try {
            $image_url = $_POST['current_image_url'];
            if (!empty($_FILES['hero_image']['name']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                $newFile = 'contact_hero_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], '../uploads/' . $newFile)) {
                    $image_url = 'uploads/' . $newFile;
                }
            }
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=?, cta_text=?, image_url=?, overlay_opacity=? WHERE section_key='contact_hero'")
                ->execute([$_POST['hero_title'], $_POST['hero_subtitle'], $_POST['badge_text'], $image_url, $_POST['overlay_opacity']]);
            $message = "Hero updated successfully!";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); }
    }

    // 2. CONTENT
    if (isset($_POST['update_content'])) {
        try {
            $pdo->prepare("UPDATE site_sections SET title=?, subtitle=? WHERE section_key='contact_intro'")
                ->execute([$_POST['intro_title'], $_POST['intro_desc']]);
            $message = "Content updated successfully!";
        } catch (Exception $e) { $message = "Error: " . $e->getMessage(); }
    }
}

// Fetch Data
$hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = 'contact_hero'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'', 'subtitle'=>'', 'image_url'=>'', 'cta_text'=>'', 'overlay_opacity'=>'0.5'];
$intro = $pdo->query("SELECT * FROM site_sections WHERE section_key = 'contact_intro'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'', 'subtitle'=>''];

include 'header.php';
?>

<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Contact Page Manager</h1>
            <p class="text-gray-500 mt-2">Manage the look and text of your contact page.</p>
        </div>
        <a href="../contact.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">Preview <span class="material-symbols-outlined text-sm">visibility</span></a>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border bg-green-100 text-green-700 border-green-400 flex items-center gap-2"><span class="material-symbols-outlined">check_circle</span><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-safari-dark mb-4 border-b pb-2">Hero Section</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="update_hero" value="1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div><label class="block font-bold text-sm mb-2">Title</label><input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero['title']); ?>" class="w-full border rounded p-3 font-serif"></div>
                    <div><label class="block font-bold text-sm mb-2">Badge Text</label><input type="text" name="badge_text" value="<?php echo htmlspecialchars($hero['cta_text']); ?>" class="w-full border rounded p-3"></div>
                    <div class="md:col-span-2"><label class="block font-bold text-sm mb-2">Subtitle</label><textarea name="hero_subtitle" class="w-full border rounded p-3"><?php echo htmlspecialchars($hero['subtitle']); ?></textarea></div>
                    <div><label class="block font-bold text-sm mb-2">Image</label><input type="file" name="hero_image" class="w-full text-sm"><input type="hidden" name="current_image_url" value="<?php echo $hero['image_url']; ?>"></div>
                    <div><label class="block font-bold text-sm mb-2">Opacity</label><input type="range" name="overlay_opacity" min="0" max="1" step="0.1" value="<?php echo $hero['overlay_opacity']; ?>" class="w-full"></div>
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Hero</button></div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-xl font-bold text-safari-dark mb-4 border-b pb-2">Page Text</h2>
            <form method="POST" class="space-y-6">
                <input type="hidden" name="update_content" value="1">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block font-bold text-sm mb-2">Information Section Title</label>
                        <input type="text" name="intro_title" value="<?php echo htmlspecialchars($intro['title']); ?>" class="w-full border rounded p-3 font-serif">
                    </div>
                    <div>
                        <label class="block font-bold text-sm mb-2">Information Section Description</label>
                        <textarea name="intro_desc" rows="3" class="w-full border rounded p-3"><?php echo htmlspecialchars($intro['subtitle']); ?></textarea>
                    </div>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg text-sm text-blue-800 flex gap-2">
                    <span class="material-symbols-outlined">info</span>
                    Note: Phone numbers, emails, and addresses are managed in "General Settings".
                </div>
                <div class="pt-4 flex justify-end"><button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Text</button></div>
            </form>
        </div>
    </div>
</div>
<?php echo "</main></div></body></html>"; ?>