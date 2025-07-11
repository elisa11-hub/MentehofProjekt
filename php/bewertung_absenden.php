<?php
session_start();

if (!isset($_SESSION["nutzer_id"])) {
    echo "Nicht eingeloggt.";
    exit;
}

$mysqli = require __DIR__ . "/database.php";

$reitschueler_id = $_SESSION["nutzer_id"];
$reitlehrer_id = $_POST["reitlehrer_id"];
$termin_id = $_POST["termin_id"];
$sterne = (int)$_POST["sterne"];
$kommentar = $_POST["kommentar"];
$datum = date("Y-m-d");

// Prüfen, ob der Nutzer ein Schüler ist 
$sql = "SELECT idreitschueler, vorname, nachname 
        FROM reitschueler 
        WHERE nutzer_idnutzer = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $nutzer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Nur Reitschüler können Bewertungen abgeben.";
    exit;
}

$schueler = $result->fetch_assoc();
$reitschueler_id = $schueler['idreitschueler'];
$name = $schueler['vorname'] . " " . $schueler['nachname'];

// Prüfen, ob der Schüler den Kurs tatsächlich besucht hat
$sql = "SELECT 1 
        FROM reitschueler_termin 
        WHERE reitschueler_idreitschueler = ? AND termin_idtermin = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $reitschueler_id, $termin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Es können nur Kurse bewertet werden, die auch besucht worden sind.";
    exit;
}

// Prüfen, ob bereits eine Bewertung für diesen Kurs existiert
$sql = "SELECT 1 
        FROM bewertung 
        WHERE reitschueler_idreitschueler = ? AND termin_idtermin = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii", $reitschueler_id, $termin_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Dieser Kurs wurde bereits bewertet.";
    exit;
}

// Bewertung speichern
$datum = date("Y-m-d H:i:s"); // Aktuelles Datum und Uhrzeit
$sql = "INSERT INTO bewertung 
        (reitschueler_idreitschueler, reitlehrer_idreitlehrer, termin_idtermin, sterne, kommentar, datum) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiiisd", $reitschueler_id, $reitlehrer_id, $termin_id, $sterne, $kommentar, $datum);

if ($stmt->execute()) {
    echo "Bewertung erfolgreich abgegeben.";
} else {
    echo "Fehler beim Speichern: " . $stmt->error;
}
