<?php
/**
 * Invitation Loader - Dynamic Template System
 * 
 * This file serves as the main entry point for viewing invitations.
 * It loads the appropriate template based on the 'visual_style' column in the database.
 */

require 'config.php';

// Get Invitation Data by Slug
$slug = $_GET['slug'] ?? '';
if (!$slug) {
    // If no slug, try to get the first one for preview
    $stmt = $pdo->query("SELECT slug FROM invitations LIMIT 1");
    $slug = $stmt->fetchColumn();
    if (!$slug)
        die("Invitation not found.");
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

// Determine template to use
$visual_style = $invitation['visual_style'] ?? 'adat-jawa';

// Template mapping - maps visual_style to template folder
$templateMap = [
    'adat-jawa' => 'templates/adat-jawa/index.php',
    'minimalis' => 'templates/minimalis/index.php',
    'modern-emerald' => 'templates/modern-emerald/index.php',
];

// Get template path
$templatePath = $templateMap[$visual_style] ?? $templateMap['adat-jawa'];

// Check if template file exists
if (!file_exists($templatePath)) {
    // Fallback to default template if selected one doesn't exist
    $templatePath = 'templates/adat-jawa/index.php';

    // If still doesn't exist, use inline template
    if (!file_exists($templatePath)) {
        // Include the original hardcoded template inline
        include 'templates/adat-jawa-fallback.php';
        exit;
    }
}

// Include the selected template
include $templatePath;
