<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$message = '';
$messageType = '';

// --- HANDLE DELETE (Species) ---
if (isset($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM species WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Species deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error deleting species: " . $e->getMessage();
        $messageType = "error";
    }
}

// --- HANDLE POST REQUESTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 1. UPDATE HERO SECTION
    if (isset($_POST['update_hero'])) {
        try {
            $image_url = $_POST['current_image_url'] ?? '';
            
            if (isset($_FILES['hero_image']) && $_FILES['hero_image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['hero_image']['name'], PATHINFO_EXTENSION));
                $newFile = 'species_hero_' . time() . '.' . $ext;
                if (move_uploaded_file($_FILES['hero_image']['tmp_name'], '../uploads/' . $newFile)) {
                    $image_url = 'uploads/' . $newFile;
                }
            }

            // Smart Insert/Update for 'species_hero'
            $sectionKey = 'species_hero';
            $check = $pdo->prepare("SELECT COUNT(*) FROM site_sections WHERE section_key = ?");
            $check->execute([$sectionKey]);

            if ($check->fetchColumn() > 0) {
                $stmt = $pdo->prepare("UPDATE site_sections SET title=?, subtitle=?, cta_text=?, image_url=?, overlay_opacity=? WHERE section_key=?");
                $stmt->execute([$_POST['hero_title'], $_POST['hero_subtitle'], $_POST['cta_text'], $image_url, $_POST['overlay_opacity'], $sectionKey]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO site_sections (section_key, title, subtitle, cta_text, image_url, overlay_opacity) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$sectionKey, $_POST['hero_title'], $_POST['hero_subtitle'], $_POST['cta_text'], $image_url, $_POST['overlay_opacity']]);
            }

            $message = "Hero section updated successfully!";
            $messageType = "success";
        } catch (Exception $e) {
            $message = "Error updating hero: " . $e->getMessage();
            $messageType = "error";
        }
    }

    // 2. ADD/EDIT SPECIES
    if (isset($_POST['save_species'])) {
        try {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;
            $sort_order = (int) $_POST['sort_order'];
            $image_url = $_POST['current_species_image'] ?? '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $newFile = 'species_' . time() . '_' . rand(100,999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], '../uploads/' . $newFile)) {
                    $image_url = 'uploads/' . $newFile; 
                }
            }

            if (!empty($_POST['id'])) {
                $stmt = $pdo->prepare("UPDATE species SET name=?, description=?, image_url=?, is_featured_on_home=?, sort_order=? WHERE id=?");
                $stmt->execute([$name, $description, $image_url, $is_featured, $sort_order, $_POST['id']]);
                $message = "Species updated successfully!";
            } else {
                $stmt = $pdo->prepare("INSERT INTO species (name, description, image_url, is_featured_on_home, sort_order) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$name, $description, $image_url, $is_featured, $sort_order]);
                $message = "Species added successfully!";
            }
            $messageType = "success";
        } catch (Exception $e) {
            $message = "Error saving species: " . $e->getMessage();
            $messageType = "error";
        }
    }
}

// --- FETCH DATA ---
$hero = $pdo->query("SELECT * FROM site_sections WHERE section_key = 'species_hero'")->fetch(PDO::FETCH_ASSOC) ?: ['title'=>'', 'subtitle'=>'', 'cta_text'=>'', 'image_url'=>'', 'overlay_opacity'=>'0.5'];
$species_list = $pdo->query("SELECT * FROM species ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-6xl mx-auto pb-20">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Wildlife Checklist</h1>
            <p class="text-gray-500 mt-2">Manage the hero banner and species list.</p>
        </div>
        <a href="../species_checklist.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">
            Preview Page <span class="material-symbols-outlined text-sm">visibility</span>
        </a>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border <?php echo $messageType === 'success' ? 'bg-green-100 text-green-700 border-green-400' : 'bg-red-100 text-red-700 border-red-400'; ?> flex items-center gap-2">
            <span class="material-symbols-outlined"><?php echo $messageType === 'success' ? 'check_circle' : 'error'; ?></span>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="flex gap-4 mb-8 border-b border-gray-200">
        <button onclick="switchTab('hero')" id="tab-hero" class="tab-btn pb-3 px-4 font-bold text-safari-green border-b-4 border-safari-green transition">Hero Section</button>
        <button onclick="switchTab('list')" id="tab-list" class="tab-btn pb-3 px-4 font-bold text-gray-500 hover:text-gray-700 transition">Species List</button>
    </div>

    <div id="content-hero" class="tab-content">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="update_hero" value="1">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block font-bold text-sm mb-2">Page Title</label>
                        <input type="text" name="hero_title" value="<?php echo htmlspecialchars($hero['title']); ?>" class="w-full border rounded p-3 font-serif">
                    </div>
                    <div>
                        <label class="block font-bold text-sm mb-2">Small Top Text</label>
                        <input type="text" name="cta_text" value="<?php echo htmlspecialchars($hero['cta_text']); ?>" class="w-full border rounded p-3" placeholder="e.g. The Inhabitants">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-bold text-sm mb-2">Subtitle</label>
                        <textarea name="hero_subtitle" class="w-full border rounded p-3"><?php echo htmlspecialchars($hero['subtitle']); ?></textarea>
                    </div>
                    
                    <div>
                        <label class="block font-bold text-sm mb-2">Banner Image</label>
                        <?php if (!empty($hero['image_url'])): ?>
                            <div class="mb-2"><img src="../<?php echo htmlspecialchars($hero['image_url']); ?>" class="h-24 rounded border"></div>
                        <?php endif; ?>
                        <input type="file" name="hero_image" class="w-full text-sm">
                        <input type="hidden" name="current_image_url" value="<?php echo htmlspecialchars($hero['image_url']); ?>">
                    </div>
                    
                    <div>
                        <label class="block font-bold text-sm mb-2">Overlay Opacity</label>
                        <input type="range" name="overlay_opacity" min="0" max="1" step="0.1" value="<?php echo htmlspecialchars($hero['overlay_opacity']); ?>" class="w-full accent-safari-green">
                    </div>
                </div>
                <div class="pt-4 flex justify-end">
                    <button type="submit" class="bg-safari-green text-white px-6 py-2 rounded-lg font-bold">Save Hero</button>
                </div>
            </form>
        </div>
    </div>

    <div id="content-list" class="tab-content hidden">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-xl font-bold text-safari-dark mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-tiger-yellow">add_circle</span> <span id="formTitle">Add Species</span>
                    </h2>
                    
                    <form method="POST" enctype="multipart/form-data" class="space-y-4" id="speciesForm">
                        <input type="hidden" name="save_species" value="1">
                        <input type="hidden" name="id" id="species_id">
                        <input type="hidden" name="current_species_image" id="current_species_image">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" required class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Description</label>
                            <textarea name="description" id="description" rows="3" class="w-full border rounded-lg p-2"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Sort Order</label>
                            <input type="number" name="sort_order" id="sort_order" value="0" class="w-full border rounded-lg p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Image</label>
                            <div id="imgPreviewBox" class="hidden mb-2"><img id="imgPreview" src="" class="h-16 rounded border"></div>
                            <input type="file" name="image" class="w-full text-xs">
                        </div>

                        <div class="flex items-center bg-gray-50 p-3 rounded-lg border border-gray-100">
                            <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 accent-safari-green">
                            <label for="is_featured" class="ml-2 text-sm font-medium text-gray-700">Feature on Home</label>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="button" onclick="resetForm()" class="flex-1 px-4 py-2 text-gray-600 bg-gray-100 rounded-lg text-sm font-bold">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-safari-green text-white rounded-lg text-sm font-bold">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-bold">Image</th>
                                <th class="px-6 py-3 font-bold">Details</th>
                                <th class="px-6 py-3 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php foreach ($species_list as $sp): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <?php if($sp['image_url']): ?>
                                            <img src="<?php echo (strpos($sp['image_url'], 'http')===0 ? $sp['image_url'] : '../'.$sp['image_url']); ?>" class="w-12 h-12 object-cover rounded-lg border">
                                        <?php else: ?>
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center text-gray-300"><span class="material-symbols-outlined">image</span></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900"><?php echo htmlspecialchars($sp['name']); ?></div>
                                        <div class="text-xs text-gray-500 line-clamp-1"><?php echo htmlspecialchars($sp['description']); ?></div>
                                        <?php if($sp['is_featured_on_home']): ?><span class="text-[10px] bg-green-100 text-green-800 px-2 py-0.5 rounded font-bold">Featured</span><?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <button onclick='editSpecies(<?php echo htmlspecialchars(json_encode($sp), ENT_QUOTES); ?>)' class="text-blue-600 hover:bg-blue-50 p-2 rounded"><span class="material-symbols-outlined text-sm">edit</span></button>
                                        <a href="?delete=<?php echo $sp['id']; ?>" onclick="return confirm('Delete?')" class="text-red-600 hover:bg-red-50 p-2 rounded"><span class="material-symbols-outlined text-sm">delete</span></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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

        function editSpecies(d) {
            document.getElementById('formTitle').innerText = 'Edit Species';
            document.getElementById('species_id').value = d.id;
            document.getElementById('name').value = d.name;
            document.getElementById('description').value = d.description;
            document.getElementById('sort_order').value = d.sort_order;
            document.getElementById('current_species_image').value = d.image_url;
            document.getElementById('is_featured').checked = (d.is_featured_on_home == 1);
            
            if(d.image_url) {
                let src = d.image_url.startsWith('http') ? d.image_url : '../' + d.image_url;
                document.getElementById('imgPreview').src = src;
                document.getElementById('imgPreviewBox').classList.remove('hidden');
            } else {
                document.getElementById('imgPreviewBox').classList.add('hidden');
            }
        }

        function resetForm() {
            document.getElementById('formTitle').innerText = 'Add Species';
            document.getElementById('speciesForm').reset();
            document.getElementById('species_id').value = '';
            document.getElementById('imgPreviewBox').classList.add('hidden');
        }
    </script>
</div>
<?php echo "</main></div></body></html>"; ?>