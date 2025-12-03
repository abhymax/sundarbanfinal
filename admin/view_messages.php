<?php
session_start();
require_once '../db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) { header('Location: login.php'); exit; }

// Delete Message
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM contact_messages WHERE id = ?")->execute([$_GET['delete']]);
    header("Location: view_messages.php"); exit;
}

$messages = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
include 'header.php';
?>

<div class="max-w-6xl mx-auto">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-safari-dark font-serif">Inbox</h1>
        <p class="text-gray-500 mt-2">Messages from the contact form.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-bold">
                <tr>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4">Name</th>
                    <th class="px-6 py-4">Subject</th>
                    <th class="px-6 py-4">Message</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if(empty($messages)): ?>
                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No messages yet.</td></tr>
                <?php else: foreach ($messages as $msg): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-400 whitespace-nowrap"><?php echo date('d M Y', strtotime($msg['created_at'])); ?></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-800"><?php echo htmlspecialchars($msg['first_name'] . ' ' . $msg['last_name']); ?></div>
                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($msg['email']); ?></div>
                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($msg['phone']); ?></div>
                        </td>
                        <td class="px-6 py-4 font-medium text-safari-green"><?php echo htmlspecialchars($msg['subject']); ?></td>
                        <td class="px-6 py-4 text-gray-600 max-w-xs truncate"><?php echo htmlspecialchars($msg['message']); ?></td>
                        <td class="px-6 py-4 text-right">
                            <a href="?delete=<?php echo $msg['id']; ?>" onclick="return confirm('Delete this message?')" class="text-red-500 hover:text-red-700 p-2"><span class="material-symbols-outlined">delete</span></a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php echo "</main></div></body></html>"; ?>