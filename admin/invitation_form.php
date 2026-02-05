<?php
require_once '../config.php';
session_start();

// Auth check (disabled per user request)
// if (!isset($_SESSION['user_id'])) { ... }

$id = $_GET['id'] ?? null;
$invitation = [];
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM invitations WHERE id = ?");
    $stmt->execute([$id]);
    $invitation = $stmt->fetch();
}

$colors = [
    'Dusty Rose' => '#b784a7',
    'Sage Green' => '#8a9a5b',
    'Champagne / Gold' => '#d4af37',
    'Ivory / Off White' => '#fdf6e3',
    'Blush Pink' => '#ffccd5',
    'Lavender / Lilac' => '#e6e6fa',
    'Emerald Green' => '#50c878',
    'Navy Blue' => '#000080',
    'Terracotta / Burnt Orange' => '#cc5500',
    'Maroon / Wine Red' => '#800000',
    'Peach / Coral' => '#ff7f50',
    'Black & White' => '#000000',
    'Dusty Blue' => '#5b7c99'
];

$styles = [
    'adat-jawa' => 'Adat Jawa (Classic Brown)',
    'minimalis' => 'Minimalis (Modern)',
    'modern-emerald' => 'Modern Emerald (Green)'
];

$features = $id && $invitation['enabled_features'] ? json_decode($invitation['enabled_features'], true) : [];

// Normalize visual style for form
$style_map = [
    'template-01' => 'adat-jawa',
    'template-02' => 'minimalis',
    'template-03' => 'modern-emerald'
];
if (isset($invitation['visual_style']) && isset($style_map[$invitation['visual_style']])) {
    $invitation['visual_style'] = $style_map[$invitation['visual_style']];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Edit' : 'Create' ?> Invitation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .radio-check:checked+div {
            ring-width: 2px;
            ring-color: #4F46E5;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 pb-32">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm backdrop-blur-md bg-white/90">
        <div class="max-w-5xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="invitations.php" class="p-2 -ml-2 rounded-full hover:bg-gray-100 transition text-gray-500">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <div>
                    <h1 class="font-bold text-lg leading-tight text-gray-900">
                        <?= $id ? 'Edit Invitation' : 'Create Project' ?>
                    </h1>
                    <p class="text-xs text-gray-500">Isi detail pernikahan Anda di bawah ini</p>
                </div>
            </div>
            <button form="mainForm" type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/20 transition-all transform active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                <?= $id ? 'Simpan Perubahan' : 'Buat Undangan' ?>
            </button>
        </div>
    </header>

    <main class="max-w-5xl mx-auto p-4 md:p-6 space-y-8">

        <form id="mainForm" action="invitation_handler.php" method="POST" enctype="multipart/form-data"
            class="space-y-8">
            <input type="hidden" name="action" value="<?= $id ? 'update_full' : 'create_full' ?>">
            <?php if ($id): ?><input type="hidden" name="id" value="<?= $id ?>"><?php endif; ?>

            <!-- 1. Couple Info -->
            <section
                class="bg-white rounded-2xl p-6 md:p-8 shadow-[0_2px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-pink-100 text-pink-600 rounded-lg">
                        <span class="material-symbols-outlined">favorite</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Pasangan Mempelai</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Groom -->
                    <div class="space-y-4">
                        <h3
                            class="font-bold text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">
                            Mempelai Pria</h3>

                        <!-- Groom Photo Upload -->
                        <div class="flex items-center gap-4 py-2">
                            <div
                                class="relative w-20 h-20 rounded-full overflow-hidden border-2 border-dashed border-gray-300 group hover:border-blue-500 transition-colors bg-gray-50 flex-shrink-0">
                                <?php $groomPhoto = $invitation['groom_photo'] ?? ''; ?>
                                <img id="groom-preview"
                                    src="<?= $groomPhoto ? $groomPhoto : '../undangan pernikahan/assets/img-1.jpg' ?>"
                                    class="w-full h-full object-cover">
                                <label
                                    class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white text-xs text-center p-1">
                                    Upload
                                    <input type="file" name="groom_photo" accept="image/*" class="hidden"
                                        onchange="previewImage(this, 'groom-preview')">
                                </label>
                            </div>
                            <?php if ($groomPhoto): ?>
                                <label
                                    class="flex items-center gap-1 text-xs text-red-500 cursor-pointer hover:text-red-700">
                                    <input type="checkbox" name="delete_groom_photo" value="1" class="rounded w-3 h-3">
                                    Hapus Foto
                                </label>
                            <?php endif; ?>
                            <div class="text-xs text-gray-400">
                                <p>Format: JPG/PNG</p>
                                <p>Max: 2MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Nama Lengkap</label>
                            <input type="text" name="groom_name"
                                value="<?= htmlspecialchars($invitation['groom_name'] ?? '') ?>"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none"
                                placeholder="Cth: Rizky Billar" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Nama Panggilan</label>
                            <input type="text" name="groom_nickname"
                                value="<?= htmlspecialchars($invitation['groom_nickname'] ?? '') ?>"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none"
                                placeholder="Cth: Rizky" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Nama Orang Tua</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="groom_father"
                                    value="<?= htmlspecialchars($invitation['groom_father'] ?? '') ?>"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm outline-none"
                                    placeholder="Ayah">
                                <input type="text" name="groom_mother"
                                    value="<?= htmlspecialchars($invitation['groom_mother'] ?? '') ?>"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm outline-none"
                                    placeholder="Ibu">
                            </div>
                        </div>
                    </div>

                    <!-- Bride -->
                    <div class="space-y-4">
                        <h3
                            class="font-bold text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100 pb-2">
                            Mempelai Wanita</h3>

                        <!-- Bride Photo Upload -->
                        <div class="flex items-center gap-4 py-2">
                            <div
                                class="relative w-20 h-20 rounded-full overflow-hidden border-2 border-dashed border-gray-300 group hover:border-pink-500 transition-colors bg-gray-50 flex-shrink-0">
                                <?php $bridePhoto = $invitation['bride_photo'] ?? ''; ?>
                                <img id="bride-preview"
                                    src="<?= $bridePhoto ? $bridePhoto : '../undangan pernikahan/assets/img-2.jpg' ?>"
                                    class="w-full h-full object-cover">
                                <label
                                    class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer text-white text-xs text-center p-1">
                                    Upload
                                    <input type="file" name="bride_photo" accept="image/*" class="hidden"
                                        onchange="previewImage(this, 'bride-preview')">
                                </label>
                            </div>
                            <?php if ($bridePhoto): ?>
                                <label
                                    class="flex items-center gap-1 text-xs text-red-500 cursor-pointer hover:text-red-700">
                                    <input type="checkbox" name="delete_bride_photo" value="1" class="rounded w-3 h-3">
                                    Hapus Foto
                                </label>
                            <?php endif; ?>
                            <div class="text-xs text-gray-400">
                                <p>Format: JPG/PNG</p>
                                <p>Max: 2MB</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Nama Lengkap</label>
                            <input type="text" name="bride_name"
                                value="<?= htmlspecialchars($invitation['bride_name'] ?? '') ?>"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none"
                                placeholder="Cth: Lesti Kejora" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Nama Panggilan</label>
                            <input type="text" name="bride_nickname"
                                value="<?= htmlspecialchars($invitation['bride_nickname'] ?? '') ?>"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 outline-none"
                                placeholder="Cth: Lesti" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Nama Orang Tua</label>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="bride_father"
                                    value="<?= htmlspecialchars($invitation['bride_father'] ?? '') ?>"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm outline-none"
                                    placeholder="Ayah">
                                <input type="text" name="bride_mother"
                                    value="<?= htmlspecialchars($invitation['bride_mother'] ?? '') ?>"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 text-sm outline-none"
                                    placeholder="Ibu">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 2. Date & Locations -->
            <section
                class="bg-white rounded-2xl p-6 md:p-8 shadow-[0_2px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                        <span class="material-symbols-outlined">event_available</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Waktu & Tempat</h2>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Date -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Tanggal Acara</label>
                            <input type="date" name="event_date_only"
                                value="<?= isset($invitation['event_date']) ? date('Y-m-d', strtotime($invitation['event_date'])) : '' ?>"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                                required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1.5">Jam Akad</label>
                                <input type="time" name="akad_time"
                                    value="<?= isset($invitation['event_date']) ? date('H:i', strtotime($invitation['event_date'])) : '' ?>"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1.5">Jam Resepsi</label>
                                <input type="time" name="reception_time"
                                    value="<?= isset($invitation['reception_date']) ? date('H:i', strtotime($invitation['reception_date'])) : '' ?>"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Link Google Maps (Akad)</label>
                            <div class="relative mb-3">
                                <span class="material-symbols-outlined absolute left-3 top-3 text-gray-400">map</span>
                                <input type="text" name="map_link"
                                    value="<?= htmlspecialchars($invitation['map_link'] ?? '') ?>"
                                    class="w-full pl-10 bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Maps Akad">
                            </div>

                            <label class="block text-sm font-semibold mb-1.5">Link Google Maps
                                (Resepsi/Optional)</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-3 text-gray-400">map</span>
                                <input type="text" name="reception_map_link"
                                    value="<?= htmlspecialchars($invitation['reception_map_link'] ?? '') ?>"
                                    class="w-full pl-10 bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none"
                                    placeholder="Maps Resepsi (Biarkan kosong jika sama)">
                            </div>
                        </div>
                        <div class="grid gap-4">
                            <textarea name="event_address" rows="2"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"
                                placeholder="Alamat Akad..."><?= htmlspecialchars($invitation['event_address'] ?? '') ?></textarea>
                            <textarea name="reception_address" rows="2"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none"
                                placeholder="Alamat Resepsi..."><?= htmlspecialchars($invitation['reception_address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 3. Themes & Customization -->
            <section
                class="bg-white rounded-2xl p-6 md:p-8 shadow-[0_2px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-purple-100 text-purple-600 rounded-lg">
                        <span class="material-symbols-outlined">palette</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Desain & Tampilan</h2>
                </div>

                <div class="mb-8">
                    <!-- Style Cards -->
                    <div>
                        <label class="block text-sm font-semibold mb-3">Gaya Desain</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <?php foreach ($styles as $key => $label): ?>
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="visual_style" value="<?= $key ?>" class="peer sr-only"
                                        <?= ($invitation['visual_style'] ?? 'adat-jawa') == $key ? 'checked' : '' ?>>
                                    <div
                                        class="p-3 rounded-xl border border-gray-200 hover:bg-gray-50 peer-checked:ring-2 peer-checked:ring-purple-500 peer-checked:bg-purple-50 transition-all text-center">
                                        <span class="material-symbols-outlined text-purple-400 mb-1">style</span>
                                        <div class="text-xs font-bold text-gray-700"><?= $label ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 4. Media & Features -->
            <section
                class="bg-white rounded-2xl p-6 md:p-8 shadow-[0_2px_20px_-5px_rgba(0,0,0,0.1)] border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                        <span class="material-symbols-outlined">perm_media</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">Media & Fitur</h2>
                </div>

                <div class="space-y-6">

                    <!-- Feature Toggles -->
                    <div class="flex flex-wrap gap-4">
                        <?php
                        $opts = [
                            'hero' => ['Hero Image', 'image'],
                            'gallery' => ['Galeri Foto', 'collections'],
                            'music' => ['Background Music', 'music_note'],
                            'countdown' => ['Countdown', 'timer'],
                            'rsvp' => ['RSVP Form', 'edit_note'],
                            'gift' => ['Wedding Gift', 'card_giftcard'],
                            'wishes' => ['Ucapan Tamu', 'forum']
                        ];
                        foreach ($opts as $val => $label):
                            ?>
                            <label class="cursor-pointer select-none">
                                <input type="checkbox" name="features[]" value="<?= $val ?>" class="peer sr-only"
                                    <?= in_array($val, $features) ? 'checked' : '' ?>>
                                <div
                                    class="px-4 py-2 rounded-full border border-gray-200 bg-gray-50 text-gray-500 text-sm font-medium peer-checked:bg-emerald-600 peer-checked:text-white peer-checked:border-emerald-600 transition-all flex items-center gap-2">
                                    <span class="material-symbols-outlined text-sm"><?= $label[1] ?></span>
                                    <?= $label[0] ?>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>

                    <!-- Uploads with Previews -->
                    <div class="grid md:grid-cols-2 gap-6 pt-4 border-t border-gray-100">
                        <!-- Hero Upload -->
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold">Hero / Cover Image (Landscape)</label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50 text-center hover:bg-white hover:border-pink-500 transition-colors relative overflow-hidden group">
                                <input type="file" name="hero_image" accept="image/*"
                                    onchange="previewImage(this, 'hero-preview')"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 cursor-pointer relative z-10">

                                <!-- Preview Image -->
                                <?php $heroUrl = isset($invitation['hero_image_link']) && $invitation['hero_image_link'] ? $invitation['hero_image_link'] : ''; ?>
                                <div class="mt-4 rounded-lg overflow-hidden border border-gray-200 relative">
                                    <img id="hero-preview" src="<?= $heroUrl ?>"
                                        class="<?= $heroUrl ? '' : 'hidden' ?> w-full h-40 object-cover transform group-hover:scale-105 transition-transform duration-500"
                                        alt="Hero Preview">

                                    <?php if ($heroUrl): ?>
                                        <div class="absolute top-2 right-2 z-20">
                                            <label
                                                class="flex items-center gap-2 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg shadow-sm cursor-pointer hover:bg-red-50 text-red-600 border border-red-200">
                                                <input type="checkbox" name="delete_hero" value="1"
                                                    class="rounded text-red-500 focus:ring-red-500 w-4 h-4">
                                                <span class="text-xs font-bold">Hapus</span>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Music Upload -->
                        <div class="space-y-3">
                            <label class="block text-sm font-semibold">Music File (.mp3)</label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50 text-center hover:bg-white hover:border-pink-500 transition-colors">
                                <input type="file" name="music_file" accept=".mp3"
                                    onchange="previewAudio(this, 'music-preview')"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 cursor-pointer">

                                <!-- Audio Player -->
                                <?php $musicUrl = isset($invitation['music_file']) && $invitation['music_file'] ? $invitation['music_file'] : ''; ?>
                                <div id="music-preview-container"
                                    class="mt-4 <?= $musicUrl ? '' : 'hidden' ?> relative group">
                                    <audio id="music-preview" controls class="w-full h-8"
                                        src="<?= $musicUrl ?>"></audio>
                                    <?php if ($musicUrl): ?>
                                        <div class="flex items-center justify-between mt-2">
                                            <p class="text-xs text-green-600 flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm">check_circle</span> File
                                                actively playing
                                            </p>
                                            <label
                                                class="flex items-center gap-2 cursor-pointer text-red-500 hover:text-red-700">
                                                <input type="checkbox" name="delete_music" value="1"
                                                    class="rounded text-red-500 focus:ring-red-500 w-4 h-4">
                                                <span class="text-xs font-bold">Hapus File</span>
                                            </label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Upload -->
                        <div class="space-y-3 md:col-span-2">
                            <label class="block text-sm font-semibold">Gallery Photos (Max 6)</label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl p-6 bg-gray-50 text-center hover:bg-white hover:border-pink-500 transition-colors">
                                <span
                                    class="material-symbols-outlined text-4xl text-gray-300 mb-2">add_photo_alternate</span>
                                <input type="file" name="gallery_images[]" multiple accept="image/*"
                                    onchange="previewGallery(this, 'gallery-preview-grid')"
                                    class="w-full text-sm text-center text-gray-500 file:block file:mx-auto file:mb-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-white hover:file:bg-black cursor-pointer">
                                <p class="text-xs text-gray-400 mt-2">Dapat memilih lebih dari 1 foto sekaligus</p>
                            </div>

                            <!-- Gallery Preview Grid -->
                            <div id="gallery-preview-grid" class="grid grid-cols-4 sm:grid-cols-6 gap-2 mt-2">
                                <?php
                                $galleryLinks = isset($invitation['gallery_links']) ? json_decode($invitation['gallery_links'], true) : [];
                                if (is_array($galleryLinks)):
                                    foreach ($galleryLinks as $i => $link):
                                        ?>
                                        <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 relative group"
                                            id="gallery-item-<?= $i ?>">
                                            <img src="<?= htmlspecialchars(trim($link)) ?>" class="w-full h-full object-cover">
                                            <input type="hidden" name="existing_gallery[]"
                                                value="<?= htmlspecialchars($link) ?>">

                                            <!-- Delete Button -->
                                            <button type="button"
                                                onclick="document.getElementById('gallery-item-<?= $i ?>').remove()"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 shadow-lg opacity-0 group-hover:opacity-100 transition hover:bg-red-600">
                                                <span class="material-symbols-outlined text-xs block">close</span>
                                            </button>
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Story & Wishes -->
                    <!-- 5. Love Story (Timeline) -->
                    <div class="pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-pink-100 text-pink-600 rounded-lg">
                                    <span class="material-symbols-outlined">timeline</span>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Kisah Cinta (Timeline)</h2>
                            </div>
                            <button type="button" onclick="addStory()"
                                class="px-3 py-1.5 text-sm font-medium text-pink-600 bg-pink-50 rounded-lg hover:bg-pink-100 transition">
                                + Tambah Cerita
                            </button>
                        </div>

                        <input type="hidden" id="love_story_json" name="love_story"
                            value="<?= htmlspecialchars($invitation['love_story'] ?? '[]') ?>">
                        <div id="love-stories-container" class="space-y-3">
                            <!-- Rendered by JS -->
                        </div>
                        <p class="text-xs text-gray-400 mt-2">*Urutkan dari yang terlama ke terbaru</p>
                    </div>

                    <!-- 6. Wedding Gift (Dynamic) -->
                    <div class="pt-6 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-emerald-100 text-emerald-600 rounded-lg">
                                    <span class="material-symbols-outlined">card_giftcard</span>
                                </div>
                                <h2 class="text-xl font-bold text-gray-900">Wedding Gift</h2>
                            </div>
                            <button type="button" onclick="addGift()"
                                class="px-3 py-1.5 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                                + Tambah Rekening
                            </button>
                        </div>

                        <input type="hidden" id="gifts_json" name="gifts"
                            value="<?= htmlspecialchars($invitation['gifts'] ?? '[]') ?>">
                        <div id="gifts-container" class="space-y-3">
                            <!-- Rendered by JS -->
                        </div>
                    </div>

                    <!-- 7. Wishes Opening -->
                    <div class="pt-6 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-semibold mb-1.5">Kalimat Pembuka Doa</label>
                            <input type="text" name="wishes_opening"
                                value="<?= htmlspecialchars($invitation['wishes_opening'] ?? '') ?>"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-emerald-500 outline-none text-sm"
                                placeholder="Mohon doa restu Anda...">
                        </div>
                    </div>

                </div>
                <!-- Guest List & Export -->
                <?php
                if ($id):
                    $stmtGuests = $pdo->prepare("SELECT * FROM guests WHERE invitation_id = ? ORDER BY created_at DESC");
                    $stmtGuests->execute([$id]);
                    $guests = $stmtGuests->fetchAll();
                    ?>
                    <div class="mt-8 border-t border-gray-100 pt-8">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                <span class="material-symbols-outlined text-yellow-500">groups</span>
                                Daftar Tamu (RSVP)
                            </h2>
                            <a href="export_guests.php?id=<?= $id ?>" target="_blank"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg flex items-center gap-2 hover:bg-green-500 transition-colors">
                                <span class="material-symbols-outlined">table_view</span>
                                Export Excel
                            </a>
                        </div>

                        <div class="overflow-x-auto bg-gray-50 rounded-xl border border-gray-200">
                            <table class="w-full text-left text-gray-700">
                                <thead class="bg-gray-100 text-gray-500 uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-3">Nama</th>
                                        <th class="px-6 py-3">Status</th>
                                        <th class="px-6 py-3">Ucapan</th>
                                        <th class="px-6 py-3">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if (count($guests) > 0): ?>
                                        <?php foreach ($guests as $guest): ?>
                                            <tr class="hover:bg-gray-100">
                                                <td class="px-6 py-4 font-medium text-gray-900">
                                                    <?= htmlspecialchars($guest['name']) ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <?php if ($guest['status'] === 'present'): ?>
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Hadir</span>
                                                    <?php else: ?>
                                                        <span
                                                            class="px-2 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">Tidak
                                                            Hadir</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-6 py-4 text-sm italic">"<?= htmlspecialchars($guest['message']) ?>"
                                                </td>
                                                <td class="px-6 py-4 text-xs text-gray-500">
                                                    <?= date('d M Y H:i', strtotime($guest['created_at'])) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">Belum ada data tamu.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

            </section>

        </form>
    </main>

    <script>
        // -- LOVE STORY LOGIC --
        let stories = [];
        try {
            const raw = document.getElementById('love_story_json').value;
            stories = raw ? JSON.parse(raw) : [];
            if (!Array.isArray(stories)) stories = [];
        } catch (e) { stories = []; }

        const storyContainer = document.getElementById('love-stories-container');

        function renderStories() {
            storyContainer.innerHTML = '';
            stories.forEach((story, index) => {
                const div = document.createElement('div');
                div.className = 'flex gap-3 items-start bg-gray-50 p-4 rounded-xl border border-gray-200 relative group';
                div.innerHTML = `
                    <div class="grid w-full gap-3">
                        <div class="flex gap-3">
                             <div class="w-1/3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Tahun / Tanggal</label>
                                <input type="text" value="${story.year || ''}" onchange="updateStory(${index}, 'year', this.value)" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500 outline-none" placeholder="2018 / 12 Jan 2020">
                            </div>
                            <div class="w-2/3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Judul Momen</label>
                                <input type="text" value="${story.title || ''}" onchange="updateStory(${index}, 'title', this.value)" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500 outline-none" placeholder="Pertama Bertemu">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Cerita Singkat</label>
                            <textarea onchange="updateStory(${index}, 'story', this.value)" rows="2" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500 outline-none" placeholder="Ceritakan detailnya...">${story.story || ''}</textarea>
                        </div>
                    </div>
                    <button type="button" onclick="removeStory(${index})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition">
                        <span class="material-symbols-outlined text-lg">cancel</span>
                    </button>
                `;
                storyContainer.appendChild(div);
            });
            if (stories.length === 0) {
                storyContainer.innerHTML = '<p class="text-sm text-gray-400 italic text-center py-4">Belum ada kisah cinta yang ditambahkan.</p>';
            }
            updateHiddenInput('love_story_json', stories);
        }

        function addStory() {
            stories.push({ year: '', title: '', story: '' });
            renderStories();
        }

        function removeStory(index) {
            stories.splice(index, 1);
            renderStories();
        }

        function updateStory(index, field, value) {
            stories[index][field] = value;
            updateHiddenInput('love_story_json', stories);
        }

        // -- WEDDING GIFT LOGIC --
        let gifts = [];
        try {
            const rawGifts = document.getElementById('gifts_json').value;
            gifts = rawGifts ? JSON.parse(rawGifts) : [];
            if (!Array.isArray(gifts)) gifts = [];
        } catch (e) { gifts = []; }

        const giftContainer = document.getElementById('gifts-container');
        const bankIcons = ['bca', 'bri', 'mandiri', 'bni', 'cimb', 'dana', 'ovo', 'gopay', 'shopeepay', 'linkaja'];

        function renderGifts() {
            giftContainer.innerHTML = '';
            gifts.forEach((gift, index) => {
                const div = document.createElement('div');
                div.className = 'flex gap-3 items-start bg-gray-50 p-4 rounded-xl border border-gray-200 relative';

                // Build Options for Select
                const options = bankIcons.map(b => `<option value="${b}" ${gift.logo === b ? 'selected' : ''}>${b.toUpperCase()}</option>`).join('');

                div.innerHTML = `
                    <div class="grid w-full gap-3">
                        <div class="flex gap-3">
                            <div class="w-1/3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Bank / E-Wallet</label>
                                <select onchange="updateGift(${index}, 'logo', this.value)" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none uppercase">
                                    <option value="other">Lainnya</option>
                                    ${options}
                                </select>
                            </div>
                            <div class="w-2/3">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Nama Bank (Teks)</label>
                                <input type="text" value="${gift.bank || ''}" onchange="updateGift(${index}, 'bank', this.value)" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Bank Central Asia">
                            </div>
                        </div>
                        <div class="flex gap-3">
                             <div class="w-1/2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">No. Rekening</label>
                                <input type="text" value="${gift.number || ''}" onchange="updateGift(${index}, 'number', this.value)" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none font-mono" placeholder="1234xxxx">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-xs font-bold text-gray-500 mb-1">Atas Nama</label>
                                <input type="text" value="${gift.owner || ''}" onchange="updateGift(${index}, 'owner', this.value)" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Nama Pemilik">
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="removeGift(${index})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 transition">
                        <span class="material-symbols-outlined text-lg">cancel</span>
                    </button>
                `;
                giftContainer.appendChild(div);
            });
            updateHiddenInput('gifts_json', gifts);
        }

        function addGift() {
            gifts.push({ bank: '', number: '', owner: '', logo: 'bca' });
            renderGifts();
        }

        function removeGift(index) {
            gifts.splice(index, 1);
            renderGifts();
        }

        function updateGift(index, field, value) {
            gifts[index][field] = value;
            // Auto-fill bank name if choosing from dropdown
            if (field === 'logo' && value !== 'other') {
                gifts[index]['bank'] = value.toUpperCase();
                // We re-render to show the updated name, but carefull about focus loss.
                // For now, let's just update data. The user can manually edit name if they want.
            }
            updateHiddenInput('gifts_json', gifts);
        }

        function updateHiddenInput(id, data) {
            document.getElementById(id).value = JSON.stringify(data);
        }

        // -- MEDIA PREVIEWS --
        function previewImage(input, targetId) {
            const preview = document.getElementById(targetId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewAudio(input, targetId) {
            const preview = document.getElementById(targetId);
            const container = document.getElementById(targetId + '-container');
            if (input.files && input.files[0]) {
                const url = URL.createObjectURL(input.files[0]);
                preview.src = url;
                container.classList.remove('hidden');
            }
        }

        function previewGallery(input, targetId) {
            const container = document.getElementById(targetId);
            container.innerHTML = ''; // Clear previous previews

            if (input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const div = document.createElement('div');
                        div.className = 'aspect-square rounded-lg overflow-hidden border border-gray-200 relative group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white text-xs">New</div>
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        // Initial Render
        renderStories();
        renderGifts();
    </script>

</body>

</html>
```