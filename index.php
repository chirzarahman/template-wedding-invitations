<?php
require_once 'config.php';

// Fetch Settings
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$settings = $stmt->fetch();

// Fetch Categories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

// Fetch Products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

// Fetch Testimonials
$stmt = $pdo->query("SELECT * FROM testimonials");
$testimonials = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= htmlspecialchars($settings['site_title']) ?></title>
    <?php if(!empty($settings['logo_image'])): ?>
    <link rel="icon" type="image/png" href="<?= htmlspecialchars($settings['logo_image']) ?>">
    <?php endif; ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;family=Playfair+Display:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        @keyframes scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); } 
        }
        .animate-scroll {
            display: flex;
            width: max-content;
            animation: scroll 30s linear infinite;
        }
        .animate-scroll:hover {
            animation-play-state: paused;
        }
        /* Hide scrollbar for smooth look if any */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#8FA395",
              "forest": "#2F4F4F",
              "forest-dark": "#264040",
              "champagne": "#C5A880",
              "champagne-light": "#E6D7C3",
              "background-light": "#2F4F4F",
              "background-dark": "#1A2621",
              "text-main": "#FFFFFF",
              "card-light": "#FFFFFF",
              "cream-light": "#FDFBF7",
              "maroon": "#800000",
            },
            fontFamily: {
              "sans": ["Plus Jakarta Sans", "sans-serif"],
              "serif": ["Playfair Display", "serif"],
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.3)',
                'card': '0 10px 30px -5px rgba(0, 0, 0, 0.2)',
                'glow': '0 0 15px rgba(197, 168, 128, 0.3)',
            }
          },
        },
      }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; min-height: max(884px, 100dvh); }
        .font-serif { font-family: 'Playfair Display', serif; }
        .hero-gradient {background: linear-gradient(180deg, #2F4F4F 0%, #243c3c 100%); }
        .pill-scroll::-webkit-scrollbar { display: none; }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #2F4F4F; }
        ::-webkit-scrollbar-thumb { background: #C5A880; border-radius: 4px; }
    </style>
</head>
<body class="bg-forest text-white pb-0 selection:bg-champagne selection:text-forest">
    <div class="sticky top-0 z-50 bg-forest/90 backdrop-blur-md transition-all border-b border-white/10">
        <div class="flex items-center p-4 justify-between max-w-7xl mx-auto w-full px-6">
            <div class="flex items-center gap-2">
                <?php if(!empty($settings['logo_image'])): ?>
                    <img src="<?= htmlspecialchars($settings['logo_image']) ?>" alt="Logo" class="w-10 h-10 object-contain drop-shadow-md">
                <?php else: ?>
                    <div class="w-8 h-8 bg-champagne rounded-lg flex items-center justify-center text-forest font-serif font-bold text-lg shadow-glow border border-white/20">S</div>
                <?php endif; ?>
                <h2 class="text-white text-lg font-bold tracking-tight font-serif"><?= htmlspecialchars($settings['logo_text']) ?></h2>
            </div>
            <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number']) ?>?text=Halo%20Admin,%20saya%20ingin%20pesan%20undangan" target="_blank" class="bg-white hover:bg-champagne hover:text-white text-forest text-sm font-semibold py-1.5 px-4 rounded-full border border-transparent transition-colors shadow-sm flex items-center gap-1 group">
                <span class="material-symbols-outlined text-sm group-hover:text-white transition-colors">shopping_cart</span>
                Pesan
            </a>
        </div>
    </div>
    
    <div class="w-full flex flex-col">
        <!-- Hero Section -->
        <div class="relative overflow-hidden hero-gradient min-h-[520px] lg:min-h-[650px] flex flex-col items-center justify-center text-center pt-20 pb-10 px-6 rounded-b-[48px] md:rounded-b-[80px] shadow-2xl mb-10 ring-1 ring-white/10">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-80 h-80 bg-champagne/20 rounded-full blur-[80px]"></div>
                <div class="absolute bottom-[20%] right-[-10%] w-64 h-64 bg-white/10 rounded-full blur-[60px]"></div>
                <span class="material-symbols-outlined absolute top-10 right-6 text-white/5 text-[80px] md:text-[200px] rotate-45">spa</span>
                <span class="material-symbols-outlined absolute bottom-20 left-10 text-champagne/10 text-[60px] md:text-[150px] -rotate-12">local_florist</span>
            </div>
            
            <div class="max-w-7xl mx-auto w-full flex flex-col md:flex-row items-center gap-10 md:gap-20 z-10">
                <!-- Hero Text -->
                <div class="flex flex-col items-center md:items-start text-center md:text-left flex-1 gap-6">
                    <span class="inline-block px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-champagne text-[10px] md:text-xs uppercase tracking-widest font-bold">New Collection <?= date('Y') ?></span>
                    <h1 class="text-white text-4xl md:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight font-serif drop-shadow-lg">
                        <?php 
                        // Auto-style the last word
                        $rawTitle = strip_tags($settings['hero_title']); // Clean any old HTML
                        $words = explode(' ', $rawTitle);
                        if (count($words) > 1) {
                            $lastWord = array_pop($words);
                            echo htmlspecialchars(implode(' ', $words)) . ' <span class="text-champagne font-serif italic pr-1">' . htmlspecialchars($lastWord) . '</span>';
                        } else {
                            echo htmlspecialchars($rawTitle);
                        }
                        ?>
                    </h1>
                    <p class="text-white/80 text-sm md:text-lg font-medium tracking-wide max-w-[280px] md:max-w-lg leading-relaxed">
                        <?= htmlspecialchars($settings['hero_description']) ?>
                    </p>
                    <div class="flex flex-col md:flex-row gap-3 mt-2">
                        <button onclick="document.getElementById('catalog').scrollIntoView({behavior: 'smooth'})" class="bg-champagne text-forest hover:bg-white hover:text-forest px-8 py-3 rounded-full font-bold text-sm md:text-base shadow-glow transition-all active:scale-95 flex items-center justify-center gap-2 group border border-champagne">
                            Lihat Katalog
                            <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_right_alt</span>
                        </button>
                        <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number']) ?>?text=Halo%20Admin,%20saya%20tertarik%20pesan%20undangan" target="_blank" class="bg-transparent text-white hover:bg-white/10 px-8 py-3 rounded-full font-bold text-sm md:text-base border border-white/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-sm">chat</span>
                            Pesan Sekarang
                        </a>
                    </div>
                </div>

                <!-- Hero Images -->
                <div class="relative w-full max-w-[320px] md:max-w-[400px] h-[400px] md:h-[500px] flex-none mt-8 md:mt-0">
                    <div class="absolute left-4 bottom-0 w-[180px] md:w-[240px] h-[360px] md:h-[450px] bg-white rounded-[24px] shadow-2xl transform -rotate-6 z-10 overflow-hidden border-[4px] border-forest ring-1 ring-white/20 hover:rotate-0 transition-transform duration-500">
                        <img alt="Wedding invitation mockup 1" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCKPJZF0QVfOyBYBzFC9zvUKDQrp3ZPcEfYiTFcua94rq1HB_UAqF3SdPVHyocdvoVm8dSEfPYlGA45om8GrMhKVsM1OKoxCX64rixaFXSAttJfj7vXwrlkW-MdENylsKY19qjwi-hjEVhx3wynI6aSMscHkZoAwyGV7Kapc-WS7Ud0DTnEAB8qO632I1UOHm_6PSzzCSiaFivA48jPHXbjmvIfJIl92lncs4AuNFriliEvmbSXWxH_bd5byI0k6L9b79btTrSzt9q6"/>
                    </div>
                    <div class="absolute right-4 bottom-10 w-[180px] md:w-[240px] h-[360px] md:h-[450px] bg-white rounded-[24px] shadow-xl transform rotate-6 z-0 overflow-hidden border-[4px] border-forest/50 opacity-90 grayscale-[20%] hover:rotate-2 hover:grayscale-0 hover:opacity-100 hover:z-20 transition-all duration-500">
                        <img alt="Wedding invitation mockup 2" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDgDdbPiToFHnhGvBy5uAj7dFniLF-Yo5JtTkO39SIrtiQ1gjDu_sDBoYy-5eJoCnhOyxh9kxiHnbpkPAii-HaS2itAtaTph4KwcQYesopH8D2MRTSj8CKOHLYY5sEJ_r2dle9fTTsH2EY6qU73hLa-ncVxd3OmC8xhonJINeIhF9C8cdqIl8wpGTMKvblZNJAlfnYlCBHQpk0cV6mfZW5AA9SOhcFiYXApJXMdqmLOpCAVJzy8sejUeceWJAT3LuK45DuKuWdKl5Sq"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto w-full px-6 flex flex-col md:flex-row gap-8 md:gap-16 py-8 md:py-16 items-center">
             <!-- Features Text Group -->
            <div class="flex-1 space-y-8 text-center md:text-left">
                <div class="space-y-3">
                    <h2 class="text-champagne text-2xl md:text-4xl font-black font-serif">Fitur Premium</h2>
                    <div class="h-0.5 w-12 bg-white/20 mx-auto md:mx-0 rounded-full"></div>
                    <p class="text-xs md:text-sm text-white/60 font-medium">Dipercaya lebih dari 4000 pasangan setiap bulannya</p>
                </div>
                
                <div class="flex justify-center md:justify-start gap-[-10px]">
                    <div class="flex -space-x-4">
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-forest ring-2 ring-champagne/40 z-30">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAG_1JPzYvdt4JMz4OYN-_yf_pasvwbQDMDuXOLTCRryzNnuP6tmTmILljKtxaOv__Z7PllszL31q-spCOeRQKVPQSCTzO2k_CVtvludJgLpPfa4FHsKKd2_qDhJC-FABWSTYF0Kcg2GO6lvNl7_SFlu4djWYFE5q0riQTreRDO1MLH5Qc2Wu00MVC5p4B_WOHLMzQzv6Kafnrng3Ene0dCymum0qUM5qOHV8HXk5i4gJDnCEOGdsAxMDEedWP-vKLe7__35R4uRGBY"/>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-forest ring-2 ring-champagne/40 z-20">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAeV5J2om_cFGqlHaxefwoG_A2JMcwDN4gIwLz-lD9ZUucxVlLQTGjpiVAXeQvova2rwaeNCVyJKdIatf23fhlkw8Uy8pGHTVlp1Yep9VrG5qyEFYzdLC-xaYboN-yx9lny-F-0msaZFCmmtumKoRKaf6oDwLImecMFGfJxJVgKygGIxWqQj0c6Pj1zajujnmgaaEsRKLAuM8xK-92seXqjcNSzVY3RypBKZAmDNcO0u62bCOFF15tdCK2r6BI6tsgFQokc4dyP221p"/>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-forest ring-2 ring-champagne/40 z-10">
                            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCERkE_-yJD0up8WdnnEWWo9T3AbwUlUwMHMJxCX8Q8kYZ07b3d3l0-fhYbGQBlKglY7kd9qWieN8vGpqkU2rX3Oqbh3_PiSpCgwFN03IO7mfdmYcMiEoTK92jrVoPenrM02D6566StGIeDCQM10kgrCj2vIdH3Hlu2Y2Rm5PJ5Ih65qyh8LsgFTT4mzLuO1z0KcDXZXCfpCVKE8lofMlBh-qYZXmriAHcIOD5A5qGUB_TPrC-8H9V_7ZMPUfm_j-qxAYdVR1xvMhbQ"/>
                        </div>
                        <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-forest ring-2 ring-champagne/40 z-0 bg-champagne flex items-center justify-center text-[10px] md:text-xs font-bold text-forest">
                        +4k
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features List -->
            <div class="flex-1 w-full bg-white/5 backdrop-blur-md rounded-[24px] p-8 md:p-12 shadow-card border border-white/10 relative overflow-hidden">
                <span class="material-symbols-outlined absolute top-[-10px] right-[-10px] text-white/5 text-9xl">featured_seasonal_and_gifts</span>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 relative z-10">
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-champagne/20 flex items-center justify-center text-champagne"><span class="material-symbols-outlined text-base font-bold">check</span></div>
                        <span class="text-sm md:text-base font-semibold text-white/90">Pengerjaan kilat &lt; 24 jam</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-champagne/20 flex items-center justify-center text-champagne"><span class="material-symbols-outlined text-base font-bold">check</span></div>
                        <span class="text-sm md:text-base font-semibold text-white/90">Revisi sepuasnya sampai hari-H</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-champagne/20 flex items-center justify-center text-champagne"><span class="material-symbols-outlined text-base font-bold">check</span></div>
                        <span class="text-sm md:text-base font-semibold text-white/90">Nama tamu tak terbatas</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-champagne/20 flex items-center justify-center text-champagne"><span class="material-symbols-outlined text-base font-bold">check</span></div>
                        <span class="text-sm md:text-base font-semibold text-white/90">Bebas request musik</span>
                    </li>
                    <li class="flex items-center gap-4 md:col-span-2 bg-champagne/10 p-2 rounded-xl border border-champagne/20">
                        <div class="w-8 h-8 rounded-full bg-champagne flex items-center justify-center text-forest"><span class="material-symbols-outlined text-base font-bold">verified</span></div>
                        <span class="text-sm md:text-base font-bold text-champagne">Desain Premium &amp; Exclusive</span>
                    </li>
                </ul>
            </div>
        </div>

        <div id="catalog" class="flex flex-col gap-6 pt-10 pb-20 max-w-7xl mx-auto w-full">
            <div class="px-6 text-center space-y-2">
                <h2 class="text-champagne text-2xl md:text-4xl font-black font-serif">Pilihan Tema</h2>
                <p class="text-xs md:text-sm text-white/60">Temukan desain yang sesuai dengan karaktermu</p>
            </div>
            
            <div class="px-4 pb-2">
                <div class="flex flex-wrap justify-center gap-3 w-full px-2">
                    <button onclick="filterCategory('all')" class="category-btn px-6 py-2.5 rounded-full border border-champagne bg-champagne text-forest text-sm font-bold shadow-glow transition-all active-cat shrink-0" data-id="all">Semua</button>
                    <?php foreach($categories as $cat): ?>
                    <button onclick="filterCategory(<?= $cat['id'] ?>)" class="category-btn px-6 py-2.5 rounded-full border border-white/20 text-white/70 bg-transparent text-sm font-bold hover:border-champagne hover:text-champagne transition-all shrink-0" data-id="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="px-6 flex justify-center w-full">
                 <div id="active-category-label" class="w-full max-w-sm bg-white/5 border border-white/10 text-champagne text-center px-4 py-3 rounded-xl font-bold text-sm shadow-sm flex flex-col md:flex-row items-center justify-center gap-2 backdrop-blur-sm">
                    <span class="material-symbols-outlined text-lg">photo_camera</span>
                    Kategori: <span id="cat-name">Semua</span>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 px-6">
                <!-- Product Card loop remains same, grid settings handled in parent div -->
                <?php foreach($products as $product): ?>
                <div class="product-card bg-white rounded-xl overflow-hidden shadow-card border border-white/10 flex flex-col group hover:-translate-y-2 hover:shadow-2xl transition-all duration-300" data-category="<?= $product['category_id'] ?>">
                    <div class="relative aspect-[3/4] bg-gray-100 overflow-hidden">
                        <?php if($product['discounted_price']): ?>
                        <div class="absolute top-0 right-0 bg-forest text-white text-[10px] font-bold px-3 py-1.5 z-10 rounded-bl-xl shadow-sm">HEMAT 40%</div>
                        <?php endif; ?>
                        <img alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="<?= htmlspecialchars($product['image']) ?>"/>
                         <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                             <a href="<?= htmlspecialchars($product['link'] ?? '#') ?>" target="_blank" class="bg-white text-forest px-6 py-2.5 rounded-full font-bold text-sm transform translate-y-4 group-hover:translate-y-0 transition-all duration-300 hover:bg-forest hover:text-white hover:scale-105 shadow-lg">Lihat Detail</a>
                        </div>
                    </div>
                    <div class="p-5 flex flex-col gap-2 bg-white h-full justify-between relative z-20">
                         <div>
                            <h3 class="font-bold text-forest text-sm uppercase tracking-wide"><?= htmlspecialchars($product['name']) ?></h3>
                            <div class="flex items-center gap-2 mt-1">
                                <?php if($product['discounted_price']): ?>
                                    <p class="text-xs text-gray-400 line-through">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                                    <p class="text-base font-bold text-champagne">Rp <?= number_format($product['discounted_price'], 0, ',', '.') ?></p>
                                <?php else: ?>
                                    <p class="text-base font-bold text-champagne">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number']) ?>?text=Halo%20Admin,%20saya%20tertarik%20dengan%20tema%20<?= urlencode($product['name']) ?>" target="_blank" class="mt-3 w-full border-2 border-forest/10 text-forest py-2.5 rounded-lg text-center text-xs font-bold hover:bg-forest hover:text-white transition-all uppercase tracking-wider block">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <script>
            function filterCategory(catId) {
                // Update Buttons
                document.querySelectorAll('.category-btn').forEach(btn => {
                    const id = btn.getAttribute('data-id');
                    if (id == catId) {
                        btn.classList.add('bg-champagne', 'text-forest', 'border-champagne');
                        btn.classList.remove('text-white/70', 'bg-transparent', 'border-white/20');
                        document.getElementById('cat-name').textContent = btn.innerText;
                    } else {
                        btn.classList.remove('bg-champagne', 'text-forest', 'border-champagne');
                        btn.classList.add('text-white/70', 'bg-transparent', 'border-white/20');
                    }
                });

                // Filter Products
                document.querySelectorAll('.product-card').forEach(card => {
                    if (catId === 'all' || card.getAttribute('data-category') == catId) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        </script>

        <div class="max-w-7xl mx-auto w-full px-6">
            <div class="bg-cream-light text-forest py-16 px-6 relative rounded-[40px] my-10 shadow-2xl overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none" style="background-image: radial-gradient(#2F4F4F 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="text-center space-y-3 mb-12 relative z-10">
                    <h2 class="text-forest text-2xl md:text-4xl font-black font-serif">Apa Kata Mereka?</h2>
                    <div class="h-0.5 w-12 bg-champagne mx-auto rounded-full"></div>
                    <p class="text-xs md:text-sm text-forest/70 font-medium">Testimoni dari pasangan berbahagia</p>
                </div>
                
                <div class="relative w-full overflow-hidden no-scrollbar fade-sides">
                     <!-- Gradient Side Overlays -->
                    <div class="absolute top-0 left-0 h-full w-20 bg-gradient-to-r from-cream-light to-transparent z-20 pointer-events-none"></div>
                    <div class="absolute top-0 right-0 h-full w-20 bg-gradient-to-l from-cream-light to-transparent z-20 pointer-events-none"></div>

                    <div class="animate-scroll gap-6 px-4">
                        <?php 
                        // Duplicate array for seamless infinite scroll
                        $allTestimonials = array_merge($testimonials, $testimonials);
                        foreach($allTestimonials as $testi): 
                        ?>
                        <div class="w-[300px] md:w-[350px] flex-shrink-0 bg-white rounded-2xl p-6 md:p-8 shadow-card border border-champagne/20 flex flex-col gap-4 relative hover:-translate-y-2 transition-transform duration-300 select-none">
                            <span class="material-symbols-outlined absolute top-4 right-4 text-champagne/20 text-4xl">format_quote</span>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 md:w-14 md:h-14 rounded-full overflow-hidden border-2 border-champagne shrink-0">
                                    <?php $imgSrc = strpos($testi['image'], 'http') === 0 ? $testi['image'] : $testi['image']; ?>
                                    <img alt="<?= htmlspecialchars($testi['couple_name']) ?>" class="w-full h-full object-cover" src="<?= htmlspecialchars($imgSrc) ?>"/>
                                </div>
                                <div>
                                    <h3 class="font-bold text-forest font-serif text-sm md:text-base"><?= htmlspecialchars($testi['couple_name']) ?></h3>
                                    <div class="flex text-champagne text-xs">
                                        <?php for($i=0; $i<$testi['rating']; $i++): ?>
                                        <span class="material-symbols-outlined text-sm filled">star</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs md:text-sm italic text-forest/80 leading-relaxed">
                                "<?= htmlspecialchars($testi['quote']) ?>"
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full bg-forest text-white pt-16 pb-12 rounded-t-[40px] md:rounded-t-[80px] mt-auto relative overflow-hidden border-t border-white/5">
            <span class="material-symbols-outlined absolute top-[-20px] left-[-20px] text-white/5 text-9xl">spa</span>
            <span class="material-symbols-outlined absolute bottom-[-20px] right-[-20px] text-white/5 text-9xl">local_florist</span>
            
            <div class="max-w-7xl mx-auto px-8 flex flex-col md:flex-row gap-8 relative z-10 justify-between">
                
                <div class="flex-1 space-y-6">
                     <h3 class="font-bold text-xl md:text-2xl font-serif tracking-wide text-champagne text-center md:text-left">Tentang Kami</h3>
                     <p class="text-sm text-white/70 leading-relaxed text-center md:text-left">
                         <?= htmlspecialchars($settings['site_title']) ?> membantu calon pengantin membagikan kabar bahagia mereka melalui undangan digital yang estetik dan modern.
                     </p>
                     <div class="flex items-center justify-center md:justify-start gap-4">
                        <a class="flex items-center gap-2 text-xs font-bold text-champagne hover:text-white transition-colors border border-champagne/30 px-4 py-2 rounded-full hover:bg-champagne/10" href="<?= htmlspecialchars($settings['instagram_link']) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.917 3.917 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.486-.276a2.478 2.478 0 0 1-.919-.598 2.48 2.48 0 0 1-.599-.92c-.11-.281-.24-.704-.275-1.485-.038-.843-.047-1.096-.047-3.232 0-2.136.009-2.388.047-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                            </svg>
                            @syifazharinvite
                        </a>
                        <a class="flex items-center gap-2 text-xs font-bold text-champagne hover:text-white transition-colors border border-champagne/30 px-4 py-2 rounded-full hover:bg-champagne/10" href="<?= htmlspecialchars($settings['tiktok_link']) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current" viewBox="0 0 16 16">
                                <path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z"/>
                            </svg>
                            syifazharinvite
                        </a>
                    </div>
                </div>

                <div class="flex-1 flex flex-col gap-6">
                    <h3 class="font-bold text-xl md:text-2xl font-serif tracking-wide text-champagne text-center md:text-left">Lokasi Studio</h3>
                    <div class="w-full h-48 bg-white/5 rounded-2xl overflow-hidden relative shadow-inner border border-white/10 group" id="map-container">
                        <div id="leaflet-map" class="w-full h-full z-0 group-hover:scale-105 transition-transform duration-700"></div>
                        <a href="https://www.google.com/maps/search/?api=1&query=<?= $settings['latitude'] ?>,<?= $settings['longitude'] ?>" target="_blank" class="absolute inset-0 z-10 bg-black/10 group-hover:bg-black/30 transition-colors flex items-center justify-center cursor-pointer">
                            <button class="bg-white text-forest px-4 py-2 rounded-full font-bold text-xs shadow-lg transform translate-y-2 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300 flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">map</span>
                                Buka di Google Maps
                            </button>
                        </a>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Default coords if not set
                                var lat = <?= !empty($settings['latitude']) ? $settings['latitude'] : '-7.7956' ?>;
                                var lng = <?= !empty($settings['longitude']) ? $settings['longitude'] : '110.3695' ?>;
                                
                                var map = L.map('leaflet-map', {
                                    center: [lat, lng],
                                    zoom: 14,
                                    zoomControl: false, 
                                    scrollWheelZoom: false,
                                    dragging: false,
                                    attributionControl: false
                                });

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: ''
                                }).addTo(map);

                                L.marker([lat, lng]).addTo(map);
                            });
                        </script>
                    </div>
                    <div class="space-y-4 text-sm font-medium text-white/80">
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-champagne mt-1">location_on</span>
                            <div>
                                <h4 class="font-bold mb-1 text-white">Alamat</h4>
                                <p class="text-xs leading-relaxed opacity-80"><?= htmlspecialchars($settings['address']) ?></p>
                            </div>
                        </div>
                         <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-champagne mt-1">schedule</span>
                            <div>
                                <h4 class="font-bold mb-1 text-white">Jam Operasional</h4>
                                <div class="grid grid-cols-2 gap-x-8 gap-y-1">
                                    <p class="text-xs leading-relaxed opacity-80">Senin - Jum'at</p>
                                    <p class="text-xs leading-relaxed opacity-80 text-right">08.00 - 21.00 WIB</p>
                                    <p class="text-xs leading-relaxed opacity-80">Sabtu</p>
                                    <p class="text-xs leading-relaxed opacity-80 text-right">09.00 - 20.00 WIB</p>
                                    <p class="text-xs leading-relaxed opacity-80">Minggu</p>
                                    <p class="text-xs leading-relaxed opacity-80 text-right">By Appointment</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="h-px w-full bg-white/10 my-8 max-w-7xl mx-auto"></div>
             <div class="text-center text-[10px] md:text-xs text-white/40">
                Copyright ©<?= date('Y') ?> <?= htmlspecialchars($settings['site_title']) ?>. All rights reserved.
            </div>
        </div>

        <div class="fixed bottom-6 right-6 z-50">
            <a href="https://wa.me/<?= htmlspecialchars($settings['whatsapp_number']) ?>" class="w-14 h-14 md:w-16 md:h-16 bg-champagne text-forest rounded-full shadow-glow flex items-center justify-center hover:scale-110 hover:bg-white transition-all ring-2 ring-forest ring-offset-2 ring-offset-transparent animate-bounce">
                <span class="material-symbols-outlined text-3xl md:text-4xl">chat</span>
            </a>
        </div>
    </div>
</body>
</html>
