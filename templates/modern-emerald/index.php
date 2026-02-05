<?php
/**
 * Template 03 - Modern Emerald
 * 
 * This file expects the following variables from invitation.php:
 * - $invitation: array with invitation data
 * - $id: invitation ID
 * - $features: enabled features array
 * - $gallery_links: gallery images array
 * - $music_file: path to music file
 * - $wishes: array of guest wishes
 */

// Ensure we have invitation data
if (!isset($invitation) || empty($invitation)) {
    die("Template requires invitation data");
}

// Helper variables
$groom_name = htmlspecialchars($invitation['groom_name'] ?? 'Mempelai Pria');
$groom_nickname = htmlspecialchars($invitation['groom_nickname'] ?? 'Pria');
$bride_name = htmlspecialchars($invitation['bride_name'] ?? 'Mempelai Wanita');
$bride_nickname = htmlspecialchars($invitation['bride_nickname'] ?? 'Wanita');
$groom_father = htmlspecialchars($invitation['groom_father'] ?? '');
$groom_mother = htmlspecialchars($invitation['groom_mother'] ?? '');
$bride_father = htmlspecialchars($invitation['bride_father'] ?? '');
$bride_mother = htmlspecialchars($invitation['bride_mother'] ?? '');
$wishes_opening = htmlspecialchars($invitation['wishes_opening'] ?? 'Dengan memohon Rahmat dan Ridho Allah SWT, kami bermaksud menyelenggarakan acara pernikahan putra-putri kami:');

$event_date = $invitation['event_date'] ? date('l, d F Y', strtotime($invitation['event_date'])) : '';
$akad_time = $invitation['event_date'] ? date('H:i', strtotime($invitation['event_date'])) : '08:00';
$reception_time = $invitation['reception_date'] ? date('H:i', strtotime($invitation['reception_date'])) : '11:00';

$event_address = htmlspecialchars($invitation['event_address'] ?? '');
$reception_address = htmlspecialchars($invitation['reception_address'] ?? $invitation['event_address'] ?? '');
$map_link = $invitation['map_link'] ?? 'https://maps.google.com';
$reception_map_link = !empty($invitation['reception_map_link']) ? $invitation['reception_map_link'] : $map_link;

$cover_image = $invitation['cover_image'] ?? '';
$hero_image = $invitation['hero_image_link'] ?? '';
$groom_photo = $invitation['groom_photo'] ?? '';
$bride_photo = $invitation['bride_photo'] ?? '';

$guest_name = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan';

// Gift info
$gifts = json_decode($invitation['gifts'] ?? '[]', true) ?: [];

// Love story
$love_story = json_decode($invitation['love_story'] ?? '[]', true) ?: [];

// SoundCloud music URL
$music_url = $invitation['music_file'] ?? 'https://soundcloud.com/romanticweddingpianomusicensemble/wedding-music';

// RSVP Stats
$stmt_present = $pdo->prepare("SELECT COUNT(*) FROM guests WHERE invitation_id = ? AND status = 'present'");
$stmt_present->execute([$id]);
$total_present = $stmt_present->fetchColumn();

$stmt_absent = $pdo->prepare("SELECT COUNT(*) FROM guests WHERE invitation_id = ? AND status = 'absent'");
$stmt_absent->execute([$id]);
$total_absent = $stmt_absent->fetchColumn();

// Fetch Wishes
$stmt_wishes = $pdo->prepare("SELECT * FROM guests WHERE invitation_id = ? AND message != '' ORDER BY created_at DESC");
$stmt_wishes->execute([$id]);
$wishes = $stmt_wishes->fetchAll();

// Countdown calculation
$target_date = strtotime($invitation['event_date']);
$now = time();
$diff = $target_date - $now;
$days = max(0, floor($diff / (60 * 60 * 24)));
$hours = max(0, floor(($diff % (60 * 60 * 24)) / (60 * 60)));
$minutes = max(0, floor(($diff % (60 * 60)) / 60));
$seconds = max(0, floor($diff % 60));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Wedding -
        <?= $groom_nickname ?> &
        <?= $bride_nickname ?>
    </title>
    <meta name="description" content="Undangan Pernikahan <?= $groom_name ?> & <?= $bride_name ?>">
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#2D6A4F",
                        secondary: "#D8F3DC",
                        accent: "#74C69D",
                        "background-light": "#FAFFF5",
                        "background-dark": "#0B2518",
                        "card-light": "#FFFFFF",
                        "card-dark": "#1B4332",
                        "text-main-light": "#1B4332",
                        "text-main-dark": "#D8F3DC",
                        "text-sub-light": "#40916C",
                        "text-sub-dark": "#95D5B2",
                        "text-muted-light": "#40916C",
                        "text-muted-dark": "#95D5B2",
                        "accent-gold": "#D4A373",
                        "text-gold": "#B08968",
                        "surface-light": "#FFFFFF",
                        "surface-dark": "#1B4332",
                        "primary-light": "#52B788",
                    },
                    fontFamily: {
                        serif: ["'Cormorant Garamond'", "serif"],
                        sans: ["'Montserrat'", "sans-serif"],
                        display: ["'Playfair Display'", "serif"],
                        body: ["'Lato'", "sans-serif"],
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'shine': 'shine 1s',
                        'fade-in-up': 'fadeInUp 1.2s ease-out forwards',
                        'fade-in': 'fadeIn 0.8s ease-out forwards',
                        'spin-slow': 'spin 4s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' },
                            '100%': { transform: 'translateY(0px)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        shine: {
                            '100%': { left: '125%' }
                        }
                    },
                },
            },
        };
    </script>
    <style>
        html {
            scroll-behavior: smooth;
        }

        .bokeh {
            position: absolute;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            filter: blur(8px);
        }

        .bokeh:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            opacity: 0.3;
            animation: float 8s infinite;
        }

        .bokeh:nth-child(2) {
            width: 40px;
            height: 40px;
            top: 40%;
            right: 20%;
            opacity: 0.2;
            animation: float 6s infinite reverse;
        }

        .bokeh:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 60%;
            left: 30%;
            opacity: 0.25;
            animation: float 7s infinite 1s;
        }

        .fade-section {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .fade-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        .slide-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .slide-left.is-visible {
            opacity: 1;
            transform: translateX(0);
        }

        .slide-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 1s ease-out, transform 1s ease-out;
        }

        .slide-right.is-visible {
            opacity: 1;
            transform: translateX(0);
        }

        body {
            min-height: max(884px, 100dvh);
        }

        /* Custom Scrollbar with Primary Green */
        #main-view::-webkit-scrollbar {
            width: 6px;
        }

        #main-view::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #main-view::-webkit-scrollbar-thumb {
            background: #7A9A7E;
            border-radius: 10px;
        }

        #main-view::-webkit-scrollbar-thumb:hover {
            background: #5a7a5e;
        }

        /* Scrollable wishes area */
        .scroll-hidden::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-hidden::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-hidden::-webkit-scrollbar-thumb {
            background: #7A9A7E;
            border-radius: 10px;
        }

        /* Active Navigation Animation */
        .nav-item {
            position: relative;
            transition: all 0.3s ease;
        }

        .nav-item.active .material-icons {
            color: #7A9A7E !important;
            transform: scale(1.2);
        }

        .nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 6px;
            height: 6px;
            background: #7A9A7E;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: translateX(-50%) scale(1);
            }

            50% {
                opacity: 0.6;
                transform: translateX(-50%) scale(1.3);
            }
        }

        .nav-item .material-icons {
            transition: all 0.3s ease;
        }

        .nav-item:hover .material-icons {
            transform: scale(1.1);
        }
    </style>
</head>

<body
    class="bg-white dark:bg-background-dark font-sans antialiased h-screen overflow-hidden flex flex-col items-center justify-center relative transition-colors duration-500">

    <div id="main-container" class="relative w-full h-full max-w-md mx-auto bg-black overflow-hidden shadow-2xl">

        <!-- Audio Player -->
        <?php
        // Check if music_url is a SoundCloud link or local file
        $is_soundcloud = strpos($music_url, 'soundcloud.com') !== false;
        ?>
        <?php if (!empty($music_url) && in_array('music', $features)): ?>
            <?php if ($is_soundcloud): ?>
                <iframe id="sc-player" width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
                    src="https://w.soundcloud.com/player/?url=<?= urlencode($music_url) ?>&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false"
                    style="opacity: 0; position: absolute; pointer-events: none; z-index: -1;"></iframe>
            <?php else: ?>
                <audio id="audio-player" loop preload="auto" style="display: none;">
                    <source src="<?= htmlspecialchars($music_url) ?>" type="audio/mpeg">
                </audio>
            <?php endif; ?>
        <?php endif; ?>


        <!-- Floating Navigation -->
        <nav id="floating-nav"
            class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 bg-[#FAF9F6]/85 backdrop-blur-xl border border-white/50 shadow-lg rounded-full px-6 py-3 flex items-center justify-between gap-6 min-w-[320px] transition-all duration-300 opacity-0 translate-y-full">
            <a href="#hero" class="nav-item flex flex-col items-center group w-12" data-section="hero">
                <span
                    class="material-icons text-2xl text-primary/70 group-hover:text-primary transition-all">home</span>
                <span class="text-[10px] mt-1 text-primary/80">Home</span>
            </a>
            <a href="#bride-groom" class="nav-item flex flex-col items-center group w-12" data-section="bride-groom">
                <span
                    class="material-icons text-2xl text-primary/70 group-hover:text-primary transition-all">favorite</span>
                <span class="text-[10px] mt-1 text-primary/80">Couple</span>
            </a>
            <a href="#events" class="nav-item flex flex-col items-center group w-12" data-section="events">
                <span
                    class="material-icons text-2xl text-primary/70 group-hover:text-primary transition-all">calendar_today</span>
                <span class="text-[10px] mt-1 text-primary/80">Event</span>
            </a>
            <a href="#gallery" class="nav-item flex flex-col items-center group w-12" data-section="gallery">
                <span
                    class="material-icons text-2xl text-primary/70 group-hover:text-primary transition-all">photo_library</span>
                <span class="text-[10px] mt-1 text-primary/80">Gallery</span>
            </a>
            <a href="#gift" class="nav-item flex flex-col items-center group w-12" data-section="gift">
                <span
                    class="material-icons text-2xl text-primary/70 group-hover:text-primary transition-all">card_giftcard</span>
                <span class="text-[10px] mt-1 text-primary/80">Gift</span>
            </a>
        </nav>

        <!-- MAIN VIEW -->
        <div id="main-view"
            class="absolute inset-0 z-0 h-full w-full overflow-y-auto overflow-x-hidden hidden opacity-0 transition-opacity duration-1000 scroll-smooth bg-background-light">
            <!-- Hero -->
            <section id="hero"
                class="fade-section relative min-h-screen w-full flex flex-col justify-end items-center text-center bg-black">
                <div class="absolute inset-0"><img class="w-full h-full object-cover"
                        src="<?= $hero_image ?: 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=1000' ?>">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/30 to-black/80"></div>
                </div>
                <div class="relative z-20 pb-24 px-6 w-full max-w-md mx-auto text-white">
                    <p class="text-sm tracking-[0.2em] uppercase opacity-90 mb-2">The Wedding Of</p>
                    <h1 class="font-serif text-5xl drop-shadow-md mb-6"><?= $groom_nickname ?> <span
                            class="text-3xl italic text-accent-gold">&</span> <?= $bride_nickname ?></h1>
                    <p class="font-display text-2xl italic text-white/80"><?= $event_date ?></p>
                    <?php if (in_array('countdown', $features) && $target_date): ?>
                    <div class="grid grid-cols-4 gap-3 max-w-xs mx-auto my-6">
                        <div class="p-2 rounded-lg bg-white/10 border border-white/10 text-center"><span id="cd-d"
                                class="font-serif text-2xl"><?= str_pad($days, 2, '0', STR_PAD_LEFT) ?></span><span
                                class="block text-[0.6rem] uppercase opacity-80">Hari</span></div>
                        <div class="p-2 rounded-lg bg-white/10 border border-white/10 text-center"><span id="cd-h"
                                class="font-serif text-2xl"><?= str_pad($hours, 2, '0', STR_PAD_LEFT) ?></span><span
                                class="block text-[0.6rem] uppercase opacity-80">Jam</span></div>
                        <div class="p-2 rounded-lg bg-white/10 border border-white/10 text-center"><span id="cd-m"
                                class="font-serif text-2xl"><?= str_pad($minutes, 2, '0', STR_PAD_LEFT) ?></span><span
                                class="block text-[0.6rem] uppercase opacity-80">Menit</span></div>
                        <div class="p-2 rounded-lg bg-white/10 border border-white/10 text-center"><span id="cd-s"
                                class="font-serif text-2xl"><?= str_pad($seconds, 2, '0', STR_PAD_LEFT) ?></span><span
                                class="block text-[0.6rem] uppercase opacity-80">Detik</span></div>
                    </div>
                    <?php endif; ?>
                    <!-- <p class="text-xs uppercase opacity-75">Kepada Bapak/Ibu/Saudara/i</p>
                    <div class="font-semibold text-lg text-accent-gold"><?= $guest_name ?></div> -->
                </div>
            </section>

            <!-- Couple -->
            <section id="bride-groom"
                class="relative w-full flex flex-col items-center pt-12 pb-24 px-6 bg-background-light">
                <div class="fade-section text-center mb-12">
                    <p class="font-display italic text-lg text-gray-700 mb-4">"Assalamu'alaikum Warahmatullahi
                        Wabarakatuh"</p>
                    <!-- Kalimat Pembuka / Doa -->
                    <p class="text-sm text-gray-600 max-w-xs mx-auto"><?= $wishes_opening ?></p>
                </div>
                <div class="slide-left w-full flex flex-col items-center mb-16">
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-lg mb-6"><img
                            class="w-full h-full object-cover"
                            src="<?= $groom_photo ?: 'https://via.placeholder.com/200' ?>"></div>
                    <h2 class="font-display text-4xl text-gray-900 mb-2"><?= $groom_nickname ?></h2>
                    <p class="font-bold text-lg text-primary mb-4"><?= $groom_name ?></p>
                    <p class="text-sm text-gray-500">Putra dari</p>
                    <p class="font-medium text-gray-800">Bapak <?= $groom_father ?> & Ibu <?= $groom_mother ?></p>
                </div>
                <div class="fade-section flex items-center justify-center my-4 w-full">
                    <div class="h-px bg-primary/30 w-1/3"></div>
                    <div class="font-display text-5xl text-accent-gold mx-4">&</div>
                    <div class="h-px bg-primary/30 w-1/3"></div>
                </div>
                <div class="slide-right w-full flex flex-col items-center mt-12">
                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-white shadow-lg mb-6"><img
                            class="w-full h-full object-cover"
                            src="<?= $bride_photo ?: 'https://via.placeholder.com/200' ?>"></div>
                    <h2 class="font-display text-4xl text-gray-900 mb-2"><?= $bride_nickname ?></h2>
                    <p class="font-bold text-lg text-primary mb-4"><?= $bride_name ?></p>
                    <p class="text-sm text-gray-500">Putri dari</p>
                    <p class="font-medium text-gray-800">Bapak <?= $bride_father ?> & Ibu <?= $bride_mother ?></p>
                </div>
            </section>

            <!-- Love Story -->
            <?php if (!empty($love_story)): ?>
                <section id="love-story"
                    class="relative w-full flex flex-col items-center pt-12 pb-24 px-6 bg-white dark:bg-background-dark">
                    <div class="fade-section text-center mb-12">
                        <span
                            class="inline-block px-3 py-1 bg-primary/10 text-primary text-xs uppercase tracking-widest rounded-full font-medium mb-3">The
                            Journey</span>
                        <h1 class="font-serif text-4xl text-primary mb-2">Our Love Story</h1>
                        <p class="text-sm text-gray-500 max-w-xs mx-auto">Setiap cerita itu indah, tapi cerita kami adalah
                            favoritku.</p>
                    </div>

                    <div class="w-full max-w-md relative">
                        <!-- Vertical Line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-primary/20"></div>

                        <div class="space-y-12">
                            <?php foreach ($love_story as $index => $story): ?>
                                <div class="slide-right relative pl-12">
                                    <!-- Dot -->
                                    <div
                                        class="absolute left-[11px] top-1.5 w-3 h-3 rounded-full bg-primary border-4 border-white shadow-sm z-10">
                                    </div>

                                    <div class="space-y-2">
                                        <span
                                            class="text-xs font-bold tracking-widest text-primary uppercase bg-primary/5 px-2 py-1 rounded">
                                            <?= htmlspecialchars($story['year']) ?>
                                        </span>
                                        <h3 class="font-serif text-2xl text-gray-800"><?= htmlspecialchars($story['title']) ?>
                                        </h3>
                                        <p class="text-sm text-gray-600 leading-relaxed">
                                            <?= nl2br(htmlspecialchars($story['story'])) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Prewedding Gallery -->
            <?php if (!empty($gallery_links) && in_array('gallery', $features)): ?>
                <section class="pt-8 text-center space-y-4 w-full z-10 mt-8 relative">
                    <h2 class="font-serif text-3xl text-primary dark:text-primary-light">Prewedding Gallery</h2>
                    <p class="text-xs uppercase tracking-widest text-text-sub-light dark:text-text-sub-dark">Captured
                        Moments</p>
                </section>
                <section id="gallery"
                    class="fade-section grid grid-cols-2 gap-4 w-full max-w-sm mx-auto mt-6 px-6 z-10 relative mb-24">
                    <?php foreach ($gallery_links as $index => $image):
                        $isLarge = ($index % 3 == 0);
                        $colClass = $isLarge ? 'col-span-2 h-64' : 'h-48';
                        ?>
                        <div class="<?= $colClass ?> rounded-xl overflow-hidden shadow-md relative group cursor-pointer"
                            onclick="openGalleryModal(<?= $index ?>)">
                            <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                src="<?= htmlspecialchars($image) ?>" alt="Gallery <?= $index + 1 ?>">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                            <div
                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <span class="material-icons text-white/80 text-4xl drop-shadow-lg">fullscreen</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

            <!-- Events -->
            <section id="events"
                class="relative w-full flex flex-col items-center pt-12 pb-24 px-6 bg-background-light">
                <div class="fade-section text-center mb-8">
                    <h1 class="font-serif text-5xl italic text-primary mb-2">The Wedding</h1>
                    <p class="text-sm tracking-[0.2em] text-text-sub-light uppercase">Of <?= $groom_nickname ?> &
                        <?= $bride_nickname ?>
                    </p>
                </div>
                <div class="slide-left w-full max-w-sm bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col items-center text-center">
                        <span class="material-icons text-primary text-3xl mb-4">diamond</span>
                        <h3 class="font-serif text-3xl text-primary mb-1">Akad Nikah</h3>
                        <p class="text-xs uppercase text-gray-500 mb-6">The Holy Matrimony</p>
                        <div class="w-full text-left space-y-3">
                            <div class="flex items-center gap-3"><span
                                    class="material-icons text-accent">calendar_today</span><span
                                    class="font-semibold"><?= $event_date ?></span></div>
                            <div class="flex items-center gap-3"><span
                                    class="material-icons text-accent">schedule</span><span
                                    class="font-semibold"><?= $akad_time ?> WIB</span></div>
                            <div class="flex items-start gap-3 pt-3 border-t border-dashed"><span
                                    class="material-icons text-accent">location_on</span><span
                                    class="text-sm text-gray-600"><?= $event_address ?></span></div>
                        </div>
                    </div>
                </div>
                <a class="w-full max-w-sm bg-white text-primary border border-primary/20 py-4 rounded-xl flex items-center justify-center gap-2 shadow-lg mb-3"
                    href="<?= $map_link ?>" target="_blank"><span class="material-icons">map</span><span
                        class="font-medium">Google Maps<strong> Lokasi Akad Nikah</strong></span></a>
                <div class="slide-right w-full max-w-sm bg-white rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col items-center text-center">
                        <span class="material-icons text-primary text-3xl mb-4">restaurant</span>
                        <h3 class="font-serif text-3xl text-primary mb-1">Resepsi</h3>
                        <p class="text-xs uppercase text-gray-500 mb-6">Wedding Reception</p>
                        <div class="w-full text-left space-y-3">
                            <div class="flex items-center gap-3"><span
                                    class="material-icons text-accent">calendar_today</span><span
                                    class="font-semibold"><?= $event_date ?></span></div>
                            <div class="flex items-center gap-3"><span
                                    class="material-icons text-accent">schedule</span><span
                                    class="font-semibold"><?= $reception_time ?> WIB</span></div>
                            <div class="flex items-start gap-3 pt-3 border-t border-dashed"><span
                                    class="material-icons text-accent">domain</span><span
                                    class="text-sm text-gray-600"><?= $reception_address ?></span></div>
                        </div>
                    </div>
                </div>
                <a class="w-full max-w-sm bg-white text-primary border border-primary/20 py-4 rounded-xl flex items-center justify-center gap-2 shadow-lg"
                    href="<?= $reception_map_link ?>" target="_blank"><span class="material-icons">map</span><span
                        class="font-medium">Google Maps<strong> Lokasi Resepsi</strong></span></a>
            </section>


            <!-- Wedding Gift -->
            <?php if (in_array('gift', $features) && !empty($gifts)): ?>
                <section id="gift" class="relative w-full flex flex-col items-center pt-12 pb-24 px-6 bg-background-light">
                    <div class="fade-section text-center mb-8">
                        <h1 class="font-serif text-5xl italic text-primary mb-2">Wedding Gift</h1>
                        <p class="text-sm tracking-[0.2em] text-text-sub-light uppercase">Tanda Kasih</p>
                    </div>

                    <div class="w-full max-w-sm space-y-4">
                        <?php foreach ($gifts as $gift): ?>
                            <div class="slide-right bg-white p-6 rounded-2xl shadow-lg border border-primary/10 text-center">
                                <p class="font-bold text-lg text-primary"><?= htmlspecialchars($gift['bank']) ?></p>
                                <div class="flex items-center justify-center gap-2 my-2">
                                    <span
                                        class="font-mono text-xl tracking-wider text-gray-700"><?= htmlspecialchars($gift['number']) ?></span>
                                    <button onclick="copyToClipboard('<?= $gift['number'] ?>')"
                                        class="text-primary hover:text-accent-gold p-1">
                                        <span class="material-icons text-sm">content_copy</span>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-500 uppercase tracking-widest">A.n
                                    <?= htmlspecialchars($gift['owner']) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- RSVP -->
            <?php if (in_array('rsvp', $features)): ?>
            <section id="rsvp" class="relative w-full flex flex-col items-center pb-24 bg-background-light px-6 pt-12">
                <div class="fade-section text-center mb-8">
                    <h1 class="font-serif text-4xl text-primary italic mb-2">Reservasi</h1>
                    <p class="text-gray-500 text-sm uppercase tracking-widest">Kehadiran Anda Sangat Berarti</p>
                </div>
                <div class="fade-section grid grid-cols-2 gap-4 w-full max-w-sm mb-8">
                    <div class="bg-primary/10 p-4 rounded-xl text-center"><span
                            class="material-icons text-primary text-3xl">check_circle</span>
                        <h3 class="font-serif text-2xl font-bold"><?= $total_present ?></h3>
                        <p class="text-xs uppercase">Total Hadir</p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-xl text-center"><span
                            class="material-icons text-red-400 text-3xl">cancel</span>
                        <h3 class="font-serif text-2xl font-bold"><?= $total_absent ?></h3>
                        <p class="text-xs uppercase">Tidak Hadir</p>
                    </div>
                </div>
                <div class="slide-left bg-white p-6 rounded-3xl shadow-lg w-full max-w-sm">
                    <form action="guest_handler.php" method="POST" class="space-y-4">
                        <input type="hidden" name="invitation_id" value="<?= $id ?>">
                        <input type="hidden" name="slug" value="<?= htmlspecialchars($invitation['slug'] ?? '') ?>">
                        <div><label class="block text-sm font-medium mb-2">Nama</label><input name="name"
                                class="w-full px-4 py-3 bg-gray-50 rounded-xl" placeholder="Nama Anda" required></div>
                        <div><label class="block text-sm font-medium mb-2">Kehadiran</label><select name="status"
                                class="w-full px-4 py-3 bg-gray-50 rounded-xl" required>
                                <option value="">Pilih...</option>
                                <option value="present">Hadir</option>
                                <option value="absent">Tidak Hadir</option>
                            </select></div>
                        <div><label class="block text-sm font-medium mb-2">Ucapan</label><textarea name="message"
                                class="w-full px-4 py-3 bg-gray-50 rounded-xl resize-none" rows="3"
                                placeholder="Ucapan & Doa..."></textarea></div>
                        <button
                            class="w-full bg-primary text-white py-4 rounded-xl font-medium flex items-center justify-center gap-2"><span>Kirim</span><span
                                class="material-icons text-sm">send</span></button>
                    </form>
                </div>
            </section>
            <?php endif; ?>

            <!-- Closing Section with Wishes & Thank You -->
            <section id="closing"
                class="relative z-10 w-full flex flex-col items-center pb-0 bg-background-light dark:bg-background-dark text-gray-800 dark:text-gray-100 transition-colors duration-300 overflow-hidden">
                <div class="w-full max-w-md px-6 pb-12">
                    <!-- Scrollable Wishes Wall -->
                    <?php if (!empty($wishes) && in_array('wishes', $features)): ?>
                        <div class="fade-section space-y-4 max-h-[400px] overflow-y-auto pr-1 scroll-hidden relative">
                            <div
                                class="sticky top-0 bg-gradient-to-b from-background-light dark:from-background-dark to-transparent h-4 z-10 w-full pointer-events-none">
                            </div>

                            <?php foreach ($wishes as $wish): ?>
                                <div
                                    class="bg-surface-light dark:bg-surface-dark p-5 rounded-xl shadow-card border-l-4 <?= $wish['status'] === 'present' ? 'border-accent-gold' : 'border-primary/40' ?> bg-white dark:bg-white/5">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-primary dark:text-gray-200 font-display text-lg">
                                            <?= htmlspecialchars($wish['name']) ?>
                                        </h4>
                                        <span
                                            class="text-[10px] text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-full"><?= date('d M Y', strtotime($wish['created_at'])) ?></span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed italic">
                                        "<?= nl2br(htmlspecialchars($wish['message'])) ?>"</p>
                                    <div
                                        class="mt-3 flex items-center gap-1 text-xs <?= $wish['status'] === 'present' ? 'text-primary dark:text-gray-300 opacity-80' : 'text-gray-500 dark:text-gray-400 opacity-60' ?>">
                                        <span
                                            class="material-icons text-[14px]"><?= $wish['status'] === 'present' ? 'check_circle' : 'remove_circle_outline' ?></span>
                                        <span><?= $wish['status'] === 'present' ? 'Hadir' : 'Tidak Hadir' ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div
                                class="sticky bottom-0 bg-gradient-to-t from-background-light dark:from-background-dark to-transparent h-8 z-10 w-full pointer-events-none">
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Closing Message -->
                    <div class="fade-section text-center relative mt-8">
                        <div class="flex items-center justify-center text-accent-gold text-2xl mb-8">
                            <div class="h-px bg-accent-gold/50 flex-1 mx-4"></div>
                            <span class="material-icons">spa</span>
                            <div class="h-px bg-accent-gold/50 flex-1 mx-4"></div>
                        </div>
                        <h2 class="font-display text-3xl font-serif font-bold text-primary dark:text-white mb-6">Thank
                            You</h2>
                        <p
                            class="text-sm text-gray-600 dark:text-gray-300 leading-loose max-w-xs mx-auto mb-8 font-light">
                            Merupakan suatu kehormatan dan kebahagiaan bagi kami <br />
                            apabila Bapak/Ibu/Saudara/i berkenan hadir <br />
                            untuk memberikan doa restu.
                        </p>
                        <div class="font-serif text-xl text-primary dark:text-primary-light mb-10">
                            <?= $bride_nickname ?> <span class="text-accent-gold text-2xl px-2">&</span>
                            <?= $groom_nickname ?>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1 mb-6">
                            <p class="uppercase tracking-widest text-[10px] text-accent-gold mb-2">Keluarga Besar</p>
                            <p>Bpk. <?= $groom_father ?> & Ibu <?= $groom_mother ?></p>
                            <p>&</p>
                            <p>Bpk. <?= $bride_father ?> & Ibu <?= $bride_mother ?></p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <footer
                    class="fade-section w-full bg-primary text-white pt-8 pb-24 text-center relative overflow-hidden mt-auto">
                    <div
                        class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
                    </div>
                    <div class="relative z-10 flex flex-col items-center justify-center gap-2 px-6">
                        <p class="font-serif italic text-lg opacity-90">"Dan di antara tanda-tanda kekuasaan-Nya ialah
                            Dia
                            menciptakan untukmu pasangan-pasangan..."</p>
                        <p class="text-[10px] uppercase tracking-widest opacity-60 mt-2">Ar-Rum: 21</p>
                        <div class="w-12 h-0.5 bg-accent-gold/50 my-4 rounded-full"></div>
                        <p class="text-[10px] opacity-50 mb-5">&copy; <?= date('Y') ?>
                            SyifazharStudio. All Rights Reserved.</p>
                    </div>
                </footer>
            </section>
        </div>

        <!-- COVER VIEW -->
        <div id="cover-view" class="absolute inset-0 z-40 transition-all duration-1000 bg-black">
            <div class="absolute inset-0"><img class="w-full h-full object-cover"
                    src="<?= $hero_image ?: 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=1000' ?>">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/30 to-black/80"></div>
            <div class="absolute inset-0 flex flex-col justify-end items-center pb-20 px-6 text-center z-10">
                <p class="text-white/80 text-sm tracking-[0.2em] uppercase mb-2">The Wedding Of</p>
                <h1 class="text-5xl text-white font-serif mb-8"><?= $groom_nickname ?> <span
                        class="text-3xl italic">&</span> <?= $bride_nickname ?></h1>
                <!-- <div class="mb-10">
                    <p class="text-white/70 text-sm italic mb-2">Dear,</p>
                    <div class="bg-white/10 backdrop-blur-sm px-6 py-2 rounded-lg inline-block">
                        <p class="text-xl text-white font-serif"><?= $guest_name ?></p>
                    </div>
                </div> -->
                <button onclick="openInvitation()"
                    class="px-8 py-3 bg-primary text-secondary font-semibold rounded-full shadow-lg w-4/5 max-w-xs flex items-center justify-center gap-2">Buka
                    Undangan</button>
            </div>
        </div>

        <!-- Music Button -->
        <!-- Music Button -->
        <?php if (!empty($music_url) && in_array('music', $features)): ?>
        <div id="music-container" class="absolute bottom-6 right-6 z-50"><button id="music-btn" onclick="toggleMusic()"
                class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white animate-spin-slow"><span
                    class="material-icons">music_note</span></button></div>
        <?php endif; ?>
        <div id="toast"
            class="fixed top-24 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs py-2 px-4 rounded-full opacity-0 transition-opacity z-50">
            <span class="material-icons text-sm text-green-400">check</span> Copied
        </div>
    </div>

    <script>
        const targetDate = <?= $target_date * 1000 ?>;
        function updateCountdown() {
            const diff = Math.max(0, targetDate - Date.now());
            document.getElementById('cd-d').textContent = String(Math.floor(diff / 86400000)).padStart(2, '0');
            document.getElementById('cd-h').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
            document.getElementById('cd-m').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
            document.getElementById('cd-s').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
        }
        setInterval(updateCountdown, 1000);

        const nav = document.getElementById('floating-nav');
        const mainView = document.getElementById('main-view');
        const musicContainer = document.getElementById('music-container');
        mainView?.addEventListener('scroll', () => {
            if (mainView.scrollTop > 300) { nav.classList.remove('opacity-0', 'translate-y-full'); musicContainer?.classList.replace('bottom-6', 'bottom-24'); }
            else { nav.classList.add('opacity-0', 'translate-y-full'); musicContainer?.classList.replace('bottom-24', 'bottom-6'); }
        });

        const observer = new IntersectionObserver(entries => entries.forEach(e => e.isIntersecting && e.target.classList.add('is-visible')), { root: mainView, threshold: 0.2 });
        document.querySelectorAll('.fade-section, .slide-left, .slide-right').forEach(el => observer.observe(el));

        // Active navigation detection
        const sections = document.querySelectorAll('section[id]');
        const navItems = document.querySelectorAll('.nav-item');

        const navObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const sectionId = entry.target.getAttribute('id');
                    navItems.forEach(item => {
                        item.classList.remove('active');
                        if (item.getAttribute('data-section') === sectionId) {
                            item.classList.add('active');
                        }
                    });
                }
            });
        }, { root: mainView, threshold: 0.3 });

        sections.forEach(section => navObserver.observe(section));

        let isPlaying = false, widget = null, audioPlayer = null;
        const iframeElement = document.getElementById('sc-player');
        const audioElement = document.getElementById('audio-player');

        // Detect which player is available
        if (iframeElement) {
            // SoundCloud player
            const tag = document.createElement('script');
            tag.src = "https://w.soundcloud.com/player/api.js";
            document.body.appendChild(tag);

            tag.onload = () => {
                widget = SC.Widget(iframeElement);
                widget.bind(SC.Widget.Events.READY, () => {
                    console.log('SoundCloud Ready');
                });
                widget.bind(SC.Widget.Events.FINISH, () => widget.play());
            };
        } else if (audioElement) {
            // HTML5 Audio player
            audioPlayer = audioElement;
            audioPlayer.addEventListener('ended', () => audioPlayer.play());
            console.log('Audio Player Ready');
        }

        function toggleMusic() {
            const btn = document.getElementById('music-btn');

            if (widget) {
                widget.toggle();
                isPlaying = !isPlaying;
            } else if (audioPlayer) {
                if (isPlaying) {
                    audioPlayer.pause();
                } else {
                    audioPlayer.play();
                }
                isPlaying = !isPlaying;
            }

            btn.classList.toggle('animate-spin-slow', isPlaying);
            btn.innerHTML = isPlaying ? '<span class="material-icons">pause</span>' : '<span class="material-icons">play_arrow</span>';
        }

        function openInvitation() {
            const cover = document.getElementById('cover-view'), main = document.getElementById('main-view');
            cover.style.transform = "translateY(-100%)"; cover.style.opacity = "0";
            main.classList.remove('hidden'); setTimeout(() => main.classList.remove('opacity-0'), 50);

            // Try to play music
            const btn = document.getElementById('music-btn');
            if (widget) {
                widget.play();
                isPlaying = true;
                btn.classList.add('animate-spin-slow');
                btn.innerHTML = '<span class="material-icons">pause</span>';
            } else if (audioPlayer) {
                audioPlayer.play().then(() => {
                    isPlaying = true;
                    btn.classList.add('animate-spin-slow');
                    btn.innerHTML = '<span class="material-icons">pause</span>';
                }).catch(err => {
                    console.log('Autoplay blocked:', err);
                    // Keep button as play icon if autoplay blocked
                });
            }

            setTimeout(() => cover.style.display = 'none', 1000);
        }


        function copyToClipboard(text) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => showToast());
            } else {
                // Fallback for HTTP
                const textArea = document.createElement("textarea");
                textArea.value = text;
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast();
                } catch (err) {
                    console.error('Unable to copy', err);
                }
                document.body.removeChild(textArea);
            }
        }

        function showToast() {
            const t = document.getElementById('toast');
            t.classList.remove('opacity-0');
            setTimeout(() => t.classList.add('opacity-0'), 2000);
        }

        // Gallery Modal
        const galleryImages = <?= json_encode($gallery_links) ?>;
        let currentGalleryIndex = 0;

        function openGalleryModal(index) {
            currentGalleryIndex = index;
            updateGalleryModal();
            const modal = document.getElementById('gallery-modal');
            modal.classList.remove('hidden');
            setTimeout(() => modal.classList.remove('opacity-0'), 10);
        }

        function closeGalleryModal() {
            const modal = document.getElementById('gallery-modal');
            modal.classList.add('opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }

        function changeGallerySlide(direction) {
            currentGalleryIndex = (currentGalleryIndex + direction + galleryImages.length) % galleryImages.length;
            updateGalleryModal();
        }

        function updateGalleryModal() {
            const img = document.getElementById('gallery-modal-image');
            const counter = document.getElementById('gallery-counter');
            img.src = galleryImages[currentGalleryIndex];
            counter.textContent = `${currentGalleryIndex + 1} / ${galleryImages.length}`;
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeGalleryModal();
            if (e.key === 'ArrowRight') changeGallerySlide(1);
            if (e.key === 'ArrowLeft') changeGallerySlide(-1);
        });
    </script>

    <!-- Gallery Modal -->
    <div id="gallery-modal"
        class="fixed inset-0 z-[60] hidden flex items-center justify-center bg-black/95 transition-opacity duration-300 opacity-0">
        <button class="absolute top-4 right-4 text-white hover:text-gray-300 z-50 p-2" onclick="closeGalleryModal()">
            <span class="material-icons text-3xl">close</span>
        </button>
        <button
            class="absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 z-50 p-2 bg-black/20 hover:bg-black/40 rounded-full"
            onclick="changeGallerySlide(-1)">
            <span class="material-icons text-4xl">chevron_left</span>
        </button>
        <button
            class="absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-gray-300 z-50 p-2 bg-black/20 hover:bg-black/40 rounded-full"
            onclick="changeGallerySlide(1)">
            <span class="material-icons text-4xl">chevron_right</span>
        </button>
        <div class="relative max-w-4xl max-h-[85vh] w-full mx-4 flex items-center justify-center">
            <img id="gallery-modal-image" src="" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
                alt="Gallery Fullscreen">
        </div>
        <div class="absolute bottom-6 left-0 right-0 text-center text-white/80 font-serif text-sm">
            <span id="gallery-counter">1 / 1</span>
        </div>
    </div>
</body>

</html>