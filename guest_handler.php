<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invitation_id = $_POST['invitation_id'] ?? null;
    $slug = $_POST['slug'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $status = $_POST['status'] ?? 'present';
    $message = trim($_POST['message'] ?? '');

    if ($invitation_id && $name) {
        try {
            $stmt = $pdo->prepare("INSERT INTO guests (invitation_id, name, status, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$invitation_id, $name, $status, $message]);

            if (isset($_POST['ajax'])) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'message' => 'Ucapan berhasil dikirim!']);
                exit;
            }

            // Set success message/cookie if needed
            // For now, straight redirect back to the section
            header("Location: invitation.php?slug=" . urlencode($slug) . "#rsvp");
            exit;
        } catch (PDOException $e) {
            if (isset($_POST['ajax'])) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode(['status' => 'error', 'message' => 'Error recording attendance: ' . $e->getMessage()]);
                exit;
            }
            die("Error recording attendance: " . $e->getMessage());
        }
    } else {
        die("Missing required fields.");
    }
} else {
    header("Location: index.php");
    exit;
}
?>