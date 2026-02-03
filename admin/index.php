<?php
session_start();
require_once '../config.php';

// Fetch all data for display
$settings = $pdo->query("SELECT * FROM settings WHERE id = 1")->fetch();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$products = $pdo->query("SELECT * FROM products")->fetchAll();
$testimonials = $pdo->query("SELECT * FROM testimonials")->fetchAll();

?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Panel - syifazharstudio</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(31, 41, 55, 0.5);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(16, 185, 129, 0.5);
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: rgba(16, 185, 129, 0.8);
        }
    </style>
</head>
<body class="bg-gray-900 text-white h-screen flex overflow-hidden selection:bg-emerald-500 selection:text-white">

    <!-- Mobile Header -->
    <div class="md:hidden fixed w-full bg-gray-800 border-b border-gray-700 z-50 flex items-center justify-between p-4 shadow-lg">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-400">dashboard</span>
            <h1 class="text-lg font-bold text-white tracking-wide">Admin Panel</h1>
        </div>
        <button id="mobile-menu-btn" class="text-gray-300 hover:text-emerald-400 focus:outline-none transition-colors p-1 rounded-md active:bg-gray-700">
            <span class="material-symbols-outlined text-2xl">menu</span>
        </button>
    </div>

    <!-- Sidebar Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm transition-opacity duration-300" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-gray-800 border-r border-gray-700 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-out z-50 md:static flex flex-col shadow-2xl h-full flex-shrink-0">
        <div class="p-6 hidden md:block border-b border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                     <span class="material-symbols-outlined text-2xl">admin_panel_settings</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white tracking-tight">Admin Panel</h1>
                    <p class="text-gray-500 text-xs font-medium uppercase tracking-wider mt-0.5">syifazharstudio</p>
                </div>
            </div>
        </div>
        
        <div class="md:hidden p-6 border-b border-gray-700 flex justify-between items-center bg-gray-850">
            <div>
                 <h1 class="text-xl font-bold text-white">Menu</h1>
                 <p class="text-gray-500 text-xs text-emerald-400">Navigation</p>
            </div>
             <button onclick="toggleSidebar()" class="text-gray-400 hover:text-white transition-transform hover:rotate-90">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-6 md:mt-6 overflow-y-auto scrollbar-hide pb-6">
            <div class="px-4 pb-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Main Menu</div>
            <button onclick="showTab('settings'); toggleSidebarIfMobile()" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-700/50 focus:bg-gray-700 transition-all duration-200 tab-btn active-tab flex items-center gap-3 group" data-tab="settings">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">settings</span>
                <span class="font-medium">Settings</span>
            </button>
            <button onclick="showTab('categories'); toggleSidebarIfMobile()" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-700/50 focus:bg-gray-700 transition-all duration-200 tab-btn flex items-center gap-3 group" data-tab="categories">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">category</span>
                <span class="font-medium">Categories</span>
            </button>
            <button onclick="showTab('products'); toggleSidebarIfMobile()" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-700/50 focus:bg-gray-700 transition-all duration-200 tab-btn flex items-center gap-3 group" data-tab="products">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">shopping_bag</span>
                <span class="font-medium">Products</span>
            </button>
            <button onclick="showTab('testimonials'); toggleSidebarIfMobile()" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-700/50 focus:bg-gray-700 transition-all duration-200 tab-btn flex items-center gap-3 group" data-tab="testimonials">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">reviews</span>
                <span class="font-medium">Testimonials</span>
            </button>
            <a href="invitations.php" class="w-full text-left px-4 py-3 rounded-xl hover:bg-gray-700/50 focus:bg-gray-700 transition-all duration-200 tab-btn flex items-center gap-3 group text-gray-400 hover:text-white">
                <span class="material-symbols-outlined text-xl group-hover:scale-110 transition-transform">card_giftcard</span>
                <span class="font-medium">Invitations</span>
            </a>


        </nav>
        <div class="p-4 border-t border-gray-700 bg-gray-850">
            <a href="../index.php" target="_blank" class="block w-full text-center px-4 py-3 bg-emerald-600 hover:bg-emerald-500 rounded-xl transition-all font-bold shadow-lg shadow-emerald-900/20 hover:shadow-emerald-900/40 flex items-center justify-center gap-2 group">
                <span>View Website</span>
                <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">open_in_new</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto bg-gray-900 w-full relative scroll-smooth custom-scrollbar">
        <!-- Content Container -->
        <div class="p-4 md:p-10 pt-24 md:pt-10 max-w-7xl mx-auto min-h-full pb-20">
            
            <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 px-6 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-lg animate-[fadeIn_0.3s_ease-out]">
                <div class="bg-emerald-500/20 p-2 rounded-full">
                    <span class="material-symbols-outlined text-xl">check</span>
                </div>
                <span class="font-medium"><?= $_SESSION['success']; unset($_SESSION['success']); ?></span>
            </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['error'])): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-6 py-4 rounded-xl mb-8 flex items-center gap-3 shadow-lg animate-[fadeIn_0.3s_ease-out]">
                <div class="bg-red-500/20 p-2 rounded-full">
                     <span class="material-symbols-outlined text-xl">priority_high</span>
                </div>
                <span class="font-medium"><?= $_SESSION['error']; unset($_SESSION['error']); ?></span>
            </div>
            <?php endif; ?>
            
            <!-- Settings Section -->
            <div id="settings" class="tab-content block space-y-6 animate-[fadeIn_0.3s_ease-out]">
                <div class="flex items-center gap-4 mb-2">
                    <div class="p-3 bg-gray-800 rounded-xl shadow-sm border border-gray-700">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl">settings</span>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-white">General Settings</h2>
                        <p class="text-gray-400 text-sm mt-1">Manage your website's core configuration</p>
                    </div>
                </div>

                <form action="handler.php" method="POST" enctype="multipart/form-data" class="space-y-8 bg-gray-800 p-6 md:p-8 rounded-2xl border border-gray-700/50 shadow-xl">
                    <input type="hidden" name="action" value="update_settings">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Branding -->
                        <div class="md:col-span-2 border-b border-gray-700 pb-2 mb-2">
                            <h3 class="text-lg font-bold text-emerald-400 flex items-center gap-2">
                                <span class="material-symbols-outlined">branding_watermark</span> Branding
                            </h3>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Site Title</label>
                            <input type="text" name="site_title" value="<?= htmlspecialchars($settings['site_title']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Logo Text</label>
                            <input type="text" name="logo_text" value="<?= htmlspecialchars($settings['logo_text']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner">
                        </div>
                        
                        <div class="md:col-span-2">
                             <label class="block text-sm font-semibold text-gray-300 mb-2">Logo Image & Favicon</label>
                             <div class="flex items-center gap-4">
                                <?php if(!empty($settings['logo_image'])): ?>
                                    <div class="w-16 h-16 bg-white/5 rounded-lg border border-gray-600 flex items-center justify-center p-1">
                                        <img src="../<?= htmlspecialchars($settings['logo_image']) ?>" alt="Current Logo" class="max-w-full max-h-full object-contain">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="logo_image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 transition-colors cursor-pointer bg-gray-900/50 rounded-xl border border-gray-600">
                             </div>
                             <p class="text-xs text-gray-500 mt-2">Replaces the "S" icon. Recommended: Square ratio (e.g., 512x512px PNG).</p>
                        </div>

                        <!-- Hero Section -->
                        <div class="md:col-span-2 border-b border-gray-700 pb-2 mb-2 mt-4">
                            <h3 class="text-lg font-bold text-emerald-400 flex items-center gap-2">
                                <span class="material-symbols-outlined">home</span> Hero Section
                            </h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Hero Title</label>
                            <input type="text" name="hero_title" value="<?= htmlspecialchars($settings['hero_title']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner">
                            <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">info</span>
                                Plain text only. The last word will be automatically styled.
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Hero Description</label>
                            <textarea name="hero_description" rows="3" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner leading-relaxed"><?= htmlspecialchars($settings['hero_description']) ?></textarea>
                        </div>

                        <!-- Contact -->
                        <div class="md:col-span-2 border-b border-gray-700 pb-2 mb-2 mt-4">
                            <h3 class="text-lg font-bold text-emerald-400 flex items-center gap-2">
                                <span class="material-symbols-outlined">contact_support</span> Contact & Socials
                            </h3>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Address</label>
                            <textarea name="address" id="address_input" rows="2" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner"><?= htmlspecialchars($settings['address']) ?></textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Map Location & Address</label>
                            
                            <!-- Hidden inputs for Coordinates -->
                            <input type="hidden" name="latitude" id="latitude" value="<?= htmlspecialchars($settings['latitude'] ?? '-7.7956') ?>">
                            <input type="hidden" name="longitude" id="longitude" value="<?= htmlspecialchars($settings['longitude'] ?? '110.3695') ?>">
                            <input type="hidden" name="map_embed" id="map_embed" value="<?= htmlspecialchars($settings['map_embed']) ?>">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="md:col-span-2 h-[300px] rounded-xl overflow-hidden shadow-lg border-2 border-slate-600 relative z-0">
                                    <div id="map" class="w-full h-full z-10"></div>
                                </div>
                                <div class="space-y-4">
                                     <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 text-sm text-blue-300">
                                        <div class="flex items-start gap-2">
                                            <span class="material-symbols-outlined text-lg mt-0.5">info</span>
                                            <p><strong>How to use:</strong><br>
                                            • Drag the marker to pin location.<br>
                                            • Or type address below to find it.<br>
                                            • Address updates automatically.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">WhatsApp Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">
                                    <span class="material-symbols-outlined text-lg">call</span>
                                </span>
                                <input type="text" name="whatsapp_number" value="<?= htmlspecialchars($settings['whatsapp_number']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">Instagram Link</label>
                             <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">
                                    <span class="material-symbols-outlined text-lg">photo_camera</span>
                                </span>
                                 <input type="text" name="instagram_link" value="<?= htmlspecialchars($settings['instagram_link']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-300 mb-2">TikTok Link</label>
                             <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">
                                    <span class="material-symbols-outlined text-lg">smart_display</span>
                                </span>
                                <input type="text" name="tiktok_link" value="<?= htmlspecialchars($settings['tiktok_link']) ?>" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner font-mono">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end pt-6 border-t border-gray-700">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-8 py-3 rounded-xl font-bold transition-all shadow-lg shadow-emerald-900/30 hover:shadow-emerald-900/50 hover:-translate-y-1 active:translate-y-0 flex items-center gap-2">
                            <span class="material-symbols-outlined">save</span>
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>

            <!-- Categories Section -->
            <div id="categories" class="tab-content hidden space-y-6 animate-[fadeIn_0.3s_ease-out]">
                <div class="flex items-center gap-4 mb-2">
                    <div class="p-3 bg-gray-800 rounded-xl shadow-sm border border-gray-700">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl">category</span>
                    </div>
                    <div>
                         <h2 class="text-2xl md:text-3xl font-bold text-white">Categories</h2>
                        <p class="text-gray-400 text-sm mt-1">Organize your products efficiently</p>
                    </div>
                </div>
                
                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-4 text-emerald-400 border-b border-gray-700 pb-2">Add New Category</h3>
                    <form action="handler.php" method="POST" class="flex flex-col md:flex-row gap-4">
                        <input type="hidden" name="action" value="add_category">
                        <input type="text" name="name" placeholder="Category Name" class="flex-1 bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all" required>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 px-8 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center justify-center gap-2 hover:-translate-y-1 active:translate-y-0">
                             <span class="material-symbols-outlined">add_circle</span> Add
                        </button>
                    </form>
                </div>

                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left min-w-[600px] whitespace-nowrap">
                            <thead class="bg-gray-700/50 text-emerald-400 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4 w-full">Name</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50 text-sm">
                                <?php foreach($categories as $cat): ?>
                                <tr class="hover:bg-gray-700/30 transition-colors group">
                                    <td class="px-6 py-4 text-gray-500 font-mono">#<?= $cat['id'] ?></td>
                                    <td class="px-6 py-4 font-bold text-base"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="px-6 py-4 text-end">
                                        <div class="flex justify-end gap-2">
                                            <a href="edit.php?type=category&id=<?= $cat['id'] ?>" class="text-blue-400 hover:text-blue-300 bg-blue-400/10 hover:bg-blue-400/20 px-3 py-2 rounded-lg transition-all flex items-center gap-1 font-bold text-xs">
                                                <span class="material-symbols-outlined text-sm">edit</span> Edit
                                            </a>
                                            <form action="handler.php" method="POST" class="inline" onsubmit="return confirm('Delete this category?');">
                                            <input type="hidden" name="action" value="delete_category">
                                            <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                                            <button type="submit" class="text-red-400 hover:text-red-300 bg-red-400/10 hover:bg-red-400/20 px-4 py-2 rounded-lg transition-all flex items-center gap-2 ml-auto font-bold text-xs disabled:opacity-50">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Products Section -->
            <div id="products" class="tab-content hidden space-y-6 animate-[fadeIn_0.3s_ease-out]">
                 <div class="flex items-center gap-4 mb-2">
                    <div class="p-3 bg-gray-800 rounded-xl shadow-sm border border-gray-700">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl">shopping_bag</span>
                    </div>
                    <div>
                         <h2 class="text-2xl md:text-3xl font-bold text-white">Products</h2>
                        <p class="text-gray-400 text-sm mt-1">Manage product listings and prices</p>
                    </div>
                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-6 text-emerald-400 border-b border-gray-700 pb-2">Add New Product</h3>
                    <form action="handler.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="hidden" name="action" value="add_product">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-400 mb-2">Product Name</label>
                            <input type="text" name="name" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                        </div>
                        
                        <div>
                             <label class="block text-sm font-semibold text-gray-400 mb-2">Category</label>
                            <div class="relative">
                                <select name="category_id" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 appearance-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                                    <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="absolute right-4 top-3.5 text-emerald-500 pointer-events-none">
                                    <span class="material-symbols-outlined">expand_more</span>
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-400 mb-2">Price (Start From)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-bold">Rp</span>
                                <input type="number" name="price" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 pr-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-400 mb-2">Discount Price (Optional)</label>
                             <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-bold">Rp</span>
                                <input type="number" name="discounted_price" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 pr-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner">
                            </div>
                        </div>

                         <div>
                            <label class="block text-sm font-semibold text-gray-400 mb-2">Product Link</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-gray-500">
                                    <span class="material-symbols-outlined text-lg">link</span>
                                </span>
                                <input type="text" name="link" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" placeholder="https://...">
                            </div>
                        </div>
                        
                         <div class="md:col-span-2">
                             <label class="block text-sm font-semibold text-gray-400 mb-2">Product Image</label>
                             <div class="relative">
                                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 transition-colors cursor-pointer bg-gray-900/50 rounded-xl border border-gray-600" required>
                                <p class="text-xs text-gray-500 mt-2">Recommended: Square ratio (e.g., 500x500px)</p>
                            </div>
                        </div>

                        <div class="md:col-span-2 flex justify-end pt-4 border-t border-gray-700/50">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 px-8 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center justify-center gap-2 hover:-translate-y-1 active:translate-y-0">
                                <span class="material-symbols-outlined">add_circle</span> Add Product
                            </button>
                        </div>
                    </form>
                </div>
                
                 <!-- Product List (Card Grid for visual appeal + Table for data) -->
                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left min-w-[800px] whitespace-nowrap">
                            <thead class="bg-gray-700/50 text-emerald-400 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Image</th>
                                    <th class="px-6 py-4">Product Name</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Price</th>
                                    <th class="px-6 py-4">Discount</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50 text-sm">
                                <?php foreach($products as $prod): 
                                    $catName = 'Unknown';
                                    foreach($categories as $c) {
                                        if($c['id'] == $prod['category_id']) { $catName = $c['name']; break; }
                                    }
                                ?>
                                <tr class="hover:bg-gray-700/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden border border-gray-600 shadow-sm relative group-hover:border-emerald-500/50 transition-colors">
                                            <?php $imgSrc = strpos($prod['image'], 'http') === 0 ? $prod['image'] : '../' . $prod['image']; ?>
                                            <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" alt="prod">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base"><?= htmlspecialchars($prod['name']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-gray-700 text-gray-300 rounded-full text-xs font-semibold border border-gray-600">
                                            <?= htmlspecialchars($catName) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-emerald-400 font-medium">Rp <?= number_format($prod['price'], 0, ',', '.') ?></td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400">
                                        <?php if($prod['discounted_price']): ?>
                                            <span class="line-through text-red-400/70 mr-2">Rp <?= number_format($prod['price'], 0, ',', '.') ?></span>
                                            <span class="text-emerald-400">Rp <?= number_format($prod['discounted_price'], 0, ',', '.') ?></span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="edit.php?type=product&id=<?= $prod['id'] ?>" class="text-blue-400 hover:text-blue-300 bg-blue-400/10 hover:bg-blue-400/20 px-3 py-2 rounded-lg transition-all flex items-center gap-1 font-bold text-xs">
                                                <span class="material-symbols-outlined text-sm">edit</span> Edit
                                            </a>
                                            <form action="handler.php" method="POST" class="inline" onsubmit="return confirm('Delete this product?');">
                                            <input type="hidden" name="action" value="delete_product">
                                            <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                                            <button type="submit" class="text-red-400 hover:text-red-300 bg-red-400/10 hover:bg-red-400/20 px-4 py-2 rounded-lg transition-all flex items-center gap-2 ml-auto font-bold text-xs disabled:opacity-50">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Testimonials Section -->
            <div id="testimonials" class="tab-content hidden space-y-6 animate-[fadeIn_0.3s_ease-out]">
                 <div class="flex items-center gap-4 mb-2">
                    <div class="p-3 bg-gray-800 rounded-xl shadow-sm border border-gray-700">
                        <span class="material-symbols-outlined text-emerald-400 text-3xl">reviews</span>
                    </div>
                    <div>
                         <h2 class="text-2xl md:text-3xl font-bold text-white">Testimonials</h2>
                        <p class="text-gray-400 text-sm mt-1">Share client feedback</p>
                    </div>
                </div>

                <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 mb-6 shadow-lg">
                    <h3 class="text-lg font-bold mb-6 text-emerald-400 border-b border-gray-700 pb-2">Add New Testimonial</h3>
                    <form action="handler.php" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="hidden" name="action" value="add_testimonial">
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-400 mb-2">Couple Name</label>
                            <input type="text" name="couple_name" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                        </div>
                        
                        <div>
                             <label class="block text-sm font-semibold text-gray-400 mb-2">Rating (1-5)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-3.5 text-yellow-500">
                                    <span class="material-symbols-outlined text-lg">star</span>
                                </span>
                                <input type="number" name="rating" min="1" max="5" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white pl-12 px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner" required>
                            </div>
                        </div>
                        
                         <div>
                             <label class="block text-sm font-semibold text-gray-400 mb-2">Client Photo</label>
                             <div class="relative">
                                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-600 file:text-white hover:file:bg-emerald-500 transition-colors cursor-pointer bg-gray-900/50 rounded-xl border border-gray-600" required>
                            </div>
                        </div>

                         <div class="md:col-span-2">
                             <label class="block text-sm font-semibold text-gray-400 mb-2">Quote</label>
                            <textarea name="quote" rows="3" class="w-full bg-gray-900/50 border-gray-600 rounded-xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all shadow-inner leading-relaxed" required></textarea>
                        </div>

                        <div class="md:col-span-2 flex justify-end pt-4 border-t border-gray-700/50">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 px-8 py-3 rounded-xl font-bold transition-all shadow-lg flex items-center justify-center gap-2 hover:-translate-y-1 active:translate-y-0">
                                <span class="material-symbols-outlined">add_comment</span> Add Testimonial
                            </button>
                        </div>
                    </form>
                </div>

                <div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
                    <div class="overflow-x-auto custom-scrollbar">
                         <table class="w-full text-left min-w-[700px] whitespace-nowrap">
                            <thead class="bg-gray-700/50 text-emerald-400 uppercase text-xs font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Image</th>
                                    <th class="px-6 py-4">Couple Name</th>
                                    <th class="px-6 py-4">Rating</th>
                                    <th class="px-6 py-4 w-1/3">Quote</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50 text-sm">
                                <?php foreach($testimonials as $testi): ?>
                                <tr class="hover:bg-gray-700/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-600 shadow-sm relative group-hover:border-emerald-500/50 transition-colors">
                                            <?php $imgSrc = strpos($testi['image'], 'http') === 0 ? $testi['image'] : '../' . $testi['image']; ?>
                                            <img src="<?= htmlspecialchars($imgSrc) ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" alt="user">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-base"><?= htmlspecialchars($testi['couple_name']) ?></td>
                                    <td class="px-6 py-4 text-yellow-500 flex items-center gap-0.5">
                                        <?php for($i=0; $i<$testi['rating']; $i++) echo '<span class="material-symbols-outlined text-sm">star</span>'; ?>
                                    </td>
                                    <td class="px-6 py-4 text-gray-400 italic truncate max-w-xs">"<?= htmlspecialchars($testi['quote']) ?>"</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="edit.php?type=testimonial&id=<?= $testi['id'] ?>" class="text-blue-400 hover:text-blue-300 bg-blue-400/10 hover:bg-blue-400/20 px-3 py-2 rounded-lg transition-all flex items-center gap-1 font-bold text-xs">
                                                <span class="material-symbols-outlined text-sm">edit</span> Edit
                                            </a>
                                            <form action="handler.php" method="POST" class="inline" onsubmit="return confirm('Delete this testimonial?');">
                                            <input type="hidden" name="action" value="delete_testimonial">
                                            <input type="hidden" name="id" value="<?= $testi['id'] ?>">
                                            <button type="submit" class="text-red-400 hover:text-red-300 bg-red-400/10 hover:bg-red-400/20 px-4 py-2 rounded-lg transition-all flex items-center gap-2 ml-auto font-bold text-xs disabled:opacity-50">
                                                <span class="material-symbols-outlined text-sm">delete</span>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>





    </main>

    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
            }
        }
        
        function toggleSidebarIfMobile() {
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        }

        mobileMenuBtn.addEventListener('click', toggleSidebar);

        function showTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Remove active class from buttons
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('bg-gray-700', 'text-white', 'border-l-4', 'border-emerald-500');
                el.classList.add('text-gray-400');
            });

            // Show selected content
            document.getElementById(tabId).classList.remove('hidden');
            // Add active class to button
            const btn = document.querySelector(`button[data-tab="${tabId}"]`);
            if(btn) {
                btn.classList.add('bg-gray-700', 'text-white', 'border-l-4', 'border-emerald-500');
                btn.classList.remove('text-gray-400');
            }
        }

        // Initialize first tab
        showTab('settings');

        // --- SMART MAP LOGIC ---
        document.addEventListener('DOMContentLoaded', function() {
            // Default: Yogyakarta if empty
            let lat = parseFloat(document.getElementById('latitude').value) || -7.7956;
            let lng = parseFloat(document.getElementById('longitude').value) || 110.3695;
            
            // Init Map
            var map = L.map('map').setView([lat, lng], 13);
            
            // Use Google Maps Tile Layer
            L.tileLayer('http://mt0.google.com/vt/lyrs=m&hl=id&x={x}&y={y}&z={z}', {
                attribution: 'Map data &copy; Google',
                maxZoom: 20
            }).addTo(map);

            var marker = L.marker([lat, lng], {draggable: true}).addTo(map);

            // Function to update hidden inputs
            function updateCoordinates(lat, lng) {
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            }

            // Function: Drag Marker -> Reverse Geocode (Get Address)
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateCoordinates(position.lat, position.lng);
                map.panTo(position);

                // Reverse Geocoding via Nominatim
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.lat}&lon=${position.lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if(data && data.display_name) {
                            document.getElementById('address_input').value = data.display_name;
                        }
                    })
                    .catch(err => console.error('Geocoding error:', err));
            });

            // Function: Type Address -> Forward Geocode (Set Marker)
            let timeout = null;
            const addressInput = document.getElementById('address_input');
            
            addressInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    const query = addressInput.value;
                    if(query.length > 5) {
                        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                            .then(response => response.json())
                            .then(data => {
                                if(data && data.length > 0) {
                                    const lat = parseFloat(data[0].lat);
                                    const lon = parseFloat(data[0].lon);
                                    
                                    // Move marker & map
                                    marker.setLatLng([lat, lon]);
                                    map.panTo([lat, lon]);
                                    updateCoordinates(lat, lon);
                                }
                            })
                            .catch(err => console.error('Search error:', err));
                    }
                }, 1000); // 1s debounce
            });
            
            // Fix Leaflet map sizing tab switching issue
            // When tab becomes visible, invalidateSize needs to be called
            const settingsTabBtn = document.querySelector('button[data-tab="settings"]');
            settingsTabBtn.addEventListener('click', function() {
                setTimeout(() => { map.invalidateSize(); }, 100);
            });
        });
    </script>
</html>
