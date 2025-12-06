<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }

$message = '';
$messageType = '';

// --- DELETE IMAGE ---
if (isset($_GET['delete'])) {
    try {
        // Optional: Delete actual file
        $stmt = $pdo->prepare("SELECT image_url FROM gallery_images WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $img = $stmt->fetch();
        if ($img && file_exists('../' . $img['image_url'])) {
            unlink('../' . $img['image_url']);
        }

        $stmt = $pdo->prepare("DELETE FROM gallery_images WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $message = "Image deleted successfully!";
        $messageType = "success";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = "error";
    }
}

// --- HANDLE FORM SUBMISSION (ADD & EDIT) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $category = $_POST['category'];
        $sort_order = (int) $_POST['sort_order'];
        $id = $_POST['id'] ?? null;

        // Handle Image Upload
        $image_url = $_POST['current_image_url'] ?? ''; // Keep existing if no new upload
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $valid = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($ext, $valid)) {
                $newFile = 'gallery_' . time() . '_' . rand(100,999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newFile)) {
                    $image_url = 'uploads/' . $newFile;
                } else {
                    throw new Exception("Failed to move file.");
                }
            } else {
                throw new Exception("Invalid file format. JPG, PNG, WEBP only.");
            }
        }

        if ($id) {
            // UPDATE EXISTING
            $stmt = $pdo->prepare("UPDATE gallery_images SET title=?, category=?, sort_order=?, image_url=? WHERE id=?");
            $stmt->execute([$title, $category, $sort_order, $image_url, $id]);
            $message = "Image updated successfully!";
        } else {
            // INSERT NEW
            if (empty($image_url)) throw new Exception("Please upload an image.");
            $stmt = $pdo->prepare("INSERT INTO gallery_images (title, category, sort_order, image_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $category, $sort_order, $image_url]);
            $message = "Image added successfully!";
        }
        $messageType = "success";

    } catch (Exception $e) {
        $message = $e->getMessage();
        $messageType = "error";
    }
}

// Fetch Images (Sorted by Order)
$images = $pdo->query("SELECT * FROM gallery_images ORDER BY sort_order ASC, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="max-w-6xl mx-auto pb-20">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-4xl font-bold text-safari-dark font-serif">Gallery Manager</h1>
            <p class="text-gray-500 mt-2">Curate your visual story.</p>
        </div>
        <a href="../gallery.php" target="_blank" class="text-safari-green font-bold hover:underline flex items-center gap-1">Preview <span class="material-symbols-outlined text-sm">visibility</span></a>
    </div>

    <?php if ($message): ?>
        <div class="p-4 mb-6 rounded-xl border <?php echo ($messageType=='error')?'bg-red-100 text-red-700':'bg-green-100 text-green-700'; ?> flex items-center gap-2">
            <span class="material-symbols-outlined">info</span> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-6">
                <h2 class="text-xl font-bold text-safari-dark mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-tiger-yellow">add_photo_alternate</span> Upload New
                </h2>
                <form method="POST" enctype="multipart/form-data" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Category</label>
                        <select name="category" class="w-full border rounded-lg p-2.5 bg-gray-50 focus:ring-2 focus:ring-safari-green outline-none">
                            <option value="wildlife">Wildlife</option>
                            <option value="nature">Nature</option>
                            <option value="boat">Boat & Resort</option>
                            <option value="tourists">Experience</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Caption</label>
                        <input type="text" name="title" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-safari-green outline-none" placeholder="e.g. Royal Bengal Tiger">
                    </div>
                     <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="0" class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-safari-green outline-none">
                    </div>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 text-center hover:bg-gray-50 transition cursor-pointer relative">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="document.getElementById('fileName').innerText = this.files[0].name" required>
                        <span class="material-symbols-outlined text-3xl text-gray-400 mb-1">cloud_upload</span>
                        <p class="text-xs text-safari-green font-bold">Click to Upload</p>
                        <p id="fileName" class="text-[10px] text-gray-400 mt-1 truncate">JPG, PNG, WEBP</p>
                    </div>
                    <button type="submit" class="w-full bg-safari-green text-white py-3 rounded-xl font-bold hover:bg-green-800 transition shadow-lg">Add Image</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                <?php foreach ($images as $img): ?>
                    <div class="group relative aspect-square rounded-xl overflow-hidden bg-gray-100 shadow-sm border border-gray-200">
                        <img src="<?php echo (strpos($img['image_url'], 'http')===0) ? $img['image_url'] : '../'.$img['image_url']; ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-3">
                            <div class="flex justify-between items-start">
                                <span class="bg-black/50 text-white text-[10px] px-2 py-1 rounded backdrop-blur-md uppercase font-bold tracking-wider">
                                    #<?php echo $img['sort_order']; ?>
                                </span>
                                <div class="flex gap-2">
                                    <button onclick='editImage(<?php echo json_encode($img); ?>)' class="w-8 h-8 rounded-full bg-white/20 hover:bg-blue-500 text-white flex items-center justify-center backdrop-blur-md transition">
                                        <span class="material-symbols-outlined text-sm">edit</span>
                                    </button>
                                    <a href="?delete=<?php echo $img['id']; ?>" onclick="return confirm('Delete this image?')" class="w-8 h-8 rounded-full bg-white/20 hover:bg-red-500 text-white flex items-center justify-center backdrop-blur-md transition">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <span class="text-xs text-tiger-yellow uppercase font-bold tracking-wider mb-0.5 block"><?php echo $img['category']; ?></span>
                                <p class="text-white font-bold text-xs truncate"><?php echo htmlspecialchars($img['title'] ?: 'No Caption'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform transition-all scale-95 opacity-0" id="modalContent">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-safari-dark">Edit Image</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-red-500"><span class="material-symbols-outlined">close</span></button>
            </div>
            
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="current_image_url" id="edit_current_image_url">
                
                <div class="flex gap-4 items-start">
                    <img id="preview_image" src="" class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-600 mb-1">Replace Image (Optional)</label>
                        <input type="file" name="image" accept="image/*" class="text-xs text-gray-500 w-full file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Category</label>
                        <select name="category" id="edit_category" class="w-full border rounded-lg p-2 text-sm bg-gray-50">
                            <option value="wildlife">Wildlife</option>
                            <option value="nature">Nature</option>
                            <option value="boat">Boat & Resort</option>
                            <option value="tourists">Experience</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" id="edit_sort_order" class="w-full border rounded-lg p-2 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Caption</label>
                    <input type="text" name="title" id="edit_title" class="w-full border rounded-lg p-2 text-sm">
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg text-sm font-bold hover:bg-gray-200">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-safari-green text-white rounded-lg text-sm font-bold hover:bg-green-800 shadow-md">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('editModal');
        const modalContent = document.getElementById('modalContent');

        function editImage(data) {
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_category').value = data.category;
            document.getElementById('edit_sort_order').value = data.sort_order;
            document.getElementById('edit_current_image_url').value = data.image_url;
            
            // Handle image preview
            let src = data.image_url;
            if(!src.startsWith('http')) src = '../' + src;
            document.getElementById('preview_image').src = src;

            modal.classList.remove('hidden');
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeModal() {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }
    </script>
</div>
<?php echo "</main></div></body></html>"; ?>