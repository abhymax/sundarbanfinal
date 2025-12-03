<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $subtitle = trim($_POST['subtitle']);
        $cta_text = trim($_POST['cta_text']);
        $cta_link = trim($_POST['cta_link']);
        $sub_heading_font_size = $_POST['sub_heading_font_size'] ?? 'text-xl';
        $image_url = $_POST['current_image_url'];
        $video_url = $_POST['video_url'] ?? '';

        if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $newFile = 'home_hero_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], $uploadDir . $newFile)) {
                    $image_url = 'uploads/' . $newFile;
                }
            }
        }

        if (isset($_FILES['hero_video']) && $_FILES['hero_video']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['hero_video']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['mp4', 'webm'])) {
                $newFile = 'hero_video_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_video']['tmp_name'], $uploadDir . $newFile)) {
                    $video_url = 'uploads/' . $newFile;
                }
            }
        }

        $sql = "UPDATE site_sections SET 
                title = :title, subtitle = :subtitle, cta_text = :cta_text, 
                cta_link = :cta_link, image_url = :image_url, video_url = :video_url,
                overlay_opacity = :overlay_opacity, sub_heading_font_size = :sub_heading_font_size
                WHERE section_key = 'home_hero'";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title, ':subtitle' => $subtitle, ':cta_text' => $cta_text,
            ':cta_link' => $cta_link, ':image_url' => $image_url, ':video_url' => $video_url,
            ':overlay_opacity' => $_POST['overlay_opacity'] ?? 0.6,
            ':sub_heading_font_size' => $sub_heading_font_size
        ]);

        $message = "Hero updated successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

$stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = 'home_hero'");
$stmt->execute();
$hero = $stmt->fetch();

include 'header.php';
?>

<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Hero Section</h1>
            <p class="text-gray-500 mt-2">The first thing visitors see on your homepage.</p>
        </div>
        <a href="../index.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">
            Preview <span class="material-symbols-outlined text-sm">visibility</span>
        </a>
    </div>

    <?php if ($message): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <form method="POST" enctype="multipart/form-data" class="p-8 space-y-8">

            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 border-b border-gray-100 pb-2">Headlines</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Main Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($hero['title']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition text-lg font-serif" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle</label>
                        <textarea name="subtitle" rows="2"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required><?php echo htmlspecialchars($hero['subtitle']); ?></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Subtitle Size</label>
                            <select name="sub_heading_font_size" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition">
                                <?php
                                $sizes = ['text-base'=>'Normal', 'text-lg'=>'Large', 'text-xl'=>'Extra Large', 'text-2xl'=>'2x Large', 'text-3xl'=>'3x Large'];
                                foreach ($sizes as $v => $l) echo "<option value='$v' " . ($hero['sub_heading_font_size'] == $v ? 'selected' : '') . ">$l</option>";
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 border-b border-gray-100 pb-2">Call to Action</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Button Text</label>
                        <input type="text" name="cta_text" value="<?php echo htmlspecialchars($hero['cta_text']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Button Link</label>
                        <input type="text" name="cta_link" value="<?php echo htmlspecialchars($hero['cta_link']); ?>"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-safari-green outline-none transition" required>
                    </div>
                </div>
            </section>

            <section>
                <h3 class="text-xl font-bold text-safari-dark mb-6 border-b border-gray-100 pb-2">Background Media</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-3">Background Image</label>
                        <div class="mb-3 w-full h-32 bg-gray-200 rounded-lg overflow-hidden relative group">
                            <?php if (!empty($hero['image_url'])): ?>
                                <img src="../<?php echo htmlspecialchars($hero['image_url']); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full text-gray-400 text-xs">No Image</div>
                            <?php endif; ?>
                        </div>
                        <input type="file" name="hero_image" accept="image/*" class="text-sm w-full">
                        <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($hero['image_url']); ?>">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                        <label class="block text-xs font-bold uppercase text-gray-500 mb-3">Background Video</label>
                        <input type="text" name="video_url" value="<?php echo htmlspecialchars($hero['video_url'] ?? ''); ?>" placeholder="YouTube URL or leave empty" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-3 focus:outline-none focus:border-safari-green">
                        <label class="block text-xs font-bold text-gray-500 mb-1">OR Upload File (MP4/WebM)</label>
                        <input type="file" name="hero_video" accept="video/*" class="text-sm w-full">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Overlay Darkness</label>
                    <div class="flex items-center gap-4">
                        <input type="range" name="overlay_opacity" min="0" max="1" step="0.1"
                            value="<?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.6'); ?>"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-safari-green"
                            oninput="document.getElementById('opacityVal').innerText = this.value">
                        <span id="opacityVal" class="font-mono bg-gray-100 px-2 py-1 rounded text-sm font-bold"><?php echo htmlspecialchars($hero['overlay_opacity'] ?? '0.6'); ?></span>
                    </div>
                </div>
            </section>

            <div class="pt-6 border-t border-gray-100 flex justify-end">
                <button type="submit"
                    class="bg-safari-green hover:bg-green-800 text-white font-bold py-4 px-10 rounded-xl shadow-lg transition flex items-center gap-2">
                    <span class="material-symbols-outlined">save</span> Save Update
                </button>
            </div>

        </form>
    </div>
</div>

</main>
</div>
</body>
</html>