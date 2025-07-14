<?php
session_start();
$mysqli = require __DIR__ . "/../database/database.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nicht eingeloggt"]);
    exit;
}

// 1. Hole die Reitlehrer-ID zur eingeloggten Nutzer-ID
$user_id = $_SESSION["user_id"];

$stmt = $mysqli->prepare("SELECT idreitlehrer FROM reitlehrer WHERE nutzer_idnutzer = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($reitlehrer_id);
$stmt->fetch();
$stmt->close();

if (!$reitlehrer_id) {
    echo json_encode([]);
    exit;
}

// 2. Hole alle Termine, bei denen dieser Reitlehrer eingetragen ist
$stmt = $mysqli->prepare("
    X
");
$stmt->bind_param("i", $reitlehrer_id);
$stmt->execute();

$result = $stmt->get_result();
$kurse = [];

while ($row = $result->fetch_assoc()) {
    $kurse[] = $row;
}

echo json_encode($kurse);
