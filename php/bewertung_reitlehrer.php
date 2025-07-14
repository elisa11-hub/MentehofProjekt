<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["error" => "Nicht eingeloggt"]);
    exit;
}

$nutzer_id = $_SESSION["user_id"];

$mysqli = require __DIR__ . "/../database/database.php";

// Reitlehrer-ID 
$sql = "SELECT idreitlehrer FROM reitlehrer WHERE nutzer_idnutzer = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $nutzer_id);
$stmt->execute();
$result = $stmt->get_result();
$reitlehrer = $result->fetch_assoc();

if (!$reitlehrer) {
    echo json_encode([]);
    exit;
}

$reitlehrer_id = $reitlehrer["idreitlehrer"];

// Bewertungen 
$sql = "
    X
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $reitlehrer_id);
$stmt->execute();
$result = $stmt->get_result();

$bewertungen = [];
while ($row = $result->fetch_assoc()) {
    $bewertungen[] = $row;
}

header("Content-Type: application/json");
echo json_encode($bewertungen);
