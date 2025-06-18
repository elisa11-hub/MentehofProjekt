<?php
header('Content-Type: application/json');
$input = json_decode(file_get_contents("php://input"), true);

$rolle = $input['rolle'];
$nutzerId = $input['nutzerId'];
$daten = $input['termin']; // Array mit kursname, bezeichnung, datum, uhrzeit, dauer, kosten, max_teilnehmerzahl, min_reitlevel

$mysqli = require __DIR__ . "/database.php";

// Lehrer-ID holen, wenn kein Admin
if ($rolle === "reitlehrer") {
    $stmt = $mysqli->prepare("SELECT idreitlehrer FROM reitlehrer WHERE nutzer_idnutzer = ?");
    $stmt->bind_param("i", $nutzerId);
    $stmt->execute();
    $stmt->bind_result($reitlehrerId);
    $stmt->fetch();
    $stmt->close();
} elseif ($rolle === "admin") {
    $reitlehrerId = $input['lehrerId']; // Admin kann Lehrer auswählen
} else {
    echo json_encode(["status" => "error", "msg" => "Unberechtigt"]);
    exit;
}

// Termin einfügen
$stmt = $mysqli->prepare("
    INSERT INTO termin (kursname, bezeichnung, datum, uhrzeit, dauer, kosten, max_teilnehmerzahl, min_reitlevel, reitlehrer_idreitlehrer)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param(
    "ssssisdii",
    $daten['kursname'],
    $daten['bezeichnung'],
    $daten['datum'],
    $daten['uhrzeit'],
    $daten['dauer'],
    $daten['kosten'],
    $daten['max_teilnehmerzahl'],
    $daten['min_reitlevel'],
    $reitlehrerId
);
$stmt->execute();
echo json_encode(["status" => "success", "msg" => "Termin erstellt"]);
$mysqli->close();
