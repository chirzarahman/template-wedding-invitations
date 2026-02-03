<?php
require_once '../config.php';
session_start();

// Validasi admin session jika diperlukan
// if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit; }

$logFile = '../logs/app.log';
$logs = [];

if (file_exists($logFile)) {
    $rawLogs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $logs = array_reverse($rawLogs); // Show newest first
}

// Handle Clear Logs
if (isset($_POST['clear_logs'])) {
    file_put_contents($logFile, '');
    $_SESSION['success'] = "Logs cleared successfully!";
    header("Location: logs.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        .font-mono { font-family: 'Fira Code', monospace; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #1f2937; }
        ::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    
    <div class="max-w-7xl mx-auto p-6">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="index.php" class="bg-gray-800 p-2 rounded-lg hover:bg-gray-700 transition group" title="Back to Dashboard">
                    <span class="material-symbols-outlined text-gray-400 group-hover:text-white transition">arrow_back</span>
                </a>
                <div>
                    <h1 class="text-3xl font-bold flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-500 text-4xl">terminal</span>
                        System Logs
                    </h1>
                    <p class="text-gray-400 text-sm mt-1">Monitor application activity and errors.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="location.reload()" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium shadow flex items-center gap-2 transition">
                    <span class="material-symbols-outlined text-sm">refresh</span> Refresh
                </button>
                <?php if(!empty($logs)): ?>
                <form method="POST" onsubmit="return confirm('Class logs? This cannot be undone.');">
                    <button type="submit" name="clear_logs" class="bg-red-500/20 hover:bg-red-500 text-red-500 hover:text-white px-4 py-2 rounded-lg font-medium shadow flex items-center gap-2 transition border border-red-500/30 hover:border-transparent">
                        <span class="material-symbols-outlined text-sm">delete</span> Clear Logs
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="bg-green-500/10 text-green-400 p-4 rounded-lg mb-6 border border-green-500/20 font-medium flex items-center gap-3 animate-fade-in-down">
                 <span class="material-symbols-outlined">check_circle</span>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <div class="bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 shadow-xl">
            <!-- Toolbar -->
            <div class="bg-gray-900/50 p-4 border-b border-gray-700 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-400">
                    <span class="material-symbols-outlined text-lg">description</span>
                    <span>File: /logs/app.log</span>
                    <?php if(file_exists($logFile)): ?>
                    <span class="bg-gray-700 text-xs px-2 py-0.5 rounded-full text-gray-300"><?= round(filesize($logFile) / 1024, 2) ?> KB</span>
                    <?php endif; ?>
                </div>
                <!-- Filter placeholder -->
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">search</span>
                    <input type="text" placeholder="Search logs..." class="bg-gray-900 border border-gray-700 rounded-full py-1.5 pl-9 pr-4 text-sm text-gray-300 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 w-64 transition">
                </div>
            </div>

            <!-- Log Viewer -->
            <div class="p-0 overflow-x-auto">
                <?php if (empty($logs)): ?>
                    <div class="flex flex-col items-center justify-center py-20 text-gray-500">
                        <span class="material-symbols-outlined text-6xl mb-4 text-gray-600">content_paste_off</span>
                        <p class="text-lg font-medium">No logs found</p>
                        <p class="text-sm">Activity will appear here when recorded.</p>
                    </div>
                <?php else: ?>
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-900/50 text-gray-400 text-xs uppercase tracking-wider">
                            <tr>
                                <th class="p-4 font-medium border-b border-gray-700 w-48">Timestamp</th>
                                <th class="p-4 font-medium border-b border-gray-700 w-24">Level</th>
                                <th class="p-4 font-medium border-b border-gray-700">Message</th>
                            </tr>
                        </thead>
                        <tbody class="font-mono text-sm divide-y divide-gray-700/50">
                            <?php foreach ($logs as $i => $line): 
                                // Colorize based on level (Simple parsing)
                                $class = 'text-gray-300';
                                $bgClass = 'hover:bg-gray-700/30';
                                if (strpos($line, 'ERROR') !== false) { $class = 'text-red-400'; $bgClass = 'bg-red-500/5 hover:bg-red-500/10'; }
                                elseif (strpos($line, 'WARNING') !== false) { $class = 'text-yellow-400'; }
                                elseif (strpos($line, 'SUCCESS') !== false) { $class = 'text-green-400'; }
                            ?>
                            <tr class="<?= $bgClass ?> transition-colors">
                                <td class="p-3 text-gray-500 whitespace-nowrap align-top">
                                    <?= substr($line, 0, 19) // Assume timestamp at start ?>
                                </td>
                                <td class="p-3 align-top">
                                    <?php if(strpos($line, 'ERROR') !== false): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100/10 text-red-500">ERROR</span>
                                    <?php elseif(strpos($line, 'WARNING') !== false): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100/10 text-yellow-500">WARN</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100/10 text-gray-400">INFO</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-3 <?= $class ?> break-all">
                                    <?= htmlspecialchars(substr($line, 20)) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            
            <!-- Footer -->
             <div class="bg-gray-900/50 p-3 border-t border-gray-700 text-xs text-gray-500 text-center">
                Showing last <?= count($logs) ?> lines
            </div>
        </div>
    </div>

    <!-- JS for Simple Search -->
    <script>
        document.querySelector('input[type="text"]').addEventListener('keyup', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
