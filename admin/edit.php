<?php
session_start();
require_once '../config.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? 0;

if (!$type || !$id) {
    header("Location: index.php");
    exit;
}

$data = [];
$title = "Edit Item";

// Fetch data based on type
if ($type === 'category') {
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    $title = "Edit Category";
} elseif ($type === 'product') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    $categories = $pdo->query("SELECT * FROM categories")->fetchAll(); // Need categories for dropdown
    $title = "Edit Product";
} elseif ($type === 'testimonial') {
    $stmt = $pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch();
    $title = "Edit Testimonial";
}

if (!$data) {
    echo "Item not found.";
    exit;
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= htmlspecialchars($title) ?> - Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-4">

    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-2xl w-full max-w-2xl">
        <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
            <h1 class="text-2xl font-bold text-emerald-400 flex items-center gap-2">
                <span class="material-symbols-outlined">edit_square</span>
                <?= htmlspecialchars($title) ?>
            </h1>
            <a href="index.php" class="text-gray-400 hover:text-white transition-colors flex items-center gap-1 text-sm font-medium">
                <span class="material-symbols-outlined text-lg">arrow_back</span> Back
            </a>
        </div>

        <form action="handler.php" method="POST" enctype="multipart/form-data" class="space-y-6">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            
            <?php if ($type === 'category'): ?>
                <input type="hidden" name="action" value="update_category">
                <div>
                    <label class="block text-sm font-semibold text-gray-400 mb-2">Category Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                </div>

            <?php elseif ($type === 'product'): ?>
                <input type="hidden" name="action" value="update_product">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Product Name</label>
                        <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Category</label>
                        <select name="category_id" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $data['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Price (Rp)</label>
                        <input type="number" name="price" value="<?= htmlspecialchars($data['price']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Discount Price (Optional)</label>
                        <input type="number" name="discounted_price" value="<?= htmlspecialchars($data['discounted_price']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Product Image</label>
                        <div class="flex items-center gap-4">
                            <?php if(!empty($data['image'])): ?>
                                <?php $imgSrc = strpos($data['image'], 'http') === 0 ? $data['image'] : '../' . $data['image']; ?>
                                <div class="w-20 h-20 bg-gray-900 rounded-lg border border-gray-600 flex items-center justify-center overflow-hidden">
                                    <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-full h-full object-cover">
                                </div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 transition-colors cursor-pointer bg-gray-900/50 rounded-xl border border-gray-600">
                                <p class="text-xs text-gray-500 mt-2">Leave empty to keep current image. Recommended: Square ratio.</p>
                            </div>
                        </div>
                    </div>
                     <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Product Link</label>
                        <input type="text" name="link" value="<?= htmlspecialchars($data['link']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner">
                    </div>
                </div>

            <?php elseif ($type === 'testimonial'): ?>
                <input type="hidden" name="action" value="update_testimonial">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Couple Name</label>
                        <input type="text" name="couple_name" value="<?= htmlspecialchars($data['couple_name']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" min="1" max="5" value="<?= htmlspecialchars($data['rating']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Quote</label>
                        <textarea name="quote" rows="4" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required><?= htmlspecialchars($data['quote']) ?></textarea>
                    </div>
                     <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-400 mb-2">Client Photo</label>
                         <div class="flex items-center gap-4">
                            <?php if(!empty($data['image'])): ?>
                                <?php $imgSrc = strpos($data['image'], 'http') === 0 ? $data['image'] : '../' . $data['image']; ?>
                                <div class="w-16 h-16 bg-gray-900 rounded-full border border-gray-600 flex items-center justify-center overflow-hidden">
                                    <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-full h-full object-cover">
                                </div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 transition-colors cursor-pointer bg-gray-900/50 rounded-xl border border-gray-600">
                                <p class="text-xs text-gray-500 mt-2">Leave empty to keep current photo.</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="flex justify-end pt-6 border-t border-gray-700 mt-6">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 px-8 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center justify-center gap-2 hover:-translate-y-1 active:translate-y-0 text-white">
                    <span class="material-symbols-outlined">save</span> Save Changes
                </button>
            </div>
        </form>
    </div>

</body>
</html>
