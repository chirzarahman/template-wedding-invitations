<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: invitations.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM invitations WHERE id = ?");
$stmt->execute([$id]);
$invitation = $stmt->fetch();

if (!$invitation) {
    die("Invitation not found");
}

// Fetch related data (stories, gallery, etc would go here)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Builder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
</head>
<body class="bg-gray-900 text-white min-h-screen pb-20">

    <!-- Header -->
    <header class="bg-gray-800 border-b border-gray-700 p-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="invitations.php" class="text-gray-400 hover:text-white transition">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="font-bold text-xl">Builder: <?= htmlspecialchars($invitation['groom_nickname']) ?> & <?= htmlspecialchars($invitation['bride_nickname']) ?></h1>
            </div>
            <a href="../invitation.php?slug=<?= $invitation['slug'] ?>" target="_blank" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg font-bold flex items-center gap-2 text-sm">
                <span class="material-symbols-outlined text-sm">visibility</span> Preview
            </a>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-6 space-y-8">

        <!-- Tabs -->
        <div class="flex overflow-x-auto gap-2 border-b border-gray-700 pb-2 mb-6">
            <button onclick="document.getElementById('couple').scrollIntoView({behavior: 'smooth'})" class="px-4 py-2 bg-gray-800 rounded-lg whitespace-nowrap hover:bg-gray-700">Couple Details</button>
            <button onclick="document.getElementById('event').scrollIntoView({behavior: 'smooth'})" class="px-4 py-2 bg-gray-800 rounded-lg whitespace-nowrap hover:bg-gray-700">Event & Location</button>
            <button onclick="document.getElementById('cover').scrollIntoView({behavior: 'smooth'})" class="px-4 py-2 bg-gray-800 rounded-lg whitespace-nowrap hover:bg-gray-700">Cover & Music</button>
        </div>

        <!-- 1. Couple Details -->
        <section id="couple" class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-pink-500">
                <span class="material-symbols-outlined">favorite</span> Couple Details
            </h2>
            <form action="invitation_handler.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="update_couple">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Groom -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-400 border-b border-gray-700 pb-2">Groom (Pria)</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Full Name</label>
                            <input type="text" name="groom_name" value="<?= htmlspecialchars($invitation['groom_name']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Nickname</label>
                            <input type="text" name="groom_nickname" value="<?= htmlspecialchars($invitation['groom_nickname']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Father's Name</label>
                            <input type="text" name="groom_father" value="<?= htmlspecialchars($invitation['groom_father']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Mother's Name</label>
                            <input type="text" name="groom_mother" value="<?= htmlspecialchars($invitation['groom_mother']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Photo</label>
                            <input type="hidden" name="old_groom_photo" value="<?= $invitation['groom_photo'] ?>">
                            <input type="file" name="groom_photo" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                            <?php if($invitation['groom_photo']): ?>
                                <img src="../<?= $invitation['groom_photo'] ?>" class="w-20 h-20 rounded-full object-cover mt-2 border-2 border-gray-600">
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Bride -->
                    <div class="space-y-4">
                        <h3 class="font-bold text-gray-400 border-b border-gray-700 pb-2">Bride (Wanita)</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Full Name</label>
                            <input type="text" name="bride_name" value="<?= htmlspecialchars($invitation['bride_name']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Nickname</label>
                            <input type="text" name="bride_nickname" value="<?= htmlspecialchars($invitation['bride_nickname']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Father's Name</label>
                            <input type="text" name="bride_father" value="<?= htmlspecialchars($invitation['bride_father']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Mother's Name</label>
                            <input type="text" name="bride_mother" value="<?= htmlspecialchars($invitation['bride_mother']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-pink-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-1">Photo</label>
                            <input type="hidden" name="old_bride_photo" value="<?= $invitation['bride_photo'] ?>">
                            <input type="file" name="bride_photo" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                             <?php if($invitation['bride_photo']): ?>
                                <img src="../<?= $invitation['bride_photo'] ?>" class="w-20 h-20 rounded-full object-cover mt-2 border-2 border-gray-600">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="text-right pt-4 border-t border-gray-700">
                    <button type="submit" class="bg-pink-600 hover:bg-pink-500 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-transform hover:scale-105">Save Couple Details</button>
                </div>
            </form>
        </section>

        <!-- 2. Event & Location -->
        <section id="event" class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
             <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-yellow-500">
                <span class="material-symbols-outlined">event</span> Event & Location
            </h2>
            <form action="invitation_handler.php" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="update_event">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Event Date & Time (Akad)</label>
                        <input type="datetime-local" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($invitation['event_date'])) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-yellow-500 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Google Maps Link</label>
                        <input type="text" name="map_link" value="<?= htmlspecialchars($invitation['map_link'] ?? '') ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-yellow-500 outline-none" placeholder="https://maps.google.com/...">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-400 mb-1">Full Address (Venue)</label>
                        <textarea name="event_address" rows="3" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-yellow-500 outline-none" required><?= htmlspecialchars($invitation['event_address'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="text-right pt-4 border-t border-gray-700">
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-transform hover:scale-105">Save Event Details</button>
                </div>
            </form>
        </section>

        <!-- 3. Cover & Music -->
        <section id="cover" class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
             <h2 class="text-xl font-bold mb-6 flex items-center gap-2 text-indigo-500">
                <span class="material-symbols-outlined">music_note</span> Cover & Music
            </h2>
            <form action="invitation_handler.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                <input type="hidden" name="action" value="update_cover">
                <input type="hidden" name="id" value="<?= $id ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1">Cover Title</label>
                        <input type="text" name="cover_title" value="<?= htmlspecialchars($invitation['cover_title']) ?>" class="w-full bg-gray-900 border border-gray-700 rounded-lg p-3 focus:border-indigo-500 outline-none">
                    </div>
                     <div>
                        <label class="block text-sm text-gray-400 mb-1">Cover Image</label>
                        <input type="hidden" name="old_cover_image" value="<?= $invitation['cover_image'] ?>">
                        <input type="file" name="cover_image" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                        <?php if($invitation['cover_image']): ?>
                            <img src="../<?= $invitation['cover_image'] ?>" class="h-20 w-auto mt-2 rounded border border-gray-600">
                        <?php endif; ?>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-400 mb-1">Background Music (MP3)</label>
                        <input type="hidden" name="old_music_file" value="<?= $invitation['music_file'] ?>">
                        <input type="file" name="music_file" accept=".mp3" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                         <?php if($invitation['music_file']): ?>
                            <audio controls class="mt-2 w-full h-8">
                                <source src="../<?= $invitation['music_file'] ?>" type="audio/mpeg">
                            </audio>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="text-right pt-4 border-t border-gray-700">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2 rounded-lg font-bold shadow-lg transition-transform hover:scale-105">Save Cover & Music</button>
                </div>
            </form>
        </section>

    </main>

</body>
</html>
