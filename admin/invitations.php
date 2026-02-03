<?php
require_once '../config.php';
session_start();
// Auth check removed
// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../login.php");
//     exit;
// }

$invitations = $pdo->query("SELECT * FROM invitations ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Invitations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="index.php" class="bg-gray-800 p-2 rounded-lg hover:bg-gray-700 transition">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-3xl font-bold">Invitations</h1>
            </div>
            <a href="invitation_form.php" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2 rounded-lg font-bold shadow-lg flex items-center gap-2">
                <span class="material-symbols-outlined">add</span> Create New
            </a>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6 border border-green-500/30 font-bold flex items-center gap-3">
                 <span class="material-symbols-outlined">check_circle</span>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($invitations as $inv): ?>
            <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 shadow-xl group hover:border-indigo-500 transition-all">
                <div class="h-40 bg-cover bg-center relative" style="background-image: url('<?= $inv['cover_image'] ?? 'https://via.placeholder.com/400x200' ?>');">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4">
                        <h2 class="font-bold text-xl"><?= htmlspecialchars($inv['groom_nickname']) ?> & <?= htmlspecialchars($inv['bride_nickname']) ?></h2>
                        <p class="text-gray-400 text-xs"><?= date('d M Y', strtotime($inv['event_date'])) ?></p>
                    </div>
                </div>
                <div class="p-4 flex gap-2">
                    <a href="../invitation.php?slug=<?= $inv['slug'] ?>" target="_blank" class="flex-1 bg-gray-700 hover:bg-gray-600 py-2 rounded-lg text-center flex items-center justify-center gap-2 text-sm font-bold">
                        <span class="material-symbols-outlined text-sm">visibility</span> View
                    </a>
                    <a href="invitation_form.php?id=<?= $inv['id'] ?>" class="flex-1 bg-indigo-600 hover:bg-indigo-500 py-2 rounded-lg text-center flex items-center justify-center gap-2 text-sm font-bold">
                        <span class="material-symbols-outlined text-sm">edit</span> Edit
                    </a>
                    <form action="invitation_handler.php" method="POST" onsubmit="return confirm('Delete this invitation?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $inv['id'] ?>">
                        <button class="bg-red-500/20 text-red-500 hover:bg-red-500 hover:text-white p-2 rounded-lg transition-colors">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
