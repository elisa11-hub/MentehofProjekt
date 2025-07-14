<?php
session_start();
if (!isset($_SESSION["nutzer_id"])) {
    http_response_code(403);
    echo "Nicht angemeldet";
    exit;
}

$nutzer_id = $_SESSION["nutzer_id"];
$mysqli = require __DIR__ . '/../database/database.php';

// Reitschueler-ID ermitteln
$stmt = $mysqli->prepare("SELECT idreitschueler FROM reitschueler WHERE nutzer_idnutzer = ?");
$stmt->bind_param("i", $nutzer_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo json_encode([]);
    exit;
}
$reitschueler_id = $row["idreitschueler"];

// Bewertungen laden
$sql = "
    X
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $reitschueler_id);
$stmt->execute();
$result = $stmt->get_result();

$bewertungen = [];
while ($row = $result->fetch_assoc()) {
    $bewertungen[] = $row;
}

header('Content-Type: application/json');
echo json_encode($bewertungen);
