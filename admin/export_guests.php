<?php
require_once '../config.php';

if (!isset($_GET['id'])) { die("ID not specified"); }
$id = $_GET['id'];

// Fetch Invitation Info for Filename
$stmtInv = $pdo->prepare("SELECT groom_nickname, bride_nickname FROM invitations WHERE id = ?");
$stmtInv->execute([$id]);
$invitation = $stmtInv->fetch();

if (!$invitation) { die("Invitation not found"); }

$filename = "RSVP_" . $invitation['groom_nickname'] . "_" . $invitation['bride_nickname'] . "_" . date('Y-m-d') . ".xls";

// Fetch Guests
$stmt = $pdo->prepare("SELECT name, status, message, created_at FROM guests WHERE invitation_id = ? ORDER BY created_at DESC");
$stmt->execute([$id]);
$guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Headers for Download
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// Output Data
echo "Nama\tStatus\tUcapan\tWaktu\n"; // Header Row

foreach ($guests as $guest) {
    $status = ($guest['status'] === 'present') ? 'Hadir' : 'Tidak Hadir';
    // Clean strings to prevent Excel formatting issues (tabs, newlines)
    $name = str_replace(["\t", "\n"], " ", $guest['name']);
    $msg = str_replace(["\t", "\n"], " ", $guest['message']);
    
    echo "$name\t$status\t$msg\t" . $guest['created_at'] . "\n";
}

exit;
?>
