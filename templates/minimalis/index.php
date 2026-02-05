<?php
/**
 * Template Minimalis (Navy Blue)
 * 
 * Variables from invitation.php:
 * - $invitation, $id, $features, $gallery_links, $music_file, $pdo
 */

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
$wishes_opening = htmlspecialchars($invitation['wishes_opening'] ?? 'Maha suci Allah yang telah menciptakan mahluk-Nya berpasang-pasangan. Ya Allah semoga ridho-Mu tercurah mengiringi pernikahan putra-putri kami:');

$event_date = $invitation['event_date'] ? date('l, d F Y', strtotime($invitation['event_date'])) : '';
$event_date_short = $invitation['event_date'] ? strtoupper(date('l, d F Y', strtotime($invitation['event_date']))) : '';
$akad_time = $invitation['event_date'] ? date('H:i', strtotime($invitation['event_date'])) : '08:00';
$reception_date = $invitation['reception_date'] ? date('l, d F Y', strtotime($invitation['reception_date'])) : $event_date;
$reception_time = $invitation['reception_date'] ? date('H:i', strtotime($invitation['reception_date'])) : '11:00';

$event_address = htmlspecialchars($invitation['event_address'] ?? '');
$reception_address = htmlspecialchars($invitation['reception_address'] ?? $invitation['event_address'] ?? '');
$map_link = $invitation['map_link'] ?? 'https://maps.google.com';
$reception_map_link = !empty($invitation['reception_map_link']) ? $invitation['reception_map_link'] : $map_link;

$hero_image = $invitation['hero_image_link'] ?? '';
$groom_photo = $invitation['groom_photo'] ?? '';
$bride_photo = $invitation['bride_photo'] ?? '';

$guest_name = isset($_GET['to']) ? htmlspecialchars($_GET['to']) : 'Tamu Undangan';
$slug = $invitation['slug'] ?? '';

// Fetch Gifts from invitation JSON data
$gifts = json_decode($invitation['gifts'] ?? '[]', true) ?: [];

// Fetch Love Stories from invitation JSON data
$stories = json_decode($invitation['love_story'] ?? '[]', true) ?: [];

// Music URL
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
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Wedding Invitation -
        <?= $groom_nickname ?> &amp;
        <?= $bride_nickname ?>
    </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Great+Vibes&family=Lato:wght@300;400;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#263c5a", // Deep Navy
                        secondary: "#e2e8f0", // Light gray/white text
                        accent: "#7a9e35", // Green from flower leaves
                        "background-light": "#f8fafc", // Very light slate
                        "background-dark": "#0f172a", // Darker slate/navy for dark mode
                        "overlay-dark": "rgba(15, 23, 42, 0.6)",
                    },
                    fontFamily: {
                        serif: ['Cinzel', 'serif'],
                        script: ['Great Vibes', 'cursive'],
                        sans: ['Lato', 'sans-serif'],
                        playfair: ["Playfair Display", "serif"],
                    },
                    backgroundImage: {
                        'gradient-to-t-custom': 'linear-gradient(to top, #263c5a 40%, transparent 100%)',
                        'gradient-dark': 'linear-gradient(to top, #0f172a 50%, transparent 100%)',
                    },
                    animation: {
                        'spin-slow': 'spin 8s linear infinite',
                        'float': 'float 6s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                },
            },
        };
    </script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #263c5a;
            /* Primary color */
            border-radius: 20px;
            border: 3px solid transparent;
            background-clip: content-box;
        }

        /* Custom Animations */
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .delay-100 {
            animation-delay: 0.1s;
        }

        .delay-200 {
            animation-delay: 0.2s;
        }

        .delay-300 {
            animation-delay: 0.3s;
        }

        .delay-500 {
            animation-delay: 0.5s;
        }

        .delay-700 {
            animation-delay: 0.7s;
        }

        .delay-1000 {
            animation-delay: 1s;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .leaf-pattern {
            position: absolute;
            background-size: contain;
            background-repeat: no-repeat;
            pointer-events: none;
            z-index: 20;
        }

        /* Fix for mobile height */
        body {
            min-height: 100vh;
            min-height: 100dvh;
        }
    </style>
</head>

<body
    class="bg-gray-100 dark:bg-gray-900 min-h-screen font-sans antialiased flex items-center justify-center overflow-x-hidden">

    <!-- Main Mobile Container -->
    <div id="main-container"
        class="relative w-full h-[100dvh] max-w-md bg-primary dark:bg-slate-900 shadow-2xl overflow-hidden flex flex-col">

        <!-- ================= MAIN VIEW (Cover 2 / Inner Content) ================= -->
        <div id="main-view"
            class="absolute inset-0 z-0 h-full w-full overflow-y-auto hidden opacity-0 transition-opacity duration-1000 scroll-smooth">

            <!-- Hero Section -->
            <div class="relative w-full h-[100dvh]">
                <!-- Background Image Section -->
                <div class="absolute inset-0 z-0 h-[70%] w-full">
                    <img alt="Happy couple wedding portrait"
                        class="w-full h-full object-cover object-top animate-[scale-in_20s_linear_infinite]"
                        src="<?= $hero_image ?: 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=1000' ?>" />
                    <!-- Gradient Overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary via-primary/60 to-transparent dark:from-slate-900 dark:via-slate-900/70 dark:to-transparent h-full">
                    </div>
                </div>

                <!-- Decorative Elements (Adjusted Position) -->
                <div
                    class="leaf-pattern top-[-10px] right-0 w-48 h-48 opacity-90 transform rotate-12 z-20 animate-float">
                    <img alt="Floral decoration top right"
                        class="w-full h-full object-cover opacity-60 mix-blend-screen rounded-full"
                        src="templates/minimalis/assets/flower-1.svg"
                        style="mask-image: radial-gradient(circle, black 50%, transparent 80%); -webkit-mask-image: radial-gradient(circle, black 50%, transparent 80%);" />
                </div>

                <div class="leaf-pattern bottom-0 left-[-30px] w-64 h-64 opacity-80 z-20 animate-float delay-1000">
                    <img alt="Floral decoration bottom left"
                        class="w-full h-full object-cover opacity-50 mix-blend-screen rounded-full"
                        src="templates/minimalis/assets/flower-2.svg"
                        style="mask-image: radial-gradient(circle at bottom left, black 50%, transparent 80%); -webkit-mask-image: radial-gradient(circle at bottom left, black 50%, transparent 80%);" />
                </div>

                <!-- Content Section (Main View) -->
                <div class="relative z-30 flex flex-col justify-end h-full pb-12 px-6 text-center text-secondary">
                    <div class="animate-fade-in-up">
                        <p class="uppercase tracking-[0.2em] text-xs font-serif text-slate-300 mb-2">The Wedding Of</p>
                    </div>

                    <div class="animate-fade-in-up delay-100 mb-6 relative">
                        <h1 class="font-script text-6xl md:text-7xl leading-tight text-white drop-shadow-lg">
                            <?= $groom_nickname ?> <span
                                class="text-4xl px-1 font-serif align-middle text-accent">&amp;</span>
                            <?= $bride_nickname ?>
                        </h1>
                    </div>

                    <div class="animate-fade-in-up delay-200 mb-8">
                        <p
                            class="font-serif text-lg tracking-widest border-y border-white/20 py-2 inline-block px-4 text-accent">
                            <?= $event_date_short ?>
                        </p>
                    </div>

                    <!-- Countdown Timer (Keep in Main View) -->
                    <?php if (in_array('countdown', $features) && $target_date): ?>
                    <div
                        class="countdown-container animate-fade-in-up delay-300 mb-8 flex justify-center space-x-4 text-slate-200">
                        <!-- Filled by JS -->
                    </div>
                    <?php endif; ?>

                    <!-- Scroll Indicator -->
                    <div class="animate-bounce mt-4">
                        <span class="material-icons text-white/50 text-2xl">keyboard_arrow_down</span>
                    </div>
                </div>
            </div>

            <!-- Bride & Groom Section (Integrated & Styled) -->
            <section
                class="relative z-30 min-h-screen flex flex-col items-center justify-start py-16 px-8 bg-primary text-secondary overflow-hidden">

                <div
                    class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(122,158,53,0.05)_0%,transparent_70%)] pointer-events-none">
                </div>

                <div
                    class="text-center mb-14 w-full max-w-md relative z-10 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                    <p class="text-accent text-sm italic font-playfair tracking-widest mb-4">
                        "Assalamu’alaikum Warahmatullahi Wabarakatuh"
                    </p>
                    <!-- Kalimat Pembuka / Doa -->
                    <p class="text-slate-300 text-xs font-light leading-relaxed px-6">
                        <?= $wishes_opening ?>
                    </p>
                </div>

                <div class="w-full max-w-sm space-y-16 relative z-10">
                    <!-- Groom -->
                    <div
                        class="flex flex-col items-center text-center scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-200">
                        <div class="relative mb-8">
                            <div
                                class="w-44 h-44 rounded-full border border-accent/40 p-1.5 flex items-center justify-center">
                                <div class="w-full h-full rounded-full overflow-hidden border border-accent/20">
                                    <img alt="<?= $groom_nickname ?>" class="w-full h-full object-cover"
                                        src="<?= $groom_photo ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' ?>" />
                                </div>
                            </div>
                        </div>
                        <h2 class="font-script text-5xl text-accent mb-2"><?= $groom_nickname ?></h2>
                        <h3 class="font-playfair text-lg font-semibold text-white mb-2 tracking-wide"><?= $groom_name ?>
                        </h3>
                        <div class="text-sm text-slate-400 space-y-1 font-light italic">
                            <p>Putra dari Bapak <?= $groom_father ?></p>
                            <p>&amp; Ibu <?= $groom_mother ?></p>
                        </div>
                        <div class="mt-4">
                            <span class="material-icons text-accent opacity-60 text-xl">camera_alt</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div
                        class="flex items-center justify-center w-full scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-300">
                        <div
                            class="h-[1px] bg-gradient-to-r from-transparent via-accent/30 to-transparent w-full relative">
                            <span
                                class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-primary px-4 font-script text-3xl text-accent">and</span>
                        </div>
                    </div>

                    <!-- Bride -->
                    <div
                        class="flex flex-col items-center text-center scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-500">
                        <div class="relative mb-8">
                            <div
                                class="w-44 h-44 rounded-full border border-accent/40 p-1.5 flex items-center justify-center">
                                <div class="w-full h-full rounded-full overflow-hidden border border-accent/20">
                                    <img alt="<?= $bride_nickname ?>" class="w-full h-full object-cover"
                                        src="<?= $bride_photo ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400' ?>" />
                                </div>
                            </div>
                        </div>
                        <h2 class="font-script text-5xl text-accent mb-2"><?= $bride_nickname ?></h2>
                        <h3 class="font-playfair text-lg font-semibold text-white mb-2 tracking-wide"><?= $bride_name ?>
                        </h3>
                        <div class="text-sm text-slate-400 space-y-1 font-light italic">
                            <p>Putri dari Bapak <?= $bride_father ?></p>
                            <p>&amp; Ibu <?= $bride_mother ?></p>
                        </div>
                        <div class="mt-4">
                            <span class="material-icons text-accent opacity-60 text-xl">camera_alt</span>
                        </div>
                    </div>
                </div>

                <!-- <div
                    class="mt-20 mb-10 w-full max-w-xs relative z-10 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-700">
                    <button
                        class="flex items-center justify-center space-x-3 w-full py-3.5 border border-accent/30 bg-accent/5 rounded-full text-white hover:bg-accent/10 transition-all duration-500 backdrop-blur-sm">
                        <span class="material-icons text-lg text-accent">calendar_today</span>
                        <span class="text-xs font-serif tracking-[0.2em] uppercase">Save the Date</span>
                    </button>
                </div> -->
            </section>

            <!-- Smooth Divider Transition -->
            <style>
                @keyframes sway {

                    0%,
                    100% {
                        transform: translateY(0) scale(1);
                    }

                    50% {
                        transform: translateY(-5px) scale(1.02);
                    }
                }

                .alive-border {
                    animation: sway 4s ease-in-out infinite;
                }
            </style>
            <div class="relative w-full z-30 -mt-2">
                <!-- Gradual Blue-to-White Gradient Transition -->
                <div class="w-full pt-24 flex flex-col items-center justify-end overflow-hidden">
                    <!-- Animated Border Image -->
                    <img src="templates/minimalis/assets/border-2.svg"
                        class="w-full max-w-5xl h-auto object-cover relative z-10 alive-border opacity-90"
                        style="min-height: 80px;" alt="Decorative Divider" />
                </div>
            </div>

            <!-- Our Story Section -->
            <section
                class="relative z-30 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 py-16 px-6 overflow-hidden">
                <!-- Decorative Elements -->
                <div
                    class="absolute top-0 right-0 w-32 h-32 opacity-20 pointer-events-none transform translate-x-8 -translate-y-8">
                    <img alt="Floral decoration " class="w-full h-full object-contain"
                        src="templates/minimalis/assets/flower-1.svg" />
                </div>

                <div class="relative z-10">
                    <div
                        class="text-center mb-10 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                        <span class="uppercase tracking-widest text-xs font-semibold text-accent mb-2 block">The
                            Journey</span>
                        <h2 class="font-display text-4xl text-gray-900 dark:text-white mb-2 font-script">Our Story</h2>
                        <div class="w-16 h-0.5 bg-accent mx-auto rounded-full"></div>
                    </div>

                    <div
                        class="space-y-6 mb-12 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-200">
                        <p
                            class="text-gray-600 dark:text-gray-300 font-serif text-lg leading-relaxed text-center italic">
                            "We met by chance, became friends by choice, and fell in love without even realizing it.
                            From strangers to soulmates, our journey has been nothing short of a beautiful serendipity."
                        </p>
                        <!-- <div
                            class="flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="material-icons-outlined text-accent text-base">favorite</span>
                            <span>Since 2018</span>
                        </div> -->
                    </div>

                    <!-- Timeline -->
                    <div class="relative border-l-2 border-accent/20 ml-6 space-y-10 pl-8 pb-4 max-w-sm mx-auto">
                        <?php if (!empty($stories)): ?>
                            <?php foreach ($stories as $index => $story): ?>
                                <div
                                    class="relative scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-<?= 300 + ($index * 200) ?>">
                                    <div
                                        class="absolute -left-[41px] top-1 w-6 h-6 rounded-full bg-white dark:bg-gray-800 border-4 border-accent flex items-center justify-center">
                                    </div>
                                    <h3 class="font-serif font-semibold text-xl text-gray-800 dark:text-gray-100">
                                        <?= htmlspecialchars($story['title']) ?>
                                    </h3>
                                    <span
                                        class="text-xs text-accent font-bold uppercase tracking-wider block mt-1"><?= htmlspecialchars($story['year']) ?></span>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                                        <?= htmlspecialchars($story['story']) ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Default placeholder if no stories -->
                            <div
                                class="relative scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-300">
                                <div
                                    class="absolute -left-[41px] top-1 w-6 h-6 rounded-full bg-white dark:bg-gray-800 border-4 border-accent flex items-center justify-center">
                                </div>
                                <h3 class="font-serif font-semibold text-xl text-gray-800 dark:text-gray-100">First Meeting
                                </h3>
                                <span class="text-xs text-accent font-bold uppercase tracking-wider block mt-1">Our
                                    Beginning</span>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">The start of our
                                    beautiful journey together.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- Events Section (Akad & Resepsi) -->
            <section class="py-16 px-6 bg-[#f9f5f6] dark:bg-[#1b2432] relative z-30">
                <div
                    class="text-center mb-10 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-100">
                    <p class="italic font-display text-lg text-gray-600 dark:text-gray-300 max-w-lg mx-auto">
                        "Dengan memohon Rahmat dan Ridho Allah SWT, kami bermaksud menyelenggarakan acara pernikahan
                        putra-putri kami."
                    </p>
                </div>

                <div class="w-full space-y-8 max-w-lg mx-auto">
                    <!-- Akad Nikah Card -->
                    <div
                        class="relative bg-white dark:bg-[#243042] shadow-xl dark:shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transform transition hover:-translate-y-1 duration-300 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-200">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-primary"></div>
                        <div class="p-8 flex flex-col items-center text-center">
                            <div
                                class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mb-4 text-primary">
                                <span class="material-icons text-2xl">favorite</span>
                            </div>
                            <h2 class="font-serif text-2xl font-bold mb-2 text-primary dark:text-white">Akad Nikah</h2>
                            <div class="my-4 w-full border-t border-gray-200 dark:border-gray-600 border-dashed"></div>
                            <div class="space-y-4 font-sans text-gray-700 dark:text-gray-300">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-accent mb-1">calendar_today</span>
                                    <p class="font-bold text-lg"><?= $event_date ?></p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-accent mb-1">schedule</span>
                                    <p class="text-lg"><?= $akad_time ?> WIB - Selesai</p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-accent mb-1">place</span>
                                    <div class="text-center px-4">
                                        <p class="text-sm opacity-80"><?= $event_address ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 w-full flex gap-3">
                                <!-- <button
                                    class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                                    <span class="material-icons text-sm">event</span>
                                    Save Date
                                </button> -->
                                <a href="<?= $map_link ?>" target="_blank"
                                    class="flex-1 bg-primary text-white hover:opacity-90 py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 transition-colors shadow-lg shadow-primary/30">
                                    <span class="material-icons text-sm">map</span>
                                    Google Maps
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Resepsi Card -->
                    <div
                        class="relative bg-white dark:bg-[#243042] shadow-xl dark:shadow-2xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 transform transition hover:-translate-y-1 duration-300 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-300">
                        <div class="absolute top-0 right-0 w-1.5 h-full bg-accent"></div>
                        <!-- Flower Decoration opacity adjusted for visibility but subtlety -->
                        <img alt="flower decoration"
                            class="absolute -top-6 -left-6 w-24 h-24 opacity-20 object-cover rounded-full mix-blend-multiply dark:mix-blend-screen pointer-events-none"
                            src="templates/minimalis/assets/flower-1.svg" />

                        <div class="p-8 flex flex-col items-center text-center relative z-10">
                            <div
                                class="w-12 h-12 rounded-full bg-accent/20 flex items-center justify-center mb-4 text-accent">
                                <span class="material-icons text-2xl">celebration</span>
                            </div>
                            <h2 class="font-serif text-2xl font-bold mb-2 text-primary dark:text-white">Resepsi</h2>
                            <div class="my-4 w-full border-t border-gray-200 dark:border-gray-600 border-dashed"></div>
                            <div class="space-y-4 font-sans text-gray-700 dark:text-gray-300">
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-accent mb-1">calendar_today</span>
                                    <p class="font-bold text-lg"><?= $reception_date ?></p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-accent mb-1">schedule</span>
                                    <p class="text-lg"><?= $reception_time ?> WIB - Selesai</p>
                                </div>
                                <div class="flex flex-col items-center">
                                    <span class="material-icons text-accent mb-1">place</span>
                                    <div class="text-center px-4">
                                        <p class="text-sm opacity-80"><?= $reception_address ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 w-full flex gap-3">
                                <!-- <button
                                    class="flex-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-white py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 transition-colors">
                                    <span class="material-icons text-sm">event</span>
                                    Save Date
                                </button> -->
                                <a href="<?= $reception_map_link ?>" target="_blank"
                                    class="flex-1 bg-primary text-white hover:opacity-90 py-3 px-4 rounded-xl text-sm font-semibold flex items-center justify-center gap-2 transition-colors shadow-lg shadow-primary/30">
                                    <span class="material-icons text-sm">map</span>
                                    Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- Gallery Preview -->
            <?php if (in_array('gallery', $features) && !empty($gallery_links)): ?>
            <section class="py-12 px-4 bg-gray-50 dark:bg-gray-800/50 relative z-30">
                <div
                    class="text-center mb-8 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                    <span class="uppercase tracking-widest text-xs font-semibold text-accent mb-2 block">Moments</span>
                    <h2 class="font-display font-script text-4xl text-gray-900 dark:text-white mb-2">Our Gallery</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-xs mx-auto">Capturing the love, laughter,
                        and every beautiful moment in between.</p>
                </div>

                <div
                    class="grid grid-cols-2 gap-3 max-w-sm mx-auto scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-200">
                    <?php
                    $defaultImages = [
                        'https://images.unsplash.com/photo-1549417229-aa67d3263c09?w=800',
                        'https://images.unsplash.com/photo-1519741497674-611481863552?w=800',
                        'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?w=800',
                        'https://images.unsplash.com/photo-1507504031981-8237c0c979d0?w=800',
                        'https://images.unsplash.com/photo-1519225421980-715cb0202128?w=800',
                        'https://images.unsplash.com/photo-1520854221256-17451cc330e7?w=800'
                    ];
                    $images = !empty($gallery_links) ? $gallery_links : $defaultImages;
                    $gridHeights = ['row-span-2 h-64', 'h-32', 'h-32', 'h-32', 'row-span-2 h-64', 'h-32'];
                    foreach ($images as $index => $image):
                        $height = $gridHeights[$index % 6] ?? 'h-32';
                        ?>
                        <div class="col-span-1 <?= $height ?> relative group overflow-hidden rounded-xl shadow-md cursor-pointer"
                            onclick="openGallery(<?= $index ?>)">
                            <img alt="Wedding Moment <?= $index + 1 ?>"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                src="<?= htmlspecialchars($image) ?>" />
                            <div
                                class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <span class="material-icons text-white">zoom_in</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </section>
            <?php endif; ?>

            <!-- Digital Gift Section -->
            <?php if (in_array('gift', $features)): ?>
            <section class="py-16 px-6 bg-[#fdf2f8] dark:bg-[#111827] relative z-30 text-slate-800 dark:text-slate-100">
                <div
                    class="text-center mb-10 space-y-4 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                    <h2 class="font-display font-script text-4xl text-primary dark:text-white mb-2">Digital Envelope
                    </h2>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light max-w-md mx-auto">
                        Your blessing is the greatest gift for us. However, if you wish to send a gift, we provide a
                        digital
                        envelope for your convenience.
                    </p>
                    <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-[#d4af37] to-transparent mx-auto mt-4">
                    </div>
                </div>

                <div class="max-w-md mx-auto space-y-6">
                    <?php if (!empty($gifts)): ?>
                        <?php foreach ($gifts as $index => $gift): ?>
                            <div
                                class="bg-white/80 dark:bg-slate-800/60 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6 relative overflow-hidden group hover:scale-[1.02] transition-transform duration-300 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-<?= 200 + ($index * 100) ?>">
                                <div
                                    class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-[#d4af37]/20 to-transparent rounded-bl-full -mr-8 -mt-8">
                                </div>
                                <div class="flex items-center justify-between mb-6">
                                    <span
                                        class="font-bold text-primary dark:text-white"><?= htmlspecialchars($gift['bank'] ?? 'Bank') ?></span>
                                    <span class="material-icons text-[#d4af37]">credit_card</span>
                                </div>
                                <div class="space-y-1 mb-6 text-center">
                                    <p class="text-xs uppercase tracking-widest text-slate-500 dark:text-slate-400">Account
                                        Number
                                    </p>
                                    <p class="font-display text-2xl tracking-wider text-slate-800 dark:text-white font-semibold tabular-nums"
                                        id="acc-num-<?= $index ?>"><?= htmlspecialchars($gift['number'] ?? '') ?></p>
                                    <p class="text-sm text-slate-600 dark:text-slate-300 italic font-light">a.n.
                                        <?= htmlspecialchars($gift['owner'] ?? '') ?>
                                    </p>
                                </div>
                                <button
                                    class="w-full bg-slate-900 dark:bg-white text-white dark:text-slate-900 py-3 rounded-lg flex items-center justify-center space-x-2 text-sm font-medium hover:bg-slate-700 dark:hover:bg-slate-200 transition-colors"
                                    onclick="copyToClipboard('<?= htmlspecialchars($gift['number'] ?? '') ?>')">
                                    <span class="material-icons text-base">content_copy</span>
                                    <span>Copy Account Number</span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-gray-500 dark:text-gray-400 italic py-8">Informasi rekening belum
                            tersedia.</p>
                    <?php endif; ?>
                </div>

                <!-- Gift Delivery -->
                <!-- <div class="text-center mt-8 mb-4">
                    <h3 class="font-display font-serif text-xl font-semibold text-slate-800 dark:text-white mb-2">
                        Gift
                        Delivery</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-light mb-4">
                        Jl. Cendrawasih No. 45, Kebayoran Baru<br />
                        Jakarta Selatan, DKI Jakarta 12160
                    </p>
                    <button
                        class="inline-flex items-center text-[#d4af37] hover:text-yellow-600 text-sm font-semibold transition-colors">
                        <span class="material-icons text-base mr-1">map</span>
                        View on Map
                    </button>
                </div> -->
            </section>
            <?php endif; ?>

            <!-- RSVP & Guestbook Section -->
            <section class="py-16 px-6 bg-[#fdf2f8] dark:bg-[#111827] relative z-30 font-sans">
                <?php if (in_array('rsvp', $features)): ?>
                <div
                    class="text-center mb-10 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out">
                    <p class="text-xs tracking-[0.2em] uppercase text-gray-500 dark:text-gray-400 mb-2 font-serif">The
                        Wedding Of</p>
                    <h1 class="text-5xl font-script text-primary dark:text-accent mb-1"><?= $groom_nickname ?> &amp;
                        <?= $bride_nickname ?>
                    </h1>
                    <div class="w-16 h-0.5 bg-primary dark:bg-accent mx-auto rounded-full mt-4 mb-6"></div>
                    <h2 class="text-2xl font-serif text-gray-800 dark:text-white font-semibold">RSVP &amp; Wishes</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-2 italic">Please confirm your attendance and
                        leave a heartfelt wish.</p>
                </div>

                <div
                    class="max-w-md mx-auto mb-6 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-200">
                    <div class="flex gap-3">
                        <div
                            class="flex-1 bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span
                                class="text-3xl font-bold text-green-600 dark:text-green-400 font-serif"><?= $total_present ?></span>
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">Attending</span>
                        </div>
                        <div
                            class="flex-1 bg-white dark:bg-slate-800 rounded-xl p-4 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col items-center justify-center text-center">
                            <span
                                class="text-3xl font-bold text-red-500 dark:text-red-400 font-serif"><?= $total_absent ?></span>
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mt-1">Not
                                Attending</span>
                        </div>
                    </div>
                </div>

                <div
                    class="max-w-md mx-auto mb-8 scroll-element opacity-0 translate-y-10 transition-all duration-1000 ease-out delay-500">
                    <div
                        class="bg-white/90 dark:bg-slate-800/80 backdrop-blur-md rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6">
                        <form action="guest_handler.php" class="space-y-5" method="POST">
                            <input type="hidden" name="invitation_id" value="<?= $id ?>">
                            <input type="hidden" name="slug" value="<?= $slug ?>">
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2 ml-1"
                                    for="name">Your Name</label>
                                <div class="relative">
                                    <span
                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">person</span>
                                    </span>
                                    <input
                                        class="w-full pl-10 pr-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-600 focus:border-primary focus:ring-1 focus:ring-primary text-gray-900 dark:text-white placeholder-gray-400 text-sm transition-all"
                                        id="name" name="name" placeholder="Enter your full name" required=""
                                        type="text" />
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 ml-1">Will
                                    you attend?</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="relative cursor-pointer group">
                                        <input checked="" class="peer sr-only" name="status" type="radio"
                                            value="present" />
                                        <div
                                            class="w-full py-3 px-4 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:border-green-500 dark:peer-checked:border-green-500 transition-all flex items-center justify-center gap-2">
                                            <span
                                                class="material-icons text-green-600 dark:text-green-400 text-sm opacity-50 peer-checked:opacity-100 transition-opacity">check_circle</span>
                                            <span
                                                class="text-sm font-medium text-gray-600 dark:text-gray-300 peer-checked:text-green-700 dark:peer-checked:text-green-400">Yes,
                                                Attend</span>
                                        </div>
                                    </label>
                                    <label class="relative cursor-pointer group">
                                        <input class="peer sr-only" name="status" type="radio" value="absent" />
                                        <div
                                            class="w-full py-3 px-4 rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:border-red-500 dark:peer-checked:border-red-500 transition-all flex items-center justify-center gap-2">
                                            <span
                                                class="material-icons text-red-500 dark:text-red-400 text-sm opacity-50 peer-checked:opacity-100 transition-opacity">cancel</span>
                                            <span
                                                class="text-sm font-medium text-gray-600 dark:text-gray-300 peer-checked:text-red-600 dark:peer-checked:text-red-400">Sorry,
                                                Can't</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-2 ml-1"
                                    for="message">Your Wishes</label>
                                <div class="relative">
                                    <span class="absolute top-3 left-3 flex pointer-events-none text-gray-400">
                                        <span class="material-icons text-lg">edit</span>
                                    </span>
                                    <textarea
                                        class="w-full pl-10 pr-4 py-3 rounded-lg bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-600 focus:border-primary focus:ring-1 focus:ring-primary text-gray-900 dark:text-white placeholder-gray-400 text-sm transition-all resize-none"
                                        id="message" name="message" placeholder="Write a warm message for the couple..."
                                        rows="3"></textarea>
                                </div>
                            </div>
                            <button
                                class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 px-6 rounded-lg shadow-md shadow-primary/30 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-2 group"
                                type="submit">
                                <span>Kirim Pesan</span>
                                <span
                                    class="material-icons text-sm group-hover:translate-x-1 transition-transform">send</span>
                            </button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (in_array('wishes', $features)): ?>
                <div class="max-w-md mx-auto pb-10">
                    <div class="flex items-center justify-between mb-4 px-2">
                        <h3 class="text-lg font-serif font-semibold text-gray-800 dark:text-white">Recent Wishes</h3>
                        <span
                            class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-md">Scroll
                            to see more</span>
                    </div>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1 custom-scrollbar">
                        <?php if (!empty($wishes)): ?>
                            <?php foreach ($wishes as $wish): ?>
                                <div
                                    class="bg-white dark:bg-slate-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden <?= $wish['status'] !== 'present' ? 'opacity-80' : '' ?>">
                                    <div
                                        class="absolute top-0 left-0 w-1 h-full <?= $wish['status'] === 'present' ? 'bg-green-500' : 'bg-red-400' ?>">
                                    </div>
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-8 h-8 rounded-full <?= $wish['status'] === 'present' ? 'bg-secondary/30 dark:bg-secondary/20 text-primary' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' ?> flex items-center justify-center font-serif font-bold text-xs">
                                                <?= strtoupper(substr($wish['name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">
                                                    <?= htmlspecialchars($wish['name']) ?>
                                                </h4>
                                                <span
                                                    class="text-[10px] text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                                    <span
                                                        class="w-1.5 h-1.5 rounded-full <?= $wish['status'] === 'present' ? 'bg-green-500' : 'bg-red-400' ?>"></span>
                                                    <?= $wish['status'] === 'present' ? 'Attending' : 'Not Attending' ?> •
                                                    <?= date('d M Y', strtotime($wish['created_at'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <p
                                        class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed pl-2 border-l-2 border-gray-100 dark:border-gray-700 ml-2">
                                        "<?= htmlspecialchars($wish['message']) ?>"
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-gray-500 dark:text-gray-400 italic py-8">Belum ada ucapan.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </section>

            <!-- Terima Kasih Section -->
            <section class="py-16 px-6 relative z-30 font-sans text-center space-y-8 bg-[#fdf2f8] dark:bg-[#111827]">
                <div
                    class="space-y-6 fade-in bg-white/50 dark:bg-slate-800/50 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-white/20 dark:border-gray-700 max-w-md mx-auto">
                    <h2 class="text-2xl font-serif font-semibold text-primary dark:text-white">Terima Kasih</h2>
                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed italic text-sm md:text-base">
                        "Merupakan suatu kehormatan dan kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir
                        untuk memberikan doa restu kepada kami."
                    </p>
                    <div class="w-16 h-0.5 bg-[#d4af37] mx-auto opacity-50"></div>
                    <p class="text-gray-600 dark:text-gray-400 text-xs md:text-sm">
                        Kami yang berbahagia,<br />
                        <span class="font-serif font-bold text-lg text-primary dark:text-white mt-2 block">Keluarga
                            Besar Kedua Mempelai</span>
                    </p>
                </div>

                <div class="mt-8 fade-in max-w-md mx-auto">
                    <span class="material-icons text-4xl text-[#d4af37] opacity-80 mb-2">format_quote</span>
                    <p class="font-serif text-sm text-gray-600 dark:text-gray-300 italic px-4">
                        "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari
                        jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di
                        antaramu rasa kasih dan sayang."
                    </p>
                    <p class="mt-2 text-xs font-bold text-primary dark:text-white">(QS. Ar-Rum: 21)</p>
                </div>

                <footer class="pt-12 pb-4 fade-in opacity-60 text-xs text-gray-500 dark:text-gray-500">
                    <p>Made with ❤️ for <?= $groom_nickname ?> &amp; <?= $bride_nickname ?></p>
                    <p class="mt-1">© &copy; <?= date('Y') ?>
                        SyifazharStudio. All Rights Reserved.</p>
                </footer>
            </section>
        </div>


        <!-- ================= COVER VIEW (Cover 1 / Unlock Screen) ================= -->
        <div id="cover-view" class="absolute inset-0 z-40 transition-all duration-1000 ease-in-out transform">
            <!-- Background Image Section -->
            <div class="absolute inset-0 z-0 h-[70%] w-full">
                <img alt="Happy couple wedding portrait"
                    class="w-full h-full object-cover object-top animate-[scale-in_20s_linear_infinite]"
                    src="<?= $hero_image ?: 'https://images.unsplash.com/photo-1583939003579-730e3918a45a?q=80&w=1000' ?>" />
                <!-- Gradient Overlay -->
                <div
                    class="absolute inset-0 bg-gradient-to-t from-primary via-primary/60 to-transparent dark:from-slate-900 dark:via-slate-900/70 dark:to-transparent h-full">
                </div>
            </div>

            <!-- Decorative Elements (Width issue fixed: right-0) -->
            <div class="leaf-pattern top-[-10px] right-0 w-48 h-48 opacity-90 transform rotate-12 z-20 animate-float">
                <img alt="Floral decoration top right"
                    class="w-full h-full object-cover opacity-60 mix-blend-screen rounded-full"
                    src="templates/minimalis/assets/flower-1.svg"
                    style="mask-image: radial-gradient(circle, black 50%, transparent 80%); -webkit-mask-image: radial-gradient(circle, black 50%, transparent 80%);" />
            </div>

            <div class="leaf-pattern bottom-0 left-[-30px] w-64 h-64 opacity-80 z-20 animate-float delay-1000">
                <img alt="Floral decoration bottom left"
                    class="w-full h-full object-cover opacity-50 mix-blend-screen rounded-full"
                    src="templates/minimalis/assets/flower-2.svg"
                    style="mask-image: radial-gradient(circle at bottom left, black 50%, transparent 80%); -webkit-mask-image: radial-gradient(circle at bottom left, black 50%, transparent 80%);" />
            </div>

            <!-- Content Section (Cover 1 specific) -->
            <div class="relative z-30 flex flex-col justify-end h-full pb-12 px-6 text-center text-secondary flex-grow">

                <div class="animate-fade-in-up">
                    <p class="uppercase tracking-[0.2em] text-xs font-serif text-slate-300 mb-2">The Wedding Of</p>
                </div>

                <div class="animate-fade-in-up delay-100 mb-6 relative">
                    <h1 class="font-script text-6xl md:text-7xl leading-tight text-white drop-shadow-lg">
                        <?= $groom_nickname ?> <span
                            class="text-4xl px-1 font-serif align-middle text-accent">&amp;</span>
                        <?= $bride_nickname ?>
                    </h1>
                </div>

                <div class="animate-fade-in-up delay-200 mb-8">
                    <p
                        class="font-serif text-lg tracking-widest border-y border-white/20 py-2 inline-block px-4 text-accent">
                        <?= $event_date_short ?>
                    </p>
                </div>

                <!-- NOT Removed for Main View, Removed for Cover View as requested -->

                <!-- <div class="animate-fade-in-up delay-500 space-y-2 mb-8">
                <p class="text-sm font-light text-slate-300">Kepada Bapak/Ibu/Saudara/i</p>
                <div
                    class="bg-white/5 inline-block py-3 px-8 rounded-lg backdrop-blur-md border border-white/10 shadow-lg transform hover:scale-105 transition-transform duration-300">
                    <p class="text-xl font-semibold tracking-wide text-white"><?= $guest_name ?></p>
                </div>
            </div> -->

                <div class="animate-fade-in-up delay-700 pb-4">
                    <button id="open-invitation-btn"
                        class="group relative inline-flex items-center justify-center px-8 py-3 bg-secondary text-primary font-medium text-sm rounded-full shadow-lg hover:bg-white hover:shadow-accent/20 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                        <span class="material-icons text-lg mr-2 group-hover:animate-bounce">mail_outline</span>
                        Buka Undangan
                    </button>
                </div>

            </div>
        </div>

        <!-- Music Floating Button -->
        <?php if (!empty($music_url) && in_array('music', $features)): ?>
        <div class="absolute bottom-6 right-6 z-50">
            <button id="music-btn" onclick="toggleMusic()"
                class="w-10 h-10 rounded-full bg-primary/90 backdrop-blur-md border border-white/20 flex items-center justify-center text-white shadow-lg animate-spin-slow hover:bg-primary transition-colors">
                <span class="material-icons text-sm">music_note</span>
            </button>
        </div>
        <?php endif; ?>




    </div>

    <!-- Music Player (SoundCloud or HTML5 Audio) - MUST be before script -->
    <?php if (in_array('music', $features) && !empty($music_url)): ?>
    <?php if (strpos($music_url, 'soundcloud.com') !== false): ?>
        <iframe id="sc-player" width="100%" height="166" scrolling="no" frameborder="no" allow="autoplay"
            src="https://w.soundcloud.com/player/?url=<?= urlencode($music_url) ?>&color=%23ff5500&auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&show_teaser=false"
            style="opacity: 0; position: absolute; pointer-events: none; z-index: -1;">
        </iframe>
    <?php else: ?>
        <?php
        // Ensure music URL is absolute
        $audio_src = $music_url;
        if (!preg_match('/^https?:\/\//', $music_url)) {
            $audio_src = '/' . ltrim($music_url, '/');
        }
        ?>
        <audio id="audio-player" loop preload="auto">
            <source src="<?= htmlspecialchars($audio_src) ?>" type="audio/mpeg">
        </audio>
    <?php endif; ?>
    <?php endif; ?>

    <script>
        // Countdown Logic
        function updateCountdown() {
            const countdownContainers = document.querySelectorAll('.countdown-container');

            // Generate HTML for countdown
            const countdownHTML = `
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-serif font-bold w-12 h-12 flex items-center justify-center bg-white/10 rounded-lg backdrop-blur-sm border border-white/10 days">00</span>
                    <span class="text-[10px] uppercase tracking-wider mt-1">Days</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-serif font-bold w-12 h-12 flex items-center justify-center bg-white/10 rounded-lg backdrop-blur-sm border border-white/10 hours">00</span>
                    <span class="text-[10px] uppercase tracking-wider mt-1">Hours</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-serif font-bold w-12 h-12 flex items-center justify-center bg-white/10 rounded-lg backdrop-blur-sm border border-white/10 minutes">00</span>
                    <span class="text-[10px] uppercase tracking-wider mt-1">Mins</span>
                </div>
                <div class="flex flex-col items-center">
                    <span class="text-2xl font-serif font-bold w-12 h-12 flex items-center justify-center bg-white/10 rounded-lg backdrop-blur-sm border border-white/10 seconds">00</span>
                    <span class="text-[10px] uppercase tracking-wider mt-1">Secs</span>
                </div>
            `;

            // Inject into all containers
            countdownContainers.forEach(container => {
                container.innerHTML = countdownHTML;
            });

            // Target date from PHP
            let seconds = <?= $seconds ?>;
            let mins = <?= $minutes ?>;
            let hours = <?= $hours ?>;
            let days = <?= $days ?>;

            const updateDisplay = () => {
                const dayEls = document.querySelectorAll('.days');
                const hourEls = document.querySelectorAll('.hours');
                const minEls = document.querySelectorAll('.minutes');
                const secEls = document.querySelectorAll('.seconds');

                dayEls.forEach(el => el.textContent = String(days).padStart(2, '0'));
                hourEls.forEach(el => el.textContent = String(hours).padStart(2, '0'));
                minEls.forEach(el => el.textContent = String(mins).padStart(2, '0'));
                secEls.forEach(el => el.textContent = String(seconds).padStart(2, '0'));
            };

            updateDisplay();

            setInterval(() => {
                seconds--;
                if (seconds < 0) {
                    seconds = 59;
                    mins--;
                    if (mins < 0) {
                        mins = 59;
                        hours--;
                        if (hours < 0) {
                            hours = 23;
                            days--;
                        }
                    }
                }
                updateDisplay();
            }, 1000);
        }

        // Open Invitation Interaction
        document.addEventListener('DOMContentLoaded', () => {
            updateCountdown();

            const openBtn = document.getElementById('open-invitation-btn');
            const coverView = document.getElementById('cover-view');
            const mainView = document.getElementById('main-view');
            const mainContainer = document.getElementById('main-container');

            if (openBtn) {
                openBtn.addEventListener('click', () => {
                    // 1. Unhide Main View (it sits behind or same level)
                    mainView.classList.remove('hidden');

                    // 2. Animate Cover View Up and Out
                    // Fade up effect: Translate Y negative and Opacity 0
                    coverView.classList.add('-translate-y-full', 'opacity-0');

                    // 3. Fade In Main View (optional, if we want it to crossfade slightly or just reveal)
                    // If it's behind, revealing is enough, but fading in ensures smoothness
                    requestAnimationFrame(() => {
                        mainView.classList.remove('opacity-0');
                    });

                    // 4. After animation, hide cover completely and enable scrolling
                    setTimeout(() => {
                        coverView.classList.add('hidden');
                        // Enable scrolling within the main view if needed, 
                        // though main-view has overflow-y-auto so it should handle itself.
                    }, 1000); // 1000ms matches transition duration

                    // 5. Play Music
                    playMusic();
                });
            }
        });

        // Music Player Logic (Supports both SoundCloud and HTML5 Audio)
        var widget = null;
        var audioPlayer = null;
        var isPlaying = false;
        var shouldPlay = false; // Add flag to handle race condition
        const iframeElement = document.getElementById('sc-player');
        const audioElement = document.getElementById('audio-player');

        // Initialize Music Player
        if (iframeElement) {
            // Load SC Widget API
            const tag = document.createElement('script');
            tag.src = "https://w.soundcloud.com/player/api.js";
            document.body.appendChild(tag);

            tag.onload = () => {
                widget = SC.Widget(iframeElement);
                widget.bind(SC.Widget.Events.READY, () => {
                    console.log('SoundCloud Ready');
                    // Play if user already clicked open
                    if (shouldPlay) {
                        widget.play();
                        isPlaying = true;
                        updateMusicBtn();
                    }
                });
                widget.bind(SC.Widget.Events.FINISH, () => widget.play());
            };
        } else if (audioElement) {
            audioPlayer = audioElement;
            audioPlayer.volume = 1.0;
            audioElement.addEventListener('ended', () => {
                audioElement.currentTime = 0;
                audioElement.play();
            });
        }

        function playMusic() {
            console.log('playMusic called');
            console.log('widget:', widget);
            console.log('audioPlayer:', audioPlayer);
            shouldPlay = true;
            if (widget) {
                widget.getVolume((vol) => {
                    if (vol === 0) widget.setVolume(100);
                });
                widget.play();
                isPlaying = true;
                updateMusicBtn();
            } else if (audioPlayer) {
                console.log('Attempting to play audio...');
                console.log('Audio src:', audioPlayer.src || audioPlayer.querySelector('source')?.src);
                audioPlayer.play().then(() => {
                    console.log('Audio playing successfully');
                    isPlaying = true;
                    updateMusicBtn();
                }).catch(err => {
                    console.error('Audio play failed:', err);
                    showToast('Gagal memutar musik: ' + err.message, 'error');
                });
            } else {
                console.log('No audio player found!');
            }
        }

        function toggleMusic() {
            if (widget) {
                widget.toggle();
                isPlaying = !isPlaying;
                updateMusicBtn();
            } else if (audioPlayer) {
                if (isPlaying) {
                    audioPlayer.pause();
                } else {
                    audioPlayer.play();
                }
                isPlaying = !isPlaying;
                updateMusicBtn();
            }
        }

        function updateMusicBtn() {
            const btn = document.getElementById('music-btn');
            const icon = btn.querySelector('.material-icons');
            if (isPlaying) {
                btn.classList.add('animate-spin-slow');
                icon.textContent = 'music_note';
            } else {
                btn.classList.remove('animate-spin-slow');
                icon.textContent = 'music_off';
            }
        }

        // Scroll Animation Observer
        document.addEventListener('DOMContentLoaded', () => {
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.remove('opacity-0', 'translate-y-10');
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-element').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
    <style>
        .fade-in {
            animation: fadeIn 1.5s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
    </style>
    <!-- Toast Notification -->
    <div id="toast"
        class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[60] hidden transition-all duration-300 transform translate-y-10 opacity-0">
        <div
            class="bg-slate-800/90 backdrop-blur-md text-white px-6 py-3 rounded-full shadow-xl flex items-center space-x-3 border border-white/10">
            <span id="toast-icon" class="material-icons text-green-400">check_circle</span>
            <span id="toast-message" class="text-sm font-medium">Message sent successfully!</span>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toast Function
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            const msg = document.getElementById('toast-message');

            msg.textContent = message;
            if (type === 'success') {
                icon.textContent = 'check_circle';
                icon.className = 'material-icons text-green-400';
            } else {
                icon.textContent = 'error';
                icon.className = 'material-icons text-red-400';
            }

            toast.classList.remove('hidden');
            // Trigger reflow
            void toast.offsetWidth;
            toast.classList.remove('translate-y-10', 'opacity-0');

            setTimeout(() => {
                toast.classList.add('translate-y-10', 'opacity-0');
                setTimeout(() => {
                    toast.classList.add('hidden');
                }, 300);
            }, 3000);
        }

        // Copy to Clipboard
        function copyToClipboard(text) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                showToast("Nomor rekening berhasil disalin!");
            }).catch(err => {
                console.error('Failed to copy: ', err);
                // Fallback for older browsers or non-secure contexts
                const textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast("Nomor rekening berhasil disalin!");
                } catch (err) {
                    showToast("Gagal menyalin nomor rekening.", 'error');
                }
                document.body.removeChild(textArea);
            });
        }

        // AJAX RSVP Form
        document.addEventListener('DOMContentLoaded', () => {
            const rsvpForm = document.querySelector('form[action="guest_handler.php"]');
            if (rsvpForm) {
                rsvpForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const btn = rsvpForm.querySelector('button[type="submit"]');
                    const originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<span class="material-icons animate-spin text-sm">refresh</span> Sending...';

                    const formData = new FormData(this);
                    formData.append('ajax', '1');

                    fetch('guest_handler.php', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                showToast(data.message, 'success');
                                rsvpForm.reset();
                                // Optional: Reload wishes list
                                setTimeout(() => location.reload(), 2000);
                            } else {
                                showToast(data.message || 'Terjadi kesalahan.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Gagal mengirim pesan. Silakan coba lagi.', 'error');
                        })
                        .finally(() => {
                            btn.disabled = false;
                            btn.innerHTML = originalText;
                        });
                });
            }
        });
    </script>
    <!-- Gallery Modal Slider -->
    <div id="galleryModal"
        class="fixed inset-0 z-[100] hidden bg-black/95 backdrop-blur-md flex items-center justify-center transition-all duration-300 opacity-0">
        <!-- Close Button -->
        <button
            class="absolute top-6 right-6 text-white/80 hover:text-white z-[110] p-2 transition-colors transform hover:scale-110"
            onclick="closeGallery()">
            <span class="material-icons text-3xl drop-shadow-lg">close</span>
        </button>

        <!-- Navigation Buttons -->
        <button
            class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 text-white/70 hover:text-white p-4 z-[110] transition-all hover:scale-110 hover:bg-white/10 rounded-full"
            onclick="changeSlide(-1)">
            <span class="material-icons text-4xl md:text-5xl drop-shadow-lg">chevron_left</span>
        </button>
        <button
            class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 text-white/70 hover:text-white p-4 z-[110] transition-all hover:scale-110 hover:bg-white/10 rounded-full"
            onclick="changeSlide(1)">
            <span class="material-icons text-4xl md:text-5xl drop-shadow-lg">chevron_right</span>
        </button>

        <!-- Image Container -->
        <div class="relative max-w-6xl w-full h-[90vh] flex flex-col items-center justify-center p-4"
            onclick="event.stopPropagation()">
            <img id="galleryModalImage" src="" alt="Gallery Image"
                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl transform scale-95 transition-all duration-300" />

            <!-- Counter -->
            <div
                class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-black/40 text-white px-4 py-2 rounded-full text-sm font-medium backdrop-blur-sm border border-white/10 tracking-wider">
                <span id="galleryCounter">1 / 1</span>
            </div>
        </div>
    </div>

    <!-- Gallery Script -->
    <script>
        // Gallery Logic
        const galleryImages = <?= json_encode($images) ?>;
        let currentGalleryIndex = 0;
        const galleryModal = document.getElementById('galleryModal');
        const galleryImg = document.getElementById('galleryModalImage');
        const galleryCounter = document.getElementById('galleryCounter');

        function openGallery(index) {
            currentGalleryIndex = index;
            updateGalleryImage();

            galleryModal.classList.remove('hidden');
            // Trigger reflow
            void galleryModal.offsetWidth;
            galleryModal.classList.remove('opacity-0');
            setTimeout(() => {
                galleryImg.classList.remove('scale-95');
                galleryImg.classList.add('scale-100');
            }, 50);
            document.body.style.overflow = 'hidden';
        }

        function closeGallery() {
            galleryModal.classList.add('opacity-0');
            galleryImg.classList.remove('scale-100');
            galleryImg.classList.add('scale-95');

            setTimeout(() => {
                galleryModal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        function changeSlide(direction) {
            // Fade out
            galleryImg.style.opacity = '0.5';
            galleryImg.style.transform = 'scale(0.98)';

            setTimeout(() => {
                currentGalleryIndex = (currentGalleryIndex + direction + galleryImages.length) % galleryImages.length;
                updateGalleryImage();

                // Fade in
                galleryImg.style.opacity = '1';
                galleryImg.style.transform = 'scale(1)';
            }, 200);
        }

        function updateGalleryImage() {
            galleryImg.src = galleryImages[currentGalleryIndex];
            galleryCounter.textContent = `${currentGalleryIndex + 1} / ${galleryImages.length}`;
        }

        // Keyboard Navigation
        document.addEventListener('keydown', (e) => {
            if (galleryModal.classList.contains('hidden')) return;

            if (e.key === 'Escape') closeGallery();
            if (e.key === 'ArrowLeft') changeSlide(-1);
            if (e.key === 'ArrowRight') changeSlide(1);
        });

        // Swipe Support (Basic)
        let touchStartX = 0;
        let touchEndX = 0;

        galleryModal.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        });

        galleryModal.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            if (touchEndX < touchStartX - 50) changeSlide(1);
            if (touchEndX > touchStartX + 50) changeSlide(-1);
        }
    </script>
</body>

</html>