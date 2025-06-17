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

$sql = "INSERT INTO bewertung (reitschueler_idreitschueler, reitlehrer_idreitlehrer, termin_idtermin, sterne, kommentar, datum)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("iiiiss", $reitschueler_id, $reitlehrer_id, $termin_id, $sterne, $kommentar, $datum);

if ($stmt->execute()) {
    echo "Bewertung erfolgreich abgegeben.";
} else {
    echo "Fehler beim Speichern: " . $stmt->error;
}
