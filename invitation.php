<?php
require 'config.php';

// Get Invitation Data by Slug
$slug = $_GET['slug'] ?? '';
if(!$slug) {
    // If no slug, try to get the first one for preview
    $stmt = $pdo->query("SELECT slug FROM invitations LIMIT 1");
    $slug = $stmt->fetchColumn();
    if(!$slug) die("Invitation not found.");
}

$stmt = $pdo->prepare("SELECT * FROM invitations WHERE slug = ?");
$stmt->execute([$slug]);
$invitation = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invitation) {
    die("Invitation not found");
}

$id = $invitation['id'];
$features = json_decode($invitation['enabled_features'] ?? '[]', true) ?: [];
$gallery_links = json_decode($invitation['gallery_links'] ?? '[]', true) ?: [];
$music_file = $invitation['music_file'] ?? '';

// Helpers
$akadDate = strtotime($invitation['event_date']);
$recepDate = strtotime($invitation['reception_date'] ?? $invitation['event_date']);
$c = [
    'primary' => '#8B4513',
    'secondary' => '#5D3A1A', 
    'accent' => '#D4A855',
    'bg' => '#FDF8F3'
];

// Logic for Maps
$akadMap = $invitation['map_link'];
$recepMap = !empty($invitation['reception_map_link']) ? $invitation['reception_map_link'] : $akadMap;

// Helper function for asset paths
function asset($path) {
    return "undangan pernikahan/" . $path;
}

// Fetch Wishes
$stmt_wishes = $pdo->prepare("SELECT * FROM guests WHERE invitation_id = ? AND status = 'present' AND message != '' ORDER BY created_at DESC");
$stmt_wishes->execute([$id]);
$wishes = $stmt_wishes->fetchAll();

?>
<!doctype html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Wedding of <?= htmlspecialchars($invitation['groom_nickname']) ?> & <?= htmlspecialchars($invitation['bride_nickname']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&amp;family=Cormorant+Garamond:wght@400;500;600&amp;family=Poppins:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.14.2/simple-lightbox.min.css" rel="stylesheet" />
    <style>
        body { box-sizing: border-box; }
        .font-display { font-family: 'Playfair Display', serif; }
        .font-elegant { font-family: 'Cormorant Garamond', serif; }
        .font-body { font-family: 'Poppins', sans-serif; }
        .scroll-smooth { scroll-behavior: smooth; }
        .tracking-ultra { letter-spacing: 0.25em; }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInLeft { from { opacity: 0; transform: translateX(-50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes fadeInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
        @keyframes zoomIn { from { opacity: 0; transform: scale(0.8); } to { opacity: 1; transform: scale(1); } }
        @keyframes floatSlow { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes pulse-soft { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.05); opacity: 0.9; } }
        
        .animate-fade-in-up { animation: fadeInUp 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        .animate-fade-in-down { animation: fadeInDown 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        .animate-fade-in-left { animation: fadeInLeft 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        .animate-fade-in-right { animation: fadeInRight 1.2s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        .animate-zoom-in { animation: zoomIn 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; }
        .animate-float { animation: floatSlow 5s ease-in-out infinite; }
        .animate-spin-slow { animation: rotate 10s linear infinite; }
        .animate-pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
        .animate-fade-out { transition: opacity 0.8s ease-in-out; opacity: 0; pointer-events: none; }
        
        .nav-btn { transition: all 0.3s; }
        .nav-btn:hover { transform: translateY(-5px); color: #8B4513 !important; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FDF8F3; }
        ::-webkit-scrollbar-thumb { background: #D4A855; border-radius: 10px; }
    </style>
</head>

<body class="h-full font-body scroll-smooth bg-[#FDF8F3] text-[#5D3A1A]">
    <!-- COVER -->
    <div id="cover" class="fixed inset-0 z-50 flex items-center justify-center bg-cover bg-center overflow-hidden"
        style="background: linear-gradient(rgba(40, 25, 10, 0.85), rgba(60, 30, 10, 0.85)), url('<?= htmlspecialchars($invitation['hero_image_link'] ?: asset('assets/bg.jpg')) ?>');">

        <!-- Corner Ornaments -->
        <div class="absolute top-4 left-4 w-24 md:w-48 animate-fade-in-down pointer-events-none">
            <img src="<?= asset('assets/border-1.svg') ?>" class="w-full opacity-90 drop-shadow-lg" alt="Border">
        </div>
        <div class="absolute bottom-4 right-4 w-24 md:w-48 animate-fade-in-up pointer-events-none">
            <img src="<?= asset('assets/border-1.svg') ?>" class="w-full opacity-90 transform rotate-180 drop-shadow-lg" alt="Border">
        </div>

        <div class="text-center w-full max-w-2xl mx-auto relative z-10 px-6 flex flex-col items-center justify-center h-full">
            <div class="animate-fade-in-up delay-100 mb-8">
                 <p class="text-xs md:text-sm tracking-ultra text-[#D4A855] uppercase mb-2">The Wedding Of</p>
                 <div class="h-px w-24 bg-[#D4A855]/50 mx-auto"></div>
            </div>
           
            
            <div class="mb-8 animate-fade-in-up delay-300 relative py-12 md:py-16">
                <!-- Decorative Circle/Gunungan Background -->
                 <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
                    <img src="<?= asset('assets/ilustrasi-1.svg') ?>" class="w-64 md:w-80" style="filter: invert(1);">
                 </div>

                <h1 class="font-display text-4xl md:text-6xl text-[#FDF8F3] leading-tight drop-shadow-lg relative z-10 px-4">
                    <?= htmlspecialchars($invitation['groom_nickname']) ?> 
                    <span class="font-elegant italic text-[#D4A855] mx-2 text-3xl md:text-5xl">&</span> 
                    <?= htmlspecialchars($invitation['bride_nickname']) ?>
                </h1>
            </div>
            
            <div class="space-y-4 mb-10 animate-fade-in-up delay-500 z-10 relative">
                <p class="font-elegant text-xl md:text-2xl text-[#D4A855] border-y border-[#D4A855]/30 py-2 px-8 inline-block bg-black/20 backdrop-blur-sm rounded-full">
                    <?= date('l, d F Y', $akadDate) ?>
                </p>
            </div>

            <button id="open-invitation-btn" class="group relative px-8 py-3 rounded-full bg-[#D4A855] text-[#2c1810] font-bold tracking-wider transition-all hover:scale-105 hover:bg-[#eac47a] shadow-xl animate-fade-in-up delay-700 flex items-center gap-2 overflow-hidden z-20">
                <span class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></span>
                <span class="material-symbols-outlined text-lg relative z-10">drafts</span>
                <span class="relative z-10">Buka Undangan</span>
            </button>
        </div>
    </div>

    <!-- MAIN APP -->
    <div id="app" class="h-full w-full overflow-auto hidden bg-fixed bg-cover bg-center"
        style="background-image: linear-gradient(rgba(253, 248, 243, 0.92), rgba(253, 248, 243, 0.92)), url('<?= htmlspecialchars($invitation['hero_image_link'] ?: asset('assets/bg.jpg')) ?>');">
        
        <!-- HERO -->
        <section id="hero" class="min-h-screen relative flex flex-col items-center justify-center py-16 md:py-20 px-4 overflow-hidden">
            <!-- Back Ornaments -->
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-5">
                <img src="<?= asset('assets/ilustrasi-1.svg') ?>" class="w-[500px] md:w-[700px]">
            </div>

            <div class="absolute top-4 left-4 w-24 md:w-32 opacity-80 animate-fade-in-down pointer-events-none">
                <img src="<?= asset('assets/border-1.svg') ?>" alt="Border">
            </div>
            <div class="absolute bottom-4 right-4 w-24 md:w-32 opacity-80 animate-fade-in-up pointer-events-none">
                <img src="<?= asset('assets/border-1.svg') ?>" class="w-full transform rotate-180" alt="Border">
            </div>

            <div class="relative w-full max-w-3xl mx-auto text-center z-10">
                <p class="text-xs md:text-sm tracking-ultra mb-6 animate-on-scroll" data-animation="fade-in-down" style="color: <?= $c['primary'] ?>;">THE WEDDING OF</p>
                
                <div class="space-y-4 mb-8">
                    <h1 class="font-display text-5xl md:text-7xl font-bold animate-on-scroll leading-tight" data-animation="fade-in-left" style="color: <?= $c['secondary'] ?>;">
                        <?= htmlspecialchars($invitation['groom_nickname']) ?>
                    </h1>
                    <div class="flex items-center justify-center gap-6 my-4 animate-on-scroll" data-animation="zoom-in">
                        <div class="h-px w-16 md:w-24 bg-[#D4A855]"></div>
                        <span class="font-elegant text-4xl md:text-5xl italic text-[#D4A855]">&</span>
                        <div class="h-px w-16 md:w-24 bg-[#D4A855]"></div>
                    </div>
                    <h1 class="font-display text-5xl md:text-7xl font-bold animate-on-scroll leading-tight" data-animation="fade-in-right" style="color: <?= $c['secondary'] ?>;">
                        <?= htmlspecialchars($invitation['bride_nickname']) ?>
                    </h1>
                </div>
                
                <div class="flex justify-center my-10 animate-on-scroll" data-animation="fade-in-up">
                    <img src="<?= asset('assets/ilustrasi-4.svg') ?>" class="w-32 md:w-48 animate-float drop-shadow-md" alt="Mempelai">
                </div>

                <div class="animate-on-scroll" data-animation="fade-in-up">
                    <p class="font-elegant text-2xl md:text-3xl text-[#D4A855]">
                        <?= date('l, d F Y', $akadDate) ?>
                    </p>
                    <p class="mt-2 text-sm tracking-widest text-[#5D3A1A]">KAMI MENGUNDANG ANDA UNTUK HADIR</p>
                </div>
            </div>
        </section>

        <!-- QUOTE -->
        <?php if(in_array('quote', $features)): ?>
        <section class="py-16 md:py-20 px-6 bg-white/30 backdrop-blur-sm border-y border-[#D4A855]/20">
            <div class="max-w-3xl mx-auto text-center animate-on-scroll" data-animation="fade-in">
                <img src="<?= asset('assets/ilustrasi-1.svg') ?>" class="w-12 mx-auto mb-6 opacity-50">
                <p class="font-elegant text-xl md:text-2xl italic leading-relaxed text-[#5D3A1A]">
                    "<?= htmlspecialchars($invitation['wishes_opening'] ?? 'Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu pasangan hidup dari jenismu sendiri...') ?>"
                </p>
                <div class="mt-6 flex items-center justify-center gap-2">
                    <div class="h-px w-8 bg-[#D4A855]"></div>
                    <p class="text-xs font-bold tracking-widest text-[#D4A855]">QS. AR-RUM: 21</p>
                    <div class="h-px w-8 bg-[#D4A855]"></div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- COUPLE -->
        <section id="couple" class="py-16 md:py-24 px-4 relative overflow-hidden">
             <!-- Background Texture -->
             <div class="absolute top-0 right-0 w-64 opacity-5 pointer-events-none translate-x-1/2 -translate-y-1/2">
                <img src="<?= asset('assets/ilustrasi-1.svg') ?>" class="w-full">
            </div>

            <div class="max-w-5xl mx-auto relative z-10">
                <div class="text-center mb-16 animate-on-scroll" data-animation="fade-in-down">
                    <div class="inline-block px-4 py-1 rounded-full border border-[#D4A855]/50 text-[#D4A855] text-xs font-bold tracking-widest mb-4">ASSALAMU’ALAIKUM WR. WB.</div>
                    <p class="text-base md:text-lg max-w-2xl mx-auto leading-relaxed text-[#5D3A1A]">
                        Dengan memohon Rahmat dan Ridho Allah SWT, kami bermaksud menyelenggarakan resepsi pernikahan putra-putri kami:
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 md:gap-20 items-stretch">
                    <!-- Groom -->
                    <div class="group relative text-center p-8 rounded-[2rem] bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-[#D4A855]/10 hover:border-[#D4A855]/30 transition-all duration-500 animate-on-scroll" data-animation="fade-in-left">
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-[#FDF8F3] border border-[#D4A855]/20 rounded-full flex items-center justify-center text-[#D4A855] text-xl z-20 shadow-sm">
                            <span class="material-symbols-outlined">man</span>
                        </div>
                        
                        <div class="relative w-48 h-48 mx-auto mb-6">
                            <div class="absolute inset-0 rounded-full border-[3px] border-dashed border-[#D4A855]/40 animate-spin-slow"></div>
                            <div class="absolute inset-2 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                <?php $groomImg = !empty($invitation['groom_photo']) ? 'admin/'.$invitation['groom_photo'] : asset('assets/img-1.jpg'); ?>
                                <img src="<?= $groomImg ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            </div>
                        </div>

                        <h3 class="font-display text-3xl md:text-4xl mb-2 text-[#5D3A1A]"><?= htmlspecialchars($invitation['groom_name']) ?></h3>
                        <p class="text-sm font-bold tracking-widest text-[#D4A855] mb-6">MEMPELAI PRIA</p>
                        
                        <div class="text-sm text-[#8B4513]/80 space-y-1">
                            <p>Putri dari Pasangan</p>
                            <p class="font-bold text-lg text-[#5D3A1A]">Bapak <?= htmlspecialchars($invitation['groom_father']) ?></p>
                            <p>&</p>
                            <p class="font-bold text-lg text-[#5D3A1A]">Ibu <?= htmlspecialchars($invitation['groom_mother']) ?></p>
                        </div>
                    </div>

                    <!-- Bride -->
                    <div class="group relative text-center p-8 rounded-[2rem] bg-white shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-[#D4A855]/10 hover:border-[#D4A855]/30 transition-all duration-500 animate-on-scroll" data-animation="fade-in-right">
                         <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-[#FDF8F3] border border-[#D4A855]/20 rounded-full flex items-center justify-center text-[#D4A855] text-xl z-20 shadow-sm">
                            <span class="material-symbols-outlined">woman</span>
                        </div>
                        
                         <div class="relative w-48 h-48 mx-auto mb-6">
                            <div class="absolute inset-0 rounded-full border-[3px] border-dashed border-[#D4A855]/40 animate-spin-slow" style="animation-direction: reverse;"></div>
                            <div class="absolute inset-2 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                 <?php $brideImg = !empty($invitation['bride_photo']) ? 'admin/'.$invitation['bride_photo'] : asset('assets/img-2.jpg'); ?>
                                <img src="<?= $brideImg ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            </div>
                        </div>

                        <h3 class="font-display text-3xl md:text-4xl mb-2 text-[#5D3A1A]"><?= htmlspecialchars($invitation['bride_name']) ?></h3>
                        <p class="text-sm font-bold tracking-widest text-[#D4A855] mb-6">MEMPELAI WANITA</p>
                        
                        <div class="text-sm text-[#8B4513]/80 space-y-1">
                            <p>Putri dari Pasangan</p>
                            <p class="font-bold text-lg text-[#5D3A1A]">Bapak <?= htmlspecialchars($invitation['bride_father']) ?></p>
                            <p>&</p>
                            <p class="font-bold text-lg text-[#5D3A1A]">Ibu <?= htmlspecialchars($invitation['bride_mother']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- LOVE STORY -->
        <?php 
        $stories = json_decode($invitation['love_story'] ?? '[]', true);
        if(is_array($stories) && count($stories) > 0): 
        ?>
        <section id="story" class="py-16 md:py-24 px-4 bg-[#D4A855]/5 border-y border-[#D4A855]/20">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16 animate-on-scroll" data-animation="fade-in-down">
                    <img src="<?= asset('assets/ilustrasi-1.svg') ?>" class="w-12 mx-auto mb-4 opacity-30">
                    <h2 class="font-display text-4xl md:text-5xl text-[#5D3A1A] mb-2">Kisah Cinta Kami</h2>
                    <p class="font-elegant text-xl text-[#D4A855]">Perjalanan Menuju Halal</p>
                </div>
                
                <div class="relative pl-8 md:pl-0 max-w-3xl mx-auto">
                    <!-- Vertical Line -->
                    <div class="absolute left-8 md:left-12 top-0 bottom-0 w-px bg-gradient-to-b from-transparent via-[#D4A855] to-transparent"></div>
                    
                    <div class="space-y-16">
                        <?php foreach($stories as $story): ?>
                        <div class="relative pl-8 md:pl-16 group animate-on-scroll" data-animation="fade-in-up">
                            <!-- Dot -->
                            <div class="absolute left-6 md:left-10 top-0 w-5 h-5 rounded-full border-4 border-[#FDF8F3] bg-[#D4A855] z-10 shadow-md group-hover:scale-125 transition-transform duration-300" style="transform: translateX(-50%);"></div>
                            
                            <!-- Content -->
                            <div class="bg-white p-8 rounded-2xl shadow-sm border border-[#D4A855]/10 hover:shadow-lg transition-all duration-300 relative">
                                <span class="absolute -left-2 top-4 w-4 h-4 bg-white transform rotate-45 border-l border-b border-[#D4A855]/10"></span>
                                <div class="flex flex-col md:flex-row md:items-center gap-4 mb-4">
                                     <span class="inline-block px-4 py-1 rounded-full bg-[#D4A855]/10 text-[#D4A855] font-bold text-sm tracking-widest border border-[#D4A855]/20">
                                        <?= htmlspecialchars($story['year'] ?? '') ?>
                                    </span>
                                    <h3 class="font-display text-2xl font-bold text-[#5D3A1A]">
                                        <?= htmlspecialchars($story['title'] ?? 'Momen Spesial') ?>
                                    </h3>
                                </div>
                                <p class="text-base text-[#5D3A1A]/80 leading-relaxed font-light">
                                    <?= nl2br(htmlspecialchars($story['story'] ?? '')) ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- EVENT -->
        <section id="event" class="py-16 md:py-24 px-4 relative">
             <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-[#FDF8F3] to-transparent z-10"></div>
             
             <!-- Ornaments -->
             <div class="absolute top-10 left-0 w-24 opacity-30 pointer-events-none">
                 <img src="<?= asset('assets/border-1.svg') ?>" class="w-full">
             </div>
             <div class="absolute top-10 right-0 w-24 opacity-30 pointer-events-none transform scale-x-[-1]">
                 <img src="<?= asset('assets/border-1.svg') ?>" class="w-full">
             </div>

            <div class="max-w-5xl mx-auto relative z-20">
                <div class="text-center mb-16 animate-on-scroll" data-animation="fade-in-down">
                    <h2 class="font-display text-4xl md:text-5xl text-[#5D3A1A] mb-4">Rangkaian Acara</h2>
                    <p class="font-elegant text-lg text-[#5D3A1A]/70 max-w-2xl mx-auto">
                        Dengan segala kerendahan hati, kami mengundang Bapak/Ibu/Saudara/i untuk hadir dalam acara pernikahan kami:
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8 md:gap-12">
                    <!-- Akad -->
                    <div class="group relative text-center px-8 py-12 rounded-[2rem] bg-white border border-[#D4A855]/20 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_-10px_rgba(212,168,85,0.1)] transition-all duration-500 animate-on-scroll" data-animation="fade-in-left">
                        <!-- Orn -->
                        <div class="absolute top-4 left-4 w-12 opacity-20 pointer-events-none"><img src="<?= asset('assets/border-1.svg') ?>"></div>
                        <div class="absolute bottom-4 right-4 w-12 opacity-20 pointer-events-none rotate-180"><img src="<?= asset('assets/border-1.svg') ?>"></div>

                         <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center bg-[#FDF8F3] border border-[#D4A855] text-[#D4A855] group-hover:scale-110 transition-transform duration-500 shadow-inner">
                            <span class="material-symbols-outlined text-4xl">favorite</span>
                        </div>
                        
                        <h3 class="font-display text-3xl mb-2 text-[#5D3A1A]">Akad Nikah</h3>
                        <div class="h-px w-16 bg-[#D4A855] mx-auto mb-6 opacity-30"></div>
                        
                        <div class="space-y-4 mb-8">
                            <div>
                                <p class="text-xs font-bold tracking-widest text-[#D4A855] uppercase mb-1">Hari / Tanggal</p>
                                <p class="font-elegant text-2xl text-[#5D3A1A]"><?= date('l, d F Y', $akadDate) ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold tracking-widest text-[#D4A855] uppercase mb-1">Waktu</p>
                                <p class="font-elegant text-2xl text-[#5D3A1A]"><?= date('H:i', $akadDate) ?> WIB - Selesai</p>
                            </div>
                             <div>
                                <p class="text-xs font-bold tracking-widest text-[#D4A855] uppercase mb-1">Lokasi</p>
                                <p class="text-sm md:text-base text-[#5D3A1A]/80 px-4"><?= htmlspecialchars($invitation['event_address']) ?></p>
                            </div>
                        </div>

                        <?php if($akadMap): ?>
                        <a href="<?= htmlspecialchars($akadMap) ?>" target="_blank" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-[#5D3A1A] text-white text-sm font-medium transition hover:bg-[#D4A855] hover:text-[#5D3A1A] shadow-lg">
                            <span class="material-symbols-outlined text-lg">map</span> 
                            Google Maps
                        </a>
                        <?php endif; ?>
                    </div>

                    <!-- Reception -->
                    <div class="group relative text-center px-8 py-12 rounded-[2rem] bg-white border border-[#D4A855]/20 shadow-[0_10px_30px_-10px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_40px_-10px_rgba(212,168,85,0.1)] transition-all duration-500 animate-on-scroll" data-animation="fade-in-right">
                         <!-- Orn -->
                        <div class="absolute top-4 left-4 w-12 opacity-20 pointer-events-none"><img src="<?= asset('assets/border-1.svg') ?>"></div>
                        <div class="absolute bottom-4 right-4 w-12 opacity-20 pointer-events-none rotate-180"><img src="<?= asset('assets/border-1.svg') ?>"></div>

                         <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center bg-[#FDF8F3] border border-[#D4A855] text-[#D4A855] group-hover:scale-110 transition-transform duration-500 shadow-inner">
                            <span class="material-symbols-outlined text-4xl">celebration</span>
                        </div>
                        
                        <h3 class="font-display text-3xl mb-2 text-[#5D3A1A]">Resepsi</h3>
                        <div class="h-px w-16 bg-[#D4A855] mx-auto mb-6 opacity-30"></div>
                        
                        <div class="space-y-4 mb-8">
                             <div>
                                <p class="text-xs font-bold tracking-widest text-[#D4A855] uppercase mb-1">Hari / Tanggal</p>
                                <p class="font-elegant text-2xl text-[#5D3A1A]"><?= date('l, d F Y', $recepDate) ?></p>
                            </div>
                            <div>
                                <p class="text-xs font-bold tracking-widest text-[#D4A855] uppercase mb-1">Waktu</p>
                                <p class="font-elegant text-2xl text-[#5D3A1A]"><?= date('H:i', $recepDate) ?> WIB - Selesai</p>
                            </div>
                             <div>
                                <p class="text-xs font-bold tracking-widest text-[#D4A855] uppercase mb-1">Lokasi</p>
                                <p class="text-sm md:text-base text-[#5D3A1A]/80 px-4"><?= htmlspecialchars($invitation['reception_address']) ?></p>
                            </div>
                        </div>

                        <?php if($recepMap): ?>
                        <a href="<?= htmlspecialchars($recepMap) ?>" target="_blank" class="inline-flex items-center gap-2 px-8 py-3 rounded-full bg-[#5D3A1A] text-white text-sm font-medium transition hover:bg-[#D4A855] hover:text-[#5D3A1A] shadow-lg">
                            <span class="material-symbols-outlined text-lg">map</span> 
                            Google Maps
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- WEDDING GIFTS -->
        <?php 
        $gifts = json_decode($invitation['gifts'] ?? '[]', true);
        if((is_array($gifts) && count($gifts) > 0) || !empty($invitation['enabled_features']) && in_array('gift', $features)):
        ?>
        <section id="gift" class="py-24 px-4 bg-[#FDF8F3]">
            <div class="max-w-2xl mx-auto text-center">
                 <div class="inline-block p-3 rounded-full bg-[#D4A855]/10 text-[#D4A855] mb-6 animate-on-scroll" data-animation="zoom-in">
                    <span class="material-symbols-outlined text-3xl">volunteer_activism</span>
                </div>
                <h2 class="font-display text-4xl md:text-5xl mb-6 animate-on-scroll text-[#5D3A1A]" data-animation="fade-in-down">Teddy Gift</h2>
                <p class="mb-12 font-elegant text-xl animate-on-scroll text-[#5D3A1A]/70 leading-relaxed" data-animation="fade-in">
                    Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.
                </p>
                
                <div class="space-y-6 animate-on-scroll" data-animation="fade-in-up">
                    <?php if(is_array($gifts)): foreach($gifts as $gift): ?>
                    <div class="p-8 rounded-2xl bg-white shadow-lg border border-[#D4A855]/20 flex flex-col items-center gap-4 relative overflow-hidden group hover:-translate-y-2 transition-transform duration-300">
                        <!-- Decorative bg -->
                        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-[#D4A855]/5 rounded-full pointer-events-none"></div>

                        <!-- Logo / Bank Name -->
                        <div class="flex items-center gap-2 mb-2">
                             <?php if(isset($gift['logo']) && in_array($gift['logo'], ['bca','bri','mandiri','bni','cimb','dana','ovo','gopay','shopeepay','linkaja'])): ?>
                                <span class="font-bold text-2xl uppercase tracking-wider text-[#5D3A1A]"><?= $gift['logo'] ?></span>
                             <?php else: ?>
                                <span class="material-symbols-outlined text-4xl text-[#D4A855]">credit_card</span>
                             <?php endif; ?>
                        </div>
                        
                         <?php if(!empty($gift['bank']) && $gift['logo'] === 'other'): ?>
                            <p class="font-bold text-xl text-[#5D3A1A]"><?= htmlspecialchars($gift['bank']) ?></p>
                        <?php endif; ?>

                        <div class="text-center z-10">
                            <p class="text-3xl font-mono font-bold tracking-widest my-2 select-all text-[#D4A855] drop-shadow-sm"><?= htmlspecialchars($gift['number']) ?></p>
                            <p class="text-sm font-bold text-[#5D3A1A] uppercase tracking-wide">a.n <?= htmlspecialchars($gift['owner']) ?></p>
                        </div>
                        
                        <!-- Copy Button -->
                         <button onclick="navigator.clipboard.writeText('<?= htmlspecialchars($gift['number']) ?>'); alert('Nomor Rekening Disalin!');" class="absolute right-4 top-4 p-2 rounded-full hover:bg-gray-100 text-[#D4A855] transition transform hover:rotate-12">
                            <span class="material-symbols-outlined text-xl">content_copy</span>
                        </button>
                    </div>
                    <?php endforeach; endif; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- COUNTDOWN MAIN -->
        <?php if(in_array('countdown', $features) && $akadDate): ?>
        <section id="countdown" class="py-16 md:py-24 px-4 bg-fixed bg-cover bg-center relative" style="background-image: url('<?= asset('assets/bg.jpg') ?>');">
             <div class="absolute inset-0 bg-[#5D3A1A]/90"></div>
             
             <div class="max-w-4xl mx-auto text-center relative z-10 animate-on-scroll" data-animation="fade-in-down">
                <h2 class="font-display text-3xl md:text-5xl mb-12 text-[#FDF8F3]">Menghitung Hari</h2>
                <div class="grid grid-cols-4 gap-4 md:gap-8 animate-on-scroll" data-animation="zoom-in">
                    <?php foreach(['Hari','Jam','Menit','Detik'] as $i => $label): ?>
                    <div class="p-6 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 shadow-xl">
                        <span id="cd-<?= $i ?>" class="font-display text-4xl md:text-6xl block text-[#D4A855] text-shadow">00</span>
                        <span class="text-xs md:text-sm font-bold tracking-widest uppercase text-white/80 mt-2 block"><?= $label ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
             </div>
        </section>
        <?php endif; ?>

        <!-- GALLERY -->
        <?php if(in_array('gallery', $features) && !empty($gallery_links)): ?>
        <section id="gallery" class="py-16 md:py-24 px-4">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16 animate-on-scroll" data-animation="fade-in-down">
                    <h2 class="font-display text-4xl md:text-5xl text-[#5D3A1A] mb-4">Galeri Foto</h2>
                    <div class="h-1 w-24 bg-[#D4A855] mx-auto rounded-full"></div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-6 animate-on-scroll" data-animation="zoom-in">
                    <?php foreach($gallery_links as $link): ?>
                    <a href="<?= htmlspecialchars(trim($link)) ?>" class="gallery-item block aspect-square rounded-xl overflow-hidden shadow-md relative group">
                        <img src="<?= htmlspecialchars(trim($link)) ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-[#5D3A1A]/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white backdrop-blur-[2px]">
                            <span class="material-symbols-outlined text-4xl drop-shadow-md transform scale-0 group-hover:scale-100 transition-transform duration-300 delay-100">zoom_in</span>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- RSVP & WISHES -->
        <?php if(in_array('rsvp', $features)): ?>
        <section id="rsvp" class="py-16 md:py-24 px-6 bg-[#FDF8F3] border-t border-[#D4A855]/20">
            <div class="max-w-lg mx-auto text-center">
                <h2 class="font-display text-4xl md:text-5xl mb-8 animate-on-scroll text-[#5D3A1A]" data-animation="fade-in-down">Konfirmasi Kehadiran</h2>
                
                <form action="guest_handler.php" method="POST" class="space-y-6 animate-on-scroll" data-animation="fade-in-up">
                    <input type="hidden" name="invitation_id" value="<?= $id ?>">
                    <input type="hidden" name="slug" value="<?= $slug ?>">
                    
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-[#D4A855] group-focus-within:text-[#5D3A1A] transition-colors">person</span>
                        <input type="text" name="name" placeholder="Nama Lengkap" required class="w-full pl-12 pr-4 py-3 rounded-xl border border-[#D4A855]/30 bg-white focus:outline-none focus:ring-2 focus:ring-[#D4A855]/50 focus:border-[#D4A855] transition-all shadow-sm">
                    </div>
                    
                    <div class="flex gap-4">
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="status" value="present" class="hidden peer" checked>
                            <div class="p-4 rounded-xl border border-[#D4A855]/30 bg-white transition-all peer-checked:bg-[#D4A855] peer-checked:text-white peer-checked:shadow-lg hover:bg-gray-50 flex flex-col items-center gap-1">
                                <span class="material-symbols-outlined">check_circle</span> 
                                <span class="text-sm font-bold">Hadir</span>
                            </div>
                        </label>
                        <label class="flex-1 cursor-pointer group">
                            <input type="radio" name="status" value="absent" class="hidden peer">
                            <div class="p-4 rounded-xl border border-[#D4A855]/30 bg-white transition-all peer-checked:bg-[#5D3A1A] peer-checked:text-white peer-checked:shadow-lg hover:bg-gray-50 flex flex-col items-center gap-1">
                                <span class="material-symbols-outlined">cancel</span> 
                                <span class="text-sm font-bold">Maaf</span>
                            </div>
                        </label>
                    </div>
                    
                    <div class="relative group">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-[#D4A855] group-focus-within:text-[#5D3A1A] transition-colors">edit_note</span>
                        <textarea name="message" placeholder="Ucapan & Doa..." rows="3" class="w-full pl-12 pr-4 py-3 rounded-xl border border-[#D4A855]/30 bg-white focus:outline-none focus:ring-2 focus:ring-[#D4A855]/50 focus:border-[#D4A855] transition-all shadow-sm resize-none"></textarea>
                    </div>
                    
                    <button type="submit" class="w-full py-4 rounded-full font-bold text-white transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg flex items-center justify-center gap-2" style="background: linear-gradient(to right, #8B4513, #5D3A1A);">
                        <span class="material-symbols-outlined">send</span> Kirim Konfirmasi
                    </button>
                </form>

                <!-- WISHES LIST -->
                <div class="mt-16 text-left space-y-4 max-h-[500px] overflow-y-auto px-2 custom-scrollbar">
                    <h3 class="font-display text-2xl text-center mb-6 text-[#5D3A1A]">Ucapan & Doa</h3>
                    <?php foreach($wishes as $wish): ?>
                    <div class="p-4 rounded-xl bg-white shadow-sm border border-[#D4A855]/10 relative">
                        <div class="absolute top-4 right-4 text-[#D4A855]/20"><span class="material-symbols-outlined text-4xl">format_quote</span></div>
                        <p class="font-bold mb-1 text-[#5D3A1A] flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[#D4A855]"></span>
                            <?= htmlspecialchars($wish['name']) ?>
                        </p>
                        <p class="text-sm italic text-[#5D3A1A]/80 pl-4 border-l-2 border-[#D4A855]/20">"<?= htmlspecialchars($wish['message']) ?>"</p>
                        <p class="text-xs text-right mt-2 text-gray-400"><?= date('d M Y', strtotime($wish['created_at'])) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- FOOTER -->
        <footer class="py-16 px-4 text-center text-[#FDF8F3] relative overflow-hidden" style="background: #5D3A1A;">
             <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none" style="background-image: url('<?= asset('assets/ilustrasi-1.svg') ?>'); background-size: 400px; background-repeat: repeat;"></div>
             
             <div class="relative z-10">
                <p class="font-display text-3xl mb-4">Terima Kasih</p>
                <div class="h-px w-24 bg-[#D4A855] mx-auto mb-6 opacity-50"></div>
                <p class="font-elegant text-2xl italic tracking-wide text-[#D4A855] mb-8">
                    <?= htmlspecialchars($invitation['groom_nickname']) ?> & <?= htmlspecialchars($invitation['bride_nickname']) ?>
                </p>
                <p class="text-[0.65rem] text-white/40 tracking-widest uppercase mb-4">&copy; <?= date('Y') ?> SyifazharStudio. All Rights Reserved.</p>
             </div>
        </footer>
    </div>
    
     <!-- NAVBAR -->
    <div id="navbar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-40 px-6 py-3 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] flex gap-6 items-center transition-all duration-500 translate-y-24 opacity-0 bg-white/80 backdrop-blur-md border border-white/50">
        <button onclick="scrollToSection('hero')" class="text-2xl text-[#5D3A1A] hover:text-[#D4A855] hover:-translate-y-1 transition-all nav-btn">
            <span class="material-symbols-outlined">home</span>
        </button>
        <button onclick="scrollToSection('couple')" class="text-2xl text-[#5D3A1A] hover:text-[#D4A855] hover:-translate-y-1 transition-all nav-btn">
            <span class="material-symbols-outlined">favorite</span>
        </button>
        <button onclick="scrollToSection('event')" class="text-2xl text-[#5D3A1A] hover:text-[#D4A855] hover:-translate-y-1 transition-all nav-btn">
            <span class="material-symbols-outlined">calendar_month</span>
        </button>
        <?php if(in_array('gallery', $features)): ?>
        <button onclick="scrollToSection('gallery')" class="text-2xl text-[#5D3A1A] hover:text-[#D4A855] hover:-translate-y-1 transition-all nav-btn">
            <span class="material-symbols-outlined">perm_media</span>
        </button>
        <?php endif; ?>
        <?php if(in_array('rsvp', $features)): ?>
        <button onclick="scrollToSection('rsvp')" class="text-2xl text-[#5D3A1A] hover:text-[#D4A855] hover:-translate-y-1 transition-all nav-btn">
            <span class="material-symbols-outlined">edit_note</span>
        </button>
        <?php endif; ?>
    </div>

    <!-- MUSIC -->
    <?php if(!empty($music_file)): ?>
    <audio id="bg-music" loop>
        <source src="<?= htmlspecialchars($music_file) ?>" type="audio/mpeg">
    </audio>
    <button id="music-toggle" class="hidden fixed bottom-24 right-6 z-40 w-12 h-12 rounded-full shadow-lg flex items-center justify-center bg-white/90 backdrop-blur border border-[#D4A855]">
        <span id="music-icon" class="material-symbols-outlined text-[#8B4513]">music_off</span>
    </button>
    <?php endif; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/simplelightbox/2.14.2/simple-lightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Lightbox
            new SimpleLightbox('.gallery-item', { 
                captionsData: 'alt',
                captionDelay: 250,
                animationSpeed: 200,
            });

            // Scroll
            window.scrollToSection = (id) => document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });

            // Open
            const openBtn = document.getElementById('open-invitation-btn');
            if(openBtn) {
                openBtn.addEventListener('click', () => {
                   document.getElementById('cover').classList.add('animate-fade-out');
                   setTimeout(() => {
                       document.getElementById('cover').style.display = 'none';
                       document.getElementById('app').classList.remove('hidden');
                       document.getElementById('app').classList.add('animate-fade-in');
                       document.getElementById('navbar').classList.remove('translate-y-24', 'opacity-0');
                       document.getElementById('music-toggle')?.classList.remove('hidden');
                       
                       const music = document.getElementById('bg-music');
                       const icon = document.getElementById('music-icon');
                       if(music) {
                           music.play().then(() => {
                               icon.innerText = 'music_note';
                               icon.parentElement.classList.add('animate-spin-slow');
                           }).catch(e => console.log('Autoplay blocked'));
                       }
                   }, 500);
                });
            }

            // Music
            const musicBtn = document.getElementById('music-toggle');
            if(musicBtn) {
                musicBtn.addEventListener('click', () => {
                    const music = document.getElementById('bg-music');
                    const icon = document.getElementById('music-icon');
                    if(music.paused) {
                        music.play();
                        icon.innerText = 'music_note';
                        musicBtn.classList.add('animate-spin-slow');
                    } else {
                        music.pause();
                        icon.innerText = 'music_off';
                        musicBtn.classList.remove('animate-spin-slow');
                    }
                });
            }

            // Countdown
            <?php if($akadDate): ?>
            const target = new Date(<?= $akadDate * 1000 ?>).getTime();
            setInterval(() => {
                const now = new Date().getTime();
                const diff = target - now;
                if(diff > 0) {
                    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((diff % (1000 * 60)) / 1000);
                    
                    ['cover-cd','cd'].forEach(p => {
                        const el = document.getElementById(`${p}-0`);
                        if(el) {
                            el.innerText = d;
                            document.getElementById(`${p}-1`).innerText = h;
                            document.getElementById(`${p}-2`).innerText = m;
                            document.getElementById(`${p}-3`).innerText = s;
                        }
                    });
                }
            }, 1000);
            <?php endif; ?>

            // Animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const anim = entry.target.getAttribute('data-animation');
                        if(anim) {
                            entry.target.classList.add(`animate-${anim}`);
                            entry.target.classList.remove('opacity-0');
                        }
                    }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>
